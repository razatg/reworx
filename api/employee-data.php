<?php
include_once('../config-ini.php');
$returnArr = array('status'=>'failure','data'=>'','totalCount'=>0);
$arrValues = json_decode(file_get_contents('php://input'), true);
$page = isset($arrValues['page'])?trim($arrValues['page']):"1";
$where = array();
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
	$where['parentUID']	 = array('$in' => array($UID));
}
if(!empty($where))
{
	$cursorCount = $collection->count($where);
	$cursor = $collection->find($where)->skip($offset)->limit(10);
	$searchResult =  iterator_to_array($cursor);
	if(!empty($searchResult))
	{
		$returnArr['data'] = $searchResult;
		$returnArr['status'] = 'success';
		$returnArr['totalCount'] = $cursorCount;
	}
}
echo json_encode($returnArr,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);exit;
?>


            
