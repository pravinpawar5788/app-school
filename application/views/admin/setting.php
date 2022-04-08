<?php

$premium_modules=$this->module_m->get_modules_array();
$bg=array('bg-violet','bg-success','bg-info','bg-warning','bg-primary','bg-blue','bg-teal','bg-green','bg-slate','bg-indigo');
$teals=array(2,3,4,6,7);
$total_bg=count($bg)-1;
$total_teals=count($teals)-1;
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
					<a class="breadcrumb-item">Organization</a>
					<span class="breadcrumb-item active">Settings</span>
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
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Organization</span> - Settings</h4>
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
						<a href="#profile" class="navbar-nav-link" data-toggle="tab" ng-click="loadSessions()">
							<i class="icon-gear mr-1"></i>
							General Settings
						</a>
					</li>
					<li class="nav-item">
						<a href="#modules" class="navbar-nav-link" data-toggle="tab" ng-click="loadModules()">
							<i class="icon-graduation2 mr-1"></i>
							Modules
						</a>
					</li>
					<li class="nav-item">
						<a href="#sessions" class="navbar-nav-link" data-toggle="tab" ng-click="loadSessions()">
							<i class="icon-calendar mr-1"></i>
							Sessions
						</a>
					</li>
					<li class="nav-item">
						<a href="#awards" class="navbar-nav-link" data-toggle="tab" ng-click="loadAwards()">
							<i class="icon-image2 mr-1"></i>Awards
						</a>
					</li>
					<li class="nav-item">
						<a href="#discipline" class="navbar-nav-link" data-toggle="tab" ng-click="loadDiscipline()">
							<i class="icon-image2 mr-1"></i>Notice's
						</a>
					</li>
					<li class="nav-item">
						<a href="#roles" class="navbar-nav-link" data-toggle="tab" ng-click="loadRoles()">
							<i class="icon-user-tie mr-1"></i>Staff Roles
						</a>
					</li>
					<li class="nav-item">
						<a href="#concessions" class="navbar-nav-link" data-toggle="tab" ng-click="loadConcessions()">
							<i class="icon-user-minus mr-1"></i>Concession Types
						</a>
					</li>
					<li class="nav-item">
						<a href="#stdgroups" class="navbar-nav-link" data-toggle="tab" ng-click="loadStdGroup()">
							<i class="icon-list mr-1"></i>Student Groups
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
					<!-- settings -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">General Settings 
							<span class="d-block font-size-base text-muted">You can update here the organizational settings. These settings will effect all the campuses of this organization.
							</span>
							</h5>
						</div>
						
						<div class="card-body">
							<div class="form-group">
							<div class="row">
								<div class="col-md-4">
									<label class="text-muted">Currency Symbol</label>
									<input type="text" class="form-control" placeholder="E.g $" ng-model="settings.currency_symbol">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Studnet ID Start Key</label>
									<input type="text" class="form-control" placeholder="E.g STD" ng-model="settings.stdid_key">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Staff ID Start Key</label>
									<input type="text" class="form-control" placeholder="E.g STF" ng-model="settings.stfid_key">
								</div>
							</div>
							</div>							
							<div class="form-group">
							<div class="row">
								<div class="col-md-5">
									<label class="text-muted">File Upload size (Rec: 2048)</label>
									<input type="text" class="form-control" placeholder="Max Upload File Size (in MB)" ng-model="settings.max_upload_size">
								</div>
								<div class="col-md-5">
									<label class="text-muted">Authorization Background</label>
									<select class="form-control select" ng-model="settings.custom_auth_bg" data-fouc>
									<option value="0">Default Background Authorization</option>
									<option value="1">Logo Background Authorization</option>
									</select>
								</div>
								<div class="col-md-2">
									<button ng-click="saveGenralSettings()" class="btn btn-success mt-4">
										<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-checkmark-circle2':!appConfig.btnClickedSave}" class="ml-2"></i>
									</button>
								</div>
							</div>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">SMS Settings 
							<span class="d-block font-size-base text-muted">You can update here the sms settings. These settings will effect all the campuses of this institute.
							</span>
							</h5>
						</div>
						
						<div class="card-body">
							<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<label class="text-muted">SMS Vendor</label>
									<select class="form-control select" ng-model="settings.sms_vendor" data-fouc>
									<option value="akspk">www.akspk.com</option>
									<option value="regularsms">www.regularsms.pk</option>
									</select>
								</div>
								<div class="col-md-6">
									<label class="text-muted">Type</label>
									<select class="form-control select" ng-model="settings.sms_type" data-fouc>
									<option value="brand">Brand SMS / Business Sender ID</option>
									<option value="regular">Regular SMS / Any Sender ID</option>
									</select>
								</div>
							</div>
							</div>
							<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<label class="text-muted">User ID/ User Name / Mobile<span class="text-danger"> *</span></label>
									<input type="text" class="form-control" placeholder="Enter user id" ng-model="settings.sms_api_username">
								</div>
								<div class="col-md-6">
									<label class="text-muted">API KEY / Password</label>
									<input type="text" class="form-control" placeholder="API Key " ng-model="settings.sms_api_key">
								</div>
							</div>
							</div>
							<div class="form-group">
							<div class="row">
								<div class="col-md-6" ng-show="settings.sms_type=='brand'">
									<label class="text-muted">Sender ID / Masking</label>
									<input type="text" class="form-control" placeholder="Sender ID or Masking" ng-model="settings.sms_mask">
								</div>
								<div class="col-md-6">
									<label class="text-muted">Language</label>
									<select class="form-control select" ng-model="settings.sms_lang" data-fouc>
									<option value="en">English</option>
									<option value="ur">Urdu (Pakistan)</option>
									</select>
								</div>
							</div>
							</div>
							<div class="form-group">
							<div class="row">
								<div class="col-md-12">
									<button ng-click="saveSmsSending(1)" class="btn btn-success" ng-show="settings.sms_sending!=='1'">
										<span class="font-weight-bold"> Enable SMS Sending</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-checkmark-circle2':!appConfig.btnClickedSave}" class="ml-2"></i>
									</button>
									<button ng-click="saveSmsSending(0)" class="btn btn-danger" ng-show="settings.sms_sending==='1'">
										<span class="font-weight-bold"> Disable SMS Sending</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-cross':!appConfig.btnClickedSave}" class="ml-2"></i>
									</button>
									<button ng-click="saveGenralSettings()" class="btn btn-success float-right">
										<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-checkmark-circle2':!appConfig.btnClickedSave}" class="ml-2"></i>
									</button>
								</div>
							</div>
							</div>
						</div>
					</div>
					<!-- /settings -->
		    	</div>

			    <div class="tab-pane fade <?php print $tab=='modules' ? 'active show': '';?>" id="modules">
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Modules Subscirption 
							<span class="d-block font-size-base text-muted">Modules enhance the features of software so that you receive most out of it. You may subscribe the modules if you need the module features.
							</span>
							</h5>
						</div>
						
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									
									<select class="form-control select" ng-model="filter.module" data-fouc>
										<option value="">Select Module</option>
										<?php foreach($premium_modules as $key=>$value){?>
										<option value="<?php print $key?>"><?php print $value ?></option>
										<?php }?>
									</select>
								</div>

								<div class="input-group-append">
									<button ng-click="saveModule()" class="btn btn-success btn-sm">
									<span class="font-weight-bold"> Subscribe</span>
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
										<th ng-click="sortBy('name');loadModules();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Name</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='name'"></i> 
										</th>
										<th>Status</th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td class="font-weight-bold">{{row.module_name}}</td>
						                <td>
						                	<div class="btn-group">
												<a ng-show="row.status==='<?php print $this->module_m->STATUS_ACTIVE;?>'" href="#" class="badge bg-success dropdown-toggle" data-toggle="dropdown">Active</a>
												<a ng-show="row.status!=='<?php print $this->module_m->STATUS_ACTIVE;?>'" href="#" class="badge bg-danger dropdown-toggle" data-toggle="dropdown">Disabled</a>
												
												<div class="dropdown-menu dropdown-menu-right">
													<a href="#" class="dropdown-item" ng-show="row.status!=='<?php print $this->module_m->STATUS_ACTIVE;?>'" ng-click="updateModuleStatus(row, '<?php print $this->module_m->STATUS_ACTIVE;?>');"><span class="badge badge-mark mr-2 bg-success border-success"></span> Activate Module</a>
													<a href="#" class="dropdown-item" ng-show="row.status==='<?php print $this->module_m->STATUS_ACTIVE;?>'" ng-click="updateModuleStatus(row, '<?php print $this->module_m->STATUS_UNACTIVE;?>');"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Disable Module</a>
												</div>
											</div>
					                	</td>
										<td>
											<div class="list-icons float-right">
												<div class="list-icons-item dropdown">
							                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item" ng-click="delModule(row)"><i class="icon-cross"></i> Remove</a>
													</div>
												</div>
											</div>
										</td>
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadModules()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadModules()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br>
							<p class="text-muted">
								<?php foreach($premium_modules as $key=>$value){?>
									<span><code><?php print $value ?>: </code></span><?php print $this->module_m->get_module_description($key); ?><br>
								<?php }?>
							</p>
							<br><br>
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
							<span class="text-muted">System tracks and save all the important events regarding this organization for record purpose. You may go through the history to analyze the organization performance.</span>
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
										<th class="font-weight-bold">Campus</th>
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
										<td>{{row.campus}}</td>
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
		    	<div class="tab-pane fade <?php print $tab=='sessions' ? 'active show': '';?>" id="sessions">
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Sessions 
							<span class="d-block font-size-base text-muted">An academic session is the time period during which a school perform academic activities. You may register sessions here. Later campus admin can use session to manage academic activities.
							</span>
							</h5>
							<button class="btn btn-success btn-sm font-weight-bold"  data-target="#add-session" <?php print $this->MODAL_OPTIONS;?>>
								<i class="icon-plus-circle2"></i> Create New Session</button>
						</div>
						
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadSessions()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadSessions()" class="btn btn-success btn-lg">
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
										<th ng-click="sortBy('title');loadSessions();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Session</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
										</th>
										<th class="font-weight-bold">Academic Year</th>
										<th class="font-weight-bold">Start Date</th>
										<th class="font-weight-bold">End Date</th>
										<th class="font-weight-bold">Status</th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td> 
										<td>{{row.year}}</td> 
										<td>{{row.start_date}}</td> 
										<td>{{row.end_date}}</td> 
						                <td>
						                	<div class="btn-group">
												<a ng-show="row.status==='<?php print $this->session_m->STATUS_ACTIVE;?>'" href="#" class="badge bg-success dropdown-toggle" data-toggle="dropdown">
													<?php print strtoupper($this->session_m->STATUS_ACTIVE);?></a>
												<a ng-show="row.status==='<?php print $this->session_m->STATUS_UPCOMING;?>'" href="#" class="badge bg-info dropdown-toggle" data-toggle="dropdown">
													<?php print strtoupper($this->session_m->STATUS_UPCOMING);?></a>
												<a ng-show="row.status==='<?php print $this->session_m->STATUS_PASSED;?>'" href="#" class="badge bg-danger dropdown-toggle" data-toggle="dropdown">
													<?php print strtoupper($this->session_m->STATUS_PASSED);?></a>
												
												<div class="dropdown-menu dropdown-menu-right" ng-show="row.status!=='<?php print $this->session_m->STATUS_PASSED;?>'">
													<a href="#" class="dropdown-item" ng-show="row.status==='<?php print $this->session_m->STATUS_UPCOMING;?>'" ng-click="changeStatus(row, '<?php print $this->session_m->STATUS_ACTIVE;?>');"><span class="badge badge-mark mr-2 bg-success border-success"></span> Activate</a>
													<a href="#" class="dropdown-item" ng-show="row.status==='<?php print $this->session_m->STATUS_ACTIVE;?>'" ng-click="changeStatus(row, '<?php print $this->session_m->STATUS_PASSED;?>');"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Mark Passed</a>
												</div>
											</div>
					                	</td>
										<td>
											<div class="list-icons float-right" ng-show="row.status!=='<?php print $this->session_m->STATUS_PASSED;?>'">
												<div class="list-icons-item dropdown">
							                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item"  data-target="#edit-session" <?php print $this->MODAL_OPTIONS;?> ng-click="selectRow(row)">
															<i class="icon-compose"></i> Update
														</a>
														<div class="dropdown-divider" ng-show="row.status==='<?php print $this->session_m->STATUS_UPCOMING;?>'"></div>
														<a class="dropdown-item" ng-click="delSession(row)" ng-show="row.status==='<?php print $this->session_m->STATUS_UPCOMING;?>'">
															<i class="icon-cross"></i> Remove </a>
													</div>
												</div>
											</div>
										</td>
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadSessions()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadSessions()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>


		    	<div class="tab-pane fade <?php print $tab=='awards' ? 'active show': '';?>" id="awards">
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Awards List 
							<span class="d-block font-size-base text-muted">An award is something given to a person like a sports man in recognition of his/her excellence in a certain field. You may register your organization specific awards here. Later campus admin can assign the awards to students and staff.
							</span>
							</h5>
							<button class="btn btn-success btn-sm font-weight-bold"  data-target="#add-award" <?php print $this->MODAL_OPTIONS;?>>
								<i class="icon-plus-circle2"></i> New Award</button>
						</div>
						
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadAwards()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadAwards()" class="btn btn-success btn-lg">
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
										<th ng-click="sortBy('title');loadAwards();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Award</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
										</th>
										<th class="font-weight-bold"><span class="m-1">Description</span></th>
										<th class="font-weight-bold"><span class="m-1">Date</span></th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td>
					  					<td>{{row.template}}</td>  
					  					<td>{{row.date}}</td>  
										<td>
											<div class="list-icons float-right">
												<div class="list-icons-item dropdown">
							                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item"  data-target="#edit-award" <?php print $this->MODAL_OPTIONS;?> ng-click="selectRow(row)">
															<i class="icon-compose"></i> Update
														</a>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" ng-click="delAward(row)"><i class="icon-cross"></i> Remove </a>
													</div>
												</div>
											</div>
										</td>
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
		    	<div class="tab-pane fade <?php print $tab=='discipline' ? 'active show': '';?>" id="discipline">
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Notices List 
							<span class="d-block font-size-base text-muted">Notice is the legal concept describing a requirement that a party be aware of legal process affecting their rights, obligations or duties. You may register your organization specific notices here. Later campus admin can assign the notice to students and staff.
							</span>
							</h5>
							<button class="btn btn-success btn-sm font-weight-bold"  data-target="#add-discipline" <?php print $this->MODAL_OPTIONS;?>>
								<i class="icon-plus-circle2"></i> New Notice</button>
						</div>
						
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadDiscipline()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadDiscipline()" class="btn btn-success btn-lg">
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
										<th ng-click="sortBy('title');loadDiscipline();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Notice</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
										</th>
										<th class="font-weight-bold"><span class="m-1">Description</span></th>
										<th class="font-weight-bold"><span class="m-1">Date</span></th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td>
					  					<td>{{row.template}}</td>  
					  					<td>{{row.date}}</td>  
										<td>
											<div class="list-icons float-right">
												<div class="list-icons-item dropdown">
							                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item"  data-target="#edit-discipline" <?php print $this->MODAL_OPTIONS;?> ng-click="selectRow(row)">
															<i class="icon-compose"></i> Update
														</a>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" ng-click="delDiscipline(row)"><i class="icon-cross"></i> Remove </a>
													</div>
												</div>
											</div>
										</td>
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
		    	<div class="tab-pane fade <?php print $tab=='roles' ? 'active show': '';?>" id="roles">
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Staff Roles 
							<span class="d-block font-size-base text-muted">A role is prescribed or expected behavior associated with a particular position or status in a group or organization. You may register your organization specific staff roles here. Later campus admin can assign the role to staff.
							</span>
							</h5>
							<button class="btn btn-success btn-sm font-weight-bold"  data-target="#add-role" <?php print $this->MODAL_OPTIONS;?>>
								<i class="icon-plus-circle2"></i> New Role</button>
						</div>
						
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadRoles()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadRoles()" class="btn btn-success btn-lg">
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
										<th ng-click="sortBy('title');loadRoles();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Role</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
										</th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td> 
										<td>
											<div class="list-icons float-right">
												<div class="list-icons-item dropdown">
							                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item"  data-target="#edit-role" <?php print $this->MODAL_OPTIONS;?> ng-click="selectRow(row)">
															<i class="icon-compose"></i> Update
														</a>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" ng-click="delRole(row)"><i class="icon-cross"></i> Remove </a>
													</div>
												</div>
											</div>
										</td>
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadRoles()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadRoles()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>

		    	<div class="tab-pane fade <?php print $tab=='concessions' ? 'active show': '';?>" id="concessions">
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Student Fee Concession Types 
							<span class="d-block font-size-base text-muted">Registered student fee concession types.
							</span>
							</h5>
							<button class="btn btn-success btn-sm font-weight-bold"  data-target="#add-concession" <?php print $this->MODAL_OPTIONS;?>>
								<i class="icon-plus-circle2"></i> New Concession Type</button>
						</div>
						
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadConcessions()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadConcessions()" class="btn btn-success btn-lg">
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
										<th ng-click="sortBy('title');loadRoles();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Type</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
										</th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td> 
										<td>
											<div class="list-icons float-right">
												<div class="list-icons-item dropdown">
							                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item"  data-target="#edit-concession" <?php print $this->MODAL_OPTIONS;?> ng-click="selectRow(row)">
															<i class="icon-compose"></i> Update
														</a>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" ng-click="delConcession(row)"><i class="icon-cross"></i> Remove </a>
													</div>
												</div>
											</div>
										</td>
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadConcessions()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadConcessions()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>

		    	<div class="tab-pane fade <?php print $tab=='stdgroups' ? 'active show': '';?>" id="stdgroups">
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Student Groups 
							<span class="d-block font-size-base text-muted">Registered student groups here. E.g Pre Medical, Pre Engg.
							</span>
							</h5>
							<button class="btn btn-success btn-sm font-weight-bold"  data-target="#add-stdgroup" <?php print $this->MODAL_OPTIONS;?>>
								<i class="icon-plus-circle2"></i> New Group</button>
						</div>
						
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadStdGroup()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadStdGroup()" class="btn btn-success btn-lg">
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
										<th ng-click="sortBy('title');loadStdGroup();" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Group</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
										</th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td> 
										<td>
											<div class="list-icons float-right">
												<div class="list-icons-item dropdown">
							                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item"  data-target="#edit-stdgroup" <?php print $this->MODAL_OPTIONS;?> ng-click="selectRow(row)">
															<i class="icon-compose"></i> Update
														</a>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" ng-click="delStdGroup(row)"><i class="icon-cross"></i> Remove </a>
													</div>
												</div>
											</div>
										</td>
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadStdGroup()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadStdGroup()">
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
								
							<?php echo form_open_multipart($this->CONT_ROOT.'upload_picture');?>
							<input id="clientImageFile" type="file" name="file" style="display: none;" onchange="showname()">
							<input type="button" value="Browse" class="btn btn-light btn-sm" onclick="document.getElementById('clientImageFile').click();" />
							<button class="btn btn-success btn-sm"><i class="icon-upload position-right"></i><strong> Upload </strong></button>   
							<?php echo form_close(); ?>
							<span class="text-muted" id="selected-file"></span>
							<br>
				    		<h6 class="font-weight-semibold mb-0"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></h6>
				    		<span class="d-block text-muted">
				    			<span class="m-2">Contact:</span><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_CONTACT_NUMBER]);?>
				    		</span>

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


