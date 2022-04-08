<?php

$teacher=$this->stf_role_m->get_by(array('title'=>'teacher'));
$faculty=$this->staff_m->get_rows(array('role_id'=>$teacher->mid));
$classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'title ASC') );

?>
<!-- Main content -->
<div class="content-wrapper" ng-controller="mozzCtrl"  ng-cloak>
	<!-- Page header -->
	<div class="page-header page-header-light border-bottom-0">

		<!-- Top breadcrumb line -->
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Settings</a>
					<a class="breadcrumb-item">Campus</a>
					<span class="breadcrumb-item active">General Settings</span>
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
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Settings</span> - General</h4>
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
						<a href="#profile" class="navbar-nav-link active" data-toggle="tab" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-profile mr-2" style="color:<?php print $clr;?>;"></i>
							General Settings
						</a>
					</li>
					<li class="nav-item">
						<a href="#bank" class="navbar-nav-link" data-toggle="tab" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-briefcase mr-2" style="color:<?php print $clr;?>;"></i>
							Bank Account Settings
						</a>
					</li>
					<li class="nav-item">
						<a href="#sections" class="navbar-nav-link" data-toggle="tab" ng-click="loadSections()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-graduation2 mr-2" style="color:<?php print $clr;?>;"></i>
							Class Sections
						</a>
					</li>
					<li class="nav-item">
						<a href="#periods" class="navbar-nav-link" data-toggle="tab" ng-click="loadPeriods()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-graduation2 mr-2" style="color:<?php print $clr;?>;"></i>
							Study Periods
						</a>
					</li>
					<?php if($this->SETTINGS[$this->system_setting_m->_ORG_TYPE]==$this->system_setting_m->TYPE_COLLEGE){ ?>
					<li class="nav-item">
						<a href="#feepackages" class="navbar-nav-link" data-toggle="tab" ng-click="loadFeePackages()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-list mr-2" style="color:<?php print $clr;?>;"></i>
							Fee Packages
						</a>
					</li>
					<?php } ?>
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

		<!-- Inner container -->
		<div class="d-flex align-items-start flex-column flex-md-row">

			<!-- Left content -->
			<div class="tab-content w-100 overflow-auto order-2 order-md-1">


			    <div class="tab-pane fade <?php print empty($tab) || $tab=='profile' ? 'active show': '';?>" id="profile">
			    	
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">General Settings
							<span class="d-block font-size-base text-muted">General settings allow you to set preferences for whole campus. 
							</span>
							</h5>
						</div>

						<div class="card-body">
							<!-- <hr> -->
							<div class="row">
	        					<div class="col-md-12">
									<div class="form-group pt-2">
										<div class="form-check">
											<label class="form-check-label">
												<input type="checkbox" class="form-check-input" ng-model="settings.prm_portal_staff_edit">
												Allow staff to edit the info in the portal account
											</label>
										</div>
										<div class="form-check">
											<label class="form-check-label">
												<input type="checkbox" class="form-check-input"  ng-model="entry.get_late_fee_fine">
												Receive fine from students if they pay fee after the due date of voucher
											</label>
										</div>
										<div class="form-check">
											<label class="form-check-label">
												<input type="checkbox" class="form-check-input" ng-model="settings.narration">
												Enable narration (if supported by system)
											</label>
										</div>

									</div>

	        					</div>
	    					</div>

							<hr>
							<div class="form-group">
								<div class="row">
									<div class="col-md-6" ng-show="entry.get_late_fee_fine">
										<label class="text-muted">Select Late Fee Fine Type</label>
										<select class="form-control select" ng-model="settings.late_fee_fine_type" data-fouc>
										<option value="0">Get Fixed Fine</option>
										<option value="1">Charge Fine Per Day</option>
										</select>
									</div>
									<div class="col-md-6" ng-show="entry.get_late_fee_fine">
										<label class="text-muted">Enter Fine Amount<span class="text-danger"> *</span></label>
										<input type="text" class="form-control" placeholder="Fine amount" ng-model="settings.late_fee_fine">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-4">
										<label class="text-muted">Default Printing Font Scale</label>
										<select class="form-control select" ng-model="settings.font_scale" data-fouc>
										<option value="0">Print Scale 0</option>
										<option value="1">Print Scale 1</option>
										<option value="2">Print Scale 2</option>
										<option value="3">Print Scale 3</option>
										<option value="4">Print Scale 4</option>
										<option value="5">Print Scale 5</option>
										</select>
									</div>
									<div class="col-md-4">
										<label class="text-muted">Monthly Passing %<span class="text-danger"> *</span></label>
										<input type="text" class="form-control" placeholder="Passing %" ng-model="settings.month_opass_percent">
									</div>
									<div class="col-md-4">
										<label class="text-muted">Final Passing %<span class="text-danger"> *</span></label>
										<input type="text" class="form-control" placeholder="Passing %" ng-model="settings.final_opass_percent">
									</div>
								</div>
							</div>
							<?php if($this->SETTINGS[$this->system_setting_m->_ORG_TYPE]==$this->system_setting_m->TYPE_COLLEGE){ ?>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-6">
										<label class="text-muted">Fee Collection Type</label>
										<select class="form-control select" ng-model="settings.std_fee_type" data-fouc>
										<option value="fixed">Get Pre-Fixed fee for session</option>
										<option value="monthly">Receive Fee Monthly</option>
										</select>
									</div>
								</div>
							</div>
							<?php } ?>
							<div class="form-group">
								<div class="row">
									<div class="col-md-12">
										<button ng-click="saveGeneralSettings()" class="btn btn-success btn-lg float-right">
											<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-checkmark-circle2':!appConfig.btnClickedSave}" class="ml-2"></i>
										</button>
									</div>
								</div>
							</div>

						</div>
					</div>
					<!-- /save info -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='bank' ? 'active show': '';?>" id="bank">
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Bank Account Settings
							<span class="d-block font-size-base text-muted">If you want to collect fee from students directly into back account you can update your bank account settings so that the account details can be printed on fee vouchers. 
							</span>
							</h5>
						</div>

						<div class="card-body">
							<!-- <hr> -->
							<div class="row">
	        					<div class="col-md-12">
									<div class="form-group pt-2">
									</div>

	        					</div>
	    					</div>

							<div class="form-group">
								<hr>
								<div class="row">
									<div class="col-md-10">
										<label class="text-muted">Bank Name<span class="text-danger"> *</span></label>
										<input type="text" class="form-control" placeholder="Name of Bank" ng-model="settings.bank_name">
									</div>
									<div class="col-md-10">
										<label class="text-muted">Account Title (May be your personal or business name)<span class="text-danger"> *</span></label>
										<input type="text" class="form-control" placeholder="Account Title" ng-model="settings.bank_title">
									</div>
									<div class="col-md-10">
										<label class="text-muted">Account Number<span class="text-danger"> *</span></label>
										<input type="text" class="form-control" placeholder="Bank Account Number" ng-model="settings.bank_account">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-12">
										<button ng-click="saveGeneralSettings()" class="btn btn-success btn-lg float-right">
											<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-checkmark-circle2':!appConfig.btnClickedSave}" class="ml-2"></i>
										</button>
									</div>
								</div>
							</div>

						</div>
					</div>
					<!-- /save info -->
		    	</div>





			    <div class="tab-pane fade <?php print $tab=='sections' ? 'active show': '';?>" id="sections">
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Class Sections 
							<span class="d-block font-size-base text-muted">Class sections allows manage classes into smaller subsets. You can create sections for your campus. Later, Teachers or admin can assign students sections and roll numbers.
							</span>
							</h5>
						</div>

						<div class="card-body">
							<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									<label class="text-muted">Name<span class="text-danger"> *</span></label>
									<input type="text" class="form-control" placeholder="Section Name" ng-model="section.name">
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Select Class<span class="text-danger"> * </span></label>
									<select class="form-control select" name="class_id" ng-model="section.class_id" data-fouc>
									<option value="">Select Class</option>
									<?php foreach ($classes as $row){?>            
									    <option value="<?php print $row['mid'];?>" /><?php print strtoupper($row['title']);?>
								    <?php }?>
									</select>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">In-charge</label>
									<select class="form-control select" ng-model="section.incharge_id" data-fouc>
									<option value="">Select Incharge</option>
									<?php foreach ($faculty as $row){?>            
									    <option value="<?php print $row['mid'];?>" /><?php print ucwords(strtolower($row['name'])).'('.$row['staff_id'].')';?>
								    <?php }?>
									</select>
								</div>
								<div class="col-md-2">
									<button ng-click="saveSection()" class="btn btn-success btn-md ml-2 mt-4">
										<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-checkmark-circle2':!appConfig.btnClickedSave}" class="ml-2"></i>
									</button>
								</div>
							</div>
							</div>

						</div>
					</div>
					<!-- /save info -->
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Registered Sections</h4>
							<span class="text-muted">Find below the registered sections for this campus.</span>
						</div>
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadSections()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadSections()" class="btn btn-success btn-lg">
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
										<th ng-click="sortBy('name');" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Section Name</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='name'"></i> 
										</th>
										<th ng-click="sortBy('class_id');" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Class</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='class_id'"></i> 
										</th>
										<th ng-click="sortBy('incharge_id');" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Incharge</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='incharge_id'"></i> 
										</th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.name}}</td>
										<td>{{row.class}}</td>
										<td>{{row.incharge}}</td>
										<td>
											<div class="list-icons float-right">
												<div class="btn-group list-icons-item dropdown">
							                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
											
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item" ng-click="selectSection(row)">
															<i class="icon-compose"></i> Update
														</a>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" ng-click="delSection(row)"><i class="icon-cross"></i> Remove </a>
													</div>
												</li>
											</div>
										</td>
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadSections()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadSections()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>
		    	  <div class="tab-pane fade <?php print $tab=='periods' ? 'active show': '';?>" id="periods">
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Study Periods 
							<span class="d-block font-size-base text-muted">Study periods allows you manage routine of periods for your institute. Later you can create timetable for students & staff in classes menu.
							</span>
							</h5>
						</div>

						<div class="card-body">
							<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									<label class="text-muted">Name<span class="text-danger"> *</span></label>
									<input type="text" class="form-control" placeholder="Name E.g. 1st" ng-model="period.name">
								</div>
								<div class="col-md-3">
									<label class="text-muted">Start Time<span class="text-danger"> *</span></label>
									<input type="text" class="form-control" placeholder="08:30" ng-model="period.from_time">
								</div>
								<div class="col-md-3">
									<label class="text-muted">End Time<span class="text-danger"> *</span></label>
									<input type="text" class="form-control" placeholder="09:15" ng-model="period.to_time">
								</div>
								<div class="col-md-3">
									<label class="text-muted">Duration (in minutes)<span class="text-danger"> *</span></label>
									<input type="text" class="form-control" placeholder="45" ng-model="period.total_time">
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Type<span class="text-danger"> * </span></label>
									<select class="form-control select" ng-model="period.type" data-fouc>
									<option value="">Select Type</option>
								    <option value="<?php print $this->period_m->TYPE_PRAYER;?>" />Prayer Time
								    <option value="<?php print $this->period_m->TYPE_PERIOD;?>" />Study Period
								    <option value="<?php print $this->period_m->TYPE_BREAK;?>" />Lunch Break
									</select>
								</div>
								<div class="col-md-3" ng-show="!period.add">
									<label class="text-muted">Sort Order<span class="text-danger"> *</span></label>
									<input type="text" class="form-control" placeholder="45" ng-model="period.sort_order">
								</div>
								<div class="col-md-2">
									<button ng-click="savePeriod()" class="btn btn-success btn-md ml-2 mt-4">
										<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-checkmark-circle2':!appConfig.btnClickedSave}" class="ml-2"></i>
									</button>
								</div>
							</div>
							</div>

						</div>
					</div>
					<!-- /save info -->
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Registered Study Periods Routine</h4>
							<span class="text-muted">Find below the registered routine for this campus.</span>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th class="mouse-pointer font-weight-bold" ><span>Name</span></th>
										<th class="mouse-pointer font-weight-bold" ><span>Start Time</span></th>
										<th class="mouse-pointer font-weight-bold" ><span>End Time</span></th>
										<th class="mouse-pointer font-weight-bold" ><span>Duration</span></th>
										<th class="mouse-pointer font-weight-bold" ><span>Type</span></th>
										<th class="mouse-pointer font-weight-bold" ><span>Sort Order</span></th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.name}}</td>
										<td>{{row.from_time}}</td>
										<td>{{row.to_time}}</td>
										<td>{{row.total_time}}</td>
										<td>{{row.type}}</td>
										<td>{{row.sort_order}}</td>
										<td>
											<div class="list-icons float-right">
												<div class="btn-group list-icons-item dropdown">
							                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
											
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item" ng-click="selectPeriod(row);">
															<i class="icon-compose"></i> Update
														</a>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" ng-click="delPeriod(row)"><i class="icon-cross"></i> Remove </a>
													</div>
												</li>
											</div>
										</td>
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadPeriods()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadPeriods()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>
		    	
		    	<div class="tab-pane fade <?php print $tab=='feepackages' ? 'active show': '';?>" id="feepackages">
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Class Fee Packages 
							<span class="d-block font-size-base text-muted">Fee Packages help you to offer multiple admission packages to students based on their perform in last exam. When you will create session voucher for the students these packages will apply to all students automatically.
							</span>
							</h5>
						</div>

						<div class="card-body">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-4">
										<label class="text-muted">Select Class<span class="text-danger"> * </span></label>
										<select class="form-control select" name="class_id" ng-model="feepackage.class_id" data-fouc>
										<option value="">Select Class</option>
										<?php foreach ($classes as $row){?>            
										    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
									    <?php }?>
										</select>
									</div>
									<div class="col-md-4">
										<label class="text-muted">Package Name<span class="text-danger"> *</span></label>
										<input type="text" class="form-control" placeholder="Package Name" ng-model="feepackage.name">
									</div>
									<div class="col-md-4">
										<label class="text-muted">Package Amount<span class="text-danger"> *</span></label>
										<input type="text" class="form-control" placeholder="Package Amount" ng-model="feepackage.amount">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-12">
										<label class="text-muted">Description</label>
										<input type="text" class="form-control" placeholder="Package Description" ng-model="feepackage.description">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-4">
										<label class="text-muted">Minimum Percentage<span class="text-danger"> *</span></label>
										<input type="text" class="form-control" placeholder="Minimum Obtained Percentage" ng-model="feepackage.obt_min_percent">
									</div>
									<div class="col-md-4">
										<label class="text-muted">Maximum Percentage<span class="text-danger"> *</span></label>
										<input type="text" class="form-control" placeholder="Maximum Obtained Percentage" ng-model="feepackage.obt_max_percent">
									</div>
									<div class="col-md-4">
										<button ng-click="saveFeePackage()" class="btn btn-success btn-md ml-2 mt-4">
											<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-checkmark-circle2':!appConfig.btnClickedSave}" class="ml-2"></i>
										</button>
									</div>
								</div>
							</div>

						</div>
					</div>
					<!-- /save info -->
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Registered Fee Packages</h4>
							<span class="text-muted">Find below the registered fee packages for this campus.</span>
						</div>
						<div class="card-body">
							<div class="input-group mb-3">
								<div class="form-group-feedback form-group-feedback-left">
									<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadFeePackages()">
									<div class="form-control-feedback form-control-feedback-lg">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="input-group-append">
									<button ng-click="loadFeePackages()" class="btn btn-success btn-lg">
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
										<th ng-click="sortBy('name');" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Package</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='name'"></i> 
										</th>
										<th ng-click="sortBy('class_id');" class="mouse-pointer font-weight-bold" >
											<span class="m-1">Class</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='class_id'"></i> 
										</th>
										<th class="font-weight-bold">Policy</th>
										<th class="font-weight-bold">Fee Amount</th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.name}}</td>
										<td>{{row.class}}</td>
										<td>{{row.obt_min_percent}}% - {{row.obt_max_percent}}%</td>
										<td>{{row.amount}}</td>
										<td>
											<div class="list-icons float-right">
												<div class="btn-group list-icons-item dropdown">
							                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
											
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item" ng-click="selectFeePackage(row)">
															<i class="icon-compose"></i> Update
														</a>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" ng-click="delFeePackage(row)"><i class="icon-cross"></i> Remove </a>
													</div>
												</li>
											</div>
										</td>
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadFeePackages()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadFeePackages()">
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
								<img class="img-fluid rounded-circle" src="<?php print $this->UPLOADS_ROOT.'images/logo/'.$this->CAMPUSSETTINGS[$this->campus_setting_m->_CAMPUS_LOGO];?>" width="170" height="170" alt="">
							</div>
								
							<?php echo form_open_multipart($this->CONT_ROOT.'upload_logo');?> 
							<input id="clientImageFile" type="file" name="file" style="display: none;" onchange="showname()">
							<input type="button" value="Browse" class="btn btn-light btn-sm" onclick="document.getElementById('clientImageFile').click();" />
							<button class="btn btn-success btn-sm"><i class="icon-upload position-right"></i><strong> Upload </strong></button>   
							<?php echo form_close(); ?>
							<span class="text-muted" id="selected-file"></span>

								
				    		<br>
				    		<h6 class="font-weight-semibold mb-0"><?php print ucwords(strtolower($this->CAMPUS->name));?></h6>
				    		<span class="d-block text-muted"><span class="m-2"></span><?php print ucwords(strtolower($this->CAMPUS->contact_number));?></span>
				    		<span class="d-block text-muted"><span class="m-2"></span><?php print ucwords(strtolower($this->SETTINGS[$this->system_setting_m->_ORG_NAME]));?></span>

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




</div>
<!-- /main content -->