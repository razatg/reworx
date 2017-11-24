<?php
ini_set('display_errors',0);
include_once('../config-ini.php');
$returnArr = array('status'=>'failure','data'=>'','totalCount'=>0);
$arrValues = json_decode(file_get_contents('php://input'), true);
$page = isset($arrValues['page'])?trim($arrValues['page']):"1";
$where = array();
$position = isset($arrValues['position'])?trim($arrValues['position']):"";
$position = $orignalPos = preg_replace('/\s+/', ' ', $position);
$company = isset($arrValues['company'])?$arrValues['company']:"";
$location = isset($arrValues['location'])?$arrValues['location']:"";
$total_experience = isset($arrValues['total_experience'])?$arrValues['total_experience']:"";
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
if(!empty($position))
{
	$position = explode(" ",$position);
	$str = '';
	foreach($position as $item)
	{
		$str.=	'"'.$item.'" ';
	}
	$str = trim($str);
	$where['$text'] = array('$search' => $str);
}
if(!empty($_SESSION['member']['cId']))
{
	$cId = $_SESSION['member']['cId'];
	$where['referrer_c_id']	 = array('$in' => array($cId));
}
if(!empty($company))
{
  $where['company']	 = new MongoRegex("/$company/i");
}
if(!empty($location))
{
  $where['area']	 = new MongoRegex("/$location/i");
}
if(!empty($total_experience))
{
  $where['total_experience']	 = $total_experience;
}
//print_r(json_encode($where));
if(!empty($where))
{
	$cursorCount = $collection->count($where);
	$cursor = $collection->find($where)->skip($offset)->limit(10);
	$searchResult =  iterator_to_array($cursor);
	if(!empty($searchResult))
	{
		$dataListArr = $db->recruitershortlist->findOne(array('cId'=>$cId));
		$dataList = array();
		foreach($searchResult  as $data)
		{
			$parentUidList = $data['parentUID'];
			$connectedProfiles = $collection->find(array('UID'=>array('$in' =>$parentUidList)),array('UID','pic_phy'));
			if(!empty($connectedProfiles))
			{
				$data['connectedUsers'] = array_values(iterator_to_array($connectedProfiles));
			}
			$data['IsEdit']	= false;
			if(in_array($data['UID'],$dataListArr['UIDList']))
			{
			  $data['IsEdit']	= true;
			}
			$dataList[] = $data;
		}
		$returnArr['data'] = $dataList;
		$returnArr['status'] = 'success';
		$returnArr['totalCount'] = $cursorCount;
	}
}
echo json_encode($returnArr,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);exit;

?>


            
