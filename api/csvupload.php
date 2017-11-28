<?php
ini_set('display_errors', 0);
$returnArr = array('status'=>'failure','data'=>'');
if(!empty($_FILES))
{
	include_once('../config-ini.php');
	$imgName = $_FILES['imageName']['name']; 
	$pathInfo  = pathinfo($imgName);
	if($pathInfo['extension']=='csv')
	{
		$fileName = $_FILES['imageName']['tmp_name'];
		$row = 1;
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
		$UID = $_SESSION['member']['UID'];
		$checkUploadedCsv = $db->employee_contacts->findOne(array("UID"=>$UID),array("connections"));
		if(!empty($checkUploadedCsv['connections']))
		{
			$returnArr['status'] = 'alredyuploaded';
		}
		else
		{
			$checkCurrentUploading =  $db->contact->count();
			$array = array();
			$row = 1;
			$finalArr = array();
			$UIDarray = array();
			if(($handle = fopen($fileName, "r")) !== FALSE) 
			{
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
				{
					if($row == 1){ $row++; continue; }	
					  $checkDuplicateAccount =  $db->contact->findOne(array("email"=>trim($data[2])));
					  if(empty($checkDuplicateAccount) )
					  {
						  $array['UID'] =  $checkCurrentUploading+1;
						  $array['First Name'] =  $data[0];
						  $array['Last Name'] =  $data[1];
						  $array['Email Address'] =      $data[2];
						  $array['Company'] =   $data[3];
						  $array['Position'] =   $data[4];
						  $array['linkedinURL'] = '';
						  $finalArr[] = $array;	  
						  $UIDarray[] = $array['UID']; 
						 $checkCurrentUploading++; 
					 }
					 else
					 {
						 $UIDarray[] = $checkDuplicateAccount['UID'];
					 }
				}
				fclose($handle);
				$db->contact->batchInsert($finalArr);
				if($db->employee_contacts->update(array("UID"=>$UID),array('$set'=>array("connections"=>$UIDarray))))
				{
					$returnArr['status'] = 'success';
				}
			}
		else
		 {
			$returnArr['status'] = 'not valid';
		 }
	    }	
	}
	else
	{
		$returnArr['status'] = 'not valid';
	}
}
echo json_encode($returnArr);exit;	
?>
