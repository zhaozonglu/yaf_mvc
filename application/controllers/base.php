<?php
/**
 * 控制器基类
 */
class BaseController extends Yaf_Controller_Abstract{

    protected function init(){
        Yaf_Dispatcher::getInstance()->disableView();
        Yaf_Session::getInstance()->start();
    }
}