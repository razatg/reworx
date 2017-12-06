<?php
ini_set('display_errors',1);
include_once('../config-ini.php');
$returnArr = array('status'=>'failure','data'=>'','totalCount'=>0);
$arrValues = json_decode(file_get_contents('php://input'), true);
$page = isset($arrValues['page'])?trim($arrValues['page']):"1";
$where = array();
$position = isset($arrValues['position'])?explode(',',rtrim(trim($arrValues['position']),",")):"";
$company = isset($arrValues['company'])?explode(',',rtrim(trim($arrValues['company']),",")):"";
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
	$tmpTitle = array();
	$tmpFeature = array();
	$summaryArr = array();
	$experienceArr = array();
	foreach($position as $q) {
	   $tmpTitle[] = array('title'=>array('$regex'=>$q,'$options'=>'i'));
	   $tmpFeature[] = array('featured_skiils'=>array('$regex'=>$q,'$options'=>'i'));
	   $summaryArr[] = array('summary'=>array('$regex'=>$q,'$options'=>'i'));
	   $experienceArr[] = array('experience'=>array('designation'=>array('$regex'=>$q,'$options'=>'i')));
	}
	$where['$or'] = array(array('$or'=>$tmpTitle),array('$or'=>$tmpFeature),array('$or'=>$summaryArr),array('$or'=>$experienceArr));
	//$where['$or'] = $tmpFeature;
}
if(!empty($_SESSION['member']['cId']))
{
	$cId = $_SESSION['member']['cId'];
	$where['referrer_c_id']	 = array('$in' => array($cId));
}
if(!empty($company))
{
		$tmp = array();
		foreach ($company as $q) 
		{
			$tmp[] = new MongoRegex("/$q/i");
		}
		$where['company'] = array('$in'=>$tmp);
}
if(!empty($location))
{
  $where['area']	 = new MongoRegex("/$location/i");
}
if(!empty($total_experience) && $total_experience!='Select Experience')
{
  $where['total_experience']	 = array('$gt'=>$total_experience);
}
//print_r(json_encode($where));exit;

if(!empty($where))
{
	$cursorCount = $collection->count($where);
	$cursor = $collection->find($where,array('summary','experience','UID','title','featured_skiils','pic_phy','name','designation','company','experience','parentUID','area'))->skip($offset)->limit(10);
	$searchResult =  iterator_to_array($cursor);
	if(!empty($searchResult))
	{
		$dataListArr = $db->recruitershortlist->findOne(array('cId'=>$cId));
		$dataList = array();
		foreach($searchResult  as $data)
		{
			$parentUidList = $data['parentUID'];
			$connectedProfiles = $collection->find(array('UID'=>array('$in' =>$parentUidList)),array('UID','pic_phy','name','designation','company'));
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


            
