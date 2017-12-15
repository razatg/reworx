<?php 
include_once('../config-ini.php');
$returnArr = array('status'=>'failure');
$userData = json_decode(file_get_contents('php://input'), true);
if(!empty($userData['data']['email']) && !empty($userData['data']['password']))
{
	$email = $userData['data']['email'];
	$password = md5($userData['data']['password']);
	$db = connect();
	$checkUser = $db->recruiter->findOne(array("email"=>$email,"password"=>$password));
	if($checkUser)
	{
		$returnArr['status'] = 'success';
		$returnArr['data'] = $checkUser;
		$checkUser['userType'] = 'recruiter';
		$_SESSION['member'] = $checkUser;
		$db->recruitershortlist->remove(array("cId"=>$checkUser['cId']));
    }
    else
    {
		$checkUser = $db->employee->findOne(array("email"=>$email,"password"=>$password));
		if(!empty($checkUser))
		{
			$returnArr['status'] = 'csvnotuploaded';
			
			if($checkUser['connectionUploaded'])
			{
				$returnArr['status'] = 'csvuploaded';
			}
			$checkUser['userType'] = 'employee';
			$returnArr['data'] = $checkUser;
			$_SESSION['member'] = $checkUser;
		}
		else
		{
			$returnArr['status'] = 'failure';
		    $returnArr['msg'] = INVALID_LOGIN_DETAIL;
		}
			
	}
	echo json_encode($returnArr);exit;
}
?>
