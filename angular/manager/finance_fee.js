app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys,Pubnub){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url='../'+config.ajaxModule+'finance/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.sms={target:'',message:''};
	$scope.filter={type:'',month:'',year:'',status:'',class:'',filter:''};
	$scope.entry={title:'',amount:'',type:'',add:false,filter:'',feetype:'',method:'cash'};
	$scope.newSlip={auto_collection:'',update_voucher:'existing',month:'',year:'',due_date:'',target:'monthly',skipZero:'',class_id:'',feepkgpolicy:''};
	$scope.exSlip={month:'',year:'',due_date:''};
	
	$scope.voucher={};
    $scope.promise={};
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
	$scope.addkey=function (key){$scope.sms.message= $scope.sms.message+' {'+key+'} ';}; 
	
	//////////////HISTORY FUNCTIONS/////////////////////////////////////	
    $scope.loadHistory=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterFeeHistory',config.postConfig).then(function(response){
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
		var data={'search':$scope.searchText,'page':config.currentPage,'class_id':$scope.filter.class,'status':$scope.filter.status,'month':$scope.filter.month,'year':$scope.filter.year,'filter':$scope.entry.filter,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterFee',config.postConfig).then(function(response){
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
	$scope.saveRow=function (){	
		if($scope.entry.type==='plus'){
			if($scope.entry.title.length< 1){
			swal({title:"Please enter record title",type:'info',showConfirmButton:false,timer:2000});
			}else if($scope.entry.feetype.length< 1){
			swal({title:"Please select category",type:'info',showConfirmButton:false,timer:2000});
			}else if($scope.entry.amount< 1){
			swal({title:"Please enter amount",type:'info',showConfirmButton:false,timer:2000});
			}else if(config.btnClickedSave===true){
			swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
			}else{				
				$scope.disableButtons();
				var data='rid='+$scope.selectedRow.mid+'&title='+encodeURIComponent($scope.entry.title);
				data+='&amount='+encodeURIComponent($scope.entry.amount)+'&operation=plus';
				data+='&type='+encodeURIComponent($scope.entry.feetype);
				$http.post($scope.ajax_url+'addFeeEntry',data,config.postConfig).then(function(response){
					$scope.enableButtons();
					var msg=response.data.message;
					if(response.data.error === true){
					swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
					}else{
					swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
					$scope.entry.title='';$scope.entry.amount='';$scope.entry.add=false;
					$scope.loadVoucher($scope.selectedRow);
					}
				},function(response){
					$scope.enableButtons();
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
					
				});
			}			
		}
		if($scope.entry.type==='minus'){
			if($scope.entry.title.length< 1){
			swal({title:"Please enter record title",type:'info',showConfirmButton:false,timer:2000});
			}else if($scope.entry.amount< 1){
			swal({title:"Please enter concession amount",type:'info',showConfirmButton:false,timer:2000});
			}else if(config.btnClickedSave===true){
			swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
			}else{				
				$scope.disableButtons();
				var data='rid='+$scope.selectedRow.mid+'&title='+encodeURIComponent($scope.entry.title)+'&amount='+encodeURIComponent($scope.entry.amount)+'&operation=subtract';
				$http.post($scope.ajax_url+'addFeeEntry',data,config.postConfig).then(function(response){
					$scope.enableButtons();
					var msg=response.data.message;
					if(response.data.error === true){
					swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
					}else{
					swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
					$scope.entry.title='';$scope.entry.amount='';$scope.entry.add=false;
					$scope.loadVoucher($scope.selectedRow);
					}
				},function(response){
					$scope.enableButtons();
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
					
				});
			}			
		}
		
    }; 
	$scope.delRow=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Cancel & Remove record("+row.title+") of student("+row.student_name+").",
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
				var data='rid='+row.mid+'&type=slip';
				$http.post($scope.ajax_url+'deleteFee',data,config.postConfig).then(function(response){
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
	
	//////////////VOUCHER FUNCTIONS/////////////////////////////////////
	$scope.payFee=function (){
		if($scope.entry.type==='advancepay'){
			swal({
			title: 'Are you sure?',
			text: "Pay fee from advance fee received from student("+$scope.selectedRow.student_name+").",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: '<strong>Yes, Pay Now<i class="icon-circle-right2 ml-2"></i></strong>',
			cancelButtonText: 'No, Go Back <i class="icon-circle-left2 ml-2"></i>',
			confirmButtonClass: 'btn btn-warning',
			cancelButtonClass: 'btn btn-light',
			buttonsStyling: false
			}).then(function (result) {
				if(result.value){
					var msg='';
					var data='rid='+$scope.selectedRow.mid+'&method=advance&title=advance&amount=advance';
					$http.post($scope.ajax_url+'getPayment',data,config.postConfig).then(function(response){
						msg=response.data.message;
						if(response.data.error===true){
							swal({title:msg,type:'error',showConfirmButton:false,timer:1500});
							$log.error(response);	
						}else{
							swal({title:msg,type:'success',showConfirmButton:false,timer:800});
							$scope.loadVoucher($scope.selectedRow);
							$scope.loadRows();
						}
					},function (response){
						swal({title:config.globalError,type:'error',showConfirmButton:false,timer:1500});
						$log.error(response);
					});
				}
			});	
        }else{
			if($scope.entry.title.length< 1){
			swal({title:"Please enter record title",type:'info',showConfirmButton:false,timer:2000});
			}if($scope.entry.amount< 0){
			swal({title:"Please enter amount paid by student",type:'info',showConfirmButton:false,timer:2000});
			}else if(config.btnClickedSave===true){
			swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
			}else{
				$scope.disableButtons();
				var data='rid='+$scope.selectedRow.mid+'&title='+encodeURIComponent($scope.entry.title)+'&amount='+encodeURIComponent($scope.entry.amount)+'&method='+encodeURIComponent($scope.entry.method);
				$http.post($scope.ajax_url+'getPayment',data,config.postConfig).then(function(response){
					$scope.enableButtons();
					var msg=response.data.message;
					if(response.data.error === true){
					swal({title:msg,type:'error',showConfirmButton:false,timer:3500});
					$log.error(response);
					}else{
					swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
					$scope.entry.title='';$scope.entry.amount='';$scope.entry.add=false;
					$scope.loadVoucher($scope.selectedRow);
					$scope.loadRows();
					}
				},function(response){
					$scope.enableButtons();
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
					
				});				
			}
		}
	
	};
	$scope.delEntry=function (row){	
		swal({
			title: 'Are you sure?',
			text: "Remove record("+row.remarks+") with amount("+row.amount+").",
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
				var data='rid='+row.mid+'&type=entry';
				$http.post($scope.ajax_url+'deleteFee',data,config.postConfig).then(function(response){
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadVoucher($scope.selectedRow);
						$scope.loadRows();
					}
				},function (response){
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	$scope.createVoucher=function (){
		if($scope.newSlip.month.length< 1 && $scope.newSlip.target==='monthly'){
		swal({title:"Please select month",type:'info',showConfirmButton:false,timer:2000});
		}else if($scope.newSlip.year.length< 1 && $scope.newSlip.target==='monthly'){
		swal({title:"Please select year",type:'info',showConfirmButton:false,timer:2000});
		}else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
		}else{				
			$scope.disableButtons();
			$scope.blockPageUI();
			var data='month='+encodeURIComponent($scope.newSlip.month)+'&year='+encodeURIComponent($scope.newSlip.year);
			data+='&auto_collection='+encodeURIComponent($scope.newSlip.auto_collection)+'&due_date='+encodeURIComponent($scope.newSlip.due_date);
			data+='&class_id='+encodeURIComponent($scope.newSlip.class_id);
			data+='&update_voucher='+encodeURIComponent($scope.newSlip.update_voucher);
			data+='&target='+encodeURIComponent($scope.newSlip.target);
			data+='&skipzero='+encodeURIComponent($scope.newSlip.skipZero);
			data+='&feepkgpolicy='+encodeURIComponent($scope.newSlip.feepkgpolicy);
			$http.post($scope.ajax_url+'createFeeVouchers',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				$scope.unblockPageUI();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:3500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.loadRows();
				}
			},function(response){
				$scope.enableButtons();
				$scope.unblockPageUI();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}
		
    }; 
	$scope.delVouchers=function (){	
		swal({
			title: 'Are you sure?',
			text: "Remove generated fee slips for this month. All fee slips either paid, not yet paid or partialy paid will be canceled and removed from system. If you have entered class arrears then you need to add them once again before creating new vouchers. Please be cautious while doing this operation and only perform this action if extremely necessary.",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: '<strong>Yes, Cancel & Remove <i class="icon-circle-right2 ml-2"></i></strong>',
			cancelButtonText: 'No, Go Back <i class="icon-circle-left2 ml-2"></i>',
			confirmButtonClass: 'btn btn-warning',
			cancelButtonClass: 'btn btn-light',
			buttonsStyling: false
		}).then(function (result) {
			if(result.value){
				var msg='';
				var data='';
				$scope.blockPageUI();
				$http.post($scope.ajax_url+'deleteFeeVouchers',data,config.postConfig).then(function(response){
					$log.info(response);
					$scope.unblockPageUI();
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
	$scope.mergeVouchers=function (){	
		swal({
			title: 'Are you sure?',
			text: "System will merge multiple vouchers into one for every students.",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: '<strong>Yes, Merge Now <i class="icon-circle-right2 ml-2"></i></strong>',
			cancelButtonText: 'No, Go Back <i class="icon-circle-left2 ml-2"></i>',
			confirmButtonClass: 'btn btn-warning',
			cancelButtonClass: 'btn btn-light',
			buttonsStyling: false
		}).then(function (result) {
			if(result.value){
				var msg='';
				var data='';
				$scope.blockPageUI();
				$http.post($scope.ajax_url+'mergeFeeVouchers',data,config.postConfig).then(function(response){
					// $log.info(response);
					$scope.unblockPageUI();
					msg=response.data.message;
					if(response.data.error===true){
						swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						$log.error(response);	
					}else{
						swal({title:msg,type:'success',showConfirmButton:false,timer:1200});
						$scope.loadRows();
					}
				},function (response){
					$scope.unblockPageUI();
					swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
					$log.error(response);
				});
			}
		});
	
	};
	$scope.delCustomVouchers=function (){
		if($scope.exSlip.month.length< 1){
		swal({title:"Please select month",type:'info',showConfirmButton:false,timer:2000});
		}else if($scope.exSlip.year.length< 1){
		swal({title:"Please select year",type:'info',showConfirmButton:false,timer:2000});
		}else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
		}else{				
			$scope.disableButtons();
			var data='month='+encodeURIComponent($scope.exSlip.month)+'&year='+encodeURIComponent($scope.exSlip.year);
			$http.post($scope.ajax_url+'deleteFeeVouchers',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:3500});
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

	//////////////HELPER FUNCTIONS/////////////////////////////////////	
    $scope.clearRows=function (){
    	$scope.responseData={};
		config.responseNumRows=0;
		$scope.validatePagination();    
    };	
    $scope.loadFeeVuchers=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'filter':$scope.entry.filter,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterPendingFee',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.responseData=response.data;
			config.responseNumRows=$scope.responseData.total_rows;
			// $scope.validatePagination();
			
		},function(response){	
			$scope.enableButtons();		
            // swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});
    
    };	

	$scope.receiveFee=function (row){
		$scope.disableButtons();
		var data='rid='+row.mid+'&title='+encodeURIComponent("Fee Received")+'&amount='+encodeURIComponent(row.amount)+'&method='+encodeURIComponent($scope.entry.method);
		$http.post($scope.ajax_url+'getPayment',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:1500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:800});
			row.status='PAID';
			// $scope.entry.title='';$scope.entry.amount='';$scope.entry.add=false;
			// $scope.loadVoucher($scope.selectedRow);
			// $scope.loadRows();
			}
		},function(response){
			$scope.enableButtons();
			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:1500});
			$log.error(response);
			
		});

		// swal({
		// title: 'Are you sure?',
		// text: "You are going to receive fee("+row.amount+") from student("+row.student_name+").",
		// type: 'warning',
		// showCancelButton: true,
		// confirmButtonText: '<strong>Yes, Receive Fee<i class="icon-circle-right2 ml-2"></i></strong>',
		// cancelButtonText: 'No, Go Back <i class="icon-circle-left2 ml-2"></i>',
		// confirmButtonClass: 'btn btn-warning',
		// cancelButtonClass: 'btn btn-light',
		// buttonsStyling: false
		// }).then(function (result) {
		// 	if(result.value){
		// 		$scope.disableButtons();
		// 		var data='rid='+row.mid+'&title='+encodeURIComponent("Fee Received")+'&amount='+encodeURIComponent(row.amount)+'&method=cash';
		// 		$http.post($scope.ajax_url+'getPayment',data,config.postConfig).then(function(response){
		// 			$scope.enableButtons();
		// 			var msg=response.data.message;
		// 			if(response.data.error === true){
		// 			swal({title:msg,type:'error',showConfirmButton:false,timer:3500});
		// 			$log.error(response);
		// 			}else{
		// 			swal({title:msg,type:'success',showConfirmButton:false,timer:2500});
		// 			// $scope.entry.title='';$scope.entry.amount='';$scope.entry.add=false;
		// 			// $scope.loadVoucher($scope.selectedRow);
		// 			// $scope.loadRows();
		// 			}
		// 		},function(response){
		// 			$scope.enableButtons();
		// 			swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
		// 			$log.error(response);
					
		// 		});	
		// 	}
		// });
	
	};
	/////////SMS FUNCTIONS//////////////////////	
	$scope.sendListSms=function (){
		if($scope.sms.target.length< 1){
		swal({title:"Please choose receiver",type:'info',showConfirmButton:false,timer:2000});
		}else if($scope.sms.message.length< 1){
		swal({title:"Please enter message",type:'info',showConfirmButton:false,timer:2000});
		}else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
		}else{	
			var data={'message':$scope.sms.message,'target':$scope.sms.target,'status':$scope.filter.status,'class_id':$scope.filter.class,'month':$scope.filter.month,'year':$scope.filter.year};
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
				$scope.sms.message='';
				}
			},function(response){	
				$scope.enableButtons();		
				swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
				$log.error(response);
			});  
		}
    };	
	$scope.sendSingleSms=function (){
		if($scope.sms.target.length< 1){
		swal({title:"Please choose receiver",type:'info',showConfirmButton:false,timer:2000});
		}else if($scope.sms.message.length< 1){
		swal({title:"Please enter message",type:'info',showConfirmButton:false,timer:2000});
		}else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
		}else{	
			var data={'message':$scope.sms.message,'target':$scope.sms.target,'rid':$scope.selectedRow.mid};
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
				$scope.sms.message='';
				}
			},function(response){	
				$scope.enableButtons();		
				swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
				$log.error(response);
			});  
		}
    };	
	///////autoload functions////////////////////
	// $scope.loadRows();
	// $timeout(function(){$scope.loadMember();},1000); 


});