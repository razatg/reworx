<?php
header('Access-Control-Allow-Origin: www.appypie.com|sa.appypie.com|snappy.appypie.com|arsnappy.appypie.com|desnappy.appypie.com|www.saralshopping.com|essnappy.appypie.com|angularml.pbodev.info|cdncloudfront.com');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: x-requested-with, Content-Type, origin, authorization, accept, client-security-token');
define("PUBLIC_PATH",'..');
define("CLOUD_CLIENT_ID", "319939676776-ki07h3te8oo240dmrnnnlc3249p309qd.apps.googleusercontent.com");
define("CLOUD_CLIENT_SECRET", "u3oj2I7o8mhbJi0RyLyy3YQu");
define("SMS_ACCOUNT_ID", "ACffedb4bc33391cd6b798addf92ee8702");
define("SMS_AUTH_TOKEN", "0e37536f0904bcbe19c84e7925be8151");
define("SMS_FORM_NUMBER", "+12075174085");
define("ENABLE_MONGO", "1");
$apiKeys=array("AIzaSyA7KFb_YPxkehANZAlddsfcNH_E16fjs_M");
define("GOOGLE_PLACES_API_KEY",  $apiKeys[array_rand($apiKeys,1)]);
//date_default_timezone_set('Europe/London');
date_default_timezone_set('UTC');
set_error_handler('exceptions_error_handler',E_ERROR);
function exceptions_error_handler($severity, $message, $filename, $lineno) 
{
	if (error_reporting() == 0) {
		return;
	}
	if (error_reporting() & $severity) {
		$myFile = "../tmp/requesterrorlog.txt";
		$fh = @fopen($myFile, 'a');
		if($fh) {
			$input = @file_get_contents('php://input');
			if(empty($input))
				$input = @serialize($_POST);
			$stringData = $input.' in file '.$filename.' on line '.$lineno.' -----> This request hit on '.date('d-m-Y H:i:s')."\n";
			@fwrite($fh, $stringData);		
		}
		@fclose($fh);
		@chmod($myFile, 0777);
		throw new ErrorException($message, 0, $severity, $filename, $lineno);
	}
}
define("SITE_URL", "https://www.appypie.com/enappypie/appystore");
define("CDN_URI", "http://snappy.appypie.com");

define("REDIS_HOST","");
define("REDIS_PORT", "");
define("REDIS_EXPIRE", "");

// Get pages data from mongodb
function getPagesData($appId, $fieldArr = array())
{
	$pagesData = array();
	if($appId!='')
	{
		include_once('mongo/mongobase.php');
	    $mongoObj = new MongoBase();
		$mongoObj->collection('apppagedata');
		$pagesData = $mongoObj->findOne(array('appId'=>$appId), $fieldArr);
		if(!empty($pagesData))
		{
			return $pagesData;	
		}
	}
}
?>
