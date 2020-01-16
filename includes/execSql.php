<?php
ini_set("display_errors", 0);
$docRoot = $_SERVER['DOCUMENT_ROOT'];
require_once($docRoot.'/includes/load.php');

if (isset($_POST["sql"])){
	$sql = $_POST["sql"];
}else{
	$sql = $_GET["sql"];
}
$sql = str_replace("||", "+", $sql);
if (isset($_POST["showtime"])){
	$showtime = $_POST["showtime"];
}else{
	if (isset($_GET["showtime"])){
	$showtime = $_GET["showtime"];
	}else{
		$showtime ="";	
	}
}

if (isset($_POST["dateFormat"])){
	$dateFormat = $_POST["dateFormat"];
}else{
	if(isset($_GET["dateFormat"])){
	$dateFormat = $_GET["dateFormat"];
	}else{
		$dateFormat ="";	
	}
}

//echo $sql."<br><br>";
$EpsDataBase = MySqlDb::GetInstance();
if($dateFormat != ""){
	$EpsDataBase->dateFormat = $dateFormat;
}
$sql = str_replace("\\", "", $sql);
$EpsDataBase->Execute($sql);
if ($EpsDataBase->success === "true"){
	if ($showtime != ""){
		$EpsDataBase->showDateTime = true;
	}
	$data = $EpsDataBase->getJsonData();
	echo $data;
}else{
	echo '{"success": "false","error":"'.$EpsDataBase->error.'"}';
}
$EpsDataBase->closeConextion();
?>