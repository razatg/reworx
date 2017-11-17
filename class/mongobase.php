<?php
$setApplication = getenv("APPLICATION_ENV");
define("APPLICATION",$setApplication);
/**
 * MongoBase Class
 * @date 2016-03-01
 * @author     KS
 * @package Snappy
 * @subpackage Model
 */
class MongoBase
{
    
    public static $_mongo = null;
    public static $_collection = null;
    public static $_collectionName = null;
    public static $db;
    
    
    protected $id = null;
    protected $document = null;
    
    
    
    /*************************************************************************************
     * Magic methods
     *************************************************************************************/
    
    /**
     * Constructor puts full object in $document variable and assigns $id
     * @param $document
     */
    public function __construct($document = null)
    {	
		//self::$db=$_db;
		if ($document != null && isset($document['_id'])) {
            $this->document = $document;
            $this->id       = $this->document['_id'];
            unset($this->document['_id']);
        } elseif ($document !== null) {
            $this->document = $document;
        } else {
            $this->document = array();
        }
    }
    
	 public function getDb()
    {
        return self::$_mongo;
    }
	/**
     * Return object ID
     */
    public function __toString()
    {
        return ucfirst(static::$_collectionName) . "Object ID:" . $this->id;
    }
    
    /**
     * Get values like an object
     * @param string $name
     */
    public function __get($name)
    {
        if ($name == "id" || $name == "_id") {
            return $this->id;
        }
        if (false !== strpos($name, '.')) {
            return $this->_getDotNotation($name, $this->document);
        }
        return isset($this->document[$name]) ? $this->document[$name] : null;
    }
    
    /**
     * Set values like an object
     * @param string $name
     * @param mixed $val
     */
    public function __set($name, $val)
    {
        if (false !== strpos($name, '.')) {
            return $this->_setDotNotation($name, $val, $this->document);
        }
        if ($val == null) {
            unset($this->document[$name]);
        }
        $this->document[$name] = $val;
    }
    
    /**
     * Check if variable is set in object
     * @param string $name
     */
    public function __isset($name)
    {
        return isset($this->document[$name]);
    }
    
    /**
     * Unset a variable in the object
     * @param $name
     */
    public function __unset($name)
    {
        unset($this->document[$name]);
    }
    
    /**
     * Allows use of the dot notation in the __get function
     * @param string $fields fields with dot notation
     * @param reference $current The current part of the array working in
     */
    protected function _getDotNotation($fields, &$current)
    {
        $i = strpos($fields, '.');
        if ($i !== false) {
            $field = substr($fields, 0, $i);
            if (!isset($current[$field])) {
                return null;
            }
            $current =& $current[$field];
            return $this->_getDotNotation(substr($fields, $i + 1), $current);
        } else {
            return isset($current[$fields]) ? $current[$fields] : null;
        }
    }
    
    /**
     * Allows use of the not notation in __set function
     * @param string $fields
     * @param mixed $value
     * @param reference $current
     */
    protected function _setDotNotation($fields, $value, &$current)
    {
        $i = strpos($fields, '.');
        if ($i !== false) {
            $field = substr($fields, 0, $i);
            if (!isset($current[$field])) {
                $current[$field] = array();
            }
            $current =& $current[$field];
            return $this->_setDotNotation(substr($fields, $i + 1), $value, $current);
        } else {
            $current[$fields] = $value;
        }
    }
    
    /***********************************************************************************
     * Object Methods
     ************************************************************************************/
    
    /**
     * Delete the object
     */
    public function delete($criteria)
    {
        static::init();
        if ($criteria) {            
            return static::$_collection->remove($criteria);
        }
    }
    /**
     * Save the object with all variables that have been set
     */
    public function save()
    {
        if ($this->id == null) {
            static::insert($this->document, true);
            $this->id = $this->document['_id'];
            unset($this->document['_id']);
            return true;
        } else {
            return static::update(array(
                "_id" => $this->id
            ), $this->document);
        }
    }
    /**
     * Do special updates to the object (incrementing, etc...)
     * @param mixed $data
     */
    public function specialUpdate($criteria, $modifier, $options)
    {
        return static::update(array(
            "_id" => $this->id
        ), $modifier, $options);
    }
    
    /*************************************************************************************
     * Static methods
     *************************************************************************************/
    
    /**
     * Connect to mongo...
     */
    protected static function connect($db="")
    {
        $dns = 'mongodb://52.73.212.217:27017';  
		$connection = new MongoClient($dns);
		self::$_mongo    = $connection->selectDB("bitnami_parse");
    }
    /**
     * Setup db connection and init mongo collection
     */
    public static function init()
    {
        if (self::$_mongo == null) {
            self::connect();
        }
    }
    
    /**
     * Load object by ID
     * @param $_id
     */
    public static function load($_id)
    {
        $object = static::findOne(array(
            "_id" => new MongoId($_id)
        ));
        if ($object === null) {
            return false;
        } else {
            return $object;
        }
    }
    
