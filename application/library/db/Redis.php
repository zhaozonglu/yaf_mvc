<?php
class Db_Redis{
    protected static $_instance = [];

    private $config;

    private $app;
    private $master;
    private $serializer;
    private $pconnect;
    private $timeout;

    private $redis;

    private function __construct($app, $master, $serializer, $pconnect, $timeout){

        $this->app = $app;
        $this->master = $master;
        $this->serializer = $serializer;
        $this->pconnect = $pconnect ? 'pconnect' : 'connect';
        $this->timeout = $timeout;

        $this->initConfig();
        $this->initRedis();
    }

    public static function getInstance($app = 'default', $master = 1, $serializer=0, $pconnect=0, $timeout = 0){
        $redis_instance_key = $app . '_' . intval($pconnect) . '_' . intval($master);
        if(isset(self::$_instance[$redis_instance_key]) && !empty(self::$_instance[$redis_instance_key])){
            if('+PONG' == self::$_instance[$redis_instance_key]->ping()){
                return self::$_instance[$redis_instance_key];
            }
        }
        self::$_instance[$redis_instance_key] = new self($app, $master, $serializer, $pconnect, $timeout);
        return self::$_instance[$redis_instance_key];
    }

    /**
     * 初始化Config
     */
    private function initConfig(){
        $config = Yaf_Registry::get('config')->redis->toArray();
        if(isset($config[$this->app]) && !empty($config[$this->app])){
            $this->config = $this->master ? $config[$this->app]['master'] : $config[$this->app]['salver'];
        }else{
            throw new Exception('wrong app');
        }
    }

    /**
     * 初始化Redis实例
     */
    private function initRedis(){
        try{
            $this->redis = new Redis();
            $this->redis->{$this->pconnect}($this->config['host'], $this->config['port'], $this->timeout);
            if($this->serializer){
                $this->redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
            }
            if(isset($this->config['db']) && empty($this->config['db'])){
                $db = intval($this->config['db']) >=0 && intval($this->config['db']) <=15 ? intval($this->config['db']) : 0;
            }else{
                $db = 0;
            }
            if(isset($this->config['passwd']) && !empty($this->config['passwd'])){
                $auth = $this->redis->auth($this->config['passwd']);
                if(!$auth){
                    throw new Exception("wrong redis password");
                }
            }
            $this->redis->select($db);
        }catch(Exception $e){
            throw new Exception($this->redis->getLastError(), $e->getCode(), $e);
        }
    }

    public function getRedis(){
        return $this->redis;
    }

    public function close(){
        return $this->redis->close();
    }

    public function __destruct(){
        $this->close();
    }

    public function __call($method, $args){
        try {
            $ref = new ReflectionMethod('Redis', $method);
            return $ref->invokeArgs($this->redis, $args);
        } catch (RedisException $e) {
            throw new Exception($redis->getLastError(), $e->getCode(), $e);
        }
    }
}