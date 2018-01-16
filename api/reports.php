<?php
include_once('../config-ini.php');
ini_set('display_errors',0);
$returnArr = array('status'=>'failure');
$db = connect();
$criteria = array();  
$userType = $_SESSION['member']['userType']?$_SESSION['member']['userType']:"";
$userData = json_decode(file_get_contents('php://input'), true);
$selecteDate = 15;
if(!empty($userData))
{
	$selecteDate = $userData['reportType'];
}
$diff = $selecteDate * 24 * 60 * 60;
$mongotime = time()-$diff;
if($userType=='recruiter')
{
	$cId = $_SESSION['member']['cId'];
	$match    = array('$match'=>array('$and'=>array(array('cId'=>(int)$cId,"addedOn"=>array('$gte'=>$mongotime)))));
}
else if($userType=='employee')
{
	$UID = $_SESSION['member']['UID'];
	$match    = array('$match'=>array('$and'=>array(array('UID'=>(int)$UID,"addedOn"=>array('$gte'=>$mongotime)))));
} 


$criteria = array($match,array('$group'=>array('_id'=>array('date'=>array('$dateToString'=>array('format'=>
				                "%Y-%m-%d","date"=>'$date')),'job_title'=>'$recruiterList.job_title'),
				                'count'=>array('$sum'=>1),
				                'employee'=>array('$push'=>'$$ROOT')
				                ))
				                );  
//print_r(json_encode($criteria));exit;				                 
$userReportData = $db->employeeReferData->aggregate($criteria);
$reportDataList = array();
$userReportCount = array('employee'=>0,'totalProfile'=>0,'selectedCandidate'=>0,'referRequest'=>0,'emailSent'=>0,'emailClicked'=>0,'hired'=>0);
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
					$profileData = $db->profile->findOne(array('UID'=>(int)$item1['UID']),array('UID','email','name','pic_phy','parentUID','profile_url'));
					$status = 'Pending';
					///$data = checkReferDate($profileData['email']);
					//print_r($data);exit;
					if($item1['notFit'] == true || $item1['donotknow'] == true)
					{
						$status = 'Not Suitable'; 
					}
					else if($item1['event'] == 'delivered')
					{
						$status = 'Mail Delivered'; 
					}
					else if($item1['event'] == 'open')
					{
						$status = 'Mail Opened'; 
					}
					else if($item1['fit'] == true)
					{
						$status = 'Sent Referral'; 
					}
					$action = '';
					if($item1['notFit'] == false && $item1['donotknow'] == false &&  $item1['fit']== false )
					{
						if($userType=='employee')
						{
							$action = 'Send Referral';
						}
						else
						{
							$action = 'Send Reminder';
						}
						
					}
					else if($item1['notFit'] == true || $item1['donotknow'] == true)
					{
						if($userType=='employee')
						{
							$action = '-';
						}
						else
						{
							$action = 'Search Again';
						}
						
					}
					else if($userType=='employee' && ($item1['event'] == 'delivered' || $item1['fit'] == true))
					{
						$action = 'Send Reminder';
					}
					else if($userType=='employee' && $item1['event'] == 'open')
					{
						$action = 'Call May Be';
					}
					else if($userType=='recruiter' && $item1['fit'] == true)
					{
						$action = '-';
					}
				
					$parentUidList = $profileData['parentUID'];
					$pic = $profileData['pic_phy'];
					if(!file_exists(ANGULAR_ABSOLUTE_PATH.$profileData['pic_phy']))
					{
						$pic  = 'newui/images/user.png';
					}
					$connectedProfiles = $db->employee->find(array('UID'=>(int)$item1['employeeList']),array('UID','first_name','last_name'));
					$userList[] =  array('addedOn'=>$item['addedOn'],'UID'=>$profileData['UID'],'profile_url'=>$profileData['name'],'name'=>$profileData['name'],'pic'=>$pic,'action'=>$action,'status'=>$status,'connectedUsers'=>array_values(iterator_to_array($connectedProfiles)));
					
				
				}
			}
		}
		
	   $reportDataList[] = array('date'=>$date,'job_position'=>$jobPost,'userList'=>$userList);	
	}
	$returnArr['data'] = $reportDataList;
	$returnArr['userReportCount'] = $userReportCount;
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
