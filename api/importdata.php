<?php
/*
$row = 1;
$array = array();
$array1 = array();
if (($handle = fopen("linkedinURL_All_Contacts.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
     if($row>1)
     {
		  
		  $array['_id'] =  $row-1;
		  $array['first_name'] =  $data[0];
		  $array['last_name'] =  $data[1];
		  $array['email'] =      $data[2];
		  $array['company'] =   $data[3];
		  $array['position'] =   $data[4];
		  $array['linkedinURL'] = '';
	  $array1[] = $array;	  
     }
   $fp = fopen("output_json/output_json.json", 'w');
	  fwrite($fp, json_encode($array1));
	  fclose($fp);         
$row++;
    }
    fclose($handle);
}*/
 
 
    $dir    = 'profile';
    $scanned_directory = array_diff(scandir($dir), array('..', '.'));
    if(!empty($scanned_directory))
    {
		foreach($scanned_directory as $data)
		{
			$string = file_get_contents( $dir.'/'.$data);
			$json_a = json_decode($string, true);
			unset($json_a['_id']);
			$url = str_replace("\\","/",$json_a['pic_phy']);
			$json_a['pic_phy'] = $url;
			//$collection = $db->profile->insert($json_a);
		}
	}

   // echo '<pre>';print_r($scanned_directory);exit;
   

?>


            
