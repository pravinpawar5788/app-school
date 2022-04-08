"use strict";
app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys,Pubnub){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url='../../'+config.ajaxModule+'campus/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.filter={status:'',module:''};
	$scope.entry={name:'',mobile:'',password:'',title:'',template:'',year:''};
	$scope.promise={};
	$scope.message='';
	$scope.record={};
	$scope.settings={};
	$scope.rid='';

	
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
    $scope.loadAdmins=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString,'rid':$scope.rid};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterAdmins',config.postConfig).then(function(response){
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
	$scope.loadHistory=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString,'rid':$scope.rid};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterHistory',config.postConfig).then(function(response){
			$log.info(response);
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
	//load campus
	$scope.loadRecord=function (){
		var data={'rid':$scope.rid};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'load',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.record=response.data.output;	
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});    
    };	
	/////////////CRUD FUNCTIONS//////////////////
		
	
	$scope.saveAdmin=function (){	
    	if($scope.entry.name.length< 1){
		swal({title:"Please enter admin name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.entry.password.length< 1){
		swal({title:"Please enter admin password",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+encodeURIComponent($scope.rid)+'&name='+encodeURIComponent($scope.entry.name);
		data+='&mobile='+encodeURIComponent($scope.entry.mobile)+'&password='+encodeURIComponent($scope.entry.password);
		$http.post($scope.ajax_url+'addAdmin',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:3500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:5000});
			$scope.entry.name='';$scope.entry.mobile='';$scope.entry.password='';
			$scope.loadAdmins();
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			
		});
        }
    }; 
	$scope.updateAdmin=function (){	
    	if($scope.selectedRow.name.length< 1){
		swal({title:"Please enter name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.mobile.length< 1){
		swal({title:"Please enter mobile number",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.user_id.length< 1){
		swal({title:"Please enter user id",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&name='+encodeURIComponent($scope.selectedRow.name)+'&mobile='+encodeURIComponent($scope.selectedRow.mobile);
		data+='&user_id='+encodeURIComponent($scope.selectedRow.user_id);
		$http.post($scope.ajax_url+'updateAdmin',data,config.postConfig).then(function(response){
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
	$scope.updateAdminPassword=function (){	
    	if($scope.entry.password.length< 1){
		swal({title:"Please enter new password",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&password='+encodeURIComponent($scope.entry.password);
		$http.post($scope.ajax_url+'updateAdminPassword',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.entry.password='';
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			
		});
        }
    }; 
	$scope.delAdmin=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Delete admin("+row.name+"). All Data of this admin account deleted. You can not recover it later.!",
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
				$http.post($scope.ajax_url+'deleteAdmin',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadAdmins();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	
	///////autoload functions////////////////////
	$timeout(function(){$scope.loadRecord();},1000); 


});