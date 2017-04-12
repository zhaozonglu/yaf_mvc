<?php
class Bootstrap extends Yaf_Bootstrap_Abstract{
    public function _initConfig(){
        
        $env = Yaf_Application::app()->environ();
        $app_config = new Yaf_Config_Ini(BASE_PATH.'/conf/application.ini', $env);
        Yaf_Registry::set('config', $app_config);
    }

    public function _initPlugin(Yaf_Dispatcher $dispatcher){
        // $user = new AutoloadPlugin();
        // $dispatcher->registerPlugin($user);
    }

    public function _initRouter(Yaf_Dispatcher $dispatcher){
        $router = $dispatcher->getRouter();
        $config = Yaf_Registry::get('config');
        //域名映射模块
        foreach ($config->host_module as $module => $host) {
            $arr_host = explode(',', $host);
            if(in_array(HTTP_HOST, $arr_host)){
                $router->addRoute($module, new MyRouter($module));
                break;
            }else{
                continue;
            }
        }
    }
}

class MyRouter implements Yaf_Route_Interface{
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