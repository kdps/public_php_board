<?php
if(!defined("__SP__")) exit();

	/**
	 * init String/int/Array
	 **/

//boolean
$ajax_mode = FALSE;
$err_mode = FALSE;
$is_admin = FALSE;

//array
$thumb_list = array();
$file_list = array();
$notice_list = array();
$board_list = array();
$get_arr = array();
$post_arr = array();

//null
$title = NULL;
$get_lastid = NULL;
$target_template = NULL;

	/**
	 * Setting Parameter
	 **/

$accept_ext = '.jpg,.png,.gif,.mp3,.mp4,.avi,.zip,.7z,.rar';
$captcha_use = FALSE;
$md5_salt = '!sd^qz!!szps';

	/**
	 * Set Site Parameter
	 **/

$base_root = basename(dirname($_SERVER['PHP_SELF']));
if($base_root){
	$sub_directory = '/'.basename(dirname($_SERVER['PHP_SELF']));
}else{
	$sub_directory = '';
}

if($sub_directory===NULL){
	$absolute_directory = $_SERVER['DOCUMENT_ROOT'];
}else{
	$absolute_directory = $_SERVER['DOCUMENT_ROOT'].$sub_directory;
}

$index_title = "larkspur";
$def_title = "larkspur";
$list_count = (int)"20";
$page_count = (int)"10";

$sql_front_sort = FALSE;

	/**
	 * Check Ajax Parameter
	 **/

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
	$ajax_mode = TRUE;
}

	/**
	 * POST, GET Parameter
	 **/

if($_SERVER['REQUEST_METHOD']){
	//set get parameter
	if($_SERVER['REQUEST_METHOD']==="GET"){
		foreach($_GET as $key=>$val){
			$get_arr[$key] = $val;
		}
	}
	
	//set post parameter
	if($_SERVER['REQUEST_METHOD']==="POST"){
		foreach($_POST as $key=>$val){
			$post_arr[$key] = $val;
		}
	}
}

	/**
	 * Menu
	 **/

if(empty($menu)){
	$_GLOBAL["menu"] = array(
		array(
			"title"=>"Front Page",
			"link"=>"http://".$_SERVER['SERVER_NAME'].'/index.php',
			"submenu"=>array(
								array(
								"link"=>"http://".$_SERVER['SERVER_NAME'].'/index.php',
								"title"=>"larkspur"
								)
							)
		),
		array(
			"title"=>"게시판",
			"link"=>"",
			"submenu"=>array(
								array(
								"link"=>$oFunc->getUrl('bd','index'),
								"title"=>"게시판1",
								),
								array(
								"link"=>$oFunc->getUrl('bd','board'),
								"title"=>"게시판2"
								)
							)
		)
	);
}

//write mode
if(isset($get_arr['serial'])){
	$mode = 'update';
}else{
	$mode = 'insert';
}

//widget setting
if(!isset($get_arr['bd']) && !isset($get_arr['act'])){
	$board_skin = 'mainpage';
}

//get captcha auth status
if(isset($post_arr['captcha_code']) && isset($_SESSION['captcha_code']))
{
	if($post_arr['captcha_code'] === $_SESSION['captcha_code']){
		$captcha_auth = TRUE;
	}else{
		$captcha_auth = FALSE;
	}
}

?>
