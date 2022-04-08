<?php
$teacher=$this->stf_role_m->get_by(array('title'=>'teacher',));
$faculty=$this->staff_m->get_rows(array('role_id'=>$teacher->mid,'campus_id'=>$this->CAMPUSID));

$sections=$this->class_section_m->get_rows(array('campus_id'=>$this->CAMPUSID,'class_id'=>$record->class_id),array('orderby'=>'name ASC') );
// $sections=$this->class_section_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'name ASC') );
// $classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'title ASC') );
// $sessions=$this->session_m->get_rows(array(),array('orderby'=>'status ASC,mid DESC') );
// $classes_array=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
// $sessions_array=$this->session_m->get_values_array('mid','title',array());

?>
<!-- Main content -->
<div class="content-wrapper" ng-controller="mozzCtrl"  ng-cloak>
	<!-- Page header -->
	<div class="page-header page-header-light border-bottom-0" ng-init="pid='<?php print $record->mid;?>'">

		<!-- Top breadcrumb line -->
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
					<a class="breadcrumb-item">Subjects</a>
					<span class="breadcrumb-item active">Subject Detail</span>
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
						<a href="<?php print $this->CONT_ROOT.'printing/list/?pid=';?>" class="dropdown-item"><i class="icon-users"></i> Students List</a>
						<a href="<?php print $this->CONT_ROOT.'printing/attendance/?pid=';?>" class="dropdown-item"><i class="icon-cash"></i> Attendance Report</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item"><i class="icon-eye"></i> Today Present List</a>
						<a href="#" class="dropdown-item"><i class="icon-eye-blocked"></i> Today Absent List</a>
						<a href="<?php print $this->CONT_ROOT.'printing/history/?pid=';?>" class="dropdown-item"><i class="icon-cash"></i> History</a>
						<div class="dropdown-divider"></div>
					</div>
					</div> -->

				</div>
			</div>
		</div>
		<!-- /breadcrumb line -->

	
		<!-- Page header content -->
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Subject Detail</span> -  <?php print $record->name ?>(<?php print $record->code ?>)</h4>
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
					Page navigation
				</button>
			</div>

			<div class="navbar-collapse collapse" id="navbar-second">
				<ul class="nav navbar-nav">
					<li class="nav-item">
						<a href="#profile" class="navbar-nav-link active" data-toggle="tab" ng-click="loadLessons()">
							<i class="icon-reading mr-2"></i>
							Lessons
						</a>
					</li>
					<li class="nav-item">
						<a href="#progress" class="navbar-nav-link" data-toggle="tab" ng-click="loadProgress()">
							<i class="icon-books mr-2"></i>
							Planning &amp; Progress
						</a>
					</li>
					<li class="nav-item">
						<a href="#qbank" class="navbar-nav-link" data-toggle="tab" ng-click="loadQbank()">
							<i class="icon-archive mr-2"></i>
							Question Bank
						</a>
					</li> 
					<li class="nav-item">
						<a href="#home-work" class="navbar-nav-link" data-toggle="tab" ng-click="loadHomework()">
							<i class="icon-home mr-2"></i>
							Home Work
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
					<!-- List table -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Subject Lessons
							<span class="d-block font-size-base text-muted">Lesson helps organizing the subject content. This may be a chapter of the book. You should create all the lessons for this subject. So that you could manage other activities of this book. This also helps manageing the progress tracking. This is one time process for the course of subject life.
							</span>
							</h5>
							<button class="btn btn-success btn-sm float-right" <?php print $this->MODAL_OPTIONS;?> data-target="#add-lesson" >
								<span class="font-weight-bold"> <i class="icon-plus-circle2"></i> New Lesson</span> 
							</button>
						</div>
						<div class="card-body">
							<div class="row">	
								<div class="col-sm-2">
									<select class="form-control select form-control-lg" ng-model="filter.chapter" ng-change="loadLessons()" data-fouc>
									<option value="">Any Chapter</option>
									<?php 
									for($i=0; $i<$record->chapters; $i++){?>            
									    <option value="<?=$i+1;?>" />Chapter <?php print $i+1;?>
								    <?php }?>
									</select>
								</div>							
								<div class="col-sm-10">
									<div class="input-group mb-3">
										<div class="form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadLessons()">
											<div class="form-control-feedback form-control-feedback-lg">
												<i class="icon-search4 text-muted"></i>
											</div>
										</div>

										<div class="input-group-append">
											<button ng-click="loadLessons()" class="btn btn-success btn-lg">
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
									<th class="font-weight-bold">Chapter</th>
									<th class="font-weight-bold">Description</th>
									<th class="text-center text-muted" style="width: 30px;"><i class="icon-checkmark3"></i></th>
					            </tr>
							</thead>
							<tbody>

								<tr ng-repeat="row in responseData.rows">
									<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
				  					<td style="min-width:15%;">{{row.name}}</td>   
									<td>{{row.chapter_number}}</td>
				  					<td>{{row.description}}</td>
									<td class="text-center">
										<div class="list-icons">
											<div class="list-icons-item dropdown">
												<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
												<div class="dropdown-menu dropdown-menu-right">	
													<a class="dropdown-item"  <?php print $this->MODAL_OPTIONS;?> data-target="#edit-lesson" ng-click="selectRow(row)">
														<i class="icon-compose"></i> Update Lesson
													</a>
													<!-- <div class="dropdown-divider"></div> -->
													<!-- <a class="dropdown-item" ng-click="delLesson(row)"><i class="icon-cross"></i> Remove Lesson</a> -->
												</div>
											</div>
										</div>
									</td>
					            </tr>


							</tbody>
						</table>
						<br><br><br>
						<div>
						<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold" ng-click="moveBack();loadLessons()">
						<i class="icon-arrow-left52"></i> Back Page</button>
						<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadLessons()">
						 Next Page <i class="icon-arrow-right6"></i></button>
						<br><br><br>
						</div>

						</div>
					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='progress' ? 'active show': '';?>" id="progress">
					
					<!-- List table -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Planning &amp; Progress
							<span class="d-block font-size-base text-muted">Organization has planned to cover up the course as per following schedule. You need to be synchronous with plan.
							</span>
							</h5>
						</div>
						<div class="table-responsive">
						<table class="table tasks-responsive table-lg">
							<thead>
								<tr>
									<th class="font-weight-bold">#</th>
									<th class="font-weight-bold">Lesson</th>
									<th class="font-weight-bold">Chapter</th>
									<th class="font-weight-bold">Status</th>
									<th class="text-center text-muted" style="width: 30px;"><i class="icon-checkmark3"></i></th>
					            </tr>
							</thead>
							<tbody>

								<tr ng-repeat="row in responseData.rows">
									<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
				  					<td style="min-width:30%;">{{row.lesson}}</td>   
									<td>{{row.chapter}}</td>
				  					<td>
					                	<div class="btn-group">
											<a ng-show="row.status==='<?php print $this->class_subject_progress_m->STATUS_COMPLETED;?>'" class="badge bg-success dropdown-toggle mouse-pointer" data-toggle="dropdown">Completed on {{row.complete_date}}</a>
											<a ng-show="row.status==='<?php print $this->class_subject_progress_m->STATUS_PENDING;?>'" class="badge bg-warning dropdown-toggle mouse-pointer" data-toggle="dropdown">Planned on {{row.start_date}}</a>
											<a ng-show="row.status==='<?php print $this->class_subject_progress_m->STATUS_READING;?>'" class="badge bg-info dropdown-toggle mouse-pointer" data-toggle="dropdown">Inprogress / Reading </a>
											
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item mouse-pointer" ng-show="row.status!=='<?php print $this->class_subject_progress_m->STATUS_COMPLETED;?>'" ng-click="changeStatus(row, '<?php print $this->class_subject_progress_m->STATUS_COMPLETED;?>');"><span class="badge badge-mark mr-2 bg-success border-success"></span> Mark Completed</a>
												<a class="dropdown-item mouse-pointer" ng-show="row.status!=='<?php print $this->class_subject_progress_m->STATUS_PENDING;?>'" ng-click="changeStatus(row, '<?php print $this->class_subject_progress_m->STATUS_PENDING;?>');"><span class="badge badge-mark mr-2 bg-warning border-warning"></span> Mark Pending</a>
												<a class="dropdown-item mouse-pointer" ng-show="row.status!=='<?php print $this->class_subject_progress_m->STATUS_READING;?>'" ng-click="changeStatus(row, '<?php print $this->class_subject_progress_m->STATUS_READING;?>');"><span class="badge badge-mark mr-2 bg-info border-info"></span> Mark Reading</a>

											</div>
										</div>
									</td>
									<td class="text-center">

										<!-- <a class="mouse-pointer" ng-click="delProgress(row)"><i class="icon-cross text-danger"></i></a> -->
										
									</td>
					            </tr>


							</tbody>
						</table>
						<br><br><br>
						<div>
						<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold" ng-click="moveBack();loadProgress()">
						<i class="icon-arrow-left52"></i> Back Page</button>
						<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadProgress()">
						 Next Page <i class="icon-arrow-right6"></i></button>
						<br><br><br>
						</div>

						</div>
					</div>
					<!-- /list table -->
		    	</div>
		    	<div class="tab-pane fade <?php print $tab=='qbank' ? 'active show': '';?>" id="qbank">					
					<!-- List table -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Question Bank
							<span class="d-block font-size-base text-muted">Question bank help in generating questions papers on the fly. It insures the random paper creation every time. You can also create printed question papers for random / surprize test any time. 
							</span>
							</h5>
							<button class="btn btn-success btn-sm float-right" <?php print $this->MODAL_OPTIONS;?> data-target="#add-question" >
								<span class="font-weight-bold"> <i class="icon-plus-circle2"></i> New Question</span> 
							</button>
						</div>
						<div class="card-body">
							<div class="row">							
								<div class="col-sm-12">
									<div class="input-group mb-3">
										<div class="form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadQbank()">
											<div class="form-control-feedback form-control-feedback-lg">
												<i class="icon-search4 text-muted"></i>
											</div>
										</div>

										<div class="input-group-append">
											<button ng-click="loadQbank()" class="btn btn-success btn-lg">
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
									<th class="font-weight-bold">Lesson</th>
									<th class="font-weight-bold">Chapter</th>
									<th class="font-weight-bold">Question</th>
									<th class="font-weight-bold">Marks</th>
									<th class="font-weight-bold">Type</th>
									<th class="text-center text-muted" style="width: 30px;"><i class="icon-checkmark3"></i></th>
					            </tr>
							</thead>
							<tbody>
								<tr ng-repeat="row in responseData.rows">
									<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
				  					<td style="min-width:20%;">{{row.lesson}}</td>   
									<td>{{row.chapter}}</td>
				  					<td>{{row.question}}</td>
				  					<td>{{row.marks}}</td>
				  					<td>{{row.type}}</td>
									<td class="text-center">
										<div class="list-icons">
											<div class="list-icons-item dropdown">
												<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
												<div class="dropdown-menu dropdown-menu-right">	
													<a class="dropdown-item"  <?php print $this->MODAL_OPTIONS;?> data-target="#edit-question" ng-click="selectRow(row)">
														<i class="icon-compose"></i> Update
													</a>
													<!-- <div class="dropdown-divider"></div> -->
													<!-- <a class="dropdown-item" ng-click="delQbank(row)"><i class="icon-cross"></i> Remove</a> -->
												</div>
											</div>
										</div>
									</td>
					            </tr>
							</tbody>
						</table>
						<br><br><br>
						<div>
						<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold" ng-click="moveBack();loadQbank()">
						<i class="icon-arrow-left52"></i> Back Page</button>
						<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadQbank()">
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
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Home work
							<span class="d-block font-size-base text-muted">Home work for this subject assigned to students.
							</span>
							</h5>
							<button class="btn btn-success btn-sm float-right" <?php print $this->MODAL_OPTIONS;?> data-target="#add-homework" >
								<span class="font-weight-bold"> <i class="icon-plus-circle2"></i> Assign Home Work</span> 
							</button>
						</div>
						<div class="card-body">
							<div class="row">							
								<div class="col-sm-12">
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
<div id="add-lesson" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Register New Lesson For Subject(<?php print $record->name ?>)</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you register new lesson for this subject. Later, this can be used in course tracking and related tasks.</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Lesson Information</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>

						<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label class="text-muted">Chapter <span class="text-danger"> * </span></label>									
									<select class="form-control select form-control-lg" ng-model="entry.chapter" data-fouc>
									<option value="">Any Chapter</option>
									<?php 
									for($i=0; $i<$record->chapters; $i++){?>            
									    <option value="<?=$i+1;?>" />Chapter <?php print $i+1;?>
								    <?php }?>
									</select>
								</div>
								<div class="col-sm-9">
									<label class="text-muted">Lesson Title <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g Introduction" ng-model="entry.name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-10">
									<label class="text-muted">Description </label>
									<textarea rows="4" cols="3" class="form-control" placeholder="About Lesson" ng-model="entry.description"></textarea>
								</div>							
							</div>
						</div>

					</div>
				</div>




			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="saveLesson()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /add modal -->

