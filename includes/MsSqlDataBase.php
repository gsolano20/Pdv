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
		//$this->DataBaseServer="192.168.1.15";
		$this->DataBaseServer="jaimebrenes.net";
		//$this->DataBaseServer="IT101\SQLEXPRESS";
		$this->DataBaseUser="sa";
		$this->DataBasePassword="Nonpass!8";
		$this->DataBaseName="epsLgomez";
	}
	private function Conect(){		
		//$this->conexion = odbc_connect("Driver={SQL Server Native Client 11.0};Server=$this->DataBaseServer;Database=$this->DataBaseName;", $this->DataBaseUser, $this->DataBasePassword);
		//$this->conexion = odbc_connect("Driver={SQL Server};Server=$this->DataBaseServer;Database=$this->DataBaseName;", $this->DataBaseUser, $this->DataBasePassword);	
		$conOpt = array(
			//"Database"=>"transbo2",
			"Database"=>"test",
			//"Database"=>"transbo2epsXBO",
			"UID"=> "query",
			"PWD"=>"query",
			"ReturnDatesAsStrings"=>true
		);
		$this->conexion = sqlsrv_connect("IBIT-07\SQLEXPRESS",$conOpt);
		//$this->conexion = sqlsrv_connect("IT-09\SQLEXPRESS",$conOpt1);
		
		if(!$this->conexion){
			$this->success = "false";
			$this->error = "Error Selecting Data Base";
		};		
		
		//odbc_autocommit($this->conexion, FALSE);
	}
	public function setStart($start){
		$this->start = $start;
	}
	public function getKeys(){
		return $this->keys;
	}
	public function roolRack(){
		//odbc_rollback($this->conexion);
		sqlsrv_rollback($this->conexion);
	}
	public function commit(){
		//odbc_commit($this->conexion);
		sqlsrv_commit($this->conexion);
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
	private function getTotals(){
		$sql = "select COUNT(".$this->index.") as total from ".$this->table;
		if($this->where != ''){
			$sql  = $sql .' where '.$this->where;
		}
		//$this->recordSet = odbc_exec($this->conexion,utf8_decode($sql));
		$this->recordSet  = sqlsrv_query($this->conexion,utf8_decode($sql));
		//$arrTotal = odbc_fetch_array($this->recordSet);
		$arrTotal = sqlsrv_fetch_array($this->recordSet);			
		return $arrTotal['total'];
		//print_r ($arrTotal);
		//return 100;
	}
	public function Execute($sql){	
		sqlsrv_configure("WarningsReturnAsErrors", 0);
		$this->sql = $sql;
		//echo $sql;
		$params = Array();
		//$options = array("scrollable" => SQLSRV_CURSOR_KEYSET);
		$options = array("scrollable" => SQLSRV_CURSOR_FORWARD);
		$this->recordSet = sqlsrv_query( $this->conexion, utf8_decode($sql),$params,$options);
		if ($this->recordSet === false) {			
			$errors = sqlsrv_errors();
			/*
			echo "<pre>";
			print_r($errors);
			echo "</pre>";
			*/
			if( ($errors ) != null){		
				 if ($errors[0]["code"] != -14){
					$this->success = "false";
					$this->error = "";
					
					//$this->error = $this->error + "SQLSTATE: ".$errors[0]['SQLSTATE']."\n";
					//$this->error = $this->error + "code: ".$errors[0]['code']."\n";
					$this->error = $errors[0]['message'];
					$this->error = str_replace("[Microsoft][ODBC Driver 11 for SQL Server][SQL Server]","", $this->error);
					/*
					foreach( $errors as $error){
						
						$this->error = $this->error + "SQLSTATE: ".$error['SQLSTATE']."\n";
						$this->error = $this->error + "code: ".$error['code']."\n";
						$this->error = $this->error + "message: ".$error['message']."\n";
					 }*/
					 sqlsrv_rollback($this->conexion);
					 
				 }else{
					 //echo "No hay error";
					 $this->success = "true";
				 }				 
			}
		}else{
			$this->data = "";
			$this->setData();
		}
	}	
	public function setData(){		
		if ($this->pagin == true){
			$this->data = array();
			$i=0;
			$j=0;		
			//while( $row = odbc_fetch_array($this->recordSet) ) {
				
			while( $row = sqlsrv_fetch_array($this->recordSet) ) {
				$this->data[$i] = $row;
				$this->data[$i]["rowid"] = $i;
				$i++;			
			}
			$this->TotalRows = $this->getTotals();
		}else{
			$this->data = array();
			$i=0;
			$j=0;
			//while( $row = sqlsrv_fetch_array($this->recordSet) ) {
			while( $row = sqlsrv_fetch_array($this->recordSet,SQLSRV_FETCH_ASSOC) ) {
				//preguntar si start entonces ir de start + 100
				//echo "<pre>";			
				/*foreach ($row as &$value) {
					if (get_class($value) == "DateTime"){
						//print_r($value);
						$value = $value->format("Y-m-d H:i:s");										
					}					
				}*/
				//echo "</pre>";
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
	public function getData(){
		//echo "<pre>";
		//echo print_r($this->data);
		//echo "</pre>";
		
		return $this->data;
	}
	public function getJson(){
		return '['.$this->json.']';
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
		//odbc_commit($this->conexion);
		sqlsrv_commit($this->conexion);
		//odbc_close($this->conexion);
		sqlsrv_close($this->conexion);
	}
}
?>