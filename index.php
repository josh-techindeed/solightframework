<?php
/**
 * solightframework index.php
 *
 * @author:leimengcheng<mengch.lei@foxmail.com>
 * @date:2014-12-26
 */
require_once dirname(__FILE__)."/framework/template.class.php";

$title = "hello world, solightframework!"; 

//assign
$data['title'] = $title;

//进行页面赋值
Template::render("index-view.php",$data); 
?>