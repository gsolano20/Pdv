<?php
ini_set("display_errors", 0); 
 $docRoot = $_SERVER['DOCUMENT_ROOT'];
  require_once($docRoot.'/includes/load.php');

/*--------------------------------------------------------------*/
/* Function for find all database table rows by table name
/*--------------------------------------------------------------*/
function find_all($table) {
	global $db;
    return find_by_sql("SELECT * FROM ".$table);
  
}
/*--------------------------------------------------------------*/
/* Function for Perform queries
/*--------------------------------------------------------------*/
function find_by_sql($sql){
	$DataBase = MySqlDb::GetInstance();
	$DataBase->Execute($sql);
	$result = $DataBase->getData();

 return $result;
}
function find_by_JSONsql($sql){
	$DataBase = MySqlDb::GetInstance();
	$DataBase->Execute($sql);
	$json = $DataBase->getJsonData();
	$result = json_decode($json);

 return $result;
}
/*--------------------------------------------------------------*/
/*  Function for Find data from table by id
/*--------------------------------------------------------------*/
function find_by_id($table,$id){
  $id = (int)$id;
		$DataBase = MySqlDb::GetInstance();
		$sql = "SELECT * FROM ".$table." WHERE id=".$id."  ";
		$DataBase->Execute($sql);
		$result = $DataBase->getData();
          if($result )
            return $result[0];
          else
            return null;
    
}
/*--------------------------------------------------------------*/
/* Function for Delete data from table by id
/*--------------------------------------------------------------*/
function delete_by_id($table,$id){
  
 $DataBase = MySqlDb::GetInstance();
    $sql = "DELETE FROM ".$table;
    $sql .= " WHERE id=".$id;
    $sql .= "  ";
    $DataBase->Execute($sql);
		$result = $DataBase->getJsonData();
          if(!$result){
            return false;
          }else{
            return true;
		  }
}
/*--------------------------------------------------------------*/
/* Function for Count id  By table name
/*--------------------------------------------------------------*/

