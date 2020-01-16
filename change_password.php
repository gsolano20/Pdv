<?php
  $page_title = 'Cambiar contraseña';
  $docRoot = $_SERVER['DOCUMENT_ROOT'];
  require_once($docRoot.'/includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
?>
<?php $user = current_user(); ?>
<?php
  if(isset($_POST['update'])){

    $req_fields = array('new-password','old-password','id' );
    validate_fields($req_fields);

    if(empty($errors)){

             if(sha1($_POST['old-password']) !== current_user()['password'] ){
               $session->msg('d', "Tu antigua contraseña no coincide");
               redirect('change_password.php',false);
             }

            $id = (int)$_POST['id'];
			$DataBase = MySqlDb::GetInstance();
            $new = remove_junk(sha1($_POST['new-password']));
            $sql = "UPDATE users SET password ='{$new}' WHERE id='{$id}'";
            $DataBase->Execute($sql);
			$json = $DataBase->getJsonData();
			$result = json_decode($json);
			if($result->{'success'}=="true"){
                  $session->logout();
                  $session->msg('s',"Inicia sesión con tu nueva contraseña.");
                  redirect('index.php', false);
                else:
                  $session->msg('d',' Lo siento, actualización falló.');
                  redirect('change_password.php', false);
                endif;
    } else {
      $session->msg("d", $errors);
      redirect('change_password.php',false);
    }
  }
?>
<?php $docRoot = $_SERVER['DOCUMENT_ROOT']; 
 include_once($docRoot.'/layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
       <h3>Cambiar contraseña</h3>
     </div>
     <?php echo display_msg($msg); ?>
      <form method="post" action="change_password.php" class="clearfix">
        <div class="form-group">
              <label for="newPassword" class="control-label">Nueva contraseña</label>
              <input type="password" class="form-control" name="new-password" placeholder="Nueva contraseña">
        </div>
        <div class="form-group">
              <label for="oldPassword" class="control-label">Antigua contraseña</label>
              <input type="password" class="form-control" name="old-password" placeholder="Antigua contraseña">
        </div>
        <div class="form-group clearfix">
               <input type="hidden" name="id" value="<?php echo (int)$user['id'];?>">
                <button type="submit" name="update" class="btn btn-info">Cambiar</button>
        </div>
    </form>
</div>
<?php $docRoot = $_SERVER['DOCUMENT_ROOT']; 
 include_once($docRoot.'/layouts/footer.php'); ?>
