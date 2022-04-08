app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys,Pubnub){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url='../'+config.ajaxModule+'stationary/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.filter={type:'',price:'',stock:'',class:'',purchaseLog:false};
	$scope.item={type:'',name:'',price:'', description:'',qty:'',amount:''};
	$scope.issue={item:'',qty:'',voucher_id:''};
	$scope.stock={amount:'',qty:'',credit_account:''};
    $scope.vouchers={};
    $scope.staffs={};
    $scope.students={};
    $scope.stationary={};
    $scope.selectedItem={item_price:0};
	
    $scope.promise={};
	$scope.message='';
	$scope.member={};
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
	/////////GENERAL FUNCTIONS/////////////////////////////////////////////////////	             
    $scope.sortBy= function (value){ $scope.sortString=$scope.processSort(value);$scope.loadRows()};
    $scope.clearFilter= function (){ $.each($scope.filter, function(index){$scope.filter[index]='';}); };
    $scope.filterGetString= function (){ var data=''; $.each($scope.filter, function(index,value){data+='&'+index+'='+value;}); return data;};
    $scope.showFilter= function (){var show=false; $.each($scope.filter, function(index,value){if(value !==''){show=true;} }); return show; };
    $scope.clearResponse= function (){ $scope.responseText='';$scope.responseModelText=''; };
    $scope.selectRow = function (row) {$scope.selectedRow = row;};
	$scope.addkey=function (key){$scope.message= $scope.message+' {'+key+'} ';}; 
    $scope.selectItem= function () {$scope.selectedItem=JSON.parse($scope.issue.item);};
	
	//////////////HISTORY FUNCTIONS/////////////////////////////////////	
    $scope.loadHistory=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString,'purchaseLog':$scope.filter.purchaseLog,'type':$scope.filter.type};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterHistory',config.postConfig).then(function(response){
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
	
	//////////////HELPER FUNCTIONS/////////////////////////////////////	
    $scope.loadRows=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'type':$scope.filter.type,'price':$scope.filter.price,'stock':$scope.filter.stock,'sortby':$scope.sortString};
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
    
    };	
	$scope.saveRow=function (){	
    	if($scope.item.name.length< 1){
		swal({title:"Please enter item name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.item.type.length< 1){
		swal({title:"Please select item type",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.item.price< 1){
		swal({title:"Please enter item price",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='item='+encodeURIComponent($scope.item.name)+'&description='+encodeURIComponent($scope.item.description)+'&type='+encodeURIComponent($scope.item.type);
		data+='&item_price='+encodeURIComponent($scope.item.price);
		$http.post($scope.ajax_url+'add',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.item.name='';$scope.item.price='';$scope.item.description='';
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);			
		});
        }
    }; 
	$scope.updateRow=function (){	
    	if($scope.selectedRow.item.length< 1){
		swal({title:"Please enter item name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.item_price.length< 1){
		swal({title:"Please enter item price",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&item='+encodeURIComponent($scope.selectedRow.item)+'&item_price='+encodeURIComponent($scope.selectedRow.item_price)+'&description='+encodeURIComponent($scope.selectedRow.description);
		$http.post($scope.ajax_url+'update',data,config.postConfig).then(function(response){
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
	$scope.delRow=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Delete item of ("+row.item+"). You can not recover it later.!",
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
	$scope.updateStock=function (){	
    	if($scope.stock.qty< 1){
		swal({title:"Please enter quantity",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.stock.amount< 1){
		swal({title:"Please enter bill amount",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.stock.credit_account< 1){
		swal({title:"Please select credit account",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&qty='+encodeURIComponent($scope.stock.qty)+'&amount='+encodeURIComponent($scope.stock.amount);
		data+='&credit_account='+encodeURIComponent($scope.stock.credit_account);
		$http.post($scope.ajax_url+'updateStock',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.stock.qty='';$scope.stock.amount='';
			$scope.loadRows();
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			
		});
        }
    }; 
	$scope.loadStaff=function (){
		var data={'search':$scope.searchText,'page':config.currentPage};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterStaff',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.staffs=response.data;
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});
    
    };
	$scope.loadStudents=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'class_id':$scope.filter.class};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterStudents',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.students=response.data;
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});
    
    };
	$scope.loadStationary=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'class_id':$scope.filter.class};
		config.postConfig.params=data;
		$http.get($scope.ajax_url+'filterStationary',config.postConfig).then(function(response){
			$scope.stationary=response.data;
		},function(response){	
			$log.error(response);
		});
    
    };		
	$scope.loadStudentVouchers=function (id){
		var data={'rid':id};
		config.postConfig.params=data;
		$http.get($scope.ajax_url+'filterStudentVouchers',config.postConfig).then(function(response){
			$scope.vouchers=response.data;
		},function(response){	
			$log.error(response);
		});
    
    };		
	$scope.issueItemStudent=function (){	
    	if($scope.selectedRow.mid.length< 1){
		swal({title:"Please select a valid studnt",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedItem.mid.length< 1){
		swal({title:"Please select a stationary item",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.issue.qty< 1){
		swal({title:"Please entery quantity to issue",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+encodeURIComponent($scope.selectedRow.mid)+'&item='+encodeURIComponent($scope.selectedItem.mid)+'&qty='+encodeURIComponent($scope.issue.qty);
		data+='&voucher_id='+encodeURIComponent($scope.issue.voucher_id);
		$http.post($scope.ajax_url+'issueStudentItem',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.loadStudentVouchers($scope.selectedRow.mid);
			$scope.issue.qty='';
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);			
		});
        }
    }; 
	$scope.issueItemStaff=function (){	
    	if($scope.selectedRow.mid.length< 1){
		swal({title:"Please select a valid studnt",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedItem.mid.length< 1){
		swal({title:"Please select a stationary item",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.issue.qty< 1){
		swal({title:"Please entery quantity to issue",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+encodeURIComponent($scope.selectedRow.mid)+'&item='+encodeURIComponent($scope.selectedItem.mid)+'&qty='+encodeURIComponent($scope.issue.qty);
		//data+='&voucher_id='+encodeURIComponent($scope.issue.voucher_id);
		$http.post($scope.ajax_url+'issueStaffItem',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.issue.qty='';
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);			
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
	// $timeout(function(){$scope.loadMember();},1000); 


});