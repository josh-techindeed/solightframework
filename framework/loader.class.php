<?php
/**
 * class loader
 */
if(!defined('DS')) {
        define('DS', DIRECTORY_SEPARATOR);
}
if(!defined('CUR_PATH')){
	define('CUR_PATH',__FILE__);
}

class Loader {
	
	/**
	 * 加载lib
	 */	
	public static function lib($instance)
	{
		self::load_file('libs',$instance);
		$newClassName = ucfirst($instance);
		return new $newClassName;
	}
		
	/**
	 * 加载model
	 */
	public static function model($instance)
	{
		self::load_file('kernel','medoo');
		self::load_file('models',$instance);
		$newClassName = ucfirst($instance);
		return new $newClassName;
	}
	
	/**
	 * 加载文件
	 */
	public static function load_file($mod,$file)
	{
		require_once dirname(dirname(CUR_PATH)).DS.$mod.DS.$file.".php";
	}	
	
}