<!-- edit modal -->
<div id="edit-lesson" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update Lesson information</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you update lesson information for this subject.</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Lesson Information</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>

						<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label class="text-muted">Chapter <span class="text-danger"> * </span></label>									
									<select class="form-control select form-control-lg" ng-model="selectedRow.chapter_number" data-fouc>
									<!-- <option value="">Any Chapter</option> -->
									<?php 
									for($i=0; $i<$record->chapters; $i++){?>            
									    <option value="<?=$i+1;?>" />Chapter <?php print $i+1;?>
								    <?php }?>
									</select>
								</div>
								<div class="col-sm-9">
									<label class="text-muted">Lesson Title <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g Introduction" ng-model="selectedRow.name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-10">
									<label class="text-muted">Description </label>
									<textarea rows="4" cols="3" class="form-control" placeholder="About Lesson" ng-model="selectedRow.description"></textarea>
								</div>							
							</div>
						</div>

					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="updateLesson()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /edit modal -->


<!-- add modal -->
<div id="add-question" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Save New Question For Subject(<?php print $record->name ?>)</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you save questions in question bank of this subject. Later, this can be used in exam and related activities.</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Question Details</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>

						<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label class="text-muted">Chapter <span class="text-danger"> * </span></label>									
									<select class="form-control select form-control-lg" ng-model="entry.chapter" ng-change="loadChapterLessons('')" data-fouc>
									<option value="">Select Chapter</option>
									<?php 
									for($i=0; $i<$record->chapters; $i++){?>            
									    <option value="<?=$i+1;?>" />Chapter <?php print $i+1;?>
								    <?php }?>
									</select>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Lesson <span class="text-danger"> * </span></label>									
									<select class="form-control select form-control-lg" ng-model="entry.lesson_id" data-fouc>
									<option value="">Select Lesson</option>
									<option ng-repeat="row in chapterLessons.rows" value="{{row.mid}}">{{row.name}}</option>
									</select>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Category <span class="text-danger"> * </span></label>									
									<select class="form-control select form-control-lg" ng-model="question.type" data-fouc>
									<option value="">Select Category</option>
									<option value="<?php print $this->class_subject_qbank_m->TYPE_MCQ;?>">MCQ</option>
									<option value="<?php print $this->class_subject_qbank_m->TYPE_BOOLEAN;?>">True &amp; False</option>
									<option value="<?php print $this->class_subject_qbank_m->TYPE_SHORT;?>">Short Question</option>
									<option value="<?php print $this->class_subject_qbank_m->TYPE_LONG;?>">Long Question</option>
									</select>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Question Marks<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control" placeholder="E.g 1" ng-model="question.marks">
										<div class="form-control-feedback">
											<i class="icon-calculator"></i>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-10">
									<label class="text-muted">Question </label>
									<textarea rows="2" class="form-control" placeholder="Write here question" ng-model="question.question"></textarea>
								</div>							
							</div>
						</div>
						<div class="form-group" ng-show="question.type==='<?php print  $this->class_subject_qbank_m->TYPE_MCQ;?>'">
							<div class="row">
								<div class="col-sm-3">
									<label class="text-muted">Option 1<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Write option 1..." ng-model="question.option1" ng-class="{'border-success text-success font-weight-bold':question.answer=='option1'}">
										<div class="form-control-feedback form-control-feedback-lg" ng-class="{'text-success':question.answer=='option1'}">
											<i class="icon-seven-segment-1" ></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Option 2<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Write option 2..." ng-model="question.option2" ng-class="{'border-success text-success font-weight-bold':question.answer=='option2'}">
										<div class="form-control-feedback form-control-feedback-lg" ng-class="{'text-success':question.answer=='option2'}">
											<i class="icon-seven-segment-2"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Option 3<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Write option 3..." ng-model="question.option3" ng-class="{'border-success text-success font-weight-bold':question.answer=='option3'}">
										<div class="form-control-feedback form-control-feedback-lg" ng-class="{'text-success':question.answer=='option3'}">
											<i class="icon-seven-segment-3"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Option 4<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Write option 4..." ng-model="question.option4" ng-class="{'border-success text-success font-weight-bold':question.answer=='option4'}">
										<div class="form-control-feedback form-control-feedback-lg" ng-class="{'text-success':question.answer=='option4'}">
											<i class="icon-seven-segment-4"></i>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-8">
									<label class="text-muted">Hint / Reference </label>
									<textarea rows="2" class="form-control" placeholder="Write here hint or reference for students" ng-model="question.detail"></textarea>
								</div>	
								<div class="col-sm-4" ng-show="question.type==='<?php print  $this->class_subject_qbank_m->TYPE_MCQ;?>'">
									<label class="text-muted">Answer <span class="text-danger"> * </span></label>									
									<select class="form-control select form-control-lg" ng-model="question.answer" data-fouc>
									<option value="">Select Answer</option>										
									<option value="option1" ng-show="question.type==='<?php print  $this->class_subject_qbank_m->TYPE_MCQ;?>'"/>Option 1
									<option value="option2" ng-show="question.type==='<?php print  $this->class_subject_qbank_m->TYPE_MCQ;?>'"/>Option 2
									<option value="option3" ng-show="question.type==='<?php print  $this->class_subject_qbank_m->TYPE_MCQ;?>'"/>Option 3
									<option value="option4" ng-show="question.type==='<?php print  $this->class_subject_qbank_m->TYPE_MCQ;?>'"/>Option 4
									</select>
								</div>	
								<div class="col-sm-4" ng-show="question.type==='<?php print  $this->class_subject_qbank_m->TYPE_BOOLEAN;?>'">
									<label class="text-muted">Answer <span class="text-danger"> * </span></label>									
									<select class="form-control select form-control-lg" ng-model="question.answer" data-fouc>
									<option value="">Select Answer</option>		
									<option value="option1" ng-hide="question.type==='<?php print  $this->class_subject_qbank_m->TYPE_MCQ;?>'"/>TRUE
									<option value="option2" ng-hide="question.type==='<?php print  $this->class_subject_qbank_m->TYPE_MCQ;?>'"/>FALSE
									</select>
								</div>					
							</div>
						</div>

					</div>
				</div>




			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="saveQbank()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /add modal -->

