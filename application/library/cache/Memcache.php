<?php
class Cache_Memcache{

    private $app;
    private $cache;
    private $key_prefix = '';
    private $time_threshold = 2591999;   // memcached 最大缓存时间 30天 (减了1s)
    private static $_instance = [];

    private function __construct($app){
        $this->app = $app;
        $this->initMemcache();
    }

    public static function getInstance($app = 'default'){
        $key = $app;
        if(!isset(self::$_instance[$key]) && empty(self::$_instance[$key])){
            self::$_instance[$key] = new self($app);
        }
        return self::$_instance[$key];
    }

    public function setPrefix($prefix){
        $this->key_prefix = $prefix;
    }

    private function initMemcache(){
        $config = Yaf_Registy::get('config')->memcache->{$this->app};
        if(!empty($config['host']) && isset($config['host'])){
            $cache = new Memcached();
            $hosts = explode(',', $config['host']);
            if(empty($hosts)){
                throw new Exception("memcache配置错误");
            }
            foreach ($hosts as $value) {
                list($host, $port) = explode(":", $value);
                $cache->addServer($host, $port);
            }
            $this->cache = $cache;
        }
    }

    public function set($name, $value, $ttl=3600){
        empty($name) && return false;
        $ttl = ($ttl >= $this->time_threshold) ? $this->time_threshold : $ttl;
        $mck = $this->key_prefix.$name;
        return $this->cache->set($mck, $value, $ttl);
    }

    public function add($name, $value, $ttl){
        empty($name) && return false;
        $ttl = ($ttl >= $this->time_threshold) ? $this->time_threshold : $ttl;
        $mck = $this->key_prefix.$name;
        return $this->cache->add($mck, $value, $ttl);
    }

    public function replace($name, $value, $ttl){
        empty($name) && return false;
        $ttl = ($ttl >= $this->time_threshold) ? $this->time_threshold : $ttl;
        $mck = $this->key_prefix.$name;
        return $this->cache->replace($mck, $value, $ttl);
    }

    public function get($name){
        empty($name) && return;
        $mck = $this->key_prefix.$name;
        return $this->cache->get($mck);
    }

    public function close(){
        $this->cache->close();
        self::$_instance[$this->app] = null;
    }

}