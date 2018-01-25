<?php
ini_set('display_errors',1);
include_once('../config-ini.php');
$returnArr = array('status'=>'failure','data'=>'','totalCount'=>0);
$arrValues = json_decode(file_get_contents('php://input'), true);
$page = isset($arrValues['page'])?trim($arrValues['page']):"1";
$where = array();
$position = isset($arrValues['position'])?explode(',',rtrim(trim($arrValues['position']),",")):"";
$company = isset($arrValues['company'])?explode(',',rtrim(trim($arrValues['company']),",")):"";
$location = isset($arrValues['location'])?$arrValues['location']:"";
$total_experience = isset($arrValues['total_experience'])?$arrValues['total_experience']:"";
$searchStringArr = array();
$db = connect();
$isSearch  = false;
$collection = $db->profile;
$offset = ($page*10);
if(!empty($_SESSION['member']['cId']))
{
	$cId = $_SESSION['member']['cId'];
	$where['referrer_c_id']	 = array('$in' => array($cId));
}
if(!empty($position))
{
	$tmpTitle = array();
	$tmpFeature = array();
	$summaryArr = array();
	$experienceArr = array();
	$keywordsList = '';
    if(count($position)>1)
	{
		foreach($position as $q) 
		{
		   array_push($searchStringArr,$q);
		   $tmpTitle[] = array('title'=>array('$regex'=>$q,'$options'=>'i'));
		   $tmpFeature[] = array('featured_skiils'=>array('$regex'=>$q,'$options'=>'i'));
		   $summaryArr[] = array('summary'=>array('$regex'=>$q,'$options'=>'i'));
		   $experienceArr[] = array('experience'=>array('designation'=>array('$regex'=>$q,'$options'=>'i')));
		   $keywordsList.= $q.' ';
		}
		$where['$text'] = array('$search'=>$keywordsList);
	}
	else
	{
		$keywordsList = '"'.$position[0].'"'; 
		$where['$text'] = array('$search'=>$keywordsList);
	}
	//$where['$or'] = array(array('$or'=>$tmpTitle),array('$or'=>$tmpFeature),array('$or'=>$summaryArr),array('$or'=>$experienceArr));
	//$where['$or'] = $tmpFeature;
	$isSearch  = true;
}

if(!empty($company))
{
		$tmp = array();
		foreach ($company as $q) 
		{
			$companyName = trim($q);
			array_push($searchStringArr,$q);
			$tmp[] = new MongoRegex("/$companyName/i");
		}
		$where['company'] = array('$in'=>$tmp);
	$isSearch  = true;	
}
if(!empty($location))
{
   array_push($searchStringArr,$q);
  $where['area']	 = new MongoRegex("/$location/i");
  $isSearch  = true;
}
if(!empty($total_experience) && $total_experience!='Select Experience')
{
  if($total_experience<10)
  {
	 $maxExp  = $total_experience+3; 
  }
  else
  {
	  $maxExp  = $total_experience+5; 
  }	
  $where['total_experience']	 = array('$gte'=>$total_experience,'$lte'=>$maxExp);
  $isSearch  = true;
}
//print_r(json_encode($where));exit;
if(!empty($where))
{
	$sortArr = array("score"=>array('$meta'=>"textScore"));
	$cursorCount = $collection->count($where);
	$cursor = $collection->find($where,$sortArr)->sort($sortArr)->skip($offset)->limit(10);
	$searchResult =  iterator_to_array($cursor);
	if(!empty($searchResult))
	{
		$dataListArr = $db->recruitershortlist->findOne(array('cId'=>$cId));
		$dataList = array();
		$referCount = 0;
		$isMarkGood = 0;
		foreach($searchResult  as $data)
		{
			$referCount = $db->employeeReferData->count(array('referalUIDList.UID'=>(int)$data['UID']));
			$data['isSortlisted'] = $referCount;
			$isMarkGood = $db->employeeshortlist->count(array('UID'=>(int)$data['UID']));
			$data['isMarkGood'] = $isMarkGood;
			$searchString = serialize($data);
			$i = 0; 
			if(!empty($position))
            {
				$data['title']	            = beliefmedia_highlights($data['title'], implode(',',$position), $case = false);
				$data['featured_skiils']	= beliefmedia_highlights(implode(', ',$data['featured_skiils']),implode(',',$position),$case = false);
				$data['summary']	        = beliefmedia_highlights($data['summary'],implode(',',$position),$case = false);
				if(!empty($data['experience']))
				{
					$expStr = '';
					foreach($data['experience'] as $exp)
					{
						$expStr .= $exp['designation'].', ';
					}
				 $data['experience']	        = beliefmedia_highlights(rtrim(trim($expStr),','), implode(',',$position), $case = false);
				} 
			}
			if(!empty($company))
            {
				$data['company']	        = beliefmedia_highlights($data['company'],implode(',',$company),$case = false);
			}
			if(!empty($location))
			{
				$data['area']	            = beliefmedia_highlights($data['area'],$location,$case = false);
			}
		    $data['sortorder']	= $i;
			$parentUidList = $data['parentUID'];
			$connectedProfiles = $db->employee->find(array('UID'=>array('$in' =>$parentUidList)),array('UID','first_name','last_name','position','company'));
			if(!empty($connectedProfiles))
			{
				$data['connectedUsers'] = array_values(iterator_to_array($connectedProfiles));
			}
			$data['IsEdit']	= false;
			if(in_array($data['UID'],$dataListArr['UIDList']))
			{
			  $data['IsEdit']	= true;
			}
			if(!file_exists(ANGULAR_ABSOLUTE_PATH.$data['pic_phy']))
			{
				$data['pic_phy']  = 'newui/images/user.png';
			}
			
			$dataList[] = $data;
		}
		//function sortByOrder($a, $b) {
		//	return $b['sortorder'] - $a['sortorder'];
		//}
      //  usort($dataList, 'sortByOrder');
		$returnArr['data'] = $dataList;
		$returnArr['status'] = 'success';
		$returnArr['isSearch'] = $isSearch;
		$returnArr['totalCount'] = $cursorCount;
	}
}
echo json_encode($returnArr,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);exit;
function beliefmedia_highlights($text, $words, $case = false) 
{ 
	 $words = trim($words);
	 $words_array = explode(',', $words);
	 $regex = ($case !== true) ? '/\b(' . implode('|', array_map('preg_quote', $words_array)) . ')\b/i' : '/\b(' . implode('|', array_map('preg_quote', $words_array)) . ')\b/';
	 foreach($words_array as $word) 
	 { 
		  if(strlen(trim($word)) != 0) 
		   $text = preg_replace($regex, '<font style="background: yellow";>$1</font>', $text);
	  } 
	  return $text;
} 
?>       
