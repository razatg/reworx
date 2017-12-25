<?php 
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
	$db = connect();
	$data = $userData['formdata'];
	if($_SERVER['HTTP_HOST']=='demo.onsisdev.info')
	{
		$to = array('to'=>array("pradip.comat@gmail.com","girdhar.rajat@gmail.com"));
	}
	else
	{
		$to = array('to'=>array($data['employeeDetail']['profile'][0]['email']));
	}
	$addedOn = $data['employeeDetail']['time'];
	$cId = $data['employeeDetail']['cId'];
	$ccArry = "";
	if(!empty($cId))
	{
		$dataListArr = $db->recruiter->findOne(array('cId'=>(int)$cId));
		if(!empty($dataListArr))
		{
			$ccArry =  $dataListArr['email'];
		}
	}
	$messageHTML =  $data['message_to_employee'];
	$messageHTML = str_replace('__MSG_CONTENT__',nl2br($messageHTML),$body);
	$from = !empty($_SESSION['member']['email'])?$_SESSION['member']['email']:"";
	$fromName = !empty($_SESSION['member']['first_name'])?$_SESSION['member']['first_name']:"";
	$toname   =  $data['employeeDetail']['profile'][0]['name'];
	$subject  =  $data['subject_to_employee'];
	$messageText = '';
	sendgridmail($from, $fromName, $to, $toname, $subject, $messageText, $messageHTML, array(),$ccArry);
	$db = connect();
	if(!empty($_SESSION['member']['UID']))
	{
		$UID = $_SESSION['member']['UID'];
		$UIDFromRemove = $data['employeeDetail']['profile'][0]['UID']; 
		$dataListArr = $db->employeeReferData->findOne(array('addedOn'=>(int)$addedOn));
		$uIdList = $dataListArr['referalUIDList'];
		if(!empty($uIdList))
		{   $newUpdatedArr =  array(); 
			foreach($uIdList as $item)
			{
				if($item['UID']== $UIDFromRemove)
				{
					$item['fit'] = true; 
				}
			   $newUpdatedArr[] = $item;
			}
		}
		$updateArr = array('$set'=>array("referalUIDList"=>$newUpdatedArr));
		$db->employeeReferData->update(array('addedOn'=>(int)$addedOn),$updateArr);
	}
	
	echo 'success';exit;
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
			//print_r( $params);exit;
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
