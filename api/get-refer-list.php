<?php
include_once('../config-ini.php');
$returnArr = array('status'=>'failure','data'=>'','totalCount'=>0);
$arrValues = json_decode(file_get_contents('php://input'), true);
$page = isset($arrValues['page'])?trim($arrValues['page']):"1";
$where = array();
$position = isset($arrValues['position'])?trim($arrValues['position']):"";
$position = $orignalPos = preg_replace('/\s+/', ' ', $position);
$company = isset($arrValues['company'])?$arrValues['company']:"";
$db = connect();
$collection = $db->profile;
$offset = ($page*10);
if(!empty($_SESSION['member']['UID']))
{
	$UID = $_SESSION['member']['UID'];
	$dataReferListArr = array();
	$dataListArr = $db->employeeReferData->find(array('referalUIDList.employeeList'=>(int)$UID));
	$dataListArr = iterator_to_array($dataListArr);
	if(!empty($dataListArr))
	{
		foreach($dataListArr as $data)
		{
			if(!empty($data['referalUIDList']))
			{
				foreach($data['referalUIDList'] as $item)
				{
					if($UID ==$item['employeeList'])
					 {
						if($item['notFit']==false && $item['donotknow']==false && $item['fit']==false)
						{
							$selectedProfile = $collection->find(array("UID"=>(int)$item['UID']),array('UID','title','pic_phy','name','email','designation','area','company','experience','parentUID','profile_url'));
							if(!empty($selectedProfile))
							{
								$profileListArr = array();
								foreach($selectedProfile as $profile)
								{
									if(!file_exists(ANGULAR_ABSOLUTE_PATH.$profile['pic_phy']))
									{
										$profile['pic_phy']  = 'newui/images/user.png';
									}
									
									$profileListArr[] =  $profile;
								}
							}
							$dataReferListArr[] = array('profile'=>array_values($profileListArr),'recruiterMsg'=>$data['recruiterList'],'time'=>$data['addedOn'],'cId'=>$data['cId']);
						}
					}
			    }
			}
		}
		if(!empty($dataReferListArr))
		{
			$returnArr['data'] = $dataReferListArr;
			$returnArr['status'] = 'success';
			$returnArr['totalCount'] = count($dataReferListArr);
	    }
	}
}
echo json_encode($returnArr,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);exit;

?>


            
