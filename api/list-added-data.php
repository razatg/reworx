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
if(!empty($_SESSION['member']['cId']))
{
	$cId = $_SESSION['member']['cId'];
	$dataListArr = $db->recruitershortlist->findOne(array('cId'=>$cId));
	$uIdList = $dataListArr['UIDList'];
	$where['UID']	 = array('$in' => $uIdList);

}
//print_r(json_encode($where));
if(!empty($where))
{
	$cursorCount = $collection->count($where);
	$cursor = $collection->find($where)->skip($offset)->limit(10);
	$searchResult =  iterator_to_array($cursor);
	if(!empty($searchResult))
	{
       $dataList = array();
       
       foreach($searchResult  as $data)
	   {
			$dataListChild = array();
			$parentUidList = $data['parentUID'];
			$i = 0;
			$connectedProfiles = $collection->find(array('UID'=>array('$in' =>$parentUidList)));
			if(!empty($connectedProfiles))
			{
				foreach(iterator_to_array($connectedProfiles) as $item)
				{
					if($i==0)
					{
						$item['IsChecked']	= true;
					}
					else
					{
						$item['IsChecked']	= false;
					}
					$i++;
				  $dataListChild[] = $item;	
				}
				$data['connectedUsers'] = array_values($dataListChild);
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


            
