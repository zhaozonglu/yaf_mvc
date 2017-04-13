<?php
class AutoloadPlugin extends Yaf_Plugin_Abstract{
    private $config;
    private $fileload;

    public function routerStartup(Yaf_Request_Abstract $req, Yaf_Response_Abstract $res){
        $this->config = Yaf_Registry::get('config');
        $this->fileload = Yaf_Loader::getInstance();
    }

    public function routerShutdown(Yaf_Request_Abstract $req, Yaf_Response_Abstract $res){
        //自动加载方法
        if(isset($this->config->application->autofunction) && !empty($this->config->application->autofunction)){
            $autofunction = explode(',',$this->config->application->autofunction);
            foreach ($autofunction as $v) {
                $file = APP_PATH.DIRECTORY_SEPARATOR.'function'.DIRECTORY_SEPARATOR.$v.'.php';
                $this->fileload->import($file); 
            }
        }

        $module_dir = BASE_PATH.DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.strtolower($req->module).DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR;

        !defined('DR_MODULE') && define('DR_MODULE', $module_dir);
    }
}