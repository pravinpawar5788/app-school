app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys,Pubnub){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url=''+config.ajaxModule+'transport/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.filter={type:'',route_id:'',vehicle_id:'',class:''};
	$scope.registration={fare:''};
	$scope.route={name:'',route_id:'',fare:''};
	$scope.vehicle={reg_no:'',driver:'',capacity:'',owner:'',amount:'',contract:'',vehicle_id:''};
    $scope.staffs={};
    $scope.students={};
    $scope.allRoutes={};
    $scope.vehicleRoutes={};
    $scope.routeVehicles={};
	
    $scope.promise={};
	$scope.message='';
	$scope.member={};
	$scope.usrid='';

	/////////HOTKEYS/////////////////////////////////////////////////////	 
	// hotkeys.add({
	  // combo: 'enter',	//enter
	  // description: 'Search staff',
	  // allowIn: ['INPUT', 'SELECT', 'TEXTAREA'],
	  // callback: function(event, hotkey) {
		// event.preventDefault();
		// $scope.loadRows();
	  // }
	// }); 
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
		var data={'search':$scope.searchText,'page':config.currentPage,'type':$scope.filter.type,'route_id':$scope.route.route_id,'vehicle_id':$scope.vehicle.vehicle_id,'sortby':$scope.sortString};
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
    	if($scope.selectedRow.fee< 0){
		swal({title:"Please enter valid fare",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&fare='+encodeURIComponent($scope.selectedRow.fee);
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
			text: "Remove member("+row.passenger_name+") from transportation.",
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
	
	//////////////ROUTE FUNCTIONS/////////////////////////////////////	
    $scope.loadRoutes=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterRoutes',config.postConfig).then(function(response){
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
	$scope.saveRoute=function (){	
    	if($scope.route.name.length< 1){
		swal({title:"Please enter route name",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='name='+encodeURIComponent($scope.route.name)+'&fare='+encodeURIComponent($scope.route.fare);
		$http.post($scope.ajax_url+'addRoute',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.route.name='';
			$scope.loadRoutes();
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);			
		});
        }
    }; 
	$scope.updateRoute=function (){	
    	if($scope.selectedRow.name.length< 1){
		swal({title:"Please enter route name",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&name='+encodeURIComponent($scope.selectedRow.name);
		data+='&fare='+encodeURIComponent($scope.selectedRow.fare);
		$http.post($scope.ajax_url+'updateRoute',data,config.postConfig).then(function(response){
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
	$scope.delRoute=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Delete route of ("+row.name+"). You can not recover it later.!",
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
				$http.post($scope.ajax_url+'deleteRoute',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadRoutes();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	
	//////////////VEHICLE ROUTES FUNCTIONS//////////////////////////////	
    $scope.loadVehicles=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'owner':$scope.filter.owner,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterVehicles',config.postConfig).then(function(response){
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
	$scope.saveVehicle=function (){	
    	if($scope.vehicle.reg_no.length< 1){
		swal({title:"Please enter registrtation number",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.vehicle.driver.length< 1){
		swal({title:"Please enter driver name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.vehicle.owner.length< 1){
		swal({title:"Please select vehicle owner ship",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.vehicle.capacity< 1){
		swal({title:"Please enter seating capacity",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='reg_no='+encodeURIComponent($scope.vehicle.reg_no)+'&driver='+encodeURIComponent($scope.vehicle.driver);
		data+='&capacity='+encodeURIComponent($scope.vehicle.capacity)+'&owner='+encodeURIComponent($scope.vehicle.owner);
		data+='&contract='+encodeURIComponent($scope.vehicle.contract)+'&amount='+encodeURIComponent($scope.vehicle.amount);
		$http.post($scope.ajax_url+'addVehicle',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.vehicle.reg_no='';$scope.vehicle.driver='';$scope.vehicle.capacity='';
			$scope.loadVehicles();
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);			
		});
        }
    }; 
	$scope.updateVehicle=function (){	
    	if($scope.selectedRow.reg_no.length< 1){
		swal({title:"Please enter registrtation number",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.driver.length< 1){
		swal({title:"Please enter driver name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.capacity.length< 1){
		swal({title:"Please enter seating capacity",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.owner.length< 1){
		swal({title:"Please select vehicle owner",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&reg_no='+encodeURIComponent($scope.selectedRow.reg_no)+'&driver='+encodeURIComponent($scope.selectedRow.driver);
		data+='&capacity='+$scope.selectedRow.capacity+'&owner='+encodeURIComponent($scope.selectedRow.owner)+'&contract='+encodeURIComponent($scope.selectedRow.contract);
		data+='&amount='+$scope.selectedRow.amount;
		$http.post($scope.ajax_url+'updateVehicle',data,config.postConfig).then(function(response){
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
	$scope.delVehicle=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Delete vehicle of ("+row.reg_no+"). You can not recover it later.!",
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
				$http.post($scope.ajax_url+'deleteVehicle',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadVehicles();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	
	//////////////VEHICLE FUNCTIONS/////////////////////////////////////	
    $scope.loadAllRoutes=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterAllRoutes',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.allRoutes=response.data;
			//config.responseNumRows=$scope.responseData.total_rows;
			//$scope.validatePagination();
			
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});
    
    };
	$scope.loadVehicleRoutes=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'vehicle_id':$scope.selectedRow.mid,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterVehicleRoutes',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.vehicleRoutes=response.data;
			//config.responseNumRows=$scope.responseData.total_rows;
			//$scope.validatePagination();
			
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});
    
    };
	$scope.saveVehicleRoute=function (){	
    	if($scope.route.route_id.length< 1){
		swal({title:"Please select a route",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+encodeURIComponent($scope.selectedRow.mid)+'&route_id='+encodeURIComponent($scope.route.route_id);
		$http.post($scope.ajax_url+'addRouteVehicle',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.loadVehicleRoutes();
			$scope.loadVehicles();
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);			
		});
        }
    }; 
	$scope.delVehicleRoute=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Remove vehicle route. You can not recover it later.!",
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
				var data='rid='+row.mid;
				$http.post($scope.ajax_url+'deleteVehicleRoute',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadVehicleRoutes();
						$scope.loadVehicles();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	$scope.loadRouteVehicles=function (){		
		var data={'search':$scope.searchText,'page':config.currentPage,'route_id':$scope.route.route_id,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterRouteVehicles',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.routeVehicles=response.data;
			$scope.registration.fare=response.data.fare;
			//config.responseNumRows=$scope.responseData.total_rows;
			//$scope.validatePagination();
			
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});
    
    };
	
	
	$scope.updateFare=function (){	
    	if($scope.stock.qty< 1){
		swal({title:"Please enter quantity",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.stock.amount< 1){
		swal({title:"Please enter bill amount",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&qty='+encodeURIComponent($scope.stock.qty)+'&amount='+encodeURIComponent($scope.stock.amount);
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
	$scope.registerStaff=function (){	
    	if($scope.selectedRow.mid.length< 1){
		swal({title:"Please select a valid member",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.route.route_id.length< 1){
		swal({title:"Please select a route",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.vehicle.vehicle_id.length< 1){
		swal({title:"Please select a vehicle",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.registration.fare< 0){
		swal({title:"Please enter a valid fare or leave blank",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='user_id='+encodeURIComponent($scope.selectedRow.mid)+'&route_id='+encodeURIComponent($scope.route.route_id)+'&vehicle_id='+encodeURIComponent($scope.vehicle.vehicle_id);
		data+='&fare='+encodeURIComponent($scope.registration.fare)+'&user_type=staff';
		$http.post($scope.ajax_url+'add',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.registration.fare='';
			$scope.loadRows();
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);			
		});
        }
    }; 
	$scope.registerStudent=function (){	
    	if($scope.selectedRow.mid.length< 1){
		swal({title:"Please select a valid member",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.route.route_id.length< 1){
		swal({title:"Please select a route",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.vehicle.vehicle_id.length< 1){
		swal({title:"Please select a vehicle",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.registration.fare< 0){
		swal({title:"Please enter a valid fare or leave blank",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='user_id='+encodeURIComponent($scope.selectedRow.mid)+'&route_id='+encodeURIComponent($scope.route.route_id)+'&vehicle_id='+encodeURIComponent($scope.vehicle.vehicle_id);
		data+='&fare='+encodeURIComponent($scope.registration.fare)+'&user_type=student';
		$http.post($scope.ajax_url+'add',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.registration.fare='';
			$scope.loadRows();
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);			
		});
        }
    }; 
	///////autoload functions////////////////////
	$scope.loadRows();
	$scope.loadAllRoutes();
	// $timeout(function(){$scope.loadMember();},1000); 


});