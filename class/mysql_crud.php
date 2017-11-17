<?php
include('class/config-ini.php');
class Database
{
	protected  $key = 'a2dc2a99e649f147bcabc5a99bea7d96';
	private $con = false; // Check to see if the connection is active
	private $result =''; // Any results from a query will be stored here
	Private $db_name = '';
	public function connect()
	{
		$db_host = "snappy.coehsve7icdk.us-east-1.rds.amazonaws.com";  // Read DB
		$db_user = "usrnmpieappy";
		$db_pass = 'Ab$AppYp!eaB$';
		$this->db_name = "appypie";
		#$db_host = '192.168.2.2';
		#$db_user = "appypie_db_ugr";
		#$db_pass = "8F2uCyuqP89YuFx6";
		#$this->db_name = "appypieml_db_local";
		$con = @mysql_connect($db_host, $db_user, $db_pass);
        if($con)
        {
			$seldb = @mysql_select_db($this->db_name, $con);
            if($seldb)
            {
				mysql_query("SET NAMES 'utf8'",$con);	
				return $con;
            }
		}
	}
		
	// Function to SELECT from the database
	public function select($con,$table, $rows = '*', $join = null, $where = null, $order = null, $limit = null, $offset = 0, $group = null)
	{
		$q = 'SELECT '.$rows.' FROM '.$table;
		if($join != null) 	$q .= ' LEFT JOIN '.$join;
		if($where != null) 	$q .= ' WHERE '.$where;
		if($group != null)	$q .= ' GROUP BY '.$group;
        if($order != null) 	$q .= ' ORDER BY '.$order;   
		if($limit != null) $q .= ' LIMIT '.$offset.','.$limit;
		//echo $q;exit;
       	$query = @mysql_query($q,$con);
		if($query){
			return $query; // Query was successful
		} else{
			$this->result= mysql_error();
			if($table=='sc_posts'){
				$dt = date("d-m-Y");
				$error = array();
				$error['sql'] = $q;
				$error['msg'] = mysql_error();
				error_log("\n ---Sql Data----\n".print_r($error,true), 3, "../log/sql-log-".$dt.".log");
			}
			return false; 	// No rows where returned
		}
	}
	// Function to insert into the database
    public function insert($con,$table,$params=array())
    {
		if($this->tableExists($con,$table)){
			$values = array();
			// apply sql injection			
			foreach( $params as $data ) {
				$values[] = "'" . mysql_real_escape_string($data) . "'";			
			}
			$values = implode(",", $values);
			
			$sql="INSERT INTO `".$table."` (`".implode("`, `",array_keys($params))."`) VALUES ($values)";
			if($ins = @mysql_query($sql,$con)){								
                return mysql_insert_id(); // The data has been inserted
            }else{	
				$this->result=mysql_error(); 
                return false; // The data has not been inserted
            }
        } else {	
			$this->result='table does not exist'; 
        	return false; // Table does not exist
        }
    }
	//Function to delete table or row(s) from database
    public function delete($con,$table,$where = null)
    {
		if($this->tableExists($con,$table)){
    	 	if($where != null){
				 $delete = 'DELETE FROM '.$table.' WHERE '.$where; // Create query to delete rows
				  if($del = @mysql_query($delete,$con)){
					return true; // The query exectued correctly
					}else{
						$this->result=mysql_error(); 
						return false; // The query did not execute correctly
					}
            }  
        }else{
			$this->result='table does not exist'; 
		     return false; // The table does not exist
        }
    }
	// Function to update row in database
    public function update($con,$table,$params=array(),$where)
    {
		if($this->tableExists($con,$table)){
    	    $args=array();
			foreach($params as $field=>$value){
				// Seperate each column out with it's corresponding value
				$args[]=$field.'="'.mysql_real_escape_string($value).'"';
			}
			// Create the query
			$sql='UPDATE '.$table.' SET '.implode(',',$args).' WHERE '.$where;
			if($query = @mysql_query($sql,$con)){
				return true; // Update has been successful
            }else{
            	$this->result=mysql_error(); 
			    return false; // Update has not been successful
            }
        }else{
			$this->result='table does not exist'; 
		      return false; // The table does not exist
        }
    }
	// Private function to check if table exists for use with queries
	private function tableExists($con,$table)
	{
		$tablesInDb = @mysql_query('SHOW TABLES FROM '.$this->db_name.' LIKE "'.$table.'"',$con);
        if($tablesInDb){
        	if(mysql_num_rows($tablesInDb)==1){
                return true; // The table exists
            }else{
				$this->result=$table." does not exist in this database";
            	 return false; // The table does not exist
            }
        }
    }
	// Public function to return the data to the user
    public function getResult()
    {
        $val = $this->result;
		return $val;
    }
	/* Execute Query */
	function query($sql,$con) 
	{
		if($query = @mysql_query($sql,$con)){
			return $query; // Update has been successful
		}else{
			$this->result=mysql_error(); 
			return false; // Update has not been successful
		}
	} 
	/* Total Rows returned by Query */
	function num_rows($sql) 
	{
		return(@mysql_num_rows($sql)); 
	} 
	/* Fetch Resultset as Object */
	function fetch_object($result) 
	{
		return(@mysql_fetch_object($result)); 
	} 
	/* Fetch Resultset as Array */
	function fetch_array($result) 
	{
		return(@mysql_fetch_assoc($result)); 
	} 
	function fetch_row($result) 
	{
		return(@mysql_fetch_row($result)); 
	} 
	function fetchRow($con,$table, $rows = '*',$where = null)
	{
		$q = 'SELECT '.$rows.' FROM '.$table;
		if($where != null) 	$q .= ' WHERE '.$where;
		//echo $q;exit;
		$query = @mysql_query($q,$con);
		if($query){
				return $this->fetch_object($query); // Query was successful
			} else{
				$this->result= mysql_error();
				return false; 	// No rows where returned
			}
	}
} 
