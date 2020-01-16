<?php
  $page_title = 'Configuracion de parametros';
  $docRoot = $_SERVER['DOCUMENT_ROOT'];
  require_once( $docRoot.'/includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>

<?php $docRoot = $_SERVER['DOCUMENT_ROOT']; 
 include_once($docRoot.'/layouts/header.php'); ?>

<div class="row"  ng-app="configapp" >
<div class="col-md-12"  ng-controller="configCtrl" >
<script src="/apps/Utilities/Config/app.js?ver=1234"></script>
  <div class="panel panel-default">
	<div class="panel-heading clearfix">
	  <strong>
		<span class="glyphicon glyphicon-th"></span>
		<span>   Configuracion principal del Sitio.</span>
	  </strong>
	</div>


        <!-- Main content -->
        <section class="content">
         <!-- Your Page Content Here -->
        <div class='col-md-12'>
        
			<div class='box-header'>
				<h3>Utilidad para Configurar Parametros de la Compañia</h3>
			</div>
        
			<div class='col-md-8'>
				<div class='col-md-4'>	
					<label>Compania</label>
				</div>
				<div class='col-md-8'>	
					<input type='text' id='paga_con' class='form-control cantidades' ng-model="Configuracion.Compania"  style="font-size:30px; text-align:center; color:red; font-weight: bold;" >
				</div>
			</div>
			<div class='col-md-4'>
				<button type='button' class='btn btn-primary pull-right' ng-click="UpdateParametrers('Compania');" id='btn-genera'><i class='fa fa-thumbs-up'></i> actualiza.</button>
			</div>
			
			<div class='col-md-8'>
				<div class='col-md-4'>	
					<label>Telefono Compania</label>
				</div>
				<div class='col-md-8'>	
					<input type='text' id='paga_con' class='form-control cantidades' ng-model="Configuracion.Telefono"  style="font-size:30px; text-align:center; color:red; font-weight: bold;" >
				</div>
			</div>
			<div class='col-md-4'>
				<button type='button' class='btn btn-primary pull-right' ng-click="UpdateParametrers('TelCompania');" id='btn-genera'><i class='fa fa-thumbs-up'></i> actualiza.</button>
			</div>
			
			<div class='col-md-8'>
				<div class='col-md-4'>	
					<label>Ced Juridica</label>
				</div>
				<div class='col-md-8'>	
					<input type='text' id='paga_con' class='form-control cantidades' ng-model="Configuracion.CedJuridica"  style="font-size:30px; text-align:center; color:red; font-weight: bold;" >
				</div>
			</div>
			<div class='col-md-4'>
				<button type='button' class='btn btn-primary pull-right' ng-click="UpdateParametrers('CedJuridica');" id='btn-genera'><i class='fa fa-thumbs-up'></i> actualiza.</button>
			</div>
			
			<div class='col-md-8'>
				<div class='col-md-4'>	
					<label>Consecutivo Factura</label>
				</div>
				<div class='col-md-8'>	
					<input type='text' id='paga_con' class='form-control cantidades' ng-model="Configuracion.ConsecutivoFactura"  style="font-size:30px; text-align:center; color:red; font-weight: bold;" >
				</div>
			</div>
			<div class='col-md-4'>
				<button type='button' class='btn btn-primary pull-right' ng-click="UpdateParametrers('ConsecutivoFactura');" id='btn-genera'><i class='fa fa-thumbs-up'></i> actualiza.</button>
			</div>
			
			<div class='col-md-8'>
				<div class='col-md-4'>	
					<label>Impuesto Venta</label>
				</div>
				<div class='col-md-8'>	
					<input type='text' id='paga_con' class='form-control cantidades' ng-model="Configuracion.ImpuestoVenta"  style="font-size:30px; text-align:center; color:red; font-weight: bold;" >
				</div>
			</div>
			<div class='col-md-4'>
				<button type='button' class='btn btn-primary pull-right' ng-click="UpdateParametrers('ImpuestoVenta');" id='btn-genera'><i class='fa fa-thumbs-up'></i> actualiza.</button>
			</div>
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
