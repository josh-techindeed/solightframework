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

class Template {
	
	/**
	 * 模板赋值
	 */
	public static function render($template,$data = array())
	{
		if(!empty($data)){
			extract($data);
		}
		require dirname(dirname(CUR_PATH))."/views/".$template;
	}
	
	/**
	 * 加载文件
	 */
	public static function load_file($mod,$file)
	{	
		require_once dirname(dirname(CUR_PATH)).DS.$mod.DS.$file.".php";
	}	
	
}
