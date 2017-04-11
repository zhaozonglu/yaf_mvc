<?php
/**
 * Model类加载方法
 */
if(!function_exists('load_class')){
    function load_class($class, $directory='', $param = NULL){
        static $_classes = array();
        $key = empty($directory) ? $class : $directory.'_'.$class;
        if (isset($_classes[$key])){
            return $_classes[$key];
        }
        if(empty($directory)){
            $filename = DR_MODULE.$class.'.php';
            $className = ucfirst($class).'Model';
            $flag = Yaf_Loader::import($filename);
        }else{
            $filename = APP_PATH.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.$directory.DIRECTORY_SEPARATOR.$class.'.php';
            //多级目录
            $arr_cname = explode(DIRECTORY_SEPARATOR, $directory);
            $className = '';
            foreach ($arr_cname as $value) {
                $className .= ucfirst($value).'_';
            }
            $className .= ucfirst($class).'Model';
            $flag = true;
        }
        if(file_exists($filename) && $flag){
            $_classes[$key] = isset($param)
                ? new $className($param)
                : new $className();
            return $_classes[$key];
        }
        return false;
    }
}