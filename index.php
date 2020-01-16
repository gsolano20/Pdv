<?php
  ob_start();
  $docRoot = $_SERVER['DOCUMENT_ROOT']; 
 require_once($docRoot.'/includes/load.php');
  if($session->isUserLoggedIn(true)) { redirect('home.php', false);}
?>
<?php $docRoot = $_SERVER['DOCUMENT_ROOT']; 
 include_once($docRoot.'/layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
       <h1>Bienvenido</h1>
       <p>Iniciar sesión </p>
     </div>
     <?php echo display_msg($msg); ?>
      <form method="post" action="auth.php" class="clearfix">
        <div class="form-group">
              <label for="username" class="control-label">Usario</label>
              <input type="name" class="form-control" name="username" placeholder="Usario">
        </div>
        <div class="form-group">
            <label for="Password" class="control-label">Contraseña</label>
            <input type="password" name= "password" class="form-control" placeholder="Contraseña">
        </div>
        <div class="form-group">
                <button type="submit" class="btn btn-info  pull-right">Entrar</button>
        </div>
    </form>
</div>
<?php $docRoot = $_SERVER['DOCUMENT_ROOT']; 
 include_once($docRoot.'/layouts/footer.php'); ?>
