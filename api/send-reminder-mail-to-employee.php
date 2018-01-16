<?php 
include_once('../config-ini.php');
ini_set('display_errors',1);
$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>TheReferralWorks</title>
</head>
<body style="margin:0px; padding:0px;" bgcolor="#cccccc">
<style>
.emailer_temp{max-width:600px; margin:15px auto;}
@media screen and (max-width: 600px) {
.emailer_temp{max-width:90%; margin:15px auto;}
}
</style>
<table class="emailer_temp" width="600px" bgcolor="#ffffff" border="0" cellspacing="0" cellpadding="0" align="center" style="border: 1px solid #bccbcb;">
   <tr>
      <td align="center" bgcolor="#ffffff">
         <table width="100%" border="0" cellspacing="15" cellpadding="0" style="border-bottom:1px solid #ccc">
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
                  <p style="font-size:16px; font-family:calibri,arial,sans-serif; color:#414747; line-height:22px; margin:0 0 20px 0">__MSG_CONTENT__</p>
               </td>
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
</body>
</html>';
$returnArr = array('status'=>'failure');
$userData = json_decode(file_get_contents('php://input'), true);
if(!empty($userData))
{
	$time = time();
	$db = connect();
	$UID = $userData['UID'];
	$addedOn = $userData['uniqueId'];
	$referData = $db->employeeReferData->findOne(array('addedOn'=>(int)$addedOn));
    if(!empty($referData))
		{
			$uIdList = $referData['referalUIDList'];
			if(!empty($uIdList))
				foreach($uIdList as $itemupdate)
				{
					if($itemupdate['UID']== $UID && $_SESSION['member']['UID'] == $itemupdate['employeeList']) 
					{
						$selectedProfile = $db->profile->findOne(array("UID"=>(int)$UID),array('UID','title','pic_phy','name','email','designation','area','company','experience','parentUID','profile_url'));
						if(!empty($selectedProfile))
						{
							if($_SERVER['HTTP_HOST']=='demo.onsisdev.info')
							{
								$to = array('to'=>array("pradip.comat@gmail.com"),'category'=>$selectedProfile['UID'].'_'.$addedOn);
							}
							else
							{
								$to = array('to'=>array($selectedProfile['email']),'category'=>$selectedProfile['UID'].'_'.$addedOn);
							}
							
							$cId = $referData['cId'];
							$ccArry = "";
							if(!empty($cId))
							{
								$dataListArr = $db->recruiter->findOne(array('cId'=>(int)$cId));
								if(!empty($dataListArr))
								{
									$ccArry =  $dataListArr['email'];
								}
							}
							$userFirstName = !empty($_SESSION['member']['first_name'])?$_SESSION['member']['first_name']:"";
							$userLastName = !empty($_SESSION['member']['last_name'])?$_SESSION['member']['last_name']:"";
							$empFullName  = explode(' ',$selectedProfile['name']);
							$empFullName = $empFullName[0];
							$messageHTML  =  str_replace('[USERNAME]', $empFullName,$referData['recruiterList']['message_to_employee']);
							$messageHTML  =  str_replace('Job Title','<b><u>Job Title</u></b>',$messageHTML);
							$messageHTML  =  str_replace('Job Desc','<b><u>Job Desc</u></b>' ,$messageHTML);
							$messageHTML  =  str_replace('[JOB_TITLE]', $referData['recruiterList']['job_title'],$messageHTML);
							$messageHTML  =  str_replace('[JOB_DESC]', $referData['recruiterList']['job_position_url'],$messageHTML);
							$messageHTML  =  str_replace('[RECRUITER_EMAIL]', '<a href="mailto:'.$dataListArr['email'].'">'.$dataListArr['email'].'</a>',$messageHTML);
							$messageHTML  =  str_replace('[COMPANY_NAME]', $dataListArr['company_name'],$messageHTML);
							$messageHTML  =  str_replace('[EMPLOYEE_NAME]', $userFirstName.' '.$userLastName,$messageHTML);
							$messageHTML = str_replace('__MSG_CONTENT__',nl2br($messageHTML),$body);
							$from = !empty($_SESSION['member']['email'])?$_SESSION['member']['email']:"";
							$fromName = $userFirstName.' '.$userLastName;
							$toname   =  $selectedProfile['name'];
							$subject  =  str_replace('[JOB_TITLE]', $referData['recruiterList']['job_title'],$referData['recruiterList']['subject_to_employee']);
							$subject  =  str_replace('[COMPANY_NAME]', $dataListArr['company_name'],$subject);
							$messageText = '';
							$checkMail = sendgridmail($from, $fromName, $to, $toname, $subject, $messageText, $messageHTML, array(),$ccArry);
						}
					}
				}
			}
		}
function sendgridmail($from, $fromName, $json_string, $toname, $subject, $messageText, $messageHTML, $headers, $ccArry) 
{

	$url = SENDGRID_URL;
	$user = SENDGRID_KEY;
	$pass = SENDGRID_PASS;
    $params = array(
							'api_user'  => $user,
							'api_key'   => $pass,
							'x-smtpapi' => json_encode($json_string),
							'to'        => 'noreply@referralworx.com',
							'cc'        => $ccArry,
							'fromname'  => $fromName,
							'toname'    => $toname,
							'subject'   => $subject,
							'html'      => $messageHTML,
							'from'      => $fromName.' via Referralworx <'.$from.'>',
			               );
			$request =  $url.'api/mail.send.json';
			// Generate curl request
			$session = curl_init($request);
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
echo json_encode($returnArr,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);exit;
?>
