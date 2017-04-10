<?php
/**
 * Model类加载方法
 */
if(!function_exists('load_class')){
    function load_class($class, $directory='', $param = NULL){
        static $_classes = array();
        if (isset($_classes[$class])){
            return $_classes[$class];
        }
        if(empty($directory)){
            $filename = DR_MODULE.$class.'.php';
            $className = ucfirst($class).'Model';
            $flag = Yaf_Loader::import($filename);
        }else{
            $filename = APP_PATH.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.$directory.DIRECTORY_SEPARATOR.$class.'.php';
            $className = ucfirst($directory)."_".ucfirst($class).'Model';
            $flag = true;
        }
        if(file_exists($filename) && $flag){
            $_classes[$class] = isset($param)
                ? new $className($param)
                : new $className();
            return $_classes[$class];
        }
        return false;
    }
}