"use strict";
app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url=config.ajaxModule+'campus/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.filter={status:'',filter_key:'',filter_value:''};
	$scope.entry={name:'',contact_number:'',address:'',password:'',mname:'',mobile:'',email:''};
    $scope.promise={};
	$scope.message='';
	$scope.usrid='';

	/////////HOTKEYS/////////////////////////////////////////////////////	 
	hotkeys.add({
	  combo: 'enter',	//enter
	  description: 'Search',
	  allowIn: ['INPUT', 'SELECT'],
	  callback: function(event, hotkey) {
		event.preventDefault();
		$scope.loadRows();
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
		var data={'search':$scope.searchText,'page':config.currentPage,'status':$scope.filter.status,'filter_key':$scope.filter.filter_key,'filter_value':$scope.filter.filter_value,'sortby':$scope.sortString};
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
    	if($scope.entry.name.length< 1){
		swal({title:"Please enter name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.entry.contact_number.length< 1){
		swal({title:"Please enter campus contact number",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.entry.address.length< 1){
		swal({title:"Please enter campus address",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.entry.mname.length< 1){
		swal({title:"Please enter manager name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.entry.email.length< 1){
		swal({title:"Please enter manager email address",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.entry.password.length< 1){
		swal({title:"Please enter manager account password",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='name='+encodeURIComponent($scope.entry.name)+'&mobile='+encodeURIComponent($scope.entry.mobile);
		data+='&contact_number='+encodeURIComponent($scope.entry.contact_number)+'&password='+encodeURIComponent($scope.entry.password);
		data+='&mname='+encodeURIComponent($scope.entry.mname)+'&address='+encodeURIComponent($scope.entry.address);
		data+='&email='+encodeURIComponent($scope.entry.email);
		$http.post($scope.ajax_url+'add',data,config.postConfig).then(function(response){
			$log.info(response);
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:3500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:5000});
			$scope.entry.name='';$scope.entry.mobile='';$scope.entry.address='';$scope.entry.password='';$scope.entry.contact_number='';
			$scope.entry.mname='';$scope.entry.email='';
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
		swal({title:"Please enter campus name",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&name='+encodeURIComponent($scope.selectedRow.name)+'&contact_number='+encodeURIComponent($scope.selectedRow.contact_number);
		data+='&address='+encodeURIComponent($scope.selectedRow.address);
		
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
	//delete account
	$scope.delRow=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Delete campus("+row.name+"). All data of this campus will be removed. You can not recover it later.!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: '<strong>Yes,  Delete it <i class="icon-circle-right2 ml-2"></i></strong>',
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
	
	///////autoload functions////////////////////
	$scope.loadRows();


});