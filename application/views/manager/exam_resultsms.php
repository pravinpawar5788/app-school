<?php
$activeSession=$this->session_m->getActiveSession();
$sections=$this->class_section_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'name ASC') );
$classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'display_order ASC') );
$terms=$this->exam_term_m->get_rows(array('campus_id'=>$this->CAMPUSID,'session_id'=>$activeSession->mid),array('orderby'=>'mid ASC') );
$sessions=$this->session_m->get_rows(array(),array('orderby'=>'status ASC,mid DESC') );
// $classes_array=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
// $sessions_array=$this->session_m->get_values_array('mid','title',array());
$defaultMonthlyMessage="Dear Parent,\n Monthly test result of {STUDENT} for {MONTH}:\n {RESULT} \n {STAMP}";
$defaultTermMessage="Dear Parents\n Term result of {STUDENT} for {TERM}:\n {RESULT} \n {STAMP}";
$defaultFinalMessage="Dear Parent,\n Final result of {STUDENT} for {SESSION}:\n {RESULT} \n {STAMP}";

?>
<!-- Main content -->
<div class="content-wrapper" ng-controller="mozzCtrl"  ng-cloak>
	<!-- Page header -->
	<div class="page-header page-header-light border-bottom-0" ng-init="message='<?php print $defaultMonthlyMessage;?>'">

		<!-- Top breadcrumb line -->
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
					<a class="breadcrumb-item">Exam</a>
					<span class="breadcrumb-item active">Send Result SMS</span>
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
					</div>
					</div> -->

				</div>
			</div>
		</div>
		<!-- /breadcrumb line -->

	
		<!-- Page header content -->
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Exam</span> - Send Result SMS</h4>
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
						<a href="#profile" class="navbar-nav-link active" data-toggle="tab" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;" ng-click="message='<?php print $defaultMonthlyMessage;?>'">
							<i class="icon-envelope mr-2" style="color:<?php print $clr;?>;"></i>
							Send Monthly Report
						</a>
					</li>
					<li class="nav-item">
						<a href="#termresult" class="navbar-nav-link" data-toggle="tab" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;" ng-click="message='<?php print $defaultTermMessage;?>'">
							<i class="icon-envelope mr-2" style="color:<?php print $clr;?>;"></i>
							Send Terms Result
						</a>
					</li>
					<li class="nav-item">
						<a href="#finalresult" class="navbar-nav-link" data-toggle="tab" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;" ng-click="message='<?php print $defaultFinalMessage;?>'">
							<i class="icon-envelope mr-2" style="color:<?php print $clr;?>;"></i>
							Send Final Result
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


			    <div class="tab-pane fade <?php print empty($tab) || $tab=='profile' ? 'active show': '';?>" id="profile">

			    	<!-- Search field -->
					<div class="card search-area" >
						<div class="card-body">
							<h5 class="mb-3">Send monthly progress report to students / parents
							<span class="d-block font-size-base text-muted">You can send monthly progress report to parents or student via sms. select the class and month for which report to be send. You can send report to student or parent mobile. select the intneded receivers. Please remember to update the results in monthly progress menu before sending sms report.
							</h5>
							</span>

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
											<label class="text-muted">Select Class</label>
											<select class="form-control select" ng-model="filter.class_id" ng-change="filterTestMonths();loadClassSections()" data-fouc>
											<option value="">Select Class</option>
											<?php foreach ($classes as $row){?>            
											    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
										    <?php }?>
											</select>
										</div>
										<div class="col-sm-2" ng-show="filter.class_id.length>0">
											<label class="text-muted">Select Section</label>
											<select class="form-control select" ng-model="filter.section_id" data-fouc>
											<option value="">All Sections</option>
											<option  ng-repeat="row in classSections" value="{{row.mid}}">{{row.name}}</option>
											</select>
										</div>
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
					</div>
					<!-- /search field -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='termresult' ? 'active show': '';?>" id="termresult">
					<!-- Search field -->
					<div class="card search-area" >
						<div class="card-body">
							<h5 class="mb-3">Send term result to students / parents
							<span class="d-block font-size-base text-muted">You can send terms result to parents or student via sms. select the class  for which result to be send. You can send result to student or parent mobile. select the intneded receivers. Please remember to update the results in term management -> update result menu before sending result sms.
							</h5>
							</span>

								<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-envelop mr-2"></i>Write Message...</legend>
								<p>
									
									<button type="button" class="btn btn-outline-success btn-sm" ng-click="addkey('STUDENT')"><i class="icon-user mr-2"></i> Student Name</button>
									<button type="button" class="btn btn-outline-info btn-sm" ng-click="addkey('GUARDIAN')"><i class="icon-people mr-2"></i> Guardian</button>
									<button type="button" class="btn btn-outline-success btn-sm" ng-click="addkey('TERM')"><i class="icon-calendar mr-2"></i> Term</button>
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
										<div class="col-sm-3">
											<label class="text-muted">Select Term</label>
											<select class="form-control select" ng-model="filter.term_id" data-fouc>
											<option value="">Select Term</option>
											<?php foreach ($terms as $row){?>            
											    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['name']);?>
										    <?php }?>
											</select>
										</div>
										<div class="col-sm-3">
											<label class="text-muted">Select Class</label>
											<select class="form-control select" ng-model="filter.class_id" ng-change="loadClassSubjects();loadClassSections()" data-fouc>
											<option value="">Select Class</option>
											<?php foreach ($classes as $row){?>            
											    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
										    <?php }?>
											</select>
										</div>
										<div class="col-sm-2" ng-show="filter.class_id.length>0">
											<label class="text-muted">Select Section</label>
											<select class="form-control select" ng-model="filter.section_id" data-fouc>
											<option value="">All Sections</option>
											<option  ng-repeat="row in classSections" value="{{row.mid}}">{{row.name}}</option>
											</select>
										</div>
										<div class="col-sm-3" ng-show="filter.class_id!==''">
											<label class="text-muted">Select Subject</label>
											<select class="form-control select" ng-model="filter.subject_id" data-fouc>
											<option value="">All Subjects</option>
											<option ng-repeat="row in classSubjects.rows" value="{{row.mid}}">{{row.name}}</option>
											</select>
										</div>
										<div class="col-sm-3">
											<label class="text-muted">Receivers</label>
											<select class="form-control select" ng-model="filter.rxr" data-fouc>
											<option value="">Select Receiver</option>
											<option value="parents">Parents</option>
											<option value="students">Students</option>
											</select>
										</div>
										<div class="col-sm-3 mt-3" >
											<button ng-click="sendTermResultSMS()" class="btn btn-success mt-2" >
												<span class="font-weight-bold">Send Result SMS</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
											</button>
										</div>


									</div>
								</div>

								
						</div>
					</div>
					<!-- /search field -->
		    	</div>

			    <div class="tab-pane fade <?php print $tab=='finalresult' ? 'active show': '';?>" id="finalresult">
					<!-- Search field -->
					<div class="card search-area" >
						<div class="card-body">
							<h5 class="mb-3">Send final result to students / parents
							<span class="d-block font-size-base text-muted">You can send final result to parents or student via sms. select the class  for which result to be send. You can send result to student or parent mobile. select the intneded receivers. Please remember to update the results in final result menu before sending result sms.
							</h5>
							</span>

								<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-envelop mr-2"></i>Write Message...</legend>
								<p>
									
									<button type="button" class="btn btn-outline-success btn-sm" ng-click="addkey('STUDENT')"><i class="icon-user mr-2"></i> Student Name</button>
									<button type="button" class="btn btn-outline-info btn-sm" ng-click="addkey('GUARDIAN')"><i class="icon-people mr-2"></i> Guardian</button>
									<button type="button" class="btn btn-outline-success btn-sm" ng-click="addkey('SESSION')"><i class="icon-calendar mr-2"></i> Session</button>
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
										<div class="col-sm-3">
											<label class="text-muted">Select Class</label>
											<select class="form-control select" ng-model="filter.class_id" ng-change="loadClassSections()" data-fouc>
											<option value="">Select Class</option>
											<?php foreach ($classes as $row){?>            
											    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
										    <?php }?>
											</select>
										</div>
										<div class="col-sm-2" ng-show="filter.class_id.length>0">
											<label class="text-muted">Select Section</label>
											<select class="form-control select" ng-model="filter.section_id" data-fouc>
											<option value="">All Sections</option>
											<option  ng-repeat="row in classSections" value="{{row.mid}}">{{row.name}}</option>
											</select>
										</div>
										<div class="col-sm-3">
											<label class="text-muted">Receivers</label>
											<select class="form-control select" ng-model="filter.rxr" data-fouc>
											<option value="">Select Receiver</option>
											<option value="parents">Parents</option>
											<option value="students">Students</option>
											</select>
										</div>
										<div class="col-sm-3 mt-3" >
											<button ng-click="sendFinalResultSMS()" class="btn btn-success mt-2" >
												<span class="font-weight-bold">Send Result SMS</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
											</button>
										</div>


									</div>
								</div>

								
						</div>
					</div>
					<!-- /search field -->
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
				<button ng-click="loadTests()" class="btn btn-success" data-dismiss="modal">
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