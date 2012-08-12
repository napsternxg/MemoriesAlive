<?php
include_once "facebook-php-sdk/src/facebook.php";
	$app_id = '471564966195723';
	$application_secret = '186ec6af8b5212008ca7188601731ebc';
	$facebook = new Facebook(array(
  'appId'  => $app_id,
  'secret' => $application_secret,
));
	$db_host="localhost";
	$db_username="root";
	$db_pass="";
	$con = mysql_connect($db_host, $db_username, $db_pass);
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
	mysql_select_db("memories_alive", $con);
?>