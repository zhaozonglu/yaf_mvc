<?php
/**
 * mongodb新扩展
 */
abstract class Db_Mongodb{

    private $configType = 'default';

    /**
     * 配置
     * @var array
     */
    private $config;

    /**
     * 库名
     * @var String
     */
    protected $dbname;

    /**
     * 集合名
     * @var string
     */
    protected $collection;

    /**
     * 命名空间
     * @var String
     */
    protected $namespace;

    private $cmd = [];

    /**
     * MongoDB driver
     * @var MongoDB\Driver\Manager
     */
    protected static $_manager = [];

    protected function getNameSpace(){
        return $this->namespace;
    }

    protected function getDbName(){
        return $this->dbname;
    }

    protected function getCollectionName(){
        return $this->collection;
    }

    public function setConfigType($type){
        $this->$configType = $type;
    }

    /**
     * 初始化配置
     */
    private function initConfig(){
        $config = Yaf_Registry::get('config')->mongodb->toArray();
        if(isset($config[$this->configType]) && !empty($config[$this->configType])){
            $this->config = $config[$this->configType];
        }else{
            $this->config = $config['default'];
        }
    }

    /**
     * 初始化连接
     */
    private function initLinkUri(){
        $uri = 'mongodb://'. $this->config['host'] . '/' . $this->dbname;
        if(isset($this->config['rp']) && !empty($this->config['rp'])){
            $uri .= '?readPreference='.$config['rp'];
        }
        return $uri;
    }

    /**
     * 获取mongodb连接管理
     */
    protected function getManager(){
        $this->initConfig();
        $key = $this->configType;
        if(self::$_manager[$key]){
        }else{
            $link_uri = $this->initLinkUri();
            self::$_manager[$key] = new MongoDB\Driver\Manager($link_uri);
        }
        return self::$_manager[$key];
    }

    protected function initBulkWrite($data, $type = 'insert', $where = []){
        if(!in_array($type, ['delete','update','insert','count', 'batchInsert'])){
            throw new Exception("Bad BulkWrite Type");
        }else{
            $bulkWrite = new MongoDB\Driver\BulkWrite();
            switch ($type) {
                case 'update':
                    $bulkWrite->update($data, $where);
                    break;
                case 'batchInsert':
                    foreach ($data as $item) {
                        $bulkWrite->insert($item);
                    }
                    break;
                default:
                    $bulkWrite->{$type}($data);
                    break;
            }
            return $bulkWrite;
        }
    }

    public function insert($data){
        if($bulk = $this->initBulkWrite($data, 'insert') && $manager = $this->getManager()){
            return $manager->executeBulkWrite($this->namespace, $bulk);
        }else{
            return false;
        }
    }

    public function batchInsert($data){
        if($bulk = $this->initBulkWrite($data, 'batchInsert') && $manager = $this->getManager()){
            return $manager->executeBulkWrite($this->namespace, $bulk);
        }else{
            return false;
        }
    }

    public function update($data, $where){
        if($bulk = $this->initBulkWrite($data, 'update', $where) && $manager = $this->getManager()){
            return $manager->executeBulkWrite($this->namespace, $bulk);
        }else{
            return false;
        }
    }

    public function delete($data){
        if($bulk = $this->initBulkWrite($data, 'delete') && $manager = $this->getManager()){
            return $manager->executeBulkWrite($this->namespace, $bulk);
        }else{
            return false;
        }
    }

    public function count($data){
        if($bulk = $this->initBulkWrite($data, 'count') && $manager = $this->getManager()){
            return $manager->executeBulkWrite($this->namespace, $bulk);
        }else{
            return false;
        } 
    }

    public function find($match=[], $field=[]){
        $options = ['projection'=>$field];
        $query = new MongoDB\Driver\Query($match, $options);
        if($query && $manager = $this->getManager()){
            return $manager->executeQuery($this->namespace, $query); 
        }
        return false;
    }

    /**
     * mongodb 聚合
     * @param  array $pipeline 聚合管道
     */
    public function aggregate($pipeline){
        if(!empty($pipeline)){
            $cmd = [
                'aggregate'=> $this->collection,
                'pipeline' => $pipeline,
                'cursor'   => new stdClass();
            ];
            return $this->command($cmd);
        }
        return false;
    }


    /**
     * 直接执行command
     * @param  array $cmd command数组
     */
    public function command($cmd){
        if(!empty($cmd) && $manager = $this->getManager()){
            $command = new MongoDB\Driver\Command($cmd);
            return $manager->executeCommand($this->dbname, $command);
        }
        return false;
    }

}