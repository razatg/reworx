<?php
ini_set('display_errors',1);
include_once('../config-ini.php');
$returnArr = array('status'=>'failure');
$db = connect();
$criteria = array();  
if(!empty($_SESSION['member']['cId']))
{
	$cId = $_SESSION['member']['cId'];
	$match    = array('$match'=>array('$and'=>array('cId'=>(int)$cId)));
}   
$criteria = array($match,array('$group'=>array('_id'=>array('date'=>array('$dateToString'=>array('format'=>
				                "%Y-%m-%d","date"=>'$date'))),
				                'count'=>array('$sum'=>1),
				                'employee'=>array('$push'=>'$$ROOT')
				                ))
				                );   
$userReportData = $db->employee->aggregate($criteria);
if(!empty($userReportData))
{
	$returnArr['data'] = $userReportData;
	$returnArr['status'] = 'success';
}
echo json_encode($returnArr);exit;	
?>
