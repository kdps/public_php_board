<?php

//date
date_default_timezone_set('Asia/Seoul');

//header
header("Content-Type: text/html; charset=utf-8");


//date
date_default_timezone_set('Asia/Seoul');

//header
header("Content-Type: text/html; charset=utf-8");

//include
$target_include = dirname(__FILE__).'/../lib/htmlfurifier/HTMLPurifier.auto.php';
if(file_exists($target_include)){
	include($target_include);
	$purifier = new HTMLPurifier();
}

$target_include = dirname(__FILE__).'/../conf/db.php';
if(file_exists($target_include)){
	include($target_include);
}

$target_include = dirname(__FILE__).'/../module/module/model.php';
if(file_exists($target_include)){
	include($target_include);
	$oModel = new Model($pdo);
}

$target_include = dirname(__FILE__).'/../module/module/controller.php';
if(file_exists($target_include)){
	include($target_include);
	$oController = new Controller($pdo);
}

$target_include = dirname(__FILE__).'/../module/func/func.php';
if(file_exists($target_include)){
	include($target_include);
	$oFunc = new Func();
	$oFunc->zip_output();
}

$target_include = dirname(__FILE__).'/../conf/site.php';
if(file_exists($target_include)){
	include($target_include);
}

?>
