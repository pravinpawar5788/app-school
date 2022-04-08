app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url=''+config.ajaxModule+'feedback/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.filter={status:''};
	$scope.entry={title:'',message:''};
    $scope.students={};
    $scope.promise={};
	$scope.message='';
	$scope.usrid='';

	/////////HOTKEYS/////////////////////////////////////////////////////	 
	hotkeys.add({
	  combo: 'enter',	//enter
	  description: 'Search data',
	  allowIn: ['INPUT', 'SELECT'],
	  callback: function(event, hotkey) {
		event.preventDefault();
		$scope.loadRows();
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
    	if($scope.entry.title.length< 1){
		swal({title:"Please enter feedback subject",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.entry.message.length< 1){
		swal({title:"Please enter feedback message",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='title='+encodeURIComponent($scope.entry.title)+'&message='+encodeURIComponent($scope.entry.message);
		//data+='&admission_number='+encodeURIComponent($scope.student.admission_number);
		$http.post($scope.ajax_url+'add',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:3500});
			if($scope.appConfig.showLog){$log.error(response);}
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.entry.title='';$scope.entry.message='';
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
        }else if($scope.selectedRow.parent_id.length< 1){
		swal({title:"Please enter parent id",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.cnic.length< 1){
		swal({title:"Please enter father cnic",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&name='+encodeURIComponent($scope.selectedRow.name)+'&cnic='+encodeURIComponent($scope.selectedRow.cnic)+'&mobile='+encodeURIComponent($scope.selectedRow.mobile);
		data+='&parent_id='+encodeURIComponent($scope.selectedRow.parent_id)
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
	//load students of a parent
	$scope.loadStudents=function (row){
		if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		var data={'rid':row.mid};
		config.postConfig.params=data;
		$http.get($scope.ajax_url+'load',config.postConfig).then(function(response){
			$log.info(response);
			$scope.students=response.data.rows;
		},function(response){
			if($scope.appConfig.showLog){$log.error(response);}
			
		});
        }
    }; 
	///////autoload functions////////////////////
	$scope.loadRows();
	/* $timeout(function(){$scope.loadRows();},1000); */


});