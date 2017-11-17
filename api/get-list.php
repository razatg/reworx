<?php 
ini_set('display_errors',0);
include_once('../config-ini.php');
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
