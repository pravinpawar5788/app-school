<?php
$sessions=$this->session_m->get_rows(array('status'=>$this->session_m->STATUS_UPCOMING),array('orderby'=>'status ASC,mid DESC') );
$classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'title ASC') );
?>
<!-- Main content -->
<div class="content-wrapper" ng-controller="mozzCtrl"  ng-cloak>
	<!-- Page header -->
	<div class="page-header page-header-light border-bottom-0">

		<!-- Top breadcrumb line -->
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Settings</a>
					<a class="breadcrumb-item">Campus</a>
					<span class="breadcrumb-item active">Students Promotion</span>
				</div>

				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="breadcrumb justify-content-center">
					<a href="#" class="breadcrumb-elements-item sidebar-control sidebar-component-toggle d-none d-md-block">
						<i class="icon-drag-right mr-2"></i>Toggle Sidebar
					</a>

				</div>
			</div>
		</div>
		<!-- /breadcrumb line -->

	
		<!-- Page header content -->
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Settings</span> - Students Promotion</h4>
				<a class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="d-flex justify-content-center">
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
						<a href="#profile" class="navbar-nav-link active" data-toggle="tab" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-profile mr-2" style="color:<?php print $clr;?>;"></i>
							Students Promotion
						</a>
					</li>
					<li class="nav-item">
						<a href="#students" class="navbar-nav-link sidebar-control sidebar-component-toggle d-none d-md-block" data-toggle="tab" ng-click="loadStudents()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-graduation2 mr-2" style="color:<?php print $clr;?>;"></i>
							Students (Pass/Fail) Status
						</a>
					</li>
				</ul>

				<ul class="nav navbar-nav ml-lg-auto">
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
			    	
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Students Promotion
							<span class="d-block font-size-base text-muted">Students promotion settings allow you to promote students automatically on end of session. Promotion policy is according to subjects passing criteria. Any student failed to fulfill the promotion criteria will remain in same class. 
							</span>
							</h5>
						</div>

						<div class="card-body">
							<p class="text-danger"> Check all the actions to promote students to next classes.</p>
							<!-- <hr> -->
							<div class="row">
	        					<div class="col-md-12">
									<div class="form-group pt-2">
										<div class="form-check">
											<label class="form-check-label">
												<input type="checkbox" class="form-check-input" ng-model="checks.class_policy">
												I have reviewed the class promotion policy in classes menu.
											</label>
										</div>
										<div class="form-check">
											<label class="form-check-label">
												<input type="checkbox" class="form-check-input"  ng-model="checks.subject_policy">
												I have reviewed the passing percentage for all subjects.
											</label>
										</div>
										<div class="form-check">
											<label class="form-check-label">
												<input type="checkbox" class="form-check-input" ng-model="checks.student_list">
												I have reviewed the students (pass/fail) status.
											</label>
										</div>

									</div>

	        					</div>
	    					</div>

							<div class="form-group">
								<hr>
								<div class="row">
									<div class="col-md-6">
										<label class="text-muted">Next Session</label>
										<select class="form-control select" ng-model="entry.session_id" data-fouc>
										<option value="">Select Next Session</option>
										<?php foreach ($sessions as $row){?>            
										    <option value="<?php print $row['mid'];?>" /><?php print ucwords($row['title']);?>
									    <?php }?>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group" ng-show="checks.student_list && checks.class_policy && checks.subject_policy">
								<div class="row">
									<div class="col-md-12">
										<button ng-click="promoteStudents()" class="btn btn-success btn-lg float-right">
											<span class="font-weight-bold"> Promote Students</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-checkmark-circle2':!appConfig.btnClickedSave}" class="ml-2"></i>
										</button>
									</div>
								</div>
							</div>

						</div>
					</div>
					<!-- /save info -->
		    	</div>


			    <div class="tab-pane fade <?php print $tab=='students' ? 'active show': '';?>" id="students">
					
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Students Promotion Status</h4>
							<span class="text-muted">Find below the students pass/fail status. You may pass the failed students inorder to force system promote them to next class.</span>
							<button ng-click="createResult()" class="btn btn-danger float-right">
								<span class="font-weight-bold"> Update All Students Status</span>
							<i ng-class="{'icon-spinner2':appConfig.btnClickedSearch, 'spinner':appConfig.btnClickedSearch, 'icon-circle-right2':!appConfig.btnClickedSearch}" class=" ml-2"></i>
							</button>
						</div>
						<div class="card-body">
							<div class="row">								
								<div class="col-sm-3">
									<select class="form-control select form-control-lg" ng-model="filter.status" ng-change="loadStudents()" data-fouc>
									<option value="">All Students</option>
									<option value="<?php print $this->student_m->STATUS_PASS;?>">Passed Students</option>
									<option value="<?php print $this->student_m->STATUS_FAIL;?>">Failed Failed</option>
									</select>
								</div>								
								<div class="col-sm-3">
									<select class="form-control select" ng-model="filter.class_id" ng-change="loadStudents()" data-fouc>
									<option value="">All Classes</option>
									<?php foreach ($classes as $row){?>            
									    <option value="<?php print $row['mid'];?>" /><?php print strtoupper($row['title']);?>
								    <?php }?>
									</select>
								</div>
								<div class="col-sm-6">
									<div class="input-group mb-3">
										<div class="form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadStudents()">
											<div class="form-control-feedback form-control-feedback-lg">
												<i class="icon-search4 text-muted"></i>
											</div>
										</div>

										<div class="input-group-append">
											<button ng-click="loadStudents()" class="btn btn-success btn-lg">
											<span class="font-weight-bold"> Search</span>
											<i ng-class="{'icon-spinner2':appConfig.btnClickedSearch, 'spinner':appConfig.btnClickedSearch, 'icon-circle-right2':!appConfig.btnClickedSearch}" class=" ml-2"></i>
											</button>
										</div>
									</div>									
								</div>	
							</div>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th ng-click="sortBy('name');" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Name</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='name'"></i> 
										</th>
										<th ng-click="sortBy('father_name');" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Father Name</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='father_name'"></i> 
										</th>
										<th ng-click="sortBy('class_id');" class="mouse-pointer font-weight-bold">
											<span class="m-1">Class</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='class_id'"></i> 
										</th>
										<th ng-click="sortBy('roll_no');" class="mouse-pointer font-weight-bold">
											<span class="m-1">Roll Number</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='roll_no'"></i> 
										</th>
										<th ng-click="sortBy('promotion_status');" class="mouse-pointer font-weight-bold">
											<span class="m-1">Status</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='promotion_status'"></i> 
										</th>
						            </tr>
								</thead>
								<tbody>
									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
						                <td>
						                	<div><a ng-href="<?php print $this->LIB_CONT_ROOT.'exam/resdetail/'?>{{row.mid}}" class="font-weight-semibold" target="_blank">
											<img ng-src="<?php print $this->UPLOADS_ROOT;?>images/student/profile/{{row.image}}" class="rounded-circle m-1" width="32" height="32" alt="">
					                		{{row.name}}
											</a></div>
										</td> 
										<td>{{row.father_name}}</td>
					  					<td>{{row.class}}</td>   
					  					<td>{{row.roll_no}}</td>   
						                <td>
						                	<div class="btn-group" style="font-size: 16px;">
												<a ng-show="row.promotion_status==='<?php print $this->student_m->STATUS_PASS;?>'" class="badge bg-success mouse-pointer">Passed with {{row.performance}}% marks &amp; cleared {{row.passed_subjects}} of {{row.total_subjects}} subjects</a>
												<a ng-show="row.promotion_status==='<?php print $this->student_m->STATUS_FAIL;?>'" class="badge bg-danger dropdown-toggle mouse-pointer" data-toggle="dropdown">Failed with {{row.performance}}% marks &amp; cleared {{row.passed_subjects}} of {{row.total_subjects}} subjects</a>
												<a ng-show="row.promotion_status!=='<?php print $this->student_m->STATUS_PASS;?>' && row.promotion_status!=='<?php print $this->student_m->STATUS_FAIL;?>'" class="badge bg-info dropdown-toggle mouse-pointer" data-toggle="dropdown">Not Created</a>
												
												<?php if($this->LOGIN_USER->prm_std_info>1){?>
												<div class="dropdown-menu dropdown-menu-right">
													<a class="dropdown-item mouse-pointer" ng-show="row.promotion_status!=='<?php print $this->student_m->STATUS_PASS;?>'" ng-click="changeStatus(row, '<?php print $this->student_m->STATUS_PASS;?>');"><span class="badge badge-mark mr-2 bg-success border-success"></span> Mark Passed</a>
													<a class="dropdown-item mouse-pointer" ng-show="row.promotion_status!=='<?php print $this->student_m->STATUS_FAIL;?>'" ng-click="changeStatus(row, '<?php print $this->student_m->STATUS_FAIL;?>');"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Mark Failed</a>

												</div>
												<?php } ?>
											</div>
					                	</td>
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadStudents()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadStudents()">
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
								<img class="img-fluid rounded-circle" src="<?php print $this->UPLOADS_ROOT.'images/logo/'.$this->SETTINGS[$this->system_setting_m->_ORG_LOGO];?>" width="170" height="170" alt="">
							</div>
								
				    		<br>
				    		<h6 class="font-weight-semibold mb-0"><?php print ucwords(strtolower($this->CAMPUS->name));?></h6>
				    		<span class="d-block text-muted"><span class="m-2"></span><?php print ucwords(strtolower($this->CAMPUS->contact_number));?></span>
				    		<span class="d-block text-muted"><span class="m-2"></span><?php print ucwords(strtolower($this->SETTINGS[$this->system_setting_m->_ORG_NAME]));?></span>

				    	</div>
			    	</div>
			    	<!-- /user card -->

						
					

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




</div>
<!-- /main content -->