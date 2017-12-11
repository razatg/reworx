<?php 
include_once('../config-ini.php');
$returnArr = array('status'=>'failure');
$userData = json_decode(file_get_contents('php://input'), true);
if(!empty($userData['data']))
{
	$userData = $userData['data'];
	$db = connect();
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
