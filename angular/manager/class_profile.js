app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys,Pubnub){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url='../../'+config.ajaxModule+'classes/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.filter={target:'',section:''};
	$scope.entry={name:'',code:'',total_marks:'',passing_percentage:'',chapters:'',description:'',teacher_id:'',subject_id:'',period_id:'',day:''};
    $scope.promise={};
    $scope.isRemoveable=false;
    $scope.showTeacher=false;
	$scope.message='';
	$scope.class={};
	$scope.pid='';

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
    $scope.sortBy= function (value){ $scope.sortString=$scope.processSort(value);};
    $scope.clearFilter= function (){ $.each($scope.filter, function(index){$scope.filter[index]='';}); };
    $scope.filterGetString= function (){ var data=''; $.each($scope.filter, function(index,value){data+='&'+index+'='+value;}); return data;};
    $scope.showFilter= function (){var show=false; $.each($scope.filter, function(index,value){if(value !==''){show=true;} }); return show; };
    $scope.clearResponse= function (){ $scope.responseText='';$scope.responseModelText=''; };
    $scope.selectRow = function (row) {$scope.selectedRow = row;};
	$scope.addkey=function (key){$scope.message= $scope.message+' {'+key+'} ';}; 
    $scope.toggleRemobeable = function () {$scope.isRemoveable = !$scope.isRemoveable;};
    $scope.toggleTeacher = function () {$scope.showTeacher = !$scope.showTeacher;};
	
	//////////////HELPER FUNCTIONS/////////////////////////////////////	
    $scope.loadSubjects=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'class_id':$scope.pid,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterSubjects',config.postConfig).then(function(response){
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
	$scope.saveSubject=function (){	
    	if($scope.entry.name.length< 1){
		swal({title:"Please enter subject name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.entry.passing_percentage.length< 1){
		swal({title:"Please enter passing percenatge",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='class_id='+$scope.pid+'&name='+encodeURIComponent($scope.entry.name)+'&code='+encodeURIComponent($scope.entry.code)+'&chapters='+encodeURIComponent($scope.entry.chapters);
		data+='&passing_percentage='+encodeURIComponent($scope.entry.passing_percentage)+'&description='+encodeURIComponent($scope.entry.description);
		// data+='&anual_increment='+encodeURIComponent($scope.staff.anual_increment);
		$http.post($scope.ajax_url+'addSubject',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.entry.name='';$scope.entry.code='';$scope.entry.chapters='';$scope.entry.passing_percentage='';
			$scope.entry.description='';
			$scope.loadSubjects();
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			
		});
        }
    }; 
	$scope.updateSubject=function (){	
    	if($scope.selectedRow.name.length< 1){
		swal({title:"Please enter subject name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.chapters.length< 1){
		swal({title:"Please enter total chapters",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.passing_percentage.length< 1){
		swal({title:"Please enter passing percentage",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&name='+encodeURIComponent($scope.selectedRow.name)+'&code='+encodeURIComponent($scope.selectedRow.code)+'&chapters='+encodeURIComponent($scope.selectedRow.chapters);
		data+='&passing_percentage='+encodeURIComponent($scope.selectedRow.passing_percentage)+'&description='+encodeURIComponent($scope.selectedRow.description);
		
		$http.post($scope.ajax_url+'updateSubject',data,config.postConfig).then(function(response){
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
	$scope.delSubject=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Delete subject of ("+row.name+"). All related data will be removed from the system. You can not recover it later.!",
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
				$http.post($scope.ajax_url+'deleteSubject',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadSubjects();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
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
	//send sms to single member
	$scope.sendSingleSms=function (){
		var data={'message':$scope.message,'rid':$scope.selectedRow.mid,'target':$scope.filter.target};
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
	//update roll no and section
	$scope.updateRow=function (){	
    	if($scope.selectedRow.roll_no.length< 1){
		swal({title:"Please enter roll number",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&roll_no='+encodeURIComponent($scope.selectedRow.roll_no)+'&section='+encodeURIComponent($scope.selectedRow.section_id);
		$http.post($scope.ajax_url+'updateRollNo',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.loadClass();
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			
		});
        }
    }; 
	//load class
	$scope.loadClass=function (){
		var data={'rid':$scope.pid,'section':$scope.filter.section,'search':$scope.searchText};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'load',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.class=response.data.output;
		},function(response){	
			$scope.enableButtons();		
            // swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});    
    };	
	//////////////HELPER FUNCTIONS/////////////////////////////////////	
    $scope.loadTimetable=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'class_id':$scope.pid,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterTimetable',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.responseData=response.data;
			$log.info($scope.responseData);
			config.responseNumRows=$scope.responseData.total_rows;
			$scope.validatePagination();			
			
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});    
    };	
	$scope.savePeriod=function (){	
    	if($scope.entry.day.length< 1){
		swal({title:"Please select Day",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.entry.period_id.length< 1){
		swal({title:"Please select period",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='class_id='+$scope.pid+'&day='+encodeURIComponent($scope.entry.day)+'&period_id='+encodeURIComponent($scope.entry.period_id);
		data+='&subject_id='+encodeURIComponent($scope.entry.subject_id)+'&teacher_id='+encodeURIComponent($scope.entry.teacher_id);
		// data+='&anual_increment='+encodeURIComponent($scope.staff.anual_increment);
		$http.post($scope.ajax_url+'addPeriod',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:5500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.loadTimetable();
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			
		});
        }
    }; 
	$scope.delPeriod=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Remove period of this subject and teacher.",
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
						$scope.loadTimetable();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	
	//////////////HELPER FUNCTIONS/////////////////////////////////////	
    $scope.loadHomework=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'rid':$scope.pid,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterHomework',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.responseData=response.data;
			$log.info($scope.responseData);
			config.responseNumRows=$scope.responseData.total_rows;
			$scope.validatePagination();			
			
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});    
    };	
	$scope.delHomework=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Remove homework of this subject.",
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
				$http.post($scope.ajax_url+'deleteHomework',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadHomework();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	
	///////autoload functions////////////////////
	// $scope.loadRows();
	$timeout(function(){$scope.loadClass();},1000); 


});