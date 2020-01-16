<?php
  $page_title = 'Punto venta';
  $docRoot = $_SERVER['DOCUMENT_ROOT'];
  require_once( $docRoot.'/includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php $docRoot = $_SERVER['DOCUMENT_ROOT']; 
 include_once($docRoot.'/layouts/header.php'); ?>
 <style>
 .alert{
	 display:none
 }
 </style>
 
 <div  ng-app="pointsaleapp" >
 <div ng-controller="pointsaleCtrl" >
  <script src="/apps/ventas/Puntodeventa/app.js?ver=1234"></script>
 <div  >
 <div class="content-wrapper">
        <!-- Content Header (Page header) -->
	<div class="col-md-12">
		<div class="panel panel-default">
		<div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Punto de Venta</span>
         </strong>
        </div>
        

        <!-- Main content -->
        <section class="content">

          <!-- Your Page Content Here -->
		<div class='row'>
			<div class='col-md-4'>
				<div class='box box-primary'>
					<div class='box-header with-border'><h3 class='box-title'>Ingresa el Codigo del Articulo:</h3></div>
					<div class='box-body'>
						<div class='input-group'>
						<div class='input-group-btn'>
						<button type='button' class='btn btn-success' ng-click='busqueda_art();' style="height: 68px;">Busqueda</button>
						</div>
						<input type='text' id='codigo' class='form-control' placeholder='Codigo...' ng-keyup="AlPrecionarTecla($event,'barcode')" ng-model="pdv.codigo"  style="font-size:20px; text-align:center; color:blue; font-weight: bold;">
						<input type='text' id='barcode' class='form-control' placeholder='Bar Code...' ng-keyup="AlPrecionarTecla($event,'cantidad')" ng-model="pdv.barcode"  style="font-size:20px; text-align:center; color:blue; font-weight: bold;">

						</div>
						<br>
						<div class='input-group'>
						<span class='input-group-addon bg-red'>Precio:</span>
						<input type='text' id='preciou' ng-model="pdv.PrecioTotal" class='form-control cantidades'  style="font-size:20px; text-align:center; color:blue; font-weight: bold;"
						data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0'" disabled>
						</div>
						<br>
						<div class='input-group'>
						<span class='input-group-addon bg-orange'>Cantidad:</span>
						<input type='text' id='cantidad' class='form-control cantidades' ng-keyup="AlPrecionarTecla($event,'btn-add')" ng-model="pdv.cantidad" style="font-size:20px; text-align:center; color:blue; font-weight: bold;"
						data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0'"  ng-change="calcularPrecio()">
						</div>
						<br>
						<button class='btn btn-success btn-lg' id='btn-add' ng-keyup="AlPrecionarTecla($event,'codigo')" ng-click='addProduct();'>  Agregar</button>
						<button class='btn btn-danger btn-lg' id='btn-cancel' onclick='cancela_codigo();'>  Cancelar</button>
					</div>
				</div>
			</div>

			<div class="col-md-4" >
				<!-- Widget: user widget style 1 -->
				<div class='box box-primary'>
				<div class="boxsinpadding box-widget widget-user "  border-top-color: #3c8dbc;">
					<!-- Add the bg color to the header using any of the bg-* classes -->
					<div class="widget-user-header bg-aqua-active">
						<h3 class="widget-user-username"></h3>
						<h5 class="widget-user-desc"></h5>
					</div>
					<div class="widget-user-image">
						<img id='imagen' class="img-circle" ng-src="/uploads/products/{{pdv.file_name}}" alt="Imagen del Articulo">
					
					</div>
					<input type='text' class='form-control' ng-model="pdv.name" style=" text-align:center; color:blue; font-weight: bold;" disabled>
					<div class="box-footer">
						<div class="row">
							<div class="col-sm-5 border-right">
							  <div class="description-block">
								<h5 class="description-header preciol">{{pdv.buy_price}}</h5>
								<span class="description-text">PRECIO U. L.</span>
							  </div><!-- /.description-block -->
							</div><!-- /.col -->
							<div class="col-sm-2 border-right">
							  <div class="description-block">

							  </div><!-- /.description-block -->
							</div><!-- /.col -->
							<div class="col-sm-5">
							  <div class="description-block">
								<h5 class="description-header exis">{{pdv.quantity}}</h5>
								<span class="description-text">EXIS.</span>
							  </div><!-- /.description-block -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div>
				</div><!-- /.widget-user -->
				</div><!-- /.widget-user -->
            </div><!-- /.col -->

			<div class="col-md-4">
				<div class=' boxpaddingtop box-primary'>
					<!-- small box -->
					<div class="small-box bg-aqua">
						<div class="inner">
							<h3><div id='totales'>{{Venta.Total}}</div></h3>
							<p>Total</p>
						</div>
						<div class="icon">
							<i class="fa fa-shopping-cart"></i>
						</div>
						<a href="#" class="small-box-footer">
							<div id='num_ticket'></div>
						</a>
						<a href="#" class="small-box-footer">
							<div id='Cliente'>
							{{Venta.ClienteName}}
							</div>
						</a>
						<a href="#" class="small-box-footer">
							<div id='tipo_de_venta'>Venta de Contado.</div>
						</a>
					</div>
					<div class='btn-group'>
						<button class='btn  btn-success' id='btn-procesa' ng-keyup="AlPrecionarTecla($event,'paga_con')" ng-click='prepara_venta();'><i class='fa fa-money'></i> Pagar</button>
						<button class='btn  btn-warning' id='btn-cancela' ng-keyup="AlPrecionarTecla($event,'codigo')" ng-click="CloseVenta()"><i class='fa fa-times-circle'></i> Cancelar</button>
						<button class='btn  btn-primary' id='btn_cre' ng-keyup="AlPrecionarTecla($event,'Cliente')" ng-click="busca_cliente();" ><i class='fa fa-user-plus'></i> Cliente (Credito)</button>
					</div>
				</div><!-- ./col -->
            </div><!-- ./col -->


          </div>

          <div class='row'>
          <div class='col-md-12'>
          <div class='box box-primary'>
          <div class='box-header'>
          <h3 class='box-title'>Lista de Articulos</h3>
          </div>
          <div class='box-body table-responsive'>
          <table id='tabla_articulos' class='table table-hover'>
           <thead>
           <tr>
           <th class='center'>Codigo</th>
           <th class='center'>Bar Code</th>
		   <th class='center'>Descripcion</th>
		   <th class='center'>Cantidad</th>
		   <th class='center'>Precio U.</th>
		   <th class='center'>Monto</th>
		   <th class='center'>Operacion</th>
           </tr>
           </thead>
           <tbody>
				<tr ng-repeat="Product in Products | orderBy:sortType:sortReverse | filter:searchsales">
				<td>{{Product.codigo}}</td>
				<td>{{Product.barcode}}</td>
				<td>{{Product.name}}</td>
				<td>{{Product.cantidad}}</td>
				<td>{{Product.buy_price}}</td>
				<td>{{Product.PrecioTotal}}</td>
				<td>
				<a href="" ng-click="EditProduct(Product.codigo)" type="submit" target="_blank">
					<span class="glyphicon glyphicon-edit" aria-hidden="true" >
					</span>
				</a>
				<a href="" ng-click="RemoveProduct($index)" type="submit" target="_blank">
					<span class="glyphicon glyphicon-remove" aria-hidden="true" >
					</span>
				</a>
				</td>

           </tbody>
          </table>
          </div>
          </div>
          </div>
          </div>
        </section><!-- /.content -->
         </div><!-- /.content-wrapper -->


           <div class="modal fade" id ="modal_tabla_clientes" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title">Selecciona el Cliente:</h4>
			 <input type='text' id='ClienteSearch' class='form-control'ng-model="ClienteSearch" placeholder='Clientes...'>
          </div>
          <div class="modal-body">
            <div id='lista_clientes'>
			<table class="table table-bordered">
				<thead>
				<th> Acciones</th>
				<th> Codigo </th>
				<th> Cliente </th>
				<th> Email </th>
				<th> Telefono </th>
				<th> Cedula </th>
				</thead>
				<tbody  id="product_info"> 
					<tr ng-repeat="Cliente in Clientes | orderBy:sortType:sortReverse | filter:ClienteSearch">
					<td>
						<a href="" ng-click="select_Cliente(Cliente.IdCliente,Cliente.Nombre)" type="submit" target="_blank">
							<span class="glyphicon glyphicon-edit" aria-hidden="true" >
							</span>
						</a>
					</td>
					<td>{{Cliente.IdCliente}}</td>
					<td>{{Cliente.Nombre}}</td>
					<td>{{Cliente.Email}}</td>
					<td>{{Cliente.Telefono}}</td>
					<td>{{Cliente.Cedula}}</td>
				</tbody>
			</table>
			</div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
	</div>
	</div>  
    </div><!-- /.modal -->


    <div class="modal fade" id ="modal_prepara_venta" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
       <div class="modal-dialog">
        <div class="modal-content">
			<div class="alert alert-success" role="alert" aria-hidden="true">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<strong>Success!</strong> Se grabo Correctamente el registro
			</div>
          <div class="modal-header">
            <button type="button" class="close" ng-click='CloseVenta()' aria-hidden="true">×</button>
            <h4 class="modal-title">RESUMEN FACTURA # {{Venta.NumFact}}</h4>
          </div>
          <div class="modal-body">

          <div class='input-group input-group-lg'>
          <span class='input-group-addon bg-blue'><b>Total de la Venta:</b></span>
          <input type='text' id='total_de_venta' ng-model="Venta.Total" class='form-control' style="font-size:30px; text-align:center; color:red; font-weight: bold;" disabled>
          </div>
          <br>
          <div class='input-group input-group-lg'>
          <span class='input-group-addon bg-blue'><b>Su Pago:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></span>
          <input type='text' id='paga_con' class='form-control cantidades' ng-keyup="AlPrecionarTecla($event,'btn-termina')" ng-model="Venta.Pago" ng-change="calculoCambio()" style="font-size:30px; text-align:center; color:red; font-weight: bold;" 
          data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0'">
          </div>
          <br>
          <div class='input-group input-group-lg'>
          <span class='input-group-addon bg-blue'><b>Cambio:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></span>
          <input type='text' id='el_cambio' class='form-control' ng-model="Venta.Cambio" style="font-size:30px; text-align:center; color:red; font-weight: bold;" disabled>
          </div>

          </div>
          <div class="modal-footer">
              <button class='btn btn-success btn-lg print_ticket' id='btn-ticket'  ng-click='openTicket();' ng-disabled="IdEncabezado==''" ><i class='fa fa-print'></i> Ticket</button>
              <button class='btn btn-success btn-lg' id='btn-termina' ng-keyup="AlPrecionarTecla($event,'btn-ticket')" ng-click='procesa_venta();'><i class='fa fa-shopping-cart'></i> Procesar</button>
              <button type="button" class="btn btn-danger btn-lg"  ng-click='CloseVenta()'><i class='fa fa-times'></i> Cerrar</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

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

      <input type='hidden' id='idcliente_credito' value="">
      <input type='hidden' id='total_venta' value="">

      <div id='impresion_de_ticket' class='print'></div>

  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Editar venta</span>
       </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="add_sale.php">
         <table class="table table-bordered">
           <thead>
            <th> Codigo </th>
            <th> #Fact </th>
            <th> Cliente </th>
            <th> Fecha </th>
            <th> Total </th>
            <th> IV </th>
            <th> Acciones</th>
			</thead>
			<tbody  id="product_info"> 
				<tr ng-repeat="sale in sales | orderBy:sortType:sortReverse | filter:searchsales">
				<td>{{sale.IdEncabezado}}</td>
				<td>{{sale.NumFact}}</td>
				<td>{{sale.Cliente}}</td> 
				<td>{{sale.Fecha}}</td> 
				<td>{{sale.Total}}</td>
				<td>{{sale.impuestodeventa}}</td> 
				<td>
				<a href="" ng-click="OpenVenta(sale.IdEncabezado)"  type="submit" target="_blank">
					<span class="glyphicon glyphicon-edit" aria-hidden="true" >
					</span>
				</a>
				</td>
			</tbody>
         </table>
       </form>
      </div>
    </div>
  </div>

</div>
</div>
</div>
</div>
</div>

<?php $docRoot = $_SERVER['DOCUMENT_ROOT']; 
 include_once($docRoot.'/layouts/footer.php'); ?>