<!-- edit modal -->
<div id="edit-question" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update Question For Subject(<?php print $record->name ?>)</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you save questions in question bank of this subject. Later, this can be used in exam and related activities.</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Question Details</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>

						<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-12">
									<label class="text-muted">Question </label>
									<textarea rows="2" class="form-control" placeholder="Write here question" ng-model="selectedRow.question"></textarea>
								</div>							
							</div>
						</div>
						<div class="form-group" ng-show="selectedRow.type==='<?php print  $this->class_subject_qbank_m->TYPE_MCQ;?>'">
							<div class="row">
								<div class="col-sm-3">
									<label class="text-muted">Option 1<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Write option 1..." ng-model="selectedRow.option1" ng-class="{'border-success text-success font-weight-bold':selectedRow.answer=='option1'}">
										<div class="form-control-feedback form-control-feedback-lg" ng-class="{'text-success':selectedRow.answer=='option1'}">
											<i class="icon-seven-segment-1" ></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Option 2<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Write option 2..." ng-model="selectedRow.option2" ng-class="{'border-success text-success font-weight-bold':selectedRow.answer=='option2'}">
										<div class="form-control-feedback form-control-feedback-lg" ng-class="{'text-success':selectedRow.answer=='option2'}">
											<i class="icon-seven-segment-2"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Option 3<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Write option 3..." ng-model="selectedRow.option3" ng-class="{'border-success text-success font-weight-bold':selectedRow.answer=='option3'}">
										<div class="form-control-feedback form-control-feedback-lg" ng-class="{'text-success':selectedRow.answer=='option3'}">
											<i class="icon-seven-segment-3"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Option 4<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Write option 4..." ng-model="selectedRow.option4" ng-class="{'border-success text-success font-weight-bold':selectedRow.answer=='option4'}">
										<div class="form-control-feedback form-control-feedback-lg" ng-class="{'text-success':selectedRow.answer=='option4'}">
											<i class="icon-seven-segment-4"></i>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-8">
									<label class="text-muted">Hint / Reference </label>
									<textarea rows="2" class="form-control" placeholder="Write here hint or reference for students" ng-model="selectedRow.detail"></textarea>
								</div>	
								<div class="col-sm-4" ng-show="selectedRow.type==='<?php print  $this->class_subject_qbank_m->TYPE_MCQ;?>'">
									<label class="text-muted">Answer <span class="text-danger"> * </span></label>									
									<select class="form-control select form-control-lg" ng-model="selectedRow.answer" data-fouc>
									<option value="option1" ng-show="selectedRow.type==='<?php print  $this->class_subject_qbank_m->TYPE_MCQ;?>'"/>Option 1
									<option value="option2" ng-show="selectedRow.type==='<?php print  $this->class_subject_qbank_m->TYPE_MCQ;?>'"/>Option 2
									<option value="option3" ng-show="selectedRow.type==='<?php print  $this->class_subject_qbank_m->TYPE_MCQ;?>'"/>Option 3
									<option value="option4" ng-show="selectedRow.type==='<?php print  $this->class_subject_qbank_m->TYPE_MCQ;?>'"/>Option 4
									</select>
								</div>	
								<div class="col-sm-4" ng-show="selectedRow.type==='<?php print  $this->class_subject_qbank_m->TYPE_BOOLEAN;?>'">
									<label class="text-muted">Answer <span class="text-danger"> * </span></label>									
									<select class="form-control select form-control-lg" ng-model="selectedRow.answer" data-fouc>
									<option value="option1" ng-hide="selectedRow.type==='<?php print  $this->class_subject_qbank_m->TYPE_MCQ;?>'"/>TRUE
									<option value="option2" ng-hide="selectedRow.type==='<?php print  $this->class_subject_qbank_m->TYPE_MCQ;?>'"/>FALSE
									</select>
								</div>					
							</div>
						</div>

					</div>
				</div>




			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="updateQbank()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /edit modal -->


<!-- edit modal -->
<div id="add-homework" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Assign homework of this subject</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let assign homework to your class online. If you are dealing with any section please select section. On the other hand, homework will be assigned to whole class.</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Information</h6>
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
									<label class="text-muted">Section</label>
									<select class="form-control select form-control-lg" ng-model="entry.section" data-fouc>
									<option value="">Select Section</option>
									<?php foreach ($sections as $row){?>            
									    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['name']);?>
								    <?php }?>
									</select>
								</div>

							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-10">
									<label class="text-muted">Homework </label>
									<textarea rows="4" cols="3" class="form-control" placeholder="Homework..." ng-model="entry.description"></textarea>
								</div>							
							</div>
						</div>

					</div>
				</div>




			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="saveHomework()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /edit modal -->




</div>
<!-- /main content -->
<!-- ********************************************************************** -->
<!-- ///////////////////////////////SCRIPTS//////////////////////////////// -->
<!-- ********************************************************************** -->
<!-- <script type="text/javascript">
	
$(function(){
   
});
</script> -->