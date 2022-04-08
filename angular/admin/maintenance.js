"use strict";
app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys,Pubnub){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url=''+config.ajaxModule+'maintenance/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.filter={status:'',module:''};
	$scope.updatesAvaileable=false;
	$scope.promise={};
	$scope.message='';
	$scope.record={};
	$scope.settings={};
	$scope.rid='';

	/////////HOTKEYS/////////////////////////////////////////////////////	 
	
	/////////GENERAL FUNCTIONS/////////////////////////////////////////////////////	             
    $scope.sortBy= function (value){ $scope.sortString=$scope.processSort(value);};
    $scope.clearFilter= function (){ $.each($scope.filter, function(index){$scope.filter[index]='';}); };
    $scope.filterGetString= function (){ var data=''; $.each($scope.filter, function(index,value){data+='&'+index+'='+value;}); return data;};
    $scope.showFilter= function (){var show=false; $.each($scope.filter, function(index,value){if(value !==''){show=true;} }); return show; };
    $scope.clearResponse= function (){ $scope.responseText='';$scope.responseModelText=''; };
    $scope.selectRow = function (row) {$scope.selectedRow = row;};
	$scope.addkey=function (key){$scope.message= $scope.message+' {'+key+'} ';}; 
    $scope.selectCampus= function (row) {$scope.campus=row;};
	//////////////LOADING FUNCTIONS/////////////////////////////////////	
	
	
	$scope.checkUpdates=function (){	
    	if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='';
		$scope.blockPageUI();
		$http.post($scope.ajax_url+'checkUpdates',data,config.postConfig).then(function(response){
			$scope.unblockPageUI();
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:3500});
			$log.error(response);
			}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:5000});
				if(response.data.updates_available===true){$scope.updatesAvaileable=true;}else{$scope.updatesAvaileable=false;}
			}
		},function(response){
			$scope.unblockPageUI();
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			
		});
        }
    }; 
	
	$scope.installUpdates=function (row){
		if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.updatesAvaileable===false){
		swal({title:"System is already upto date.",type:'info',showConfirmButton:false,timer:2000});
        }else{
			swal({
				title: 'Are you sure?',
				text: "Please make complete system backup before proceed!",
				type: 'warning',
				showCancelButton: true,
				confirmButtonText: '<strong>Yes, Proceed <i class="icon-circle-right2 ml-2"></i></strong>',
				cancelButtonText: 'No, Go Back <i class="icon-circle-left2 ml-2"></i>',
				confirmButtonClass: 'btn btn-warning',
				cancelButtonClass: 'btn btn-light',
				buttonsStyling: false
			}).then(function (result) {
				if(result.value){
					var msg='';
					$scope.blockPageUI();
					var data='';
					$http.post($scope.ajax_url+'installUpdates',data,config.postConfig).then(function(response){
						$scope.unblockPageUI();
						msg=response.data.message;
						if(response.data.error===true){
							swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
							$log.error(response);	
						}else{
							swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
							if(response.data.updates_available===true){$scope.updatesAvaileable=true;}else{$scope.updatesAvaileable=false;}
						}
					},function (response){
						$scope.unblockPageUI();
						swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);
					});
				}
			});
		}
	
	};
	
	

});