app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys,Pubnub){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url='../'+config.ajaxModule+'library/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.filter={type:'',filter:'',stock:'',class:'',late:false};
	$scope.item={type:'',name:'',sub_title:'', author:'',sub_author:'',statement:'',publisher:'',isbn:'',placement_number:'',accession_number:'',ddc_number:'',place:'',volume:'',binding:'',year:'',pages:'',stock:''};
	$scope.issue={member:'',days:''};
    $scope.vouchers={};
    $scope.staffs={};
    $scope.students={};
    $scope.stationary={};
    $scope.selectedMember={mid:0};
	
    $scope.promise={};
	$scope.message='';
	$scope.member={};
	$scope.usrid='';

	/////////HOTKEYS/////////////////////////////////////////////////////	 
	hotkeys.add({
	  combo: 'enter',	//enter
	  description: 'search / save record',
	  allowIn: ['INPUT', 'SELECT'],
	  callback: function(event, hotkey) {
		event.preventDefault();
		switch(config.enter) {
		  case 'add':
			$scope.saveRow();
			break;
		  case 'update':
			$scope.updateRow();
			break;
		  default:
			$scope.loadRows();
		} 
	  }
	});  
	hotkeys.add({
	  combo: 'ctrl+enter',	//enter
	  description: 'save data',
	  allowIn: ['INPUT', 'SELECT', 'TEXTAREA'],
	  callback: function(event, hotkey) {
		event.preventDefault();
		$scope.saveRow();
	  }
	}); 
	/////////GENERAL FUNCTIONS/////////////////////////////////////////////////////	             
    $scope.sortBy= function (value){ $scope.sortString=$scope.processSort(value);$scope.loadRows()};
    $scope.clearFilter= function (){ $.each($scope.filter, function(index){$scope.filter[index]='';}); };
    $scope.filterGetString= function (){ var data=''; $.each($scope.filter, function(index,value){data+='&'+index+'='+value;}); return data;};
    $scope.showFilter= function (){var show=false; $.each($scope.filter, function(index,value){if(value !==''){show=true;} }); return show; };
    $scope.clearResponse= function (){ $scope.responseText='';$scope.responseModelText=''; };
    $scope.selectRow = function (row) {$scope.selectedRow = row;};
	$scope.addkey=function (key){$scope.message= $scope.message+' {'+key+'} ';}; 
    //$scope.selectMember= function () {$scope.selectedMember=JSON.parse($scope.issue.member);};
    $scope.selectMember= function (row) {$scope.selectedMember=row;};
	
	//////////////HISTORY FUNCTIONS/////////////////////////////////////	
    $scope.loadHistory=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':'','late':$scope.filter.late,'type':$scope.filter.type};
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
	
	//////////////HELPER FUNCTIONS/////////////////////////////////////	
    $scope.loadRows=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'type':$scope.filter.type,'filter':$scope.filter.filter,'stock':$scope.filter.stock,'year':$scope.filter.year,'sortby':$scope.sortString};
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
			$log.error(response);
		});
    
    };	
	$scope.saveRow=function (){	
    	if($scope.item.name.length< 1){
		swal({title:"Please enter book name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.item.type.length< 1){
		swal({title:"Please select book category",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='name='+encodeURIComponent($scope.item.name)+'&catagory='+encodeURIComponent($scope.item.type)+'&sub_title='+encodeURIComponent($scope.item.sub_title);
		data+='&author='+encodeURIComponent($scope.item.author)+'&sub_author='+encodeURIComponent($scope.item.sub_author)+'&statement='+encodeURIComponent($scope.item.statement);
		data+='&publisher='+encodeURIComponent($scope.item.publisher)+'&isbn='+encodeURIComponent($scope.item.isbn)+'&placement_number='+encodeURIComponent($scope.item.placement_number);
		data+='&accession_number='+encodeURIComponent($scope.item.accession_number)+'&ddc_number='+encodeURIComponent($scope.item.ddc_number)+'&place='+encodeURIComponent($scope.item.place);
		data+='&volume='+encodeURIComponent($scope.item.volume)+'&binding='+encodeURIComponent($scope.item.binding)+'&year='+encodeURIComponent($scope.item.year);
		data+='&pages='+encodeURIComponent($scope.item.pages)+'&stock='+encodeURIComponent($scope.item.stock);
		$http.post($scope.ajax_url+'add',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.item.name='';$scope.item.sub_title='';$scope.item.author='';$scope.item.sub_author='';$scope.item.statement='';
			$scope.item.publisher='';$scope.item.isbn='';$scope.item.placement_number='';$scope.item.accession_number='';$scope.item.ddc_number='';
			$scope.item.place='';$scope.item.volume='';$scope.item.binding='';$scope.item.year='';$scope.item.pages='';$scope.item.stock='';
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);			
		});
        }
    }; 
	$scope.updateRow=function (){	
    	if($scope.selectedRow.name.length< 1){
		swal({title:"Please enter book name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.catagory.length< 1){
		swal({title:"Please select book category",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		
		var data='rid='+$scope.selectedRow.mid+'&name='+encodeURIComponent($scope.selectedRow.name)+'&catagory='+encodeURIComponent($scope.selectedRow.catagory)+'&sub_title='+encodeURIComponent($scope.selectedRow.sub_title);
		data+='&author='+encodeURIComponent($scope.selectedRow.author)+'&sub_author='+encodeURIComponent($scope.selectedRow.sub_author)+'&statement='+encodeURIComponent($scope.selectedRow.statement);
		data+='&publisher='+encodeURIComponent($scope.selectedRow.publisher)+'&isbn='+encodeURIComponent($scope.selectedRow.isbn)+'&placement_number='+encodeURIComponent($scope.selectedRow.placement_number);
		data+='&accession_number='+encodeURIComponent($scope.selectedRow.accession_number)+'&ddc_number='+encodeURIComponent($scope.selectedRow.ddc_number)+'&place='+encodeURIComponent($scope.selectedRow.place);
		data+='&volume='+encodeURIComponent($scope.selectedRow.volume)+'&binding='+encodeURIComponent($scope.selectedRow.binding)+'&year='+encodeURIComponent($scope.selectedRow.year);
		data+='&pages='+encodeURIComponent($scope.selectedRow.pages)+'&stock='+encodeURIComponent($scope.selectedRow.stock);
		$http.post($scope.ajax_url+'update',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.loadRows();
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			
		});
        }
    }; 
	$scope.delRow=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Delete book of ("+row.name+"). You can not recover it later.!",
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
				$http.post($scope.ajax_url+'delete',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadRows();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	}; 
	$scope.loadStaff=function (){
		var data={'search':$scope.searchText,'page':config.currentPage};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterStaff',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.staffs=response.data;
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});
    
    };
	$scope.loadStudents=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'class_id':$scope.filter.class};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterStudents',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.students=response.data;
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});
    
    };
	$scope.receiveBook=function (row){	
		swal({
			title: 'Are you sure?',
			text: "You are going to receive back the book("+row.book+") from member.!",
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
				var data='rid='+row.mid;
				$http.post($scope.ajax_url+'receiveBook',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadHistory();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	}; 
			
	$scope.issueItemStudent=function (){	
    	if($scope.selectedRow.mid.length< 1){
		swal({title:"Please select a valid book",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedMember.mid.length< 1){
		swal({title:"Please select a member",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.issue.days< 1){
		swal({title:"Please enter for how many days to issue the book",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+encodeURIComponent($scope.selectedMember.mid)+'&item='+encodeURIComponent($scope.selectedRow.mid)+'&days='+encodeURIComponent($scope.issue.days);
		$http.post($scope.ajax_url+'issueStudentItem',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.issue.days='';
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);			
		});
        }
    }; 
	$scope.issueItemStaff=function (){	
    	if($scope.selectedRow.mid.length< 1){
		swal({title:"Please select a valid book",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedMember.mid.length< 1){
		swal({title:"Please select a member",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.issue.days< 1){
		swal({title:"Please enter for how many days to issue the book",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+encodeURIComponent($scope.selectedMember.mid)+'&item='+encodeURIComponent($scope.selectedRow.mid)+'&days='+encodeURIComponent($scope.issue.days);
		//data+='&voucher_id='+encodeURIComponent($scope.issue.voucher_id);
		$http.post($scope.ajax_url+'issueStaffItem',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.issue.days='';
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);			
		});
        }
    }; 
	/////////////////////////////////////////////
	$('#add').on('shown.bs.modal', function () {config.enter='add';});
	$('#edit').on('shown.bs.modal', function () {config.enter='update';});
	$('#add').on('hidden.bs.modal', function () {config.enter='search';});
	$('#edit').on('hidden.bs.modal', function () {config.enter='search';});
	///////autoload functions////////////////////
	$scope.loadRows();
	// $timeout(function(){$scope.loadMember();},1000); 


});