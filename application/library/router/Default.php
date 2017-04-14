<?php
/**
 * 自定义路由类
 */
class Router_Default implements Yaf_Route_Interface{
    public function assemble(array $info, array $query = NULL) {
        return true;
    }

    public function route($request) {
        foreach(Yaf_Registry::get('config')->module_host as $module => $host) {
            if (in_array(HTTP_HOST, explode(',', $host))) {
                $uri = explode('/', $request->uri);
                $request->module = $module;
                $request->controller = empty($uri[1]) ? 'index' : $uri[1];
                $request->action = empty($uri[2]) ? 'index' : $uri[2];
                return true;
            }
        }
        return false;
    }
}