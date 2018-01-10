<?php
$myFile = "tmp/log.txt";
$fh = fopen($myFile, 'a+') or die("can't open file");
include_once('config-ini.php');
if ($fh){
    $headers = apache_request_headers();
    $postdata = file_get_contents("php://input");
    $postdatanew = json_decode($postdata,true);
    if(!empty($postdatanew))
    {	 $db = connect();
		$insertArr = array();
		foreach($postdatanew as $data)
		{
			if(!empty($data['category']))
			{
				$cat = explode('_',$data['category']);
				$insertArr['email'] = $data['email'];
				$insertArr['timestamp'] = $data['timestamp'];
				$insertArr['UID'] = $cat[0];
				$insertArr['identifire'] = $cat[1];
				$db->emailActivity->insert($insertArr);
			}
		}
	}
     
    foreach ($headers as $header => $value) {
    //fwrite($fh, print_r("$header: $value \n", true));
}

fwrite($fh, print_r("$postdata \n", true));
fclose($fh);
}

echo "ok";
?>
