<?php
class UserPlugin extends Yaf_Plugin_Abstract{
    public function routerStartup(Yaf_Request_Abstract $req, Yaf_Response_Abstract $res){
        echo "--router plugin-- start";
        var_dump($req);//routerstartup时$request是空的
        echo "<br><br>";
    }

    public function routerShutdown(Yaf_Request_Abstract $req, Yaf_Response_Abstract $res){
        echo "---router plugin -- shutdown";
        var_dump($req);
    }

    public function dispatchLoopStartup(Yaf_Request_Abstract $req, Yaf_Response_Abstract $res){
        echo "<br>---dispatcher---<br>";
        var_dump($req);
    }

    public function preDispatch(Yaf_Request_Abstract $req, Yaf_Response_Abstract $res){
        echo "<br><br>--pre dispatcher";
        var_dump($req);
    }

    public function postDispatch(Yaf_Request_Abstract $req, Yaf_Response_Abstract $res){
        echo "<br><br><br>--postdispatcher";
        var_dump($req);
    }

    public function dispatchLoopShutdown(Yaf_Request_Abstract $req, Yaf_Response_Abstract $res){
        echo "<br> dispatch shutdown--<br>";
        echo "++++=";
    }
}