    /**
     * Find all records in a collection
     */
    public static function findAll()
    {
        return static::find();
    }
    /**
     * Get one record
     */
    public static function distinct($field,$conditionalArray = null, $fieldsArray = null, $sort = null)
    {
        $document = static::$_collection->distinct($field,$conditionalArray);
        //$object = new $className($document);
        return $document;
    }
    
    /**
     * Get one record
     */
    public static function findOne($conditionalArray = null, $fieldsArray = null, $sort = null)
    {
        $className = get_called_class();
        $document  = static::getCursor($conditionalArray, $fieldsArray, true);
        if ($document == null) {
            return null;
        }
        $object = new $className($document);
        return $object;
    }
    
    /**
     * Query the database for documents in the collection
     * @param array $conditionalArray 
     * @param array $fieldsArray 
     * @param array $sort
     * @param int $limit
     */
    public static function find($conditionalArray = null, $fieldsArray = null, $sort = null, $limit = null, $skip = null)
    {
        $cursor = static::getCursor($conditionalArray, $fieldsArray);
        if ($limit != null) {
            $cursor = $cursor->limit($limit);
        }
        if ($sort != null) {
            $cursor = $cursor->sort($sort);
        }
        if ($skip != null) {
            $cursor = $cursor->skip($skip);
        }
        $className   = get_called_class();
        $objectArray = array();
        foreach ($cursor as $document) {
            $objectArray[] = new $className($document);
        }
        return $objectArray;
    }
     /**
     * Query the database for documents in the collection
     * @param array $conditionalArray 
     * @param array $fieldsArray 
     * @param array $sort
     * @param int $limit
     */
    public static function findArray($conditionalArray = null, $fieldsArray = null, $sort = null, $limit = null, $skip = null)
    {
        $cursor = static::getCursor($conditionalArray, $fieldsArray);
        if ($limit != null) {
            $cursor = $cursor->limit($limit);
        }
        if ($sort != null) {
            $cursor = $cursor->sort($sort);
        }
        if ($skip != null) {
            $cursor = $cursor->skip($skip);
        }
        $cursor = @iterator_to_array($cursor);
        $objectArray = array();
        foreach ($cursor as $document) {
            $objectArray[] = $document;
        }
        return $objectArray;
    }
    /**
     * Count by query array
     * @param array $conditionalArray
     */
    public static function count($conditionalArray = null)
    {
        $cursor = static::getCursor($conditionalArray);
        return $cursor->count();
    }
    
    /**
     * Create cursor by query document
     * @param array $conditionalArray
     * @param array $fieldsArray
     */
    protected static function getCursor($conditionalArray = null, $fieldsArray = null, $one = false)
    {
        static::init();
        if ($conditionalArray == null)
            $conditionalArray = array();
        if ($fieldsArray == null)
            $fieldsArray = array();
        if ($one) {
            return static::$_collection->findOne($conditionalArray, $fieldsArray);
        }
        $cursor = static::$_collection->find($conditionalArray, $fieldsArray);
        return $cursor;
    }
    /**
     * Set document
     */
    public function setOptions($document = null)
    { 
		if($document !== null){
			if($document['_id']=='' || empty($document['_id'])) $document['_id']= (string) new MongoId();
			if ($document)
                $document['addedon'] = time();
                
            $this->document = $document;
        }
        else {
            $this->document = array();
        }
    }
    
    /**
     * 
     * Enter description here ...
     * @param array $data
     * @param bool $safe // Set true if you want to wait for database response...
     * @param bool $fsync
     */
    public static function insert($data, $safe = false, $fsync = false)
    {
        static::init();
        $options = array();
        if ($safe) {
            $options['w'] = true;
        }
        if ($fsync) {
            $options['fsync'] = true;
        }
       
        return static::$_collection->insert($data, $options);
    }
    
    /**
     * Do a batch insert into the collection
     * @param array $data
     */
    public static function batchInsert($data)
    {
        static::init();
        return static::$_collection->batchInsert($data);
    }
    
    public static function update($criteria, $update, $upsert = false, $multi = false)
    {
        static::init();
        $options = array();
        if ($upsert) {
            $options['upsert'] = true;
        }
        if ($multi) {
            $options['multiple'] = true;
        }
        if ($update)
            $update['updatedon'] = time();
        return static::$_collection->update($criteria, $update, $options);
    }
	
	public static function updateGroup($criteria, $update, $upsert = false, $multi = false)
    {
        static::init();
        $options = array();
        if ($upsert) {
            $options['upsert'] = true;
        }
        if ($multi) {
            $options['multiple'] = true;
        }
		return static::$_collection->update($criteria, $update, $options);
    }
    
     /**
     * Drop database
     */
    public static function dropDb()
    {
        static::init();
        return self::$_mongo->drop();
    }
    
	/**
     * Drop database
     */
    public static function dbStats($dbname=null)
    {
        static::init();
        $dbs=self::$_mongo->command(array('listDatabases' => 1));
        foreach ($dbs['databases'] as $db) {
            if ($db['name']==$dbname) {
                $return['size'] = (!$db['empty'] ? round($db['sizeOnDisk'] / 1000000) . 'mb' : 'empty') ;
            }
        }
       
        return $return;
        
        
    }
	
