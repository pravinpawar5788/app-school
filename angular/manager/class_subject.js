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
	$scope.filter={target:'',chapter:''};
	$scope.entry={name:'',chapter:'',description:'',lesson_id:'',date:'',staff_id:''};
	$scope.question={type:'',question:'',answer:'',option1:'',option2:'',option3:'',option4:'',detail:'',marks:''};
    $scope.promise={};
    $scope.chapterLessons={};
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
	
	//////////////HELPER FUNCTIONS/////////////////////////////////////	
    $scope.loadLessons=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'subject_id':$scope.pid,'chapter':$scope.filter.chapter,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterLessons',config.postConfig).then(function(response){
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
	$scope.saveLesson=function (){	
    	if($scope.entry.name.length< 1){
		swal({title:"Please enter lesson name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.entry.chapter.length< 1){
		swal({title:"Please select chapter",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='subject_id='+$scope.pid+'&name='+encodeURIComponent($scope.entry.name)+'&description='+encodeURIComponent($scope.entry.description);
		data+='&chapter_number='+encodeURIComponent($scope.entry.chapter);
		$http.post($scope.ajax_url+'addLesson',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.entry.name='';$scope.entry.description='';
			$scope.loadLessons();
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			
		});
        }
    }; 
	$scope.updateLesson=function (){	
    	if($scope.selectedRow.name.length< 1){
		swal({title:"Please enter subject name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.chapter_number.length< 1){
		swal({title:"Please select chapter",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&name='+encodeURIComponent($scope.selectedRow.name)+'&description='+encodeURIComponent($scope.selectedRow.description);
		data+='&chapter_number='+encodeURIComponent($scope.selectedRow.chapter_number);
		
		$http.post($scope.ajax_url+'updateLesson',data,config.postConfig).then(function(response){
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
	$scope.delLesson=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Delete lesson of ("+row.name+"). All related data will be removed from the system. You can not recover it later.!",
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
				$http.post($scope.ajax_url+'deleteLesson',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadLessons();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	
	$scope.loadChapterLessons=function (target){
		var data={'subject_id':$scope.pid,'chapter':$scope.entry.chapter,'target':target};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterChapterLessons',config.postConfig).then(function(response){
			$log.info(response);
			$scope.enableButtons();
			$scope.chapterLessons=response.data;
			
		},function(response){	
			$scope.enableButtons();
			$log.error(response);
		});    
    };	
	$scope.loadProgress=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'subject_id':$scope.pid,'chapter':$scope.filter.chapter,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterProgress',config.postConfig).then(function(response){
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
	$scope.saveProgress=function (){	
    	if($scope.entry.chapter.length< 1){
		swal({title:"Please select chapter",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.entry.lesson_id.length< 1){
		swal({title:"Please select lessson",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.entry.date.length< 1){
		swal({title:"Please select start date",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='subject_id='+$scope.pid+'&lesson_id='+encodeURIComponent($scope.entry.lesson_id)+'&start_date='+encodeURIComponent($scope.entry.date);
		data+='&chapter='+encodeURIComponent($scope.entry.chapter);
		$http.post($scope.ajax_url+'addProgress',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.entry.date='';
			$scope.loadProgress();
			$scope.loadChapterLessons();
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			
		});
        }
    }; 
	$scope.updateProgress=function (){	
    	if($scope.selectedRow.status.length< 1){
		swal({title:"Please select status",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&status='+encodeURIComponent($scope.selectedRow.status);
		
		$http.post($scope.ajax_url+'updateProgress',data,config.postConfig).then(function(response){
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
	$scope.delProgress=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Delete planning entry. All related data will be removed from the system. You can not recover it later.!",
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
				$http.post($scope.ajax_url+'deleteProgress',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadProgress();
						$scope.loadChapterLessons();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	$scope.changeStatus=function (row,status){	
		var msg='';
		var data='rid='+row.mid+'&status='+status;
		$http.post($scope.ajax_url+'updateProgress',data,config.postConfig).then(function(response){
			msg=response.data.message;
			if(response.data.error===true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);	
			}else{
				// swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
				row.status=status;
			}
		},function (response){
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
		});
	
	};
	
	$scope.loadFaculty=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'subject_id':$scope.pid,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterFaculty',config.postConfig).then(function(response){
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
	$scope.saveFaculty=function (){	
    	if($scope.entry.staff_id.length< 1){
		swal({title:"Please select teacher",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='subject_id='+$scope.pid+'&staff_id='+encodeURIComponent($scope.entry.staff_id);
		$http.post($scope.ajax_url+'addFaculty',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.entry.staff_id='';
			$scope.loadFaculty();
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			
		});
        }
    }; 
	$scope.updateFaculty=function (){	
    	if($scope.selectedRow.staff_id.length< 1){
		swal({title:"Please select teacher",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&staff_id='+encodeURIComponent($scope.selectedRow.staff_id);
		
		$http.post($scope.ajax_url+'updateFaculty',data,config.postConfig).then(function(response){
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
	$scope.delFaculty=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Remove teacher access from this subject!",
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
				$http.post($scope.ajax_url+'deleteFaculty',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadFaculty();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	
	$scope.loadQbank=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'subject_id':$scope.pid,'chapter':$scope.filter.chapter,'lesson_id':$scope.filter.lesson_id,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterQbank',config.postConfig).then(function(response){
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
	$scope.saveQbank=function (){	
    	if($scope.entry.chapter.length< 1){
		swal({title:"Please select chapter",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.entry.lesson_id.length< 1){
		swal({title:"Please select lesson",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.question.type.length< 1){
		swal({title:"Please select category",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.question.marks.length< 1){
		swal({title:"Please enter marks",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.question.question.length< 1){
		swal({title:"Please enter question",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.question.type==='mcq' && $scope.question.answer.length< 1){
		swal({title:"Please select answer",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.question.type==='boolean' && $scope.question.answer.length< 1){
		swal({title:"Please select answer",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='subject_id='+$scope.pid+'&chapter='+encodeURIComponent($scope.entry.chapter)+'&lesson_id='+encodeURIComponent($scope.entry.lesson_id);
		data+='&type='+$scope.question.type+'&marks='+encodeURIComponent($scope.question.marks)+'&question='+encodeURIComponent($scope.question.question);
		data+='&option1='+$scope.question.option1+'&option2='+encodeURIComponent($scope.question.option2)+'&option3='+encodeURIComponent($scope.question.option3);
		data+='&option4='+$scope.question.option4+'&answer='+encodeURIComponent($scope.question.answer)+'&detail='+encodeURIComponent($scope.question.detail);
		$http.post($scope.ajax_url+'addQbank',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.question.question='';$scope.question.option1='';$scope.question.option2='';$scope.question.option2='';
			$scope.question.option3='';$scope.question.detail='';$scope.question.answer='';
			$scope.loadQbank();
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			
		});
        }
    }; 
	$scope.updateQbank=function (){	
    	if($scope.selectedRow.question.length< 1){
		swal({title:"Please enter question",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.type==='mcq' && $scope.selectedRow.answer.length< 1){
		swal({title:"Please select answer",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.type==='boolean' && $scope.selectedRow.answer.length< 1){
		swal({title:"Please select answer",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&question='+encodeURIComponent($scope.selectedRow.question);
		data+='&option1='+$scope.selectedRow.option1+'&option2='+encodeURIComponent($scope.selectedRow.option2)+'&option3='+encodeURIComponent($scope.selectedRow.option3);
		data+='&option4='+$scope.selectedRow.option4+'&answer='+encodeURIComponent($scope.selectedRow.answer)+'&detail='+encodeURIComponent($scope.selectedRow.detail);;
		
		$http.post($scope.ajax_url+'updateQbank',data,config.postConfig).then(function(response){
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
	$scope.delQbank=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Remove question!",
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
				$http.post($scope.ajax_url+'deleteQbank',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadQbank();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	
	//load subject
	$scope.loadSubject=function (){
		var data={'rid':$scope.pid};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'loadSubject',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.class=response.data.output;
		},function(response){	
			$scope.enableButtons();		
            // swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});    
    };	
	
	///////autoload functions////////////////////
	// $scope.loadRows();
	$timeout(function(){$scope.loadSubject();$scope.loadLessons()},1000); 


});