<?php
include_once('../config-ini.php');
ini_set('display_errors', 0);
$returnArr = array('status'=>'failure','data'=>'');
$arrValues = json_decode(file_get_contents('php://input'), true);
if(!empty($arrValues))
{
	$type = isset($arrValues['type'])?trim($arrValues['type']):"";
	$typeSearch = isset($arrValues['keywords'])?trim($arrValues['keywords']):"";
	$db = connect();
	$collection = $db->profile;
	if($type=='position')
	{
		$where = array('$text' => array('$search' =>$typeSearch));
		$cursor = $collection->find($where,array('featured_skiils'=>1,'title'=>1));
		$searchResult =  iterator_to_array($cursor);
		$suggestionResult2 = array();
		if(!empty($searchResult))
		{
			$suggestionResult = array();
			foreach($searchResult  as $data)
			{
				if($data['title']!='')
				{
					$suggestionResult[] = array('value'=>str_replace(',', '', $data['title']),'label'=>str_replace(',', '', $data['title']));
				}
				foreach($data['featured_skiils'] as $key=>$val)
				{
					if(strpos(strtolower($val), strtolower($typeSearch)) !== false && $val!='') 
					{
						$suggestionResult2[] = array('value'=>str_replace(',', '', $val),'label'=>str_replace(',', '', $val));
					}
				}
			}
		}
		if(!empty($suggestionResult2))
		{
			$uniquePids = array_unique($suggestionResult2, SORT_REGULAR);
			$suggestionResult = array_values($uniquePids);
		}
	}
	else if($type=='location')
	{
		$where = array(
						'$or' => array(
						array(
						'area' => new MongoRegex("/strtolower($typeSearch)/i"),
						),
						array(
						'area' => new MongoRegex("/$typeSearch/i"),
						),
						)
					);
		$cursor = $collection->find($where,array('area'=>1));
		$searchResult =  iterator_to_array($cursor);
		$suggestionResult2 = array();
		if(!empty($searchResult))
		{
			$suggestionResult = array();
			foreach($searchResult  as $data)
			{
				if($data['area']!='')
				{
					$suggestionResult[] =   array('value'=>$data['area'],'label'=>$data['area']);
				}
				
			}
		}
		if(!empty($suggestionResult))
		{
			$uniquePids = array_unique($suggestionResult, SORT_REGULAR);
			$suggestionResult = array_values($uniquePids);
		}
	}
	else
	{
		$where = array(
						'$or' => array(
						array(
						'company' => new MongoRegex("/strtolower($typeSearch)/i"),
						),
						array(
						'company' => new MongoRegex("/$typeSearch/i"),
						),
						)
					);
		$cursor = $collection->find($where,array('company'=>1));
		$searchResult =  iterator_to_array($cursor); 
		if(!empty($searchResult))
		{
			foreach($searchResult as $data)
			{
				if($data['company']!='')
				{
					$suggestionResult[] = array('value'=>str_replace(',', '', $data['company']),'label'=>str_replace(',', '', $data['company']));
				}
			}
			$uniquePids = array_unique($suggestionResult, SORT_REGULAR);
		    $suggestionResult = array_values($uniquePids);
		}
	}
	$returnArr['data'] = $suggestionResult;
	$returnArr['status'] = 'success';
	echo json_encode($returnArr,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);exit;
}
?>


            
