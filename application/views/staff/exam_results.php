<?php
$activeSession=$this->session_m->getActiveSession();
$sections=$this->class_section_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'name ASC') );
$classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'title ASC') );
$sessions=$this->session_m->get_rows(array(),array('orderby'=>'status ASC,mid DESC') );
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
					<a class="breadcrumb-item">Exam</a>
					<span class="breadcrumb-item active">Final Results</span>
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
					<div class="dropdown-menu dropdown-menu-right">
						<a href="<?php print $this->CONT_ROOT.'printing/report/?rpt=clsmarksheet&usr=cls';?>" class="dropdown-item" target="_blank">
							<i class="icon-users"></i>Class Mark Sheets</a>
						<!-- <a href="<?php print $this->CONT_ROOT.'printing/attendance/?pid=';?>" class="dropdown-item"><i class="icon-cash"></i> Attendance Report</a> -->
						<!-- <div class="dropdown-divider"></div> -->
						<!-- <a href="#" class="dropdown-item"><i class="icon-eye"></i> Today Present List</a> -->
						<!-- <a href="#" class="dropdown-item"><i class="icon-eye-blocked"></i> Today Absent List</a> -->
					</div>
					</div>

				</div>
			</div>
		</div>
		<!-- /breadcrumb line -->

	
		<!-- Page header content -->
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Exam</span> - Final Result</h4>
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
						<a href="#profile" class="navbar-nav-link active" data-toggle="tab"  style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-stats-bars2 mr-2" style="color:<?php print $clr;?>;"></i>
							Update Final Result
						</a>
					</li>
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
					<!-- Search field -->
					<div class="card search-area" >
						<div class="card-body">
							<h5 class="mb-3">Load students to update final result
							<span class="d-block font-size-base text-muted">Update here final exam results of students. Please make sure to update this result before session change. Student will be promoted to next class depending on this result.
							</h5>
							</span>
								<div class="form-group">
									<div class="row">
										<div class="col-sm-3" ng-show="!filter.isHoliday">
											<label class="text-muted">Select Class</label>
											<select class="form-control select" ng-model="filter.class_id" ng-change="filterSubjects()" data-fouc>
											<option value="">Select Class</option>
											<?php foreach ($classes as $row){?>            
											    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
										    <?php }?>
											</select>
										</div>
										<div class="col-sm-4">
											<label class="text-muted">Subject</label>
											<select class="form-control select form-control-lg" ng-model="filter.subject_id" data-fouc>
											<option value="">Select Subject</option>
											<option ng-repeat="row in subjects.rows" value="{{row.mid}}">{{row.name+' ('+row.code+')'}}</option>
											</select>
										</div>
										<div class="col-sm-4 mt-2" >
											<button ng-click="loadResult()" class="btn btn-success btn-lg mt-2" >
												<span class="font-weight-bold">Load Students</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
											</button>
										</div>


									</div>
								</div>

								
						</div>
					</div>
					<!-- /search field -->

					<!-- List table -->
					<div class="card list-area" ng-show="filter.subject_id.length>0 && filter.class_id.length>0 && results.rows.length>0" >
						<div class="card-header bg-transparent">
							<h4 class="card-title">Students List 
										<div class="col-sm-3 float-right">
											<div class="form-group form-group-feedback form-group-feedback-left">
												<input type="text" class="form-control" placeholder="Subject Total Marks" ng-model="entry.total_marks" ng-keyup="updateTotalMarks()">
												<div class="form-control-feedback">
													<i class="icon-calculator"></i>
												</div>
											</div>
										</div>
							</h4>
							<span class="text-muted">Below is the list of students of selected classs. You are going to mark class attendance for {{filter.date}}.</span>
						</div>
						<div class="table-responsive">
						<table class="table tasks-responsive table-lg">
							<thead>
								<tr>
									<th class="font-weight-bold">#</th>
									<th class="font-weight-bold">Name</th>
									<th class="font-weight-bold">Father Name</th>
									<th class="font-weight-bold">Roll Number</th>
									<th class="font-weight-bold">Total Marks</th>
									<th class="font-weight-bold">Obtained Marks</th>
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
								<td>{{row.student.father_name}}</td>  
								<td>{{row.student.roll_no}}</td>
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
						<br><br><br>

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
						<div class="col-sm-4">
							<label class="text-muted">Session</label>
							<select class="form-control select" ng-model="filter.session_id" data-fouc>
							<option value="">Select Session</option>
							<?php foreach ($sessions as $row){?>            
							    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
						    <?php }?>
							</select>
						</div>
						<div class="col-sm-4">
							<label class="text-muted">Class</label>
							<select class="form-control select" ng-model="filter.class_id" ng-change="loadSubjects()" data-fouc>
							<option value="">Select Class</option>
							<?php foreach ($classes as $row){?>            
							    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
						    <?php }?>
							</select>
						</div>
						<div class="col-sm-4">
							<label class="text-muted">Subject</label>
							<select class="form-control select form-control-lg" ng-model="filter.subject_id" data-fouc>
							<option value="">Select Subject</option>
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





</div>
<!-- /main content -->
<!-- ********************************************************************** -->
<!-- ///////////////////////////////SCRIPTS//////////////////////////////// -->
<!-- ********************************************************************** -->
<!-- <script type="text/javascript">
	
$(function(){
   
});
</script> -->