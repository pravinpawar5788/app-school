app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys,Pubnub){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url='../'+config.ajaxModule+'finance/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.sms={target:'',message:''};
	$scope.filter={account:'',month:'',year:''};
	$scope.entry={title:'',amount:'',account:'',date:'',description:'',credit_account:''};
	
	$scope.voucher={};
    $scope.promise={};
	$scope.member={};
	$scope.usrid='';

	/////////HOTKEYS/////////////////////////////////////////////////////	 
	hotkeys.add({
	  combo: 'enter',	//enter
	  description: 'Search /save record',
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
	$scope.addkey=function (key){$scope.sms.message= $scope.sms.message+' {'+key+'} ';}; 
	
	//////////////HELPER FUNCTIONS/////////////////////////////////////	
    $scope.loadRows=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'month':$scope.filter.month,'year':$scope.filter.year,'account':$scope.filter.account,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterExpenses',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.responseData=response.data;
			config.responseNumRows=$scope.responseData.total_rows;
			$scope.validatePagination();
			
		},function(response){	
			$scope.enableButtons();		
            // swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			if($scope.appConfig.showLog){$log.error(response);}
		});
    
    };	
	$scope.saveRow=function (){	
		if($scope.entry.title.length< 1){
		swal({title:"Please enter record title",type:'info',showConfirmButton:false,timer:2000});
		}else if($scope.entry.amount< 1){
		swal({title:"Please enter amount",type:'info',showConfirmButton:false,timer:2000});
		}else if($scope.entry.account.length< 1){
		swal({title:"Please select account",type:'info',showConfirmButton:false,timer:2000});
		}else if($scope.entry.date.length< 1){
		swal({title:"Please select date",type:'info',showConfirmButton:false,timer:2000});
		}else if($scope.entry.credit_account.length< 1){
		swal({title:"Please select credit account",type:'info',showConfirmButton:false,timer:2000});
		}else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
		}else{				
			$scope.disableButtons();
			var data='title='+encodeURIComponent($scope.entry.title)+'&amount='+encodeURIComponent($scope.entry.amount)+'&account='+encodeURIComponent($scope.entry.account);
			data+='&date='+encodeURIComponent($scope.entry.date)+'&description='+encodeURIComponent($scope.entry.description);
			data+='&credit_account='+encodeURIComponent($scope.entry.credit_account);
			$http.post($scope.ajax_url+'addExpense',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				if($scope.appConfig.showLog){$log.error(response);}
				}else{
				if($scope.appConfig.showLog){$log.info(response);}
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.entry.title='';$scope.entry.amount='';$scope.entry.description='';
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
		if($scope.selectedRow.title.length< 1){
		swal({title:"Please enter record title",type:'info',showConfirmButton:false,timer:2000});
		}else if($scope.selectedRow.amount< 1){
		swal({title:"Please enter amount",type:'info',showConfirmButton:false,timer:2000});
		}else if($scope.selectedRow.type.length< 1){
		swal({title:"Please select type",type:'info',showConfirmButton:false,timer:2000});
		}else if($scope.selectedRow.date.length< 1){
		swal({title:"Please select date",type:'info',showConfirmButton:false,timer:2000});
		}else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
		}else{				
			$scope.disableButtons();
			var data='rid='+$scope.selectedRow.mid+'&title='+encodeURIComponent($scope.selectedRow.title)+'&amount='+encodeURIComponent($scope.selectedRow.amount);
			data+='&date='+encodeURIComponent($scope.selectedRow.date)+'&description='+encodeURIComponent($scope.selectedRow.description);
			$http.post($scope.ajax_url+'updateExpense',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				if($scope.appConfig.showLog){$log.error(response);}
				}else{
				if($scope.appConfig.showLog){$log.info(response);}
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.loadRows();
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				if($scope.appConfig.showLog){$log.error(response);}
				
			});
		}		
    }; 
	$scope.delRow=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Remove record("+row.title+") .",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: '<strong>Yes, Remove it <i class="icon-circle-right2 ml-2"></i></strong>',
			cancelButtonText: 'No, Go Back <i class="icon-circle-left2 ml-2"></i>',
			confirmButtonClass: 'btn btn-warning',
			cancelButtonClass: 'btn btn-light',
			buttonsStyling: false
		}).then(function (result) {
			if(result.value){
				var msg='';
				var data='rid='+row.mid+'';
				$http.post($scope.ajax_url+'deleteExpense',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						if($scope.appConfig.showLog){$log.error(response);}
					}else{
						if($scope.appConfig.showLog){$log.info(response);}
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
	/////////////////////////////////////////////
	$('#add').on('shown.bs.modal', function () {config.enter='add';});
	$('#edit').on('shown.bs.modal', function () {config.enter='update';});
	$('#add').on('hidden.bs.modal', function () {config.enter='search';});
	$('#edit').on('hidden.bs.modal', function () {config.enter='search';});
	///////autoload functions////////////////////
	$scope.loadRows();
	// $timeout(function(){$scope.loadMember();},1000); 


});