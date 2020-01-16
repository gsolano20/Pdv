<?php
class MySqlDb {
	private $DataBaseServer;
	private $DataBaseUser;
	private $DataBasePassword;
	private $DataBaseName;
	private $conexion;
	private $pagin = false;
	private $index;
	private $table;
	public $TotalRows = 0;
	private $recordSet;
	private $data;
	private $start;
	static $_instance;
	private $where = '';
	public $success = "true";
	public $error;
	public $showDateTime = false;
	public $json;
	public $keys;
	public $dateFormat = "m/d/Y h:i:s";
	public $sql;
	private function __construct(){		
		$this->SetConexion();
		$this->Conect();		
	}
	public static function GetInstance(){
		if (!(self::$_instance instanceof self)){
	   	self::$_instance=new self();
		}
		return self::$_instance;
	}
	private function SetConexion(){
		$this->DataBaseServer="localhost";
		$this->DataBaseUser="root";
		$this->DataBasePassword="toor";
		$this->DataBaseName="mydb";
	}
	private function Conect(){		
		// Try and connect to the database
		$this->conexion = mysqli_connect($this->DataBaseServer,$this->DataBaseUser,$this->DataBasePassword,$this->DataBaseName);
		if(!$this->conexion){
			$this->success = "false";
			$this->error = "Error Selecting Data Base | ".mysqli_connect_error();
		};
		mysqli_autocommit($this->conexion, true);
	}
	public function setStart($start){
		$this->start = $start;
	}
	public function getKeys(){
		return $this->keys;
	}
	public function roolRack(){
		odbc_rollback($this->conexion);
	}
	public function commit(){
		odbc_commit($this->conexion);
	}
	public function setPagin($pagin){
		$this->pagin = $pagin;
	}
	public function setIndex($index){
		$this->index = $index;
	}
	public function setTable($table){
		$this->table = $table;
	}
	public function setWhere($where){
		$this->where = $where;
	}
	public function getData(){
		return $this->data;
	}
	public function Execute($sql){		
		//echo $sql.'<br>';
		$this->sql = $sql;
		$this->recordSet = $this->conexion->query(utf8_decode($sql));
		if ($this->conexion->error !=0) {
			$this->success = "false";
			$this->error = $this->conexion->error;
			$this->error = str_replace("'","", $this->error);
			if($this->error ==""){
				$this->error = 'Error no espcificado';
				$this->conexion->rollback();				
			}			
		}else{			
			if( $this->conexion->field_count>0){
				$this->setData();
			} 
		}
	}
	public function setData(){		
		if ($this->pagin == true){
			$this->data = array();
			$i=0;
			$j=0;
			while( $row = $this->recordSet->fetch_array() ) {						
				$this->data[$i] = $row;
				$this->data[$i]["rowid"] = $i;
				$i++;			
			}
			//$this->TotalRows = $this->getTotals();
		}else{
			$this->data = array();
			$i=0;
			$j=0;		
			while( $row = $this->recordSet->fetch_array(MYSQLI_ASSOC) ) {
				//preguntar si start entonces ir de start + 100
				if($this->start == ""){				
					$this->data[$i] = $row;
					$this->data[$j]["rowid"] = $j;
				}else{
					//echo "start =".$this->start."<br>";
					if ($i >= $this->start){
						if ($j < 100){
							$this->data[$j] = $row;						
							$this->data[$j]["rowid"] = $j;
						}
						$j++;
					}
				}			
				$i++;			
			} 				
			$this->TotalRows = $i;
		}
	}
	public function getJsonData(){			
		$jsonArray = array();
		foreach ($this->data as $rs) {
			$jsonData = array();
			$keys = array();
			$i = 0;
			foreach ($rs as $key => $value) {								
				if (strlen($value) == 23){					
					if(strtotime($value)){
						//jaime
						if ($this->showDateTime == true){
							$value = date($this->dateFormat,strtotime($value));
							$value = str_replace("\EST", 'T', $value);
							$value = str_replace("\EDT", 'T', $value);							
						}else{
							$value = date("d/m/Y",strtotime($value));
						}
					}
				}
				$value = str_replace(",", "", $value);
				$value = str_replace('"', '', $value);
				$keys[] = $key;
				$jsonData[] = '"'.$key.'":"'.iconv('ISO-8859-1', 'UTF-8', $value).'"'; 
				
			}
			$jsonArray[] = "{".implode(",", $jsonData)."}";
		}		
		$this->json = implode(",", $jsonArray);
		$this->keys = $keys;		
		if($this->TotalRows ==0){
			//return "{total:0,data:[{'Error':'No results found for your query'}], success: true}";
			return '{"total":0,"data":[{"Error":"No results found for your query"}], "success": "true"}';
			//return "{total:0,data:'', success: true,'Error':'No results found for your query'}";
		}else{			
			return '{"total":'.$this->TotalRows.',"data":['.$this->json.'], "success": "true"}';
		}
	}
	public function getXmlDAta(){
		$xml = new SimpleXMLElement('<?xml version="1.0"?><root/>');
		array_walk_recursive($this->data, array ($xml, 'addChild'));
		return $xml->asXML();
	}	
	public function closeConextion(){
		$this->conexion->commit();		
		$this->conexion->close();		
	}	
	
}
?>