<?php 
include_once('../config-ini.php');
$returnArr = array('status'=>'failure');
$userData = json_decode(file_get_contents('php://input'), true);
if(!empty($userData))
{
	$db = connect();
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
