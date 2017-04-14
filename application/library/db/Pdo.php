<?php
/**
 * Mysql PDO操作类
 */
class Db_Pdo{

    private $config;
    private $dsn;
    private $pdo;
    private $dbname;

    private $dbnum;
    private $master;
    private $transactionLevel = 0;

    private static $instance = [];

    private function __construct($dbnum, $master){
        $this->master = $master;
        $this->dbnum = $dbnum;
        $this->initConfig();
        $this->initConnectDsn($master);
        $this->initPdo();
    }

    private function __clone(){}

    public static function getInstance($dbnum, $master = 1){
        $key = $dbnum.'_'.$master;
        if(isset(self::$instance[$key]) && !empty(self::$instance[$key])){
            return self::$instance[$key];
        }
        self::$instance[$key] = new self($dbnum, $master);
        return self::$instance[$key];
    }

    public function 

    private function initConfig($dbnum,$master=0){
        $db_config = Yaf_Registry::get('config')->database->$dbnum->toArray();
        $this->config = $master ? $db_config['master'] : $db_config['slave'];
    }

    private function initConnectDsn($master){
        $this->dsn = "mysql:host={$this->config['host']};port={$this->config['port']};dbname={$this->dbname}";
    }

    private function initPdo(){
        try{
            $options = array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
            $this->pdo = new PDO($this->dsn, $this->config['username'], $this->config['password'], $option);
        }catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
        
    }

    public function getPdo(){
        return $this->pdo;
    }

    public function close(){
        if($this->pdo !== null){
            $this->pdo = null;
        }
    }

    public function isTransaction(){
        return $this->transactionLevel >0 && $this->master && $this->pdo && $this->pdo->inTransaction();
    }

    public function beginTransaction(){
        if ($this->transactionLevel === 0 && $this->master) {
            $this->transactionLevel = 1;
            return $this->pdo->beginTransaction();
        }
        $this->transactionLevel++;
        return true;
    }

    public function commit(){
        if(!$this->isTransaction()){
            return false;
        }
        $this->transactionLevel--;
        if ($this->transactionLevel === 0 && $this->master) {
            return $this->pdo->commit();
        }
        return true;
    }

    public function rollBack() {
        if (!$this->isTransaction()) {
            return false;
        }
        $this->transactionLevel--;
        if ($this->transactionLevel === 0 && $this->master) {
            return $this->pdo->rollBack();
        }
        return true;
    }

    public function __destruct() {
        $this->close();
    }
}