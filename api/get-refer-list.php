<?php
ini_set('display_errors',1);
include_once('../config-ini.php');
$returnArr = array('status'=>'failure','data'=>'','totalCount'=>0);
$arrValues = json_decode(file_get_contents('php://input'), true);
$page = isset($arrValues['page'])?trim($arrValues['page']):"1";
$where = array();
$position = isset($arrValues['position'])?trim($arrValues['position']):"";
$position = $orignalPos = preg_replace('/\s+/', ' ', $position);
$company = isset($arrValues['company'])?$arrValues['company']:"";
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
$collection = $db->profile;
$offset = ($page*10);
if(!empty($_SESSION['member']['UID']))
{
	$UID = $_SESSION['member']['UID'];
	$dataListArr = $db->employeeReferData->findOne(array('UID'=>$UID));
	$uIdList = $dataListArr['referalUIDList'];
	$where['UID']	 = array('$in' =>$uIdList);
}
if(!empty($where))
{
	$cursorCount = $collection->count($where);
	$cursor = $collection->find($where);
	$searchResult =  array_values(iterator_to_array($cursor));
	if(!empty($searchResult))
	{
	   	$returnArr['data'] = $searchResult;
		$returnArr['status'] = 'success';
		$returnArr['totalCount'] = $cursorCount;
		$returnArr['recruiterList'] = $dataListArr['recruiterList'];
	}
}
echo json_encode($returnArr,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);exit;

?>


            
