<?php
$sections=$this->class_section_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'name ASC') );
$classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID,'incharge_id'=>$this->LOGIN_USER->mid),array('orderby'=>'display_order ASC') );
$sessions=$this->session_m->get_rows(array(),array('orderby'=>'status ASC,mid DESC') );

?>
<!-- Main content -->
<div class="content-wrapper" ng-controller="mozzCtrl"  ng-cloak>

<!-- Page header -->
<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Attendace</span> - Student Attendance</h4>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a><br>			
		</div>

		<div class="header-elements d-none">
			<!-- <div class="d-flex justify-content-center">
				<a href="#" class="btn btn-link btn-float text-default"><i class="icon-bars-alt text-primary"></i><span>Statistics</span></a>
				<a href="#" class="btn btn-link btn-float text-default"><i class="icon-calculator text-primary"></i> <span>Invoices</span></a>
				<a href="#" class="btn btn-link btn-float text-default"><i class="icon-calendar5 text-primary"></i> <span>Schedule</span></a>
			</div> -->
		</div>
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php print $this->LIB_CONT_ROOT;?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<span class="breadcrumb-item active">Student Attendance</span>
			</div>
		</div>
	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<!-- <div class="d-flex">
			<div class="breadcrumb justify-content-center">				
				<a href="<?php print $this->LIB_CONT_ROOT;?>attendance/student" class="breadcrumb-elements-item">
					<i class="icon-users2 mr-2"></i> Students</a>
				<a href="" class="breadcrumb-elements-item" data-target="#add" <?php print $this->MODAL_OPTIONS;?>>
					<i class="icon-envelope mr-2"></i> Send SMS
				</a>
			</div>

			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div> -->

		<div class="header-elements d-none">
			<div class="breadcrumb justify-content-center">

				<!-- <div class="breadcrumb-elements-item dropdown p-0">
					<a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
						<i class="icon-printer mr-2"></i>Print
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a href="<?php print $this->CONT_ROOT.'printing/list/?status=present&role=student';?>" class="dropdown-item">
							<i class="icon-eye"></i> Today Present List</a>
						<a href="<?php print $this->CONT_ROOT.'printing/list/?status=absent&role=student';?>" class="dropdown-item">
							<i class="icon-eye-blocked"></i> Today Absent List</a>
						<a href="<?php print $this->CONT_ROOT.'printing/list/?status=leave&role=student';?>" class="dropdown-item">
							<i class="icon-eye-blocked"></i> Today Leave List</a>
					</div>
				</div> -->
			</div>
		</div>
	</div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">


	<?php $this->load->view($this->LIB_VIEW_DIR.'includes/alert_inc');?>
	<!-- Search field -->
	<div class="card search-area" >
		<div class="card-body">
			<h5 class="mb-3">Load Students</h5>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2" ng-show="!filter.isHoliday">
							<label class="text-muted">Select Class</label>
							<select class="form-control select" ng-model="filter.class_id" data-fouc>
							<option value="">Select Class</option>
							<?php foreach ($classes as $row){?>            
							    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
						    <?php }?>
							</select>
						</div>
						<div class="col-sm-2" ng-show="!filter.isHoliday">
							<label class="text-muted">Select Section</label>
							<select class="form-control select" ng-model="filter.section" data-fouc>
							<option value="">Select Section</option>
							<?php foreach ($sections as $row){?>            
							    <option value="<?=$row['name'];?>" /><?php print strtoupper($row['name']);?>
						    <?php }?>
							</select>
						</div>
						<div class="col-sm-6" ng-show="!filter.isHoliday">
							<label class="text-muted">Choose date to mark attendance</label>
							<div class="input-group mb-4" ng-init="filter.date='<?php print date('d-M-Y');?>'">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="filter.date" placeholder="Choose date" readonly="readonly">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-calendar text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadStudents()" class="btn btn-success btn-lg">
									<span class="font-weight-bold"> Fetch Students</span>
									<i ng-class="{'icon-spinner2':appConfig.btnClickedSearch, 'spinner':appConfig.btnClickedSearch, 'icon-circle-right2':!appConfig.btnClickedSearch}" class=" ml-2"></i>
									</button>
								</div>
							</div>
						</div>


					</div>
				</div>

				
		</div>
	</div>
	<!-- /search field -->

	<!-- List table -->
	<div class="card list-area" ng-show="filter.date.length>0 && filter.class_id.length>0 && responseData.rows.length>0" >
		<div class="card-header bg-transparent">
			<h4 class="card-title">Students List </h4>
			<span class="text-muted">Below is the list of students for selected classs. You are going to mark class attendance for {{filter.date}}.</span>
		</div>
		<div class="table-responsive">
		<table class="table tasks-responsive table-lg">
			<thead>
				<tr>
					<th class="font-weight-bold">#</th>
					<th class="font-weight-bold">Name</th>
					<th class="font-weight-bold">Section</th>
					<th class="font-weight-bold">Roll Number</th>
					<!-- <th class="font-weight-bold">Status</th> -->
					<th class="text-center text-muted" style="width: 30px;">
						<div class="btn-group btn-lg">
							<a class="mouse-pointer badge bg-info dropdown-toggle" data-toggle="dropdown">
								Mark Class Attendance</a>
							
							<div class="dropdown-menu dropdown-menu-right">
								<a class="mouse-pointer dropdown-item" ng-click="markClassAttendance('<?php print $this->std_attendance_m->LABEL_PRESENT;?>');">
									<span class="badge badge-mark mr-2 bg-success border-success"></span>All Present</a>
								<a class="mouse-pointer dropdown-item" ng-click="markClassAttendance('<?php print $this->std_attendance_m->LABEL_ABSENT;?>');"><span class="badge badge-mark mr-2 bg-danger border-danger"></span>All Absent</a>
								<a class="mouse-pointer dropdown-item" ng-click="markClassAttendance('<?php print $this->std_attendance_m->LABEL_HOLIDAY;?>');"><span class="badge badge-mark mr-2 bg-warning border-warning"></span> Holiday</a>

							</div>
						</div>
					</th>
	            </tr>
			</thead>
			<tbody>

				<tr ng-repeat="row in responseData.rows">
					<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
	                <td>
	                	<div><a href class="font-weight-semibold">
						<img ng-src="<?php print $this->UPLOADS_ROOT;?>images/student/profile/{{row.image}}" class="rounded-circle m-1" width="32" height="32" alt="">
                		{{row.name}}
						</a></div>
					</td> 
  					<td>{{row.section}}</td> 
					<td>{{row.roll_no}}</td>
  					<!-- <td>{{row.attendance}}</td>  -->
	                <td class="mr-4">
	                	<div class="btn-group btn-lg">
							<a ng-show="row.attendance===''" class="mouse-pointer badge bg-info dropdown-toggle" data-toggle="dropdown">
								Mark Attendance</a>
							<a ng-show="row.attendance==='<?php print $this->std_attendance_m->LABEL_PRESENT;?>'" class="mouse-pointer badge bg-success dropdown-toggle" data-toggle="dropdown">
								Present</a>
							<a ng-show="row.attendance==='<?php print $this->std_attendance_m->LABEL_ABSENT;?>'" class="mouse-pointer badge bg-danger dropdown-toggle" data-toggle="dropdown">
								Absent</a>
							<a ng-show="row.attendance==='<?php print $this->std_attendance_m->LABEL_HOLIDAY;?>'" class="mouse-pointer badge bg-warning dropdown-toggle" data-toggle="dropdown">	Holiday</a>
							<a ng-show="row.attendance==='<?php print $this->std_attendance_m->LABEL_LEAVE;?>'" class="mouse-pointer badge bg-primary dropdown-toggle" data-toggle="dropdown">
								On Leave</a>
							
							<div class="dropdown-menu dropdown-menu-right">
								<a class="mouse-pointer dropdown-item" ng-show="row.attendance!=='<?php print $this->std_attendance_m->LABEL_PRESENT;?>'" ng-click="markStudentAttendance(row, '<?php print $this->std_attendance_m->LABEL_PRESENT;?>');"><span class="badge badge-mark mr-2 bg-success border-success"></span> Present</a>
								<a class="mouse-pointer dropdown-item" ng-show="row.attendance!=='<?php print $this->std_attendance_m->LABEL_ABSENT;?>'" ng-click="markStudentAttendance(row, '<?php print $this->std_attendance_m->LABEL_ABSENT;?>');"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Absent</a>
								<a class="mouse-pointer dropdown-item" ng-show="row.attendance!=='<?php print $this->std_attendance_m->LABEL_LEAVE;?>'" ng-click="markStudentAttendance(row, '<?php print $this->std_attendance_m->LABEL_LEAVE;?>');"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> On Leave</a>

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