function count_by_id($table){
 
	$DataBase = MySqlDb::GetInstance();
    $sql    = "SELECT COUNT(id) AS total FROM ".$table;
	$DataBase->Execute($sql);
	$result = $DataBase->getData();
     return $result[0];
  
}
/*--------------------------------------------------------------*/
/* Determine if database table exists
/*--------------------------------------------------------------*/

 /*--------------------------------------------------------------*/
 /* Login with the data provided in $_POST,
 /* coming from the login form.
/*--------------------------------------------------------------*/
  function authenticate($username, $password) {
	$DataBase = MySqlDb::GetInstance();
    $sql  = "SELECT id,username,password,user_level FROM users WHERE username ='". $username."'";
	$DataBase->Execute($sql);
	$result = $DataBase->getData();
      if($result[0]['password'] === sha1($password) ){
        return $result[0]['id'];
      }else{
		return false;
	  }
  }

  /*--------------------------------------------------------------*/
  /* Find current log in user by session id
  /*--------------------------------------------------------------*/
  function current_user(){
      static $current_user;
      global $db;
      if(!$current_user){
         if(isset($_SESSION['user_id'])):
             $user_id = intval($_SESSION['user_id']);
             $current_user = find_by_id('users',$user_id);
        endif;
      }
    return $current_user;
  }
  /*--------------------------------------------------------------*/
  /* Find all user by
  /* Joining users table and user gropus table
  /*--------------------------------------------------------------*/
  function find_all_user(){
      global $db;
      $results = array();
      $sql = "SELECT u.id,u.name,u.username,u.user_level,u.status,u.last_login,";
      $sql .="g.group_name ";
      $sql .="FROM users u ";
      $sql .="LEFT JOIN user_groups g ";
      $sql .="ON g.group_level=u.user_level ORDER BY u.name ASC";
      $result = find_by_sql($sql);
      return $result;
  }
  /*--------------------------------------------------------------*/
  /* Function to update the last log in of a user
  /*--------------------------------------------------------------*/

 function updateLastLogIn($user_id)	{
	$DataBase = MySqlDb::GetInstance();
    $date = make_date();
    $sql = "UPDATE users SET last_login='{$date}' WHERE id ='{$user_id}'  ";
	$DataBase->Execute($sql);
	$result = $DataBase->getData();

    return ($result);
	}

  /*--------------------------------------------------------------*/
  /* Find all Group name
  /*--------------------------------------------------------------*/
  function find_by_groupName($val)  {
    $DataBase = MySqlDb::GetInstance();
    $sql = "SELECT group_name FROM user_groups WHERE group_name = ".$val."   ";
    $DataBase->Execute($sql);
	$result = $DataBase->getData();
	if(!$result[0]){
		return false;
	}else{
		return true;
	}
  }
  /*--------------------------------------------------------------*/
  /* Find group level
  /*--------------------------------------------------------------*/
  function find_by_groupLevel($level)  {
	$DataBase = MySqlDb::GetInstance();
    $sql = "SELECT group_level FROM user_groups WHERE group_level = ".$level."   ";    
	$DataBase->Execute($sql);
	$result = $DataBase->getData();
	if(!$result[0]){
		return false;
	}else{
		return true;
	}
   
  }
  /*--------------------------------------------------------------*/
  /* Function for cheaking which user level has access to page
  /*--------------------------------------------------------------*/
   function page_require_level($require_level){
     global $session;
     $current_user = current_user();
     $login_level = find_by_groupLevel($current_user['user_level']);
     //if user not login
     if (!$session->isUserLoggedIn(true)):
            $session->msg('d','Por favor Iniciar sesión...');
            redirect('index.php', false);
      //if Group status Deactive
     elseif($login_level['group_status'] === '0'):
           $session->msg('d','Este nivel de usaurio esta inactivo!');
           redirect('home.php',false);
      //cheackin log in User level and Require level is Less than or equal to
     elseif($current_user['user_level'] <= (int)$require_level):
              return true;
      else:
            $session->msg("d", "¡Lo siento!  no tienes permiso para ver la página.");
            redirect('home.php', false);
        endif;

     }
   /*--------------------------------------------------------------*/
   /* Function for Finding all product name
   /* JOIN with categorie  and media database table
   /*--------------------------------------------------------------*/
  function join_product_table(){
     global $db;
     $sql  =" SELECT p.id,p.name,p.quantity,p.buy_price,p.sale_price,p.media_id,p.date,c.name";
    $sql  .=" AS categorie,m.file_name AS image, case when excento =0 then 'PagaImpuesto' else 'Excemto' end as excento";
    $sql  .=" FROM products p";
    $sql  .=" LEFT JOIN categories c ON c.id = p.categorie_id";
    $sql  .=" LEFT JOIN media m ON m.id = p.media_id";
    $sql  .=" ORDER BY p.id ASC";
    return find_by_sql($sql);

   }
  /*--------------------------------------------------------------*/
  /* Function for Finding all product name
  /* Request coming from ajax.php for auto suggest
  /*--------------------------------------------------------------*/

   function find_product_by_title($product_name){

     $p_name = remove_junk($product_name);
     $sql = "SELECT name FROM products WHERE name like '%$p_name%' LIMIT 5";
     $result = find_by_sql($sql);
     return $result;
   }

  /*--------------------------------------------------------------*/
  /* Function for Finding all product info by product title
  /* Request coming from ajax.php
  /*--------------------------------------------------------------*/
  function find_all_product_info_by_title($title){
    global $db;
    $sql  = "SELECT * FROM products ";
    $sql .= " WHERE name ='{$title}'";
    $sql .="  ";
    return find_by_sql($sql);
  }

  /*--------------------------------------------------------------*/
  /* Function for Update product quantity
  /*--------------------------------------------------------------*/
  function update_product_qty($qty,$p_id){
    $DataBase = MySqlDb::GetInstance();
    $qty = (int) $qty;
    $id  = (int)$p_id;
    $sql = "UPDATE products SET quantity=quantity -'{$qty}' WHERE id = '{$id}'";
    $DataBase->Execute($sql);
	$result = $DataBase->getJsonData();
	if(!$result){
	return false;
	}else{
	return true;
	}
  }
  /*--------------------------------------------------------------*/
  /* Function for Display Recent product Added
  /*--------------------------------------------------------------*/
 function find_recent_product_added($limit){
   global $db;
   $sql   = " SELECT  top ".$limit." p.id,p.name,p.sale_price,p.media_id,c.name AS categorie,";
   $sql  .= "m.file_name AS image FROM products p";
   $sql  .= " LEFT JOIN categories c ON c.id = p.categorie_id";
   $sql  .= " LEFT JOIN media m ON m.id = p.media_id";
   $sql  .= " ORDER BY p.id DESC ";
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for Find Highest saleing Product
 /*--------------------------------------------------------------*/
 function find_higest_saleing_product($limit){
global $db;
   $sql  = "SELECT  top ".$limit."  p.name, COUNT(s.product_id) AS totalSold, SUM(s.qty) AS totalQty";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN products p ON p.id = s.product_id ";
   $sql .= " GROUP BY s.product_id ,p.name";
   $sql .= " ORDER BY SUM(s.qty) DESC";
	return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for find all sales
 /*--------------------------------------------------------------*/
 function find_all_sale(){
   global $db;
   $sql  = "SELECT s.id,s.qty,s.price,s.date,p.name";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN products p ON s.product_id = p.id";
   $sql .= " ORDER BY s.date DESC";
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for Display Recent sale
 /*--------------------------------------------------------------*/
function find_recent_sale_added($limit){
  global $db;
  $sql  = "SELECT top ".$limit." s.id,s.qty,s.price,s.date,p.name";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " ORDER BY s.date DESC ";
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate sales report by two dates
/*--------------------------------------------------------------*/
function find_sale_by_dates($start_date,$end_date){

  $start_date  = date("Y-m-d", strtotime($start_date));
  $end_date    = date("Y-m-d", strtotime($end_date));
  $sql  = "SELECT s.date, p.name,p.sale_price,p.buy_price,";
  $sql .= "COUNT(s.product_id) AS total_records,";
  $sql .= "SUM(s.qty) AS total_sales,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price,";
  $sql .= "SUM(p.buy_price * s.qty) AS total_buying_price ";
  $sql .= "FROM sales s ";
  $sql .= "LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE s.date BETWEEN '{$start_date}' AND '{$end_date}'";
  $sql .= " GROUP BY s.date,p.name,p.sale_price,p.buy_price";
  $sql .= " ORDER BY s.date DESC";
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate Daily sales report
/*--------------------------------------------------------------*/
function  dailySales($year,$month){
  global $db;
  $sql  = "SELECT s.qty, s.date,p.name, SUM(p.sale_price * s.qty) AS total_saleing_price 
			FROM sales s LEFT JOIN products p ON s.product_id = p.id 
			WHERE YEAR(s.date) ='{$year}' and MONTH(s.date)='{$month}'
			GROUP BY Day(s.date),s.product_id ,s.qty,s.date,p.name";
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate Monthly sales report
/*--------------------------------------------------------------*/
function  monthlySales($year){
  global $db;
  $sql  = "SELECT s.qty,";
  $sql .= " MONTH(s.date) AS date,p.name,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE year(s.date) = '{$year}'";
  $sql .= " GROUP BY MONTH(s.date),s.product_id,s.qty,p.name";
  $sql .= " ORDER BY MONTH(s.date) ASC";
  return find_by_sql($sql);
}


	

?>
