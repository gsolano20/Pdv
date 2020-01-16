<?php
  $page_title = 'Agregar producto';
   $docRoot = $_SERVER['DOCUMENT_ROOT'];
  require_once($docRoot.'/includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $all_categories = find_all('categories');
  $all_photo = find_all('media');
?>
<?php
 if(isset($_POST['add_product'])){
   $req_fields = array('product-title','product-barcode','product-categorie','product-quantity','buying-price', 'saleing-price' );
   validate_fields($req_fields);
   if(empty($errors)){
     $p_name  = remove_junk($_POST['product-title']);
     $p_code  = remove_junk($_POST['product-barcode']);
     $p_cat   = remove_junk($_POST['product-categorie']);
     $p_qty   = remove_junk($_POST['product-quantity']);
     $p_buy   = remove_junk($_POST['buying-price']);
     $p_sale  = remove_junk($_POST['saleing-price']);
     $p_excento  = remove_junk($_POST['product-excento']);
     if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
       $media_id = '0';
     } else {
       $media_id = remove_junk($_POST['product-photo']);
     }
     $date    = make_date();
	 $DataBase = MySqlDb::GetInstance();
     $query  = "  INSERT INTO products (";
     $query .=" name,barcode,quantity,buy_price,sale_price,categorie_id,media_id,date,excento";
     $query .=") VALUES (";
     $query .=" '{$p_name}','{$p_code}', '{$p_qty}', '{$p_buy}', '{$p_sale}', '{$p_cat}', '{$media_id}', getdate(), '{$p_excento}'";
     $query .=")";
     $query .=" ";
     $DataBase->Execute($query);
	$json = $DataBase->getJsonData();
	$result = json_decode($json);
	if($result->{'success'}=="true"){
       $session->msg('s',"Producto agregado exitosamente. ");
       redirect('add_product.php', false);
     } else {
       $session->msg('d',' Lo siento, registro falló.');
       redirect('product.php', false);
     }

   } else{
     $session->msg("d", $errors);
     redirect('add_product.php',false);
   }

 }

?>
<?php $docRoot = $_SERVER['DOCUMENT_ROOT']; 
 include_once($docRoot.'/layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
  <div class="row">
  <div class="col-md-9">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Agregar producto</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="add_product.php" class="clearfix">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title" placeholder="Descripción">
               </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <select class="form-control" name="product-categorie">
                      <option value="">Selecciona una categoría</option>
                    <?php  foreach ($all_categories as $cat): ?>
                      <option value="<?php echo (int)$cat['id'] ?>">
                        <?php echo $cat['name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <select class="form-control" name="product-photo">
                      <option value="">Selecciona una imagen</option>
                    <?php  foreach ($all_photo as $photo): ?>
                      <option value="<?php echo (int)$photo['id'] ?>">
                        <?php echo $photo['file_name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-group">
               <div class="row">
                 <div class="col-md-4">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="fa fa-barcode"></i>
                     </span>
                     <input type="text" class="form-control" name="product-barcode" placeholder="BarCode">
                  </div>
                 </div>
				 <div class="col-md-4">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                     </span>
                     <input type="number" class="form-control" name="product-quantity" placeholder="Cantidad">
                  </div>
                 </div>
                 <div class="col-md-4">
                   <div class="input-group">
                     <span class="input-group-addon">
                       <i class="glyphicon glyphicon-usd"></i>
                     </span>
                     <input type="number" class="form-control" name="buying-price" placeholder="Precio de compra">
                     <span class="input-group-addon">.00</span>
                  </div>
                 </div>
                  <div class="col-md-4">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-usd"></i>
                      </span>
                      <input type="number" class="form-control" name="saleing-price" placeholder="Precio de venta">
                      <span class="input-group-addon">.00</span>
                   </div>
                  </div>
				  <div class="col-md-4">
                    <select class="form-control" name="product-excento">
                      <option value="">Selecciona Tipo Excento</option>
                    
                      <option value="1">Excento</option>
                      <option value="0">Paga Impuestos</option>
                    
                    </select>
                  </div>
               </div>
              </div>
              <button type="submit" name="add_product" class="btn btn-danger">Agregar producto</button>
          </form>
         </div>
        </div>
      </div>
    </div>
  </div>

<?php $docRoot = $_SERVER['DOCUMENT_ROOT']; 
 include_once($docRoot.'/layouts/footer.php'); ?>
