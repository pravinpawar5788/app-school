app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url=config.ajaxModule+'staff/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.filter={status:'',role:'',salary:'',gender:'',blood_group:'',filter:''};
	$scope.staff={name:'',cnic:'',mobile:'',salary:'',home_phone:'',guardian_name:'',gender:'male',blood_group:'',staff_id:'',role:'',email:'',postal_address:'',home_address:'',qualification:'',anual_increment:'',contract:'',experience:'',favourite_subject:''};
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
	  description: 'Opne new account registration form.',
	  //allowIn: ['INPUT', 'SELECT', 'TEXTAREA'],
	  callback: function(event, hotkey) {
		event.preventDefault();
		$('#add').modal('show');
	  }
	});
	
	hotkeys.add({
	  combo: 'shift+s',	//ctrl+enter
	  description: 'Save new staff while creating new account',
	  //allowIn: ['INPUT', 'SELECT', 'TEXTAREA'],
	  callback: function(event, hotkey) {
		event.preventDefault();
		$scope.saveRow();
	  }
	});
	/////////GENERAL FUNCTIONS/////////////////////////////////////////////////////	          
    $scope.sortBy= function (value){ $scope.sortString=$scope.processSort(value);$scope.loadRows()};
    $scope.clearFilter= function (){ $.each($scope.filter, function(index){$scope.filter[index]='';}); };
    $scope.filterGetString= function (){ var data=''; $.each($scope.filter, function(index,value){data+='&'+index+'='+value;}); return data;};
    $scope.showFilter= function (){var show=false; $.each($scope.filter, function(index,value){if(value !==''){show=true;} }); return show; };
    $scope.clearResponse= function (){ $scope.responseText='';$scope.responseModelText=''; };
    $scope.selectRow = function (row) {$scope.selectedRow = row;};
	$scope.addkey=function (key){$scope.message= $scope.message+' {'+key+'} ';};     
	
	//////////////HELPER FUNCTIONS/////////////////////////////////////	
    $scope.loadRows=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'status':$scope.filter.status,'role_id':$scope.filter.role,'salary':$scope.filter.salary,'gender':$scope.filter.gender,'blood_group':$scope.filter.blood_group,'filter':$scope.filter.filter,'sortby':$scope.sortString};
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
			$log.error(response);
		});
		/*
		//SET INTERVAL TO AUTO UPDATE THE ROWS
		$scope.promise=$interval(function () {
			$http.get($scope.ajax_url+'filterBrandRows',config.postConfig).then(function(response){
			$scope.responseData=response.data;
			config.responseNumRows=$scope.responseData.total_rows;
			$scope.validatePagination();
			},function(response){	
				$log.error(response);
			});
		}, 10000);*/
    
    };	
	$scope.saveRow=function (){	
    	if($scope.staff.name.length< 1){
		swal({title:"Please enter full name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.staff.cnic.length< 1){
		swal({title:"Please enter cnic",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.staff.mobile.length< 1){
		swal({title:"Please mobile number",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.staff.role< 1){
		swal({title:"Please select designation",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.staff.salary< 1){
		swal({title:"Please provide basic salary",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.staff.staff_id.length< 1){
		swal({title:"Please provide staff id",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='name='+encodeURIComponent($scope.staff.name)+'&cnic='+encodeURIComponent($scope.staff.cnic)+'&mobile='+encodeURIComponent($scope.staff.mobile);
		data+='&salary='+encodeURIComponent($scope.staff.salary)+'&home_phone='+encodeURIComponent($scope.staff.home_phone)+'&guardian_name='+encodeURIComponent($scope.staff.guardian_name);
		data+='&role_id='+encodeURIComponent($scope.staff.role)+'&gender='+encodeURIComponent($scope.staff.gender)+'&blood_group='+encodeURIComponent($scope.staff.blood_group);
		data+='&qualification='+encodeURIComponent($scope.staff.qualification)+'&postal_address='+encodeURIComponent($scope.staff.postal_address)+'&home_address='+encodeURIComponent($scope.staff.home_address);
		data+='&staff_id='+encodeURIComponent($scope.staff.staff_id)+'&password='+encodeURIComponent($scope.staff.password)+'&email='+encodeURIComponent($scope.staff.email);
		data+='&favourite_subject='+encodeURIComponent($scope.staff.favourite_subject)+'&experience='+encodeURIComponent($scope.staff.experience)+'&contract='+encodeURIComponent($scope.staff.contract);
		data+='&anual_increment='+encodeURIComponent($scope.staff.anual_increment);
		$http.post($scope.ajax_url+'add',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.staff.name='';$scope.staff.cnic='';$scope.staff.mobile='';$scope.staff.salary='';$scope.staff.home_phone='';$scope.staff.guardian_name='';
			$scope.staff.role='';$scope.staff.gender='male';$scope.staff.blood_group='';$scope.staff.qualification='';$scope.staff.postal_address='';$scope.staff.home_address='';
			$scope.staff.staff_id='';$scope.staff.password='';$scope.staff.email='';$scope.staff.favourite_subject='';$scope.staff.experience='';$scope.staff.contract='';
			$scope.staff.anual_increment='';
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
    	if($scope.selectedRow.name.length< 1){
		swal({title:"Please enter full name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.cnic.length< 1){
		swal({title:"Please enter cnic",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.mobile.length< 1){
		swal({title:"Please mobile number",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.role_id< 1){
		swal({title:"Please select designation",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.salary< 1){
		swal({title:"Please provide basic salary",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.staff_id.length< 1){
		swal({title:"Please provide staff id",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&name='+encodeURIComponent($scope.selectedRow.name)+'&cnic='+encodeURIComponent($scope.selectedRow.cnic)+'&mobile='+encodeURIComponent($scope.selectedRow.mobile);
		data+='&salary='+encodeURIComponent($scope.selectedRow.salary)+'&home_phone='+encodeURIComponent($scope.selectedRow.home_phone)+'&guardian_name='+encodeURIComponent($scope.selectedRow.guardian_name);
		data+='&role_id='+encodeURIComponent($scope.selectedRow.role_id)+'&gender='+encodeURIComponent($scope.selectedRow.gender)+'&blood_group='+encodeURIComponent($scope.selectedRow.blood_group);
		data+='&qualification='+encodeURIComponent($scope.selectedRow.qualification)+'&postal_address='+encodeURIComponent($scope.selectedRow.postal_address)+'&home_address='+encodeURIComponent($scope.selectedRow.home_address);
		data+='&staff_id='+encodeURIComponent($scope.selectedRow.staff_id)+'&email='+encodeURIComponent($scope.selectedRow.email)+'&anual_increment='+encodeURIComponent($scope.selectedRow.anual_increment);
		data+='&favourite_subject='+encodeURIComponent($scope.selectedRow.favourite_subject)+'&experience='+encodeURIComponent($scope.selectedRow.experience)+'&contract='+encodeURIComponent($scope.selectedRow.contract);
		$http.post($scope.ajax_url+'update',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			
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
					$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						row.status=status;
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
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
	//change password
	$scope.changePassword=function (){	
    	if($scope.staff.password.length< 1){
		swal({title:"Please enter new password",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&password='+encodeURIComponent($scope.staff.password);
		$http.post($scope.ajax_url+'updatePassword',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.staff.password='';
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			
		});
        }
    }; 
	//send sms to all staff
	$scope.sendListSms=function (){
		var data={'message':$scope.message,'status':$scope.filter.status,'role_id':$scope.filter.role,'salary':$scope.filter.salary,'gender':$scope.filter.gender,'blood_group':$scope.filter.blood_group};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'sendListSms',config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.clearFilter();
			$scope.message='';
			}
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});    
    };	
	//send sms to single staff
	$scope.sendSingleSms=function (){
		var data={'message':$scope.message,'rid':$scope.selectedRow.mid};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'sendSingleSms',config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.message='';
			}
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});    
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