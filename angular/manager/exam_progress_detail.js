app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url='../../'+config.ajaxModule+'exam/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.filter={status:'',session_id:''};
	$scope.promise={};
	$scope.message='';
	$scope.usrid='';

	/////////HOTKEYS/////////////////////////////////////////////////////	 
	hotkeys.add({
	  combo: 'enter',	//enter
	  description: 'Search student',
	  allowIn: ['INPUT', 'SELECT', 'TEXTAREA'],
	  callback: function(event, hotkey) {
		event.preventDefault();
		$scope.loadRows();
	  }
	}); 
	
	/////////GENERAL FUNCTIONS/////////////////////////////////////////////////////	          
    $scope.sortBy= function (value){ $scope.sortString=$scope.processSort(value);};
    $scope.clearFilter= function (){ $.each($scope.filter, function(index){$scope.filter[index]='';}); };
    $scope.filterGetString= function (){ var data=''; $.each($scope.filter, function(index,value){data+='&'+index+'='+value;});data+='&search='+$scope.searchText; return data;};
    $scope.showFilter= function (){var show=false; $.each($scope.filter, function(index,value){if(value !==''){show=true;} }); return show; };
    $scope.clearResponse= function (){ $scope.responseText='';$scope.responseModelText=''; };
    $scope.selectRow = function (row) {$scope.selectedRow = row;};
	$scope.addkey=function (key){$scope.message= $scope.message+' {'+key+'} ';}; 
	//////////////HELPER FUNCTIONS/////////////////////////////////////	
    $scope.loadRows=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'session_id':$scope.filter.session_id,'rid':$scope.usrid,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterTestProgressDetail',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.responseData=response.data;
			config.responseNumRows=$scope.responseData.total_rows;
			$scope.validatePagination();			
			
		},function(response){	
			$scope.enableButtons();		
            // swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});
    
    };	
	
	///////autoload functions////////////////////
	// $scope.loadRows();
	$timeout(function(){$scope.loadRows();},1000);


});