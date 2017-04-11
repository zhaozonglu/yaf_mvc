<?php
class IndexController extends BaseController{
    public function indexAction(){
        $cur = Yaf_Dispatcher::getInstance()->getRouter()->getCurrentRoute();
        echo "curs----";
        var_dump($cur);
        exit;
    }

    public function showmsgAction(){
        $this->display('index');
    }
    public function demoAction(){
        $msg = load_class('message');
        $dao = load_class('base', 'dao');
        $blog = load_class('blog','dao/index');
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