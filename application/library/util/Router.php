<?php
/**
 * 自定义路由类
 */
class Util_Router implements Yaf_Route_Interface{
    private $module;
    public function __construct($module){
        $this->module = $module;
    }
    public function route($req){
        $req->module = $this->module;
    }
    public function assemble(array $info, array $query=array()){
        return true;
    }
}