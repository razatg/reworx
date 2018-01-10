<?php
$myFile = "tmp/log.txt";
$fh = fopen($myFile, 'a+') or die("can't open file");
$postdata = file_get_contents("php://input");
fwrite($fh, print_r("$postdata \n", true));
fclose($fh);
include_once('config-ini.php');
$postdata = file_get_contents("php://input");
if(!empty())
{
	$db = connect();
	$db->emailActivity->insert(json_decode($postdata,true));
}
?>
