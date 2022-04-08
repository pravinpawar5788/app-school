app.controller("mozzCtrl", function($scope,$http,$timeout,$log,$window,$sce,$interval,config,hotkeys){
	//controller variables
	$scope.appConfig=config;
	$scope.ajax_url=config.ajaxModule+'student/';
	$scope.pageIndex=config.currentPage;
	$scope.subPageIndex=0;
	$scope.searchText='';
	$scope.responseText='';
	$scope.responseData={};
	$scope.selectedRow={};
	$scope.sortString='';
	///////////////////////////////
	$scope.filter={status:'',session:'',class:'',fee:'',gender:'',blood_group:'',target:'',filter:'',group_id:''};
	$scope.entry={name:'',title:'',amount:'',due_date:''};
	$scope.student={admission_number:'',computer_number:'',group_id:'',family_number:'',name:'',nic:'',mobile:'',fee:'',date_of_birth:'',gender:'male',blood_group:'',medical_problem:'',emergency_contact:'',guardian_name:'',guardian_mobile:'',father_name:'',father_nic:'',father_occupation:'',mother_name:'',mother_nic:'',address:'',religion:'',cast:'',class_id:'',session_id:'',section_id:'',section:'',roll_no:'',fee_type:'monthly',other_info:'',create_voucher:'',admission_fee:'',security_fee:'',annual_fund:'',prospectus:'',create_fee:'',is_installments:'',installments:'',force_inspkg:'',installment:'',pkg_installment:'',first_installment:'',pkg_total_marks:'',pkg_obt_marks:'',due_date:'',frequency:''};
    $scope.last_admission_number='';
	$scope.classSections={};
	$scope.classFeePackage={};
    $scope.promise={};
	$scope.message='';
	$scope.usrid='';

	/////////HOTKEYS///////////////////////////////////////////////////	 
	hotkeys.add({
	  combo: 'enter',	//enter
	  description: 'Search / Save student',
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
	  combo: 'shift+n',	//ctrl+enter
	  description: 'Open new account registration form.',
	  //allowIn: ['INPUT', 'SELECT', 'TEXTAREA'],
	  callback: function(event, hotkey) {
		event.preventDefault();
		$('#add').modal('show');
	  }
	});
	
	hotkeys.add({
	  combo: 'shift+s',	//ctrl+enter
	  description: 'Save new student while creating new account',
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
    $scope.calculateInstallment = function () {
    	$scope.student.installment = ($scope.student.fee-$scope.student.first_installment)/$scope.student.installments;
    	$scope.loadClassFeePackage();
    };
	//////////////HELPER FUNCTIONS/////////////////////////////////////	
    $scope.loadRows=function (){
		var data={'search':$scope.searchText,'page':config.currentPage,'status':$scope.filter.status,'class_id':$scope.filter.class,'group_id':$scope.filter.group_id,'session_id':$scope.filter.session,'fee':$scope.filter.fee,'gender':$scope.filter.gender,'blood_group':$scope.filter.blood_group,'filter':$scope.filter.filter,'sortby':$scope.sortString};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filter',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.responseData=response.data;
			config.responseNumRows=$scope.responseData.total_rows;
			$scope.student.computer_number=parseInt($scope.responseData.last_computer_number)+1;
			$scope.student.family_number=parseInt($scope.responseData.last_family_number)+1;
			$scope.validatePagination();			
			
		},function(response){	
			$scope.enableButtons();		
            // swal({title: "Error!",text: ""+config.globalError,confirmButtonColor: "#2196F3",type: "error"});
			$log.error(response);
		});
    
    };	
	$scope.saveRow=function (){	
    	if($scope.student.admission_number.length< 1){
		swal({title:"Please enter admission number",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.student.computer_number.length< 1){
		swal({title:"Please enter computer number",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.student.family_number.length< 1){
		swal({title:"Please enter famlily number",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.student.name.length< 1){
		swal({title:"Please enter full name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.student.guardian_name.length< 1){
		swal({title:"Please provide guardian name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.student.guardian_mobile.length< 1){
		swal({title:"Please provide guardian mobile",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.student.session_id< 1){
		swal({title:"Please selection admission session",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.student.class_id< 1){
		swal({title:"Please select admission class",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='name='+encodeURIComponent($scope.student.name)+'&nic='+encodeURIComponent($scope.student.nic)+'&mobile='+encodeURIComponent($scope.student.mobile)+'&fee='+encodeURIComponent($scope.student.fee);
		data+='&fee_type='+encodeURIComponent($scope.student.fee_type)+'&date_of_birth='+encodeURIComponent($scope.student.date_of_birth)+'&gender='+encodeURIComponent($scope.student.gender);
		data+='&blood_group='+encodeURIComponent($scope.student.blood_group)+'&medical_problem='+encodeURIComponent($scope.student.medical_problem)+'&emergency_contact='+encodeURIComponent($scope.student.emergency_contact);
		data+='&guardian_name='+encodeURIComponent($scope.student.guardian_name)+'&guardian_mobile='+encodeURIComponent($scope.student.guardian_mobile)+'&father_name='+encodeURIComponent($scope.student.father_name);
		data+='&father_nic='+encodeURIComponent($scope.student.father_nic)+'&father_occupation='+encodeURIComponent($scope.student.father_occupation)+'&mother_name='+encodeURIComponent($scope.student.mother_name);
		data+='&mother_nic='+encodeURIComponent($scope.student.mother_nic)+'&address='+encodeURIComponent($scope.student.address)+'&religion='+encodeURIComponent($scope.student.religion);
		data+='&cast='+encodeURIComponent($scope.student.cast)+'&class_id='+encodeURIComponent($scope.student.class_id)+'&session_id='+encodeURIComponent($scope.student.session_id);
		data+='&roll_no='+encodeURIComponent($scope.student.roll_no)+'&other_info='+encodeURIComponent($scope.student.other_info)+'&admission_fee='+encodeURIComponent($scope.student.admission_fee);
		data+='&admission_number='+encodeURIComponent($scope.student.admission_number)+'&section='+encodeURIComponent($scope.student.section);
		data+='&computer_number='+encodeURIComponent($scope.student.computer_number)+'&family_number='+encodeURIComponent($scope.student.family_number);
		data+='&section_id='+encodeURIComponent($scope.student.section_id);
		data+='&create_fee='+encodeURIComponent($scope.student.create_fee)+'&is_installments='+encodeURIComponent($scope.student.is_installments);
		data+='&create_voucher='+encodeURIComponent($scope.student.create_voucher)+'&security_fee='+encodeURIComponent($scope.student.security_fee);
		data+='&annual_fund='+encodeURIComponent($scope.student.annual_fund)+'&prospectus='+encodeURIComponent($scope.student.prospectus);
		data+='&pkg_total_marks='+encodeURIComponent($scope.student.pkg_total_marks)+'&pkg_obt_marks='+encodeURIComponent($scope.student.pkg_obt_marks);
		data+='&first_installment='+encodeURIComponent($scope.student.first_installment)+'&installments='+encodeURIComponent($scope.student.installments);
		data+='&installment='+encodeURIComponent($scope.student.installment);
		data+='&force_inspkg='+encodeURIComponent($scope.student.force_inspkg);
		data+='&frequency='+encodeURIComponent($scope.student.frequency);
		data+='&due_date='+encodeURIComponent($scope.student.due_date);
		data+='&group_id='+encodeURIComponent($scope.student.group_id);
		$http.post($scope.ajax_url+'add',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.student.admission_number='';
			$scope.student.name='';$scope.student.nic='';$scope.student.fee='';$scope.student.date_of_birth='';$scope.student.medical_problem='';$scope.student.emergency_contact='';
			$scope.student.guardian_name='';$scope.student.guardian_mobile='';$scope.student.father_name='';$scope.student.father_nic='';$scope.student.father_occupation='';
			$scope.student.mother_name='';$scope.student.mother_nic='';$scope.student.address='';$scope.student.religion='';$scope.student.cast='';$scope.student.roll_no='';
			$scope.student.other_info='';$scope.student.admission_fee='';$scope.student.mobile='';
			$scope.student.first_installment='';$scope.student.installments='';$scope.student.installment='';
			$scope.student.security_fee='';$scope.student.annual_fund='';$scope.student.prospectus='';
			$scope.student.create_voucher='';$scope.student.create_fee='';$scope.student.is_installments='';
			$scope.student.due_date='';$scope.student.frequency='';$scope.student.force_inspkg='';
			$scope.student.pkg_obt_marks='';$scope.student.pkg_total_marks='';
			$scope.loadRows();
			$scope.resetSelect();
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
        }else if($scope.selectedRow.guardian_name.length< 1){
		swal({title:"Please enter guardian name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.guardian_mobile.length< 1){
		swal({title:"Please enter guardian mobile number",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.class_id< 1){
		swal({title:"Please select class",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.selectedRow.session_id< 1){
		swal({title:"Please choose a valid session",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&name='+encodeURIComponent($scope.selectedRow.name)+'&nic='+encodeURIComponent($scope.selectedRow.nic)+'&mobile='+encodeURIComponent($scope.selectedRow.mobile);
		data+='&fee='+encodeURIComponent($scope.selectedRow.fee)+'&fee_type='+encodeURIComponent($scope.selectedRow.fee_type)+'&guardian_name='+encodeURIComponent($scope.selectedRow.guardian_name);
		data+='&guardian_mobile='+encodeURIComponent($scope.selectedRow.guardian_mobile)+'&gender='+encodeURIComponent($scope.selectedRow.gender)+'&blood_group='+encodeURIComponent($scope.selectedRow.blood_group);
		data+='&date_of_birth='+encodeURIComponent($scope.selectedRow.date_of_birth)+'&medical_problem='+encodeURIComponent($scope.selectedRow.medical_problem)+'&emergency_contact='+encodeURIComponent($scope.selectedRow.father_occupation);
		data+='&father_name='+encodeURIComponent($scope.selectedRow.father_name)+'&father_nic='+encodeURIComponent($scope.selectedRow.father_nic)+'&father_occupation='+encodeURIComponent($scope.selectedRow.anual_increment);
		data+='&mother_name='+encodeURIComponent($scope.selectedRow.mother_name)+'&mother_nic='+encodeURIComponent($scope.selectedRow.mother_nic)+'&address='+encodeURIComponent($scope.selectedRow.address);
		data+='&religion='+encodeURIComponent($scope.selectedRow.religion)+'&cast='+encodeURIComponent($scope.selectedRow.cast)+'&class_id='+encodeURIComponent($scope.selectedRow.class_id);
		data+='&session_id='+encodeURIComponent($scope.selectedRow.session_id)+'&roll_no='+encodeURIComponent($scope.selectedRow.roll_no)+'&other_info='+encodeURIComponent($scope.selectedRow.other_info);
		data+='&section='+encodeURIComponent($scope.selectedRow.section)+'&date='+encodeURIComponent($scope.selectedRow.date);
		data+='&family_number='+encodeURIComponent($scope.selectedRow.family_number)+'&computer_number='+encodeURIComponent($scope.selectedRow.computer_number);
		data+='&admission_no='+encodeURIComponent($scope.selectedRow.admission_no);
		data+='&section_id='+encodeURIComponent($scope.selectedRow.section_id);
		data+='&group_id='+encodeURIComponent($scope.selectedRow.group_id);

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


	$scope.createFeeRow=function (){	
    	if($scope.entry.name.length< 1){
		swal({title:"Please enter voucher name",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.entry.title.length< 1){
		swal({title:"Please enter record title",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.entry.amount.length< 1){
		swal({title:"Please provide amount",type:'info',showConfirmButton:false,timer:2000});
        }else if($scope.entry.due_date.length< 1){
		swal({title:"Please select due date of voucher",type:'info',showConfirmButton:false,timer:2000});
        }else if(config.btnClickedSave===true){
		swal({title:"Please wait for existing command.",type:'info',showConfirmButton:false,timer:2000});
        }else{
		$scope.disableButtons();
		var data='rid='+$scope.selectedRow.mid+'&name='+encodeURIComponent($scope.entry.name)+'&title='+encodeURIComponent($scope.entry.title);
		data+='&amount='+encodeURIComponent($scope.entry.amount)+'&due_date='+encodeURIComponent($scope.entry.due_date);
		$http.post($scope.ajax_url+'createFeeVoucher',data,config.postConfig).then(function(response){
			$scope.enableButtons();
			var msg=response.data.message;
			if(response.data.error === true){
			swal({title:msg,type:'error',showConfirmButton:false,timer:2500});
			$log.error(response);
			}else{
			swal({title:msg,type:'success',showConfirmButton:false,timer:2000});
			$scope.entry.name='';$scope.entry.title='';$scope.entry.amount='';$scope.entry.due_date='';
			// $scope.loadRows();
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
		var data={'message':$scope.message,'target':$scope.filter.target,'status':$scope.filter.status,'class_id':$scope.filter.class,'session_id':$scope.filter.session,'fee':$scope.filter.fee,'gender':$scope.filter.gender,'blood_group':$scope.filter.blood_group};
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
		var data={'message':$scope.message,'target':$scope.filter.target,'rid':$scope.selectedRow.mid};
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

    $scope.loadClassSections=function (){
		var data={'class_id':$scope.student.class_id};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterClassSections',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.classSections=response.data.rows;
		},function(response){	
			$scope.enableButtons();		
            $log.error(response);
		});    
    };	
    $scope.loadClassFeePackage=function (){
		var data={'class_id':$scope.student.class_id,'pkg_obt_marks':$scope.student.pkg_obt_marks,'pkg_total_marks':$scope.student.pkg_total_marks};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterClassFeePackage',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.classFeePackage=response.data.result;
			if($scope.classFeePackage.policy.name.length>0){
			$scope.student.pkg_installment = ($scope.classFeePackage.policy.amount-$scope.student.first_installment)/$scope.student.installments;

			}
    	
		},function(response){	
			$scope.enableButtons();		
            $log.error(response);
		});    
    };	
    $scope.loadClassFee=function (){
		var data={'class_id':$scope.student.class_id};
		config.postConfig.params=data;
		$scope.disableButtons();
		$http.get($scope.ajax_url+'filterClassFee',config.postConfig).then(function(response){
			$scope.enableButtons();
			$scope.student.fee=response.data.fee;
		},function(response){	
			$scope.enableButtons();		
            $log.error(response);
		});    
    };	
	
	/////////////////////////////////////////////
	$('#add').on('shown.bs.modal', function () {config.enter='add';});
	$('#edit').on('shown.bs.modal', function () {config.enter='update';});
	$('#add').on('hidden.bs.modal', function () {config.enter='search';});
	$('#edit').on('hidden.bs.modal', function () {config.enter='search';});
	///////autoload functions////////////////////
	$scope.loadRows();
	/* $timeout(function(){$scope.loadRows();},1000); */


});