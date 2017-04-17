<?php
abstract class Db_Tablebase{

    const PARAM_PREFIX = ':qp'; //占位

    protected $dbname;
    private $db;
    private $pdo;
    private $isMaster;

    private $sql;

    public function __construct($isMaster){
        $this->isMaster = $isMaster;
        $this->connect();
    }

    abstract public function setTableName(){}
    abstract public function columnNames(){}

    public function prepareData(&$para) {
        $collumns = $this->columnNames();
        foreach ($para as $name => $val) {
            if (!in_array($name, $collumns)) {
                unset($para[$name]);
            }
        }
        return $para;
    }

    public function insert($data, $upset = false){

    }

    public function bindValue($name, $value, $dataType = null){
        
    }

    public function bindValues($value){

    }

    private function connect(){
        $this->db = Db_Pdo::getInstance($this->dbname, $this->isMaster);
        $this->pdo = $this->db->getPdo();
    }


}