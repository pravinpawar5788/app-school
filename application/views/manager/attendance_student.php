<?php
$sections=$this->class_section_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'name ASC') );
$classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'display_order ASC') );
$sessions=$this->session_m->get_rows(array(),array('orderby'=>'status ASC,mid DESC') );

?>
<!-- Main content -->
<div class="content-wrapper" ng-controller="mozzCtrl"  ng-cloak>

<!-- Page header -->
<div class="page-header page-header-light" ng-init="filter.date='<?php print $this->class_m->date;?>'">
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
		<div class="d-flex">
			<div class="breadcrumb justify-content-center">				
				<a href="<?php print $this->LIB_CONT_ROOT;?>attendance/student" class="breadcrumb-elements-item" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
					<i class="icon-users2 mr-2" style="color:<?php print $clr;?>;"></i> Students</a>
				<?php if($this->LOGIN_USER->prm_std_info>1){?>
				<a <?php print $this->MODAL_OPTIONS;?> data-target="#list-sms" class="breadcrumb-elements-item mouse-pointer" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
					<i class="icon-envelope mr-2" style="color:<?php print $clr;?>;"></i> Send SMS Notification</a>
				<?php } ?>
				<!-- <a href="" class="breadcrumb-elements-item" data-target="#add" <?php print $this->MODAL_OPTIONS;?>>
					<i class="icon-envelope mr-2"></i> Send SMS
				</a> -->
			</div>

			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>

		<div class="header-elements d-none">
			<div class="breadcrumb justify-content-center">

				<div class="breadcrumb-elements-item dropdown p-0">
					<a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
						<i class="icon-printer mr-2"></i>Print
					</a>
					<div class="dropdown-menu dropdown-menu-left">
						<div class="dropdown-submenu dropdown-submenu-left">
							<a href="#" class="dropdown-item"><i class="icon-clipboard6"></i> Reports</a>
							<div class="dropdown-menu">
								<!-- <div class="dropdown-submenu dropdown-submenu-left">
									<a href="#" class="dropdown-item">Attendance Reports</a>
									<div class="dropdown-menu">
										<a href="#" class="dropdown-item">Monthly Attendance Report</a>
									</div>
								</div> -->
								<a href="<?php print $this->CONT_ROOT.'printing/report/?rpt=std_attendance';?>" class="dropdown-item">Daily Attendance Report</a>
								<a href="<?php print $this->CONT_ROOT.'printing/report/?rpt=std_atd_monsimple';?>" class="dropdown-item">Attendance Summary</a>
								<a href="<?php print $this->CONT_ROOT.'printing/report/?rpt=std_atd_mondetail';?>" class="dropdown-item">Attendance Register</a>
								<!-- <a href="<?php print $this->CONT_ROOT.'printing/list/?alumni';?>" class="dropdown-item">Student Strength Report</a> -->
								<!-- <a href="<?php print $this->CONT_ROOT.'printing/list/?alumni';?>" class="dropdown-item">Admissions Report</a> -->
							</div>
						</div>
						<a href="<?php print $this->CONT_ROOT.'printing/list/?status=present&role=student';?>" class="dropdown-item">
							<i class="icon-eye"></i> Today Present List</a>
						<a href="<?php print $this->CONT_ROOT.'printing/list/?status=absent&role=student';?>" class="dropdown-item">
							<i class="icon-eye-blocked"></i> Today Absent List</a>
						<a href="<?php print $this->CONT_ROOT.'printing/list/?status=leave&role=student';?>" class="dropdown-item">
							<i class="icon-eye-blocked"></i> Today Leave List</a>
					</div>
				</div>
				<!-- on each page -->
				
				<a href="<?php print $this->APP_ROOT.'docs/attendance';?>" target="_blank" class="breadcrumb-elements-item">
					<i class="icon-lifebuoy mr-2"></i>Docs</a>
				<a class="breadcrumb-elements-item mouse-pointer" data-popup="popover" data-trigger="hover" data-html="true" data-placement="left" title="View Hotkeys" data-content="You can press <code>?</code> button to view the hotkeys for this module. Remember to press <code>shift</code> key while pressing <code>?</code>key.">
					<i class="icon-help mr-2"></i>Hotkeys
				</a>
				<!-- end on each page -->
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
			<h5 class="mb-3">Fetch Student List</h5>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-3">
							<label class="text-muted" ng-show="filter.isHoliday">Leave for all classes</label>
							<label class="text-muted" ng-show="!filter.isHoliday">Select Class</label>
							<select class="form-control select-search" ng-model="filter.class_id" ng-change="loadClassSections()" data-fouc>
							<option value="">Select Class</option>
							<?php foreach ($classes as $row){?>            
							    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
						    <?php }?>
							</select>
						</div>
						<div class="col-sm-2" ng-show="!filter.isHoliday && classSections.length>0">
							<label class="text-muted">Select Section</label>
							<select class="form-control select" ng-model="filter.section" data-fouc>
							<option value="">All Sections</option>
							<option  ng-repeat="row in classSections" value="{{row.mid}}">{{row.name}}</option>
							</select>
						</div>
						<div class="col-sm-6" ng-show="!filter.isHoliday">
							<label class="text-muted">Choose date to mark attendance</label>
							<div class="input-group mb-4">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-md alpha-grey datepicker" ng-model="filter.date" placeholder="Choose date" value="<?php print date('d-M-Y');?>">
									<div class="form-control-feedback form-control-feedback-md">
										<i class="icon-calendar text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="searchText='';loadStudents()" class="btn btn-success btn-md">
									<span class="font-weight-bold"> Load</span>
									<i ng-class="{'icon-spinner2':appConfig.btnClickedSearch, 'spinner':appConfig.btnClickedSearch, 'icon-circle-right2':!appConfig.btnClickedSearch}" class=" ml-2"></i>
									</button>
								</div>
							</div>
						</div>
						<div class="col-sm-6" ng-show="filter.isHoliday">
							<label class="text-muted">Choose date to mark holiday</label>
							<div class="input-group mb-4">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey datepicker" ng-model="filter.date" placeholder="Choose date">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-calendar text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="markStudentHoliday()" class="btn btn-success btn-lg">
									<span class="font-weight-bold"> Mark Holiday</span>
									<i ng-class="{'icon-spinner2':appConfig.btnClickedSearch, 'spinner':appConfig.btnClickedSearch, 'icon-circle-right2':!appConfig.btnClickedSearch}" class=" ml-2"></i>
									</button>
								</div>
							</div>
						</div>
						<div class="col-sm-1" ng-hide="responseData.rows.length>0">
							<label class="text-muted">Holiday?</label>
							<input type="checkbox" class="form-control" ng-model="filter.isHoliday" />
						</div>
						<div class="col-sm-1" ng-show="filter.class_id.length>0 && !filter.isHoliday && responseData.rows.length>0">
							<label class="text-muted">Search?</label>
							<input type="checkbox" class="form-control" ng-model="filter.isSearch" />
						</div>
					</div>
				</div>				
		</div>
	</div>
	<!-- /search field -->
	<!-- Search params field -->
	<div class="card search-area" ng-show="filter.isSearch">
		<div class="card-body">
			<h5 class="mb-3">Search Students</h5>
				<div class="form-group" ng-show="filter.date.length>0 && filter.class_id.length>0">
					<div class="row">
						<div class="col-sm-3">
							<select class="form-control select" ng-model="filter.filter" data-fouc>
								<option value="">Via Name &amp; Roll Number</option>
								<option value="admission_no">Via Admission Number</option>
								<option value="computer_number">Via Computer Number</option>
								<option value="family_number">Via Family Number</option>
								<option value="student_id">Via Student ID</option>
							</select>
						</div>
						<div class="col-sm-8">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search Student" ng-keyup="loadStudents()">
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
		</div>
	</div>
	<!-- /search params field -->

	<!-- List table -->
	<div class="card list-area" ng-show="filter.date.length>0 && filter.class_id.length>0 && responseData.rows.length>0" >
		<div class="card-header bg-transparent">
			<h4 class="card-title">Students List </h4>
			<span class="text-muted">Below is the list of students of selected classs. You are going to mark class attendance for {{filter.date}}.</span>

			
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
					<th class="text-center text-muted" style="min-width: 100px;">
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
	                	<div><a ng-href="<?php print $this->LIB_CONT_ROOT.'student/profile/'?>{{row.mid}}" class="font-weight-semibold">
						<img ng-src="<?php print $this->UPLOADS_ROOT;?>images/student/profile/{{row.image}}" class="rounded-circle m-1" width="32" height="32" alt="">
                		{{row.name}}
						</a></div>
					</td> 
  					<td>{{row.section}}</td> 
					<td>{{row.roll_no}}</td>
					<td class="ml-2">
						<span ng-show="row.attendance===''" >
							<a class="btn btn-sm btn-default text-success" ng-click="markStudentAttendance(row,'<?php print $this->std_attendance_m->LABEL_PRESENT;?>');" title="Present">P</a>
							<a class="btn btn-sm btn-default text-danger" ng-click="markStudentAttendance(row, '<?php print $this->std_attendance_m->LABEL_ABSENT;?>');" title="Absent">A</a>
							<a class="btn btn-sm btn-default text-info" ng-click="markStudentAttendance(row, '<?php print $this->std_attendance_m->LABEL_LEAVE;?>');" title="Leave">L</a>
							<a class="btn btn-sm btn-default text-warning" ng-click="markStudentAttendance(row,'<?php print $this->std_attendance_m->LABEL_HOLIDAY;?>');" title="Holiday">H</a>
						</span>
						<span ng-show="row.attendance!==''" >
							<a ng-show="row.attendance!=='<?php print $this->std_attendance_m->LABEL_PRESENT;?>'" class="btn btn-sm btn-default text-success" ng-click="markStudentAttendance(row,'<?php print $this->std_attendance_m->LABEL_PRESENT;?>');" title="Present">P</a>
							<a ng-show="row.attendance==='<?php print $this->std_attendance_m->LABEL_PRESENT;?>'" class="btn btn-sm btn-success text-white" title="Present">P</a>

							<a ng-show="row.attendance!=='<?php print $this->std_attendance_m->LABEL_ABSENT;?>'" class="btn btn-sm btn-default text-danger" ng-click="markStudentAttendance(row, '<?php print $this->std_attendance_m->LABEL_ABSENT;?>');" title="Absent">A</a>
							<a ng-show="row.attendance==='<?php print $this->std_attendance_m->LABEL_ABSENT;?>'" class="btn btn-sm btn-danger text-white" title="Absent">A</a>

							<a ng-show="row.attendance!=='<?php print $this->std_attendance_m->LABEL_LEAVE;?>'" class="btn btn-sm btn-default text-info" ng-click="markStudentAttendance(row, '<?php print $this->std_attendance_m->LABEL_LEAVE;?>');" title="Leave">L</a>
							<a ng-show="row.attendance==='<?php print $this->std_attendance_m->LABEL_LEAVE;?>'" class="btn btn-sm btn-info text-white" title="On Leave">L</a>

							<a ng-show="row.attendance!=='<?php print $this->std_attendance_m->LABEL_HOLIDAY;?>'" class="btn btn-sm btn-default text-warning" ng-click="markStudentAttendance(row,'<?php print $this->std_attendance_m->LABEL_HOLIDAY;?>');" title="Holiday">H</a>
							<a ng-show="row.attendance==='<?php print $this->std_attendance_m->LABEL_HOLIDAY;?>'" class="btn btn-sm btn-warning text-white" title="Holiday">H</a>
						</span>
					</td>
	                <!-- <td class="mr-4">
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
								<a class="mouse-pointer dropdown-item" ng-show="row.status!=='<?php print $this->std_attendance_m->LABEL_HOLIDAY;?>'" ng-click="markStudentAttendance(row, '<?php print $this->std_attendance_m->LABEL_HOLIDAY;?>');"><span class="badge badge-mark mr-2 bg-warning border-warning"></span> Holiday</a>

							</div>
						</div>
                	</td> -->
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


