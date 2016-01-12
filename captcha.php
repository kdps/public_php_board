<?php
	session_start();
	include("./phptextClass.php");	
	$phptextObj = new phptextClass();
	$phptextObj->phpcaptcha('#F01D1D','#fff',40,19,34,1);	
 ?>
