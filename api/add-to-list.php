<?php 
include_once('../config-ini.php');
$returnArr = array('status'=>'failure');
$userData = json_decode(file_get_contents('php://input'), true);
if(!empty($userData))
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
	$cId = $_SESSION['member']['cId'];
	$listArr['cId'] = $cId;
	$listArr['UIDList'] = $userData['UIDList'];
	$listArr['addedon'] = time();
	$db->recruitershortlist->remove(array('cId'=>$cId));
	if($db->recruitershortlist->insert($listArr))
	{
		$returnArr['status'] = 'success';
		$returnArr['msg'] = SUCCESSFULLY_REGISTER;	
	}
	echo json_encode($returnArr);exit;
}
?>