<!-- ********************************************************************** -->
<!-- ///////////////////////////////MODALS///////////////////////////////// -->
<!-- ********************************************************************** -->



<!-- add modal -->
<div id="add-award" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Register New Award</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Register as many awards as you need. Please provide required information to proceed next...</p>


				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Award Information</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>
						
					<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-8">
									<label class="text-muted">Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Award name" ng-model="entry.title">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>

							</div>
							<div class="row">
								<div class="col-sm-12">
									<label class="text-muted">Description <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<textarea class="form-control form-control-lg" placeholder="Description" ng-model="entry.template" rows="6"></textarea>
										
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>


			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="saveAward()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /add modal -->

<!-- add modal -->
<div id="add-discipline" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Register New Notice</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Register as many notices as you need. Please provide required information to proceed next...</p>


				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Notice Information</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>
						
					<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-8">
									<label class="text-muted">Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Notice name" ng-model="entry.title">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>

							</div>
							<div class="row">
								<div class="col-sm-12">
									<label class="text-muted">Description <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<textarea class="form-control form-control-lg" placeholder="Description" ng-model="entry.template" rows="6"></textarea>
										
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>


			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="saveDiscipline()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /add modal -->

<!-- add modal -->
<div id="add-role" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Register New Staff Role</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Register as many staff roles as you need. Please provide required information to proceed next...</p>


				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Role Information</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>
						
					<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-8">
									<label class="text-muted">Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Role name e.g Teacher" ng-model="entry.title">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
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
				<button ng-click="saveRole()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /add modal -->

