<?php
session_start();
if($_SERVER['HTTP_HOST']=='localhost')
{
	$angRoute = "http://localhost/reworx";
}
else if($_SERVER['HTTP_HOST']=='demo.onsisdev.info')
{
	$angRoute = "http://demo.onsisdev.info/tracking";
}
define("ANGULAR_ROUTE",$angRoute);
define("EMAIL_ALLREADY","An account for the specified email address already exists. Try another email address.");
define("SUCCESSFULLY_REGISTER","Thank You. We will get back to you Soon");
define("INVALID_LOGIN_DETAIL","The Email and password you entered don't match");
?>
