<?php
class ErrorController extends Yaf_Controller_Abstract{
    
    public function errorAction($exception){
        echo '<br>Throw Exception'.time().':['.$exception->getMessage().']<br>';
    }
}