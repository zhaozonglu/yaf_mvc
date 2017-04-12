<?php
class MessageModel extends BaseModel{
    public function testmsg(){
        $dao = load_class('base', 'dao');
        $blog = load_class('blog','dao/index');
        var_dump($dao, $blog);
    }
}