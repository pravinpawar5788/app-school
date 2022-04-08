app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys,Pubnub){
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
	$scope.filter={status:'',session:'',class:'',fee:'',gender:'',blood_group:''};
	$scope.section={name:'',class_id:'',incharge_id:'',add:true};
	$scope.period={name:'',from_time:'',to_time:'',total_time:'',sort_order:'',type:'',number:'',add:true};
	$scope.feepackage={name:'',class_id:'',description:'',amount:'',last_exam_min_percent:'',add:true};
	$scope.entry={get_late_fee_fine:false};
	$scope.settings={};
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
    $scope.selectSection= function (row) {$scope.section = row;$scope.section.add=false;};
    $scope.selectPeriod= function (row) {$scope.period = row;$scope.period.add=false;};
    $scope.selectFeePackage= function (row) {$scope.feepackage = row;$scope.section.add=false;};
	
	//////////////SECTION FUNCTIONS/////////////////////////////////////	
    $scope.loadSections=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterSections',config.postConfig).then(function(response){
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
	$scope.saveSection=function (){	
    	if($scope.section.name.length< 1){
		swal({title:"Please enter name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.section.class_id.length< 1){
		swal({title:"Please select class",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		if($scope.section.add===true){	
			//add data
			var data='name='+encodeURIComponent($scope.section.name)+'&class_id='+encodeURIComponent($scope.section.class_id);
			data+='&incharge_id='+encodeURIComponent($scope.section.incharge_id);
			$http.post($scope.ajax_url+'addSection',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.section.name='';
				$scope.loadSections();
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}else{		
			//update data
			var data='rid='+encodeURIComponent($scope.section.mid)+'&name='+encodeURIComponent($scope.section.name);
			data+='&class_id='+encodeURIComponent($scope.section.class_id)+'&incharge_id='+encodeURIComponent($scope.section.incharge_id);
			$http.post($scope.ajax_url+'updateSection',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.section.name='';$scope.section.add=true;
				$scope.loadSections();
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}
        }
    }; 
	$scope.delSection=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Remove class section("+row.name+") of this campus. ",
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
				$http.post($scope.ajax_url+'deleteSection',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadSections();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	
	//////////////PERIOD FUNCTIONS/////////////////////////////////////	
    $scope.loadPeriods=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterPeriods',config.postConfig).then(function(response){
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
	$scope.savePeriod=function (){	
    	if($scope.period.name.length< 1){
		swal({title:"Please enter name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.period.from_time.length< 1){
		swal({title:"Please enter start time",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.period.to_time.length< 1){
		swal({title:"Please enter end time",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.period.total_time.length< 1){
		swal({title:"Please enter duration in minutes",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.period.type.length< 1){
		swal({title:"Please select type",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		if($scope.period.add===true){	
			//add data
			var data='name='+encodeURIComponent($scope.period.name)+'&type='+encodeURIComponent($scope.period.type);
			data+='&from_time='+encodeURIComponent($scope.period.from_time)+'&to_time='+encodeURIComponent($scope.period.to_time);
			data+='&total_time='+encodeURIComponent($scope.period.total_time);
			$http.post($scope.ajax_url+'addPeriod',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.period.name='';$scope.period.from_time='';$scope.period.to_time='';$scope.period.total_time='';
				$scope.loadPeriods();
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}else{		
			//update data
			var data='rid='+encodeURIComponent($scope.period.mid)+'&name='+encodeURIComponent($scope.period.name)+'&type='+encodeURIComponent($scope.period.type);
			data+='&from_time='+encodeURIComponent($scope.period.from_time)+'&to_time='+encodeURIComponent($scope.period.to_time);
			data+='&total_time='+encodeURIComponent($scope.period.total_time)+'&sort_order='+encodeURIComponent($scope.period.sort_order);
			data+='&number='+encodeURIComponent($scope.period.number);
			$http.post($scope.ajax_url+'updatePeriod',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.period.name='';$scope.period.from_time='';$scope.period.to_time='';$scope.period.total_time='';$scope.period.add=true;
				$scope.loadPeriods();
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}
        }
    }; 
	$scope.delPeriod=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Remove study period("+row.name+") of this campus. ",
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
				$http.post($scope.ajax_url+'deletePeriod',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadPeriods();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	

	//////////////PERIOD FUNCTIONS/////////////////////////////////////	
    $scope.loadFeePackages=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterFeePackages',config.postConfig).then(function(response){
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
	$scope.saveFeePackage=function (){	
    	if($scope.feepackage.name.length< 1){
		swal({title:"Please enter name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.feepackage.amount.length< 1){
		swal({title:"Please enter package amount",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.feepackage.obt_min_percent.length< 1){
		swal({title:"Please enter minimum required percentage to quaily for package",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.feepackage.obt_max_percent.length< 1){
		swal({title:"Please enter maximum required percentage to quaily for package",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		if($scope.feepackage.add===true){	
			//add data
			var data='name='+encodeURIComponent($scope.feepackage.name)+'&amount='+encodeURIComponent($scope.feepackage.amount);
			data+='&description='+encodeURIComponent($scope.feepackage.description)+'&class_id='+encodeURIComponent($scope.feepackage.class_id);
			data+='&obt_min_percent='+encodeURIComponent($scope.feepackage.obt_min_percent);
			data+='&obt_max_percent='+encodeURIComponent($scope.feepackage.obt_max_percent);
			$http.post($scope.ajax_url+'addFeePackage',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.feepackage.name='';$scope.feepackage.amount='';$scope.feepackage.description='';
				$scope.feepackage.obt_min_percent='';$scope.feepackage.obt_max_percent=''
				$scope.loadFeePackages();
				$scope.resetSelect();
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}else{		
			//update data
			var data='rid='+encodeURIComponent($scope.feepackage.mid)+'&name='+encodeURIComponent($scope.feepackage.name);
			data+='&amount='+encodeURIComponent($scope.feepackage.amount)+'&class_id='+encodeURIComponent($scope.feepackage.class_id);
			data+='&description='+encodeURIComponent($scope.feepackage.description);
			data+='&obt_min_percent='+encodeURIComponent($scope.feepackage.obt_min_percent);
			data+='&obt_max_percent='+encodeURIComponent($scope.feepackage.obt_max_percent);
			$http.post($scope.ajax_url+'updateFeePackage',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.feepackage.name='';$scope.feepackage.amount='';$scope.feepackage.class_id='';
				$scope.feepackage.obt_min_percent='';$scope.feepackage.obt_max_percent='';$scope.period.add=true;
				$scope.loadFeePackages();
				$scope.resetSelect();
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}

        }
    }; 
	$scope.delFeePackage=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Remove package("+row.name+") . ",
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
				$http.post($scope.ajax_url+'deleteFeePackage',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadFeePackages();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	
	//////////////HELPER FUNCTIONS/////////////////////////////////////	
    $scope.loadRows=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'status':$scope.filter.status,'role_id':$scope.filter.role,'salary':$scope.filter.salary,'gender':$scope.filter.gender,'blood_group':$scope.filter.blood_group,'sortby':$scope.sortString};
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
	///////////////////////////////////////////////////////
	$scope.saveGeneralSettings=function (){	
    	if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data ='&prm_portal_staff_edit='+encodeURIComponent($scope.settings.prm_portal_staff_edit)+'&narration='+encodeURIComponent($scope.settings.narration);
		data+='&get_late_fee_fine='+encodeURIComponent($scope.entry.get_late_fee_fine)+'&late_fee_fine='+encodeURIComponent($scope.settings.late_fee_fine);
		data+='&late_fee_fine_type='+encodeURIComponent($scope.settings.late_fee_fine_type)+'&bank_title='+encodeURIComponent($scope.settings.bank_title);
		data+='&bank_name='+encodeURIComponent($scope.settings.bank_name)+'&bank_account='+encodeURIComponent($scope.settings.bank_account);
		data+='&font_scale='+encodeURIComponent($scope.settings.font_scale)+'&month_opass_percent='+encodeURIComponent($scope.settings.month_opass_percent);
		data+='&final_opass_percent='+encodeURIComponent($scope.settings.final_opass_percent);
		data+='&std_fee_type='+encodeURIComponent($scope.settings.std_fee_type);
		$http.post($scope.ajax_url+'updateCampusSettings',data,config.postConfig).then(function(response){
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
	
	//load settings
	$scope.loadCampus=function (){
		var data={};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterCampusSettings',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.settings=response.data.output;
			if($scope.settings.prm_portal_staff_edit>0){$scope.settings.prm_portal_staff_edit=true;}else{$scope.settings.prm_portal_staff_edit=false;}
			if($scope.settings.narration>0){$scope.settings.narration=true;}else{$scope.settings.narration=false;}
			if($scope.settings.late_fee_fine>0){$scope.entry.get_late_fee_fine=true;}else{$scope.entry.get_late_fee_fine=false;}
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});    
    };	
	
	///////autoload functions////////////////////
	$scope.loadCampus();
	// $timeout(function(){$scope.loadCampus();},1000); 


});