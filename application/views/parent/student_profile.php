<?php
$sessions=$this->session_m->get_rows(array(),array('orderby'=>'status ASC,mid DESC') );
$classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'title ASC') );
$classes_array=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
$sessions_array=$this->session_m->get_values_array('mid','title',array());
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

					<!-- <div class="breadcrumb-elements-item dropdown p-0  d-none d-md-block">						
					<a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
						<i class="icon-printer mr-2"></i>Print
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a href="<?php print $this->CONT_ROOT.'printing/profile/?usr='.$member->mid;?>" class="dropdown-item"><i class="icon-vcard"></i> Profile</a>
					</div>
					</div> -->

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
						<a href="#profile" class="navbar-nav-link active" data-toggle="tab">
							<i class="icon-profile mr-2"></i>
							Profile
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
						<a href="#accounts" class="navbar-nav-link" data-toggle="tab" ng-click="loadFeerecord()">
							<i class="icon-cash mr-2"></i>
							Fee Record
						</a>
					</li>
					<li class="nav-item">
						<a href="#termresult" class="navbar-nav-link" data-toggle="tab" ng-click="loadTermResult()">
							<i class="icon-clipboard"></i>
							 Term Result
						</a>
					</li>
					<li class="nav-item">
						<a href="#finalresult" class="navbar-nav-link" data-toggle="tab" ng-click="loadFinalResult()">
							<i class="icon-clipboard2"></i>
							 Final Result
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
									<input type="text" value="<?php print $classes_array[$member->class_id];?>" class="form-control" readonly="readonly" >
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
									<div class="col-md-12">
										<label class="text-muted">Medical Problem (if any)</label>
										<textarea class="form-control" readonly="readonly"><?php print $member->medical_problem;?></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- /profile info -->
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
		    	<div class="tab-pane fade <?php print $tab=='accounts' ? 'active show': '';?>" id="accounts">
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Fee Record</h4>
							<span class="text-muted">Search here the fee record of student.</span>
						</div>
						<div class="card-body">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-12">
										<div class="input-group mb-4">
											<div class="form-group-feedback form-group-feedback-left">
												<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadFeerecord()">
												<div class="form-control-feedback form-control-feedback-lg">
													<i class="icon-search4 text-muted"></i>
												</div>
											</div>

											<div class="input-group-append">
												<button ng-click="loadFeerecord()" class="btn btn-success btn-lg">
												<span class="font-weight-bold"> Search</span>
												<i ng-class="{'icon-spinner2':appConfig.btnClickedSearch, 'spinner':appConfig.btnClickedSearch, 'icon-circle-right2':!appConfig.btnClickedSearch}" class=" ml-2"></i>
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="table-responsive">
								<p class="text-muted">Find below the fee vouchers of the student.</p>
								<table class="table tasks-responsive table-lg">
									<thead>
										<tr>
											<th class="font-weight-bold">#</th>
											<th class="font-weight-bold">Title</th>
											<th class="font-weight-bold">Amount</th>
											<th class="font-weight-bold">Due Date</th>
											<th class="font-weight-bold">Status</th>
											<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
							            </tr>
									</thead>
									<tbody>

										<tr ng-repeat="row in responseData.rows">
											<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
											<td>{{row.title}}</td>
											<td><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]; ?> {{row.amount}}</td>
											<td>{{row.due_date}}</td>
											<td>
												<span class="badge bg-success" ng-show="row.status==='<?php print $this->std_fee_voucher_m->STATUS_PAID;?>'">PAID ON {{row.date_paid}}</span>
												<span class="badge bg-info" ng-show="row.status==='<?php print $this->std_fee_voucher_m->STATUS_PARTIAL_PAID;?>'">PAID <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{row.amount_paid}} ON {{row.date_paid}}</span>
												<span class="badge bg-warning" ng-show="row.status==='<?php print $this->std_fee_voucher_m->STATUS_UNPAID;?>'">NOT YET PAID </span>
												<span class="badge bg-danger" ng-show="row.status==='<?php print $this->std_fee_voucher_m->STATUS_CANCELED;?>'">Canceled </span>
											</td>
											<td class="text-center">
												<a class="mouse-pointer" <?php print $this->MODAL_OPTIONS;?> data-target="#view-record" ng-click="selectRow(row);loadVoucher(row);"><i class="icon-eye text-info"></i>
												</a>
											</td>
							            </tr>


									</tbody>
								</table>
								<br><br><br>

								<div>
								<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadFeerecord()">
								<i class="icon-arrow-left52"></i> Back Page</button>
								<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadFeerecord()">
								 Next Page <i class="icon-arrow-right6"></i></button>
								<br><br><br>
								</div>
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
							<span class="text-muted">Find below the activity history of your child.</span>
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
										<th class="font-weight-bold">Descritption</th>
										<th class="font-weight-bold">Date</th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
					  					<td>{{row.description}}</td>  
					  					<td>{{row.date}}</td> 
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





			    <div class="tab-pane fade <?php print $tab=='academics' ? 'active show': '';?>" id="academics">
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Academic Record</h4>
							<span class="text-muted">Find below the academic record of the student.</span>
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
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Assigned Awards</h4>
							<span class="text-muted">Following are the awards catched by the student during his/her joining in this organization.</span>
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
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td>
					  					<td>{{row.remarks}}</td>  
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
					
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Endorsements</h4>
							<span class="text-muted">Find below the endorsement of faculty and chairman for this student.</span>
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
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td>
					  					<td>{{row.remarks}}</td> 
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
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Issued Notices</h4>
							<span class="text-muted">Following are the notices issued to this student. </span>
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
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td>
					  					<td>{{row.remarks}}</td>  
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


			    <div class="tab-pane fade <?php print $tab=='termresult' ? 'active show': '';?>" id="termresult">
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Result of Term Exams</h4>
							<span class="text-muted">Find below the students performance in the term exam.</span>
						</div>
						<div class="table-responsive">
						<table class="table tasks-responsive table-sm">
							<thead>
								<tr>
									<th class="font-weight-bold">#</th>
									<th class="font-weight-bold">Term</th>
									<th class="font-weight-bold">Subject</th>
									<th class="font-weight-bold">Marks</th>
									<th class="font-weight-bold">Status</th>
					            </tr>
							</thead>
							<tbody>

								<tr ng-repeat="row in responseData.rows">
									<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
									<td>{{row.term}}</td>
									<td class="text-success"><strong>{{row.subject}}</strong></td>
				  					<td>{{row.obt_marks+'/'+row.total_marks}}</td>  
				  					<td>
				  						<span ng-show="row.status==='<?php print $this->std_subject_test_result_m->STATUS_PASS;?>'" class="badge badge-success">Passed</span>
				  						<span ng-show="row.status==='<?php print $this->std_subject_test_result_m->STATUS_FAIL;?>'" class="badge badge-danger">Fail</span>
				  						<span ng-show="row.status==='<?php print $this->std_subject_test_result_m->STATUS_UNDECLARED;?>'" class="badge badge-info">Not Announced</span>
				  					</td>  
					            </tr>


							</tbody>
						</table>
						<br><br><br>
						<div>
						<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold" ng-click="moveBack();loadTermResult()">
						<i class="icon-arrow-left52"></i> Back Page</button>
						<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadTermResult()">
						 Next Page <i class="icon-arrow-right6"></i></button>
						<br><br><br>
						</div>

						</div>
					</div>
					<!-- /list table -->
		    	</div>

			    <div class="tab-pane fade <?php print $tab=='finalresult' ? 'active show': '';?>" id="finalresult">
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Result of Final Exams</h4>
							<span class="text-muted">Find below the students performance in the final exam.</span>
						</div>
						<div class="table-responsive">
						<table class="table tasks-responsive table-sm">
							<thead>
								<tr>
									<th class="font-weight-bold">#</th>
									<th class="font-weight-bold">Session</th>
									<th class="font-weight-bold">Subject</th>
									<th class="font-weight-bold">Marks</th>
									<th class="font-weight-bold">Status</th>
					            </tr>
							</thead>
							<tbody>

								<tr ng-repeat="row in responseData.rows">
									<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
									<td>{{row.session}}</td>
									<td class="text-success"><strong>{{row.subject}}</strong></td>
				  					<td>{{row.obt_marks+'/'+row.total_marks}}</td>  
				  					<td>
				  						<span ng-show="row.status==='<?php print $this->std_subject_test_result_m->STATUS_PASS;?>'" class="badge badge-success">Passed</span>
				  						<span ng-show="row.status==='<?php print $this->std_subject_test_result_m->STATUS_FAIL;?>'" class="badge badge-danger">Fail</span>
				  						<span ng-show="row.status==='<?php print $this->std_subject_test_result_m->STATUS_UNDECLARED;?>'" class="badge badge-info">Not Announced</span>
				  					</td>  
					            </tr>


							</tbody>
						</table>
						<br><br><br>
						<div>
						<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold" ng-click="moveBack();loadFinalResult()">
						<i class="icon-arrow-left52"></i> Back Page</button>
						<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadFinalResult()">
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
			<div class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-right wmin-200 border-0 shadow-0 order-1 order-sm-2 sidebar-expand-sm">

				<!-- Sidebar content -->
				<div class="sidebar-content">

					<!-- User card -->
					<div class="card">
						<div class="card-body text-center">
							<div class="card-img-actions d-inline-block mb-3">
								<img class="img-fluid rounded-circle" src="<?php print $this->UPLOADS_ROOT.'images/student/profile/'.$member->image;?>" width="170" height="170" alt="">
							</div>
								
				    		<h6 class="font-weight-semibold mb-0"><?php print ucwords(strtolower($member->name));?></h6>
				    		<span class="d-block text-muted">
				    			<span class="m-2">Guardian:</span><?php print ucwords(strtolower($member->guardian_name));?></span>
				    		<span class="d-block text-muted"><span class="m-2">Student Mobile:</span><?php print $member->mobile;?></span>
				    		<span class="d-block text-muted">
				    			<span class="m-2">Class:</span><?php print $classes_array[$member->class_id];?></span>
				    		<span class="d-block text-muted">
				    			<span class="m-2">Session:</span><?php print $sessions_array[$member->session_id];?></span>
				    		<span class="d-block text-muted">
				    			<span class="m-2">Fee:</span><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$member->fee.' '.ucwords($member->fee_type);?>
				    		</span>
				    		<span class="d-block text-muted">
				    			<span class="m-2">Advance Deposited:</span><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$member->advance_amount;?>
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
									<a href="#history" class="nav-link" data-toggle="tab" ng-click="loadHistory()">
										<i class="icon-history mr-2"></i>
										History
									</a>
								</li>
								<li class="nav-item">
									<a href="#academics" class="nav-link" data-toggle="tab" ng-click="loadAcademic()">
										<i class="icon-graduation2 mr-2"></i>
										Academics
									</a>
								</li>
								<!-- <li class="nav-item">
									<a href="#termresult" class="nav-link" data-toggle="tab" ng-click="loadTermResult()">
										<i class="icon-clipboard"></i>
										 Term Result
									</a>
								</li>
								<li class="nav-item">
									<a href="#finalresult" class="nav-link" data-toggle="tab" ng-click="loadFinalResult()">
										<i class="icon-clipboard2"></i>
										 Final Result
									</a>
								</li> -->
								<li class="nav-item">
									<a href="#awards" class="nav-link" data-toggle="tab" ng-click="loadAwards()">
										<i class="icon-image2"></i>
										 Awards
										<span class="badge bg-success badge-pill ml-auto">{{member.total_awards}}</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#discipline" class="nav-link" data-toggle="tab" ng-click="loadDiscipline()">
										<i class="icon-image2"></i>
										 Notice's
										<span class="badge bg-danger badge-pill ml-auto">{{member.total_punishments}}</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#achievements" class="nav-link" data-toggle="tab" ng-click="loadAchievement()">
										<i class="icon-image2"></i>
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


