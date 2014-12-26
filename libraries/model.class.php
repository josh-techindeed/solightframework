<?php
/**
 * TODO: mysql数据库基本模型
 * 
 * @package solightframework
 * @author leimengcheng<mengch.lei@foxmail.com>
 * @date 2014-10-023
 */
if(!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
require_once(dirname(__FILE__).DS."medoo.class.php");
class Model {

	/**
	 * var database
	 */
	public $database;

	/**
	 * var table
	 */
	public $table; 
	 
	/**
	 * construct
	 */
	public function __construct($table = "")
	{
		$this->database = Database::getInstance();
		$this->table = $table;
	}
	
	/**
	 * query
	 */
	public function query($sql,$query = false)
	{
		if($query == false){
			return $this->database->query($sql)->fetchAll(PDO::FETCH_ASSOC);	
		}
		return $this->database->query($sql);
	}
	
	/**
	 * fetch one record
	 */
	public function fetchOne($where = "")
	{
		$sql = "SELECT * FROM ".$this->table;
		if(!empty($where)){
			$sql .= " where ".$where;
		}
		$sql .= " LIMIT 1";
		$result = $this->database->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		if(!empty($result) && isset($result[0])){
			return $result[0];
		}
		return false;		
	}
	
	/**
	 * fetch all records
	 */
	public function fetchAll($where = "",$order = "")
	{
		$sql = "SELECT * FROM ".$this->table;
		if(!empty($where)){
			$sql .= " where ".$where;
		}
		if(!empty($order)){
			$sql .= " ".$order;
		}		
		return $this->database->query($sql)->fetchAll(PDO::FETCH_ASSOC);		
	}
	
	/**
	 * insert data
	 */
	public function insert($data)
	{
		return $this->database->insert($this->table, $data);
	}
} 
