<?php
include_once('../config-ini.php');
ini_set('display_errors',0);
$returnArr = array('status'=>'failure','data'=>'','totalCount'=>0);
$arrValues = json_decode(file_get_contents('php://input'), true);
$page = isset($arrValues['page'])?trim($arrValues['page']):"1";
$where = array();
$db = connect();
$collection = $db->profile;
$offset = ($page*10);
if(!empty($_SESSION['member']['UID']))
{
	$UID = $_SESSION['member']['UID'];
	$cursorCount = $db->employeeshortlist->count(array('employeeId'=>(int)$UID));
	$dataListArr = $db->employeeshortlist->find(array('employeeId'=>(int)$UID))->skip($offset)->limit(10);
	if(!empty(iterator_to_array($dataListArr)))
	{
		$dataList = array();
		foreach(iterator_to_array($dataListArr) as $empData)
		{
			$data = $collection->findOne(array('UID'=>(int)$empData['UID']));
			if(!file_exists(ANGULAR_ABSOLUTE_PATH.$data['pic_phy']))
			{
			 $data['pic_phy']  = 'newui/images/user.png';
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


            
