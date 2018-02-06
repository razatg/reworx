<?php
include_once('../config-ini.php');
ini_set('display_errors',0);
$returnArr = array('status'=>'failure');
$db = connect();
$criteria = array();  
$userType = $_SESSION['member']['userType']?$_SESSION['member']['userType']:"";
$userData = json_decode(file_get_contents('php://input'), true);
$reportType = $userData['reportType']?$userData['reportType']:"";
$position = $userData['position']?$userData['position']:"openposition";
$selecteDate = 15;
$diff = $selecteDate * 24 * 60 * 60;
$mongotime = time()-$diff;
$addedOn = array('$gte'=>$selecteDate);
if(!empty($userData))
{
	$selecteDate = $userData['reportType'];
	$diff = $selecteDate * 24 * 60 * 60;
    $mongotime = time()-$diff;
	$addedOn = array('$gte'=>$mongotime);
	if($selecteDate!=1 && $selecteDate!=15)
	{
		$selecteDate = explode('-',$userData['reportType']);
		$startDate = strtotime($selecteDate[0]);
		$endDate = strtotime($selecteDate[1]);
		$addedOn = array('$gte'=>$startDate,'$lte'=>$endDate);
	}
}

$cId = $_SESSION['member']['cId'];
if($position=='showall')
{
	$match    = array('$match'=>array('$and'=>array(array('cId'=>(int)$cId,'addedOn'=>$addedOn))));
}
else 
{
	$match    = array('$match'=>array('$and'=>array(array('referalUIDList.hired'=>true,'cId'=>(int)$cId,"addedOn"=>$addedOn))));
}

$sort =  array('$sort'=>array('_id'=>-1));

$criteria = array($match,array('$group'=>array('_id'=>array('date'=>array('$dateToString'=>array('format'=>
				                "%Y-%m-%d","date"=>'$date')),'job_title'=>'$recruiterList.job_title'),
				                'count'=>array('$sum'=>1),
				                'employee'=>array('$push'=>'$$ROOT')
				                )),$sort
				                );  
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
							$action = 'Send Reminder';
						}
						else if($item1['notFit'] == true || $item1['donotknow'] == true)
						{
							$action = 'Search Again';
							
						}
						else if($item1['event'] == 'open')
						{
								$action = '-';
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
						$userList[] =  array('hired'=>$item1['hired'],'addedOn'=>$item['addedOn'],'UID'=>$profileData['UID'],'profile_url'=>$profileData['name'],'name'=>$profileData['name'],'pic'=>$pic,'action'=>$action,'status'=>$status,'connectedUsers'=>array_values(iterator_to_array($connectedProfiles)));
					
				}
			}
		}
		
	   $reportDataList[] = array('date'=>$date,'job_position'=>$jobPost,'userList'=>$userList);	
	}
	$returnArr['data'] = $reportDataList;
	$returnArr['userReportCount'] = $userReportCount;
	$returnArr['status'] = 'success';
}
echo json_encode($returnArr);exit;	
?>
