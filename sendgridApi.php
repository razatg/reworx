<?php
include_once('config-ini.php');
$postdata = file_get_contents("php://input");
if(!empty())
{
	$db = connect();
	$db->emailActivity->insert(json_decode($postdata));
}
?>
