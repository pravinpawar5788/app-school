app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys,Pubnub){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url='./'+config.ajaxModule+'history/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.filter={status:'',role:'',salary:'',gender:'',blood_group:''};
	$scope.academic={qualification:'',year:'',roll_number:'',registration_no:'',program:'',institute:'', add:true};
    $scope.promise={};
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
    $scope.sortBy= function (value){ $scope.sortString=$scope.processSort(value);};
    $scope.clearFilter= function (){ $.each($scope.filter, function(index){$scope.filter[index]='';}); };
    $scope.filterGetString= function (){ var data=''; $.each($scope.filter, function(index,value){data+='&'+index+'='+value;}); return data;};
    $scope.showFilter= function (){var show=false; $.each($scope.filter, function(index,value){if(value !==''){show=true;} }); return show; };
    $scope.clearResponse= function (){ $scope.responseText='';$scope.responseModelText=''; };
    $scope.selectRow = function (row) {$scope.selectedRow = row;};
	$scope.addkey=function (key){$scope.message= $scope.message+' {'+key+'} ';};
    $scope.selectAcademic= function (row) {$scope.academic=row;};
	//////////////LOAD FUNCTIONS/////////////////////////////////////	
    $scope.loadAllowances=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString,'rid':$scope.member.mid};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterAllowances',config.postConfig).then(function(response){
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
    $scope.loadAdvance=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString,'rid':$scope.member.mid};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterAdvance',config.postConfig).then(function(response){
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
	$scope.loadLoan=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString,'rid':$scope.member.mid};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterAdvance',config.postConfig).then(function(response){
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
	$scope.loadHistory=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString,'rid':$scope.member.mid};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterHistory',config.postConfig).then(function(response){
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
	$scope.loadAcademic=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString,'rid':$scope.member.mid};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterQual',config.postConfig).then(function(response){
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
	$scope.loadAwards=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString,'rid':$scope.member.mid};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterAwards',config.postConfig).then(function(response){
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
	$scope.loadDiscipline=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString,'rid':$scope.member.mid};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterPunishments',config.postConfig).then(function(response){
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
	$scope.loadAchievement=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString,'rid':$scope.member.mid};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterAchievements',config.postConfig).then(function(response){
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
	$scope.loadMember=function (){
		var data={'rid':$scope.usrid};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'load',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.member=response.data.output;		
			if(response.data.narration===true){
				$scope.speak('Welcome To '+$scope.member.name+' profile.');
			}	
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});    
    };	
	//save functions
	$scope.saveAcademic=function (){	
    	if($scope.academic.qualification.length< 1){
		swal({title:"Please enter qualification",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.academic.institute.length< 1){
		swal({title:"Please enter institute",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.academic.year.length< 1){
		swal({title:"Please provide a valid passing year",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		if($scope.academic.add===true){	
			//add data
			var data='rid='+encodeURIComponent($scope.member.mid)+'&qualification='+encodeURIComponent($scope.academic.qualification)+'&year='+encodeURIComponent($scope.academic.year);
			data+='&program='+encodeURIComponent($scope.academic.program)+'&roll_number='+encodeURIComponent($scope.academic.roll_number)+'&registration_no='+encodeURIComponent($scope.academic.registration_no);
			data+='&institute='+encodeURIComponent($scope.academic.institute);
			$http.post($scope.ajax_url+'addQual',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.academic.qualification='';$scope.academic.year='';$scope.academic.registration_no='';
				$scope.academic.roll_number='';$scope.academic.program='';$scope.academic.institute='';
				$scope.loadAcademic();
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}else{		
			//update data
			var data='rid='+encodeURIComponent($scope.academic.mid)+'&qualification='+encodeURIComponent($scope.academic.qualification)+'&year='+encodeURIComponent($scope.academic.year);
			data+='&program='+encodeURIComponent($scope.academic.program)+'&roll_number='+encodeURIComponent($scope.academic.roll_number)+'&registration_no='+encodeURIComponent($scope.academic.registration_no);
			data+='&institute='+encodeURIComponent($scope.academic.institute);
			$http.post($scope.ajax_url+'updateQual',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.academic.qualification='';$scope.academic.year='';$scope.academic.registration_no='';
				$scope.academic.roll_number='';$scope.academic.program='';$scope.academic.institute='';$scope.academic.add=true;
				$scope.loadAcademic();
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}
        }
    }; 
	
	///////autoload functions////////////////////
	// $scope.loadRows();
	$timeout(function(){$scope.loadMember();},1000); 


});