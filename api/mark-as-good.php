<?php 
include_once('../config-ini.php');
$returnArr = array('status'=>'failure');
$userData = json_decode(file_get_contents('php://input'), true);
if(!empty($userData))
{
	$type = $userData['type'];
	$db = connect();
	$employeId = $_SESSION['member']['UID'];
	$listArr['employeeId'] = (int)$employeId;
	$listArr['UID'] = (int)$userData['UID'];
	$listArr['addedon'] = time();
	if($type==true)
	{
		if($db->employeeshortlist->insert($listArr))
		{
			$returnArr['status'] = 'success';
		}
	}
	else
	{
		$db->employeeshortlist->remove(array('employeeId'=>(int)$employeId,'UID'=>(int)$userData['UID']));
		$returnArr['status'] = 'success';
	}
	echo json_encode($returnArr);exit;
}
?>
