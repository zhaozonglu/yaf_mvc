<?php
/**
 * rabbitmq消息队列工具类
 */
class Mq_Rabbit{
    
    private $config;
    static private $_instance;
    static private $_conn;

    private function __construct($config){
        $this->config = $config;
    }

    public function getInstance(){
        if(empty(self::$_instance)){
            $config = Yaf_Registry::get('config');
            $rabbit_config = $config->rabbit->toArray();
            self::$_instance = new self($rabbit_config);
        }
        return self::$_instance;
    }
}