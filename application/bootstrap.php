<?php
class Bootstrap extends Yaf_Bootstrap_Abstract{
    public function _initConfig(){
        $env = Yaf_Application::app()->environ();
        $app_config = new Yaf_Config_Ini(BASE_PATH.'/conf/application.ini', $env);
        Yaf_Registry::set('config', $app_config);
    }

    public function _initPlugin(Yaf_Dispatcher $dispatcher){
        $user = new AutoloadPlugin();
        $dispatcher->registerPlugin($user);
    }

    public function _initRouter(Yaf_Dispatcher $dispatcher){
        $dispatcher->getRouter()->addRoute('default', new Router_Default());
    }

    public function __initView(Yaf_Dispatcher $dispatcher){
        //关闭自动渲染
        $dispatcher->autoRender(false);
        //返回响应对象
        $dispatcher->returnResponse(true);
    }
}