<!-- add modal -->
<div id="add-session" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Register New Session</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Register as many sessions as you need. Please provide required information to proceed next...</p>


				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Session Information</h6>
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
									<label class="text-muted">Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Session name e.g Spring" ng-model="entry.title">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Academic Year <span class="text-danger"> * </span></label>
									<select class="form-control select" ng-model="entry.year" data-fouc>
										<option value="">Select Academic Year</option>
										<option value="<?php print date('Y');?>"><?php print date('Y');?></option>
										<option value="<?php print date('Y')+1;?>"><?php print date('Y')+1;?></option>
									</select>
								</div>

							</div>
						</div>
					</div>
				</div>


			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="saveSession()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /add modal -->

<!-- add modal -->
<div id="add-concession" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Register New Fee Concession Type</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you register as many concession types as you need. Please provide required information to proceed next...</p>


				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Type Information</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>
						
					<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-8">
									<label class="text-muted">Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Type name e.g Sibling concession" ng-model="entry.title">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
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
				<button ng-click="saveConcession()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /add modal -->


<!-- add modal -->
<div id="add-stdgroup" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Register New Student Group</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you register as many student groups as you need. Please provide required information to proceed next...</p>


				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Group Information</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>
						
					<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-8">
									<label class="text-muted">Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Group name e.g Pre Medical" ng-model="entry.title">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
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
				<button ng-click="saveStdGroup()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /add modal -->










