<?php
class IndexController extends BaseController{

    public function tmongoAction(){
        $msg = load_class('message');
        $msg->testmongo();
    }

    public function tredisAction(){
        $dbredis = Db_Redis::getInstance('default', 1, 0);
        $dbredis->set('name','zonglu000');
        $nm = $dbredis->get('name');
        var_dump($nm);
        $redis = $dbredis->getRedis();
        var_dump($redis);
    }

    public function indexAction(){
        $cur = Yaf_Dispatcher::getInstance()->getRouter()->getCurrentRoute();
        echo "curs----";
        var_dump($cur);
        exit;
    }

    public function democurlAction(){
        $res = Util_Curl::request('www.baidu.com','get');
        var_dump($res);
    }

    public function showmsgAction(){
        $this->display('index');
    }
    public function demoAction(){
        $msg = load_class('message');
        echo "<pre>";
        $msg->testmsg();
        var_dump($msg);
    }
    public function productAction($age){
        echo "<br>product<br>";
        var_dump($age);
        $cur = Yaf_Dispatcher::getInstance()->getRouter()->getCurrentRoute();
        echo "curs----";
        var_dump($cur);
        exit;
    }
    public function pageshowAction($name,$id){
        echo "<br>page<br>";
        var_dump($name,$id);
        $cur = Yaf_Dispatcher::getInstance()->getRouter()->getCurrentRoute();
        echo "curs----";
        var_dump($cur);
        exit;
    }
}