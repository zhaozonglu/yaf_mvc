<?php
/**
 * Mysql PDO操作类
 */
class Db_Pdo{

    /**
     * 数据库配置信息
     * @var array
     */
    private $config;

    /**
     * pdo连接串
     * @var string
     */
    private $dsn;

    /**
     * PDO实例
     * @var PDO
     */
    private $pdo;

    /**
     * 连接的库名
     * @var string
     */
    private $dbname;

    /**
     * 主从
     * @var int 1主库 0从库
     */
    private $master;

    /**
     * 事务层
     * @var integer
     */
    private $transactionLevel = 0;

    private static $instance = [];

    private function __construct($dbname, $master){
        $this->master = $master;
        $this->dbname = $dbname;
        $this->initConfig($master);
        $this->initConnectDsn();
        $this->initPdo();
    }

    private function __clone(){}

    public static function getInstance($dbname, $master = 1){
        $key = $dbname.'_'.$master;
        if(isset(self::$instance[$key]) && !empty(self::$instance[$key])){
            return self::$instance[$key];
        }
        self::$instance[$key] = new self($dbname, $master);
        return self::$instance[$key];
    }

    /**
     * 初始化配置信息
     * @param  String  $dbname  数据库配置
     * @param  integer $master 主库
     */
    private function initConfig($master=0){
        $db_config = Yaf_Registry::get('config')->database->toArray();
        $this->config = $master ? $db_config['master'] : $db_config['slave'];
    }

    /**
     * 初始化连接dsn
     * @param  integer $master 主库1 从库0
     */
    private function initConnectDsn(){
        $this->dsn = "mysql:host={$this->config['host']};port={$this->config['port']};dbname={$this->dbname}";
        if(isset($this->config['charset']) && !empty($this->config['charset'])){
            $this->dsn .= ";charset={$this->config['charset']}";
        }
    }

    /**
     * 初始化PDO实例
     */
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