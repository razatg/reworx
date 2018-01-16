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
					$activityList = $db->emailActivity->findOne(array("UID"=>(int)$cat[0],"employeeId"=>(int)$cat[2],"identifier"=>(int)$cat[1]));
					if(!empty($activityList))
					{
						$db->emailActivity->update(array("UID"=>(int)$cat[0],"employeeId"=>(int)$cat[2],"identifier"=>(int)$cat[1]),array('$set'=>array('event'=>$data['event'],'timestamp'=>(int)$data['timestamp'])));
					}
					else
					{
						
						$insertArr['email'] = $data['email'];
						$insertArr['timestamp'] = (int)$data['timestamp'];
						$insertArr['UID'] = (int)$cat[0];
						$insertArr['employeeId'] = (int)$cat[2];
						$insertArr['identifier'] = (int)$cat[1];
						$insertArr['event'] = $data['event'];
						$db->emailActivity->insert($insertArr);
					}
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
