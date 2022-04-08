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
	$scope.filter={status:'',session_id:'',subject_id:'',class_id:'',section_id:''};
	$scope.entry={class_id:'',subject_id:'',title:'',description:'',total_marks:'',chapter:''};
	$scope.classSections={};
    $scope.subjects={};
    $scope.results={};
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
	
	//////////////RESULTs FUNCTIONS///////
	$scope.loadResult=function (){
		if($scope.filter.class_id.length< 1){
		swal({title:"Please select class",type:'info',showConfirmButton:false,timer:2000});
        }else{
			var data={'class_id':$scope.filter.class_id,'section':$scope.filter.section_id};
			config.postConfig.params=data;
			$scope.disableButtons();
			$http.get($scope.ajax_url+'filterPastResult',config.postConfig).then(function(response){
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
    	if(parseInt(row.pkg_obt_marks)>parseInt(row.pkg_total_marks)){
    		row.pkg_obt_marks=0;
			swal({title:"Please enter marks less then total marks",type:'info',showConfirmButton:false,timer:2000});
        }else{  
			var data='rid='+row.mid+'&obt_marks='+encodeURIComponent(row.pkg_obt_marks);		
			$http.post($scope.ajax_url+'updatePastResult',data,config.postConfig).then(function(response){
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
		}	
    }; 
	$scope.updateTotalMarks=function (){
		var data='class_id='+$scope.filter.class_id+'&total_marks='+encodeURIComponent($scope.entry.total_marks);		
		data+='&section='+$scope.filter.section_id;		
		$http.post($scope.ajax_url+'updatePastTotalMarks',data,config.postConfig).then(function(response){
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
	// $scope.loadRows();
	/* $timeout(function(){$scope.loadRows();},1000); */


});