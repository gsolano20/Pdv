
<?php
// -----------------------------------------------------------------------
// DEFINE SEPERATOR ALIASES
// -----------------------------------------------------------------------
  $docRoot = $_SERVER['DOCUMENT_ROOT'];
define("URL_SEPARATOR", '/');

define("DS", DIRECTORY_SEPARATOR);

// -----------------------------------------------------------------------
// DEFINE ROOT PATHS
// -----------------------------------------------------------------------
defined('SITE_ROOT')? null: define('SITE_ROOT', realpath(dirname(__FILE__)));
define("LIB_PATH_INC", SITE_ROOT.DS);


require_once($docRoot.'/includes/config.php');
require_once($docRoot.'/includes/functions.php');
require_once($docRoot.'/includes/session.php');
require_once($docRoot.'/includes/upload.php');
//require_once($docRoot.'/includes/database2.php');
require_once($docRoot.'/includes/sql.php');
//require_once($docRoot.'/includes/MySqlDb.php');
require_once($docRoot.'/includes/MsSqlDataBase.php');

?>
