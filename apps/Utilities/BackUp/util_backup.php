<?php
  $page_title = 'Generacion de backUP';
  $docRoot = $_SERVER['DOCUMENT_ROOT'];
  require_once( $docRoot.'/includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>

<?php $docRoot = $_SERVER['DOCUMENT_ROOT']; 
 include_once($docRoot.'/layouts/header.php'); ?>
 

<div class="row">
<div class="col-md-12">
  <div class="panel panel-default">
	<div class="panel-heading clearfix">
	  <strong>
		<span class="glyphicon glyphicon-th"></span>
		<span>   Respaldo de la Base de Datos.</span>
	  </strong>
	</div>


        <!-- Main content -->
        <section class="content">
         <!-- Your Page Content Here -->
         <div class='col-md-12'>
        
         <div class='box-header'>
          <h3>Utilidad para generar respaldo de la Base de Datos.</h3>
         </div>
         <div class='box-footer'>
         <button type='button' class='btn btn-primary pull-right' onclick="respalda();" id='btn-genera'><i class='fa fa-thumbs-up'></i> Generar Respaldo.</button>

         </div>
         </div>
         <div class='col-md-6'>
         <div id='respuesta'></div>
         </div>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
  </div><!-- /.content-wrapper -->
</div><!-- /.content-wrapper -->
<script>
	function respalda(){
		$.ajax({
		   beforeSend: function(){
		   $("#btn-procede").prop("disabled", true)
		   $("#respuesta").html("Respaldando base de datos... <img src='Imagenes/loader.gif'></img>");
		   },
		   url: 'crea_respaldo.php',
		   type: 'POST',
		   data: null,
		   success: function(x){
			$("#respuesta").html(x);
		   },
		  error: function(jqXHR,estado,error){
		   $("#respuesta").html('Hubo un error al generar el respaldo!!!Reporte a soporte...'+'     '+estado +' '+error);
		  }
		});
	}
</script>

<?php $docRoot = $_SERVER['DOCUMENT_ROOT']; 
 include_once($docRoot.'/layouts/footer.php'); ?>
