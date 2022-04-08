app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url='../../'+config.ajaxModule+'exam/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.filter={status:'',session_id:'',section:'',subjectSection:'',class_id:'',subject_id:''};
	$scope.entry={class_id:'',subject_id:'',title:'',description:'',total_marks:'',chapter:''};
	$scope.classSections={};
    $scope.subjects={};
    $scope.results={};
    $scope.promise={};
	$scope.message='';
	$scope.rid='';

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
		var data={'search':$scope.searchText,'page':config.currentPage,'term_id':$scope.rid,'class_id':$scope.filter.class_id,'section':$scope.filter.section,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterTermResult',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.responseData=response.data;
			config.responseNumRows=$scope.responseData.total_rows;
			$scope.validatePagination();			
			
		},function(response){	
			$scope.enableButtons();		
            // swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});    
    };	
	
	//////////////RESULTs FUNCTIONS///////
	$scope.loadResult=function (){
		if($scope.filter.subject_id.length< 1){
		swal({title:"Please select subject",type:'info',showConfirmButton:false,timer:2000});
        }else{
			var data={'term_id':$scope.rid,'section':$scope.filter.subjectSection,'rid':$scope.filter.subject_id};
			config.postConfig.params=data;
			$scope.disableButtons();
			$http.get($scope.ajax_url+'filterSubjectTermResult',config.postConfig).then(function(response){
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
    	if(parseInt(row.obt_marks)>parseInt(row.total_marks)){
    		row.obt_marks=0;
			swal({title:"Please enter marks less then total marks",type:'info',showConfirmButton:false,timer:2000});
        }else{  
			var data='rid='+row.mid+'&term_id='+$scope.rid+'&obt_marks='+encodeURIComponent(row.obt_marks);		
			$http.post($scope.ajax_url+'updateTermResult',data,config.postConfig).then(function(response){
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
		var data='rid='+$scope.filter.subject_id+'&term_id='+$scope.rid+'&total_marks='+encodeURIComponent($scope.entry.total_marks);		
		$http.post($scope.ajax_url+'updateTermTotalMarks',data,config.postConfig).then(function(response){
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
    $scope.filterSubjects=function (){
		var data={'class_id':$scope.filter.class_id};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterSubjects',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.subjects=response.data;			
			
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
		$http.get('../../'+config.ajaxModule+'classes/filterClassSections',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.classSections=response.data.rows;
		},function(response){	
			$scope.enableButtons();		
            $log.error(response);
		});    
    };	
	
    
	///////autoload functions////////////////////
	$scope.loadRows();
	/* $timeout(function(){$scope.loadRows();},1000); */


});