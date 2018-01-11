<?php
ini_set('display_errors',0);
include_once('../config-ini.php');
$db = connect();
$udpateDbList =  $db->emailActivity->find();
if(!empty($udpateDbList))
{
	foreach($udpateDbList as $item)
	{
		$dataListArr = $db->employeeReferData->findOne(array('addedOn'=>(int)$item['identifier']));
		if(!empty($dataListArr))
		{
			$uIdList = $dataListArr['referalUIDList'];
			if(!empty($uIdList))
			{   $newUpdatedArr =  array(); 
				foreach($uIdList as $itemupdate)
				{
					if($itemupdate['UID']== $item['UID'])
					{
						$itemupdate['event'] = $item['event']; 
						$itemupdate['timestamp'] = $item['timestamp']; 
					}
				   $newUpdatedArr[] = $itemupdate;
				}
			}
			$updateArr = array('$set'=>array("referalUIDList"=>$newUpdatedArr));
			if($db->employeeReferData->update(array('addedOn'=>(int)$addedOn),$updateArr))
			{
				$returnArr['status'] = 'success';
			}
			
		}
	}
}
echo $returnArr['status'];exit;
?>


            
