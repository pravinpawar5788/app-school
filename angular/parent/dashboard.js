"use strict";
app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url='./'+config.ajaxModule+'home/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};


	//////////////HELPER FUNCTIONS/////////////////////////////////////	
    $scope.loadRows=function (){
		var data={};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterDashboard',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.responseData=response.data;
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});
		
		//SET INTERVAL TO AUTO UPDATE THE ROWS
		$scope.promise=$interval(function () {
			$http.get($scope.ajax_url+'filterDashboard',config.postConfig).then(function(response){
			$scope.responseData=response.data;
			},function(response){	
				$log.error(response);
			});
		}, 10000);
    
    };	
	
	///////autoload functions////////////////////


});