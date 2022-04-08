app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys,Pubnub){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url='../../'+config.ajaxModule+'staff/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.filter={status:'',role:'',salary:'',gender:'',blood_group:''};
	$scope.allowance={title:'',amount:'', add:true};
	$scope.deduction={title:'',amount:'',duration:'', add:true};
	$scope.advance={title:'',amount:'', add:true};
	$scope.academic={qualification:'',year:'',roll_number:'',registration_no:'',program:'',institute:'', add:true};
	$scope.award={awardId:'',remarks:'', add:true};
	$scope.discipline={disciplineId:'',remarks:'', add:true};
	$scope.achievement={title:'',remarks:'', add:true};
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
	$scope.saveAllowance=function (){	
    	if($scope.allowance.title.length< 1){
		swal({title:"Please enter title",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.allowance.amount< 1){
		swal({title:"Please provide amount",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		if($scope.allowance.add===true){	
			//add data
			var data='rid='+encodeURIComponent($scope.member.mid)+'&title='+encodeURIComponent($scope.allowance.title)+'&amount='+encodeURIComponent($scope.allowance.amount);
			$http.post($scope.ajax_url+'addAllowance',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.allowance.title='';$scope.allowance.amount='';
				$scope.loadAllowances();
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}else{		
			//update data
			var data='rid='+encodeURIComponent($scope.allowance.mid)+'&title='+encodeURIComponent($scope.allowance.title)+'&amount='+encodeURIComponent($scope.allowance.amount);
			$http.post($scope.ajax_url+'updateAllowance',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.allowance.title='';$scope.allowance.amount='';
				$scope.loadAllowances();
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}
        }
    }; 
	$scope.delAllowance=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Terminate allowance ("+row.title+") of this member. ",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: '<strong>Yes, Terminate it <i class="icon-circle-right2 ml-2"></i></strong>',
			cancelButtonText: 'No, Go Back <i class="icon-circle-left2 ml-2"></i>',
			confirmButtonClass: 'btn btn-warning',
			cancelButtonClass: 'btn btn-light',
			buttonsStyling: false
		}).then(function (result) {
			if(result.value){
				var msg='';
				var data='rid='+row.mid;
				$http.post($scope.ajax_url+'deleteAllowance',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadAllowances();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	
	//////////////DEDUCTION FUNCTIONS/////////////////////////////////////	
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
	$scope.saveDeduction=function (){	
    	if($scope.deduction.title.length< 1){
		swal({title:"Please enter reason",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.deduction.amount< 1){
		swal({title:"Please provide amount",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.deduction.duration< 1){
		swal({title:"Please provide valid installments",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		if($scope.deduction.add===true){	
			//add data
			var data='rid='+encodeURIComponent($scope.member.mid)+'&title='+encodeURIComponent($scope.deduction.title)+'&amount='+encodeURIComponent($scope.deduction.amount)+'&duration='+encodeURIComponent($scope.deduction.duration);
			$http.post($scope.ajax_url+'addDeduction',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:3500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.deduction.title='';$scope.deduction.amount='';$scope.deduction.duration='';$scope.deduction.add=true;
				$scope.loadDeductions();
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}else{		
			//update data
			var data='rid='+encodeURIComponent($scope.deduction.mid)+'&title='+encodeURIComponent($scope.deduction.title)+'&amount='+encodeURIComponent($scope.deduction.amount);
			$http.post($scope.ajax_url+'updateDeduction',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.deduction.title='';$scope.deduction.amount='';$scope.deduction.duration='';$scope.deduction.add=true;
				$scope.loadDeductions();
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}
        }
    }; 
	$scope.delDeduction=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Terminate installment ("+row.title+") of this member. Please only do this if you have received back the cash.",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: '<strong>Yes, Terminate it <i class="icon-circle-right2 ml-2"></i></strong>',
			cancelButtonText: 'No, Go Back <i class="icon-circle-left2 ml-2"></i>',
			confirmButtonClass: 'btn btn-warning',
			cancelButtonClass: 'btn btn-light',
			buttonsStyling: false
		}).then(function (result) {
			if(result.value){
				var msg='';
				var data='rid='+row.mid;
				$http.post($scope.ajax_url+'deleteDeduction',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadDeductions();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	
	//////////////ADVANCE FUNCTIONS/////////////////////////////////////	
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
	$scope.saveAdvance=function (){	
    	if($scope.advance.title.length< 1){
		swal({title:"Please enter narration",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.advance.amount< 1){
		swal({title:"Please provide amount",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		if($scope.allowance.add===true){	
			//add data
			var data='rid='+encodeURIComponent($scope.member.mid)+'&title='+encodeURIComponent($scope.advance.title)+'&amount='+encodeURIComponent($scope.advance.amount);
			$http.post($scope.ajax_url+'addAdvance',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:4500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.allowance.title='';$scope.allowance.amount='';
				$scope.loadAdvance();
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}else{		
			//update data
			var data='rid='+encodeURIComponent($scope.allowance.mid)+'&title='+encodeURIComponent($scope.advance.title)+'&amount='+encodeURIComponent($scope.advance.amount);
			$http.post($scope.ajax_url+'updateAdvance',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.allowance.title='';$scope.allowance.amount='';
				$scope.loadAdvance();
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}
        }
    }; 
	$scope.delAdvance=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Terminate advance ("+row.title+") of this member. ",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: '<strong>Yes, Terminate it <i class="icon-circle-right2 ml-2"></i></strong>',
			cancelButtonText: 'No, Go Back <i class="icon-circle-left2 ml-2"></i>',
			confirmButtonClass: 'btn btn-warning',
			cancelButtonClass: 'btn btn-light',
			buttonsStyling: false
		}).then(function (result) {
			if(result.value){
				var msg='';
				var data='rid='+row.mid;
				$http.post($scope.ajax_url+'deleteAdvance',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadAdvance();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	
	//////////////LOAN FUNCTIONS/////////////////////////////////////	
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
	$scope.saveLoan=function (){	
    	if($scope.advance.title.length< 1){
		swal({title:"Please enter narration",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.advance.amount< 1){
		swal({title:"Please provide amount",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		if($scope.allowance.add===true){	
			//add data
			var data='rid='+encodeURIComponent($scope.member.mid)+'&title='+encodeURIComponent($scope.advance.title)+'&amount='+encodeURIComponent($scope.advance.amount);
			$http.post($scope.ajax_url+'addAdvance',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.allowance.title='';$scope.allowance.amount='';
				$scope.loadAdvance();
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}else{		
			//update data
			var data='rid='+encodeURIComponent($scope.allowance.mid)+'&title='+encodeURIComponent($scope.advance.title)+'&amount='+encodeURIComponent($scope.advance.amount);
			$http.post($scope.ajax_url+'updateAdvance',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.allowance.title='';$scope.allowance.amount='';
				$scope.loadAdvance();
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}
        }
    }; 
	$scope.delLoan=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Terminate allowance ("+row.title+") of this member. ",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: '<strong>Yes, Terminate it <i class="icon-circle-right2 ml-2"></i></strong>',
			cancelButtonText: 'No, Go Back <i class="icon-circle-left2 ml-2"></i>',
			confirmButtonClass: 'btn btn-warning',
			cancelButtonClass: 'btn btn-light',
			buttonsStyling: false
		}).then(function (result) {
			if(result.value){
				var msg='';
				var data='rid='+row.mid;
				$http.post($scope.ajax_url+'deleteAdvance',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadAdvance();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	
	//////////////HISTORY FUNCTIONS/////////////////////////////////////	
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
		
    $scope.loadSalaryHistory=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString,'rid':$scope.member.mid};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterSalaryHistory',config.postConfig).then(function(response){
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
	
	//////////////ACADEMICS FUNCTIONS/////////////////////////////////////	
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
	$scope.delAcademic=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Remove academic record("+row.qualification+") of this member. ",
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
				$http.post($scope.ajax_url+'deleteQual',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadAcademic();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	
	//////////////AWARD FUNCTIONS/////////////////////////////////////	
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
	$scope.saveAward=function (){	
    	if($scope.award.awardId< 1){
		swal({title:"Please select award",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.award.remarks.length< 1){
		swal({title:"Please enter remarks",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		if($scope.award.add===true){	
			//add data
			var data='rid='+encodeURIComponent($scope.member.mid)+'&award='+encodeURIComponent($scope.award.awardId)+'&remarks='+encodeURIComponent($scope.award.remarks);
			$http.post($scope.ajax_url+'addAward',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.award.awardId='';$scope.award.remarks='';
				$scope.loadAwards();
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}else{		
			//update data
			var data='rid='+encodeURIComponent($scope.award.mid)+'&awardId='+encodeURIComponent($scope.award.awardId)+'&remarks='+encodeURIComponent($scope.award.remarks);
			$http.post($scope.ajax_url+'updateAward',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.award.awardId='';$scope.award.remarks='';$scope.award.add=true;
				$scope.loadAwards();
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}
        }
    }; 
	$scope.delAward=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Remove award("+row.title+") of this member. ",
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
				$http.post($scope.ajax_url+'deleteAward',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadAwards();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	
	//////////////DISCIPLINE FUNCTIONS/////////////////////////////////////	
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
	$scope.saveDiscipline=function (){	
    	if($scope.discipline.disciplineId< 1){
		swal({title:"Please select notice",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.discipline.remarks.length< 1){
		swal({title:"Please enter remarks",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		if($scope.discipline.add===true){	
			//add data
			var data='rid='+encodeURIComponent($scope.member.mid)+'&notice='+encodeURIComponent($scope.discipline.disciplineId)+'&remarks='+encodeURIComponent($scope.discipline.remarks);
			$http.post($scope.ajax_url+'addPunishment',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.discipline.disciplineId='';$scope.discipline.remarks='';
				$scope.loadDiscipline();
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}else{		
			//update data
			var data='rid='+encodeURIComponent($scope.discipline.mid)+'&notice='+encodeURIComponent($scope.discipline.disciplineId)+'&remarks='+encodeURIComponent($scope.discipline.remarks);
			$http.post($scope.ajax_url+'updatePunishment',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.discipline.disciplineId='';$scope.discipline.remarks='';$scope.discipline.add=true;
				$scope.loadDiscipline();
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}
        }
    }; 
	$scope.delDiscipline=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Remove notice("+row.title+") of this member. ",
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
				$http.post($scope.ajax_url+'deletePunishment',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadDiscipline();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	
	//////////////ACHIEVEMENT FUNCTIONS/////////////////////////////////////	
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
	$scope.saveAchievement=function (){	
    	if($scope.achievement.title.length< 1){
		swal({title:"Please enter title",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.achievement.remarks.length< 1){
		swal({title:"Please enter remarks",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		if($scope.achievement.add===true){	
			//add data
			var data='rid='+encodeURIComponent($scope.member.mid)+'&title='+encodeURIComponent($scope.achievement.title)+'&remarks='+encodeURIComponent($scope.achievement.remarks);
			$http.post($scope.ajax_url+'addAchievement',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.achievement.title='';$scope.achievement.remarks='';
				$scope.loadAchievement();
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}else{		
			//update data
			var data='rid='+encodeURIComponent($scope.achievement.mid)+'&title='+encodeURIComponent($scope.achievement.title)+'&remarks='+encodeURIComponent($scope.achievement.remarks);
			$http.post($scope.ajax_url+'updateAchievement',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.achievement.title='';$scope.achievement.remarks='';$scope.achievement.add=true;
				$scope.loadAchievement();
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}
        }
    }; 
	$scope.delAchievement=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Remove endorsement("+row.title+") of this member. ",
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
				$http.post($scope.ajax_url+'deleteAchievement',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadAchievement();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	
	//////////////HELPER FUNCTIONS/////////////////////////////////////	
    $scope.loadRows=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'status':$scope.filter.status,'role_id':$scope.filter.role,'salary':$scope.filter.salary,'gender':$scope.filter.gender,'blood_group':$scope.filter.blood_group,'sortby':$scope.sortString};
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
		/*
		//SET INTERVAL TO AUTO UPDATE THE ROWS
		$scope.promise=$interval(function () {
			$http.get($scope.ajax_url+'filterBrandRows',config.postConfig).then(function(response){
			$scope.responseData=response.data;
			config.responseNumRows=$scope.responseData.total_rows;
			$scope.validatePagination();
			},function(response){	
				$log.error(response);
			});
		}, 10000);*/
    
    };	
	$scope.saveRow=function (){	
    	if($scope.staff.name.length< 1){
		swal({title:"Please enter full name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.staff.cnic.length< 1){
		swal({title:"Please enter cnic",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.staff.mobile.length< 1){
		swal({title:"Please mobile number",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.staff.role< 1){
		swal({title:"Please select designation",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.staff.salary< 1){
		swal({title:"Please provide basic salary",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.staff.staff_id.length< 1){
		swal({title:"Please provide staff id",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='name='+encodeURIComponent($scope.staff.name)+'&cnic='+encodeURIComponent($scope.staff.cnic)+'&mobile='+encodeURIComponent($scope.staff.mobile);
		data+='&salary='+encodeURIComponent($scope.staff.salary)+'&home_phone='+encodeURIComponent($scope.staff.home_phone)+'&guardian_name='+encodeURIComponent($scope.staff.guardian_name);
		data+='&role_id='+encodeURIComponent($scope.staff.role)+'&gender='+encodeURIComponent($scope.staff.gender)+'&blood_group='+encodeURIComponent($scope.staff.blood_group);
		data+='&qualification='+encodeURIComponent($scope.staff.qualification)+'&postal_address='+encodeURIComponent($scope.staff.postal_address)+'&home_address='+encodeURIComponent($scope.staff.home_address);
		data+='&staff_id='+encodeURIComponent($scope.staff.staff_id)+'&password='+encodeURIComponent($scope.staff.password)+'&email='+encodeURIComponent($scope.staff.email);
		data+='&favourite_subject='+encodeURIComponent($scope.staff.favourite_subject)+'&experience='+encodeURIComponent($scope.staff.experience)+'&contract='+encodeURIComponent($scope.staff.contract);
		data+='&anual_increment='+encodeURIComponent($scope.staff.anual_increment);
		$http.post($scope.ajax_url+'add',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.staff.name='';$scope.staff.cnic='';$scope.staff.mobile='';$scope.staff.salary='';$scope.staff.home_phone='';$scope.staff.guardian_name='';
			$scope.staff.role='';$scope.staff.gender='male';$scope.staff.blood_group='';$scope.staff.qualification='';$scope.staff.postal_address='';$scope.staff.home_address='';
			$scope.staff.staff_id='';$scope.staff.password='';$scope.staff.email='';$scope.staff.favourite_subject='';$scope.staff.experience='';$scope.staff.contract='';
			$scope.staff.anual_increment='';
			$scope.loadRows();
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
		swal({title:"Please enter full name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.cnic.length< 1){
		swal({title:"Please enter cnic",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.mobile.length< 1){
		swal({title:"Please mobile number",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.role_id< 1){
		swal({title:"Please select designation",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.salary< 1){
		swal({title:"Please provide basic salary",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.staff_id.length< 1){
		swal({title:"Please provide staff id",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&name='+encodeURIComponent($scope.selectedRow.name)+'&cnic='+encodeURIComponent($scope.selectedRow.cnic)+'&mobile='+encodeURIComponent($scope.selectedRow.mobile);
		data+='&salary='+encodeURIComponent($scope.selectedRow.salary)+'&home_phone='+encodeURIComponent($scope.selectedRow.home_phone)+'&guardian_name='+encodeURIComponent($scope.selectedRow.guardian_name);
		data+='&role_id='+encodeURIComponent($scope.selectedRow.role_id)+'&gender='+encodeURIComponent($scope.selectedRow.gender)+'&blood_group='+encodeURIComponent($scope.selectedRow.blood_group);
		data+='&qualification='+encodeURIComponent($scope.selectedRow.qualification)+'&postal_address='+encodeURIComponent($scope.selectedRow.postal_address)+'&home_address='+encodeURIComponent($scope.selectedRow.home_address);
		data+='&staff_id='+encodeURIComponent($scope.selectedRow.staff_id)+'&email='+encodeURIComponent($scope.selectedRow.email)+'&anual_increment='+encodeURIComponent($scope.selectedRow.anual_increment);
		data+='&favourite_subject='+encodeURIComponent($scope.selectedRow.favourite_subject)+'&experience='+encodeURIComponent($scope.selectedRow.experience)+'&contract='+encodeURIComponent($scope.selectedRow.contract);
		$http.post($scope.ajax_url+'update',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			
		});
        }
    }; 
	//change status
	$scope.changeStatus=function (row,status){	
		swal({
			title: 'Are you sure?',
			text: "Update status of ("+row.name+")!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: '<strong>Yes, Update <i class="icon-circle-right2 ml-2"></i></strong>',
			cancelButtonText: 'No, Go Back <i class="icon-circle-left2 ml-2"></i>',
			confirmButtonClass: 'btn btn-warning',
			cancelButtonClass: 'btn btn-light',
			buttonsStyling: false
		}).then(function (result) {
			if(result.value){
				var msg='';
				var data='rid='+row.mid+'&status='+status;
				$http.post($scope.ajax_url+'changeStatus',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						row.status=status;
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	//delete account
	$scope.delRow=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Delete account of ("+row.name+"). You can not recover it later.!",
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
	//change password
	$scope.changePassword=function (){	
    	if($scope.staff.password.length< 1){
		swal({title:"Please enter new password",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&password='+encodeURIComponent($scope.staff.password);
		$http.post($scope.ajax_url+'updatePassword',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.staff.password='';
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			
		});
        }
    }; 
	//send sms to all staff
	$scope.sendListSms=function (){
		var data={'message':$scope.message,'status':$scope.filter.status,'role_id':$scope.filter.role,'salary':$scope.filter.salary,'gender':$scope.filter.gender,'blood_group':$scope.filter.blood_group};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'sendListSms',config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.clearFilter();
			$scope.message='';
			}
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});    
    };	
	//send sms to single staff
	$scope.sendSingleSms=function (){
		var data={'message':$scope.message,'rid':$scope.selectedRow.mid};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'sendSingleSms',config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.message='';
			}
		},function(response){	
			$scope.enableButtons();		
            swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});    
    };	
	//load staff member
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
	
	///////autoload functions////////////////////
	// $scope.loadRows();
	$timeout(function(){$scope.loadMember();},1000); 


});