<!-- edit modal -->
<div id="edit-award" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update Award({{selectedRow.title}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Update awards here. Please provide required information to proceed next...</p>				
				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Award Information</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>

						<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-8">
									<label class="text-muted">Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="name" ng-model="selectedRow.title">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<label class="text-muted">Description <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<textarea class="form-control form-control-lg" placeholder="Description" ng-model="selectedRow.template" rows="6"></textarea>
										
									</div>
								</div>

							</div>
						</div>

					</div>
				</div>



			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="updateAward()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /edit modal -->

<!-- edit modal -->
<div id="edit-discipline" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update Notice({{selectedRow.title}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Update notices here. Please provide required information to proceed next...</p>				
				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Notice Information</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>

						<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-8">
									<label class="text-muted">Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="name" ng-model="selectedRow.title">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<label class="text-muted">Description <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<textarea class="form-control form-control-lg" placeholder="Description" ng-model="selectedRow.template" rows="6"></textarea>
										
									</div>
								</div>

							</div>
						</div>

					</div>
				</div>



			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="updateDiscipline()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /edit modal -->

<!-- edit modal -->
<div id="edit-role" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update Role({{selectedRow.title}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Update roles here. Please provide required information to proceed next...</p>				
				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Role Information</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>

						<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-8">
									<label class="text-muted">Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="name" ng-model="selectedRow.title">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
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
				<button ng-click="updateRole()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /edit modal -->

<!-- edit modal -->
<div id="edit-session" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update Session({{selectedRow.title}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Update sessions here. Please provide required information to proceed next...</p>				
				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Session Information</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>

						<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-8">
									<label class="text-muted">Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="name" ng-model="selectedRow.title">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
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
				<button ng-click="updateSession()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /edit modal -->

<!-- edit modal -->
<div id="edit-module" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update Module({{selectedRow.name}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">As an admin you can update charges of this module for this organizaiton...</p>				
				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Charges Information</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>

						<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-8">
									<label class="text-muted">Charges <span class="text-danger"> * (in $ currency) </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="monthly charges" ng-model="selectedRow.monthly_charges">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-calculator"></i>
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
				<button ng-click="updateModule()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /edit modal -->


<!-- edit modal -->
<div id="edit-concession" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update Concession Type({{selectedRow.title}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you update concession types here. Please provide required information to proceed next...</p>				
				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Type Information</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>

						<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-8">
									<label class="text-muted">Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="name" ng-model="selectedRow.title">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
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
				<button ng-click="updateConcession()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /edit modal -->


<!-- edit modal -->
<div id="edit-stdgroup" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update Student Group({{selectedRow.title}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you update student group here. Please provide required information to proceed next...</p>				
				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Group Information</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>

						<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-8">
									<label class="text-muted">Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="name" ng-model="selectedRow.title">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
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
				<button ng-click="updateStdGroup()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /edit modal -->



</div>
<!-- /main content -->