app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url=config.ajaxModule+'office/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.filter={status:''};
	$scope.entry={name:'',type_auth:'1',mobile:'',password:'',prm_std_info:'0',prm_stf_info:'0',prm_finance:'0',prm_class:'0',prm_library:'0',prm_stationary:'0',prm_transport:'0',prm_lms:'0',prm_hostel:'0',prm_parents:'0'};
    $scope.promise={};
	$scope.message='';
	$scope.usrid='';

	/////////HOTKEYS/////////////////////////////////////////////////////	 
	hotkeys.add({
	  combo: 'enter',	//enter
	  description: 'Search / save record',
	  allowIn: ['INPUT', 'SELECT'],
	  callback: function(event, hotkey) {
		event.preventDefault();
		switch(config.enter) {
		  case 'add':
			$scope.saveRow();
			break;
		  case 'update':
			$scope.updateRow();
			break;
		  default:
			$scope.loadRows();
		} 
	  }
	}); 
	
	hotkeys.add({
	  combo: 'shift+n',	//ctrl+enter
	  description: 'Open new account registration form.',
	  //allowIn: ['INPUT', 'SELECT', 'TEXTAREA'],
	  callback: function(event, hotkey) {
		event.preventDefault();
		$('#add').modal('show');
	  }
	});
	
	hotkeys.add({
	  combo: 'shift+s',	//ctrl+enter
	  description: 'Save new account while creating new account',
	  //allowIn: ['INPUT', 'SELECT', 'TEXTAREA'],
	  callback: function(event, hotkey) {
		event.preventDefault();
		$scope.saveRow();
	  }
	});
	/////////GENERAL FUNCTIONS/////////////////////////////////////////////////////	          
    $scope.sortBy= function (value){ $scope.sortString=$scope.processSort(value);$scope.loadRows()};
    $scope.clearFilter= function (){ $.each($scope.filter, function(index){$scope.filter[index]='';}); };
    $scope.filterGetString= function (){ var data=''; $.each($scope.filter, function(index,value){data+='&'+index+'='+value;});data+='&search='+$scope.searchText; return data;};
    $scope.showFilter= function (){var show=false; $.each($scope.filter, function(index,value){if(value !==''){show=true;} }); return show; };
    $scope.clearResponse= function (){ $scope.responseText='';$scope.responseModelText=''; };
    $scope.selectRow = function (row) {$scope.selectedRow = row;};
	$scope.addkey=function (key){$scope.message= $scope.message+' {'+key+'} ';}; 
	//////////////HELPER FUNCTIONS/////////////////////////////////////	
    $scope.loadRows=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'status':$scope.filter.status,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filter',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.responseData=response.data;
			config.responseNumRows=$scope.responseData.total_rows;
			$scope.validatePagination();			
			
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			if($scope.appConfig.showLog){$log.error(response);}
		});
    
    };	
	$scope.saveRow=function (){	
    	if($scope.entry.name.length< 1){
		swal({title:"Please enter name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.entry.password.length< 1){
		swal({title:"Please enter new login password ",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='name='+encodeURIComponent($scope.entry.name)+'&type_auth='+encodeURIComponent($scope.entry.type_auth)+'&mobile='+encodeURIComponent($scope.entry.mobile)+'&password='+encodeURIComponent($scope.entry.password);
		data+='&prm_std_info='+encodeURIComponent($scope.entry.prm_std_info)+'&prm_stf_info='+encodeURIComponent($scope.entry.prm_stf_info);
		data+='&prm_finance='+encodeURIComponent($scope.entry.prm_finance)+'&prm_class='+encodeURIComponent($scope.entry.prm_class);
		data+='&prm_library='+encodeURIComponent($scope.entry.prm_library)+'&prm_stationary='+encodeURIComponent($scope.entry.prm_stationary);
		data+='&prm_transport='+encodeURIComponent($scope.entry.prm_transport)+'&prm_lms='+encodeURIComponent($scope.entry.prm_lms);
		data+='&prm_hostel='+encodeURIComponent($scope.entry.prm_hostel)+'&prm_parents='+encodeURIComponent($scope.entry.prm_parents);
		$http.post($scope.ajax_url+'add',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:3500});
			if($scope.appConfig.showLog){$log.error(response);}
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.entry.name='';$scope.entry.mobile='';$scope.entry.password='';
			$scope.loadRows();
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			if($scope.appConfig.showLog){$log.error(response);}
			
		});
        }
    }; 
	$scope.updateRow=function (){	
    	if($scope.selectedRow.name.length< 1){
		swal({title:"Please enter name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.admin_id.length< 1){
		swal({title:"Please enter admin id",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&name='+encodeURIComponent($scope.selectedRow.name)+'&type_auth='+encodeURIComponent($scope.selectedRow.type_auth)+'&mobile='+encodeURIComponent($scope.selectedRow.mobile);
		data+='&prm_std_info='+encodeURIComponent($scope.selectedRow.prm_std_info)+'&prm_stf_info='+encodeURIComponent($scope.selectedRow.prm_stf_info);
		data+='&prm_finance='+encodeURIComponent($scope.selectedRow.prm_finance)+'&prm_class='+encodeURIComponent($scope.selectedRow.prm_class);
		data+='&prm_library='+encodeURIComponent($scope.selectedRow.prm_library)+'&prm_stationary='+encodeURIComponent($scope.selectedRow.prm_stationary);
		data+='&prm_transport='+encodeURIComponent($scope.selectedRow.prm_transport)+'&prm_lms='+encodeURIComponent($scope.selectedRow.prm_lms);
		data+='&prm_hostel='+encodeURIComponent($scope.selectedRow.prm_hostel)+'&prm_parents='+encodeURIComponent($scope.selectedRow.prm_parents);
		data+='&admin_id='+encodeURIComponent($scope.selectedRow.admin_id)
		$http.post($scope.ajax_url+'update',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			if($scope.appConfig.showLog){$log.error(response);}
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			if($scope.appConfig.showLog){$log.error(response);}
			
		});
        }
    }; 
	//change status
	$scope.changeStatus=function (row,status){	
		swal({
			title: 'Are you sure?',
			text: "Update status of ("+row.name+")!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: '<strong>Yes, Update <i class="icon-circle-right2 ml-2"></i></strong>',
			cancelButtonText: 'No, Go Back <i class="icon-circle-left2 ml-2"></i>',
			confirmButtonClass: 'btn btn-warning',
			cancelButtonClass: 'btn btn-light',
			buttonsStyling: false
		}).then(function (result) {
			if(result.value){
				var msg='';
				var data='rid='+row.mid+'&status='+status;
				$http.post($scope.ajax_url+'changeStatus',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						if($scope.appConfig.showLog){$log.error(response);}
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						row.status=status;
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					if($scope.appConfig.showLog){$log.error(response);}
				});
			}
		});
	
	};
	//delete account
	$scope.delRow=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Delete account of ("+row.name+"). You can not recover it later.!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: '<strong>Yes, Delete it <i class="icon-circle-right2 ml-2"></i></strong>',
			cancelButtonText: 'No, Go Back <i class="icon-circle-left2 ml-2"></i>',
			confirmButtonClass: 'btn btn-warning',
			cancelButtonClass: 'btn btn-light',
			buttonsStyling: false
		}).then(function (result) {
			if(result.value){
				var msg='';
				var data='rid='+row.mid;
				$http.post($scope.ajax_url+'delete',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						if($scope.appConfig.showLog){$log.error(response);}
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadRows();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					if($scope.appConfig.showLog){$log.error(response);}
				});
			}
		});
	
	};
	//change password
	$scope.changePassword=function (){	
    	if($scope.entry.password.length< 1){
		swal({title:"Please enter new password",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&password='+encodeURIComponent($scope.entry.password);
		$http.post($scope.ajax_url+'updatePassword',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			if($scope.appConfig.showLog){$log.error(response);}
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.entry.password='';
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			if($scope.appConfig.showLog){$log.error(response);}
			
		});
        }
    };
	/////////////////////////////////////////////
	$('#add').on('shown.bs.modal', function () {config.enter='add';});
	$('#edit').on('shown.bs.modal', function () {config.enter='update';});
	$('#add').on('hidden.bs.modal', function () {config.enter='search';});
	$('#edit').on('hidden.bs.modal', function () {config.enter='search';});
	///////autoload functions////////////////////
	$scope.loadRows();
	/* $timeout(function(){$scope.loadRows();},1000); */


});