	/**
     * Drop database
     */
    public static function collection($collection)
    {   
	static::init();
	
    static::$_collectionName=$collection;
    $collectionName = static::$_collectionName;
        try {
            
            static::$_collection = self::$_mongo->selectCollection($collectionName);
            
        }
        catch (MongoCursorException $e) {
            //echo "error message: ".$e->getMessage()."\n";
        }
	}
	
	/* slect db */

	public static function db($db)
    {   
	 //if (self::$_mongo == null) {
            self::connect($db);
       // }
	}	
	public static function aggregate($criteria_aggregate=array())
    {
        static::init();
        $criteria = array();
        
        if (isset($criteria_aggregate['lookup'])) {
			$criteria[] =  array(
				'$lookup' => $criteria_aggregate['lookup']
			);           
        }
		
		if (isset($criteria_aggregate['project'])) {
			$project = $criteria_aggregate['project'];
			$criteria[] =  array(
				'$project' => $project
			);           
        }
		
		if (isset($criteria_aggregate['match'])) {
			$match = $criteria_aggregate['match'];
			if (isset($match['_id'])) {
				if (is_array($match['_id'])) {
					$condArr = array();
					foreach ($match['_id'] as $key => $val) {
						$condArr[$key] = ($val);
					}
					$match['_id'] = $condArr;
				} else {
					$match['_id'] = ($match['_id']);
				}
			}
			
            $criteria[] = array(
                    '$match' => $match
            );
        }
        
        if (isset($criteria_aggregate['sort'])) {
			$sort = $criteria_aggregate['sort'];
			$criteria[] =  array(
				'$sort' => $sort
			);           
        }
        if (isset($criteria_aggregate['limit'])) {
			$limit = $criteria_aggregate['limit'];
			$skip = $criteria_aggregate['skip'];
			$criteria[] =  array(
				'$limit' => $limit+$skip
			);           
        }
        if (isset($criteria_aggregate['skip'])) {
			$skip = $criteria_aggregate['skip'];
			$criteria[] =  array(
				'$skip' => $skip
			);           
        }
        if (isset($criteria_aggregate['group'])) {
			$group = $criteria_aggregate['group'];
			$criteria[] =  array(
				'$group' => $group
			);           
        }        
        $result = static::$_collection->aggregate($criteria);
        foreach ($result['result'] as $key => $val) {
			if(is_object($val['_id']))			
			$result['result'][$key]['_id'] = ($val['_id']);
		}
        return $result['result'];
    }
    
    public static function aggregate2($criteria=array())
	{
		static::init();
		$result = static::$_collection->aggregate($criteria);
		foreach ($result['result'] as $key => $val) {
			if(is_object($val['_id']))			
			$result['result'][$key]['_id'] = ($val['_id']);
		}
		return $result['result'];
	}

	public static function lookupMultiple($criteria_aggregate=array())
	{
		static::init();
		$criteria = array();
		
		if (isset($criteria_aggregate['lookup'])) {
			
			foreach($criteria_aggregate['lookup'] as $lookups)
			{
				$criteria[] =  array(
					'$lookup' => $lookups
				);
			}
		}
		if (isset($criteria_aggregate['geoNear'])) {
			$criteria[] =  array(
				'$geoNear' => $criteria_aggregate['geoNear']
			);           
		}
		if (isset($criteria_aggregate['project'])) {
			$project = $criteria_aggregate['project'];
			$criteria[] =  array(
				'$project' => $project
			);           
		}
		if (isset($criteria_aggregate['match'])) 
		{
			$match = $criteria_aggregate['match'];
			if (isset($match['_id'])) {
				if (is_array($match['_id'])) {
					$condArr = array();
					foreach ($match['_id'] as $key => $val) {
						$condArr[$key] = ($val);
					}
					$match['_id'] = $condArr;
				} else {
					$match['_id'] = ($match['_id']);
				}
			}
			
			$criteria[] = array(
					'$match' => $match
			);
		}		
		if (isset($criteria_aggregate['sort'])) {
			$sort = $criteria_aggregate['sort'];
			$criteria[] =  array(
				'$sort' => $sort
			);           
		}
		if (isset($criteria_aggregate['limit'])) {
			$limit = $criteria_aggregate['limit'];
			$skip = $criteria_aggregate['skip'];
			$criteria[] =  array(
				'$limit' => $limit+$skip
			);           
		}
		if (isset($criteria_aggregate['skip'])) {
			$skip = $criteria_aggregate['skip'];
			$criteria[] =  array(
				'$skip' => $skip
			);           
		}
		if (isset($criteria_aggregate['group'])) {
			$group = $criteria_aggregate['group'];
			$criteria[] =  array(
				'$group' => $group
			);           
		}		
		$result = static::$_collection->aggregate($criteria);
		foreach ($result['result'] as $key => $val) {
			if(is_object($val['_id']))			
			$result['result'][$key]['_id'] = ($val['_id']);
		}
		return $result['result'];
	}   
}
