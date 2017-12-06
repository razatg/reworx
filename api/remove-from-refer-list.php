<?php
ini_set('display_errors',0);
include_once('../config-ini.php');
$returnArr = array('status'=>'failure','data'=>'');
$arrValues = json_decode(file_get_contents('php://input'), true);
$UIDFromRemove = isset($arrValues['UID'])?trim($arrValues['UID']):"1";
$addedOn = isset($arrValues['addedOn'])?trim($arrValues['addedOn']):"";
$type = isset($arrValues['type'])?trim($arrValues['type']):"";
$flagValue = true;
if($_SERVER['HTTP_HOST']=='localhost')
{
	$m = new MongoClient("mongodb://192.168.3.2:27017");
	$db = $m->RPO_DataBase;
}
else if($_SERVER['HTTP_HOST']=='demo.onsisdev.info')
{
	$m = new MongoClient("mongodb://dheeraj:dheeraj@ds117485.mlab.com:17485/pradip");
	$db = $m->pradip;
}
if(!empty($_SESSION['member']['UID']))
{
	$UID = $_SESSION['member']['UID'];
	$dataListArr = $db->employeeReferData->findOne(array('addedOn'=>(int)$addedOn));
	$uIdList = $dataListArr['referalUIDList'];
	if(!empty($uIdList))
	{   $newUpdatedArr =  array(); 
		foreach($uIdList as $item)
		{
			if($item['UID']== $UIDFromRemove)
			{
				$item[$type] = true; 
			}
		   $newUpdatedArr[] = $item;
		}
	}
	$updateArr = array('$set'=>array("referalUIDList"=>$newUpdatedArr));
	if($db->employeeReferData->update(array('addedOn'=>(int)$addedOn),$updateArr))
	{
		$returnArr['status'] = 'success';
	}
}
echo json_encode($returnArr,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);exit;

?>


            
