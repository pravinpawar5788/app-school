app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url='../../'+config.ajaxModule+'parents/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.filter={status:''};
	$scope.entry={title:'',message:''};
	$scope.reply={message:''};
    $scope.chat_details='';
    $scope.promise={};
	$scope.message='';
	$scope.rid='';

	/////////HOTKEYS/////////////////////////////////////////////////////	 
	hotkeys.add({
	  combo: 'ctrl+enter',	//enter
	  description: 'Submit Reply',
	  allowIn: ['INPUT', 'SELECT','TEXTAREA'],
	  callback: function(event, hotkey) {
		event.preventDefault();
		$scope.sendReply();
	  }
	}); 
	
	hotkeys.add({
	  combo: 'shift+n',	//ctrl+enter
	  description: 'Send New Feedback Message.',
	  //allowIn: ['INPUT', 'SELECT', 'TEXTAREA'],
	  callback: function(event, hotkey) {
		event.preventDefault();
		$('#add').modal('show');
	  }
	});
	
	hotkeys.add({
	  combo: 'shift+s',	//ctrl+enter
	  description: 'Submit new feedback message',
	  //allowIn: ['INPUT', 'SELECT', 'TEXTAREA'],
	  callback: function(event, hotkey) {
		event.preventDefault();
		$scope.saveRow();
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
		var data={'search':$scope.searchText,'page':config.currentPage,'status':$scope.filter.status,'sortby':$scope.sortString};
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
			if($scope.appConfig.showLog){$log.error(response);}
		});
    
    };	
	$scope.saveRow=function (){	
    	if($scope.entry.title.length< 1){
		swal({title:"Please enter feedback subject",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.entry.message.length< 1){
		swal({title:"Please enter feedback message",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='title='+encodeURIComponent($scope.entry.title)+'&message='+encodeURIComponent($scope.entry.message);
		//data+='&admission_number='+encodeURIComponent($scope.student.admission_number);
		$http.post($scope.ajax_url+'add',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:3500});
			if($scope.appConfig.showLog){$log.error(response);}
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.entry.title='';$scope.entry.message='';
			$scope.loadRows();
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			if($scope.appConfig.showLog){$log.error(response);}
			
		});
        }
    }; 
	$scope.sendReply=function (){	
    	if($scope.reply.message.length< 1){
		swal({title:"Please enter message",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+encodeURIComponent($scope.rid)+'&message='+encodeURIComponent($scope.reply.message);
		//data+='&admission_number='+encodeURIComponent($scope.student.admission_number);
		$http.post($scope.ajax_url+'replyFeedback',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:3500});
			if($scope.appConfig.showLog){$log.error(response);}
			}else{
			// swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.reply.message='';
			$scope.loadChat();
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			if($scope.appConfig.showLog){$log.error(response);}
			
		});
        }
    };
	//load students of a parent
	$scope.loadChat=function (){
		if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		var data={'rid':$scope.rid};
		config.postConfig.params=data;
		$http.get($scope.ajax_url+'loadFeedback',config.postConfig).then(function(response){
			$scope.chat_details=$sce.trustAsHtml(response.data.chat_details);
			// $log.info(response.data);
		},function(response){
			if($scope.appConfig.showLog){$log.error(response);}
			
		});
        }
    }; 
	///////autoload functions////////////////////
	// $scope.loadRows();
	 $timeout(function(){$scope.loadChat();},1000); 


});