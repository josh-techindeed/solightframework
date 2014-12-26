<?php
/*
 * Created on 2013-3-28
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
/**
 * xml数据结果输出
 */
function returnXml($status, $msg) 
{
    header("Content-Type: text/xml;charset=utf-8");
    echo '<?xml version="1.0" encoding="utf-8"?><result>'
    . '<retVal>' . $status . '</retVal>'
    . '<msg>' . $msg . '</msg>';
    echo '</result>';
}

/**
 * 获得用户的IP地址
 */
function getClientIp()
{
	$uip = '';
	if(getenv('HTTP_CLIENTIP') && strcasecmp(getenv('HTTP_CLIENTIP'), 'unknown')) {
		$uip = getenv('HTTP_CLIENTIP');
	} else if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$uip = getenv('HTTP_CLIENT_IP');
	} else if(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$uip = getenv('HTTP_X_FORWARDED_FOR');
		strpos(',', $uip) && list($uip) = explode(',', $uip);
	} else if(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$uip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$uip = $_SERVER['REMOTE_ADDR'];
	}
	return $uip;
}

/**
 * 设置输出格式控制
 * 
 * @param mixed $data 需要输出的数据
 * @param string $type 需要输出的格式类型
 */
function output($data,$type)
{
	$isDebug = isset($_GET['debug']) ? true : false;
	if($isDebug){
		print_r($data);
	}else{
		echo empty($type) || isset($type) ? $type($data) : "param type is require";
	}
}

/**
 * 解密客户端加密信息
 * 
 * @param string $encode 需要解密的数据
 */
function de_encrypt($encode)
{
	$src = array();
	$before_src = str_split($encode);
	for($i=0,$length=count($before_src);$i<$length;$i=$i+2){
		$item = $before_src[$i];
		$item .= $before_src[$i+1];
		array_push($src,hexdec($item));
	}
	$length = count($src);
	
	$encrypt_size = 0x100;
    $global_key = 0x6b;
	
	$ok = 0;
    $dest = array();
    if($length == -1)
		$length = strlen($src);
    if($length == 0)
    	return array($ok, $dest);

    $cb_key1 = array();
    $cb_key2 = array();
	
	for($i=0;$i<$encrypt_size;$i++){
		array_push($cb_key1,$i);
		array_push($cb_key2,$global_key);
	}

	$index = 0;
	for($i=0;$i<($encrypt_size + $length);$i++){
		$index2 = $i & 0xff;
		$index += $cb_key1[$index2];
		$index += $cb_key2[$index2];
		$index &= 0xff;
		
		$tmp = $cb_key1[$index];
		$cb_key1[$index] = $cb_key1[$index2];
		$cb_key1[$index2] = $tmp;
	}
	
	$index = 0;
	for($i=0;$i<$length;$i++){
		$m = ($i + 1) % $encrypt_size;
		$index += $cb_key1[$m];
		$index &= 0xff;
		$index2 = $cb_key1[$m];
		$index2 += $cb_key1[$index];
		$index2 &= 0xff;
		
		$tmp = $cb_key1[$m];
		$cb_key1[$m] = $cb_key1[$index];
		$cb_key1[$index] = $tmp;
		
		array_push($dest,$src[$i] ^ $cb_key1[$index2]);
	}
	
	$strr = "";
	for($i=0,$len=count($dest);$i<$len;$i++){
		$strr .= chr($dest[$i]);
	}
	$arrStrr = explode(" ",$strr);
	$code = end($arrStrr);
	return $code;
}

/**
 * 系统邮件发送函数
 *
 * @param string $to    接收邮件者邮箱
 * @param string $name  接收邮件者名称
 * @param string $subject 邮件主题 
 * @param string $body    邮件内容
 * @param string $attachment 附件列表
 * @return boolean 
 * @example: 
		$to = array(
			0 => array("email" => "mengch.lei@foxmail.com", "name" => "leimengcheng"),
		);
		send_mail($to, array(), 'test','test body', array());
 */
function send_mail($arrToUsers, $arrCcUsers = array(), $subject = '', $body = '', $attachment = array()){
	require_once("./libs/PHPMailer/PHPMailerAutoload.php");
	
    $config 		  = include_once("./config/email.php");
    $mail             = new PHPMailer; 			//PHPMailer对象
    $mail->CharSet    = 'UTF-8'; 				//设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->isSMTP(); 							// 设定使用SMTP服务
    $mail->SMTPDebug  = false;                  // 关闭SMTP调试功能
												// 1 = errors and messages
												// 2 = messages only
    $mail->SMTPAuth   = true;                   // 启用 SMTP 验证功能
    //$mail->SMTPSecure = 'tls';                // 使用安全协议
    $mail->Host       = $config['SMTP_HOST'];  	// SMTP 服务器
    $mail->Port       = $config['SMTP_PORT'];  	// SMTP服务器的端口号
    $mail->Username   = $config['SMTP_USER'];  	// SMTP服务器用户名
    $mail->Password   = $config['SMTP_PASS'];  	// SMTP服务器密码
    
	$mail->From 	  = $config['FROM_EMAIL'];
	$mail->FromName   = $config['FROM_NAME'];
	
	foreach($arrToUsers as $value){
        $mail->AddAddress($value['email'], $value['name']);
    }
	
	foreach($arrCcUsers as $value){
        $mail->addCC($value['email'], $value['name']);
    }
	
    $mail->Subject    = $subject;
	$mail->Body 	  = $body;

    $mail->isHTML(true);
    
    if(is_array($attachment)){ 					// 添加附件
        foreach ($attachment as $file){
            is_file($file) && $mail->AddAttachment($file);
        }
    }
    return $mail->Send() ? true : $mail->ErrorInfo;
}

/**
 * 创建压缩文件
 */
function create_zipfile($filename,$filepath){
	$ZIP_PATH = "/home/www/www.kanbootstrap.com/demo/";	
	shell_exec("cd $ZIP_PATH; zip -r $filename.zip $filepath; mv $filename.zip ../downloads");
}
?>
