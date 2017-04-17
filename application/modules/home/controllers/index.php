<?php
class IndexController extends BaseController{

    public function testAction(){
        $conn = Mq_Rabbit::getInstance();
        $ex = $conn->initExchange('exchange', AMQP_DURABLE, AMQP_EX_TYPE_DIRECT);
        $queue = $conn->initQueue('queue2', AMQP_DURABLE, 'exchange', 'key1');
        for ($i=0; $i < 100; $i++) { 
            // $ex->publish("hellozonglu".$i, 'key1');
        }
        for ($i=0; $i < 100; $i++) { 
            $msg = $queue->get('queue2',true)->getBody();
            echo $msg."<br>";
        }
    }
}