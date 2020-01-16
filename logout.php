<?php
  $docRoot = $_SERVER['DOCUMENT_ROOT']; 
 require_once($docRoot.'/includes/load.php');
  if(!$session->logout()) {redirect("index.php");}
?>
