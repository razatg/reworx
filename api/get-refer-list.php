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
	$dataReferListArr = array();
	$dataListArr = $db->employeeReferData->find(array('UID'=>(int)$UID));
	$dataListArr = iterator_to_array($dataListArr);
	if(!empty($dataListArr))
	{
		foreach($dataListArr as $data)
		{
			if(!empty($data['referalUIDList']))
			{
				foreach($data['referalUIDList'] as $item)
				{
					$selectedProfile = $collection->find(array("UID"=>(int)$item['UID']),array('UID','title','pic_phy','name','designation','company','experience','parentUID'));
				    $dataReferListArr[] = array('profile'=>array_values(iterator_to_array($selectedProfile)),'recruiterMsg'=>$data['recruiterList']);
				}
			}
		}
		
		$returnArr['data'] = $dataReferListArr;
		$returnArr['status'] = 'success';
		$returnArr['totalCount'] = count($dataReferListArr);
	}
}
echo json_encode($returnArr,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);exit;

?>


            
