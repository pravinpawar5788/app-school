<?php

$teacher=$this->stf_role_m->get_by(array('title'=>'teacher',));
$faculty=$this->staff_m->get_rows(array('role_id'=>$teacher->mid,'campus_id'=>$this->CAMPUSID));
$periods=$this->period_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'sort_order ASC'));
$classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'display_order ASC'));
$subjects=$this->class_subject_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'display_order ASC'));

$sections=$this->class_section_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'name ASC') );
// $classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'title ASC') );
// $sessions=$this->session_m->get_rows(array(),array('orderby'=>'status ASC,mid DESC') );
// $classes_array=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
// $sessions_array=$this->session_m->get_values_array('mid','title',array());

?>
<!-- Main content -->
<div class="content-wrapper" ng-controller="mozzCtrl"  ng-cloak>
	<!-- Page header -->
	<div class="page-header page-header-light border-bottom-0">

		<!-- Top breadcrumb line -->
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
					<a class="breadcrumb-item">Class</a>
					<span class="breadcrumb-item active">Timetable</span>
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
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Classes </span> - Timetable</h4>
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
						<a href="#profile" class="navbar-nav-link <?php print empty($tab) || $tab=='profile' ? 'active': '';?>" data-toggle="tab" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;" ng-click="loadTimetable()">
							<i class="icon-alarm mr-2" style="color:<?php print $clr;?>;"></i>
							Timetable
						</a>
					</li>
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
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Class Timetable
							<span class="d-block font-size-base text-muted">Find below class the class timetable. You may update the class timetable below. Manage study periods in campus settings menu.
							</span>
							</h5>
							<?php if($this->LOGIN_USER->prm_class>1){?>
							<button class="btn btn-success btn-sm float-right" <?php print $this->MODAL_OPTIONS;?> data-target="#add-period" >
								<span class="font-weight-bold"> <i class="icon-plus-circle2"></i> Register Period</span> 
							</button>
							<button class="btn btn-info btn-sm float-right" ng-click="toggleTeacher()">
								<span class="font-weight-bold"> <i class="icon-sync"></i> Faculty</span> 
							</button>
							<button class="btn btn-warning btn-sm float-right" ng-click="toggleRemobeable()">
								<span class="font-weight-bold"> <i class="icon-sync"></i> Remove</span> 
							</button>
							<?php } ?>
							<div class="col-sm-3">
								<select class="form-control select" ng-model="filter.class_id" ng-change="loadTimetable()" data-fouc>
								<option value="">Select Class</option>
								<?php foreach ($classes as $row){?>            
								    <option value="<?=$row['mid'];?>" /><?php print ucwords(strtolower($row['title']));?>
							    <?php }?>
								</select>
							</div>
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

			</div>
			<!-- /left content -->


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
									<label class="text-muted">Select Class<span class="text-danger"> * </span></label>
									<select class="form-control select" ng-model="entry.class_id" ng-change="loadClassSubjects()" data-fouc>
									<option value="">Select Class</option>
									<?php foreach ($classes as $row){?>            
									    <option value="<?=$row['mid'];?>" /><?php print ucwords(strtolower($row['title']));?>
								    <?php }?>
									</select>
								</div>
								<div class="col-sm-3" ng-show="classSubjects.length>0">
									<label class="text-muted">Select Subject</label>
									<select class="form-control select" ng-model="entry.subject_id" data-fouc>
									<option value="">Select subject</option>
									<option  ng-repeat="row in classSubjects" value="{{row.mid}}">{{row.name}}</option>
									</select>
								</div>
								<!-- <div class="col-sm-3">
									<label class="text-muted">Select Subject<span class="text-danger"> </span></label>
									<select class="form-control select" ng-model="entry.subject_id" data-fouc>
									<option value="">Select Subject</option>
									<?php foreach ($subjects as $row){?>            
									    <option value="<?=$row['mid'];?>" /><?php print ucwords(strtolower($row['name']));?>
								    <?php }?>
									</select>
								</div> -->
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





</div>
<!-- /main content -->
<!-- ********************************************************************** -->
<!-- ///////////////////////////////SCRIPTS//////////////////////////////// -->
<!-- ********************************************************************** -->
<!-- <script type="text/javascript">
	
$(function(){
   
});
</script> -->