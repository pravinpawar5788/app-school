"use strict";
app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url=config.ajaxModule+'feedback/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.filter={status:'',filter_key:'',filter_value:''};
	$scope.entry={subject:'',message:''};
    $scope.promise={};
	$scope.message='';
	$scope.usrid='';

	$scope.sendMessage=function (){	
    	if($scope.entry.subject.length< 1){
		swal({title:"Please enter subject",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.entry.message.length< 1){
		swal({title:"Please enter feedback message",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='subject='+encodeURIComponent($scope.entry.subject)+'&message='+encodeURIComponent($scope.entry.message);
		$http.post($scope.ajax_url+'sendMessage',data,config.postConfig).then(function(response){
			$log.info(response);
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:3500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:5000});
			$scope.entry.subject='';$scope.entry.message='';
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			
		});
        }
    }; 
	
	///////autoload functions////////////////////


});