app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url='../'+config.ajaxModule+'exam/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.filter={status:'',session_id:'',class_id:'',section_id:'',test_id:'',term_id:'',subject_id:'',day:'',month:'',rxr:''};
	$scope.entry={class_id:'',subject_id:'',title:'',description:'',total_marks:'',chapter:''};
	$scope.classSections={};
    $scope.testMonths={};
    $scope.testDays={};
    $scope.classSubjects={};
    $scope.monthTests={};
    $scope.promise={};
	$scope.message='';
	$scope.usrid='';

	/////////HOTKEYS/////////////////////////////////////////////////////	  
	hotkeys.add({
	  combo: 'enter',	//enter
	  description: 'Search ',
	  allowIn: ['INPUT', 'SELECT', 'TEXTAREA'],
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
		var data={'search':$scope.searchText,'page':config.currentPage,'class_id':$scope.filter.class_id,'section':$scope.filter.section,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterFinalResult',config.postConfig).then(function(response){
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
	
	//////////////RESULTs FUNCTIONS///////
	$scope.loadResult=function (){
		if($scope.filter.subject_id.length< 1){
		swal({title:"Please select subject",type:'info',showConfirmButton:false,timer:2000});
        }else{
			var data={'rid':$scope.filter.subject_id};
			config.postConfig.params=data;
			$scope.disableButtons();
			$http.get($scope.ajax_url+'filterSubjectFinalResult',config.postConfig).then(function(response){
				$log.info(response);
				$scope.enableButtons();
				$scope.results=response.data;			
				
			},function(response){	
				$scope.enableButtons();		
				swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
				$log.error(response);
			});  
		}
    };	
    $scope.updateResult=function (row){
		var data='rid='+row.mid+'&obt_marks='+encodeURIComponent(row.obt_marks);		
		$http.post($scope.ajax_url+'updateFinalResult',data,config.postConfig).then(function(response){
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			// swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			}
		},function(response){
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			
		});		
    }; 
	$scope.updateTotalMarks=function (){
		var data='rid='+$scope.filter.subject_id+'&total_marks='+encodeURIComponent($scope.entry.total_marks);		
		$http.post($scope.ajax_url+'updateFinalTotalMarks',data,config.postConfig).then(function(response){
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			// swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.loadResult();
			}
		},function(response){
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			
		});		
    }; 
	//////////////HELPER FUNCTIONS/////////////////////////////////////		
    $scope.filterTestMonths=function (){
		var data={'class_id':$scope.filter.class_id};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterTestMonths',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.testMonths=response.data;	
			$log.info(response);		
			
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
		});    
    };	
	//////////////HELPER FUNCTIONS/////////////////////////////////////		
    $scope.filterMonthTests=function (){
		var data={'class_id':$scope.filter.class_id,'month':$scope.filter.month,'day':$scope.filter.day};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterMonthTests',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.monthTests=response.data;
			
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
		});    
    };	
	//////////////HELPER FUNCTIONS/////////////////////////////////////		
    $scope.filterTestDays=function (){
		var data={'class_id':$scope.filter.class_id,'month':$scope.filter.month};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterTestDays',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.testDays=response.data;
			
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
		});    
    };	
	//////////////HELPER FUNCTIONS/////////////////////////////////////		
    $scope.loadClassSubjects=function (){
		var data={'class_id':$scope.filter.class_id};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterSubjects',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.classSubjects=response.data;	
			$log.info(response);		
			
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
		});    
    };	
    /////////////////////////////////////////////
    
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
	//send monthly report sms
	$scope.sendMonthlyReportSMS=function (){	

    	if($scope.filter.class_id.length< 1){
		swal({title:"Please select class",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.filter.month.length< 1){
		swal({title:"Please select month",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.filter.rxr.length< 1){
		swal({title:"Please select message receivers",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
			swal({
				title: 'Are you sure?',
				text: "You are going to send monthly report sms to selected class!",
				type: 'warning',
				showCancelButton: true,
				confirmButtonText: '<strong>Yes, Send SMS <i class="icon-circle-right2 ml-2"></i></strong>',
				cancelButtonText: 'No, Go Back <i class="icon-circle-left2 ml-2"></i>',
				confirmButtonClass: 'btn btn-warning',
				cancelButtonClass: 'btn btn-light',
				buttonsStyling: false
			}).then(function (result) {
				if(result.value){
					$scope.disableButtons();
					var msg='';
					var data='session='+$scope.filter.session_id+'&class_id='+$scope.filter.class_id+'&month='+$scope.filter.month;
					data+='&rxr='+$scope.filter.rxr+'&test_id='+$scope.filter.test_id;
					data+='&day='+$scope.filter.day;
					data+='&section_id='+$scope.filter.section_id;
					data+='&message='+encodeURIComponent($scope.message);
		
					$http.post($scope.ajax_url+'sendMonthlyReportSMS',data,config.postConfig).then(function(response){
						$scope.enableButtons();	
						msg=response.data.message;
						if(response.data.error===true){
							swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
							$log.error(response);	
						}else{
							swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
							$scope.loadRows();
						}
					},function (response){
						$scope.enableButtons();	
						swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);
					});
				}
			});
		}
	};
	
	//send final result sms
	$scope.sendTermResultSMS=function (){	

    	if($scope.filter.class_id.length< 1){
		swal({title:"Please select class",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.filter.term_id.length< 1){
		swal({title:"Please select term",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.filter.rxr.length< 1){
		swal({title:"Please select message receivers",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
			swal({
				title: 'Are you sure?',
				text: "You are going to send term result via sms to selected class!",
				type: 'warning',
				showCancelButton: true,
				confirmButtonText: '<strong>Yes, Send SMS <i class="icon-circle-right2 ml-2"></i></strong>',
				cancelButtonText: 'No, Go Back <i class="icon-circle-left2 ml-2"></i>',
				confirmButtonClass: 'btn btn-warning',
				cancelButtonClass: 'btn btn-light',
				buttonsStyling: false
			}).then(function (result) {
				if(result.value){
					$scope.disableButtons();
					var msg='';
					var data='session='+$scope.filter.session_id+'&class_id='+$scope.filter.class_id;
					data+='&rxr='+$scope.filter.rxr+'&term_id='+$scope.filter.term_id;
					data+='&subject='+$scope.filter.subject_id;
					data+='&section_id='+$scope.filter.section_id;
					data+='&message='+encodeURIComponent($scope.message);
		
					$http.post($scope.ajax_url+'sendTermResultSMS',data,config.postConfig).then(function(response){
						$scope.enableButtons();	
						msg=response.data.message;
						if(response.data.error===true){
							swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
							$log.error(response);	
						}else{
							swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
							$scope.loadRows();
						}
					},function (response){
						$scope.enableButtons();	
						swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);
					});
				}
			});
		}
	};
	
	//send final result sms
	$scope.sendFinalResultSMS=function (){	

    	if($scope.filter.class_id.length< 1){
		swal({title:"Please select class",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.filter.rxr.length< 1){
		swal({title:"Please select message receivers",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
			swal({
				title: 'Are you sure?',
				text: "You are going to send final result via sms to selected class!",
				type: 'warning',
				showCancelButton: true,
				confirmButtonText: '<strong>Yes, Send SMS <i class="icon-circle-right2 ml-2"></i></strong>',
				cancelButtonText: 'No, Go Back <i class="icon-circle-left2 ml-2"></i>',
				confirmButtonClass: 'btn btn-warning',
				cancelButtonClass: 'btn btn-light',
				buttonsStyling: false
			}).then(function (result) {
				if(result.value){
					$scope.disableButtons();
					var msg='';
					var data='session='+$scope.filter.session_id+'&class_id='+$scope.filter.class_id;
					data+='&rxr='+$scope.filter.rxr;
					data+='&section_id='+$scope.filter.section_id;
					data+='&message='+encodeURIComponent($scope.message);
		
					$http.post($scope.ajax_url+'sendFinalResultSMS',data,config.postConfig).then(function(response){
						$scope.enableButtons();	
						msg=response.data.message;
						if(response.data.error===true){
							swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
							$log.error(response);	
						}else{
							swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
							$scope.loadRows();
						}
					},function (response){
						$scope.enableButtons();	
						swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);
					});
				}
			});
		}
	};
	

	///////autoload functions////////////////////
	// $scope.loadRows();
	/* $timeout(function(){$scope.loadRows();},1000); */


});