<?php
include_once('../config-ini.php');
ini_set('display_errors',0);
$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>TheReferralWorks</title>
<style>.table-report{background:#f9f8f8;    width: 100%;
    max-width: 100%;
    margin-bottom: 20px;    border-spacing: 0;
    border-collapse: collapse;    font-family: Lato,sans-serif;
    font-size: 13px;
    line-height: 20px;
    color: #444;}
.table-report table, .table-report table td, .table-report table tr{    background: #f9f8f8;
    border: 0px !important;
    padding: 8px 0px !important;
    margin: 0px;
    vertical-align: text-bottom;}
.report_title{padding: 14px 0;
font-weight: bold;
display: inline-block;}
.report_img_icon{width: 35px;
border-radius: 100%;
height: 35px;
margin-right: 5px;}</style>
</head>
<body style="margin:0px; padding:0px;" bgcolor="#cccccc">
<style>
.emailer_temp{max-width:600px; margin:15px auto;}
@media screen and (max-width: 600px) {
.emailer_temp{max-width:90%; margin:15px auto;}
}
</style>
<a style="text-decoration:none;" href="'.ANGULAR_ROUTE.'/user-report">
<table class="emailer_temp" width="600px" bgcolor="#ffffff" border="0" cellspacing="0" cellpadding="0" align="center" style="border: 1px solid #bccbcb;">
   <tr>
      <td align="center" bgcolor="#fff">
         <table width="100%" border="0" cellspacing="15" cellpadding="0"  style="border-bottom:1px solid #ccc">
            <tr>
               <td><a href="#" target="_blank"><img border="0" alt="" src="'.ANGULAR_ROUTE.'/newui/images/logo.png"/></a></td>
            </tr>
         </table>
      </td>
   </tr>
   <tr>
      <td>
         <table width="100%" border="0" cellspacing="15" cellpadding="0">
            <tr>
               <td valign="top">
                  <h1 style="font-size:20px; font-family:calibri,arial,sans-serif; color:#414747; padding:0px 0; line-height:normal; margin:0; margin-bottom:5px;">
                  </h1>
                  <p style="font-size:16px; font-family:calibri,arial,sans-serif; color:#414747; line-height:22px; margin:0 0 0 0">__MSG_CONTENT__</p>
               </td>
            </tr>
            <tr>
            	<td></td>
            </tr>
            <tr>
            </tr>
         </table>
      </td>
   </tr>
   <tr>
      <td bgcolor="#f4f4f4" style="border-top: 1px solid #bccbcb;">
         <table width="100%" border="0" cellspacing="13" cellpadding="0">
            <tr>
               <td style="font-size:14px; font-family:calibri,arial,sans-serif; color:#819191; line-height:20px; margin:0; text-align:center;">© '.date('Y').' refhireable.com. All rights reserved.</td>
            </tr>
         </table>
      </td>
   </tr>
</table>
</a>
</body>
</html>'; 
$db = connect();
$returnArr = array('status'=>'failure');
	$employeeList = $db->employee->find();
	if(!empty($employeeList))
	{
	   foreach(iterator_to_array($employeeList) as $rdata)
		{
			$criteria = array();  
			$selecteDate = 20;
			$diff = $selecteDate * 24 * 60 * 60;
			$mongotime = time()-$diff;
			$UID = $rdata['UID'];
			$match    = array('$match'=>array('$and'=>array(array('referalUIDList.employeeList'=>(int)$UID,"addedOn"=>array('$gte'=>$mongotime)))));

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
			if(!empty($userReportData['result']))
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
								$userList[] =  array('addedOn'=>$item['addedOn'],'UID'=>$profileData['UID'],'profile_url'=>$profileData['name'],'name'=>$profileData['name'],'pic'=>$pic,'action'=>$action,'status'=>$status);
								
							
							}
						}
					}
					
				   $reportDataList[] = array('date'=>$date,'job_position'=>$jobPost,'userList'=>$userList);	
				}
			
			$referArr = array();
			$referArrParent = array();
			$currentTime = time();
			$reportDataList = $reportDataList[0];
			if(!empty($reportDataList))
			{
				$htmlTable = '<table class="table table-report"><thead>
                              <tr>
                                <th style="height:40px" width="15%">Job Position</th>
                                <th style="height:40px" width="16%">Connections</th>
                                <th style="height:40px" width="13%">Status</th>
                              </tr>
                            </thead><tbody>'; 
				$rlist = $reportDataList;	
				 $htmlTable .=	   '<tr>
									<td><span class="report_title">'.$rlist["job_position"].'</span></td>
									<td colspan="5">
										<table class="table" width="100%">';
											foreach($rlist['userList'] as $ulist)
											{
												
										 $htmlTable .='<tr>
												<td width="210px"><img src='.ANGULAR_ROUTE.'/'.$ulist["pic"].' width="30px" class="report_img_icon"/>'.$ulist["name"].'</td>';
										 $htmlTable .='	<td  width="90px">'.$ulist["status"].'</td>
											</tr>';
											}
											
					 $htmlTable .=	'</table>
									</td>
								  </tr>';
                        
			   $htmlTable .= ' </tbody></table>';
		  }
		  
		  		$referArrParent[] =  array('employeeList'=>(int)$item['UID'],'UID'=>(int)$data['UID'],'notFit'=>false,'donotknow'=>false,'fit'=>false);
				$to = array('to'=>array($rdata['email']));
				$messageHTML = str_replace('__MSG_CONTENT__',$htmlTable,$body);
				$messageHTML = str_replace('__MSG_CONTENT__',$htmlTable,$body);
				$from = '';
				$fromName = $rdata['full_name'];
				$toname =   $rdata['full_name'];
				$subject = 'Weekly Refhireable Report';
				$messageText = '';
				sendgridmail( $from, $fromName, $to, $toname, $subject, $messageText, $messageHTML, array(),array("UID"=>$data['UID']));
		  }
    	}
	}

function sendgridmail( $from, $fromName, $json_string, $toname, $subject, $messageText, $messageHTML, $headers, $unique_args) 
{
	
	$email = 'noreply@referralworx.com';
	$url = SENDGRID_URL;
	$user = SENDGRID_KEY;
	$pass = SENDGRID_PASS;
    $params = array(
					'api_user'  => $user,
					'api_key'   => $pass,
					'x-smtpapi' => json_encode($json_string),
					'to'        => $email,
					'fromname'  => $fromName,
					'toname'    => $toname,
					'subject'   => $subject,
					'html'      => $messageHTML,
					'from'      => $company_name.' via Referralworx <'.$email.'>',
					'unique_args' =>json_encode($unique_args)
				   );
			$request =  $url.'api/mail.send.json';
			// Generate curl request
			$session = curl_init($request);
			//print_r($messageHTML);exit;
			// Tell curl to use HTTP POST
			curl_setopt ($session, CURLOPT_POST, true);
			// Tell curl that this is the body of the POST
			curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
			// Tell curl not to return headers, but do return the response
			curl_setopt($session, CURLOPT_HEADER, false);
			// Tell PHP not to use SSLv3 (instead opting for TLS)
			curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
			// obtain response
			$response = curl_exec($session);
			curl_close($session);
			// print everything out
			print_r($response);
}
?>
