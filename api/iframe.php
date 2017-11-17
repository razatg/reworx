<?php
ini_set('display_errors',0);
$row = 1;
$m = new MongoClient("mongodb://dheeraj:dheeraj@ds117485.mlab.com:17485/pradip");
$db = $m->pradip;

$array = array();
$array1 = array();
if (($handle = fopen("linkedinURL_All_Contacts.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
     if($row>1)
     {
		
		$collection = $db->profile->findOne(array("email"=>$data[2]));
		if(!empty($collection))
		{
			//$collection = $db->profile->update(array("UID"=>$collection['UID']),array('$set'=>array('profile_url'=>$data[5])));
		}
		else
		{
			echo 'no found';
		}
     }
        
$row++;
    }
  // echo '<pre>'; print_r($array1);
    $m->pradip->contact1->batchInsert($array1);	  
}
exit;


$dir    = 'profile';

$allProfile =  $db->contact->find();
foreach($allProfile as $data)
{
   if(!empty($data))
   {
	    $collection = $db->profile->findOne(array("UID"=>$data['UID']));
	    if(!empty($collection))
	    {
			 echo '<br>UID' .$data['UID']. 'match found ';  
		}
		else
		{
			  unset($data['_id']);
			  $db->tmpcontactnew->insert($data);
			   echo '<br>UID'.$data['UID'].'not found ';
		}
	  
   }
   else
   {
	  
   }

}
exit;

$dir = 'profile';
$i = 1;
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
		 $string = explode(' ',$json_a['name']); 
		
		 $collection = $db->contact->findOne(array("email"=>$json_a['email']));
		 if(!empty($collection['email']))
		 {
			 echo '<br>'.'email'.' => '.$i . $collection['email'];
			 $json_a['UID'] = $collection['UID'];
			 $json_a['email'] = $collection['email'];
			 $json_a['referrer_c_id'] = array(1508145049);
			 $json_a['parentUID'] = array(1);
			 //$db->profile1->insert($json_a);
	     }
	     else
	     {
		   echo $lastName = '<br>'. $string[0].' '.$string[1].' '.$string[2];
		 echo '<br> not found';
		 }
		 
		$i++;
		 
	}
}
exit;

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta charset="utf-8">
		<link rel="canonical" href="<?= $canonical ?>"/>
	<title>Iframe  <?= $name ?></title>
		<?php
		if(!empty($_POST))
		{
			?>
			<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
			<?php
		}
		?>
	</head>
	<body style="height:100%;" width="">
		<iframe id="clientFrame" src="<?= empty($_POST)?$url:'' ?>" style="position:absolute; left:0; right:0; top:0; bottom:0; width:100%; height:100%; border:none;"></iframe>
		<?php
		if(!empty($_POST))
		{
			?>
			<form action="<?= $url ?>" method="POST" target="clientFrame" id="clientFrameForm">
			<?php foreach($_POST AS $key => $value)
				{ 
					?>
				 	<input type="hidden" name="<?= $key ?>" value="<?= $value ?>" />
					<?php 
				} 
			?>
			</form>
			<script>
			$().ready(function() {
				$('#clientFrameForm').submit();
			});
			</script>
			<?php
		}
		?>
	</body>
</html>
