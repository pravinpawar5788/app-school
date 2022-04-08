app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url='../'+config.ajaxModule+'settings/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.filter={class_id:'',date:'',target:''};
	$scope.hook={hook:'',template:'',target:''};
    $scope.promise={};
	$scope.systemHook={};
	$scope.systemHookKeys={};
	$scope.message='';
	$scope.usrid='';
	
	/////////GENERAL FUNCTIONS/////////////////////////////////////////////////////	          
    $scope.sortBy= function (value){ $scope.sortString=$scope.processSort(value);$scope.loadRows()};
    $scope.clearFilter= function (){ $.each($scope.filter, function(index){$scope.filter[index]='';}); };
    $scope.filterGetString= function (){ var data=''; $.each($scope.filter, function(index,value){data+='&'+index+'='+value;});data+='&search='+$scope.searchText; return data;};
    $scope.showFilter= function (){var show=false; $.each($scope.filter, function(index,value){if(value !==''){show=true;} }); return show; };
    $scope.clearResponse= function (){ $scope.responseText='';$scope.responseModelText=''; };
    $scope.selectRow = function (row) {$scope.selectedRow = row; $scope.message=row.template;$scope.loadSystemHookUpdate();};
	$scope.addkey=function (key){$scope.message= $scope.message+' {'+key+'} ';}; 
	//////////////HELPER FUNCTIONS/////////////////////////////////////	
    $scope.loadRows=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterSmsHooks',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.responseData=response.data;
			config.responseNumRows=$scope.responseData.total_rows;
			$scope.validatePagination();			
			
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});
    
    };	
	$scope.saveRow=function (){	
    	if($scope.hook.hook.length< 1){
		swal({title:"Please select a valid event",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.message.length< 1){
		swal({title:"Please write message",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.hook.target.length< 1){
		swal({title:"Please select a target audience",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='hook='+encodeURIComponent($scope.hook.hook)+'&template='+encodeURIComponent($scope.message);
		data+='&event='+encodeURIComponent($scope.systemHook.event)+'&target='+encodeURIComponent($scope.hook.target);
		$http.post($scope.ajax_url+'addSmsHook',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.message='';$scope.hook.target='';
			$scope.loadRows();
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			
		});
        }
    }; 
	$scope.updateRow=function (){
		if($scope.message.length< 1){
		swal({title:"Please enter message",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.target.length< 1){
		swal({title:"Please choose target",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&template='+encodeURIComponent($scope.message)+'&target='+encodeURIComponent($scope.selectedRow.target);
		$http.post($scope.ajax_url+'updateSmsHook',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.loadRows();
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			
		});
        }
    }; 
	//delete account
	$scope.delRow=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Cancel notification. System will stop sending this notification to audience.!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: '<strong>Yes, Cencel it <i class="icon-circle-right2 ml-2"></i></strong>',
			cancelButtonText: 'No, Go Back <i class="icon-circle-left2 ml-2"></i>',
			confirmButtonClass: 'btn btn-warning',
			cancelButtonClass: 'btn btn-light',
			buttonsStyling: false
		}).then(function (result) {
			if(result.value){
				var msg='';
				var data='rid='+row.mid;
				$http.post($scope.ajax_url+'deleteSmsHook',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadRows();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	/////////////////////////////////////////////////////////////////
	$scope.loadSystemHook=function (){
		if($scope.hook.hook.length< 1){
		swal({title:"Please choose an event",type:'info',showConfirmButton:false,timer:2000});
        }else {
			var data={'hook':$scope.hook.hook};
			config.postConfig.params=data;
			$scope.disableButtons();
			$http.get($scope.ajax_url+'loadSystemHook',config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
					swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
					$('#add').modal('show');
					$scope.systemHook=response.data.output;
				}
				
			},function(response){	
				$scope.enableButtons();		
				swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
				$log.error(response);
			});
		}
    
    };	
	$scope.loadSystemHookUpdate=function (){
		if($scope.selectedRow.hook.length< 1){
		swal({title:"Please choose valid event",type:'info',showConfirmButton:false,timer:2000});
        }else {
			var data={'hook':$scope.selectedRow.hook};
			config.postConfig.params=data;
			$scope.disableButtons();
			$http.get($scope.ajax_url+'loadSystemHook',config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
					swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
					$scope.systemHook=response.data.output;
				}
				
			},function(response){	
				$scope.enableButtons();		
				swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
				$log.error(response);
			});
		}
    
    };	
	
	///////autoload functions////////////////////
	$scope.loadRows();
	/* $timeout(function(){$scope.loadRows();},1000); */


});