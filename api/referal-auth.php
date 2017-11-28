<?php 
include_once('../config-ini.php');
$returnArr = array('status'=>'failure');
$email = isset($_GET['email'])?$_GET['email']:"";
$UID = isset($_GET['UID'])?$_GET['UID']:"";
if(!empty($UID) && !empty($email))
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
	$checkUser = $db->employee_contacts->findOne(array("email"=>base64_decode($email),"UID"=>(int)base64_decode($UID)));
	if($checkUser)
	{
		$returnArr['status'] = 'csvnotuploaded';
		if(!empty($checkUser['connections']))
		{
			$returnArr['status'] = 'csvuploaded';
		}
		$checkUser['userType'] = 'employee';
		$returnArr['data'] = $checkUser;
		$_SESSION['member'] = $checkUser;
		$url = ANGULAR_ROUTE.'/refer/'.base64_decode($UID);
		header("Location: $url");exit;
    }
   
}
?>
