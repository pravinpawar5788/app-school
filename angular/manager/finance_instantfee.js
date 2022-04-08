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
	$scope.filter={computer_number:'',type:'',month:'',year:'',status:'',class:'',filter:''};
	$scope.pay={voucher_id:'',amount:'',date:'',method:'cash'};
	$scope.entry={voucher_id:'',
		fttl_transport:'Van Fee',famt_transport:'',
		fttl_stationery:'Stationery Funds',famt_stationery:'',
		fttl_library:'Library Fee',famt_library:'',
		fttl_admission:'Admission Fee',famt_admission:'',
		fttl_readmission:'Re Admission Fee',famt_readmission:'',
		fttl_annualfund:'Annual Funds',famt_annualfund:'',
		fttl_paperfund:'Paper Funds',famt_paperfund:'',
		fttl_miscfund:'Misc. Funds',famt_miscfund:'',
		fttl_prospectus:'Prospectus Fee',famt_prospectus:'',
		fttl_absentfine:'Absent Fine',famt_absentfine:'',
		fttl_latefeefine:'Late Fee Fine',famt_latefeefine:'',
		fttl_miscfine:'Misc. Fine',famt_miscfine:'',
		fttl_other:'Others',famt_other:'',
		fttl_concession:'Concession',famt_concession:'',
	};
	
	$scope.vouchers={};
	$scope.listingVouchers=[];
    $scope.promise={};
	$scope.member='';
	$scope.usrid='';
	$scope.focusTriggerInput;
	var timer;
	/////////HOTKEYS/////////////////////////////////////////////////////	 
	hotkeys.add({
	  combo: 'enter',	//enter
	  description: 'Load and save data',
	  allowIn: ['INPUT', 'SELECT'],
	  callback: function(event, hotkey) {
		event.preventDefault();
		switch(config.enter) {
		  case 'pay':
			$scope.saveInstantPayRecord();
			break;
		  default:
			$scope.loadFeeRecord();
		} 
	  }
	}); 
	/////////GENERAL FUNCTIONS/////////////////////////////////////////////////////	             
    $scope.sortBy= function (value){ $scope.sortString=$scope.processSort(value);$scope.loadRows()};
    $scope.clearFilter= function (){ $.each($scope.filter, function(index){$scope.filter[index]='';}); };
    $scope.filterGetString= function (){ var data=''; $.each($scope.filter, function(index,value){data+='&'+index+'='+value;}); return data;};
    $scope.showFilter= function (){var show=false; $.each($scope.filter, function(index,value){if(value !==''){show=true;} }); return show; };
    $scope.clearResponse= function (){ $scope.responseText='';$scope.responseModelText=''; };
    $scope.selectRow = function (row) {$scope.selectedRow = row;};
	$scope.addkey=function (key){$scope.sms.message= $scope.sms.message+' {'+key+'} ';}; 
	
	//////////////////////////LOAD FEE RECORD/////////////////////////////////////
	//load fee record
	$scope.loadFeeRecord=function (){		
		$timeout.cancel(timer);
		timer=$timeout(
			function(){
				if($scope.filter.computer_number.length<1){
					$scope.member='';
					$scope.listingVouchers=[];
					$scope.responseData={};
				}else{
					var data={'rid':$scope.filter.computer_number};
					config.postConfig.params=data;
					$scope.disableButtons();
					$http.get($scope.ajax_url+'loadInstantFeeRecord',config.postConfig).then(function(response){
						$scope.enableButtons();
						var msg=response.data.message;
						if(response.data.error === true){
							$scope.member='';
							$scope.listingVouchers=[];
							$scope.responseData={};
							$log.error(response);
							swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
						}else{
							$scope.member=response.data.student;
							$scope.responseData=response.data.vouchers;
							$scope.listingVouchers=response.data.listed_vouchers;
							if($scope.listingVouchers.length>0){
								$scope.listingVouchers.forEach(function(item,index){
									$scope.pay.voucher_id=item.mid;
									$scope.pay.amount=item.amount;
								});
							}
						}
					},function(response){	
						$scope.enableButtons();		
			            $log.error(response);
					}); 
				} 
			},
			300
		);  
    };	
    //save instant records for any voucher.
	$scope.saveInstantFeeRecord=function (){
		if($scope.pay.voucher_id.length< 1){
		swal({title:"Please select a voucher",type:'info',showConfirmButton:false,timer:2000});
		}else if($scope.pay.voucher_id === null ){
		swal({title:"Please select a voucher",type:'info',showConfirmButton:false,timer:2000});
		}else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
		}else{				
			$scope.disableButtons();
			var data='rid='+$scope.pay.voucher_id;
			data+='&fttl_transport='+encodeURIComponent($scope.entry.fttl_transport)+'&famt_transport='+encodeURIComponent($scope.entry.famt_transport);
			data+='&fttl_stationery='+encodeURIComponent($scope.entry.fttl_stationery)+'&famt_stationery='+encodeURIComponent($scope.entry.famt_stationery);
			data+='&fttl_library='+encodeURIComponent($scope.entry.fttl_library)+'&famt_library='+encodeURIComponent($scope.entry.famt_library);
			data+='&fttl_admission='+encodeURIComponent($scope.entry.fttl_admission)+'&famt_admission='+encodeURIComponent($scope.entry.famt_admission);
			data+='&fttl_readmission='+encodeURIComponent($scope.entry.fttl_readmission)+'&famt_readmission='+encodeURIComponent($scope.entry.famt_readmission);
			data+='&fttl_annualfund='+encodeURIComponent($scope.entry.fttl_annualfund)+'&famt_annualfund='+encodeURIComponent($scope.entry.famt_annualfund);
			data+='&fttl_paperfund='+encodeURIComponent($scope.entry.fttl_paperfund)+'&famt_paperfund='+encodeURIComponent($scope.entry.famt_paperfund);
			data+='&fttl_miscfund='+encodeURIComponent($scope.entry.fttl_miscfund)+'&famt_miscfund='+encodeURIComponent($scope.entry.famt_miscfund);
			data+='&fttl_prospectus='+encodeURIComponent($scope.entry.fttl_prospectus)+'&famt_prospectus='+encodeURIComponent($scope.entry.famt_prospectus);
			data+='&fttl_absentfine='+encodeURIComponent($scope.entry.fttl_absentfine)+'&famt_absentfine='+encodeURIComponent($scope.entry.famt_absentfine);
			data+='&fttl_latefeefine='+encodeURIComponent($scope.entry.fttl_latefeefine)+'&famt_latefeefine='+encodeURIComponent($scope.entry.famt_latefeefine);
			data+='&fttl_miscfine='+encodeURIComponent($scope.entry.fttl_miscfine)+'&famt_miscfine='+encodeURIComponent($scope.entry.famt_miscfine);
			data+='&fttl_other='+encodeURIComponent($scope.entry.fttl_other)+'&famt_other='+encodeURIComponent($scope.entry.famt_other);
			data+='&fttl_concession='+encodeURIComponent($scope.entry.fttl_concession)+'&famt_concession='+encodeURIComponent($scope.entry.famt_concession);
			$http.post($scope.ajax_url+'addInstantFeeRecord',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.entry.famt_transport='';$scope.entry.famt_stationery='';$scope.entry.famt_library='';$scope.entry.famt_admission='';
				$scope.entry.famt_readmission='';$scope.entry.famt_annualfund='';$scope.entry.famt_paperfund='';$scope.entry.famt_miscfund='';
				$scope.entry.famt_prospectus='';$scope.entry.famt_absentfine='';$scope.entry.famt_latefeefine='';$scope.entry.famt_miscfine='';
				$scope.entry.famt_other='';$scope.entry.famt_concession='';
				$scope.loadFeeRecord();
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}					
    }; 
    //save instant records for any voucher.
	$scope.saveInstantPayRecord=function (){
		if($scope.pay.voucher_id.length< 1){
		swal({title:"Please select a voucher",type:'info',showConfirmButton:false,timer:2000});
		}else if($scope.pay.date.length< 1){
		swal({title:"Please select receiving date",type:'info',showConfirmButton:false,timer:2000});
		}else if($scope.pay.amount.length< 1){
		swal({title:"Please enter received amount",type:'info',showConfirmButton:false,timer:2000});
		}else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
		}else{				
			$scope.disableButtons();
			var data='rid='+$scope.pay.voucher_id;
			data+='&date='+encodeURIComponent($scope.pay.date)+'&amount='+encodeURIComponent($scope.pay.amount);
			data+='&method='+encodeURIComponent($scope.pay.method);
			$http.post($scope.ajax_url+'addInstantPayRecord',data,config.postConfig).then(function(response){
				$scope.enableButtons();
				var msg=response.data.message;
				if(response.data.error === true){
				swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				}else{
				swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
				$scope.pay.amount='';
				$scope.loadFeeRecord();
				$scope.focusTriggerInput=true;
				}
			},function(response){
				$scope.enableButtons();
				swal({title:config.globalError,type:'error',showConfirmButton:false,timer:2500});
				$log.error(response);
				
			});
		}					
    }; 


	///////autoload functions////////////////////
	// $scope.loadRows();
	// $timeout(function(){$scope.loadMember();},1000); 


});