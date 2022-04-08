app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url='./'+config.ajaxModule+'attendance/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.filter={class_id:'',section:'',date:'',target:'',isHoliday:false};
	$scope.class={title:'',incharge_id:'',display:''};
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
	/////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////
	$scope.loadStudents=function (){
		if($scope.filter.class_id.length< 1){
		swal({title:"Please choose a class",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.filter.date.length< 1){
		swal({title:"Please choose date",type:'info',showConfirmButton:false,timer:2000});
        }else {
			var data={'class_id':$scope.filter.class_id,'section':$scope.filter.section,'date':$scope.filter.date};
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
	/////////////////////////////////////////////////////////////////
	//mark student attendance
	$scope.markStudentAttendance=function (row,status){	
		var data='rid='+row.attendance_id+'&day='+row.attendance_day+'&status='+status;
		$http.post($scope.ajax_url+'markStudentAttendance',data,config.postConfig).then(function(response){
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
	
	///////autoload functions////////////////////
	//$scope.loadRows();
	/* $timeout(function(){$scope.loadRows();},1000); */


});