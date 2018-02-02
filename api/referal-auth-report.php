<?php 
include_once('../config-ini.php');
$returnArr = array('status'=>'failure');
$email =  isset($_GET['email'])?$_GET['email']:"";
$type =  isset($_GET['type'])?$_GET['type']:"";
if(!empty($email))
{
	$email = $userData['data']['email'];
	$db = connect();
	$checkUser = $db->recruiter->findOne(array("email"=>$email));
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
		$checkUser = $db->employee->findOne(array("email"=>$email));
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
	$url = ANGULAR_ROUTE.'/'.$type;
	header("Location: $url");exit;
}
?>
