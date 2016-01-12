<?php
if(!defined("__SP__")) exit();

$host = "localhost";
$db = "gz";
$user = "root";
$pass = "";

//try
//{
    $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$pass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
	PDO::ATTR_ERRMODE=> PDO::ERRMODE_EXCEPTION));
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
/*}
catch (Exception $e)
{
    echo $e->getMessage();
}*/
?>
