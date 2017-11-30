<?php 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: x-requested-with, Content-Type, origin, authorization, accept, client-security-token');
class listApi
{
	public function listing($cat,$filter,$page,$payment_type,$featureApp,$limit)
	{
		
		$where = array();
		$category = '';
		if(isset($filter['search_location']) && $filter['search_location']!='')
		{
			$where['search_location']	 = array('$in' => array($filter['search_location']));
		}
		else if(isset($filter['no_of_guest']) && $filter['no_of_guest']!='')
		{
			$where['no_of_guest'] =  $filter['no_of_guest'];
		}
			if($page!='')
			{
				$offset = ($page-1)*20;
			}
			else
			{
				$offset = 0;
			}
			if($featureApp=='1')
			{
				$where .= " AND xmlg = $featureApp";
			}
			$m = new MongoClient("mongodb://192.168.3.2:27017");
        	$db = $m->RPO_DataBase;
			$propertyList = $db->plisting->find($where)->skip($offset)->limit(20);
			$listArr = array_values(iterator_to_array($propertyList));
            if(!empty($listArr))
            {
				return $listArr;
			}
	}
}

$request = json_decode(file_get_contents('php://input'), true);
$responseData = array();
try{
	if (!empty($request['method'])) 
	{
		$objModel = new listApi();
		$method = $request['method'];
		switch ($method) 
		{
			case 'listing':
				$filter       		= isset($request['filter']) ? $request['filter'] : "";
				$page          		= isset($request['page']) ? $request['page'] : "";
				$limit              = isset($request['limit']) ? $request['limit'] : "20";
				$responseData       = $objModel->$method('',$filter,$page,'','',$limit);
			break;
			
		//	default: $responseData  = errorHandler('methodNotFound','wrong');
		}
	}
	else 
	{
		//$responseData = errorHandler('methodNotFound','empty');
	}
} 
catch (Exception $e) 
{
	$responseData = array('status'=>0,'msg'=>$e->getMessage());
}
echo json_encode($responseData,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
exit;

?>
