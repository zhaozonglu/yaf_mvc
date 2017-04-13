<?php
/**
 * 公共函数库
 */

/**
 * 加载本地models类库
 * @param String $class 类名
 * @param String $directory 路径名【dao/子文件夹  service/子文件夹 为空时为当前模块下models】
 * @param Mix    $param 参数
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

/**
 * 加载第三方类库扩展
 * @param string $ext 入口文件路径
 */
if(!function_exists('load_ext')){
    function load_ext($ext){
        $extlib = Yaf_Registry::get('config')->application->extlib;
        $in_file = APP_PATH .DIRECTORY_SEPARATOR.$extlib.DIRECTORY_SEPARATOR.$ext;
        return Yaf_Loader::import($in_file);
    }
}

