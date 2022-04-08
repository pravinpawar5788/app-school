<?php
$roles=$this->stf_role_m->get_rows(array('status'=>$this->stf_role_m->STATUS_ACTIVE),array('orderby'=>'title ASC') );
$awards=$this->award_m->get_rows(array());
$disciplines=$this->punishment_m->get_rows(array());

?>
<!-- Main content -->
<div class="content-wrapper" ng-controller="mozzCtrl"  ng-cloak>
	<!-- Page header -->
	<div class="page-header page-header-light border-bottom-0" ng-init="usrid='<?php print $member->mid;?>'">

		<!-- Top breadcrumb line -->
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
					<a class="breadcrumb-item">Staff</a>
					<span class="breadcrumb-item active">Profile</span>
				</div>

				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="breadcrumb justify-content-center">
					<a href="#" class="breadcrumb-elements-item sidebar-control sidebar-component-toggle d-none d-md-block">
						<i class="icon-drag-right mr-2"></i>Toggle Profile
					</a>

					<div class="breadcrumb-elements-item dropdown p-0  d-none d-md-block">						
					<a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
						<i class="icon-printer mr-2"></i>Print
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a href="<?php print $this->CONT_ROOT.'printing/profile/?usr='.$member->mid;?>" class="dropdown-item"><i class="icon-vcard"></i> Profile</a>
						<a href="<?php print $this->CONT_ROOT.'printing/allowances/?usr='.$member->mid;?>" class="dropdown-item"><i class="icon-cash"></i> Allowances List</a>
						<a href="<?php print $this->CONT_ROOT.'printing/deductions/?usr='.$member->mid;?>" class="dropdown-item"><i class="icon-cash"></i> Loan History</a>
						<!-- <div class="dropdown-divider"></div> -->
					</div>
					</div>

				</div>
			</div>
		</div>
		<!-- /breadcrumb line -->

	
		<!-- Page header content -->
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Staff</span> - Profile</h4>
				<a class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="d-flex justify-content-center">
					<!-- <a href="#" class="btn btn-link btn-float text-default"><i class="icon-calculator text-success"></i><span class="text-success">Payroll</span></a> -->
					<!-- <a href="#" class="btn btn-link btn-float text-default"><i class="icon-bars-alt text-primary"></i><span>Statistics</span></a> -->
				</div>
			</div>
		</div>
		<!-- /page header content -->


		<!-- Profile navigation -->
		<div class="navbar navbar-expand-lg navbar-light bg-light">
			<div class="text-center d-lg-none w-100">
				<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-second">
					<i class="icon-menu7 mr-2"></i>
					Profile navigation
				</button>
			</div>

			<div class="navbar-collapse collapse" id="navbar-second">
				<ul class="nav navbar-nav">
					<li class="nav-item">
						<a href="#profile" class="navbar-nav-link active" data-toggle="tab" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-profile mr-2" style="color:<?php print $clr;?>;"></i>
							Profile
						</a>
					</li>
					<li class="nav-item">
						<a href="#allowances" class="navbar-nav-link" data-toggle="tab" ng-click="loadAllowances()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-cash mr-2" style="color:<?php print $clr;?>;"></i>
							Allowances
						</a>
					</li>
					<li class="nav-item">
						<a href="#deductions" class="navbar-nav-link" data-toggle="tab" ng-click="loadDeductions()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-briefcase mr-2" style="color:<?php print $clr;?>;"></i>
							Loans
						</a>
					</li>
					<li class="nav-item">
						<a href="#accounts" class="navbar-nav-link" data-toggle="tab" ng-click="loadSalaryHistory()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-cash mr-2" style="color:<?php print $clr;?>;"></i>
							Accounts
						</a>
					</li>
					<li class="nav-item">
						<a href="#history" class="navbar-nav-link" data-toggle="tab" ng-click="loadHistory()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-history mr-2" style="color:<?php print $clr;?>;"></i>
							History
						</a>
					</li>
					<li class="nav-item">
						<a href="#academics" class="navbar-nav-link" data-toggle="tab" ng-click="loadAcademic()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-graduation2 mr-2" style="color:<?php print $clr;?>;"></i>
							Academics
						</a>
					</li>
					<li class="nav-item">
						<a href="<?php print $this->LIB_CONT_ROOT.'staff';?>" class="navbar-nav-link" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-arrow-left52 mr-2" style="color:<?php print $clr;?>;"></i>
							Go Back
						</a>
					</li> 

				</ul>

				<ul class="nav navbar-nav ml-lg-auto">
					<!-- li class="nav-item">
						<a href="#" class="navbar-nav-link">
							<i class="icon-stack-text mr-2"></i>
							Notes
						</a>
					</li> -->
				</ul>
			</div>
		</div>
		<!-- /profile navigation -->

	</div>
	<!-- /page header -->


	<!-- Content area -->
	<div class="content">
		<?php $this->load->view($this->LIB_VIEW_DIR.'includes/alert_inc');?>
		<!-- Inner container -->
		<div class="d-flex align-items-start flex-column flex-md-row">

			<!-- Left content -->
			<div class="tab-content w-100 overflow-auto order-2 order-md-1">


			    <div class="tab-pane fade <?php print empty($tab) || $tab=='profile' ? 'active show': '';?>" id="profile">
					<!-- Profile info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Profile information</h5>
							<p class="text-muted">Profile infortmation of this member</p>
						</div>

						<div class="card-body">
							<div class="form-group">
							<div class="row">
								<div class="col-md-4">
									<label class="text-muted">Staff ID</label>
									<input type="text" value="<?php print $member->staff_id;?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Full Name</label>
									<input type="text" value="<?php print ucwords(strtolower($member->name));?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Father/Husband Name</label>
									<input type="text" value="<?php print ucwords(strtolower($member->guardian_name));?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Mobile</label>
									<input type="text" value="<?php print $member->mobile;?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">National ID</label>
									<input type="text" value="<?php print $member->cnic;?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Email Address</label>
									<input type="text" value="<?php print strtolower($member->email);?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Gender</label>
									<input type="text" value="<?php print ucwords(strtolower($member->gender));?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Blood Group</label>
									<input type="text" value="<?php print strtoupper($member->blood_group);?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Designation</label>
									<input type="text" value="<?php print $this->stf_role_m->get_by_primary($member->role_id)->title;?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Basic Salary</label>
									<input type="text" value="<?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$member->salary;?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Anual Increment (As Per Contract)</label>
									<input type="text" value="<?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$member->anual_increment;?>" class="form-control" readonly="readonly" >
								</div>
							</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-6">
										<label class="text-muted">Home Address</label>
										<textarea class="form-control" readonly="readonly"><?php print $member->home_address;?></textarea>
									</div>
									<div class="col-md-6">
										<label class="text-muted">Qualification</label>
										<textarea class="form-control" readonly="readonly"><?php print $member->qualification;?></textarea>
									</div>
									<div class="col-md-6">
										<label class="text-muted">Experience</label>
										<textarea class="form-control" readonly="readonly"><?php print $member->experience;?></textarea>
									</div>
									<div class="col-md-6">
										<label class="text-muted">Favourite Subjects</label>
										<textarea class="form-control" readonly="readonly"><?php print $member->favourite_subject;?></textarea>
									</div>
									<div class="col-md-6">
										<label class="text-muted">Contract</label>
										<textarea class="form-control" readonly="readonly"><?php print $member->contract;?></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- /profile info -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='allowances' ? 'active show': '';?>" id="allowances">

					<?php if($this->LOGIN_USER->prm_stf_info>1 && $member->status==$this->staff_m->STATUS_ACTIVE){?>
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Monthly Allowances
							<span class="d-block font-size-base text-muted">Monthly allowances helps you pay the allowances to staff along with basic salary. All the allowances you register here will automatically add to staff pay roll while generating the monthly pay roll.</span>
							</h5>
						</div>

						<div class="card-body">
							<div class="form-group">
							<div class="row">
								<div class="col-md-4">
									<label class="text-muted">Title <span class="text-danger"> * </span></label>
									<input type="text" class="form-control" placeholder="E.g Residance Allowance" ng-model="allowance.title">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Amount <span class="text-danger"> * </span></label>
									<input type="text" class="form-control" placeholder="3000" ng-model="allowance.amount">
								</div>
								<div class="col-md-4">
									<button ng-click="saveAllowance()" class="btn btn-success btn-lg ml-2 mt-3">
										<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-checkmark-circle2':!appConfig.btnClickedSave}" class=" ml-2"></i>
									</button>
								</div>
							</div>
							</div>

						</div>
					</div>
					<!-- /save info -->
					<?php } ?>
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Allowances</h4>
							<span class="text-muted">Following allowances will be added to this staff montthly payroll automatically.</span>
						</div>
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadAllowances()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadAllowances()" class="btn btn-success btn-lg">
									<span class="font-weight-bold"> Search</span>
									<i ng-class="{'icon-spinner2':appConfig.btnClickedSearch, 'spinner':appConfig.btnClickedSearch, 'icon-circle-right2':!appConfig.btnClickedSearch}" class=" ml-2"></i>
									</button>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th ng-click="sortBy('title');loadAllowances();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Title</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
										</th>
										<th ng-click="sortBy('amount');loadAllowances();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Amount</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='amount'"></i> 
										</th>
										<th ng-click="sortBy('date');loadAllowances();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Registered On</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='date'"></i> 
										</th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td>
					  					<td><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]?>{{row.amount}}</td>  
					  					<td>{{row.date}}</td> 
										<td class="text-center">
											<div class="list-icons">
												<div class="list-icons-item dropdown">
													<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9 mr-5"></i></a>
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item" ng-click="selectAllowance(row)">
															<i class="icon-compose"></i> Update
														</a>
														<div class="dropdown-divider"></div>
														<?php if($this->LOGIN_USER->prm_stf_info>2){?>
														<a class="dropdown-item" ng-click="delAllowance(row)"><i class="icon-cross"></i> Terminate Allowance</a>
														<?php }?>
													</div>
												</li>
											</div>
										</td>
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadAllowances()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadAllowances()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='deductions' ? 'active show': '';?>" id="deductions">
					<?php if($this->LOGIN_USER->prm_stf_info>1 && $member->status==$this->staff_m->STATUS_ACTIVE){?>
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Issue Loan
							<span class="d-block font-size-base text-muted">Loan helps you issue loan to this staff member. You can set the number of installments to recover back the amount via deduction from staff salary. System automatically deduct this amount from staff salary while generating the monthly payroll.</span>
							</h5>
						</div>

						<div class="card-body">
							<div class="form-group">
							<div class="row">
								<div class="col-md-4">
									<label class="text-muted">Reason <span class="text-danger"> * </span></label>
									<input type="text" class="form-control" placeholder="E.g Emergency Loan" ng-model="deduction.title">
								</div>
								<div class="col-md-3">
									<label class="text-muted">Amount <span class="text-danger"> * </span></label>
									<input type="text" class="form-control" placeholder="3000" ng-model="deduction.amount">
								</div>
								<div class="col-md-3">
									<label class="text-muted">Installments <span class="text-danger"> * </span></label>
									<input type="number" class="form-control" placeholder="3" ng-model="deduction.duration">
								</div>
								<div class="col-md-2">
									<button ng-click="saveDeduction()" class="btn btn-success btn-lg ml-2 mt-3">
										<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-checkmark-circle2':!appConfig.btnClickedSave}" class=" ml-2"></i>
									</button>
								</div>
							</div>
							</div>

						</div>
					</div>
					<!-- /save info -->
					<?php } ?>
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Recovery Installments</h4>
							<span class="text-muted">Following are remaining installments of this member. </span>
						</div>
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadDeductions()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadDeductions()" class="btn btn-success btn-lg">
									<span class="font-weight-bold"> Search</span>
									<i ng-class="{'icon-spinner2':appConfig.btnClickedSearch, 'spinner':appConfig.btnClickedSearch, 'icon-circle-right2':!appConfig.btnClickedSearch}" class=" ml-2"></i>
									</button>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th ng-click="sortBy('title');loadDeductions();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Title</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
										</th>
										<th ng-click="sortBy('amount');loadDeductions();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Amount</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='amount'"></i> 
										</th>
										<th ng-click="sortBy('month');loadDeductions();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Installment</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='month'"></i> 
										</th>
										<!-- <th ng-click="sortBy('year');loadDeductions();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Recovery Year</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='year'"></i> 
										</th> -->
										<th ng-click="sortBy('date');loadDeductions();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Issued On</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='date'"></i> 
										</th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td>
					  					<td><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]?>{{row.amount}}</td>  
					  					<td>{{row.installment}}</td> 
					  					<!-- <td>{{row.year}}</td>  -->
					  					<td>{{row.date}}</td> 
										<td class="text-center">
											<div class="list-icons">
												<div class="list-icons-item dropdown">
													<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9 mr-5"></i></a>
													<div class="dropdown-menu dropdown-menu-right">
														<!-- <a class="dropdown-item" ng-click="selectAllowance(row)">
															<i class="icon-compose"></i> Update
														</a>
														<div class="dropdown-divider"></div> -->
														<?php if($this->LOGIN_USER->prm_stf_info>2){?>
														<a class="dropdown-item" ng-click="delDeduction(row)"><i class="icon-cross"></i> Terminate Installment</a>
														<?php }?>
													</div>
												</li>
											</div>
										</td>
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadDeductions()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadDeductions()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='accounts' ? 'active show': '';?>" id="accounts">
					<?php if($this->LOGIN_USER->prm_stf_info>1 && $member->status==$this->staff_m->STATUS_ACTIVE){?>
					<!-- save info -->
					<!-- <div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Staff Accounts
							<span class="d-block font-size-base text-muted">Staff accounts helps you manage advance salary for this member. You may recover the salary in upcoming months. 
							</span>
							</h5>
						</div>

						<div class="card-body">
							<div class="form-group">
							<div class="row">
								<div class="col-md-4">
									<label class="text-muted">Narration<span class="text-danger"> *</span></label>
									<input type="text" class="form-control" placeholder="E.g Advance amount" ng-model="advance.title">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Amount <span class="text-danger"> * </span></label>
									<input type="text" class="form-control" placeholder="amount given 3000" ng-model="advance.amount">
								</div>
								<div class="col-md-4">
									<button ng-click="saveAdvance()" class="btn btn-success btn-lg ml-2 mt-3">
										<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-checkmark-circle2':!appConfig.btnClickedSave}" class="ml-2"></i>
									</button>
								</div>
							</div>
							</div>

						</div>
					</div> -->
					<!-- /save info -->
					<?php } ?>
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Salary Payment Details</h4>
							<span class="text-muted">Following are the salary history for this staff member.</span>
						</div>
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadSalaryHistory()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadSalaryHistory()" class="btn btn-success btn-lg">
									<span class="font-weight-bold"> Search</span>
									<i ng-class="{'icon-spinner2':appConfig.btnClickedSearch, 'spinner':appConfig.btnClickedSearch, 'icon-circle-right2':!appConfig.btnClickedSearch}" class=" ml-2"></i>
									</button>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th ng-click="sortBy('title');loadSalaryHistory();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Title</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
										</th>
										<th ng-click="sortBy('amount');loadSalaryHistory();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Amount</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='amount'"></i> 
										</th>
										<th ng-click="sortBy('date');loadSalaryHistory();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Date</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='date'"></i> 
										</th>
										<!-- <th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th> -->
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td>
					  					<td><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]?>{{row.amount}}</td>  
					  					<td>{{row.date}}</td> 
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadSalaryHistory()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadSalaryHistory()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='history' ? 'active show': '';?>" id="history">
					
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">History</h4>
							<span class="text-muted">System tracks and save all the important events regarding this staff member for record purpose. You may go through the history to analyze the member behaviour.</span>
						</div>
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadHistory()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadHistory()" class="btn btn-success btn-lg">
									<span class="font-weight-bold"> Search</span>
									<i ng-class="{'icon-spinner2':appConfig.btnClickedSearch, 'spinner':appConfig.btnClickedSearch, 'icon-circle-right2':!appConfig.btnClickedSearch}" class=" ml-2"></i>
									</button>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th ng-click="sortBy('title');loadHistory();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Title</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
										</th>
										<th ng-click="sortBy('description');loadHistory();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Description</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='description'"></i> 
										</th>
										<th ng-click="sortBy('date');loadHistory();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Date</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='date'"></i> 
										</th>
										<!-- <th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th> -->
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td>
					  					<td>{{row.description}}</td>  
					  					<td>{{row.date}}</td> 
										<!-- <td class="text-center">
											<div class="list-icons">
												<div class="list-icons-item dropdown">
													<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9 mr-5"></i></a>
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item" ng-click="selectAllowance(row)">
															<i class="icon-compose"></i> Update
														</a>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" ng-click="delAllowance(row)"><i class="icon-cross"></i> Terminate Allowance</a>
													</div>
												</li>
											</div>
										</td> -->
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadHistory()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadHistory()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>





			    <div class="tab-pane fade <?php print $tab=='academics' ? 'active show': '';?>" id="academics">
					<?php if($this->LOGIN_USER->prm_stf_info>1){?>
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Academics 
							<span class="d-block font-size-base text-muted">Academics module help you keep record of this member qualification. All these academics will printed on staff profile while printing or other places.
							</span>
							</h5>
						</div>

						<div class="card-body">
							<div class="form-group">
							<div class="row">
								<div class="col-md-4">
									<label class="text-muted">Qualification<span class="text-danger"> *</span></label>
									<input type="text" class="form-control" placeholder="E.g Qualification" ng-model="academic.qualification">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Institute<span class="text-danger"> *</span></label>
									<input type="text" class="form-control" placeholder="E.g Institute" ng-model="academic.institute">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Program</label>
									<input type="text" class="form-control" placeholder="E.g Y year Honour" ng-model="academic.program">
								</div>
								<div class="col-md-3">
									<label class="text-muted">Registration Number</label>
									<input type="text" class="form-control" placeholder="E.g Roll Number" ng-model="academic.registration_no">
								</div>
								<div class="col-md-3">
									<label class="text-muted">Roll Number</label>
									<input type="text" class="form-control" placeholder="E.g Roll Number" ng-model="academic.roll_number">
								</div>
								<div class="col-md-3">
									<label class="text-muted">Year <span class="text-danger"> * </span></label>
									<input type="year" class="form-control" placeholder="Passing Year" ng-model="academic.year">
								</div>
								<div class="col-md-3">
									<button ng-click="saveAcademic()" class="btn btn-success btn-lg ml-2 mt-3">
										<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-checkmark-circle2':!appConfig.btnClickedSave}" class="ml-2"></i>
									</button>
								</div>
							</div>
							</div>

						</div>
					</div>
					<!-- /save info -->
					<?php } ?>
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Registered Qualification</h4>
							<span class="text-muted">Academics module help you keep record of this member qualification. All these academics will printed on staff profile while printing or other places. </span>
						</div>
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadAcademic()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadAcademic()" class="btn btn-success btn-lg">
									<span class="font-weight-bold"> Search</span>
									<i ng-class="{'icon-spinner2':appConfig.btnClickedSearch, 'spinner':appConfig.btnClickedSearch, 'icon-circle-right2':!appConfig.btnClickedSearch}" class=" ml-2"></i>
									</button>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th ng-click="sortBy('qualification');loadAcademic();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Qualification</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='qualification'"></i> 
										</th>
										<th ng-click="sortBy('year');loadAcademic();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Passing Year</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='year'"></i> 
										</th>
										<th ng-click="sortBy('program');loadAcademic();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Progream</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='program'"></i> 
										</th>
										<th ng-click="sortBy('institute');loadHistory();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Institute</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='institute'"></i> 
										</th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.qualification}}</td>
					  					<td>{{row.year}}</td>  
					  					<td>{{row.program}}</td> 
					  					<td>{{row.institute}}</td> 
										<td class="text-center">
											<div class="list-icons">
												<div class="list-icons-item dropdown">
													<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9 mr-5"></i></a>
													<div class="dropdown-menu dropdown-menu-right">
														<?php if($this->LOGIN_USER->prm_stf_info>1){?>
														<a class="dropdown-item" ng-click="selectAcademic(row)">
															<i class="icon-compose"></i> Update
														</a>
														<div class="dropdown-divider"></div>
														<?php } ?>
														<?php if($this->LOGIN_USER->prm_stf_info>2){?>
														<a class="dropdown-item" ng-click="delAcademic(row)"><i class="icon-cross"></i> Remove </a>
														<?php }?>
													</div>
												</li>
											</div>
										</td>
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadAcademic()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadAcademic()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='awards' ? 'active show': '';?>" id="awards">
					<?php if($this->LOGIN_USER->prm_stf_info>1 && $member->status==$this->staff_m->STATUS_ACTIVE){?>
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Assign Award 
							<span class="d-block font-size-base text-muted">Aawrds could be assigned to staff on good performance. This module helps you keep a soft record for award history for this module. Later it will be displayed on staff profress report or profile.
							</span>
							</h5>
						</div>

						<div class="card-body">
							<div class="form-group">
							<div class="row">
								<div class="col-md-4">
									<label class="text-muted">Choose Award (Create New Award in Documents)<span class="text-danger"> * </span></label>
									<select class="form-control select" ng-model="award.awardId" data-fouc>
									<option value="">Select Award</option>
									<?php foreach($awards as $row){?>
									<option value="<?php print $row['mid'];?>"><?php print $row['title'];?></option>
									<?php }?>
									</select>
								</div>
								<div class="col-md-5">
									<label class="text-muted">Remarks<span class="text-danger"> *</span></label>
									<textarea class="form-control" placeholder="E.g Remarks" ng-model="award.remarks"></textarea>
								</div><!-- 
								<div class="col-md-4">
									<label class="text-muted">Institute<span class="text-danger"> *</span></label>
									<input type="text" class="form-control" placeholder="E.g Institute" ng-model="academic.institute">
								</div> -->
								<div class="col-md-3">
									<button ng-click="saveAward()" class="btn btn-success btn-lg ml-2 mt-3">
										<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-checkmark-circle2':!appConfig.btnClickedSave}" class="ml-2"></i>
									</button>
								</div>
							</div>
							</div>

						</div>
					</div>
					<!-- /save info -->
					<?php } ?>
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Assigned Awards</h4>
							<span class="text-muted">Following are the awards catched by this member during his/her jon in this organization. Later, you may provide a hard copy of this award. </span>
						</div>
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadAwards()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadAwards()" class="btn btn-success btn-lg">
									<span class="font-weight-bold"> Search</span>
									<i ng-class="{'icon-spinner2':appConfig.btnClickedSearch, 'spinner':appConfig.btnClickedSearch, 'icon-circle-right2':!appConfig.btnClickedSearch}" class=" ml-2"></i>
									</button>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th ng-click="sortBy('title');loadAwards();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Award</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
										</th>
										<th ng-click="sortBy('remarks');loadAwards();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Remarks</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='remarks'"></i> 
										</th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td>
					  					<td>{{row.remarks}}</td>  
										<td class="text-center">
											<div class="list-icons">
												<div class="list-icons-item dropdown">
													<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9 mr-5"></i></a>
													<div class="dropdown-menu dropdown-menu-right">
														<!-- <a class="dropdown-item" ng-click="selectAward(row)">
															<i class="icon-compose"></i> Update
														</a>
														<div class="dropdown-divider"></div> -->
														<?php if($this->LOGIN_USER->prm_stf_info>2){?>
														<a class="dropdown-item" ng-click="delAward(row)"><i class="icon-cross"></i> Remove </a>
														<?php }?>
													</div>
												</li>
											</div>
										</td>
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadAwards()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadAwards()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='achievements' ? 'active show': '';?>" id="achievements">
					<?php if($this->LOGIN_USER->prm_stf_info>1 && $member->status==$this->staff_m->STATUS_ACTIVE){?>
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Endorsements 
							<span class="d-block font-size-base text-muted">Endorsements help you give feedback about this staff member for his/her performance randomly. This help analyze overall improvement of staff member during his/her job. Later it can be displayed on staff progress report or profile.
							</span>
							</h5>
						</div>

						<div class="card-body">
							<div class="form-group">
							<div class="row">
								<div class="col-md-4">
									<label class="text-muted">Title / Badge <span class="text-danger"> *</span></label>
									<input type="text" class="form-control" placeholder="E.g Institute" ng-model="achievement.title">
								</div>
								<div class="col-md-5">
									<label class="text-muted">Remarks<span class="text-danger"> *</span></label>
									<textarea class="form-control" placeholder="E.g Remarks" ng-model="achievement.remarks"></textarea>
								</div>
								<div class="col-md-3">
									<button ng-click="saveAchievement()" class="btn btn-success btn-lg ml-2 mt-3">
										<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-checkmark-circle2':!appConfig.btnClickedSave}" class="ml-2"></i>
									</button>
								</div>
							</div>
							</div>

						</div>
					</div>
					<!-- /save info -->
					<?php } ?>
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Past Endorsements</h4>
							<span class="text-muted">Following are the past endorsements of this member during his/her job in this organization.</span>
						</div>
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadAchievements()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadAchievements()" class="btn btn-success btn-lg">
									<span class="font-weight-bold"> Search</span>
									<i ng-class="{'icon-spinner2':appConfig.btnClickedSearch, 'spinner':appConfig.btnClickedSearch, 'icon-circle-right2':!appConfig.btnClickedSearch}" class=" ml-2"></i>
									</button>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th ng-click="sortBy('title');loadAchievements();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Title / Badge</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
										</th>
										<th ng-click="sortBy('remarks');loadAchievements();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Remarks</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='remarks'"></i> 
										</th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td>
					  					<td>{{row.remarks}}</td>  
										<td class="text-center">
											<div class="list-icons">
												<div class="list-icons-item dropdown">
													<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9 mr-5"></i></a>
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item" ng-click="selectAchievement(row)">
															<i class="icon-compose"></i> Update
														</a>
														<div class="dropdown-divider"></div>
														<?php if($this->LOGIN_USER->prm_stf_info>2){?>
														<a class="dropdown-item" ng-click="delAchievement(row)"><i class="icon-cross"></i> Remove </a>
														<?php }?>
													</div>
												</li>
											</div>
										</td>
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadAchievements()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadAchievements()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='discipline' ? 'active show': '';?>" id="discipline">
					<?php if($this->LOGIN_USER->prm_stf_info>1 && $member->status==$this->staff_m->STATUS_ACTIVE){?>
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Notic's 
							<span class="d-block font-size-base text-muted">Notice module helps record the indisciplinary behaviour of this member. You may issue a notice to this member. System will keep record of notices issued to this member. Later it can be displayed on progress report or profile.
							</span>
							</h5>
						</div>

						<div class="card-body">
							<div class="form-group">
							<div class="row">
								<div class="col-md-4">
									<label class="text-muted">Choose Notice (Create New Notice in Documents)<span class="text-danger"> * </span></label>
									<select class="form-control select" ng-model="discipline.disciplineId" data-fouc>
									<option value="">Select Notice</option>
									<?php foreach($disciplines as $row){?>
									<option value="<?php print $row['mid'];?>"><?php print $row['title'];?></option>
									<?php }?>
									</select>
								</div>
								<div class="col-md-5">
									<label class="text-muted">Remarks<span class="text-danger"> *</span></label>
									<textarea class="form-control" placeholder="E.g Remarks" ng-model="discipline.remarks"></textarea>
								</div><!-- 
								<div class="col-md-4">
									<label class="text-muted">Institute<span class="text-danger"> *</span></label>
									<input type="text" class="form-control" placeholder="E.g Institute" ng-model="academic.institute">
								</div> -->
								<div class="col-md-3">
									<button ng-click="saveDiscipline()" class="btn btn-success btn-lg ml-2 mt-3">
										<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-checkmark-circle2':!appConfig.btnClickedSave}" class="ml-2"></i>
									</button>
								</div>
							</div>
							</div>

						</div>
					</div>
					<!-- /save info -->
					<?php } ?>
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Issued Notices</h4>
							<span class="text-muted">Following are the notices issued to this member during his/her jon in this organization. Later, you may provide a hard copy of this notice. </span>
						</div>
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadDiscipline()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadDiscipline()" class="btn btn-success btn-lg">
									<span class="font-weight-bold"> Search</span>
									<i ng-class="{'icon-spinner2':appConfig.btnClickedSearch, 'spinner':appConfig.btnClickedSearch, 'icon-circle-right2':!appConfig.btnClickedSearch}" class=" ml-2"></i>
									</button>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th ng-click="sortBy('title');loadDiscipline();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Notice</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
										</th>
										<th ng-click="sortBy('remarks');loadDiscipline();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Remarks</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='remarks'"></i> 
										</th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td>
					  					<td>{{row.remarks}}</td>  
										<td class="text-center">
											<div class="list-icons">
												<div class="list-icons-item dropdown">
													<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9 mr-5"></i></a>
													<div class="dropdown-menu dropdown-menu-right">
														<!-- <a class="dropdown-item" ng-click="selectDiscipline(row)">
															<i class="icon-compose"></i> Update
														</a>
														<div class="dropdown-divider"></div> -->
														<?php if($this->LOGIN_USER->prm_stf_info>2){?>
														<a class="dropdown-item" ng-click="delDiscipline(row)"><i class="icon-cross"></i> Remove </a>
														<?php }?>
													</div>
												</li>
											</div>
										</td>
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadDiscipline()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadDiscipline()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>



			   
			</div>
			<!-- /left content -->


			<!-- Right sidebar component -->
			<div class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-right wmin-300 border-0 shadow-0 order-1 order-md-2 sidebar-expand-md">

				<!-- Sidebar content -->
				<div class="sidebar-content">

					<!-- User card -->
					<div class="card">
						<div class="card-body text-center">
							<div class="card-img-actions d-inline-block mb-3">
								<img class="img-fluid rounded-circle" src="<?php print $this->UPLOADS_ROOT.'images/staff/profile/'.$member->image;?>" width="170" height="170" alt="">
							</div>
								
							<?php if($this->LOGIN_USER->prm_stf_info>1){?>
							<?php echo form_open_multipart($this->CONT_ROOT.'upload_picture');?> 
							<input type="hidden" name="user" value="<?=$member->mid;?>" />
							<input id="clientImageFile" type="file" name="file" style="display: none;" onchange="showname()">
							<input type="button" value="Browse" class="btn btn-light btn-sm" onclick="document.getElementById('clientImageFile').click();" />
							<button class="btn btn-success btn-sm"><i class="icon-upload position-right"></i><strong> Upload </strong></button>   
							<?php echo form_close(); ?>
							<span class="text-muted" id="selected-file"></span>
							<?php } ?>
							<br>
				    		<h6 class="font-weight-semibold mb-0"><?php print ucwords(strtolower($member->name));?></h6>
				    		<span class="d-block text-muted"><?php print $this->stf_role_m->get_by_primary($member->role_id)->title;?></span>
				    		<span class="d-block text-muted">
				    			<span class="m-2">Mobile:</span><?php print $member->mobile;?>
				    		</span>
				    		<span class="d-block text-muted">
				    			<span class="m-2">Email:</span><?php print $member->email;?>
				    		</span>
				    		<span class="d-block text-muted">
				    			<span class="m-2">National ID:</span><?php print $member->cnic;?>
				    		</span>
				    		<span class="d-block text-muted">
				    			<span class="m-2">Basic Salary:</span><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$member->salary;?>
				    		</span>

				    	</div>
			    	</div>
			    	<!-- /user card -->

						

					<!-- Navigation -->
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="card-title font-weight-semibold">Sub Navigation</span>
						</div>

						<div class="card-body p-0">
							<ul class="nav nav-sidebar my-2">
								<li class="nav-item">
									<a href="#awards" class="nav-link" data-toggle="tab" ng-click="loadAwards()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
										<i class="icon-image2" style="color:<?php print $clr;?>;"></i>
										 Awards
										<span class="badge bg-success badge-pill ml-auto">{{member.total_awards}}</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#discipline" class="nav-link" data-toggle="tab" ng-click="loadDiscipline()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
										<i class="icon-image2" style="color:<?php print $clr;?>;"></i>
										 Notice's
										<span class="badge bg-danger badge-pill ml-auto">{{member.total_punishments}}</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#achievements" class="nav-link" data-toggle="tab" ng-click="loadAchievements()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
										<i class="icon-image2" style="color:<?php print $clr;?>;"></i>
										 Endorsements
										<span class="badge bg-info badge-pill ml-auto">{{member.total_acheivements}}</span>
									</a>
								</li>
								<!-- <li class="nav-item">
									<a href="#awards" class="nav-link" data-toggle="tab" ng-click="loadAttendance()">
										<i class="icon-user"></i>
										 Attendance
									</a>
								</li> -->

								<!-- <li class="nav-item-divider"></li> -->
								<!-- 
								<li class="nav-item">
									<a href="#" class="nav-link">
										<i class="icon-calendar3"></i>
										Events
										<span class="badge bg-teal-400 badge-pill ml-auto">48</span>
									</a>
								</li> -->
							</ul>
						</div>
					</div>
					<!-- /navigation -->


					<!-- Share your thoughts -->
					<!-- <div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="card-title font-weight-semibold">Share your thoughts</span>
							<div class="header-elements">
								<div class="list-icons">
			                		<a class="list-icons-item" data-action="collapse"></a>
		                		</div>
	                		</div>
						</div>

						<div class="card-body">
							<form action="#">
		                    	<textarea name="enter-message" class="form-control mb-3" rows="3" cols="1" placeholder="Enter your message..."></textarea>

		                    	<div class="d-flex align-items-center">
		                    		<div class="list-icons list-icons-extended">
		                                <a href="#" class="list-icons-item" data-popup="tooltip" title="Add photo" data-container="body"><i class="icon-images2"></i></a>
		                            	<a href="#" class="list-icons-item" data-popup="tooltip" title="Add video" data-container="body"><i class="icon-film2"></i></a>
		                                <a href="#" class="list-icons-item" data-popup="tooltip" title="Add event" data-container="body"><i class="icon-calendar2"></i></a>
		                    		</div>

		                    		<button type="button" class="btn bg-blue btn-labeled btn-labeled-right ml-auto"><b><i class="icon-paperplane"></i></b> Share</button>
		                    	</div>
							</form>
						</div>
					</div> -->
					<!-- /share your thoughts -->


					<!-- Balance changes -->
					<!-- <div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="card-title font-weight-semibold">Balance changes</span>
							<div class="header-elements">
								<span><i class="icon-arrow-down22 text-danger"></i> <span class="font-weight-semibold">- 29.4%</span></span>
	                		</div>
						</div>

						<div class="card-body">
							<div class="chart-container">
								<div class="chart has-fixed-height" id="balance_statistics"></div>
							</div>
						</div>
					</div> -->
					<!-- /balance changes -->


					<!-- Latest connections -->
					<?php
					$history=$this->stf_history_m->get_rows(array('staff_id'=>$member->mid,),array('limit'=>15));
					if(count($history)>0){
					?>
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="card-title font-weight-semibold">History</span>
							<div class="header-elements">
								<span class="badge bg-success badge-pill">Latest</span>
	                		</div>
						</div>

						<ul class="media-list media-list-linked my-2">
							<?php foreach($history as $row){?>
							<li>
								<a class="media">
									<div class="media-body">
										<div class="media-title font-weight-semibold"><?php print $row['title'];?></div>
										<span class="text-muted font-size-sm"><?php print $row['description'];?></span>
										<span class="text-muted font-size-sm"><br><?php print $row['date'];?></span>
									</div>
								</a>
							</li>
							<?php }?>
						</ul>
					</div>
					<?php }?>
					<!-- /latest connections -->

				</div>
				<!-- /sidebar content -->

			</div>
			<!-- /right sidebar component -->

		</div>
		<!-- /inner container -->

	</div>
	<!-- /content area -->

<!-- Footer -->
<?php
$this->load->view($LIB_VIEW_DIR.'includes/footer_inc');
?>
<!-- /footer -->


<!-- ********************************************************************** -->
<!-- ///////////////////////////////MODALS///////////////////////////////// -->
<!-- ********************************************************************** -->



</div>
<!-- /main content -->
<!-- ********************************************************************** -->
<!-- ///////////////////////////////SCRIPTS//////////////////////////////// -->
<!-- ********************************************************************** -->
<!-- <script type="text/javascript">
	
$(function(){
   
});
</script> -->