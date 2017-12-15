<?php
ini_set('display_errors', 1);
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
		$db = connect();
		$UID = $_SESSION['member']['UID'];
		$checkUploadedCsv = $db->employee->findOne(array("UID"=>$UID),array("connections"));
		if(!empty($checkUploadedCsv['connections']))
		{
			$returnArr['status'] = 'alredyuploaded';
		}
		else
		{
		    $UID = !empty($_SESSION['member']['UID'])?$_SESSION['member']['UID']:"";
			$target_dir = "../tmp/profile/";
            $target_file = $target_dir.$UID.'.'.$pathInfo['extension'];
            $target_file = $target_dir.$UID.'.'.$pathInfo['extension'];
            $target_file = $target_dir.$UID.'.'.$pathInfo['extension'];
			if(move_uploaded_file($fileName,$target_file))
			{
				if($db->employee->update(array("UID"=>$UID),array('$set'=>array("connectionUploaded"=>true))))
				{
					$returnArr['status'] = 'success';
				}
			}
			else
			{
				$returnArr['status'] = 'not valid';
			}
			/*
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
				if($db->employee->update(array("UID"=>$UID),array('$set'=>array("connections"=>$UIDarray))))
				{
					$returnArr['status'] = 'success';
				}
			}
		else
		 {
			$returnArr['status'] = 'not valid';
		 } */	
		 
	    }
	   
	}
	else
	{
		$returnArr['status'] = 'not valid';
	}
}
echo json_encode($returnArr);exit;	
?>
