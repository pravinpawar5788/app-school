<?php
$classes=$this->class_m->get_rows(array('org_id'=>$this->ORGID,'campus_id'=>$this->CAMPUSID),array('orderby'=>'title ASC') );
$sessions=$this->session_m->get_rows(array('org_id'=>$this->ORGID),array('orderby'=>'status ASC,mid DESC') );

?>
<!-- Main content -->
<div class="content-wrapper" ng-controller="mozzCtrl"  ng-cloak>

<!-- Page header -->
<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Students</span> - Student List</h4>
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
				<span class="breadcrumb-item active">Student List</span>
			</div>
		</div>
	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb justify-content-center">				
				<a href="<?php print $this->LIB_CONT_ROOT;?>staff" class="breadcrumb-elements-item"><i class="icon-users2 mr-2"></i> Students</a>
				<a href="" class="breadcrumb-elements-item" data-target="#add" <?php print $this->MODAL_OPTIONS;?>>
					<i class="icon-user-plus mr-2"></i> Create New Account</a>
				<a <?php print $this->MODAL_OPTIONS;?> data-target="#list-sms" class="breadcrumb-elements-item mouse-pointer">
					<i class="icon-envelope mr-2"></i> Send SMS Notification
				</a>
			</div>

			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>

		<div class="header-elements d-none">
			<div class="breadcrumb justify-content-center">

				<div class="breadcrumb-elements-item dropdown p-0">
					<a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
						<i class="icon-printer mr-2"></i>Print
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a href="<?php print $this->CONT_ROOT.'printing/list/?';?>" class="dropdown-item"><i class="icon-users"></i> All Students List</a>
						<a href="<?php print $this->CONT_ROOT.'printing/list/?status=active';?>" class="dropdown-item"><i class="icon-user-check"></i> Active Students List</a>
						<a href="<?php print $this->CONT_ROOT.'printing/list/?nstatus=active';?>" class="dropdown-item"><i class="icon-user-block"></i> Not Active Students</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item"><i class="icon-eye"></i> Today Present List</a>
						<a href="#" class="dropdown-item"><i class="icon-eye-blocked"></i> Today Absent List</a>
					</div>
				</div>
				<!-- on each page -->
				<a href="<?php print $this->APP_ROOT.'docs/student';?>" target="_blank" class="breadcrumb-elements-item">
					<i class="icon-lifebuoy mr-2"></i>Docs
				</a>
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
			<h5 class="mb-3">Search Student</h5>
			<div class="row">
				<div class="col-sm-9">
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
				<div class="col-sm-3">
					<a class="btn btn-info text-white" <?php print $this->MODAL_OPTIONS;?> data-target="#refine-search">
							<i class="icon-filter3"></i></a>
					<a class="btn btn-danger text-white" ng-show="showFilter()" ng-click="clearFilter();loadRows();">
							<i class="icon-diff-removed"></i></a>
					<div class="list-icons m-2" ng-show="showFilter()" >
						<div class="list-icons-item dropdown">
							<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown">
								<i class="icon-menu9 mr-5"></i></a>
							<div class="dropdown-menu dropdown-menu-right">
								<a class="dropdown-item" ng-href="<?php print $this->CONT_ROOT.'printing/list/?'?>{{filterGetString()}}">
									<i class="icon-printer mr-2"></i> Print Search Data 
								</a>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	<!-- /search field -->

	<!-- List table -->
	<div class="card">
		<div class="card-header bg-transparent">
			<h4 class="card-title">Students List </h4>
			<span class="text-muted">Students list of the session.</span>
		</div>
		<div class="table-responsive">
		<table class="table tasks-responsive table-lg">
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
					<th ng-click="sortBy('fee');" class="mouse-pointer font-weight-bold">
						<span class="m-1">Fee</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='fee'"></i> 
					</th>
					<th ng-click="sortBy('status');" class="mouse-pointer font-weight-bold">
						<span class="m-1">Status</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='status'"></i> 
					</th>
					<th class="text-center text-muted" style="width: 30px;"><i class="icon-checkmark3"></i></th>
	            </tr>
			</thead>
			<tbody>

				<tr ng-repeat="row in responseData.rows">
					<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
	                <td>
	                	<div><a ng-href="<?php print $this->CONT_ROOT.'profile/'?>{{row.mid}}" class="font-weight-semibold">
						<img ng-src="<?php print $this->ORG_RES_ROOT;?>images/student/profile/{{row.image}}" class="rounded-circle m-1" width="32" height="32" alt="">
                		{{row.name}}
						</a></div>
					</td> 
					<td>{{row.father_name}}</td>
  					<td>{{row.class}}</td>   
  					<td>{{row.roll_no}}</td>   
  					<td><?php print $this->ORGSETTINGS->currency_symbol?>{{row.fee}}</td>  
	                <td>
                	<div class="btn-group">
						<a ng-show="row.status==='<?php print $this->student_m->STATUS_ACTIVE;?>'" href="#" class="badge bg-success dropdown-toggle" data-toggle="dropdown">
							<?php print strtoupper($this->student_m->STATUS_ACTIVE);?></a>
						<a ng-show="row.status==='<?php print $this->student_m->STATUS_UNACTIVE;?>'" href="#" class="badge bg-danger dropdown-toggle" data-toggle="dropdown">
							<?php print strtoupper($this->student_m->STATUS_UNACTIVE);?></a>
						<a ng-show="row.status==='<?php print $this->student_m->STATUS_EXPELLED;?>'" href="#" class="badge bg-warning dropdown-toggle" data-toggle="dropdown">
							<?php print strtoupper($this->student_m->STATUS_EXPELLED);?></a>
						<a ng-show="row.status==='<?php print $this->student_m->STATUS_TRANSFER;?>'" href="#" class="badge bg-info dropdown-toggle" data-toggle="dropdown"><?php print strtoupper($this->student_m->STATUS_TRANSFER);?></a>
						<a ng-show="row.status==='<?php print $this->student_m->STATUS_PASSOUT;?>'" href="#" class="badge bg-primary dropdown-toggle" data-toggle="dropdown">
							<?php print strtoupper($this->student_m->STATUS_PASSOUT);?></a>
						<a ng-show="row.status==='<?php print $this->student_m->STATUS_LEFT;?>'" href="#" class="badge bg-pink dropdown-toggle" data-toggle="dropdown">
							<?php print strtoupper($this->student_m->STATUS_LEFT);?></a>
						
						<div class="dropdown-menu dropdown-menu-right">
							<a href="#" class="dropdown-item" ng-show="row.status!=='<?php print $this->student_m->STATUS_ACTIVE;?>'" ng-click="changeStatus(row, '<?php print $this->student_m->STATUS_ACTIVE;?>');"><span class="badge badge-mark mr-2 bg-success border-success"></span> Activate</a>
							<a href="#" class="dropdown-item" ng-show="row.status!=='<?php print $this->student_m->STATUS_UNACTIVE;?>'" ng-click="changeStatus(row, '<?php print $this->student_m->STATUS_UNACTIVE;?>');"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Disable</a>
							<a href="#" class="dropdown-item" ng-show="row.status!=='<?php print $this->student_m->STATUS_EXPELLED;?>'" ng-click="changeStatus(row, '<?php print $this->student_m->STATUS_EXPELLED;?>');"><span class="badge badge-mark mr-2 bg-warning border-warning"></span> Expel Now</a>
							<a href="#" class="dropdown-item" ng-show="row.status!=='<?php print $this->student_m->STATUS_TRANSFER;?>'" ng-click="changeStatus(row, '<?php print $this->student_m->STATUS_TRANSFER;?>');"><span class="badge badge-mark mr-2 bg-info border-info"></span> Transfer to other campus</a>
							<a href="#" class="dropdown-item" ng-show="row.status!=='<?php print $this->student_m->STATUS_PASSOUT;?>'" ng-click="changeStatus(row, '<?php print $this->student_m->STATUS_PASSOUT;?>');"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Passed Out</a>
							<a href="#" class="dropdown-item" ng-show="row.status!=='<?php print $this->student_m->STATUS_LEFT;?>'" ng-click="changeStatus(row, '<?php print $this->student_m->STATUS_LEFT;?>');"><span class="badge badge-mark mr-2 bg-pink border-pink"></span> Mark Left</a>

						</div>
					</div>


                	</td>
					<td class="text-center">
						<div class="list-icons">
							<div class="list-icons-item dropdown">
								<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
								<div class="dropdown-menu dropdown-menu-right">
									<a ng-href="<?php print $this->CONT_ROOT.'profile/';?>{{row.mid}}" class="dropdown-item"><i class="icon-user"></i> Profile</a>
									<a <?php print $this->MODAL_OPTIONS;?> data-target="#edit" class="dropdown-item" ng-click="selectRow(row)">
										<i class="icon-compose"></i> Update Account
									</a>
									<a <?php print $this->MODAL_OPTIONS;?> data-target="#sms" class="dropdown-item" ng-click="selectRow(row)">
										<i class="icon-envelop"></i> Send SMS Notification
									</a>
									<a ng-href="<?php print $this->CONT_ROOT.'printing/profile/?usr='?>{{row.mid}}" class="dropdown-item">
										<i class="icon-printer"></i> Print Profile
									</a>
									<div class="dropdown-divider"></div>
									<a <?php print $this->MODAL_OPTIONS;?> data-target="#password" class="dropdown-item" ng-click="selectRow(row)">
										<i class="icon-user-lock"></i> Change Portal Password
									</a>
									<?php if($this->LOGIN_USER->can_del_student>0){?>
									<a class="dropdown-item" ng-click="delRow(row)"><i class="icon-user-cancel"></i> Delete Account</a>
									<?php }?>
								</div>
							</li>
						</div>
					</td>
	            </tr>


			</tbody>
		</table>
		<br><br><br>

		<div>
		<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadRows()">
		<i class="icon-arrow-left52"></i> Back Page</button>
		<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadRows()">
		 Next Page <i class="icon-arrow-right6"></i></button>
		<br><br><br>
		</div>
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
							<label class="text-muted">Session</label>
							<select class="form-control select" ng-model="filter.session" data-fouc>
							<option value="">Select Session</option>
							<?php foreach ($sessions as $row){?>            
							    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
						    <?php }?>
							</select>
						</div>
						<div class="col-sm-2">
							<label class="text-muted">Class</label>
							<select class="form-control select" ng-model="filter.class" data-fouc>
							<option value="">Select Class</option>
							<?php foreach ($classes as $row){?>            
							    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
						    <?php }?>
							</select>
						</div>
						<div class="col-sm-2">
							<label class="text-muted">Status</label>
							<select class="form-control select" ng-model="filter.status" data-fouc>
							<option value="">Select Status</option>
							<option value="<?php print $this->student_m->STATUS_ACTIVE;?>" /><?php print strtoupper($this->student_m->STATUS_ACTIVE);?>
							<option value="<?php print $this->student_m->STATUS_UNACTIVE;?>" /><?php print strtoupper($this->student_m->STATUS_UNACTIVE);?>
							<option value="<?php print $this->student_m->STATUS_EXPELLED;?>" /><?php print strtoupper($this->student_m->STATUS_EXPELLED);?>
							<option value="<?php print $this->student_m->STATUS_LEFT;?>" /><?php print strtoupper($this->student_m->STATUS_LEFT);?>
							<option value="<?php print $this->student_m->STATUS_PASSOUT;?>" /><?php print strtoupper($this->student_m->STATUS_PASSOUT);?>
							<option value="<?php print $this->student_m->STATUS_TRANSFER;?>" /><?php print strtoupper($this->student_m->STATUS_TRANSFER);?>
							</select>
						</div>
						<div class="col-sm-2">
							<label class="text-muted">Minimum Fee</label>
							<select class="form-control select" ng-model="filter.fee" data-fouc>
								<option value="">Select Minimum Fee</option>
								<option value="100">100</option>
								<option value="500">500</option>
								<option value="1000">1000</option>
								<option value="5000">5000</option>
								<option value="10000">10000</option>
								<option value="50000">50000</option>
							</select>
						</div>
						<div class="col-sm-2">
							<label class="text-muted">Gender</label>
							<select class="form-control select" ng-model="filter.gender" data-fouc>
							<option value="">Select Gender</option>
							<option value="male">Male</option>
							<option value="female">Female</option>
							<option value="other">Other</option>
							</select>
						</div>
						<div class="col-sm-2">
							<label class="text-muted">Blood Group</label>
							<select class="form-control select" ng-model="filter.blood_group" data-fouc>
							<option value="">Selec Boold Group</option>
							<option value="A+">A+</option>
							<option value="A-">A-</option>
							<option value="B+">B+</option>
							<option value="B-">B-</option>
							<option value="O+">O+</option>
							<option value="O-">O-</option>
							<option value="AB+">AB+</option>
							<option value="AB-">AB-</option>
							</select>
						</div>

						
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="loadRows()" class="btn btn-success btn-lg" data-dismiss="modal">
						<span class="font-weight-bold"> Search</span>
						<i class="icon-circle-right2 ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /refine search modal -->


