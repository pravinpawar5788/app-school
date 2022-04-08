<?php
$activeSession=$this->session_m->getActiveSession();
$sections=$this->class_section_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'name ASC') );
$classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'title ASC') );
$sessions=$this->session_m->get_rows(array(),array('orderby'=>'status ASC,mid DESC') );
// $classes_array=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
// $sessions_array=$this->session_m->get_values_array('mid','title',array());
$defaultMessage="Dear Parent,\n Monthly test result of {STUDENT} for {MONTH}:\n {RESULT} \n {STAMP}";

?>
<!-- Main content -->
<div class="content-wrapper" ng-controller="mozzCtrl"  ng-cloak>
	<!-- Page header -->
	<div class="page-header page-header-light border-bottom-0" ng-init="message='<?php print $defaultMessage;?>'">

		<!-- Top breadcrumb line -->
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
					<a class="breadcrumb-item">Exam</a>
					<span class="breadcrumb-item active">Student Progress</span>
				</div>

				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="breadcrumb justify-content-center">
					<!-- <a href="#" class="breadcrumb-elements-item sidebar-control sidebar-component-toggle d-none d-md-block">
						<i class="icon-drag-right mr-2"></i>Toggle Profile
					</a> -->

					<div class="breadcrumb-elements-item dropdown p-0  d-none d-md-block">						
					<a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
						<i class="icon-printer mr-2"></i>Print
					</a>
					<div class="dropdown-menu dropdown-menu-left">						
						<div class="dropdown-submenu dropdown-submenu-left">
							<a href="#" class="dropdown-item"><i class="icon-clipboard4"></i> Forms</a>
							<div class="dropdown-menu">
								<a href="<?php print $this->CONT_ROOT.'printing/form/?frm=resultform';?>" class="dropdown-item">Blank Result Form</a>
								<!-- <a href="<?php print $this->CONT_ROOT.'printing/list/?alumni';?>" class="dropdown-item">Fee Defaulter Notice</a> -->
							</div>
						</div>
						<div class="dropdown-divider"></div>
						<a href="<?php print $this->CONT_ROOT.'printing/report/?rpt=clsprgsheet&usr=cls';?>" class="dropdown-item" target="_blank">
							<i class="icon-users"></i>Class Progress Sheets</a>
						<a href="<?php print $this->CONT_ROOT.'printing/report/?rpt=clsprglist&usr=cls';?>" class="dropdown-item" target="_blank">
							<i class="icon-list"></i>Class Progress Lists</a>
						<!-- <a href="<?php print $this->CONT_ROOT.'printing/attendance/?pid=';?>" class="dropdown-item"><i class="icon-cash"></i> Attendance Report</a> -->
						<!-- <div class="dropdown-divider"></div> -->
						<!-- <a href="#" class="dropdown-item"><i class="icon-eye"></i> Today Present List</a> -->
						<!-- <a href="#" class="dropdown-item"><i class="icon-eye-blocked"></i> Today Absent List</a> -->
					</div>
					</div>

				</div>
			</div>
		</div>
		<!-- /breadcrumb line --->

	
		<!-- Page header content -->
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Exam</span> - Student Progress</h4>
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
					Navigation
				</button>
			</div>

			<div class="navbar-collapse collapse" id="navbar-second">
				<ul class="nav navbar-nav">
					<li class="nav-item">
						<a href="#profile" class="navbar-nav-link active" data-toggle="tab" ng-click="loadRows()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-stats-bars2 mr-2" style="color:<?php print $clr;?>;"></i>
							Monthly Progress
						</a>
					</li>
					<li class="nav-item">
						<a href="#tests" class="navbar-nav-link" data-toggle="tab" ng-click="loadTests()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-clipboard mr-2" style="color:<?php print $clr;?>;"></i>
							Monthly Test &amp; Result
						</a>
					</li>
					<!--<li class="nav-item">
						<a href="#academics" class="navbar-nav-link" data-toggle="tab" ng-click="loadAcademic()">
							<i class="icon-graduation2 mr-2"></i>
							Academics
						</a>
					</li> -->
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


			    <div class="tab-pane fade <?php print empty($tab) || $tab=='profile' ? 'active show': '';?>" id="profile"><!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Students Progress in tests</h4>
							<span class="text-muted">Find below the students performance in the tests for  session <strong><?php print $this->session_m->getActiveSession()->title; ?></strong>.</span>
						</div>
						<div class="card-body">
							<div class="row">	
								<div class="col-sm-3">
									<select class="form-control select form-control-lg" ng-model="filter.class_id" ng-change="loadRows();loadClasseseSections();" data-fouc>
									<option value="">All Classes</option>
									<?php foreach ($classes as $row){?>            
									    <option value="<?=$row['mid'];?>" /><?php print ucwords($row['title']);?>
								    <?php }?>
									</select>
								</div>		
								<!-- <div class="col-sm-3">
									<select class="form-control select form-control-lg" ng-model="filter.section" ng-change="loadRows()" data-fouc>
									<option value="">All Students</option>
									<?php foreach ($sections as $row){?>            
									    <option value="<?=$row['name'];?>" />Section <?php print ucwords($row['name']);?>
								    <?php }?>
									</select>
								</div> -->		
								<div class="col-sm-3" ng-show="classSections.length>0">
									<!-- <label class="text-muted">Select Section</label> -->
									<select class="form-control select" ng-model="filter.section" ng-change="loadRows()" data-fouc>
									<option value="">All Sections</option>
									<option  ng-repeat="row in classSections" value="{{row.mid}}">{{row.name}}</option>
									</select>
								</div>					
								<div class="col-sm-6">
									<div class="input-group mb-3">
										<div class="form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadRows()">
											<div class="form-control-feedback form-control-feedback-lg">
												<i class="icon-search4 text-muted"></i>
											</div>
										</div>

										<div class="input-group-append">
											<button ng-click="loadRows()" class="btn btn-success btn-lg">
											<span class="font-weight-bold"> Search</span>
											<i ng-class="{'icon-spinner2':appConfig.btnClickedSearch, 'spinner':appConfig.btnClickedSearch, 'icon-circle-right2':!appConfig.btnClickedSearch}" class=" ml-2"></i>
											</button>
										</div>
									</div>
								</div>
								
							</div>
						</div>
						<div class="table-responsive">
						<table class="table tasks-responsive table-lg">
							<thead>
								<tr>
									<th class="font-weight-bold">#</th>
									<th class="font-weight-bold">Name</th>
									<th class="font-weight-bold">Father Name</th>
									<th class="font-weight-bold">Roll No</th>
									<th class="font-weight-bold">Session Performance</th>
									<th class="text-center text-muted" style="width: 30px;"><i class="icon-checkmark3"></i></th>
					            </tr>
							</thead>
							<tbody>

								<tr ng-repeat="row in responseData.rows">
									<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
					                <td>
					                	<div><a ng-href="<?php print $this->CONT_ROOT.'prgdetail/';?>{{row.mid}}" class="font-weight-semibold">
										<img ng-src="<?php print $this->UPLOADS_ROOT;?>images/student/profile/{{row.image}}" class="rounded-circle m-1" width="32" height="32" alt="">
				                		{{row.name}}
										</a></div>
									</td> 
									<td>{{row.father_name}}</td>
									<td>{{row.roll_no}}</td>
				  					<td>{{row.performance}}%</td>  
									<td>
										<div class="list-icons float-right">
											<div class="btn-group list-icons-item dropdown">
						                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>

												<div class="dropdown-menu dropdown-menu-right">
													<a ng-href="<?php print $this->CONT_ROOT.'prgdetail/';?>{{row.mid}}" class="dropdown-item"><i class="icon-eye"></i>View Details</a>
													<a ng-href="<?php print $this->CONT_ROOT.'printing/report/?rpt=prgsheet&usr='?>{{row.mid}}" class="dropdown-item" target="_blank">
														<i class="icon-printer"></i> Print Progress Report
													</a>
													<a <?php print $this->MODAL_OPTIONS;?> data-target="#send-sms" class="dropdown-item" ng-click="selectRow(row);filterTestMonths();">
														<i class="icon-envelope"></i> Send Result SMS
													</a>
												</div>
											</div>
										</div>
									</td>
					            </tr>


							</tbody>
						</table>
						<br><br><br>
						<div>
						<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold" ng-click="moveBack();loadRows()">
						<i class="icon-arrow-left52"></i> Back Page</button>
						<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadRows()">
						 Next Page <i class="icon-arrow-right6"></i></button>
						<br><br><br>
						</div>

						</div>
					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='tests' ? 'active show': '';?>" id="tests">
					
					<!-- List table -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Tests taken from students during session(<?php print $activeSession->title; ?>)
							<span class="d-block font-size-base text-muted">Find below the tests taken from students. you may create new test for any subject. Later, you or assigned staff can manage test results in their accounts.
							</span>
							</h5>
							<?php if($this->LOGIN_USER->prm_class>1){?>
							<button class="btn btn-success btn-sm float-right" <?php print $this->MODAL_OPTIONS;?> data-target="#add-test" >
								<span class="font-weight-bold"> <i class="icon-plus-circle2"></i> Create New Test</span> 
							</button>
							<?php } ?>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-sm-8">
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
								<div class="col-sm-4">
									<a class="btn btn-info btn-lg text-white" <?php print $this->MODAL_OPTIONS;?> data-target="#refine-search">
											<i class="icon-filter3"></i> Advance Search</a>
									<a class="btn btn-danger text-white" ng-show="showFilter()" ng-click="clearFilter();loadTests();">
											<i class="icon-diff-removed"></i></a>									

								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th ng-click="sortBy('title');loadTests();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Test</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
										</th>
										<th class="font-weight-bold">Class</th>
										<th class="font-weight-bold">Subject</th>
										<!-- <th class="font-weight-bold">Chapter</th> -->
										<th class="font-weight-bold">Total Marks</th>
										<th class="font-weight-bold">Date</th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<!-- <td><a ng-href="<?php print $this->CONT_ROOT.'subject/'?>{{row.mid}}" target="_blank">{{row.title}}</a></td> -->
					  					<td class="font-weight-bold">{{row.title}}</td>  
					  					<td>{{row.class}}</td>  
					  					<td>{{row.subject}}</td>  
					  					<!-- <td>{{row.chapter}}</td>  -->
					  					<td>{{row.total_marks}}</td> 
					  					<td>{{row.date}}</td> 
										<td>
											<div class="list-icons float-right">
												<div class="btn-group list-icons-item dropdown">
							                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
											
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item"  <?php print $this->MODAL_OPTIONS;?> data-target="#load-students" ng-click="selectRow(row);loadResult();loadClassSections(row.class_id);"><i class="icon-eye"></i>Update Results</a>
														<?php if($this->LOGIN_USER->prm_class>2){?>
														<a class="dropdown-item"  <?php print $this->MODAL_OPTIONS;?> data-target="#edit-test" ng-click="selectRow(row)"><i class="icon-compose"></i> Update Test</a>
														<?php } ?>
														<?php if($this->LOGIN_USER->prm_class>2){?>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" ng-click="resetTestStudents(row)"><i class="icon-history"></i> Reset Students</a>
														<?php }?>
														<?php if($this->LOGIN_USER->prm_class>2){?>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" ng-click="delTest(row)" ng-show="row.session_id==='<?php print $activeSession->mid; ?>'"><i class="icon-cross"></i> Cancel</a>
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


			</div>
			<!-- /left content -->


			<!-- Right sidebar component -->
			<!-- <div class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-right wmin-300 border-0 shadow-0 order-1 order-md-2 sidebar-expand-md"> -->

				<!-- Sidebar content -->
				<!-- <div class="sidebar-content"> -->


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


				<!-- </div> -->
				<!-- /sidebar content -->

			<!-- </div> -->
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

<!-- refine search modal -->
<div id="refine-search" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Refine Your Search</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you search specific data by applying various filters on search. Please choose the filters you want to apply in your next search. After filter selection click the search button...</p>

				<hr>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<label class="text-muted">Select Session</label>
							<select class="form-control select" ng-model="filter.session_id" data-fouc>
							<option value="">Current Session</option>
							<?php foreach ($sessions as $row){?>            
							    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
						    <?php }?>
							</select>
						</div>
						<div class="col-sm-2">
							<label class="text-muted">Test Month - <?php print date('Y'); ?></label>
							<select class="form-control select" ng-model="filter.month" data-fouc>
							<option value="">All Months</option>
							<?php for($m=1; $m<=12; $m++){?>            
							    <option value="<?php print $m;?>" /><?php print month_string($m);?>
						    <?php }?>
							</select>
						</div>
						<div class="col-sm-3">
							<label class="text-muted">Test Date </label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-lg datepicker" placeholder="Test Date" ng-model="filter.date">
								<div class="form-control-feedback form-control-feedback-lg">
									<i class="icon-calendar"></i>
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<label class="text-muted">Select Class</label>
							<select class="form-control select" ng-model="filter.class_id" ng-change="filterSubjects()" data-fouc>
							<option value="">All Classes</option>
							<?php foreach ($classes as $row){?>            
							    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
						    <?php }?>
							</select>
						</div>
						<div class="col-sm-2">
							<label class="text-muted">Select Subject</label>
							<select class="form-control select form-control-lg" ng-model="filter.subject_id" data-fouc>
							<option value="">All Subjects</option>
							<option ng-repeat="row in subjects.rows" value="{{row.mid}}">{{row.name+' ('+row.code+')'}}</option>
							</select>
						</div>
						
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="loadTests()" class="btn btn-success btn-lg" data-dismiss="modal">
						<span class="font-weight-bold"> Search</span>
						<i class="icon-circle-right2 ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /refine search modal -->


<!-- add modal -->
<div id="add-test" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Create New Test</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you register new test for students. Later, you and assigned faculty can update the results in their account.</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Test Information</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>

						<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-4">
									<label class="text-muted">Class</label>
									<select class="form-control select form-control-lg" ng-model="entry.class_id" ng-change="loadSubjects()" data-fouc>
									<option value="">Select Class</option>
									<?php foreach ($classes as $row){?>            
									    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
								    <?php }?>
									</select>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Subject</label>
									<select class="form-control select form-control-lg" ng-model="entry.subject_id" data-fouc>
									<option value="">Select Subject</option>
									<option ng-repeat="row in subjects.rows" value="{{row.mid}}">{{row.name+' - '+row.code}}</option>
									</select>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Name<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g Math surprise test" ng-model="entry.title">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-4">
									<label class="text-muted">Test Date </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg datepicker" placeholder="Test Date" ng-model="entry.date">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-calendar"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Total Marks<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g 100" ng-model="entry.total_marks">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-calculator"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Chapter</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g 40" ng-model="entry.chapter">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-calculator"></i>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-10">
									<label class="text-muted">Description </label>
									<textarea rows="2" cols="3" class="form-control" placeholder="About Subject" ng-model="entry.description"></textarea>
								</div>							
							</div>
						</div>

					</div>
				</div>




			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="saveTest()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /add modal -->

<!-- edit modal -->
<div id="edit-test" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update test information</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you update test information. Later, you and assigned faculty can update the results in their account.</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Test Information</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>

						<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-4">
									<label class="text-muted">Name<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g Math surprise test" ng-model="selectedRow.title">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Total Marks<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g 100" ng-model="selectedRow.total_marks">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-calculator"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Chapter</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g 40" ng-model="selectedRow.chapter">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-calculator"></i>
										</div>
									</div>
								</div>


							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-10">
									<label class="text-muted">Description </label>
									<textarea rows="2" cols="3" class="form-control" placeholder="About Subject" ng-model="selectedRow.description"></textarea>
								</div>							
							</div>
						</div>

					</div>
				</div>





			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="updateTest()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /edit modal -->


<!-- load student modal -->
<div id="load-students" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update Result of Test (<strong>{{selectedRow.title}}</strong>) </h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">
				<div class="row">
					<div ng-show="classSections.length>0">
						<label class="text-muted">Load Students of one Section</label>
						<select class="form-control select" ng-model="filter.section" ng-change="loadResult()" data-fouc>
						<option value="">All Sections</option>
						<option  ng-repeat="row in classSections" value="{{row.mid}}">{{row.name}}</option>
						</select>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table tasks-responsive table-lg">
						<thead>
							<tr>
								<th class="font-weight-bold">#</th>
								<th class="font-weight-bold">Student Name</th>
								<th class="font-weight-bold">Roll Number</th>
								<th class="font-weight-bold">Class</th>
								<th class="font-weight-bold">Total Marks</th>
								<th class="font-weight-bold">Obtained Marks By Student

									
								</th>
								<!-- <th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th> -->
				            </tr>
						</thead>
						<tbody>

							<tr ng-repeat="row in results.rows">
								<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
								<td>
									<div><a class="font-weight-semibold">
									<img ng-src="<?php print $this->UPLOADS_ROOT;?>images/student/profile/{{row.student.image}}" class="rounded-circle m-1" width="32" height="32" alt="">
        							{{row.student.name}}
									</a></div>
								</td>
								<td>{{row.student.roll_no}}</td>  
								<td>{{row.class}}</td>  
								<td>{{row.total_marks}}</td>
			  					<td>
			  						<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control" placeholder="Obtaine Marks" ng-model="row.obt_marks" ng-blur="updateResult(row)">
										<div class="form-control-feedback">
											<i class="icon-user-check"></i>
										</div>
									</div>
								</td> 
								
				            </tr>


						</tbody>
					</table>					
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /load student modal -->


<!-- load sms modal -->
<div id="send-sms" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Send Result SMS To Student</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">	
				<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-envelop mr-2"></i>Write Message...</legend>
				<p>
					<button type="button" class="btn btn-outline-success btn-sm" ng-click="addkey('STUDENT')"><i class="icon-user mr-2"></i> Student Name</button>
					<button type="button" class="btn btn-outline-info btn-sm" ng-click="addkey('GUARDIAN')"><i class="icon-people mr-2"></i> Guardian</button>
					<button type="button" class="btn btn-outline-success btn-sm" ng-click="addkey('MONTH')"><i class="icon-calendar mr-2"></i> Month</button>
					<button type="button" class="btn btn-outline-info btn-sm" ng-click="addkey('RESULT')"><i class="icon-vcard mr-2"></i> Result</button>
					<button type="button" class="btn btn-outline-danger btn-sm" ng-click="addkey('STAMP')"><i class="icon-hammer2 mr-2"></i> Stamp</button>
				</p>
				<div class="form-group">
					<label class="text-muted">Dynamic keys will be changed into actual values before sending sms.</label>
					<div class="row">
						<div class="col-sm-12">
							<textarea class="form-control" ng-model="message" placeholder="Write your message..." rows="5"></textarea>
						</div>
					</div>
				</div>			
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<label class="text-muted">Month</label>
							<select class="form-control select" ng-model="filter.month" ng-change="filterTestDays();filterMonthTests()" data-fouc>
							<option value="">Select Month</option>
							<option ng-repeat="row in testMonths.rows" value="{{row.month}}">{{row.month_name}}</option>
							</select>
						</div>
						<div class="col-sm-2">
							<label class="text-muted">Date</label>
							<select class="form-control select" ng-model="filter.day" ng-change="filterMonthTests()" data-fouc>
							<option value="">Select Day</option>
							<option ng-repeat="row in testDays.rows" value="{{row.day}}">{{row.day}}</option>
							</select>
						</div>
						<div class="col-sm-3">
							<label class="text-muted">Test</label>
							<select class="form-control select" ng-model="filter.test_id" data-fouc>
							<option value="">All Tests</option>
							<option ng-repeat="row in monthTests.rows" value="{{row.mid}}">{{row.title}}</option>
							</select>
						</div>
						<div class="col-sm-2">
							<label class="text-muted">Receivers</label>
							<select class="form-control select" ng-model="filter.rxr" data-fouc>
							<option value="">Select Receiver</option>
							<option value="parents">Parents</option>
							<option value="students">Students</option>
							</select>
						</div>
						<div class="col-sm-3 mt-3" >
							<button ng-click="sendMonthlyReportSMS()" class="btn btn-success mt-2" >
								<span class="font-weight-bold">Send Report SMS</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
							</button>
						</div>


					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /load student modal -->





</div>
<!-- /main content -->
<!-- ********************************************************************** -->
<!-- ///////////////////////////////SCRIPTS//////////////////////////////// -->
<!-- ********************************************************************** -->
<!-- <script type="text/javascript">
	
$(function(){
   
});
</script> -->