<?php
ini_set('display_errors',1);
$row = 1;
$m = new MongoClient("mongodb://referrer:refer2hire!1@ds139856-a0.mlab.com:39856,ds139856-a1.mlab.com:39856/refhireable?replicaSet=rs-ds139856");
$db = $m->refhireable;
$exp = 0;
$array = array();
$array1 = array();
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
		 $string = explode(' ',$json_a['name']); 
		 $collection = $db->connections->findOne(array("email"=>$json_a['email']));
		 if(!empty($collection['email']))
		 {
			 
			  $profileList = $db->profile->findOne(array("UID"=>(int)$collection['UID']),array('UID'));
			  if(!empty($profileList))
			  {
				   echo '<br>'.'profile Already Added'.' => '.$i . $collection['email'];
			  }
			  else
			  {
				  $datalist = $db->employee->findOne(array("connections"=>array('$in'=>array($collection['UID']))));
				  if(!empty($datalist))
				  {
					 $json_a['parentUID'] = array($datalist['UID']);
				  }
				  if(!empty($data['experience']))
				  {
					  $expcount = count($data['experience'])-1;
					  $originaldata = $data['experience'][$expcount]['start_dt'];
					  $yearOfexp = explode(' ',$originaldata);
					  if(!empty($yearOfexp[1]))
					  {
						  $exp = 2017-(int)trim($yearOfexp[1]);
					  }
					//  echo '<br>'.'UID from exp ' . $data['UID'].'=> '.  $exp;
				  }
				  else
				  {
					  $yearOfexp = explode('â€“',$data['education'][0]['end_dt']);
					  if(count($yearOfexp)>1)
					  {
						   if(!empty($yearOfexp[1]))
						   {
							   $exp = 2017 - (int)$yearOfexp[1];
						   }
						  
						   //echo '<br>'.'UID from edu ' . $data['UID'].' => '. $exp;
					  }
					  else if(!empty($data['education'][0]['end_dt']))
					  {
						  $exp = 2017 - (int)$data['education'][0]['end_dt'];
						 //  echo '<br>'.'UID from edu end_dt date' . $data['UID'].'=> '. $exp;
					  }
				  }
				 $json_a['total_experience'] = (int)$exp;
				 echo '<br>'.'email'.' => '.$i . $collection['email'];
				 $image = file_get_contents($json_a['pic']);
				 $url = 'images/'.$collection['UID'].'.jpg';
				 file_put_contents('images/'.$collection['UID'].'.jpg', $image);
				 $json_a['UID'] = $collection['UID'];
				 $json_a['email'] = $collection['email'];
				 $json_a['referrer_c_id'] = array(1508145049);
				 $json_a['pic_phy'] = 'tmp/'.$url;
				 if($db->profile->insert($json_a))
				 {
					$allProfile =  $db->connections->update(array('UID'=> $collection['UID']),array('$set'=>array('isProfile'=>true)));
				 }
			  }
			 
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
