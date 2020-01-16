var app = angular.module('pointsaleapp', []);

app.controller('pointsaleCtrl',['$scope','$http', function($scope, $http) {
	$scope.pdv={
		id:'',
		file_name:'sin_foto.png'
		
	};
	$scope.Products=[];
	$scope.Venta={
		IdCliente:'1',
		NombreCliente:'Contado' 
	};
	$scope.IdEncabezado='';
	$scope.AlPrecionarTecla=function(event,Next) { 
	
		var key = event.keyCode; 
		console.log(key,Next)
		if (key == 13 || key ==9){
			
			if (key == 13){
				$( "#"+Next ).focus();
			}
			if (key ==9){
				$( "#"+Next ).focus();
			}
			switch(Next) {
				case "barcode":
					$scope.busca_articulo();
				break;
				case "cantidad":
					$scope.busca_articulo();
				break;
				case "btn-termina":
					if($scope.Venta.Pago===undefined){
						$( "#paga_con" ).focus();
					}
					if(parseFloat($scope.Venta.Pago)<parseFloat($scope.Venta.Total)){
						BootstrapDialog.show({
							type:BootstrapDialog.TYPE_DANGER,
							title: 'Error',
							message: 'Debe monto inferior al total'
						});	
						$( "#paga_con" ).focus();
					}
				break;
				case "btn-add":
			
							if(($scope.pdv.codigo==undefined && $scope.pdv.cantidad==undefined)){
								setTimeout(function(){ $( "#btn-procesa" ).focus(); }, 1500);
							}
					
				break;
				case "paga_con":
					if($scope.Products.length>0){
						setTimeout(function(){ $( "#paga_con" ).focus(); }, 1500);
						
					//$scope.prepara_venta();
					}else{
						BootstrapDialog.show({
						type:BootstrapDialog.TYPE_DANGER,
						title: 'Error',
						message: 'Debe agregar almenos un aritulo.'
						});
					}
				break;
				
				case "btn-ticket":
					setTimeout(function(){ $( "#btn-ticket" ).focus(); }, 2000);
				break;
				case "codigo":
					setTimeout(function(){ $( "#btn-procesa" ).focus(); }, 2000);
				break;
				
				default:
			}
			
		}
		
		if (key == 39){
			switch(Next) {
				case "paga_con":
					 $( "#btn-cancela" ).focus();
				break;
				case "btn_cre":
					 $( "#btn_cre" ).focus();
				break;
				default:
			}
		}
	},
	
	$scope.loadVentas = function(){
	 $scope.sql = "SELECT * FROM  ventasencabezado  ";
	    $http({
	        method: 'POST',
	        url:"/includes/execSql.php",
			
	        params: { 
	            sql:$scope.sql
	        } 
	    }).success(function(response){
	        if(response.success == 'true'){
	            $scope.sales =response.data;
	        }else{
	            BootstrapDialog.show({
	                type:BootstrapDialog.TYPE_DANGER,
	                title: 'Error',
	                message: response.error
	            });
	        }     
	    });
	},
	$scope.loadVentasDetalle = function(IdEncabezado){
	 $scope.sql = "SELECT *, products.id as codigo,  Cantidad as cantidad, Precio as PrecioTotal FROM  ventasdetalle  left join products on products.id=ventasdetalle.IdProducto  WHERE ventasdetalle.IdEncabezado ="+IdEncabezado;
	    $http({
	        method: 'POST',
	        url:"/includes/execSql.php",
			
	        params: { 
	            sql:$scope.sql
	        } 
	    }).success(function(response){
	        if(response.success == 'true'){
				$scope.Products =response.data;
	        }else{
	            BootstrapDialog.show({
	                type:BootstrapDialog.TYPE_DANGER,
	                title: 'Error',
	                message: response.error
	            });
	        }     
	    });
	},
	$scope.OpenVenta = function(id){
		for(i=0; i < $scope.sales.length; i++){
			if($scope.sales[i].IdEncabezado==id){
				$scope.pdv={
					PrecioTotal :$scope.sales[i].PrecioTotal				
				}
			}	
		}
		$scope.loadVentasDetalle(id);	
	}
	$scope.loadVentas();

	$scope.busca_articulo= function(){
		if($scope.pdv.codigo==undefined){
		$scope.sql = "SELECT *, products.id as codigo, 1 as cantidad,sale_price as PrecioTotal \
		, 0 as IndNuevo FROM  products  left join media on media.id=products.media_id \
		WHERE  (barcode='"+$scope.pdv.barcode+"')";
		}else{
		$scope.sql = "SELECT *, products.id as codigo, 1 as cantidad,sale_price as PrecioTotal \
		, 0 as IndNuevo FROM  products  left join media on media.id=products.media_id \
		WHERE (products.id='"+$scope.pdv.codigo+"') ";	
		}
	    $http({
	        method: 'POST',
	        url:"/includes/execSql.php",
			
	        params: { 
	            sql:$scope.sql
	        } 
	    }).success(function(response){
	        if(response.success == 'true'){
	            $scope.pdv =response.data[0];
				
	        }else{
	            BootstrapDialog.show({
	                type:BootstrapDialog.TYPE_DANGER,
	                title: 'Error',
	                message: response.error
	            });
	        }     
	    });
	},
	$scope.calcularPrecio= function(){
		
		if(parseFloat($scope.pdv.cantidad)<=parseFloat($scope.pdv.quantity)){
			
		$scope.pdv.PrecioTotal=$scope.pdv.buy_price*$scope.pdv.cantidad;
		}else{
			if(parseFloat($scope.pdv.cantidad)>=0){
				BootstrapDialog.show({
					type:BootstrapDialog.TYPE_DANGER,
					title: 'Error',
					message:'La cantidad dijitada es mayor a la existente en inventario'
				});
				$scope.pdv.cantidad=0;
			}else{
				
			}
			
		}
	},
	$scope.RemoveProduct= function(index){
		$scope.Products.splice(index, 1);
		$scope.calcularTotales();
	}
	$scope.addProduct= function(){
		if($scope.pdv.IndNuevo==0){
			$scope.Products.push($scope.pdv);
		}else{
			for(i=0; i < $scope.Products.length; i++){
				if($scope.Products[i].codigo==$scope.pdv.codigo){
					$scope.Products[i].PrecioTotal =$scope.pdv.PrecioTotal;
					$scope.Products[i].Id			=$scope.pdv.Id;
					$scope.Products[i].name		=$scope.pdv.name;		
					$scope.Products[i].cantidad	=$scope.pdv.cantidad;	
					$scope.Products[i].buy_price	=$scope.pdv.buy_price;	
					$scope.Products[i].PrecioTotal	=$scope.pdv.PrecioTotal;
					$scope.Products[i].quantity	=$scope.pdv.quantity;
					$scope.Products[i].file_name	=$scope.pdv.file_name;
					$scope.Products[i].codigo		=$scope.pdv.codigo;
					$scope.Products[i].barcode		=$scope.pdv.barcode;
					$scope.Products[i].IndNuevo	=2
				}
			}
		}
		$scope.calcularTotales();
		$scope.pdv={
			id:'',
			file_name:'sin_foto.png'
		}
	},
	$scope.EditProduct= function(id){
		for(i=0; i < $scope.Products.length; i++){
			if($scope.Products[i].codigo==id){
				$scope.pdv={
					PrecioTotal :$scope.Products[i].PrecioTotal,
					Id			:$scope.Products[i].Id,		
					name		:$scope.Products[i].name,		
					cantidad	:$scope.Products[i].cantidad,	
					buy_price	:$scope.Products[i].buy_price,	
					PrecioTotal	:$scope.Products[i].PrecioTotal,
					quantity	:$scope.Products[i].quantity,
					file_name	:$scope.Products[i].file_name,
					codigo		:$scope.Products[i].codigo,
					barcode		:$scope.Products[i].barcode,
					IndNuevo	:2
				
				}
			}
		}
	},	
	$scope.calcularTotales= function(){
		$scope.Venta.Total=0;
		for(i=0; i < $scope.Products.length; i++){
			//$scope.Venta.SubTotal=parseFloat($scope.Venta.Total)+parseFloat($scope.Products[i].PrecioTotal);
			$scope.Venta.Total=parseFloat((parseFloat($scope.Venta.Total)+parseFloat($scope.Products[i].PrecioTotal))).toFixed(2);
		}	
	},
	$scope.busqueda_art= function(){
		$scope.sql = "SELECT *, 1 as cantidad,buy_price as PrecioTotal, 0 as IndNuevo, products.id as codigo FROM  products  left join media on media.id=products.media_id";
	    $http({
	        method: 'POST',
	        url:"/includes/execSql.php",
			
	        params: { 
	            sql:$scope.sql
	        } 
	    }).success(function(response){
	        if(response.success == 'true'){
	            $scope.ProductSearchs =response.data;
	        }else{
	            BootstrapDialog.show({
	                type:BootstrapDialog.TYPE_DANGER,
	                title: 'Error',
	                message: response.error
	            });
	        }     
	    });
		$('#modal_busqueda_arts').modal('show'); 
	},
	
		
	$scope.select_Articulo =function(id,barcode){
		$scope.pdv.codigo=id;
		$scope.pdv.barcode=barcode;
		$('#modal_busqueda_arts').modal('toggle');	
		$scope.busca_articulo();
	},
	
	$scope.prepara_venta =function(id){
		$('#modal_prepara_venta').modal('show'); 
		
		$scope.sql = "exec  getORsetConsecutivo 1";
	    $http({
	        method: 'POST',
	        url:"/includes/execSql.php",
			
	        params: { 
	            sql:$scope.sql
	        } 
	    }).success(function(response){
	        if(response.success == 'true'){
	            $scope.Venta.NumFact =response.data[0].Valor;
	        }else{
	            BootstrapDialog.show({
	                type:BootstrapDialog.TYPE_DANGER,
	                title: 'Error',
	                message: response.error
	            });
	        }     
	    });
	}
	$scope.calculoCambio =function(){
		$scope.Venta.Cambio=parseFloat(parseFloat($scope.Venta.Pago)-parseFloat($scope.Venta.Total)).toFixed(2);;
		
	}
	$scope.procesa_venta =function(){
		$scope.sql = " declare @fecha date=getdate(); exec  InsertVentasEncabezado '1', '"+$scope.Venta.NumFact+"',\
		'"+$scope.Venta.Total+"', '"+$scope.Venta.IdCliente+"', @fecha, '"+parseFloat($scope.Venta.Total)+"';";
		$http({
			method: 'POST',
			url:"/includes/execSql.php",
			
			params: { 
				sql:$scope.sql
			} 
		}).success(function(response){
			if(response.success == 'true'){
				$scope.IdEncabezado =response.data[0].Id;
				$scope.GrabarDetalles()
				$scope.loadVentas()
				/*BootstrapDialog.show({
						type:BootstrapDialog.TYPE_INFO,
						title: 'SUCCESS',
						message: "Se grabo Correctamente el registro"
					});*/
				
					$(".alert").css("display","inherit");
					setTimeout(function(){$(".alert").css("display","none"); }, 3000);
				
			}else{
				BootstrapDialog.show({
					type:BootstrapDialog.TYPE_DANGER,
					title: 'Error',
					message: response.error
				});
			}     
		});
	},	
	$scope.GrabarDetalles =function(){
		for(i=0; i <= $scope.Products.length; i++){
			if(i < $scope.Products.length){
				$scope.sql = "exec InsertVentasDetalle\
					"+$scope.IdEncabezado+",\
					"+$scope.Products[i].PrecioTotal+",\
					"+$scope.Products[i].cantidad+",\
					"+$scope.Products[i].codigo+",\
					0";
					$http({
						method: 'POST',
						url:"/includes/execSql.php",
						
						params: { 
							sql:$scope.sql
						} 
					}).success(function(response){
						if(response.success == 'true'){
							
						}else{
							BootstrapDialog.show({
								type:BootstrapDialog.TYPE_DANGER,
								title: 'Error',
								message: response.error
							});
						}     
					});
				
			}else{
				$scope.updateSales($scope.IdEncabezado);
			}
		}	
	},	
	$scope.updateSales =function(idEncabezado){
		$scope.sql = "exec  updateSales '"+idEncabezado+"';";
		$http({
			method: 'POST',
			url:"/includes/execSql.php",
			
			params: { 
				sql:$scope.sql
			} 
		}).success(function(response){
			if(response.success == 'true'){
			
			}else{
				BootstrapDialog.show({
					type:BootstrapDialog.TYPE_DANGER,
					title: 'Error',
					message: response.error
				});
			}     
		});
	}
	
	$scope.CloseVenta=function(){
		$('#modal_prepara_venta').modal('toggle'); 
		$scope.pdv={
			id:'',
			file_name:'sin_foto.png'
			
		};
		$scope.Venta={
			IdCliente:'1',
			NombreCliente:'Contado'
		};
			
		$scope.Products=[];
		$scope.IdEncabezado='';
	}
	$scope.openTicket=function(){
		//window.open("http://localhost:8082/apps/ventas/Puntodeventa/ticket.txt");
	
		$http({
			method: 'POST',
			url:"/apps/ventas/Puntodeventa/impresion_tickets.php",
			params: { 
				IdEncabezado:$scope.IdEncabezado,
				fecha:'2018-07-05',
				NumFact:$scope.Venta.NumFact,
				total:$scope.Venta.Total,
				supago:$scope.Venta.Pago,
				cambio:$scope.Venta.Cambio
			} 
		}).success(function(response){
			eval(response.window);
			if(response.success == "true"){
				eval(response.window);
			}else{
				BootstrapDialog.show({
					type:BootstrapDialog.TYPE_DANGER,
					title: 'Error',
					message: response.error
				});
			}     
		});
	},
	$scope.busca_cliente= function(){
		$scope.sql = "SELECT *  FROM  Cliente";
	    $http({
	        method: 'POST',
	        url:"/includes/execSql.php",
			
	        params: { 
	            sql:$scope.sql
	        } 
	    }).success(function(response){
	        if(response.success == 'true'){
	            $scope.Clientes =response.data;
	        }else{
	            BootstrapDialog.show({
	                type:BootstrapDialog.TYPE_DANGER,
	                title: 'Error',
	                message: response.error
	            });
	        }     
	    });
		$('#modal_tabla_clientes').modal('show'); 
	},
	$scope.select_Cliente =function(id,Nombre){
		$scope.Venta.IdCliente=id;
		$scope.Venta.ClienteName=Nombre;
		$('#modal_tabla_clientes').modal('toggle');	
		setTimeout(function(){ $( "#btn-procesa" ).focus(); }, 1500);
		
	}
	
	
}]);