<?php
$sessions=$this->session_m->get_rows(array(),array('orderby'=>'status ASC,mid DESC') );
$classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'title ASC') );
$classes_array=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
$sessions_array=$this->session_m->get_values_array('mid','title',array());
$awards=$this->award_m->get_rows(array());
$disciplines=$this->punishment_m->get_rows(array());
$concessions=$this->concession_type_m->get_rows(array());

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
					<a class="breadcrumb-item">Student</a>
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
						<!-- <a href="<?php print $this->CONT_ROOT.'printing/attendance/?usr='.$member->mid;?>" class="dropdown-item"><i class="icon-cash"></i> Attendance Report</a> -->
						<a href="<?php print $this->CONT_ROOT.'printing/history/?usr='.$member->mid;?>" class="dropdown-item"><i class="icon-history"></i> History</a>
						<a href="<?php print $this->CONT_ROOT.'printing/feehistory/?usr='.$member->mid;?>" class="dropdown-item"><i class="icon-cash"></i>Fee History</a>
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
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Student</span> - Profile</h4>
				<a class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="d-flex justify-content-center">
					<!-- <a href="#" class="btn btn-link btn-float text-default"><i class="icon-calculator text-success"></i><span class="text-success">Feeroll</span></a> -->
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
						<a href="#advance" class="navbar-nav-link" data-toggle="tab" ng-click="loadAdvance()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-credit-card mr-2" style="color:<?php print $clr;?>;"></i>
							Advance Fee
						</a>
					</li>
					<li class="nav-item">
						<a href="#accounts" class="navbar-nav-link" data-toggle="tab" ng-click="loadVouchers()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-cash mr-2" style="color:<?php print $clr;?>;"></i>
							Account
						</a>
					</li>
					<li class="nav-item">
						<a href="#feehistory" class="navbar-nav-link" data-toggle="tab" ng-click="loadFeeHistory()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-history mr-2" style="color:<?php print $clr;?>;"></i>
							Fee History
						</a>
					</li>
					<li class="nav-item">
						<a href="#home-work" class="navbar-nav-link" data-toggle="tab" ng-click="loadHomework()">
							<i class="icon-home mr-2"></i>
							Home Work
						</a>
					</li>
					<li class="nav-item">
						<a href="#attendance" class="navbar-nav-link" data-toggle="tab" ng-click="loadAttendance()">
							<i class="icon-calendar3 mr-2"></i>
							Attendance
						</a>
					</li>
					<li class="nav-item">
						<a href="#tests" class="navbar-nav-link" data-toggle="tab" ng-click="loadTests()">
							<i class="icon-stats-bars2 mr-2"></i>
							Tests Report
						</a>
					</li>
					<li class="nav-item">
						<a href="<?php print $this->LIB_CONT_ROOT.'student';?>" class="navbar-nav-link" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
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
							<p class="text-muted">Profile infortmation of this student</p>
						</div>

						<div class="card-body">
							<div class="form-group">
							<div class="row">
								<div class="col-md-4">
									<label class="text-muted">Admission Number</label>
									<input type="text" value="<?php print $member->admission_no;?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Computer Number</label>
									<input type="text" value="<?php print $member->computer_number;?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Family Number</label>
									<input type="text" value="<?php print $member->family_number;?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted"> Computer ID / Student ID </label>
									<input type="text" value="<?php print $member->student_id;?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Full Name</label>
									<input type="text" value="<?php print ucwords(strtolower($member->name));?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Emergency Contact Number</label>
									<input type="text" value="<?php print ucwords(strtolower($member->emergency_contact));?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Guardian Name</label>
									<input type="text" value="<?php print ucwords(strtolower($member->guardian_name));?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Guardian Mobile</label>
									<input type="text" value="<?php print $member->guardian_mobile;?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">National ID / B-Form</label>
									<input type="text" value="<?php print $member->nic;?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Student Fee</label>
									<input type="text" value="<?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].strtolower($member->fee).' '.ucwords($member->fee_type);?>" class="form-control" readonly="readonly" >
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
									<label class="text-muted">Class</label>
									<input type="text" value="<?php print $member->class_id>0 ? $classes_array[$member->class_id] : '';?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Session</label>
									<input type="text" value="<?php print $sessions_array[$member->session_id];?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Date Of Birth</label>
									<input type="text" value="<?php print $member->date_of_birth;?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Student Mobile</label>
									<input type="text" value="<?php print $member->mobile;?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Religion</label>
									<input type="text" value="<?php print $member->religion;?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Father Name</label>
									<input type="text" value="<?php print ucwords(strtolower($member->father_name));?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Father NIC</label>
									<input type="text" value="<?php print $member->father_nic;?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Mother Name</label>
									<input type="text" value="<?php print ucwords(strtolower($member->mother_name));?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Mother NIC</label>
									<input type="text" value="<?php print $member->mother_nic;?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Admission Fee</label>
									<input type="text" value="<?php print $member->admission_fee;?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Security Fee</label>
									<input type="text" value="<?php print $member->security_fee;?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Annual Fund</label>
									<input type="text" value="<?php print $member->annual_fund;?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Admission Date</label>
									<input type="text" value="<?php print $member->date;?>" class="form-control" readonly="readonly" >
								</div>
							</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-6">
										<label class="text-muted">Father Occupation</label>
										<textarea class="form-control" readonly="readonly"><?php print $member->father_occupation;?></textarea>
									</div>
									<div class="col-md-6">
										<label class="text-muted">Address</label>
										<textarea class="form-control" readonly="readonly"><?php print $member->address;?></textarea>
									</div>
									<div class="col-md-6">
										<label class="text-muted">Medical Problem</label>
										<textarea class="form-control" readonly="readonly"><?php print $member->medical_problem;?></textarea>
									</div>
									<div class="col-md-6">
										<label class="text-muted">Other Information</label>
										<textarea class="form-control" readonly="readonly"><?php print $member->other_info;?></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- /profile info -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='advance' ? 'active show': '';?>" id="advance">
					<?php if($this->LOGIN_USER->prm_std_info>1){?>
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Student Account
							<span class="d-block font-size-base text-muted">Student account helps you manage advance fee collection from this student. Later you can pay fee from student account. 
							</span>
							</h5>
							<button class="btn btn-default float-right">
								<span class="font-weight-bold"> Balance : <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{responseData.total_advance}}</span> 
							</button>
						</div>

						<?php if($member->status==$this->student_m->STATUS_ACTIVE){?>
						<div class="card-body">
							<div class="form-group">
							<div class="row">
								<div class="col-md-2">
									<label class="text-muted">Type</label>
									<select class="form-control select" ng-model="advance.type" data-fouc>
									<option value="">Select Type</option>
									<option value="plus">Receive</option>
									<option value="minus">Deduct</option>
									</select>
								</div>
								<div class="col-md-4">
									<label class="text-muted">Narration<span class="text-danger"> *</span></label>
									<input type="text" class="form-control" placeholder="E.g Advance Fee" ng-model="advance.title">
								</div>
								<div class="col-md-3">
									<label class="text-muted">Amount <span class="text-danger"> * </span></label>
									<input type="text" class="form-control" placeholder="amount Received E.g. 3000" ng-model="advance.amount">
								</div>
								<div class="col-md-3">
									<button ng-click="saveAdvance()" class="btn btn-success btn-lg ml-2 mt-3">
										<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-checkmark-circle2':!appConfig.btnClickedSave}" class="ml-2"></i>
									</button>
								</div>
							</div>
							</div>

						</div>
						<?php } ?>


					</div>
					<!-- /save info -->
					<?php } ?>
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Advance Deposit History</h4>
							<span class="text-muted">Following are the advanced deposit history for this student.</span>
						</div>
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadAdvance()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadAdvance()" class="btn btn-success btn-lg">
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
										<th ng-click="sortBy('title');loadAdvance();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Title</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
										</th>
										<th ng-click="sortBy('amount');loadAdvance();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Amount</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='amount'"></i> 
										</th>
										<th ng-click="sortBy('date');loadAdvance();" class="mouse-pointer font-weight-bold">
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
											<div class="list-icons" ng-show="row.date==='<?php print $this->student_m->date;?>'">
												<div class="list-icons-item dropdown">
													<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9 mr-5"></i></a>
													<div class="dropdown-menu dropdown-menu-right">
														<!-- <a class="dropdown-item" ng-click="selectAllowance(row)">
															<i class="icon-compose"></i> Update
														</a>
														<div class="dropdown-divider"></div> -->
														<?php if($this->LOGIN_USER->prm_std_info>2){?>
														<a class="dropdown-item" ng-click="delAdvance(row)">
															<i class="icon-cross"></i> Terminate Advance</a>
														<?php }?>
													</div>
												</div>
											</div>
										</td>
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadAdvance()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadAdvance()">
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
							<button class="btn btn-default float-right">
								<span class="font-weight-bold"> Balance : <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{responseData.total_advance}}</span> 
							</button>
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
														<?php if($this->LOGIN_USER->prm_std_info>0){?>
														<a class="dropdown-item" ng-click="delAllowance(row)"><i class="icon-cross"></i> Terminate Allowance</a>
														<?php }?>
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
			    <div class="tab-pane fade <?php print $tab=='feehistory' ? 'active show': '';?>" id="feehistory">
					
					<!-- List table -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Fee History
							<span class="d-block font-size-base text-muted">System tracks and save all the important events regarding this student fee payment. You may go through the fee history to verify the records. 
							</span>
							</h5>
							<button class="btn btn-default float-right mr-1">
								<span class="font-weight-bold"> Total Billing : <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{responseData.total_fee}}</span> 
							</button>
							<button class="btn btn-default float-right">
								<span class="font-weight-bold"> Balance : <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{responseData.balance}}</span> 
							</button>
						</div>
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadFeeHistory()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadFeeHistory()" class="btn btn-success btn-lg">
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
										<th ng-click="sortBy('title');loadFeeHistory();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Title</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
										</th>
										<th ng-click="sortBy('amount');loadFeeHistory();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Amount Received</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='amount'"></i> 
										</th>
										<th ng-click="sortBy('date');loadFeeHistory();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Date</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='date'"></i> 
										</th>
										<!-- <th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th> -->
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td>
					  					<td>{{row.amount}}</td>  
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
														<?php if($this->LOGIN_USER->prm_std_info>0){?>
														<a class="dropdown-item" ng-click="delAllowance(row)"><i class="icon-cross"></i> Terminate Allowance</a>
														<?php }?>
													</div>
												</li>
											</div>
										</td> -->
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadFeeHistory()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadFeeHistory()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='accounts' ? 'active show': '';?>" id="accounts">
					
					<!-- List table -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Student Account
							<span class="d-block font-size-base text-muted">System tracks and save all the important events regarding this student fee. You may go through the accounts to verify the records. 
							</span>
							</h5>
							<button class="btn btn-default float-right mr-1">
								<span class="font-weight-bold"> Total Billing : <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{responseData.total_fee}}</span> 
							</button>
							<button class="btn btn-default float-right">
								<span class="font-weight-bold"> Balance : <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{responseData.balance}}</span> 
							</button>
						</div>
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadVouchers()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadVouchers()" class="btn btn-success btn-lg">
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
										<th ng-click="sortBy('remarks');loadVouchers();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Title</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='remarks'"></i> 
										</th>
										<th ng-click="sortBy('amount');loadVouchers();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Amount</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='amount'"></i> 
										</th>
										<th ng-click="sortBy('date');loadVouchers();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Date</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='date'"></i> 
										</th>
										<!-- <th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th> -->
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.remarks}}</td>
					  					<td><span ng-show="row.operation==='<?php print $this->std_fee_entry_m->OPT_MINUS;?>'">-</span>{{row.amount}}</td>  
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
														<?php if($this->LOGIN_USER->prm_std_info>0){?>
														<a class="dropdown-item" ng-click="delAllowance(row)"><i class="icon-cross"></i> Terminate Allowance</a>
														<?php }?>
													</div>
												</li>
											</div>
										</td> -->
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadVouchers()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadVouchers()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>

			    <div class="tab-pane fade <?php print $tab=='concession' ? 'active show': '';?>" id="concession">
					<?php if($this->LOGIN_USER->prm_std_info>1){?>
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Student Concessions
							<span class="d-block font-size-base text-muted">Student concessions helps you grant permanant concessions to students 
							</span>
							</h5>
							<button class="btn btn-default float-right">
								<span class="font-weight-bold"> Granted : <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{responseData.total_concession}}</span> 
							</button>
						</div>

						<?php if($member->status==$this->student_m->STATUS_ACTIVE){?>
						<div class="card-body">
							<div class="form-group">
							<div class="row">
								<div class="col-sm-4">
									<label class="text-muted">Category <span class="text-danger"> * </span></label>
									<select class="form-control select" ng-model="concession.type_id" data-fouc>
									<option value="">Select Category</option>
									<?php foreach($concessions as $row){?>
									<option value="<?php print $row['mid'];?>"><?php print $row['title'];?></option>
									<?php }?>
									</select>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Type</label>
									<select class="form-control select" ng-model="concession.type" data-fouc>
									<option value="">Select Type</option>
									<option value="<?php print $this->std_fee_concession_m->TYPE_FIXED; ?>">Fixed Amount</option>
									<option value="<?php print $this->std_fee_concession_m->TYPE_PERCENTAGE; ?>">Percentage</option>
									</select>
								</div>
								<div class="col-md-3">
									<label class="text-muted">Concession <span class="text-danger"> * </span></label>
									<input type="text" class="form-control" placeholder="concession value" ng-model="concession.amount">
								</div>
								<div class="col-md-2">
									<button ng-click="saveConcession()" class="btn btn-success btn-lg ml-2 mt-3">
										<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-checkmark-circle2':!appConfig.btnClickedSave}" class="ml-2"></i>
									</button>
								</div>
							</div>
							</div>

						</div>
						<?php } ?>


					</div>
					<!-- /save info -->
					<?php } ?>
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Concession Granted</h4>
							<span class="text-muted">Following are the concessions grated to this student.</span>
						</div>
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadConcession()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadConcession()" class="btn btn-success btn-lg">
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
										<th class="font-weight-bold">Category</th>
										<th class="font-weight-bold">Type</th>
										<th class="font-weight-bold">Concession</th>
										<th class="font-weight-bold">Date</th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td>
										<td>{{row.type}}</td>
					  					<td>
					  						<span ng-show="row.type==='<?php print $this->std_fee_concession_m->TYPE_FIXED;?>'"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]?>{{row.amount}}</span>
					  						<span ng-show="row.type==='<?php print $this->std_fee_concession_m->TYPE_PERCENTAGE;?>'">{{row.amount}}%</span>
					  					</td>  
					  					<td>{{row.date}}</td> 
										<td class="text-center">											
											<?php if($this->LOGIN_USER->prm_std_info>2){?>
											<a class="btn" ng-click="delConcession(row)">
												<i class="icon-cross text-danger"></i></a>
											<?php }?>
											<!-- <div class="list-icons" ng-show="row.date==='<?php print $this->student_m->date;?>'">
												<div class="list-icons-item dropdown">
													<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9 mr-5"></i></a>
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item" ng-click="selectAllowance(row)">
															<i class="icon-compose"></i> Update
														</a>
														<div class="dropdown-divider"></div>
														<?php if($this->LOGIN_USER->prm_std_info>2){?>
														<a class="dropdown-item" ng-click="delAdvance(row)">
															<i class="icon-cross"></i> Terminate Advance</a>
														<?php }?>
													</div>
												</div>
											</div> -->
										</td>
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadConcession()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadConcession()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>




			    <div class="tab-pane fade <?php print $tab=='academics' ? 'active show': '';?>" id="academics">
					<?php if($this->LOGIN_USER->prm_std_info>1){?>
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Academics 
							<span class="d-block font-size-base text-muted">Academics module help you keep record of this student academics. All these academics will printed on student profile while printing or other places.
							</span>
							</h5>
						</div>

						<div class="card-body">
							<div class="form-group">
							<div class="row">
								<div class="col-md-4">
									<label class="text-muted">Session<span class="text-danger"> *</span></label>
									<input type="text" class="form-control" placeholder="E.g 2017-2018" ng-model="academic.session">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Class<span class="text-danger"> *</span></label>
									<input type="text" class="form-control" placeholder="E.g Nursery" ng-model="academic.class">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Roll Number</label>
									<input type="text" class="form-control" placeholder="E.g Roll Number" ng-model="academic.roll_number">
								</div>
								<div class="col-md-3">
									<label class="text-muted">Total Marks</label>
									<input type="text" class="form-control" placeholder="E.g total marks" ng-model="academic.total_marks">
								</div>
								<div class="col-md-3">
									<label class="text-muted">Obtained Marks</label>
									<input type="text" class="form-control" placeholder="E.g obtained marks" ng-model="academic.obtained_marks">
								</div>
								<div class="col-md-3">
									<label class="text-muted">Status <span class="text-danger"> * </span></label>
									<input type="year" class="form-control" placeholder="Pass or Fail" ng-model="academic.status">
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
							<h4 class="card-title">Registered Academics</h4>
							<span class="text-muted">Academics module help you keep record of this student academics. All these academics will printed on student profile while printing or other places.</span>
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
										<th ng-click="sortBy('session');loadAcademic();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Session</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='session'"></i> 
										</th>
										<th ng-click="sortBy('class');loadAcademic();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Class</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='class'"></i> 
										</th>
										<th ng-click="sortBy('roll_number');loadAcademic();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Roll Number</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='roll_number'"></i> 
										</th>
										<th class="font-weight-bold"><span class="m-1">Marks</span></th>
										<th class="font-weight-bold"><span class="m-1">Status</span></th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.session}}</td>
					  					<td>{{row.class}}</td>  
					  					<td>{{row.roll_number}}</td> 
					  					<td>{{row.obtained_marks}}/{{row.total_marks}}</td> 
					  					<td>{{row.status}}</td> 
										<td class="text-center">
											<div class="list-icons">
												<div class="list-icons-item dropdown">
													<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9 mr-5"></i></a>
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item" ng-click="selectAcademic(row)">
															<i class="icon-compose"></i> Update
														</a>
														<div class="dropdown-divider"></div>
														<?php if($this->LOGIN_USER->prm_std_info>2){?>
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
		    	
		    	<div class="tab-pane fade <?php print $tab=='results' ? 'active show': '';?>" id="results">
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Previous Results</h4>
							<span class="text-muted">This module help you keep record of this student previous sessions results. You can print marksheet of students for given sessions.</span>
						</div>
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadResults()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadResults()" class="btn btn-success btn-lg">
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
										<th class="font-weight-bold">Session</th>
										<th class="font-weight-bold">Class</th>
										<th class="font-weight-bold">Marks</th>
										<th class="font-weight-bold"><span class="m-1">Status</span></th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.session}}</td>
					  					<td>{{row.class}}</td>  
					  					<td>{{row.obt_marks}}/{{row.total_marks}}</td> 
					  					<td>{{row.status}}</td> 
										<td class="text-center">
											<div class="list-icons">
												<div class="list-icons-item dropdown">
													<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9 mr-5"></i></a>
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item" ng-href="<?php print $this->LIB_CONT_ROOT.'exam/printing/report/?rpt=marksheet&usr='?>{{row.student_id}}&session={{row.session_id}}&class_id={{row.class_id}}" target="_blank">
															<i class="icon-printer"></i> Print Marksheet
														</a>
													</div>
												</li>
											</div>
										</td>
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadResults()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadResults()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>
		    	
			    <div class="tab-pane fade <?php print $tab=='home-work' ? 'active show': '';?>" id="home-work">
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Homework assigned to student</h4>
							<span class="text-muted">Please help children to complete their homework list below.</span>	
						</div>
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadHomework()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadHomework()" class="btn btn-success btn-lg">
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
										<th class="font-weight-bold">Subject</th>
										<th class="font-weight-bold">Homework</th>
										<th class="font-weight-bold">Date</th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.subject}}</td>
										<td>{{row.homework}}</td>
					  					<td>{{row.date}}</td> 
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadHomework()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadHomework()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='attendance' ? 'active show': '';?>" id="attendance">
					<?php
						$report=$this->std_attendance_m->get_attendance_report($this->CAMPUSID,$member->mid,'yearly');
						//////////////////////////////////////////////////////////////////////////////////////
					?>
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Attendance Record For the Year (<?php print date('Y');?>)</h4>
							<span class="text-muted">Find below the attendance record of your child.</span>
							<p>
				                <span><strong>Total Days (until End of <?php print date('F');?>): </strong><span class="badge badge-info"><?php print $report[$member->mid]['total_report']['total_days'];?></span></span>
				                <span><strong>Holidays: </strong><span class="badge bg-primary"><?php print $report[$member->mid]['total_report']['holidays'];?></span></span>
				                <span><strong>Present: </strong><span class="badge bg-success"><?php print $report[$member->mid]['total_report']['present'];?></span></span>
				                <span><strong>Absent: </strong><span class="badge bg-danger"><?php print $report[$member->mid]['total_report']['absent'];?></span></span>
				                <span><strong>Leaves: </strong><span class="badge bg-info"><?php print $report[$member->mid]['total_report']['leave'];?></span></span>
				                <span><strong>Others: </strong><span class="badge bg-warning"><?php print $report[$member->mid]['total_report']['others'];?></span></span>
				            </p>
						</div>
						<div class="card-body">
							<!-- <div class="fullcalendar-basic"></div> -->
							<div class="calendar-attendance"></div>
						</div>
						<div class="card-footer">
							<span class="text-muted"><code>Holidays:</code>Official holidays.</span>
							<span class="text-muted"><code>Present:</code>Student attended the school.</span>
							<span class="text-muted"><code>Absent:</code>Student absent from school.</span>
							<span class="text-muted"><code>Leave:</code>Student on official leave.</span>
							<span class="text-muted"><code>Others:</code>Attendance not marked for student.</span>
							

						</div>

					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='tests' ? 'active show': '';?>" id="tests">
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Test Progress details</h4>
							<span class="text-muted">Find below the students performance in the monthly tests.</span>
						</div>
						<!--<div class="card-body">
							 <div class="row">						
								<div class="col-sm-12">
									<div class="input-group mb-3">
										<div class="form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadTests()">
											<div class="form-control-feedback form-control-feedback-lg">
												<i class="icon-search4 text-muted"></i>
											</div>
										</div>

										<div class="input-group-append">
											<button ng-click="loadTests()" class="btn btn-success btn-lg">
											<span class="font-weight-bold"> Search</span>
											<i ng-class="{'icon-spinner2':appConfig.btnClickedSearch, 'spinner':appConfig.btnClickedSearch, 'icon-circle-right2':!appConfig.btnClickedSearch}" class=" ml-2"></i>
											</button>
										</div>
									</div>
								</div>
								
							</div> 
						</div>-->
						<div class="table-responsive">
						<table class="table tasks-responsive table-sm">
							<thead>
								<tr>
									<th class="font-weight-bold">#</th>
									<th class="font-weight-bold">Subject</th>
									<th class="font-weight-bold">Test</th>
									<th class="font-weight-bold">Marks</th>
									<th class="font-weight-bold">Status</th>
									<th class="font-weight-bold">Date</th>
									<!-- <th class="text-center text-muted" style="width: 30px;"><i class="icon-checkmark3"></i></th> -->
					            </tr>
							</thead>
							<tbody>

								<tr ng-repeat="row in responseData.rows">
									<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
									<td>{{row.subject}}</td>
									<td>{{row.test}}</td>
				  					<td>{{row.obt_marks+'/'+row.total_marks}}</td>  
				  					<td>
				  						<span ng-show="row.status==='<?php print $this->std_subject_test_result_m->STATUS_PASS;?>'" class="badge badge-success">Passed</span>
				  						<span ng-show="row.status==='<?php print $this->std_subject_test_result_m->STATUS_FAIL;?>'" class="badge badge-danger">Fail</span>
				  						<span ng-show="row.status==='<?php print $this->std_subject_test_result_m->STATUS_UNDECLARED;?>'" class="badge badge-info">Not Announced</span>
				  					</td>  
				  					<td>{{row.date}}</td> 
					            </tr>


							</tbody>
						</table>
						<br><br><br>
						<div>
						<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold" ng-click="moveBack();loadTests()">
						<i class="icon-arrow-left52"></i> Back Page</button>
						<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadTests()">
						 Next Page <i class="icon-arrow-right6"></i></button>
						<br><br><br>
						</div>

						</div>
					</div>
					<!-- /list table -->
		    	</div>





			    <div class="tab-pane fade <?php print $tab=='awards' ? 'active show': '';?>" id="awards">
					<?php if($this->LOGIN_USER->prm_std_info>1){?>
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
														<?php if($this->LOGIN_USER->prm_std_info>2){?>
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
					<?php if($this->LOGIN_USER->prm_std_info>1){?>
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
														<?php if($this->LOGIN_USER->prm_std_info>2){?>
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
					<?php if($this->LOGIN_USER->prm_std_info>1){?>
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
														<?php if($this->LOGIN_USER->prm_std_info>2){?>
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
			<div class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-right wmin-200 border-0 shadow-0 order-1 order-md-2 sidebar-expand-md">

				<!-- Sidebar content -->
				<div class="sidebar-content">

					<!-- User card -->
					<div class="card">
						<div class="card-body text-center">
							<div class="card-img-actions d-inline-block mb-3">
								<img class="img-fluid rounded-circle" src="<?php print $this->UPLOADS_ROOT.'images/student/profile/'.$member->image;?>" width="170" height="170" alt="">
							</div>
								
							<?php if($this->LOGIN_USER->prm_std_info>1){?>
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
				    		<span class="d-block text-muted">
				    			<span class="m-2">Student ID:</span><?php print ucwords(strtolower($member->mid));?></span>
				    		<span class="d-block text-muted">
				    			<span class="m-2">Computer Number:</span><?php print ucwords(strtolower($member->computer_number));?></span>
				    		<span class="d-block text-muted">
				    			<span class="m-2">Guardian:</span><?php print ucwords(strtolower($member->guardian_name));?></span>
				    		<span class="d-block text-muted"><span class="m-2">Student Mobile:</span><?php print $member->mobile;?></span>
				    		<span class="d-block text-muted">
				    			<span class="m-2">Class:</span><?php print $member->class_id>0 ? $classes_array[$member->class_id] : '';?></span>
				    		<span class="d-block text-muted">
				    			<span class="m-2">Session:</span><?php print $sessions_array[$member->session_id];?></span>
				    		<span class="d-block text-muted">
				    			<span class="m-2">Fee:</span><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$member->fee.' '.ucwords($member->fee_type);?>
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
									<a href="#concession" class="navbar-nav-link" data-toggle="tab" ng-click="loadConcession()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
										<i class="icon-user-minus mr-2" style="color:<?php print $clr;?>;"></i>
										Concessions
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
									<a href="#results" class="navbar-nav-link" data-toggle="tab" ng-click="loadResults()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
										<i class="icon-graph mr-2" style="color:<?php print $clr;?>;"></i>
										Previous Results
									</a>
								</li>
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
									<a href="#achievements" class="nav-link" data-toggle="tab" ng-click="loadAchievement()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
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

					<!-- Application status -->
					<!-- <div class="card">
						<div class="card-header header-elements-inline">
							<h6 class="card-title">App Status</h6>

							<div class="header-elements">
								<div><span class="badge badge-mark border-success mr-2"></span> Operational</div>
							</div>
						</div>

						<div class="card-body">
					        <ul class="list-unstyled mb-0">
					            <li class="mb-3">
					                <div class="d-flex align-items-center mb-1">CPU usage <span class="text-muted ml-auto">50%</span></div>
									<div class="progress" style="height: 0.375rem;">
										<div class="progress-bar bg-info" style="width: 50%">
											<span class="sr-only">50% Complete</span>
										</div>
									</div>
					            </li>

					            <li class="mb-3">
					                <div class="d-flex align-items-center mb-1">RAM usage <span class="text-muted ml-auto">70%</span></div>
									<div class="progress" style="height: 0.375rem;">
										<div class="progress-bar bg-danger" style="width: 70%">
											<span class="sr-only">70% Complete</span>
										</div>
									</div>
					            </li>

					            <li class="mb-3">
					                <div class="d-flex align-items-center mb-1">Disc space <span class="text-muted ml-auto">80%</span></div>
									<div class="progress" style="height: 0.375rem;">
										<div class="progress-bar bg-success" style="width: 80%">
											<span class="sr-only">80% Complete</span>
										</div>
									</div>
					            </li>

					            <li>
					                <div class="d-flex align-items-center mb-1">Bandwidth <span class="text-muted ml-auto">60%</span></div>
									<div class="progress" style="height: 0.375rem;">
										<div class="progress-bar bg-primary" style="width: 60%">
											<span class="sr-only">60% Complete</span>
										</div>
									</div>
					            </li>
					        </ul>
						</div>
					</div> -->
					<!-- /application status -->

					<!-- Latest connections -->
					<?php
					$history=$this->std_history_m->get_rows(array('student_id'=>$member->mid,),array('limit'=>10));
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