<!-- add modal -->
<div id="add" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Create New Account</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you admit new student here. Please provide required data to register a new account. Ask your administrator to create a new classes for you if target class is not available in drop down option...</p>
				

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
								<div class="col-sm-3">
									<label class="text-muted">Admission Number <span class="text-danger"> * </span><span class="auto">(Last admission: <strong>{{responseData.last_admission_number}}</strong>)</span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Admission Number" ng-model="student.admission_number">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Student Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Full Name" ng-model="student.name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Guardian Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Guardian Name" ng-model="student.guardian_name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Guardian Mobile <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="03xxxxxxxxx" ng-model="student.guardian_mobile">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-mobile"></i>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="form-group">
							<div class="row">

								
								<div class="col-sm-2">
									<label class="text-muted">Admission Session<span class="text-danger"> * </span></label>
									<select class="form-control select" ng-model="student.session_id" data-fouc>
									<option value="">Select Session</option>
									<?php foreach ($sessions as $row){?>            
									    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
								    <?php }?>
									</select>
								</div>
								<div class="col-sm-2">
									<label class="text-muted">Admission Class<span class="text-danger"> * </span></label>
									<select class="form-control select" ng-model="student.class_id" data-fouc>
									<option value="">Select Class</option>
									<?php foreach ($classes as $row){?>            
									    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
								    <?php }?>
									</select>
								</div>
								<div class="col-sm-2">
									<label class="text-muted">Admission Voucher</label>
									<select class="form-control select" ng-model="student.create_voucher" data-fouc>
									<option value="">Do not create admission voucer</option>
									<option value="1">Create admission voucher</option>
									</select>
								</div>
								<div class="col-sm-4" ng-show="student.create_voucher>0">
									<label class="text-muted">Admission Fee <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Admission Fee" ng-model="student.admission_fee">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-coin-dollar"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Fee Type</label>
									<select class="form-control select" ng-model="student.fee_type" data-fouc>
									<option value="<?php print $this->student_m->FEE_TYPE_MONTHLY ?>">Student will Pay Fee Monthly</option>
									<option value="<?php print $this->student_m->FEE_TYPE_FIXED ?>">Student will pay pre-fixed fee for a session</option>
									</select>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Student Fee <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Student Fee" ng-model="student.fee">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-coin-dollar"></i>
										</div>
									</div>
								</div>

							</div>
						</div>

					</div>
				</div>


				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Other Details</h6>
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
									<label class="text-muted">Date Of Birth </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg datepicker" placeholder="Date Of Birth" ng-model="student.date_of_birth">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-calendar"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Student Mobile </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Student Mobile Number" ng-model="student.mobile">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-mobile"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Student NIC / B-Form Number </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="xxxxxxxxxxxx" ng-model="student.nic">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Religion </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Islam" ng-model="student.religion">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="form-group">
							<div class="row">

								<div class="col-sm-3">
									<label class="text-muted">Gender</label>
									<select class="form-control select" ng-model="student.gender" data-fouc>
									<option value="">Select Gender</option>
									<option value="male">Male</option>
									<option value="female">Female</option>
									<option value="other">Other</option>
									</select>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Blood Group</label>
									<select class="form-control select" ng-model="student.blood_group" data-fouc>
									<option value="">Selec Boold Group</option>
									<option value="A+">A+</option>
									<option value="A-">A-</option>
									<option value="B+">B+</option>
									<option value="B-">B-</option>
									<option value="O+">O+</option>
									<option value="O-">O-</option>
									<option value="AB+">AB+</option>
									<option value="AB-">AB-</option>
									</select>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Emergency Contact Number </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Emergency Contact Number" ng-model="student.emergency_contact">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-phone2"></i>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="form-group">
							<div class="row">


								<div class="col-sm-3">
									<label class="text-muted">Father Name </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Father Name" ng-model="student.father_name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Father NIC </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Father NIC" ng-model="student.father_nic">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Mother Name </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Mother Name" ng-model="student.mother_name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Mother NIC </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Mother NIC" ng-model="student.mother_nic">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>


							</div>
						</div>
						<div class="form-group">
							<div class="row">


								<div class="col-sm-6">
									<label class="text-muted">Father Occupation </label>
									<textarea rows="2" cols="3" class="form-control" placeholder="Father Occupation" ng-model="student.father_occupation"></textarea>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Home Address </label>
									<textarea rows="2" cols="3" class="form-control" placeholder="Home Address" ng-model="student.address"></textarea>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Medical Problem</label>
									<textarea rows="2" cols="3" class="form-control" placeholder="if any medical problem" ng-model="student.medical_problem"></textarea>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Any Other Information</label>
									<textarea rows="2" cols="3" class="form-control" placeholder="if any" ng-model="student.other_info"></textarea>
								</div>								
							</div>
						</div>

					</div>
				</div>


			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="saveRow()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /add modal -->

