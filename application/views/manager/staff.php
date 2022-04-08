<?php
$roles=$this->stf_role_m->get_rows(array(),array('orderby'=>'title ASC') );

?>
<!-- Main content -->
<div class="content-wrapper" ng-controller="mozzCtrl"  ng-cloak>

<!-- Page header -->
<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Staff</span> - Staff List</h4>
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
				<span class="breadcrumb-item active">Staff List</span>
			</div>
		</div>
	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb justify-content-center">				
				<a href="<?php print $this->LIB_CONT_ROOT;?>staff" class="breadcrumb-elements-item" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
					<i class="icon-users2 mr-2" style="color:<?php print $clr;?>;"></i> Staff</a>
				<?php if($this->LOGIN_USER->prm_stf_info>1){?>
				<a href="" class="breadcrumb-elements-item" data-target="#add" <?php print $this->MODAL_OPTIONS;?> style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
					<i class="icon-user-plus mr-2" style="color:<?php print $clr;?>;"></i> Add New Staff</a>
				<a <?php print $this->MODAL_OPTIONS;?> data-target="#list-sms" class="breadcrumb-elements-item mouse-pointer" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
					<i class="icon-envelope mr-2" style="color:<?php print $clr;?>;"></i> Send SMS Notification</a>
				<?php } ?>
			</div>

			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>

		<div class="header-elements d-none">
			<div class="breadcrumb justify-content-center">

				<div class="breadcrumb-elements-item dropdown p-0">
					<a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
						<i class="icon-printer mr-2"></i>Print
					</a>
					<?php $defprnt_prm='&p_mobile&p_stfid&p_grd';?>
					<div class="dropdown-menu dropdown-menu-left">
						<!-- <div class="dropdown-submenu dropdown-submenu-left">
							<a href="#" class="dropdown-item"><i class="icon-clipboard6"></i> Reports</a>
							<div class="dropdown-menu"> -->
								<!-- <div class="dropdown-submenu dropdown-submenu-left">
									<a href="#" class="dropdown-item">Attendance Reports</a>
									<div class="dropdown-menu">
										<a href="<?php print $this->LIB_CONT_ROOT.'attendance/printing/report/?rpt=stf_attendance';?>" class="dropdown-item">Daily Attendance Report</a>
									</div>
								</div> -->
								<!-- <a href="<?php print $this->CONT_ROOT.'printing/list/?alumni';?>" class="dropdown-item">Student Strength Report</a> -->
								<!-- <a href="<?php print $this->CONT_ROOT.'printing/list/?alumni';?>" class="dropdown-item">Admissions Report</a> -->
							<!-- </div>
						</div> -->
						<div class="dropdown-submenu dropdown-submenu-left">
							<a href="#" class="dropdown-item"><i class="icon-clipboard4"></i> Forms</a>
							<div class="dropdown-menu">
								<!-- <div class="dropdown-submenu dropdown-submenu-left">
									<a href="#" class="dropdown-item">Student Cards</a>
									<div class="dropdown-menu">
										<a href="#" class="dropdown-item">Identity Cards</a>
										<a href="#" class="dropdown-item">Transport Cards</a>
									</div>
								</div> -->
								<a href="<?php print $this->CONT_ROOT.'printing/form/?type=stfreg';?>" class="dropdown-item">Staff Registration Form</a>
								<!-- <a href="<?php print $this->CONT_ROOT.'printing/list/?alumni';?>" class="dropdown-item">Fee Defaulter Notice</a> -->
							</div>
						</div>
						<div class="dropdown-divider"></div>
						<a href="<?php print $this->CONT_ROOT.'printing/list/?'.$defprnt_prm;?>" class="dropdown-item"><i class="icon-users"></i> All Staff List</a>
						<a href="<?php print $this->CONT_ROOT.'printing/list/?status=active'.$defprnt_prm;?>" class="dropdown-item"><i class="icon-user-check"></i> Active Staff List</a>
						<a href="<?php print $this->CONT_ROOT.'printing/list/?nstatus=active'.$defprnt_prm;?>" class="dropdown-item"><i class="icon-user-block"></i> Not Active Staff</a>
					</div>
				</div>
				<!-- on each page -->
				<a href="<?php print $this->APP_ROOT.'docs/staff';?>" target="_blank" class="breadcrumb-elements-item">
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
			<h5 class="mb-3">Search Staff</h5>
			<div class="row">
				<div class="col-sm-3">
					<select class="form-control select" ng-model="filter.filter" data-fouc>
						<option value="">Via General Data</option>
						<option value="name">Via Name</option>
						<option value="email">Via Email</option>
						<option value="mobile">Via Mobile</option>
						<option value="cnic">Via National ID</option>
						<option value="staff_id">Via Staff ID</option>

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
				<div class="col-sm-3">
					<a class="btn btn-info btn-lg text-white" <?php print $this->MODAL_OPTIONS;?> data-target="#refine-search">
							<i class="icon-filter3"></i> Advance Search</a>
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
			<h4 class="card-title">Staff List</h4>
			<span class="text-muted">Registered staff list.</span>
		</div>
		<div class="table-responsive">
		<table class="table tasks-responsive table-lg">
			<thead>
				<tr>
					<th class="font-weight-bold">#</th>
					<th ng-click="sortBy('name');" class="mouse-pointer font-weight-bold" >
						<span class="m-1">Name</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='name'"></i> 
					</th>
					<th ng-click="sortBy('role_id');" class="mouse-pointer font-weight-bold">
						<span class="m-1">Role</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='role_id'"></i> 
					</th>
					<th ng-click="sortBy('mobile');" class="mouse-pointer font-weight-bold">
						<span class="m-1">Mobile</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='mobile'"></i> 
					</th>
					<th ng-click="sortBy('salary');" class="mouse-pointer font-weight-bold">
						<span class="m-1">Basic Salary</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='salary'"></i> 
					</th>
					<th ng-click="sortBy('status');" class="mouse-pointer font-weight-bold">
						<span class="m-1">Status</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='status'"></i> 
					</th>
					<th class="text-center text-muted" style="min-width: 30px;"><i class="icon-checkmark3"></i></th>
	            </tr>
			</thead>
			<tbody>

				<tr ng-repeat="row in responseData.rows">
					<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
	                <td>
	                	<div><a ng-href="<?php print $this->CONT_ROOT.'profile/'?>{{row.mid}}" class="font-weight-semibold">
						<img ng-src="<?php print $this->UPLOADS_ROOT;?>images/staff/profile/{{row.image}}" class="rounded-circle m-1" width="32" height="32" alt="">
                		{{row.name}}
						</a></div>
					</td> 
					<td>{{row.role}}</td>
  					<td>{{row.mobile}}</td>   
  					<td><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]?>{{row.salary}}</td>  
	                <td>
                	<div class="btn-group">
						<a ng-show="row.status==='<?php print $this->staff_m->STATUS_ACTIVE;?>'" class="badge bg-success dropdown-toggle mouse-pointer" data-toggle="dropdown">
							<?php print strtoupper($this->staff_m->STATUS_ACTIVE);?></a>
						<a ng-show="row.status==='<?php print $this->staff_m->STATUS_UNACTIVE;?>'" class="badge bg-danger dropdown-toggle mouse-pointer" data-toggle="dropdown">
							<?php print strtoupper($this->staff_m->STATUS_UNACTIVE);?></a>
						<a ng-show="row.status==='<?php print $this->staff_m->STATUS_EXPELLED;?>'" class="badge bg-warning dropdown-toggle mouse-pointer" data-toggle="dropdown">
							<?php print strtoupper($this->staff_m->STATUS_EXPELLED);?></a>
						<a ng-show="row.status==='<?php print $this->staff_m->STATUS_TRANSFERRED;?>'" class="badge bg-info dropdown-toggle mouse-pointer" data-toggle="dropdown"><?php print strtoupper($this->staff_m->STATUS_TRANSFERRED);?></a>
						<a ng-show="row.status==='<?php print $this->staff_m->STATUS_RETIRED;?>'" class="badge bg-primary dropdown-toggle mouse-pointer" data-toggle="dropdown">
							<?php print strtoupper($this->staff_m->STATUS_RETIRED);?></a>
						<a ng-show="row.status==='<?php print $this->staff_m->STATUS_LEFT;?>'" class="badge bg-pink dropdown-toggle mouse-pointer" data-toggle="dropdown">
							<?php print strtoupper($this->staff_m->STATUS_LEFT);?></a>
						
						<?php if($this->LOGIN_USER->prm_stf_info>1){?>
						<div class="dropdown-menu dropdown-menu-right">
							<a class="dropdown-item mouse-pointer" ng-show="row.status!=='<?php print $this->staff_m->STATUS_ACTIVE;?>'" ng-click="changeStatus(row, '<?php print $this->staff_m->STATUS_ACTIVE;?>');"><span class="badge badge-mark mr-2 bg-success border-success"></span> Activate</a>
							<a class="dropdown-item mouse-pointer" ng-show="row.status!=='<?php print $this->staff_m->STATUS_UNACTIVE;?>'" ng-click="changeStatus(row, '<?php print $this->staff_m->STATUS_UNACTIVE;?>');"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Disable</a>
							<a class="dropdown-item mouse-pointer" ng-show="row.status!=='<?php print $this->staff_m->STATUS_EXPELLED;?>'" ng-click="changeStatus(row, '<?php print $this->staff_m->STATUS_EXPELLED;?>');"><span class="badge badge-mark mr-2 bg-warning border-warning"></span> Expel Now</a>
							<a class="dropdown-item mouse-pointer" ng-show="row.status!=='<?php print $this->staff_m->STATUS_TRANSFERRED;?>'" ng-click="changeStatus(row, '<?php print $this->staff_m->STATUS_TRANSFERRED;?>');"><span class="badge badge-mark mr-2 bg-info border-info"></span> Transfer</a>
							<a class="dropdown-item mouse-pointer" ng-show="row.status!=='<?php print $this->staff_m->STATUS_RETIRED;?>'" ng-click="changeStatus(row, '<?php print $this->staff_m->STATUS_RETIRED;?>');"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Retire</a>
							<a class="dropdown-item mouse-pointer" ng-show="row.status!=='<?php print $this->staff_m->STATUS_LEFT;?>'" ng-click="changeStatus(row, '<?php print $this->staff_m->STATUS_LEFT;?>');"><span class="badge badge-mark mr-2 bg-pink border-pink"></span> Mark Left</a>

						</div>
						<?php } ?>
					</div>


                	</td>
					<td>
						<div class="list-icons float-right">
							<div class="btn-group list-icons-item dropdown">
		                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
								
								<div class="dropdown-menu dropdown-menu-right">
									<a ng-href="<?php print $this->CONT_ROOT.'profile/';?>{{row.mid}}" class="dropdown-item"><i class="icon-user"></i> Profile</a>

									<?php if($this->LOGIN_USER->prm_stf_info>1){?>
									<a <?php print $this->MODAL_OPTIONS;?> data-target="#edit" class="dropdown-item" ng-click="selectRow(row)">
										<i class="icon-compose"></i> Update Account
									</a>
									<?php }?>
									<a <?php print $this->MODAL_OPTIONS;?> data-target="#sms" class="dropdown-item" ng-click="selectRow(row)">
										<i class="icon-envelop"></i> Send SMS Notification
									</a>
									<a ng-href="<?php print $this->CONT_ROOT.'printing/profile/?usr='?>{{row.mid}}" class="dropdown-item">
										<i class="icon-printer"></i> Print Profile
									</a>
									<?php if($this->LOGIN_USER->prm_stf_info>1){?>
									<div class="dropdown-divider"></div>
									<a <?php print $this->MODAL_OPTIONS;?> data-target="#password" class="dropdown-item" ng-click="selectRow(row)">
										<i class="icon-user-lock"></i> Change Portal Password
									</a>
									<?php if($this->IS_DEV_LOGIN){?>
									<a class="dropdown-item" ng-href="<?php print $this->CONT_ROOT.'login/'?>{{row.mid}}" target="_blank"><i class="icon-user-lock"></i> Account Login
									</a>
									<?php }?>
									<?php }?>
									<?php if($this->LOGIN_USER->prm_stf_info>2){?>
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
							<label class="text-muted">Staff Role</label>
							<select class="form-control select" ng-model="filter.role" data-fouc>
							<option value="">Select Designation</option>
							<?php foreach ($roles as $row){?>            
							    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
						    <?php }?>
							</select>
						</div>
						<div class="col-sm-2">
							<label class="text-muted">Status</label>
							<select class="form-control select" ng-model="filter.status" data-fouc>
							<option value="">Select Status</option>
							<option value="<?php print $this->staff_m->STATUS_ACTIVE;?>" /><?php print strtoupper($this->staff_m->STATUS_ACTIVE);?>
							<option value="<?php print $this->staff_m->STATUS_UNACTIVE;?>" /><?php print strtoupper($this->staff_m->STATUS_UNACTIVE);?>
							<option value="<?php print $this->staff_m->STATUS_EXPELLED;?>" /><?php print strtoupper($this->staff_m->STATUS_EXPELLED);?>
							<option value="<?php print $this->staff_m->STATUS_TRANSFERRED;?>" /><?php print strtoupper($this->staff_m->STATUS_TRANSFERRED);?>
							<option value="<?php print $this->staff_m->STATUS_RETIRED;?>" /><?php print strtoupper($this->staff_m->STATUS_RETIRED);?>
							<option value="<?php print $this->staff_m->STATUS_LEFT;?>" /><?php print strtoupper($this->staff_m->STATUS_LEFT);?>
							</select>
						</div>
						<div class="col-sm-2">
							<label class="text-muted">Minimum Salary</label>
							<select class="form-control select" ng-model="filter.salary" data-fouc>
								<option value="">Select Minimum Salary</option>
								<option value="2000">2000</option>
								<option value="5000">5000</option>
								<option value="10000">10000</option>
								<option value="50000">50000</option>
								<option value="100000">100000</option>
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

				<p class="text-muted">EMS let you add new staff. Please provide required data to register a new account. Ask your administrator to create a new role for you if target role is not available in drop down option...</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Bio Data</h6>
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
									<label class="text-muted">Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Full Name" ng-model="staff.name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">CNIC <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="National ID" ng-model="staff.cnic">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Mobile Number <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Mobile Number" ng-model="staff.mobile">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-mobile"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Basic Salary <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="15520" ng-model="staff.salary">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-coin-dollar"></i>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="form-group">
							<div class="row">

								<div class="col-sm-3">
									<label class="text-muted">Home Contact </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Home Contact Number" ng-model="staff.home_phone">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-phone2"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Father / Husband Name </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Father / Husband Name" ng-model="staff.guardian_name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-collaboration"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<label class="text-muted">Designation<span class="text-danger"> * </span></label>
									<select class="form-control select" ng-model="staff.role" data-fouc>
									<option value="">Select Role</option>
									<?php foreach ($roles as $row){?>            
									    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
								    <?php }?>
									</select>
								</div>
								<div class="col-sm-2">
									<label class="text-muted">Gender</label>
									<select class="form-control select" ng-model="staff.gender" data-fouc>
									<option value="male">Male</option>
									<option value="female">Female</option>
									<option value="other">Other</option>
									</select>
								</div>
								<div class="col-sm-2">
									<label class="text-muted">Blood Group</label>
									<select class="form-control select" ng-model="staff.blood_group" data-fouc>
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
						<div class="form-group">
							<div class="row">

								<div class="col-sm-4">
									<label class="text-muted">Qualification </label>
									<textarea rows="3" cols="3" class="form-control" placeholder="Recent Degree" ng-model="staff.qualification"></textarea>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Postal Address</label>
									<textarea rows="3" cols="3" class="form-control" placeholder="Postal Address"  ng-model="staff.postal_address"></textarea>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Home Address</label>
									<textarea rows="3" cols="3" class="form-control" placeholder="Home Address" ng-model="staff.home_address"></textarea>
								</div>

								
							</div>
						</div>

					</div>
				</div>

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Portal Registration</h6>
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
									<label class="text-muted">Staff ID<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Staff ID for staff portal login" ng-model="staff.staff_id">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user-check"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Password</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Password for staff portal login" ng-model="staff.password">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-key"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Email Address (if any) </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Email Address" ng-model="staff.email">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-envelop"></i>
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

								<div class="col-sm-6">
									<label class="text-muted">Favourite Subjects </label>
									<textarea rows="2" cols="3" class="form-control" placeholder="Favourite Subjects" ng-model="staff.favourite_subject"></textarea>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Experience</label>
									<textarea rows="2" cols="3" class="form-control" placeholder="if any" ng-model="staff.experience"></textarea>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Contract Details</label>
									<textarea rows="2" cols="3" class="form-control" placeholder="if any" ng-model="staff.contract"></textarea>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Annual Increment</label>
									<textarea rows="2" cols="3" class="form-control" placeholder="if decided at contract time" ng-model="staff.anual_increment"></textarea>
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

				<p class="text-muted">EMS let you update staff account here. Please change required data and click on save. Ask your administrator to create a new role for you if target role is not available in drop down option...</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Bio Data</h6>
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
									<label class="text-muted">Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Full Name" ng-model="selectedRow.name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">CNIC <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="National ID" ng-model="selectedRow.cnic">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Mobile Number <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Mobile Number" ng-model="selectedRow.mobile">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-mobile"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Basic Salary <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="15520" ng-model="selectedRow.salary">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-coin-dollar"></i>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="form-group">
							<div class="row">

								<div class="col-sm-3">
									<label class="text-muted">Home Contact </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Home Contact Number" ng-model="selectedRow.home_phone">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-phone2"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Father / Husban Name </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Father / Husband Name" ng-model="selectedRow.guardian_name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-collaboration"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<label class="text-muted">Designation<span class="text-danger"> * </span></label>
									<select class="form-control select" ng-model="selectedRow.role_id" data-fouc>
									<!-- <option value="">Select Role</option> -->
									<?php foreach ($roles as $row){?>            
									    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
								    <?php }?>
									</select>
								</div>
								<div class="col-sm-2">
									<label class="text-muted">Gender</label>
									<select class="form-control select" ng-model="selectedRow.gender" data-fouc>
									<option value="male">Male</option>
									<option value="female">Female</option>
									<option value="other">Other</option>
									</select>
								</div>
								<div class="col-sm-2">
									<label class="text-muted">Blood Group</label>
									<select class="form-control select" ng-model="selectedRow.blood_group" data-fouc>
									<!-- <option value="">Selec Boold Group</option> -->
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
						<div class="form-group">
							<div class="row">

								<div class="col-sm-4">
									<label class="text-muted">Qualification </label>
									<textarea rows="3" cols="3" class="form-control" placeholder="Recent Degree" ng-model="selectedRow.qualification"></textarea>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Postal Address</label>
									<textarea rows="3" cols="3" class="form-control" placeholder="Postal Address"  ng-model="selectedRow.postal_address"></textarea>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Home Address</label>
									<textarea rows="3" cols="3" class="form-control" placeholder="Home Address" ng-model="selectedRow.home_address"></textarea>
								</div>

								
							</div>
						</div>

					</div>
				</div>

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Portal Registration</h6>
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
									<label class="text-muted">Staff ID<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Staff ID for staff portal login" ng-model="selectedRow.staff_id">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user-check"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Email Address (if any) </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Email Address" ng-model="selectedRow.email">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-envelop"></i>
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

								<div class="col-sm-6">
									<label class="text-muted">Favourite Subjects </label>
									<textarea rows="2" cols="3" class="form-control" placeholder="Favourite Subjects" ng-model="selectedRow.favourite_subject"></textarea>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Experience</label>
									<textarea rows="2" cols="3" class="form-control" placeholder="if any" ng-model="selectedRow.experience"></textarea>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Contract Details</label>
									<textarea rows="2" cols="3" class="form-control" placeholder="if any" ng-model="selectedRow.contract"></textarea>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Annual Increment</label>
									<textarea rows="2" cols="3" class="form-control" placeholder="if decided at contract time" ng-model="selectedRow.anual_increment"></textarea>
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

				<p class="text-muted">EMS let the staff login into their portal. You may reset the password for staff portal if they forget the current password...</p>
				

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
									<label class="text-muted">New Passsword for staff id({{selectedRow.staff_id}})<span class="text-danger"> * </span></label>
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
				<h6 class="modal-title">Send SMS Notification To All Staff</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you send sms notitification to all staff after applying below filter. You need to enable sms notification feature from settings before sending the sms notification. <?php print $this->SMS_HOST_NOTE; ?></p>

				<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-filter3 mr-2"></i>Choose Staff Filters</legend>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<label class="text-muted">Staff Role</label>
							<select class="form-control select" ng-model="filter.role" data-fouc>
							<option value="">Select Designation</option>
							<?php foreach ($roles as $row){?>            
							    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
						    <?php }?>
							</select>
						</div>
						<div class="col-sm-2">
							<label class="text-muted">Status</label>
							<select class="form-control select" ng-model="filter.status" data-fouc>
							<option value="">Select Status</option>
							<option value="<?php print $this->staff_m->STATUS_ACTIVE;?>" /><?php print strtoupper($this->staff_m->STATUS_ACTIVE);?>
							<option value="<?php print $this->staff_m->STATUS_UNACTIVE;?>" /><?php print strtoupper($this->staff_m->STATUS_UNACTIVE);?>
							<option value="<?php print $this->staff_m->STATUS_EXPELLED;?>" /><?php print strtoupper($this->staff_m->STATUS_EXPELLED);?>
							<option value="<?php print $this->staff_m->STATUS_TRANSFERRED;?>" /><?php print strtoupper($this->staff_m->STATUS_TRANSFERRED);?>
							<option value="<?php print $this->staff_m->STATUS_RETIRED;?>" /><?php print strtoupper($this->staff_m->STATUS_RETIRED);?>
							<option value="<?php print $this->staff_m->STATUS_LEFT;?>" /><?php print strtoupper($this->staff_m->STATUS_LEFT);?>
							</select>
						</div>
						<div class="col-sm-2">
							<label class="text-muted">Minimum Salary</label>
							<select class="form-control select" ng-model="filter.salary" data-fouc>
								<option value="">Select Minimum Salary</option>
								<option value="2000">2000</option>
								<option value="5000">5000</option>
								<option value="10000">10000</option>
								<option value="50000">50000</option>
								<option value="100000">100000</option>
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
				<br>
				<p class="text-muted">Sms notification will be sent to filtered staff only. Incase you do not choose any filter then sms notificaton will be delivered to all registered staff of this campus...</p>

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