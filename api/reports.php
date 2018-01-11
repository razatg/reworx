<?php
include_once('../config-ini.php');
ini_set('display_errors',0);
$returnArr = array('status'=>'failure');
$db = connect();
$criteria = array();  
$userType = $_SESSION['member']['userType']?$_SESSION['member']['userType']:"";
if($userType=='recruiter')
{
	$cId = $_SESSION['member']['cId'];
	$match    = array('$match'=>array('$and'=>array(array('cId'=>(int)$cId))));
}
else if($userType=='employee')
{
	$UID = $_SESSION['member']['UID'];
	$match    = array('$match'=>array('$and'=>array(array('UID'=>(int)$UID))));
}  
$criteria = array($match,array('$group'=>array('_id'=>array('date'=>array('$dateToString'=>array('format'=>
				                "%Y-%m-%d","date"=>'$date')),'job_title'=>'$recruiterList.job_title'),
				                'count'=>array('$sum'=>1),
				                'employee'=>array('$push'=>'$$ROOT')
				                ))
				                );   
$userReportData = $db->employeeReferData->aggregate($criteria);
$reportDataList = array();
if(!empty($userReportData))
{
	foreach($userReportData['result'] as $data)
	{
		$jobPost  = $data['_id']['job_title'];
		$date   = $data['_id']['date'];
		if(!empty($data['employee']))
		{
			$userList = array();
			foreach($data['employee'] as $item)
			{
				foreach($item['referalUIDList'] as $item1)
				{
					$profileData = $db->profile->findOne(array('UID'=>(int)$item1['UID']),array('email','name','pic_phy','parentUID'));
					$status = 'Pending';
					///$data = checkReferDate($profileData['email']);
					//print_r($data);exit;
					if($item1['notfit'] == true || $item1['donotknow'] == true)
					{
						$status = 'Not Suitable'; 
					}
					if($item1['fit'] == true)
					{
						$status = 'Sent Referral'; 
					}
					$parentUidList = $profileData['parentUID'];
					$pic = $profileData['pic_phy'];
					if(!file_exists(ANGULAR_ABSOLUTE_PATH.$profileData['pic_phy']))
					{
						$pic  = 'newui/images/user.png';
					}
					$connectedProfiles = $db->employee->find(array('UID'=>array('$in' =>$parentUidList)),array('UID','first_name','last_name'));
					$userList[] =  array('name'=>$profileData['name'],'pic'=>$pic,'status'=>$status,'connectedUsers'=>array_values(iterator_to_array($connectedProfiles)));
					
				
				}
			}
		}
		
	   $reportDataList[] = array('date'=>$date,'job_position'=>$jobPost,'userList'=>$userList);	
	}
	$returnArr['data'] = $reportDataList;
	$returnArr['status'] = 'success';
}

function checkReferDate($email)
{
	$url = SENDGRID_URL;
	$user = SENDGRID_KEY;
	$pass = SENDGRID_PASS;
    $params = array(
					'api_user'  => $user,
					'api_key'   => $pass,
					'date'      => 1,
					'email'     =>$email
				   );
			echo $request =  $url.'api/bounces.get.json?api_user='.$user.'&api_key='.$pass.'&date=1&email='.$email;
			// Generate curl request
			$session = curl_init($request);
			// Tell curl to use HTTP POST
			//curl_setopt ($session, CURLOPT_GET, true);
			// Tell curl that this is the body of the POST
			//curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
			// Tell curl not to return headers, but do return the response
			curl_setopt($session, CURLOPT_HEADER, false);
			// Tell PHP not to use SSLv3 (instead opting for TLS)
			curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
			// obtain response
			$response = curl_exec($session);
			curl_close($session);
			// print everything out
			return $response;
}

echo json_encode($returnArr);exit;	
?>
