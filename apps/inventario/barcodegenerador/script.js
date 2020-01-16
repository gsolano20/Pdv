angular
  .module("CodigosDeBarras", ["angular-barcode"])
  .controller("ControladorPrincipal", ["$scope",'$http', function($scope, $http) {
    
	
	$scope.opciones = {
      format: 'CODE128',
      lineColor: '#000000',
      width: 2,
      height: 100,
      displayValue: true,
      fontOptions: '',
      font: 'monospace',
      textAlign: 'center',
      textPosition: 'bottom',
      textMargin: 2,
      fontSize: 20,
      background: '#ffffff',
      margin: 0,
      marginTop: undefined,
      marginBottom: undefined,
      marginLeft: undefined,
      marginRight: undefined,
      valid: function(valid) {}
    },
	
	$scope.pdv={}

    $scope.codigoBarras="00001";
  /*  $scope.imprimir = function(){
      window.print();
    },*/
	$scope.select_Articulo =function(id,barcode){
		$scope.pdv.codigo=id;
		$scope.pdv.barcode=barcode;
		$('#modal_busqueda_arts').modal('toggle');	
		$scope.busca_articulo();
	},
	$scope.busqueda_art= function(){
		$scope.sql = "SELECT *, 1 as cantidad,buy_price as PrecioTotal, 0 as IndNuevo, products.id as codigo FROM products left join media on media.id=products.media_id";
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
		
	$scope.busca_articulo= function(){
		$scope.sql = "SELECT * FROM products left join media on media.id=products.media_id WHERE ( barcode='"+$scope.pdv.barcode+"')";
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
	
	
	$scope.imprimir= function(){
		var el='panelCodigos'
		var restorepage = $('body').html();
		var printcontent = $('#' + el).clone();
		var enteredtext = $('#text').val();
		$('body').empty().html(printcontent);
		window.print();
		$('body').html(restorepage);
		$('#text').html(enteredtext);
	}

  }]);