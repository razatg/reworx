<?php
ini_set('display_errors', 1);
include_once('../config-ini.php');
$returnArr = array('status'=>'failure','data'=>'');
$target_dir = "../tmp/profile/";
$scanned_directory = array_diff(scandir($target_dir), array('..', '.'));
if(!empty($scanned_directory))
{
	foreach($scanned_directory as $data)
	{
		$fileName = $target_dir.$data;
		$pathInfo  = pathinfo($fileName); 
		if(updateToDB($fileName))
		{
			echo "Profile with UID=> ".$pathInfo['filename']." Udated<br>";
			
			$target_dir = "../tmp/trash-profile/";
			if(rename($fileName, $target_dir.$data))
			{
			   @unlink($fileName);
			}
			sleep(10);
		}
	}
}
else
{
	echo "No connection uploaded";
}
function updateToDB($fileName)
{
	$db = connect();
	$pathInfo  = pathinfo($fileName);
	if($pathInfo['extension']=='csv')
	{
		$row = 1;
		$UID = $pathInfo['filename'];
		$checkUploadedCsv = $db->employee->findOne(array("UID"=>$UID),array("connections"));
		if(!empty($checkUploadedCsv['connections']))
		{
			$returnArr['status'] = 'alredyuploaded';
		}
		else
		{
			$checkCurrentUploading =  $db->connections->count();
			$array = array();
			$row = 1;
			$finalArr = array();
			$UIDarray = array();
			if(($handle = fopen($fileName, "r")) !== FALSE) 
			{
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
				{
					if($row == 1){ $row++; continue; }	
					  $checkDuplicateAccount =  $db->connections->findOne(array("email"=>trim($data[2])));
					  if(empty($checkDuplicateAccount) )
					  {
						  $array['UID'] =  $checkCurrentUploading+1;
						  $array['first_name'] =  $data[0];
						  $array['last_name'] =   $data[1];
						  $array['email'] =       $data[2];
						  $array['company'] =     $data[3];
						  $array['position'] =    $data[4];
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
				if($db->employee->update(array("UID"=>(int)$UID),array('$set'=>array("connections"=>$UIDarray))))
				{
					if(!empty($finalArr))
					{
						$db->connections->batchInsert($finalArr);
					}
					return true;
				}
			fclose($handle);
			}
		else
		 {
			return false;
		 } 
		 
	    }
	}
}
?>
