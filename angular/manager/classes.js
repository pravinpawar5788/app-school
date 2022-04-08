app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url=config.ajaxModule+'classes/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.filter={title:'',target:''};
	$scope.class={title:'',incharge_id:'',display:'',fee:''};
	$scope.entry={name:'',title:'',amount:'',type:''};
    $scope.promise={};
	$scope.message='';
	$scope.usrid='';

	/////////HOTKEYS/////////////////////////////////////////////////////	 
	hotkeys.add({
	  combo: 'enter',	//enter
	  description: 'Search class',
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
	  description: 'register new class.',
	  //allowIn: ['INPUT', 'SELECT', 'TEXTAREA'],
	  callback: function(event, hotkey) {
		event.preventDefault();
		$('#add').modal('show');
	  }
	});
	
	hotkeys.add({
	  combo: 'shift+s',	//ctrl+enter
	  description: 'Save new class',
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
		var data={'search':$scope.searchText,'page':config.currentPage,'status':$scope.filter.status,'class_id':$scope.filter.class,'session_id':$scope.filter.session,'fee':$scope.filter.fee,'gender':$scope.filter.gender,'blood_group':$scope.filter.blood_group,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filter',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.responseData=response.data;
			config.responseNumRows=$scope.responseData.total_rows;
			$scope.validatePagination();			
			
		},function(response){	
			$scope.enableButtons();		
            // swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
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
    	if($scope.class.title.length< 1){
		swal({title:"Please enter class name",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='title='+encodeURIComponent($scope.class.title)+'&incharge_id='+encodeURIComponent($scope.class.incharge_id)+'&fee='+encodeURIComponent($scope.class.fee);
		$http.post($scope.ajax_url+'add',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.class.title='';$scope.class.fee='';
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
    	if($scope.selectedRow.title.length< 1){
		swal({title:"Please enter class name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.incharge_id.length< 1){
		swal({title:"Please select class incharge",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&title='+encodeURIComponent($scope.selectedRow.title)+'&incharge_id='+encodeURIComponent($scope.selectedRow.incharge_id);
		data+='&display_order='+encodeURIComponent($scope.selectedRow.display_order)+'&promotion_id='+encodeURIComponent($scope.selectedRow.promotion_id);
		data+='&fee='+encodeURIComponent($scope.selectedRow.fee);
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
	// $scope.changeStatus=function (row,status){	
		// swal({
			// title: 'Are you sure?',
			// text: "Update status of ("+row.name+")!",
			// type: 'warning',
			// showCancelButton: true,
			// confirmButtonText: '<strong>Yes, Update <i class="icon-circle-right2 ml-2"></i></strong>',
			// cancelButtonText: 'No, Go Back <i class="icon-circle-left2 ml-2"></i>',
			// confirmButtonClass: 'btn btn-warning',
			// cancelButtonClass: 'btn btn-light',
			// buttonsStyling: false
		// }).then(function (result) {
			// if(result.value){
				// var msg='';
				// var data='rid='+row.mid+'&status='+status;
				// $http.post($scope.ajax_url+'changeStatus',data,config.postConfig).then(function(response){
					// msg=response.data.message;
					// if(response.data.error===true){
						// swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
					// $log.error(response);	
					// }else{
						// swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						// row.status=status;
					// }
				// },function (response){
					// swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					// $log.error(response);
				// });
			// }
		// });
	
	// };
	//delete account
	$scope.delRow=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Delete class of ("+row.title+"). You can not recover it later.!",
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

	$scope.createFeeRow=function (){	
    	if($scope.entry.type.length< 1){
		swal({title:"Please select category",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.entry.title.length< 1){
		swal({title:"Please enter record title",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.entry.amount.length< 1){
		swal({title:"Please provide amount",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&type='+encodeURIComponent($scope.entry.type)+'&title='+encodeURIComponent($scope.entry.title);
		data+='&amount='+encodeURIComponent($scope.entry.amount);
		$http.post($scope.ajax_url+'createFeeVoucher',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.entry.title='';$scope.entry.amount='';
			// $scope.loadRows();
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			
		});
        }
    }; 
	//send sms to single staff
	$scope.sendSingleSms=function (){
		var data={'message':$scope.message,'rid':$scope.selectedRow.mid,'target':$scope.filter.target};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'sendSms',config.postConfig).then(function(response){
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