<!-- view voucher modal -->
<div id="view-record" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h4 class="modal-title">Voucher Information <strong>({{selectedRow.title}})</strong></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<!-- Invoice template -->
				<div class="card">

					<div class="card-body">
						<div class="row">
							<div class="col-sm-6">
								<div class="mb-4">
									<img ng-src="<?php print $this->UPLOADS_ROOT;?>images/student/profile/{{selectedRow.image}}" class="mb-3 mt-2" style="width: 60px;" alt="">
		 							<ul class="list list-unstyled mb-0">
										<li><strong>{{selectedRow.student_name}}</strong></li>
										<li>Roll Number: {{selectedRow.roll_no}}</li>
										<li>Advance Amount: <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]; ?>{{selectedRow.advance_amount}}</li>
										<li>Status: 
											<span class="text-success" ng-show="voucher.status==='<?php print $this->std_fee_voucher_m->STATUS_PAID;?>'"> Paid On {{voucher.date_paid}}</span>
											<span class="text-info" ng-show="voucher.status==='<?php print $this->std_fee_voucher_m->STATUS_PARTIAL_PAID;?>'"> Paid {{voucher.amount_paid}} On {{voucher.date_paid}}</span>
											<span class="text-warning" ng-show="voucher.status==='<?php print $this->std_fee_voucher_m->STATUS_UNPAID;?>'"> Not Yet Paid</span>
											<span class="text-danger" ng-show="voucher.status==='<?php print $this->std_fee_voucher_m->STATUS_CANCELED;?>'"> Canceled</span>
										</li>
									</ul>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="mb-4">
									<div class="text-sm-right">
										<h4 class="text-primary mb-2 mt-md-2">Voucher# {{selectedRow.voucher_id}}</h4>
										<ul class="list list-unstyled mb-0">
											<li>Due date: <span class="font-weight-semibold">{{selectedRow.due_date}}</span></li>
											<li>Total Amount: <span class="font-weight-semibold"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]; ?>{{voucher.amount}}</span></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-7">
							<div class="table-responsive">
								<h4 class="text-muted text-center">Payments due</h4>
							    <table class="table table-sm">
							        <thead>
							            <tr>
							                <th>#</th>
							                <th>Title</th>
							                <th>Amount</th>
							            </tr>
							        </thead>
							        <tbody>
							            <tr ng-repeat="row in voucher.entries" class="text-muted" ng-show="row.operation==='<?php print $this->std_fee_entry_m->OPT_PLUS;?>'">
											<td>{{$index+1}}</td>
							                <td>
							                	<span>{{row.remarks}}</span>
						                	</td>
							                <td class="text-info"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{row.amount}}</td>

							            </tr>
							        </tbody>
							    </table>
							</div>
						</div>
						<div class="col-sm-5" ng-show="(voucher.entries|filter:{$:'minus'}).length>0">
							<div class="table-responsive">
								<h4 class="text-muted text-center">History</h4>
							    <table class="table table-sm">
							        <thead>
							            <tr>
							                <th>Amount Paid</th>
							                <th>Date</th>
							            </tr>
							        </thead>
							        <tbody>
							            <tr ng-repeat="row in voucher.entries" class="text-muted" ng-show="row.operation==='<?php print $this->std_fee_entry_m->OPT_MINUS;?>'">
							                <td class="text-success">{{row.remarks}} (<?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{row.amount}})</td>
							                <td class="text-success">{{row.date}}</td>
							            </tr>
							        </tbody>
							    </table>
							</div>
						</div>
					</div>

					<div class="card-body">
						<br>
						<div class="d-md-flex flex-md-wrap">
							<div class="pt-2 mb-3">
								
							</div>

							<div class="pt-2 mb-3 wmin-md-400 ml-auto">
								<!-- <h6 class="mb-3">Total due</h6> -->
								<div class="table-responsive">
									<table class="table">
										<tbody>
											<tr>
												<th>Subtotal:</th>
												<td class="text-right"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{voucher.amount}}</td>
											</tr>
											<tr ng-show="voucher.concession>0">
												<th>Concession:<span class="font-weight-normal"></span></th>
												<td class="text-right"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{voucher.concession}}</td>
											</tr>
											<tr>
												<th>Paid: {{voucher.date_paid}}<span class="font-weight-normal"></span></th>
												<td class="text-right"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{voucher.amount_paid}}</td>
											</tr>
											<tr>
												<th>Remaining:</th>
												<td class="text-right text-primary">
													<h5 class="font-weight-semibold"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{voucher.amount-voucher.amount_paid-voucher.concession | number:2}}</h5></td>
											</tr>
										</tbody>
									</table>
								</div>

							</div>
						</div>
					</div>

					<div class="card-footer">
						<span class="text-muted">Please pay the fee before due date so that your child can continue his/her studies. You can contact with accounts for more information.</span>
					</div>
				</div>
				<!-- /invoice template -->


			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /view voucher modal -->




</div>
<!-- /main content -->
<!-- ********************************************************************** -->
<!-- ///////////////////////////////SCRIPTS//////////////////////////////// -->
<!-- ********************************************************************** -->
<!-- <script type="text/javascript">
	
$(function(){
   
});
</script> -->