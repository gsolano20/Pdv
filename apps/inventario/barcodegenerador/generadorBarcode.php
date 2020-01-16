<?php
  $page_title = 'Generacion de BackUp';
  $docRoot = $_SERVER['DOCUMENT_ROOT'];
  require_once( $docRoot.'/includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>

<?php $docRoot = $_SERVER['DOCUMENT_ROOT']; 
 include_once($docRoot.'/layouts/header.php'); ?>

 
 <div  ng-app="CodigosDeBarras" >
 <div ng-controller="ControladorPrincipal" >
  <script src="angular-barcode.js?ver=1234"></script>
  <script src="script.js?ver=1234"></script>
 <div  >
 <div class="content-wrapper">
        <!-- Content Header (Page header) -->
	<div class="col-md-12">
		<div class="panel panel-default">
		<div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Generedor de Codigos de Barras </span>
         </strong>
        </div>
		
		<section class="content">

          <!-- Your Page Content Here -->
		<div class='row'>
			<div class='col-md-12'>
				<div class='box box-primary'>
				<div class='box-body'>
					<div class='col-md-4'>
							<div class='input-group'>
								<div class='input-group-btn'>
									<button type='button' class='btn btn-success' ng-click='busqueda_art();'>Busqueda</button>
								</div>
								<input type='text' id='barcode' class='form-control' placeholder='Bar Code...' ng-keyup="AlPrecionarTecla($event,'cantidad')" ng-model="pdv.barcode"  style="font-size:20px; text-align:center; color:blue; font-weight: bold;">
							</div>	
					</div>	
					<div class='col-md-4'>
					
							<!--<input type='text' id='barcode' class='form-control' placeholder='cantidad' ng-keyup="AlPrecionarTecla($event,'cantidad')" ng-model="pdv.cantidad"  style="font-size:20px; text-align:center; color:blue; font-weight: bold;">-->
						
					</div>	
					 
					<div class='col-md-12 '>
						<label for="mostrarCodigos">Mostrar
							<input type="checkbox" ng-model="mostrarCodigos" />
						</label>
						<button type="button" ng-click="imprimir()">Imprimir</button>
						<br>
						<div class='col-md-12' id="panelCodigos">
							<angular-barcode ng-show="mostrarCodigos" ng-model="pdv.barcode" bc-options="opciones" bc-class="barcode" bc-type="svg"></angular-barcode>
							<angular-barcode ng-show="mostrarCodigos" ng-model="pdv.barcode" bc-options="opciones" bc-class="barcode" bc-type="svg"></angular-barcode>
							<angular-barcode ng-show="mostrarCodigos" ng-model="pdv.barcode" bc-options="opciones" bc-class="barcode" bc-type="svg"></angular-barcode>
							<angular-barcode ng-show="mostrarCodigos" ng-model="pdv.barcode" bc-options="opciones" bc-class="barcode" bc-type="svg"></angular-barcode>
							<angular-barcode ng-show="mostrarCodigos" ng-model="pdv.barcode" bc-options="opciones" bc-class="barcode" bc-type="svg"></angular-barcode>
							<angular-barcode ng-show="mostrarCodigos" ng-model="pdv.barcode" bc-options="opciones" bc-class="barcode" bc-type="svg"></angular-barcode>
							<angular-barcode ng-show="mostrarCodigos" ng-model="pdv.barcode" bc-options="opciones" bc-class="barcode" bc-type="svg"></angular-barcode>
							<angular-barcode ng-show="mostrarCodigos" ng-model="pdv.barcode" bc-options="opciones" bc-class="barcode" bc-type="svg"></angular-barcode>
							<angular-barcode ng-show="mostrarCodigos" ng-model="pdv.barcode" bc-options="opciones" bc-class="barcode" bc-type="svg"></angular-barcode>
							<angular-barcode ng-show="mostrarCodigos" ng-model="pdv.barcode" bc-options="opciones" bc-class="barcode" bc-type="svg"></angular-barcode>
							<angular-barcode ng-show="mostrarCodigos" ng-model="pdv.barcode" bc-options="opciones" bc-class="barcode" bc-type="svg"></angular-barcode>
							<angular-barcode ng-show="mostrarCodigos" ng-model="pdv.barcode" bc-options="opciones" bc-class="barcode" bc-type="svg"></angular-barcode>
							<angular-barcode ng-show="mostrarCodigos" ng-model="pdv.barcode" bc-options="opciones" bc-class="barcode" bc-type="svg"></angular-barcode>
							<angular-barcode ng-show="mostrarCodigos" ng-model="pdv.barcode" bc-options="opciones" bc-class="barcode" bc-type="svg"></angular-barcode>
							<angular-barcode ng-show="mostrarCodigos" ng-model="pdv.barcode" bc-options="opciones" bc-class="barcode" bc-type="svg"></angular-barcode>
							<angular-barcode ng-show="mostrarCodigos" ng-model="pdv.barcode" bc-options="opciones" bc-class="barcode" bc-type="svg"></angular-barcode>
						</div>	 
					</div>	
			</div>	 
			</div>	 
			</div>
		</div>
	</section><!-- /.content -->
	    <div class="modal fade" id ="modal_busqueda_arts" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title">Busqueda de Articulos:</h4>
          </div>
          <div class="modal-body">
          <div class='input-group'>
          <span class='input-group-addon bg-blue'><b>Articulo:</b></span>
          <input type='text' id='articulo_buscar' class='form-control'ng-model="articulo" placeholder='Descripcion del articulo...'>
          </div>
          <br>
            <div id='lista_articulos'>
			<table class="table table-bordered">
				<thead>
				<th> Acciones</th>
				<th> Codigo </th>
				<th> Barcode </th>
				<th> Producto </th>
				<th> Precio </th>
				<th> Cantidad </th>
				</thead>
				<tbody  id="product_info"> 
					<tr ng-repeat="ProductSearch in ProductSearchs | orderBy:sortType:sortReverse | filter:articulo">
					<td>
						<a href="" ng-click="select_Articulo(ProductSearch.codigo,ProductSearch.barcode)" type="submit" target="_blank">
							<span class="glyphicon glyphicon-edit" aria-hidden="true" >
							</span>
						</a>
					</td>
					<td>{{ProductSearch.codigo}}</td>
					<td>{{ProductSearch.barcode}}</td>
					<td>{{ProductSearch.name}}</td>
					<td>{{ProductSearch.buy_price}}</td>
					<td>{{ProductSearch.quantity}}</td>
				</tbody>
			</table>
			</div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
</div>
</div>
</div>
</div>

<?php $docRoot = $_SERVER['DOCUMENT_ROOT']; 
 include_once($docRoot.'/layouts/footer.php'); ?>