<!-- all staff sms -->
<div id="list-sms" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Send SMS Notification To Students</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you send sms notitification to all students after applying below filter. You need to enable sms notification feature from settings before sending the sms notification. <?php print $this->SMS_HOST_NOTE; ?></p>

				<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-filter3 mr-2"></i>Choose Student Filters</legend>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-4">
							<label class="text-muted">Target</label>
							<select class="form-control select" ng-model="filter.target" data-fouc>
							<option value="">Student Mobile</option>
							<option value="grd">Guardian Mobile</option>
							</select>
						</div>
						<div class="col-sm-4">
							<label class="text-muted">Student Status</label>
							<select class="form-control select" ng-model="filter.status" ng-change="loadNotifyStudents()" data-fouc>
							<option value="">Select Status</option>
							<option value="absent">Absent Today</option>
							<option value="present">Present Today</option>
							</select>
						</div>						
					</div>
				</div>

				<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-envelop mr-2"></i>Write Message...</legend>
				<p>
					<button type="button" class="btn btn-outline-success btn-sm" ng-click="addkey('NAME')"><i class="icon-user mr-2"></i> Student Name</button>
					<button type="button" class="btn btn-outline-info btn-sm" ng-click="addkey('GUARDIAN')"><i class="icon-people mr-2"></i> Guardian</button>
					<button type="button" class="btn btn-outline-warning btn-sm" ng-click="addkey('CLASS')"><i class="icon-people mr-2"></i> CLASS</button>
				</p>
				<div class="form-group">
					<label class="text-muted">Dynamic keys will be changed into actual values before sending sms.</label>
					<div class="row">
						<div class="col-sm-10">
							<textarea class="form-control" ng-model="message" placeholder="Write your message..." rows="5"></textarea>
						</div>
					</div>
				</div>
				<br>
				<p class="text-muted">You can see below the students with selected status after selection...</p>
				<p class="text-success" ng-show="filter.status==='present'">List of students present today...</p>
				<p class="text-danger" ng-show="filter.status==='absent'">List of students absent today...</p>
				<div class="table-responsive">
				<table class="table tasks-responsive table-sm">
					<thead>
						<tr>
							<th class="font-weight-bold">#</th>
							<th class="font-weight-bold">Class</th>
							<th class="font-weight-bold">Name</th>
							<th class="font-weight-bold">Roll Number</th>
			            </tr>
					</thead>
					<tbody>

						<tr ng-repeat="row in notifyStudents.rows">
							<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
		  					<td>{{row.class}}</td> 
			                <td>
			                	<div><a class="font-weight-semibold">
								<img ng-src="<?php print $this->UPLOADS_ROOT;?>images/student/profile/{{row.image}}" class="rounded-circle m-1" width="32" height="32" alt="">
		                		{{row.name}}
								</a></div>
							</td> 
							<td>{{row.roll_no}}</td>
			            </tr>
					</tbody>
				</table>
				<br>
				</div>


			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="sendListSms()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Send SMS</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /all staff sms -->


<!-- single staff sms -->
<div id="sms" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Send SMS Notification To {{selectedRow.name}}</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you send sms notitification to all staff after applying below filter. You need to enable sms notification feature from settings before sending the sms notification. <?php print $this->SMS_HOST_NOTE; ?></p>

				<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-envelop mr-2"></i>Write Message...</legend>
				<p>
					<button type="button" class="btn btn-outline-success btn-sm" ng-click="addkey('NAME')"><i class="icon-user mr-2"></i> Staff Name</button>
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
<!-- /single staff sms -->





</div>
<!-- /main content -->
<!-- ********************************************************************** -->
<!-- ///////////////////////////////SCRIPTS//////////////////////////////// -->
<!-- ********************************************************************** -->
<!-- <script type="text/javascript">
	
$(function(){
   
});
</script> -->