<?php
class Bootstrap extends Yaf_Bootstrap_Abstract{
    public function _initConfig(){
        $config = Yaf_Application::app()->getConfig();
        $test_config = new Yaf_Config_Ini(APP_PATH.'/conf/application.ini','test');
        Yaf_Registry::set('config',$test_config);
    }

    public function _initPlugin(Yaf_Dispatcher $dispatcher){
        // $user = new UserPlugin();
        // $dispatcher->registerPlugin($user);
    }

    public function _initRouter(Yaf_Dispatcher $dispatcher){
        $http_host = $_SERVER['HTTP_HOST'];
        $router = $dispatcher->getRouter();
        // $routes_config = Yaf_Registry::get('config');
        // $router->addConfig($routes_config->routes);
        // var_dump($router);
        // $route = new Yaf_Route_Simple("m", "c", "a");
        // $router->addRoute("myroute", $route);
        // $roters = new Yaf_Route_Rewrite("/product/:id",['controller'=>'index','action'=>'product','module'=>'home']);
        // $roter1 = new Yaf_Route_Rewrite("/page/:id",['controller'=>'index','action'=>'page','module'=>'home']);
        switch ($http_host) {
            case 'localhost':
                $router->addRoute('home', new HomeRouter());
                break;
            default:
                $router->addRoute('home', new HomeRouter());
                break;
        }
        
        // var_dump($router);
        // $router->addRoute('page',$roter1);
    }
}

class HomeRouter implements Yaf_Route_Interface{
    public function route($req){
        $req->module = 'home';
        // $req->controller = 'index';
        // $req->action = 'showmsg';
    }
    public function assemble(array $info, array $query=array()){
    }
}