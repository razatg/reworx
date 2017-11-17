<?php 
include_once('../config-ini.php');
$returnArr = array('status'=>'failure');
$userData = json_decode(file_get_contents('php://input'), true);
if(!empty($userData['data']))
{
	$userData = $userData['data'];
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
	$checkUser = $db->recruiter->findOne(array("email"=>trim($userData['email'])));
	if(empty($checkUser))
	{
		$userData['password'] = "";
		$userData['status'] = 0;
		$userData['addedon'] = time();
		if($db->recruiter->insert($userData))
		{
			$returnArr['status'] = 'success';
			$returnArr['msg'] = SUCCESSFULLY_REGISTER;	
		}
    }
    else
    {
		$returnArr['status'] = 'success';
		$returnArr['msg'] = EMAIL_ALLREADY;	
	}
	echo json_encode($returnArr);exit;
}
?>
