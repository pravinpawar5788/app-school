<?php

$teacher=$this->stf_role_m->get_by(array('title'=>'teacher',));
$faculty=$this->staff_m->get_rows(array('role_id'=>$teacher->mid,'campus_id'=>$this->CAMPUSID));
$periods=$this->period_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'sort_order ASC'));
$subjects=$this->class_subject_m->get_rows(array('class_id'=>$class->mid,'campus_id'=>$this->CAMPUSID),array('orderby'=>'display_order ASC'));

$sections=$this->class_section_m->get_rows(array('campus_id'=>$this->CAMPUSID,'class_id'=>$class->mid),array('orderby'=>'name ASC') );
// $classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'title ASC') );
// $sessions=$this->session_m->get_rows(array(),array('orderby'=>'status ASC,mid DESC') );
// $classes_array=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
// $sessions_array=$this->session_m->get_values_array('mid','title',array());

?>
<!-- Main content -->
<div class="content-wrapper" ng-controller="mozzCtrl"  ng-cloak>
	<!-- Page header -->
	<div class="page-header page-header-light border-bottom-0" ng-init="pid='<?php print $class->mid;?>'">

		<!-- Top breadcrumb line -->
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
					<a class="breadcrumb-item">Class</a>
					<span class="breadcrumb-item active">Details</span>
				</div>

				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="breadcrumb justify-content-center">
					<!-- <a href="#" class="breadcrumb-elements-item sidebar-control sidebar-component-toggle d-none d-md-block">
						<i class="icon-drag-right mr-2"></i>Toggle Profile
					</a> -->

					<!-- <div class="breadcrumb-elements-item dropdown p-0  d-none d-md-block">						
					<a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
						<i class="icon-printer mr-2"></i>Print
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a href="<?php print $this->CONT_ROOT.'printing/list/?pid='.$class->mid;?>" class="dropdown-item"><i class="icon-users"></i> Students List</a>
						<a href="<?php print $this->CONT_ROOT.'printing/attendance/?pid='.$class->mid;?>" class="dropdown-item"><i class="icon-cash"></i> Attendance Report</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item"><i class="icon-eye"></i> Today Present List</a>
						<a href="#" class="dropdown-item"><i class="icon-eye-blocked"></i> Today Absent List</a>
					</div>
					</div> -->

				</div>
			</div>
		</div>
		<!-- /breadcrumb line -->

	
		<!-- Page header content -->
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Class Details</span> - <?php print $class->title; ?></h4>
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
						<a href="#profile" class="navbar-nav-link <?php print empty($tab) || $tab=='profile' ? 'active': '';?>" data-toggle="tab" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-reading mr-2" style="color:<?php print $clr;?>;"></i>
							Class Students
						</a>
					</li>
					<li class="nav-item">
						<a href="#timetable" class="navbar-nav-link <?php print $tab=='subjects' ? 'active': '';?>" data-toggle="tab" ng-click="loadTimetable()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-alarm mr-2" style="color:<?php print $clr;?>;"></i>
							Timetable
						</a>
					</li>
					<li class="nav-item">
						<a href="#home-work" class="navbar-nav-link" data-toggle="tab" ng-click="loadHomework()">
							<i class="icon-home mr-2"></i>
							Home Work
						</a>
					</li>
					<!-- <li class="nav-item">
						<a href="#subjects" class="navbar-nav-link <?php print $tab=='subjects' ? 'active': '';?>" data-toggle="tab" ng-click="loadSubjects()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-books mr-2" style="color:<?php print $clr;?>;"></i>
							Curriculum
						</a>
					</li> -->
					<li class="nav-item">
						<a href="<?php print $this->LIB_CONT_ROOT.'classes';?>" class="navbar-nav-link" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-arrow-left52 mr-2" style="color:<?php print $clr;?>;"></i>
							Go Back
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

		<!-- Inner container -->
		<div class="d-flex align-items-start flex-column flex-md-row">

			<!-- Left content -->
			<div class="tab-content w-100 overflow-auto order-2 order-md-1">


			    <div class="tab-pane fade <?php print empty($tab) || $tab=='profile' ? 'active show': '';?>" id="profile">					
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Students List</h4>
							<span class="text-muted">Find below the list of active student of class (<strong><?php print $class->title; ?></strong>) for session <strong><?php print $this->session_m->getActiveSession()->title; ?></strong>.</span>
						</div>
						<div class="card-body">
							<div class="row">	
								<div class="col-sm-3">
									<select class="form-control select form-control-lg" ng-model="filter.section" ng-change="loadClass()" data-fouc>
									<option value="">All Students</option>
									<?php foreach ($sections as $row){?>            
									    <option value="<?=$row['mid'];?>" />Students of <?php print ucwords($row['name']);?> Section
								    <?php }?>
									</select>
								</div>							
								<div class="col-sm-9">
									<div class="input-group mb-3">
										<div class="form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadClass()">
											<div class="form-control-feedback form-control-feedback-lg">
												<i class="icon-search4 text-muted"></i>
											</div>
										</div>

										<div class="input-group-append">
											<button ng-click="loadClass()" class="btn btn-success btn-lg">
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
									<th class="font-weight-bold">Section</th>
									<th class="font-weight-bold">Roll Number</th>
									<th class="font-weight-bold">Fee</th>
									<th class="text-center text-muted" style="width: 30px;"><i class="icon-checkmark3"></i></th>
					            </tr>
							</thead>
							<tbody>

								<tr ng-repeat="row in class.students">
									<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
					                <td>
					                	<div><a ng-href="<?php print $this->LIB_CONT_ROOT.'student/profile/'?>{{row.mid}}" class="font-weight-semibold">
										<img ng-src="<?php print $this->UPLOADS_ROOT;?>images/student/profile/{{row.image}}" class="rounded-circle m-1" width="32" height="32" alt="">
				                		{{row.name}}
										</a></div>
									</td> 
									<td>{{row.father_name}}</td>
				  					<td>{{row.section}}</td>   
				  					<td>{{row.roll_no}}</td>   
				  					<td><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]?>{{row.fee}} {{row.fee_type | uppercase}}</td>
									<td>
										<div class="list-icons float-right">
											<div class="btn-group list-icons-item dropdown">
						                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
								
												<div class="dropdown-menu dropdown-menu-right">
													<a ng-href="<?php print $this->LIB_CONT_ROOT.'student/profile/';?>{{row.mid}}" class="dropdown-item"><i class="icon-user"></i>Student Profile</a>
													<?php if($this->LOGIN_USER->prm_class>1){?>
													<a <?php print $this->MODAL_OPTIONS;?> data-target="#edit" class="dropdown-item" ng-click="selectRow(row)"><i class="icon-compose"></i> Update Account
													</a>
													<a <?php print $this->MODAL_OPTIONS;?> data-target="#sms" class="dropdown-item" ng-click="selectRow(row)">
														<i class="icon-envelop"></i> Send SMS
													</a>
													<?php } ?>
													<a ng-href="<?php print $this->LIB_CONT_ROOT.'student/printing/profile/?usr='?>{{row.mid}}" class="dropdown-item" target="_blank">
														<i class="icon-printer"></i> Print Student Profile
													</a>
												</div>
											</div>
										</div>
									</td>
					            </tr>


							</tbody>
						</table>
						<br><br><br>

						</div>
					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='timetable' ? 'active show': '';?>" id="timetable">
					
					<!-- List table -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Class Timetable
							<span class="d-block font-size-base text-muted">Find below class the class timetable. You may update the class timetable below. Manage study periods in campus settings menu.
							</span>
							</h5>
							<button class="btn btn-success btn-sm float-right" <?php print $this->MODAL_OPTIONS;?> data-target="#add-period" >
								<span class="font-weight-bold"> <i class="icon-plus-circle2"></i> Assign New Period</span> 
							</button>
							<button class="btn btn-info btn-sm float-right" ng-click="toggleTeacher()">
								<span class="font-weight-bold"> <i class="icon-sync"></i> Toggle Teacher</span> 
							</button>
							<button class="btn btn-warning btn-sm float-right" ng-click="toggleRemobeable()">
								<span class="font-weight-bold"> <i class="icon-sync"></i> Toggle Remove</span> 
							</button>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table tasks-responsive table-lg">
									<thead>
										<tr>
											<th class="font-weight-bold text-center bg-success">PERIOD</th>
											<th class="font-weight-bold text-center bg-success">TIME</th>
											<th ng-repeat="row in responseData.days" class="font-weight-bold text-center bg-success">{{row | uppercase}}</th>
							            </tr>
									</thead>
									<tbody>

										<tr ng-repeat="row in responseData.periods">
											<td class="text-center bg-info">{{row.name | uppercase}}</td> 
											<td class="text-center bg-warning">{{row.from_time}} - {{row.to_time}}</td> 

						  					<td class="text-center bg-danger font-weight-bold" ng-show="row.type==='prayer'" colspan="6">ASSEMBLEY &AMP; PRAYER TIME</td>

						  					<td ng-repeat="sub in row.subjects track by $index" class="text-center text-slate" ng-show="row.type==='period'">
						  						<span>{{sub.name}}</span>
						  						<span ng-show="showTeacher && sub.teacher !=='***'"><br>By <span class="text-info">{{sub.teacher}}</span></span>
						  						<span class="ml-1" ng-show="isRemoveable && sub.name!=='---'">
						  							<a ng-click="delPeriod(sub)" class="mouse-pointer"><i class="icon-cross text-danger"></i></a>
						  						</span>
						  					</td>

						  					<td class="text-center bg-danger font-weight-bold" ng-show="row.type==='break'" colspan="6"> LUNCH BREAK</td> 

											<!-- <td>
												<div class="list-icons float-right">
													<div class="btn-group list-icons-item dropdown">
								                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
									
														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item" ng-href="<?php print $this->CONT_ROOT.'subject/'?>{{row.mid}}">
																<i class="icon-eye"></i> Manage Subject
															</a>
															<?php if($this->LOGIN_USER->prm_class>2){?>
															<a class="dropdown-item"  <?php print $this->MODAL_OPTIONS;?> data-target="#edit-subject" ng-click="selectRow(row)">
																<i class="icon-compose"></i> Update Subject
															</a>
															<?php } ?>
															<?php if($this->LOGIN_USER->prm_class>2){?>
															<div class="dropdown-divider"></div>
															<a class="dropdown-item" ng-click="delSubject(row)"><i class="icon-cross"></i> Remove Subject</a>
															<?php }?>
														</div>
													</div>
												</div>
											</td> -->
							            </tr>


									</tbody>
								</table>
								<br><br><br>

								<div>
								<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold" ng-click="moveBack();loadSubjects()">
								<i class="icon-arrow-left52"></i> Back Page</button>
								<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadSubjects()">
								 Next Page <i class="icon-arrow-right6"></i></button>
								<br><br><br>
								</div>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='subjects' ? 'active show': '';?>" id="subjects">
					
					<!-- List table -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Class Curriculum
							<span class="d-block font-size-base text-muted">Find below class curriculum. you may create new subjects for this class. Later, you or assigned staff can manage curriculum in their accounts.
							</span>
							</h5>
							<?php if($this->LOGIN_USER->prm_class>1){?>
							<button class="btn btn-success btn-sm float-right" <?php print $this->MODAL_OPTIONS;?> data-target="#add-subject" >
								<span class="font-weight-bold"> <i class="icon-plus-circle2"></i> New Subject</span> 
							</button>
							<?php } ?>
						</div>
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadSubjects()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadSubjects()" class="btn btn-success btn-lg">
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
										<th ng-click="sortBy('name');loadSubjects();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Name</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='name'"></i> 
										</th>
										<th class="font-weight-bold">Passing Marks</th>
										<th class="font-weight-bold">Chapters</th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td><a ng-href="<?php print $this->CONT_ROOT.'subject/'?>{{row.mid}}">{{row.name}} <span ng-show="row.code.length>0">({{row.code}})</span></a></td>
					  					<td>{{row.passing_percentage}}%</td> 
					  					<td>{{row.chapters}}</td> 
										<td>
											<div class="list-icons float-right">
												<div class="btn-group list-icons-item dropdown">
							                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
								
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item" ng-href="<?php print $this->CONT_ROOT.'subject/'?>{{row.mid}}">
															<i class="icon-eye"></i> Manage Subject
														</a>
														<?php if($this->LOGIN_USER->prm_class>2){?>
														<a class="dropdown-item"  <?php print $this->MODAL_OPTIONS;?> data-target="#edit-subject" ng-click="selectRow(row)">
															<i class="icon-compose"></i> Update Subject
														</a>
														<?php } ?>
														<?php if($this->LOGIN_USER->prm_class>2){?>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" ng-click="delSubject(row)"><i class="icon-cross"></i> Remove Subject</a>
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
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold" ng-click="moveBack();loadSubjects()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadSubjects()">
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
							<span class="text-muted">Following is the homework of this class assigned to students.</span>	
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
										<?php if(count($sections)>0){ ?>
										<th class="font-weight-bold">Section</th>
										<?php } ?>
										<th class="font-weight-bold">Subject</th>
										<th class="font-weight-bold">Homework</th>
										<th class="font-weight-bold">Date</th>
										<th class="font-weight-bold">0ptions</th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<?php if(count($sections)>0){ ?>
										<td>{{row.section}}</td>
										<?php } ?>
										<td>{{row.subject}}</td>
										<td>{{row.homework}}</td>
					  					<td>{{row.date}}</td> 
					  					<td>
											<a class="btn" ng-click="delHomework(row)" title="Remove Homework"><i class="icon-cross text-danger"></i></a>
										</td>
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

<!-- add modal -->
<div id="add-subject" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Register New Subject For Class</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you register new subject for this class curriculum. Later, you and assigned faculty can update the curriculum in their account.</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Subject Information</h6>
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
									<label class="text-muted">Subject Code</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g Eng101" ng-model="entry.code">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Subject Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g English" ng-model="entry.name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<label class="text-muted">Total Chapters</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g 20" ng-model="entry.chapters">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-calculator"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<label class="text-muted">Pass Percentage<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g 40" ng-model="entry.passing_percentage">
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
									<textarea rows="4" cols="3" class="form-control" placeholder="About Subject" ng-model="entry.description"></textarea>
								</div>							
							</div>
						</div>

					</div>
				</div>




			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="saveSubject()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /add modal -->

<!-- edit modal -->
<div id="edit-subject" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update subject information</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you update subject information for this class curriculum. Later, you and assigned faculty can update the curriculum in their account.</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Subject Information</h6>
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
									<label class="text-muted">Subject Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g English" ng-model="selectedRow.name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Subject Code <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g Eng101" ng-model="selectedRow.code">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<label class="text-muted">Total Chapters<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g 20" ng-model="selectedRow.chapters">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-calculator"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<label class="text-muted">Pass Percentage<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g 40" ng-model="selectedRow.passing_percentage">
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
									<textarea rows="4" cols="3" class="form-control" placeholder="About Subject" ng-model="selectedRow.description"></textarea>
								</div>							
							</div>
						</div>

					</div>
				</div>




			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="updateSubject()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /edit modal -->


<!-- add modal -->
<div id="add-period" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Assign New Period to Teacher</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you register free periods to teachers.</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Study Period Information</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>

						<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-2">
									<label class="text-muted">Select Day<span class="text-danger"> * </span></label>
									<select class="form-control select" ng-model="entry.day" data-fouc>
									<option value="">Select Day</option>
									<option value="monday">Monday</option>
									<option value="tuesday">Tuesday</option>
									<option value="wednesday">Wednesday</option>
									<option value="thursday">Thursday</option>
									<option value="friday">Friday</option>
									<option value="saturday">Saturday</option>
									</select>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Select Period<span class="text-danger"> * </span></label>
									<select class="form-control select" ng-model="entry.period_id" data-fouc>
									<option value="">Select Period</option>
									<?php foreach ($periods as $row){?>            
									    <option value="<?=$row['mid'];?>" /><?php print ucwords(strtolower($row['name']));?>
								    <?php }?>
									</select>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Select Subject<span class="text-danger"> </span></label>
									<select class="form-control select" ng-model="entry.subject_id" data-fouc>
									<option value="">Select Subject</option>
									<?php foreach ($subjects as $row){?>            
									    <option value="<?=$row['mid'];?>" /><?php print ucwords(strtolower($row['name']));?>
								    <?php }?>
									</select>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Select Teacher<span class="text-danger"> </span></label>
									<select class="form-control select" ng-model="entry.teacher_id" data-fouc>
									<option value="">Select Teacher</option>
									<?php foreach ($faculty as $row){?>            
									    <option value="<?=$row['mid'];?>" /><?php print ucwords(strtolower($row['name'])).'('.$row['staff_id'].')';?>
								    <?php }?>
									</select>
								</div>

							</div>
						</div>
					</div>
				</div>




			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="savePeriod()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Assign</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /add modal -->

<!-- edit modal -->
<div id="edit-period" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update subject information</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you update subject information for this class curriculum. Later, you and assigned faculty can update the curriculum in their account.</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Subject Information</h6>
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
									<label class="text-muted">Subject Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g English" ng-model="selectedRow.name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Subject Code <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g Eng101" ng-model="selectedRow.code">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<label class="text-muted">Total Chapters<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g 20" ng-model="selectedRow.chapters">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-calculator"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<label class="text-muted">Pass Percentage<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g 40" ng-model="selectedRow.passing_percentage">
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
									<textarea rows="4" cols="3" class="form-control" placeholder="About Subject" ng-model="selectedRow.description"></textarea>
								</div>							
							</div>
						</div>

					</div>
				</div>




			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="updateSubject()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /edit modal -->

<!-- edit modal -->
<div id="edit" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update Student ({{selectedRow.name}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Update here the student roll number &amp; section.</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Required Data</h6>
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
									<label class="text-muted">Student Name</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Class Name" ng-model="selectedRow.name" readonly="readonly">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user"></i>
										</div>
									</div>
								</div>
								<?php if(count($sections)>0){?>
								<div class="col-sm-4">
									<label class="text-muted">Section</label>
									<select class="form-control select form-control-lg" ng-model="selectedRow.section_id" data-fouc>
									<option value="0">None</option>
									<?php foreach ($sections as $row){?>            
									    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['name']);?>
								    <?php }?>
									</select>
								</div>
								<?php } ?>
								<div class="col-sm-4">
									<label class="text-muted">Roll Number<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Student Roll Number" ng-model="selectedRow.roll_no">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>



			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="updateRow()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /edit modal -->

<!-- send sms -->
<div id="sms" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Send SMS Notification To Student {{selectedRow.name}}</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you send sms notitification to the student. You need to enable sms notification feature from settings before sending the sms notification. <?php print $this->SMS_HOST_NOTE; ?></p>
				<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-filter3 mr-2"></i>Whome to send sms?</legend>
				<div class="form-group">
					<div class="row">
						
						<div class="col-sm-4">
							<label class="text-muted">Send sms to...</label>
							<select class="form-control select" ng-model="filter.target" data-fouc>
							<option value="">Both Guardian &amp; Student</option>
							<option value="guardian">Only Guardian's</option>
							<option value="student">Only Student's</option>
							</select>
						</div>
					</div>
				</div>
				<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-envelop mr-2"></i>Write Message...</legend>
				<p>
					<button type="button" class="btn btn-outline-success btn-sm" ng-click="addkey('NAME')"><i class="icon-user mr-2"></i> Student Name</button>
					<button type="button" class="btn btn-outline-info btn-sm" ng-click="addkey('GUARDIAN')"><i class="icon-people mr-2"></i> Guardian</button>
				</p>
				<div class="form-group">
					<label class="text-muted">Dynamic keys will be changed into actual values before sending sms.</label>
					<div class="row">
						<div class="col-sm-10">
							<textarea class="form-control" ng-model="message" placeholder="Write your message..." rows="5"></textarea>
						</div>
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="sendSingleSms()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Send SMS</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /send sms -->






</div>
<!-- /main content -->
<!-- ********************************************************************** -->
<!-- ///////////////////////////////SCRIPTS//////////////////////////////// -->
<!-- ********************************************************************** -->
<!-- <script type="text/javascript">
	
$(function(){
   
});
</script> -->