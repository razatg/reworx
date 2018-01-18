<?php
include_once('../config-ini.php');
$returnArr = array('status'=>'failure','data'=>'','totalCount'=>0);
$arrValues = json_decode(file_get_contents('php://input'), true);
$page = isset($arrValues['page'])?trim($arrValues['page']):"1";
$company = isset($arrValues['company'])?explode(',',rtrim(trim($arrValues['company']),",")):"";
$name = isset($arrValues['name'])?$arrValues['name']:"";
$where = array();
$db = connect();
$collection = $db->profile;
$offset = ($page*10);
if(!empty($_SESSION['member']['UID']))
{
	$UID = $_SESSION['member']['UID'];
	$where['parentUID']	 = array('$in' => array($UID));
}
if(!empty($company))
{
	$tmp = array();
	foreach($company as $q) 
	{
		$companyName = trim($q);
		array_push($searchStringArr,$q);
		$tmp[] = new MongoRegex("/$companyName/i");
	}
	$where['company'] = array('$in'=>$tmp);
}
if(!empty($name))
{
  $where['name']	 = new MongoRegex("/$name/i");
}
if(!empty($where))
{
	$cursorCount = $collection->count($where);
	$cursor = $collection->find($where)->skip($offset)->limit(10);
	$searchResult =  iterator_to_array($cursor);
	$dataList = array();
	if(!empty($searchResult))
	{
		foreach($searchResult  as $data)
		{
			if(!file_exists(ANGULAR_ABSOLUTE_PATH.$data['pic_phy']))
			{
				$data['pic_phy']  = 'newui/images/user.png';
			}
		 $checkResult =$db->employeeshortlist->count(array('employeId'=>(int)$UID,'UID'=>(int)$data['UID']));
		 if($checkResult>0)
		 {
			  $data['IsEdit']	= false;	
		 }
		 else
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


            
