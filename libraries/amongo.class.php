<?php
/**
 * TODO:mongodb的单例
 * 
 * @package dump.baidu.com
 * @author leimengcheng<leimengcheng@baidu.com>
 * @date 2014-07-23
 */
if(!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
class Amongo {
	
	/**
	 * var $instance
	 */
	private static $__instance;
	
	/**
	 * private construct
	 */
	private function __construct(){}
	
	/**
	 * get instance
	 */
	public static function getInstance()
	{
		if(is_null(self::$__instance)){
			$config = include_once(dirname(dirname(__FILE__)).DS."config".DS.'mongodb.php');
			$connection = "mongodb://".$config['host'].":".$config['port'];
			try{
				$mongo = new Mongo($connection);
			}catch(Exception $e){}
			self::$__instance = $mongo->$config['db_name'];
		}
		return self::$__instance;
	}
	
	/** 
     * Description:私有化克隆函数，防止外界克隆对象 
     */  
    private function __clone(){}  
    
} 