<!-- edit modal -->
<div id="edit" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update Account ({{selectedRow.name}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you update student account here. Please change required data and click on save. Ask your administrator to create a new classes for you if target class is not available in drop down option...</p>
				

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
								<div class="col-sm-3">
									<label class="text-muted">Student Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Full Name" ng-model="selectedRow.name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Guardian Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Guardian Name" ng-model="selectedRow.guardian_name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Guardian Mobile <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="03xxxxxxxxx" ng-model="selectedRow.guardian_mobile">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-mobile"></i>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="form-group">
							<div class="row">

								
								<div class="col-sm-2">
									<label class="text-muted">Session<span class="text-danger"> * </span></label>
									<select class="form-control select" ng-model="selectedRow.session_id" data-fouc>
									<option value="">Select Session</option>
									<?php foreach ($sessions as $row){?>            
									    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
								    <?php }?>
									</select>
								</div>
								<div class="col-sm-2">
									<label class="text-muted">Student Class<span class="text-danger"> * </span></label>
									<select class="form-control select" ng-model="selectedRow.class_id" data-fouc>
									<option value="">Select Class</option>
									<?php foreach ($classes as $row){?>            
									    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
								    <?php }?>
									</select>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Fee Type</label>
									<select class="form-control select" ng-model="selectedRow.fee_type" data-fouc>
									<option value="<?php print $this->student_m->FEE_TYPE_MONTHLY ?>">Student will Pay Fee Monthly</option>
									<option value="<?php print $this->student_m->FEE_TYPE_FIXED ?>">Student will pay pre-fixed fee for a session</option>
									</select>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Student Fee <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Student Fee" ng-model="selectedRow.fee">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-coin-dollar"></i>
										</div>
									</div>
								</div>

							</div>
						</div>

					</div>
				</div>


				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Other Details</h6>
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
									<label class="text-muted">Date Of Birth </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg datepicker" placeholder="Date Of Birth" ng-model="selectedRow.date_of_birth">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-calendar"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Student Mobile </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Student Mobile Number" ng-model="selectedRow.mobile">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-mobile"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Student NIC / B-Form Number </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="xxxxxxxxxxxx" ng-model="selectedRow.nic">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Religion </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Islam" ng-model="selectedRow.religion">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="form-group">
							<div class="row">

								<div class="col-sm-3">
									<label class="text-muted">Gender</label>
									<select class="form-control select" ng-model="selectedRow.gender" data-fouc>
									<option value="">Select Gender</option>
									<option value="male">Male</option>
									<option value="female">Female</option>
									<option value="other">Other</option>
									</select>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Blood Group</label>
									<select class="form-control select" ng-model="selectedRow.blood_group" data-fouc>
									<option value="">Selec Boold Group</option>
									<option value="A+">A+</option>
									<option value="A-">A-</option>
									<option value="B+">B+</option>
									<option value="B-">B-</option>
									<option value="O+">O+</option>
									<option value="O-">O-</option>
									<option value="AB+">AB+</option>
									<option value="AB-">AB-</option>
									</select>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Emergency Contact Number </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Emergency Contact Number" ng-model="selectedRow.emergency_contact">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-phone2"></i>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="form-group">
							<div class="row">


								<div class="col-sm-3">
									<label class="text-muted">Father Name </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Father Name" ng-model="selectedRow.father_name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Father NIC </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Father NIC" ng-model="selectedRow.father_nic">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Mother Name </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Mother Name" ng-model="selectedRow.mother_name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Mother NIC </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Mother NIC" ng-model="selectedRow.mother_nic">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>


							</div>
						</div>
						<div class="form-group">
							<div class="row">


								<div class="col-sm-6">
									<label class="text-muted">Father Occupation </label>
									<textarea rows="2" cols="3" class="form-control" placeholder="Father Occupation" ng-model="selectedRow.father_occupation"></textarea>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Home Address </label>
									<textarea rows="2" cols="3" class="form-control" placeholder="Home Address" ng-model="selectedRow.address"></textarea>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Medical Problem</label>
									<textarea rows="2" cols="3" class="form-control" placeholder="if any medical problem" ng-model="selectedRow.medical_problem"></textarea>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Any Other Information</label>
									<textarea rows="2" cols="3" class="form-control" placeholder="if any" ng-model="selectedRow.other_info"></textarea>
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


<!-- change password modal -->
<div id="password" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update Account Password ({{selectedRow.name}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let the student login into their portal. You may reset the password for student portal if they forget the current password...</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Portal Registration </h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>

						<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-10">
									<label class="text-muted">New Passsword for student id({{selectedRow.student_id}})<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Enter New Password here" ng-model="staff.password">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-key"></i>
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
				<button ng-click="changePassword()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /edit modal -->

<!-- all staff sms -->
<div id="list-sms" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Send SMS Notification To Student List</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you send sms notitification to all students after applying below filter. You need to enable sms notification feature from settings before sending the sms notification. <?php print $this->SMS_HOST_NOTE; ?></p>

				<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-filter3 mr-2"></i>Choose Student Filters</legend>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<label class="text-muted">Session</label>
							<select class="form-control select" ng-model="filter.session" data-fouc>
							<option value="">Select Session</option>
							<?php foreach ($sessions as $row){?>            
							    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
						    <?php }?>
							</select>
						</div>
						<div class="col-sm-2">
							<label class="text-muted">Class</label>
							<select class="form-control select" ng-model="filter.class" data-fouc>
							<option value="">Select Class</option>
							<?php foreach ($classes as $row){?>            
							    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
						    <?php }?>
							</select>
						</div>
						<div class="col-sm-2">
							<label class="text-muted">Status</label>
							<select class="form-control select" ng-model="filter.status" data-fouc>
							<option value="">Select Status</option>
							<option value="<?php print $this->student_m->STATUS_ACTIVE;?>" /><?php print strtoupper($this->student_m->STATUS_ACTIVE);?>
							<option value="<?php print $this->student_m->STATUS_UNACTIVE;?>" /><?php print strtoupper($this->student_m->STATUS_UNACTIVE);?>
							<option value="<?php print $this->student_m->STATUS_EXPELLED;?>" /><?php print strtoupper($this->student_m->STATUS_EXPELLED);?>
							<option value="<?php print $this->student_m->STATUS_LEFT;?>" /><?php print strtoupper($this->student_m->STATUS_LEFT);?>
							<option value="<?php print $this->student_m->STATUS_PASSOUT;?>" /><?php print strtoupper($this->student_m->STATUS_PASSOUT);?>
							<option value="<?php print $this->student_m->STATUS_TRANSFER;?>" /><?php print strtoupper($this->student_m->STATUS_TRANSFER);?>
							</select>
						</div>
						<div class="col-sm-2">
							<label class="text-muted">Minimum Fee</label>
							<select class="form-control select" ng-model="filter.fee" data-fouc>
								<option value="">Select Minimum Fee</option>
								<option value="100">100</option>
								<option value="500">500</option>
								<option value="1000">1000</option>
								<option value="5000">5000</option>
								<option value="10000">10000</option>
								<option value="50000">50000</option>
							</select>
						</div>
						<div class="col-sm-2">
							<label class="text-muted">Gender</label>
							<select class="form-control select" ng-model="filter.gender" data-fouc>
							<option value="">Select Gender</option>
							<option value="male">Male</option>
							<option value="female">Female</option>
							<option value="other">Other</option>
							</select>
						</div>
						<div class="col-sm-2">
							<label class="text-muted">Blood Group</label>
							<select class="form-control select" ng-model="filter.blood_group" data-fouc>
							<option value="">Selec Boold Group</option>
							<option value="A+">A+</option>
							<option value="A-">A-</option>
							<option value="B+">B+</option>
							<option value="B-">B-</option>
							<option value="O+">O+</option>
							<option value="O-">O-</option>
							<option value="AB+">AB+</option>
							<option value="AB-">AB-</option>
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
				<br>
				<p class="text-muted">Sms notification will be sent to filtered students only. Incase you do not choose any filter then sms notificaton will be delivered to all registered students of this campus...</p>

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