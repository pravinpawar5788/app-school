<?php
$roles=$this->stf_role_m->get_rows(array(),array('orderby'=>'title ASC') );

?>
<!-- Main content -->
<div class="content-wrapper" ng-controller="mozzCtrl"  ng-cloak>
	<!-- Page header -->
	<div class="page-header page-header-light border-bottom-0" ng-init="usrid='<?php print $this->LOGIN_USER->mid;?>'">

		<!-- Top breadcrumb line -->
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
					<a class="breadcrumb-item">Staff</a>
					<span class="breadcrumb-item active">Activity History</span>
				</div>

				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="breadcrumb justify-content-center">
				</div>
			</div>
		</div>
		<!-- /breadcrumb line -->

	
		<!-- Page header content -->
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Staff</span> - Activity Record</h4>
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
					Record navigation
				</button>
			</div>

			<div class="navbar-collapse collapse" id="navbar-second">
				<ul class="nav navbar-nav">
					<li class="nav-item">
						<a href="#profile" class="navbar-nav-link active" data-toggle="tab">
							<i class="icon-profile mr-2"></i>
							Profile
						</a>
					</li>
					<li class="nav-item">
						<a href="#allowances" class="navbar-nav-link" data-toggle="tab" ng-click="loadAllowances()">
							<i class="icon-cash mr-2"></i>
							Allowances
						</a>
					</li>
					<li class="nav-item">
						<a href="#accounts" class="navbar-nav-link" data-toggle="tab" ng-click="loadAdvance()">
							<i class="icon-cash mr-2"></i>
							Accounts
						</a>
					</li>
					<li class="nav-item">
						<a href="#history" class="navbar-nav-link" data-toggle="tab" ng-click="loadHistory()">
							<i class="icon-history mr-2"></i>
							History
						</a>
					</li>
					<li class="nav-item">
						<a href="#academics" class="navbar-nav-link" data-toggle="tab" ng-click="loadAcademic()">
							<i class="icon-graduation2 mr-2"></i>
							Academics
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
	<?php $this->load->view($this->LIB_VIEW_DIR.'includes/alert_inc');?>

		<!-- Inner container -->
		<div class="d-flex align-items-start flex-column flex-md-row">

			<!-- Left content -->
			<div class="tab-content w-100 overflow-auto order-2 order-md-1">


			    <div class="tab-pane fade <?php print empty($tab) || $tab=='profile' ? 'active show': '';?>" id="profile">
					<!-- Profile info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Profile information</h5>
							<p class="text-muted">Profile infortmation of this member</p>
						</div>

						<div class="card-body">
							<div class="form-group">
							<div class="row">
								<div class="col-md-4">
									<label class="text-muted">Full Name</label>
									<input type="text" value="<?php print ucwords(strtolower($this->LOGIN_USER->name));?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Father/Husband Name</label>
									<input type="text" value="<?php print ucwords(strtolower($this->LOGIN_USER->guardian_name));?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Mobile</label>
									<input type="text" value="<?php print $this->LOGIN_USER->mobile;?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">National ID</label>
									<input type="text" value="<?php print $this->LOGIN_USER->cnic;?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Email Address</label>
									<input type="text" value="<?php print strtolower($this->LOGIN_USER->email);?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Gender</label>
									<input type="text" value="<?php print ucwords(strtolower($this->LOGIN_USER->gender));?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Blood Group</label>
									<input type="text" value="<?php print strtoupper($this->LOGIN_USER->blood_group);?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Designation</label>
									<input type="text" value="<?php print $this->stf_role_m->get_by_primary($this->LOGIN_USER->role_id)->title;?>" class="form-control" readonly="readonly" >
								</div>
								<div class="col-md-4">
									<label class="text-muted">Basic Salary</label>
									<input type="text" value="<?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$this->LOGIN_USER->salary;?>" class="form-control" readonly="readonly" >
								</div>
							</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-6">
										<label class="text-muted">Home Address</label>
										<textarea class="form-control" readonly="readonly"><?php print $this->LOGIN_USER->home_address;?></textarea>
									</div>
									<div class="col-md-6">
										<label class="text-muted">Qualification</label>
										<textarea class="form-control" readonly="readonly"><?php print $this->LOGIN_USER->qualification;?></textarea>
									</div>
									<div class="col-md-6">
										<label class="text-muted">Experience</label>
										<textarea class="form-control" readonly="readonly"><?php print $this->LOGIN_USER->experience;?></textarea>
									</div>
									<div class="col-md-6">
										<label class="text-muted">Favourite Subjects</label>
										<textarea class="form-control" readonly="readonly"><?php print $this->LOGIN_USER->favourite_subject;?></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- /profile info -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='allowances' ? 'active show': '';?>" id="allowances">
					
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Allowances</h4>
							<span class="text-muted">An allowance is money that is given to you by organization, usually on a with monthly salary, in order to help you pay for the things that you need. It is worth to note that this depends on organization pay &amp; allowace structure.</span>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th ng-click="sortBy('title');loadAllowances();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Title</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
										</th>
										<th ng-click="sortBy('amount');loadAllowances();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Amount</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='amount'"></i> 
										</th>
										<th ng-click="sortBy('date');loadAllowances();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Registered On</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='date'"></i> 
										</th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td>
					  					<td><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]?>{{row.amount}}</td>  
					  					<td>{{row.date}}</td>
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadAllowances()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadAllowances()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='accounts' ? 'active show': '';?>" id="accounts">
					
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Advance Salary</h4>
							<span class="text-muted">Find below the amount(s) of advance salary (only if you have acquired advance salary). This amount will deducted from your upcoming salary. You may consult accountant to convert this amount into installments if organization have any policy regarding this.</span>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th ng-click="sortBy('title');loadAdvance();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Title</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
										</th>
										<th ng-click="sortBy('amount');loadAdvance();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Amount</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='amount'"></i> 
										</th>
										<th ng-click="sortBy('date');loadAdvance();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Received On</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='date'"></i> 
										</th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td>
					  					<td><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]?>{{row.amount}}</td>  
					  					<td>{{row.date}}</td> 
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadAdvance()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadAdvance()">
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
							<span class="text-muted">System tracks and save all the important events regarding your activity. Find below that important activity events about you.</span>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th ng-click="sortBy('title');loadHistory();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Title</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
										</th>
										<th ng-click="sortBy('description');loadHistory();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Description</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='description'"></i> 
										</th>
										<th ng-click="sortBy('date');loadHistory();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Date</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='date'"></i> 
										</th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td>
					  					<td>{{row.description}}</td>  
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





			    <div class="tab-pane fade <?php print $tab=='academics' ? 'active show': '';?>" id="academics">
			    	<?php if(intval($this->CAMPUSSETTINGS[$this->campus_setting_m->_PRM_PORTAL_STAFF_EDIT])>0){ ?>
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Academics 
							<span class="d-block font-size-base text-muted">Academics module help you update your academic record on your portal.
							</span>
							</h5>
						</div>

						<div class="card-body">
							<div class="form-group">
							<div class="row">
								<div class="col-md-4">
									<label class="text-muted">Qualification<span class="text-danger"> *</span></label>
									<input type="text" class="form-control" placeholder="E.g MA" ng-model="academic.qualification">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Institute<span class="text-danger"> *</span></label>
									<input type="text" class="form-control" placeholder="Institute name" ng-model="academic.institute">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Program</label>
									<input type="text" class="form-control" placeholder="E.g English" ng-model="academic.program">
								</div>
								<div class="col-md-3">
									<label class="text-muted">Registration Number</label>
									<input type="text" class="form-control" placeholder="E.g Registration Number" ng-model="academic.registration_no">
								</div>
								<div class="col-md-3">
									<label class="text-muted">Roll Number</label>
									<input type="text" class="form-control" placeholder="E.g Roll Number" ng-model="academic.roll_number">
								</div>
								<div class="col-md-3">
									<label class="text-muted">Year <span class="text-danger"> * </span></label>
									<input type="year" class="form-control" placeholder="Passing Year" ng-model="academic.year">
								</div>
								<div class="col-md-3">
									<button ng-click="saveAcademic()" class="btn btn-success btn-lg ml-2 mt-3">
										<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-checkmark-circle2':!appConfig.btnClickedSave}" class="ml-2"></i>
									</button>
								</div>
							</div>
							</div>

						</div>
					</div>
					<!-- /save info -->
					<?php } ?>
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Academice Record</h4>
							<span class="text-muted">Find below the record of your qualification registered in your account. You may consult administrator to update your academice record.</span>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th ng-click="sortBy('qualification');loadAcademic();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Qualification</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='qualification'"></i> 
										</th>
										<th ng-click="sortBy('year');loadAcademic();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Passing Year</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='year'"></i> 
										</th>
										<th ng-click="sortBy('program');loadAcademic();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Program</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='program'"></i> 
										</th>
										<th ng-click="sortBy('institute');loadAcademic();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Institute</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='institute'"></i> 
										</th>
										<?php if(intval($this->CAMPUSSETTINGS[$this->campus_setting_m->_PRM_PORTAL_STAFF_EDIT])>0){ ?>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
										<?php } ?>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.qualification}}</td>
					  					<td>{{row.year}}</td>  
					  					<td>{{row.program}}</td> 
					  					<td>{{row.institute}}</td> 
					  					<?php if(intval($this->CAMPUSSETTINGS[$this->campus_setting_m->_PRM_PORTAL_STAFF_EDIT])>0){ ?>
										<td class="text-center">
											<div class="list-icons">
												<div class="list-icons-item dropdown">
													<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9 mr-5"></i></a>
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item" ng-click="selectAcademic(row)">
															<i class="icon-compose"></i> Update
														</a>
													</div>
												</div>
											</div>
										</td>
										<?php } ?>
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadAcademic()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadAcademic()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='awards' ? 'active show': '';?>" id="awards">
					
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Earned Awards</h4>
							<span class="text-muted">Following are the awards catched by you during your job in this organization. Later, you can acquire hard copy from school organization.</span>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th ng-click="sortBy('title');loadAwards();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Award</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
										</th>
										<th ng-click="sortBy('remarks');loadAwards();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Remarks</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='remarks'"></i> 
										</th>
										<th class="font-weight-bold">Date</th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td>
					  					<td>{{row.remarks}}</td> 
					  					<td>{{row.date}}</td> 
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadAwards()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadAwards()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='achievements' ? 'active show': '';?>" id="achievements">
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Endorsements</h4>
							<span class="text-muted">Following are your endorsements during your job in this organization.</span>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th ng-click="sortBy('title');loadAchievements();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Title / Badge</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
										</th>
										<th ng-click="sortBy('remarks');loadAchievements();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Remarks</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='remarks'"></i> 
										</th>
										<th class="font-weight-bold">Date</th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td>
					  					<td>{{row.remarks}}</td>  
					  					<td>{{row.date}}</td>  
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadAchievements()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadAchievements()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='discipline' ? 'active show': '';?>" id="discipline">
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Issued Notices</h4>
							<span class="text-muted">Following are the notices issued to you during your job in this organization. Later, you may acquire hard copy of notice from organization</span>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th ng-click="sortBy('title');loadDiscipline();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Notice</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
										</th>
										<th ng-click="sortBy('remarks');loadDiscipline();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Remarks</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='remarks'"></i> 
										</th>
										<th class="font-weight-bold">Date</th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td>
					  					<td>{{row.remarks}}</td> 
					  					<td>{{row.date}}</td> 
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadDiscipline()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadDiscipline()">
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
								<img class="img-fluid rounded-circle" src="<?php print $this->UPLOADS_ROOT.'images/staff/profile/'.$this->LOGIN_USER->image;?>" width="170" height="170" alt="">
							</div>
								
			    			<?php if(intval($this->CAMPUSSETTINGS[$this->campus_setting_m->_PRM_PORTAL_STAFF_EDIT])>0){ ?>
							<?php echo form_open_multipart($this->CONT_ROOT.'upload_picture');?>
							<input id="clientImageFile" type="file" name="file" style="display: none;" onchange="showname()">
							<input type="button" value="Browse" class="btn btn-light btn-sm" onclick="document.getElementById('clientImageFile').click();" />
							<button class="btn btn-success btn-sm"><i class="icon-upload position-right"></i><strong> Upload </strong></button>   
							<?php echo form_close(); ?>
							<span class="text-muted" id="selected-file"></span>
							<?php } ?>
							<hr>
				    		<h6 class="font-weight-semibold mb-0"><?php print ucwords(strtolower($this->LOGIN_USER->name));?></h6>
				    		<span class="d-block text-muted"><?php print $this->stf_role_m->get_by_primary($this->LOGIN_USER->role_id)->title;?></span>
				    		<span class="d-block text-muted">
				    			<span class="m-2">Mobile:</span><?php print $this->LOGIN_USER->mobile;?>
				    		</span>
				    		<span class="d-block text-muted">
				    			<span class="m-2">National ID:</span><?php print $this->LOGIN_USER->cnic;?>
				    		</span>
				    		<span class="d-block text-muted">
				    			<span class="m-2">Basic Salary:</span><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$this->LOGIN_USER->salary;?>
				    		</span>

				    	</div>
			    	</div>
			    	<!-- /user card -->

						

					<!-- Navigation -->
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="card-title font-weight-semibold">Sub Navigation</span>
						</div>

						<div class="card-body p-0">
							<ul class="nav nav-sidebar my-2">
								<li class="nav-item">
									<a href="#awards" class="nav-link" data-toggle="tab" ng-click="loadAwards()">
										<i class="icon-image2"></i>
										 Awards
										<span class="badge bg-success badge-pill ml-auto">{{member.total_awards}}</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#discipline" class="nav-link" data-toggle="tab" ng-click="loadDiscipline()">
										<i class="icon-image2"></i>
										 Notice's
										<span class="badge bg-danger badge-pill ml-auto">{{member.total_punishments}}</span>
									</a>
								</li>
								<li class="nav-item">
									<a href="#achievements" class="nav-link" data-toggle="tab" ng-click="loadAchievements()">
										<i class="icon-image2"></i>
										 Endorsements
										<span class="badge bg-info badge-pill ml-auto">{{member.total_acheivements}}</span>
									</a>
								</li>
							</ul>
						</div>
					</div>
					<!-- /navigation -->




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