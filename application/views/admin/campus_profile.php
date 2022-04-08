<?php

$premium_modules=$this->module_m->get_modules_array();
$bg=array('bg-violet','bg-success','bg-info','bg-warning','bg-primary','bg-blue','bg-teal','bg-green','bg-slate','bg-indigo');
$teals=array(2,3,4,6,7);
$total_bg=count($bg)-1;
$total_teals=count($teals)-1;
/////////////////////////////////////////////////////////////////////////////////////////////
$notifications=$this->note_m->get_rows(array('campus_id'=>$record->mid));
$filter=array('campus_id'=>$record->mid);

$total_admins=$this->user_m->get_rows(array('campus_id'=>$record->mid,'type'=>$this->user_m->TYPE_MANAGER),'',true);
$total_students=$this->student_m->get_rows($filter,'',true);
$total_staff=$this->staff_m->get_rows($filter,'',true);

?>
<!-- Main content -->
<div class="content-wrapper" ng-controller="mozzCtrl"  ng-cloak>
	<!-- Page header -->
	<div class="page-header page-header-light border-bottom-0" ng-init="rid='<?php print $record->mid;?>'">

		<!-- Top breadcrumb line -->
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
					<a class="breadcrumb-item">Campus</a>
					<span class="breadcrumb-item active">Details</span>
				</div>

				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

		</div>
		<!-- /breadcrumb line -->

	
		<!-- Page header content -->
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Campus Profile</span> - <?php print $record->name ?></h4>
				<a class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
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
						<a href="#profile" class="navbar-nav-link active" data-toggle="tab">
							<i class="icon-profile mr-2"></i>
							Dashboard
						</a>
					</li>
					<li class="nav-item">
						<a href="#admins" class="navbar-nav-link" data-toggle="tab" ng-click="loadAdmins()">
							<i class="icon-user-tie mr-2"></i>
							Campus Admins
						</a>
					</li>
					<li class="nav-item">
						<a href="#history" class="navbar-nav-link" data-toggle="tab" ng-click="loadHistory()">
							<i class="icon-history mr-2"></i>
							History
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
					<!-- Profile info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Campus information</h5>
						</div>

						<div class="card-body">
							<!-- Dashboard content -->
							<div class="row">
								<div class="col-xl-12">
									<!-- Quick stats boxes -->
									<div class="row">
										<div class="col-lg-4">
											<!-- Members online -->
											<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
											<div class="card <?php print $bg_color.$t_color;?>">
												<div class="card-body">
													<div class="d-flex">
														<h3 class="font-weight-semibold mb-0"><?php print $total_admins;?></h3>
														<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto">As of <?php print date('d-M-Y');?></span>
								                	</div>					                	
								                	<div>
														<strong>Total Administrators</strong>
														<div class="font-size-sm opacity-85">Total count of all campuses</div>
													</div>
												</div>

												<div class="container-fluid">
													<div id="members-online"></div>
												</div>
											</div>
											<!-- /members online -->
										</div>
										<div class="col-lg-4">
											<!-- Members online -->
											<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
											<div class="card <?php print $bg_color.$t_color;?>">
												<div class="card-body">
													<div class="d-flex">
														<h3 class="font-weight-semibold mb-0"><?php print $total_staff;?></h3>
														<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto">As of <?php print date('d-M-Y');?></span>
								                	</div>					                	
								                	<div>
														<strong>Total Staff</strong>
														<div class="font-size-sm opacity-85">Total count including left overs</div>
													</div>
												</div>

												<div class="container-fluid">
													<div id="members-online"></div>
												</div>
											</div>
											<!-- /members online -->
										</div>
										<div class="col-lg-4">
											<!-- Members online -->
											<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
											<div class="card <?php print $bg_color.$t_color;?>">
												<div class="card-body">
													<div class="d-flex">
														<h3 class="font-weight-semibold mb-0"><?php print $total_students;?></h3>
														<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto">As of <?php print date('d-M-Y');?></span>
								                	</div>					                	
								                	<div>
														<strong>Registered Students</strong>
														<div class="font-size-sm opacity-85">Total count including alumni &amp; Left Overs</div>
													</div>
												</div>

												<div class="container-fluid">
													<div id="members-online"></div>
												</div>
											</div>
											<!-- /members online -->
										</div>



									</div>
									<!-- /quick stats boxes -->
								</div>
							</div>
							<!-- /dashboard content -->



						</div>
					</div>
					<!-- /profile info -->
		    	</div>

			    <div class="tab-pane fade <?php print $tab=='admins' ? 'active show': '';?>" id="admins">
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Campus Administrators List 
							<span class="d-block font-size-base text-muted">Find below the campus administrators list of this campus. You may register as many campus administrators as you need.
							</span>
							</h5>
							<button class="btn btn-success btn-sm font-weight-bold"  data-target="#add-admin" <?php print $this->MODAL_OPTIONS;?>>
								<i class="icon-plus-circle2"></i> Register Administrator</button>
						</div>
						
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadAdmins()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadAdmins()" class="btn btn-success btn-lg">
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
										<th ng-click="sortBy('name');loadAdmins();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Name</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='name'"></i> 
										</th>
										<th class="font-weight-bold"><span class="m-1">Admin ID</span></th>
										<th class="font-weight-bold"><span class="m-1">Contact Number</span></th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.name}}</td>
										<td>{{row.user_id}}</td>
					  					<td>{{row.mobile}}</td>  
										<td>
											<div class="list-icons float-right">
												<div class="list-icons-item dropdown">
							                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item"  data-target="#edit-admin" <?php print $this->MODAL_OPTIONS;?> ng-click="selectRow(row)">
															<i class="icon-compose"></i> Update
														</a>
														<a class="dropdown-item"  data-target="#edit-pass" <?php print $this->MODAL_OPTIONS;?> ng-click="selectRow(row)">
															<i class="icon-lock"></i> Change Password
														</a>
														<div class="dropdown-divider"></div>
														<a ng-href="<?php print $this->CONT_ROOT.'login/';?>{{row.mid}}" class="dropdown-item" target="_blank"><i class="icon-user-lock"></i> Account Login</a>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" ng-click="delAdmin(row)"><i class="icon-cross"></i> Delete Account</a>
													</div>
												</div>
											</div>
										</td>
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadAdmins()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadAdmins()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='history' ? 'active show': '';?>" id="history">
					
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">History</h4>
							<span class="text-muted">System tracks and save all the important events regarding this campus for record purpose. You may go through the history to analyze the campus performance.</span>
						</div>
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadHistory()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadHistory()" class="btn btn-success btn-lg">
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
										<th ng-click="sortBy('message');loadHistory();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Description</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='message'"></i> 
										</th>
										<th ng-click="sortBy('date');loadHistory();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Date</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='date'"></i> 
										</th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.message}}</td>
					  					<td>{{row.date}}</td> 
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadHistory()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadHistory()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
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
<div id="add-admin" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Register New Campus Administrator</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">register as many campuses admins as you need. Please provide required information to proceed next...</p>


				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Administrator Information</h6>
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
									<label class="text-muted">Admin Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Admin name" ng-model="entry.name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Admin Mobile Number <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Admin mobile number" ng-model="entry.mobile">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-mobile"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Account Password <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="password" class="form-control form-control-lg" placeholder="Admin Account Password" ng-model="entry.password">
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
				<button ng-click="saveAdmin()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /add modal -->

<!-- edit modal -->
<div id="edit-admin" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update Campus administrator({{selectedRow.name}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">update campus admin account here. Please provide required information to proceed next...</p>				
				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Admin Information</h6>
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
									<label class="text-muted">Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="name" ng-model="selectedRow.name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Mobile Number <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Mobile Number" ng-model="selectedRow.mobile">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-mobile"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Admin ID <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Admin ID" ng-model="selectedRow.user_id">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user"></i>
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
				<button ng-click="updateAdmin()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /edit modal -->

<!-- edit modal -->
<div id="edit-pass" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update Password ({{selectedRow.name}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">change password of campus administrator on the fly so you could reset password on his/her behalf if he/she forget the password.</p>				
				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Change Password</h6>
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
									<label class="text-muted">New Password <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="password" class="form-control form-control-lg" placeholder="Admin New Password" ng-model="entry.password">
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
				<button ng-click="updateAdminPassword()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /edit modal -->



</div>
<!-- /main content -->