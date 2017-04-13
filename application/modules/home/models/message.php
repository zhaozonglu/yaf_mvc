<?php
class MessageModel extends BaseModel{
    public function testmsg(){
        $dao = load_class('base', 'dao');
        $blog = load_class('blog','dao/index');
        var_dump($dao, $blog);
    }

    public function testmongo(){
        $dao = load_class('blog', 'dao/index');
        $row = $dao->find([], ['name'=>1, '_id'=>0]);
        foreach ($row as $key => $value) {
            var_dump($value);
        }
    }
}