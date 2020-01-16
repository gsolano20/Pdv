var app = angular.module('configapp', []);

app.controller('configCtrl',['$scope','$http', function($scope, $http) {
	
	$scope.Configuracion={};
	
	$scope.loadcompania = function(){
	 $scope.sql = "SELECT Valor as Compania FROM parameter where parametro='compania' ";
	    $http({
	        method: 'POST',
	        url:"/includes/execSql.php",
			
	        params: { 
	            sql:$scope.sql
	        } 
	    }).success(function(response){
	        if(response.success == 'true'){
	            $scope.Configuracion.Compania =response.data[0].Compania;
	        }else{
	            BootstrapDialog.show({
	                type:BootstrapDialog.TYPE_DANGER,
	                title: 'Error',
	                message: response.error
	            });
	        }     
	    });
		
		$scope.sql = "SELECT Valor as Telefono FROM parameter where parametro='TelCompania' ";
	    $http({
	        method: 'POST',
	        url:"/includes/execSql.php",
			
	        params: { 
	            sql:$scope.sql
	        } 
	    }).success(function(response){
	        if(response.success == 'true'){
	            $scope.Configuracion.Telefono =response.data[0].Telefono;
	        }else{
	            BootstrapDialog.show({
	                type:BootstrapDialog.TYPE_DANGER,
	                title: 'Error',
	                message: response.error
	            });
	        }     
	    });
		
		
		$scope.sql = "SELECT Valor as CedJuridica FROM parameter where parametro='CedJuridica' ";
	    $http({
	        method: 'POST',
	        url:"/includes/execSql.php",
			
	        params: { 
	            sql:$scope.sql
	        } 
	    }).success(function(response){
	        if(response.success == 'true'){
	            $scope.Configuracion.CedJuridica =response.data[0].CedJuridica;
	        }else{
	            BootstrapDialog.show({
	                type:BootstrapDialog.TYPE_DANGER,
	                title: 'Error',
	                message: response.error
	            });
	        }     
	    });
		
		
		$scope.sql = "SELECT Valor as ConsecutivoFactura FROM parameter where parametro='ConsecutivoFactura' ";
	    $http({
	        method: 'POST',
	        url:"/includes/execSql.php",
			
	        params: { 
	            sql:$scope.sql
	        } 
	    }).success(function(response){
	        if(response.success == 'true'){
	            $scope.Configuracion.ConsecutivoFactura =response.data[0].ConsecutivoFactura;
	        }else{
	            BootstrapDialog.show({
	                type:BootstrapDialog.TYPE_DANGER,
	                title: 'Error',
	                message: response.error
	            });
	        }     
	    });
		$scope.sql = "SELECT Valor as ImpuestoVenta FROM parameter where parametro='ImpuestoVenta' ";
	    $http({
	        method: 'POST',
	        url:"/includes/execSql.php",
			
	        params: { 
	            sql:$scope.sql
	        } 
	    }).success(function(response){
	        if(response.success == 'true'){
	            $scope.Configuracion.ImpuestoVenta =response.data[0].ImpuestoVenta;
	        }else{
	            BootstrapDialog.show({
	                type:BootstrapDialog.TYPE_DANGER,
	                title: 'Error',
	                message: response.error
	            });
	        }     
	    });
	},
	$scope.loadcompania();
	
	$scope.UpdateParametrers = function(Parametro){
	switch(Parametro) {
		case "ConsecutivoFactura":
			$scope.valorparametro=$scope.Configuracion.ConsecutivoFactura;
		break;
		case "CedJuridica":
			$scope.valorparametro=$scope.Configuracion.CedJuridica;
		break;
		case "TelCompania":
			$scope.valorparametro=$scope.Configuracion.TelCompania;
		break;
		case "Compania":
			$scope.valorparametro=$scope.Configuracion.Compania;
		break;
		case "ImpuestoVenta":
			$scope.valorparametro=$scope.Configuracion.ImpuestoVenta;
		break;
			default:
		}
	 $scope.sql = "Update parameter set  Valor ='"+$scope.valorparametro+"'\
	 where parametro='"+Parametro+"'";
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
}]);