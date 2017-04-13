<?php
class Bootstrap extends Yaf_Bootstrap_Abstract{
    public function _initConfig(){
        $env = Yaf_Application::app()->environ();
        $conf = Yaf_Application::app()->getConfig('product');
        echo "<pre>";
        var_dump($conf);
        echo "<------------><br>";
        $app_config = new Yaf_Config_Ini(BASE_PATH.'/conf/application.ini', $env);
        Yaf_Registry::set('config', $app_config);
    }

    public function _initPlugin(Yaf_Dispatcher $dispatcher){
        $user = new AutoloadPlugin();
        $dispatcher->registerPlugin($user);
    }

    public function _initRouter(Yaf_Dispatcher $dispatcher){
        $router = $dispatcher->getRouter();
        $config = Yaf_Registry::get('config');
        //域名映射模块
        foreach ($config->host_module as $module => $host) {
            $arr_host = explode(',', $host);
            if(in_array(HTTP_HOST, $arr_host)){
                $router->addRoute($module, new Util_Router($module));
                break;
            }else{
                continue;
            }
        }
    }
}