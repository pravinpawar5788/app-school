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
	$scope.filter={status:'',class_id:''};
	$scope.section={name:'',add:true};
	$scope.checks={class_policy:false,subject_policy:false,student_list:false};
	$scope.entry={session_id:''};
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
    $scope.sortBy= function (value){ $scope.sortString=$scope.processSort(value);$scope.loadStudents()};
    $scope.clearFilter= function (){ $.each($scope.filter, function(index){$scope.filter[index]='';}); };
    $scope.filterGetString= function (){ var data=''; $.each($scope.filter, function(index,value){data+='&'+index+'='+value;}); return data;};
    $scope.showFilter= function (){var show=false; $.each($scope.filter, function(index,value){if(value !==''){show=true;} }); return show; };
    $scope.clearResponse= function (){ $scope.responseText='';$scope.responseModelText=''; };
    $scope.selectRow = function (row) {$scope.selectedRow = row;};
	$scope.addkey=function (key){$scope.message= $scope.message+' {'+key+'} ';}; 
    $scope.selectSection= function (row) {$scope.section = row;$scope.section.add=false;};
	
	//////////////SECTION FUNCTIONS/////////////////////////////////////	
    $scope.loadStudents=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString,'status':$scope.filter.status,'class_id':$scope.filter.class_id};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterStudents',config.postConfig).then(function(response){
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
	$scope.changeStatus=function (row,status){	
		var msg='';
		var data='rid='+row.mid+'&status='+status;
		$http.post($scope.ajax_url+'changeStudentStatus',data,config.postConfig).then(function(response){
			msg=response.data.message;
			if(response.data.error===true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);	
			}else{
				// swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
				row.promotion_status=status;
			}
		},function (response){
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
		});
	
	};
	$scope.createResult=function (){	
		swal({
			title: 'Are you sure?',
			text: "Create result once again. System will overwrite the manual result status!",
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
				var data='';
				$scope.disableButtons();
				$http.post($scope.ajax_url+'updateFinalResult',data,config.postConfig).then(function(response){	
					$scope.enableButtons();	
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadStudents();
					}
				},function (response){	
					$scope.enableButtons();	
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	$scope.promoteStudents=function (){	
		if($scope.entry.session_id.length< 1){
		swal({title:"Please select next session",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
			swal({
			title: 'Are you sure?',
			text: "Promote students to next session. Student will be listed in classes after new session activation!",
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
					var data='session_id='+$scope.entry.session_id;
					$scope.disableButtons();
					$http.post($scope.ajax_url+'promoteStudents',data,config.postConfig).then(function(response){	
						$scope.enableButtons();	
						msg=response.data.message;
						if(response.data.error===true){
							swal({title:msg,type:'error',showConfirmButton:false,timer:4500});
							$log.error(response);	
						}else{
							swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
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