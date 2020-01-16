<?php $user = current_user(); ?>
<!DOCTYPE html>
  <html lang="en">
    <head>
    <meta charset="UTF-8">
    <title><?php if (!empty($page_title))
           echo remove_junk($page_title);
            elseif(!empty($user))
           echo ucfirst($user['name']);
            else echo "Sistema simple de inventario";
			
			?>
    </title>
	<script src="/libs/js/jquery/jquery-2.1.4.min.js"></script>
 	<link rel="stylesheet" href="/libs/js/bootstrap/css/bootstrap.min.css">
<!--<link rel="stylesheet" href="/libs/css/datePicker.min.css" />  -->
	<link rel="stylesheet" href="/libs/css/main.css" />
    <link rel="stylesheet" href="/libs/css/font-awesome.min.css" />
    <link rel="stylesheet" href="/libs/css/sweetalert2.css" />
    <link rel="stylesheet" href="/libs/css/AdminLTE.css" />
	
	<link rel="stylesheet" href="/libs/js/bootstrap/css/style.css">	
	<script src="/libs/js/bootstrap/js/bootstrap.min.js"></script>
	<script src="/libs/js/bootstrap/dialog/js/bootstrap-dialog.js"></script>
	<link rel="stylesheet" type="text/css" href="/libs/js/bootstrap/dialog/css/bootstrap-dialog.css">
	
	
	<!-- <script src="/libs/js/jquery/jquery-ui/jquery-ui.min.js"></script>
   <link rel="stylesheet" href="/libs/js/jquery/jquery-ui/jquery-ui.min.css">-->
	<script type="text/javascript" src="/libs/js/angular/angular.js?ver=123"></script>
  </head>
  <body>
  <?php  if ($session->isUserLoggedIn(true)): ?>
    <header id="header">
	
	
      <div class="logo pull-left"> Sistema PDV  </div>
      <div class="header-content">
      <div class="header-date pull-left">
        <strong><?php echo date("d/m/Y  g:i a");?></strong>
      </div>
      <div class="pull-right clearfix">
        <ul class="info-menu list-inline list-unstyled">
          <li class="profile">
            <a href="#" data-toggle="dropdown" class="toggle" aria-expanded="false">
              <img src="/uploads/users/<?php echo $user['image'];?>" alt="user-image" class="img-circle img-inline">
              <span><?php echo remove_junk(ucfirst($user['name'])); ?> <i class="caret"></i></span>
            </a>
            <ul class="dropdown-menu">
              <li>
                  <a href="/profile.php?id=<?php echo (int)$user['id'];?>">
                      <i class="glyphicon glyphicon-user"></i>
                      Perfil
                  </a>
              </li>
             <li>
                 <a href="/edit_account.php" title="edit account">
                     <i class="glyphicon glyphicon-cog"></i>
                     Configuración
                 </a>
             </li>
             <li class="last">
                 <a href="/logout.php">
                     <i class="glyphicon glyphicon-off"></i>
                     Salir
                 </a>
             </li>
           </ul>
          </li>
        </ul>
      </div>
     </div>
    </header>
    <div class="sidebar">
      <?php if($user['user_level'] === 1): ?>
        <!-- admin menu -->
      <?php $docRoot = $_SERVER['DOCUMENT_ROOT']; 
 include_once($docRoot.'/layouts/admin_menu.php');?>

      <?php elseif($user['user_level'] === '2'): ?>
        <!-- Special user -->
      <?php $docRoot = $_SERVER['DOCUMENT_ROOT']; 
 include_once($docRoot.'/layouts/special_menu.php');?>

      <?php elseif($user['user_level'] === '3'): ?>
        <!-- User menu -->
      <?php $docRoot = $_SERVER['DOCUMENT_ROOT']; 
 include_once($docRoot.'/layouts/user_menu.php');?>

      <?php endif;?>

   </div>
<?php endif;?>

<div class="page">
  <div class="container-fluid">
