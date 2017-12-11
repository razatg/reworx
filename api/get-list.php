<?php 
ini_set('display_errors',0);
include_once('../config-ini.php');
$db = connect();
if(!empty($_SESSION['member']['cId']))
{
	$cId = $_SESSION['member']['cId'];
	$listArr['cId'] = $cId;
	$dataList = $db->recruitershortlist->findOne(array('cId'=>$cId));
	if(!empty($dataList))
	{
		echo json_encode($dataList['UIDList']);
	}
}
?>
