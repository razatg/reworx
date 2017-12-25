<?php
ini_set('display_errors', 1);
include_once('../config-ini.php');
$returnArr = array('status'=>'failure','data'=>'');
$target_dir = "../tmp/profile/";
$scanned_directory = array_diff(scandir($target_dir), array('..', '.'));
if(!empty($scanned_directory))
{
	$flag = true;
	foreach($scanned_directory as $data)
	{
		if(strpos($data,'done_')===false)
		{
			$fileName = $target_dir.$data;
			$pathInfo  = pathinfo($fileName); 
			updateToDB($fileName);
			if(rename($fileName, $target_dir.'done_'.$data))
			{
			   //@unlink($fileName);
			}
			sleep(10);
	   }
	   else
	   {
		 echo "connections already updated with UID => ".$data." <br>";
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
					  $checkDuplicateAccount =  $db->connections1->findOne(array("email"=>trim($data[2])));
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
				if($db->employee1->update(array("UID"=>(int)$UID),array('$set'=>array("connections"=>$UIDarray))))
				{
					if(!empty($finalArr))
					{
						if($db->connections1->batchInsert($finalArr))
						{
							echo "connections updated with UID => ".$UID." <br>";
						}
						else
						{
							echo "connections not updated with UID => ".$UID." <br>";
						}
					}
					return true;
				}
			fclose($handle);
			}
		else
		 {
			echo "Not valid csv file";
		 } 
		 
	    }
	}
}
?>
