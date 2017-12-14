<?php
ini_set('display_errors', 0);
session_start();
$angRoute = "http://refhireable.com";
if($_SERVER['HTTP_HOST']=='localhost')
{
	$angRoute = "http://localhost/reworx";
}
else if($_SERVER['HTTP_HOST']=='demo.onsisdev.info')
{
	$angRoute = "http://demo.onsisdev.info/tracking";
}
define("ANGULAR_ROUTE",$angRoute);
define("SENDGRID_URL","");
define("SENDGRID_KEY","");
define("SENDGRID_PASS","");
define("ANGULAR_ROUTE",$angRoute);
define("EMAIL_ALLREADY","An account for the specified email address already exists. Try another email address.");
define("SUCCESSFULLY_REGISTER","Thank You. We will get back to you Soon");
define("INVALID_LOGIN_DETAIL","The Email and password you entered don't match");
function connect()
{
	if($_SERVER['HTTP_HOST']=='localhost')
	{
		$m = new MongoClient("mongodb://192.168.3.2:27017");
	    $db = $m->RPO_DataBase;
	}
	else if($_SERVER['HTTP_HOST']=='demo.onsisdev.info')
	{
		$m = new MongoClient("mongodb://dheeraj:dheeraj@ds117485.mlab.com:17485/pradip");
		$db = $m->pradip;
	}
	else if($_SERVER['HTTP_HOST']=='refhireable.com')
	{
		$m = new MongoClient("mongodb://dheeraj:dheeraj@ds117485.mlab.com:17485/pradip");
		$db = $m->pradip;
	}
	return $db;
}
?>
