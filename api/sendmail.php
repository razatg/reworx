<?php
ini_set('display_errors',0);
include_once('../config-ini.php');
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
            	<td><a href="__LINK_URL__" style="background: #0085c8;color: #fff;font-size: 20px;text-align: center;padding: 0px 20px;border-radius: 7px;overflow: hidden;text-decoration: navajowhite;display: table;margin: 15px auto;width: auto;height: 40px;font-family: arial;line-height: 20px;">__LINK_URL_MSG__</a></td>
            </tr>
            <tr>
            	<td>__HR_TEAM__</td>
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
	$mailData = $userData['formdata'];
	$db = connect();
	$referArr = array();
	$referArrParent = array();
	$currentTime = time();
	foreach($userData['connection']['data'] as $data)
	{
		if(!empty($data['connectedUsers']))
		{
			foreach($data['connectedUsers'] as $key=>$item)
			{
				if($item['IsChecked']==true)
				{
					$referArrParent[] =  array('UID'=>(int)$data['UID'],'notFit'=>false,'donotknow'=>false,'fit'=>false);
					$referArr[$item['UID']] =  $item['UID'];
					$to = array('to'=>array($item['email']));
					$messageHTML = "Hi ".$item['name'].",<br>".$data['name']." seems to be a good fit for the open position that we have for, <strong>".$mailData['job_title']."</strong>. <br>Since ".$data['name']." is connected with you on your social network, requesting you to write to him and have him get in touch with me or the HR team.<br>Please Click on the Button Below to write to him:<br>";
					$messageHTML = str_replace('__MSG_CONTENT__',$messageHTML,$body);
					$messageHTML = str_replace('__HR_TEAM__',"Regards,<br>Team HR",$messageHTML);
					$linkurl = ANGULAR_ROUTE.'/api/referal-auth.php?email='.base64_encode($item['email']).'&UID='.base64_encode($item['UID']).'&key='.base64_encode($data['UID']).'&uniqueID='.base64_encode($currentTime);
					$messageHTML = str_replace('__LINK_URL__',$linkurl,$messageHTML);
					$messageHTML = str_replace('__LINK_URL_MSG__','Refer '.$data['name'],$messageHTML);
					
					$from = '';
					$fromName = $_SESSION['member']['full_name'];
					$toname = $_SESSION['member']['full_name'];
					$subject = 'Refer your Connection '.$data['name'];
					$messageText = '';
					sendgridmail( $from, $fromName, $to, $toname, $subject, $messageText, $messageHTML, array(),array("UID"=>$data['UID']));
				}
			}
		}
	}
	if(!empty($referArr))
	{
		foreach($referArr as $key=>$val)
		{
			$dataRefer['UID'] = $key;
			$dataRefer['cId'] = $_SESSION['member']['cId'];
			$dataRefer['referalUIDList'] = $referArrParent;
			$dataRefer['recruiterList'] =  $mailData;
			$dataRefer['addedOn'] = $currentTime;
			$dataRefer['date'] = new MongoDate();
			$db->employeeReferData->insert($dataRefer);
		}
		if(!empty($_SESSION['member']['cId']))
		{
			$cId = $_SESSION['member']['cId'];
			$db->recruitershortlist->remove(array("cId"=>$cId));
		}
	}
	echo 'success';exit;
}

function sendgridmail( $from, $fromName, $json_string, $toname, $subject, $messageText, $messageHTML, $headers, $unique_args) 
{
	$email = 'info@refhireable.com';
	if(!empty($_SESSION['member']['cId']))
	{
		$company_name = $_SESSION['member']['company_name'];
	}
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
			//print_r($response);
}
?>
