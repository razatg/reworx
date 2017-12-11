<?php 
include_once('../config-ini.php');
$returnArr = array('status'=>'failure');
$email = isset($_GET['email'])?$_GET['email']:"";
$UID = isset($_GET['UID'])?$_GET['UID']:"";
$key = isset($_GET['key'])?$_GET['key']:"";
$uniqueID = isset($_GET['uniqueID'])?$_GET['uniqueID']:"";
if(!empty($UID) && !empty($email))
{
	$db = connect();
	$checkUser = $db->employee_contacts->findOne(array("email"=>base64_decode($email),"UID"=>(int)base64_decode($UID)));
	if($checkUser)
	{
		$returnArr['status'] = 'csvnotuploaded';
		if(!empty($checkUser['connections']))
		{
			$returnArr['status'] = 'csvuploaded';
		}
		$checkUser['userType'] = 'employee';
		$checkUser['selectedProfile'] = base64_decode($key);
		$checkUser['uniqueID'] = base64_decode($uniqueID);
		$returnArr['data'] = $checkUser;
		$_SESSION['member'] = $checkUser;
		$url = ANGULAR_ROUTE.'/refer/'.base64_decode($UID);
		header("Location: $url");exit;
    }
   
}
?>
