app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url='../'+config.ajaxModule+'attendance/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.filter={class_id:'',section:'',date:'',target:'',status:'',filter:'',isHoliday:false};
	$scope.class={title:'',incharge_id:'',display:''};
	$scope.classSections={};
	$scope.notifyStudents={};
    $scope.promise={};
	$scope.message='';
	$scope.usrid='';
	
	/////////////////////////GENERAL FUNCTIONS/////////////////////////////////////////////////////	          
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
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});
    
    };	
	$scope.saveRow=function (){	
    	if($scope.class.title.length< 1){
		swal({title:"Please enter class name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.class.incharge_id.length< 1){
		swal({title:"Please select class incharge",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='title='+encodeURIComponent($scope.class.title)+'&incharge_id='+encodeURIComponent($scope.class.incharge_id);
		$http.post($scope.ajax_url+'add',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.class.title='';
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
		data+='&display_order='+encodeURIComponent($scope.selectedRow.display_order);
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
	/////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////
	$scope.loadStaff=function (){
		if($scope.filter.date.length< 1){
		swal({title:"Please choose date",type:'info',showConfirmButton:false,timer:2000});
        }else {
			var data={'date':$scope.filter.date,'search':$scope.searchText};
			config.postConfig.params=data;
			$scope.disableButtons();
			$http.get($scope.ajax_url+'filterStaff',config.postConfig).then(function(response){
				$log.info(response);
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
					swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
					$scope.responseData=response.data;
					config.responseNumRows=$scope.responseData.total_rows;
				}
				
			},function(response){	
				$scope.enableButtons();		
				swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
				$log.error(response);
			});
		}
    
    };	
	$scope.loadStudents=function (){
		if($scope.filter.class_id.length< 1){
		swal({title:"Please choose a class",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.filter.date.length< 1){
		swal({title:"Please choose date",type:'info',showConfirmButton:false,timer:2000});
        }else {
			var data={'class_id':$scope.filter.class_id,'section':$scope.filter.section,'date':$scope.filter.date,'filter':$scope.filter.filter,'search':$scope.searchText};
			config.postConfig.params=data;
			$scope.disableButtons();
			$http.get($scope.ajax_url+'filterStudents',config.postConfig).then(function(response){
				$log.info(response);
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
					swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
					$scope.responseData=response.data;
					config.responseNumRows=$scope.responseData.total_rows;
				}
				
			},function(response){	
				$scope.enableButtons();		
				swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
				$log.error(response);
			});
		}
    
    };	
	$scope.loadNotifyStudents=function (){
		if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
			var data={'status':$scope.filter.status,'search':$scope.searchText};
			config.postConfig.params=data;
			$scope.disableButtons();
			$http.get($scope.ajax_url+'filterNotifyStudents',config.postConfig).then(function(response){
				$log.info(response);
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
					swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
					$scope.notifyStudents=response.data;
				}
				
			},function(response){	
				$scope.enableButtons();		
				swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
				$log.error(response);
			});
		}
    
    };	
	/////////////////////////////////////////////////////////////////
	//mark staff attendance
	$scope.markStaffAttendance=function (row,status){	
		var data='rid='+row.attendance_id+'&day='+row.attendance_day+'&status='+status;
		$http.post($scope.ajax_url+'markStaffAttendance',data,config.postConfig).then(function(response){
			msg=response.data.message;
			if(response.data.error===true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);	
			}else{
				row.attendance=status;
			}
		},function (response){
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
		});
	
	};
	//mark all staff attendance
	$scope.markAllStaffAttendance=function (status){	
		swal({
			title: 'Are you sure?',
			text: "Mark all staff attendance at once.",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: '<strong>Yes, Continue <i class="icon-circle-right2 ml-2"></i></strong>',
			cancelButtonText: 'No, Go Back <i class="icon-circle-left2 ml-2"></i>',
			confirmButtonClass: 'btn btn-warning',
			cancelButtonClass: 'btn btn-light',
			buttonsStyling: false
		}).then(function (result) {
			if(result.value){
				var msg='';
				$scope.blockPageUI();
				var data='date='+$scope.filter.date+'&status='+status;
				$http.post($scope.ajax_url+'markAllStaffAttendance',data,config.postConfig).then(function(response){
					$scope.unblockPageUI();
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadStaff();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	};
	//mark student attendance
	$scope.markStudentAttendance=function (row,status){	
		var data='rid='+row.attendance_id+'&day='+row.attendance_day+'&status='+status;
		// $scope.blockPageUI();
		$http.post($scope.ajax_url+'markStudentAttendance',data,config.postConfig).then(function(response){
			// $scope.unblockPageUI();
			msg=response.data.message;
			if(response.data.error===true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);	
			}else{
				row.attendance=status;
			}
		},function (response){
			// $scope.unblockPageUI();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
		});
	
	};
	//mark class attendance
	$scope.markClassAttendance=function (status){	
		swal({
			title: 'Are you sure?',
			text: "Mark all class students attendance at once.",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: '<strong>Yes, Continue <i class="icon-circle-right2 ml-2"></i></strong>',
			cancelButtonText: 'No, Go Back <i class="icon-circle-left2 ml-2"></i>',
			confirmButtonClass: 'btn btn-warning',
			cancelButtonClass: 'btn btn-light',
			buttonsStyling: false
		}).then(function (result) {
			if(result.value){
				var msg='';
				$scope.blockPageUI();
				var data='date='+$scope.filter.date+'&class_id='+$scope.filter.class_id+'&section='+$scope.filter.section+'&status='+status;
				$http.post($scope.ajax_url+'markClassAttendance',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						$scope.unblockPageUI();
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadStudents();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	};
	//mark student holiday
	$scope.markStudentHoliday=function (){	
		swal({
			title: 'Are you sure?',
			text: "Mark ("+$scope.filter.date+") as holiday for all students.",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: '<strong>Yes, Continue <i class="icon-circle-right2 ml-2"></i></strong>',
			cancelButtonText: 'No, Go Back <i class="icon-circle-left2 ml-2"></i>',
			confirmButtonClass: 'btn btn-warning',
			cancelButtonClass: 'btn btn-light',
			buttonsStyling: false
		}).then(function (result) {
			if(result.value){
				var msg='';
				$scope.blockElementUI('.search-area','Please Wait...');
				var data='date='+$scope.filter.date+'&class_id='+$scope.filter.class_id;
				$http.post($scope.ajax_url+'markStudentHoliday',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						$scope.unblockElementUI('.search-area');
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});	
	};
	//mark staff holiday
	$scope.markStaffHoliday=function (){	
		swal({
			title: 'Are you sure?',
			text: "Mark ("+$scope.filter.date+") as holiday for all staff members.",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: '<strong>Yes, Continue <i class="icon-circle-right2 ml-2"></i></strong>',
			cancelButtonText: 'No, Go Back <i class="icon-circle-left2 ml-2"></i>',
			confirmButtonClass: 'btn btn-warning',
			cancelButtonClass: 'btn btn-light',
			buttonsStyling: false
		}).then(function (result) {
			if(result.value){
				var msg='';
				$scope.blockElementUI('.search-area','Please Wait...');
				var data='date='+$scope.filter.date;
				$http.post($scope.ajax_url+'markStaffHoliday',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						$scope.unblockElementUI('.search-area');
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});	
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
	//send sms to all students
	$scope.sendListSms=function (){
		var data={'message':$scope.message,'target':$scope.filter.target,'status':$scope.filter.status};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'sendAttendanceSms',config.postConfig).then(function(response){
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
    $scope.loadClassSections=function (){
		var data={'class_id':$scope.filter.class_id};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get('../'+config.ajaxModule+'classes/filterClassSections',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.classSections=response.data.rows;
		},function(response){	
			$scope.enableButtons();		
            $log.error(response);
		});    
    };	
	
	///////autoload functions////////////////////
	//$scope.loadRows();
	/* $timeout(function(){$scope.loadRows();},1000); */


});