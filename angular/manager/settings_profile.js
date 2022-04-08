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
	$scope.entry={password:'',new_password:''};
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
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		if($scope.section.add===true){	
			//add data
			var data='name='+encodeURIComponent($scope.section.name);
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
	
	//////////////HELPER FUNCTIONS/////////////////////////////////////	
    //change password
	$scope.changePassword=function (){	
    	if($scope.entry.password.length< 1){
		swal({title:"Please enter current password",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.entry.new_password.length< 1){
		swal({title:"Please enter new password",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&password='+encodeURIComponent($scope.entry.password)+'&npassword='+encodeURIComponent($scope.entry.new_password);
		$http.post($scope.ajax_url+'updatePassword',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.entry.password='';$scope.entry.new_password='';
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			
		});
        }
    }; 
	///////autoload functions////////////////////
	// $scope.loadCampus();
	// $timeout(function(){$scope.loadCampus();},1000); 


});