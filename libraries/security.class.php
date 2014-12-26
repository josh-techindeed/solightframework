<?php
/**
 * 对服务器请求参数进行安全验证
 * 
 * @author leimengcheng<mengch.lei@foxmail.com>
 * @date 2014-07-20
 */

class Security {

	public static $SRCRET_KEY = "yinyuan_2014_lei";

	/**
	 * 对url参数的合法性进行验证
	 * 
	 * @param array $sign_data
	 */
	public static function getToken($sign_data)
	{
		if(empty($sign_data)){
			return "";
		}
		
		ksort($sign_data);
		$sortString = '';
		foreach($sign_data as $key => $val){
			$sortString .= $key.$val;
		}
		return md5($sortString.self::$SRCRET_KEY);
	}

}

?>
