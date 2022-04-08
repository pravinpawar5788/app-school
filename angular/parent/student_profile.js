app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys,Pubnub){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url='../../'+config.ajaxModule+'student/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.filter={status:'',session:'',class:'',fee:'',gender:'',blood_group:''};
	$scope.allowance={title:'',amount:'', add:true};
	$scope.deduction={title:'',amount:'',duration:'', add:true};
	$scope.advance={title:'',amount:'',create_voucher:'', add:true};
	$scope.academic={session:'',class:'',roll_number:'',obtained_marks:'',total_marks:'',status:'', add:true};
	$scope.award={awardId:'',remarks:'', add:true};
	$scope.discipline={disciplineId:'',remarks:'', add:true};
	$scope.achievement={title:'',remarks:'', add:true};
    $scope.promise={};
	$scope.message='';
	$scope.events=[];
	$scope.defaultDate='';
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
    $scope.selectAllowance= function (row) {$scope.allowance=row;};
    $scope.selectDeduction= function (row) {$scope.deduction=row;};
    $scope.selectAcademic= function (row) {$scope.academic=row;};
    $scope.selectAward= function (row) {$scope.award=row;};
    $scope.selectAchievement= function (row) {$scope.achievement=row;};
    $scope.selectDiscipline= function (row) {$scope.discipline=row;};
	//////////////ALLOWANCE FUNCTIONS/////////////////////////////////////	
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
	$scope.loadDeductions=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString,'rid':$scope.member.mid};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterDeductions',config.postConfig).then(function(response){
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
	$scope.loadFeerecord=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString,'rid':$scope.member.mid};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterFee',config.postConfig).then(function(response){
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
	$scope.loadVoucher=function (row){
		var data={'rid':row.mid};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'loadFeeVoucher',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.voucher=response.data.output;
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
	$scope.loadHomework=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString,'rid':$scope.member.mid};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterHomework',config.postConfig).then(function(response){
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
	$scope.loadAttendance=function (){
		var data={'rid':$scope.usrid};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'loadAttendance',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.events=response.data.events;
			$scope.defaultDate=response.data.default_date;
			$scope.initCalendar();
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});    
    };	
	
	$scope.loadTests=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString,'rid':$scope.member.mid};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterTestProgressDetail',config.postConfig).then(function(response){
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

	$scope.loadTermResult=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString,'rid':$scope.member.mid};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterTermResult',config.postConfig).then(function(response){
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

	$scope.loadFinalResult=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString,'rid':$scope.member.mid};
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

	$scope.loadMember=function (){
		var data={'rid':$scope.usrid};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'load',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.member=response.data.output;		
			// if(response.data.narration===true){
				// $scope.speak('Welcome To '+$scope.member.name+' profile.');
			// }	
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});    
    };	
	
    // Initializ Calendar
    $scope.initCalendar=function (){
		// Basic view
		$('.calendar-attendance').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,basicWeek'
			},
			defaultDate: $scope.defaultDate,
			editable: false,
			events: $scope.events,
			eventLimit: true,
			isRTL: $('html').attr('dir') == 'rtl' ? true : false
		});
	};

	///////autoload functions////////////////////	
	// $scope.loadRows();
	$timeout(function(){$scope.loadMember();},1000); 


});