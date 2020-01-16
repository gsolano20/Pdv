<?php
$docRoot = getenv("DOCUMENT_ROOT");
require $docRoot.'/includes/MsSqlDataBase.php';
date_default_timezone_set("America/Chihuahua");
 
  $IdEncabezado=$_GET['IdEncabezado'];
  $fecha= date ("j/n/Y");
  $hora= date ("h:m:s");
  $supago=$_GET['supago'];
  $total=$_GET['total'];
  $cambio=$_GET['cambio'];
  $NumFact=$_GET['NumFact'];
  
	$DataBase = MySqlDb::GetInstance();
	$sql2="Select (SELECT valor FROM parameter WHERE Parametro='Compania') as Compania,
	(SELECT valor FROM parameter WHERE Parametro='TelCompania') as Telefono,
	(SELECT valor FROM parameter WHERE Parametro='CedJuridica') as CedJuridica";
	$DataBase->Execute($sql2);
	$Encabezado = $DataBase->getData();
  
	$sql="SELECT Cantidad, Precio, products.name, subtotal  FROM ventasdetalle
	inner join products on products.id= ventasdetalle.IdProducto
	WHERE ventasdetalle.IdEncabezado=".$IdEncabezado;
	$DataBase = MySqlDb::GetInstance();
	$DataBase->Execute($sql);
	$Details = $DataBase->getData();
	
	$sql2="select IdEncabezado,NumFact,Total,Fecha,impuestodeventa
		,Subtotal,Descuento from ventasencabezado where IdEncabezado=".$IdEncabezado;
	$DataBase = MySqlDb::GetInstance();
	$DataBase->Execute($sql2);
	$EncabezadoFact = $DataBase->getData();
	 
  $ar=fopen("ticket.txt","w") or die("Problemas en la creacion...");
  fputs($ar,"     ".$Encabezado[0]["Compania"]."  "."\n");
  fputs($ar,"CedJuridica: ".$Encabezado['0']["CedJuridica"]."  "."\n");
  fputs($ar,"     FACTURA # ".$EncabezadoFact[0]['NumFact']."   "."\n");
  fputs($ar,"Tel:".$Encabezado['0']["Telefono"].""."\n");
  fputs($ar,"Fecha: ".$EncabezadoFact[0]['Fecha']."\n");
  fputs($ar,"Hora: ".$hora."\n");
  fputs($ar,"---------------------"."\n");
   fputs($ar,"Cant. Art.  Monto"."\n");
  fputs($ar,"---------------------"."\n");
  
  for ($i = 0; $i < count($Details); $i++) {
  fputs($ar,$Details[$i]["Cantidad"]."|".substr($Details[$i]["name"],0,18)."\n");
  fputs($ar,"           ".$Details[$i]["subtotal"]."\n");
  }
   fputs($ar,"---------------------"."\n");
   fputs($ar,"SubTotal  : ".$EncabezadoFact[0]['Subtotal']."\n");
  fputs($ar,"Descuento  : ".$EncabezadoFact[0]['Descuento']."\n");
  fputs($ar,"I.V  : ".$EncabezadoFact[0]['impuestodeventa']."\n");
  fputs($ar,"Total  : ".$EncabezadoFact[0]['Total']."\n");
  fputs($ar,"Su Pago: ".$supago."\n");
  fputs($ar,"Cambio : ".$cambio."\n");
 
  fclose($ar);
  echo '{"success": "true","window":"window.open(\"http://localhost:82/apps/ventas/Puntodeventa/ticket.txt\")"}';

 ?>