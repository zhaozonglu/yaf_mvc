<?php
/**
 * rabbitmq消息队列工具类
 */
class Mq_Rabbit{

    private $config;
    private $connect;
    
    private $channel;
    private $exchange;
    private $queue = [];

    private $name;
    private $flag;
    private $type;

    static private $_instance;

    private function __construct(){
        $this->config = Yaf_Registry::get('config')->rabbit->config->toArray();
        $this->initRabbit();
        $this->initChannel();
    }

    public static function getInstance(){
        if(empty(self::$_instance)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function initExchange($exname, $exflag, $extype){
        $this->exchange = new AMQPExchange($this->channel);
        $this->exchange->setName($exname);
        $this->exchange->setType($extype);
        $this->exchange->setFlags($exflag);
        return $this;
    }

    public function initQueue($qname, $qflag, $exname, $bindkey){
        if(isset($this->queue[$qname]) && !empty($this->queue[$qname])){
            return $this->queue[$qname];
        }
        $queue = new AMQPQueue($this->channel);
        $queue->setName($qname);
        $queue->setFlags($qflag);
        $queue->bind($exname, $bindkey);
        $this->queue[$qname] = $queue;
        return $this;
    }

    /**
     * 推送消息到消息队列
     * @param  String $msg       消息内容
     * @param  String $routerKey 路由key
     */
    public function publish($msg, $routerKey){
        try{
            return $this->exchange->publish($msg, $routerKey);
        }catch(Exception $e){
            throw $e;  
        }
    }

    /**
     * 从消息队列中取一个值
     * @param  String  $qname   队列名
     * @param  boolean $autoack 是否发送ack
     */
    public function get($qname, $autoack = false){
        if(isset($this->queue[$qname]) && !empty($this->queue[$qname])){
            if($autoack){
                return $this->queue[$qname]->get(AMQP_AUTOACK);
            }else{
                return $this->queue[$qname]->get();
            }
        }
        return false;
    }

    /**
     * 初始化Rabbit连接
     */
    private function initRabbit(){
        try{
            $conn = new AMQPConnection($this->config);
            if($conn->connect()){
                $this->connect = $conn;
            }
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    private function initChannel(){
        $this->channel = new AMQPChannel($this->connect);
    }
}