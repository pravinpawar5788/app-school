
<!-- Main content -->
<div class="content-wrapper" ng-controller="mozzCtrl"  ng-cloak>
	<!-- Page header -->
	<div class="page-header page-header-light border-bottom-0">

		<!-- Top breadcrumb line -->
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
					<a class="breadcrumb-item">Settings</a>
					<span class="breadcrumb-item active">System Maintenance</span>
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
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Settings</span> - System Maintenance</h4>
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
					Page Navigation
				</button>
			</div>

			<div class="navbar-collapse collapse" id="navbar-second">
				<ul class="nav navbar-nav">
					<li class="nav-item">
						<a href="#profile" class="navbar-nav-link <?php print $tab=='index' || empty($tab) ? 'active': '';?>" data-toggle="tab">
							<i class="icon-database mr-2"></i>System Settings
						</a>
					</li>
					<li class="nav-item">
						<a href="#backup" class="navbar-nav-link <?php print $tab=='backup' ? 'active': '';?>" data-toggle="tab">
							<i class="icon-lock mr-2"></i>
							System Backup
						</a>
					</li>
					<li class="nav-item">
						<a href="#update" class="navbar-nav-link <?php print $tab=='update' ? 'active': '';?>" data-toggle="tab">
							<i class="icon-versions mr-2"></i>
							Updates
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
							<h5 class="card-title">System Settings
							<span class="d-block font-size-base text-muted">You can update here the system settings. These settings will effect whole platform so be cautious while updating these settings.
							</span>
							</h5>
						</div>						
						
						<?php echo form_open_multipart($this->CONT_ROOT.'save/settings');?> 
						<div class="card-body">
							<div class="form-group">
								<div class="row">
									<div class="col-md-4 col-sm-4">
										<label class="text-muted">Institutes Name</label>
										<input type="text" class="form-control" placeholder="Institutes Name" name="<?php print $this->system_setting_m->_ORG_NAME;?>" value="<?php print $this->SETTINGS[$this->system_setting_m->_ORG_NAME];?>">
									</div>
									<div class="col-md-4 col-sm-4">
										<label class="text-muted">Institutes Short Name</label>
										<input type="text" class="form-control" placeholder="Institutes Name" name="<?php print $this->system_setting_m->_ORG_SHORT_NAME;?>" value="<?php print $this->SETTINGS[$this->system_setting_m->_ORG_SHORT_NAME];?>">
									</div>
									<div class="col-md-4 col-sm-4">
										<label class="text-muted">Max File Upload Limit (in MB)</label>
										<input type="text" class="form-control" placeholder="File Upload Limit" name="<?php print $this->system_setting_m->_MAX_UPLOAD_SIZE;?>" value="<?php print $this->SETTINGS[$this->system_setting_m->_MAX_UPLOAD_SIZE];?>">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-4 col-sm-4">
										<label class="text-muted">Institutes Contact</label>
										<input type="text" class="form-control" placeholder="Institutes Contact" name="<?php print $this->system_setting_m->_ORG_CONTACT_NUMBER;?>" value="<?php print $this->SETTINGS[$this->system_setting_m->_ORG_CONTACT_NUMBER];?>">
									</div>
									<div class="col-md-8 col-sm-8">
										<label class="text-muted">Institutes Address</label>
										<input type="text" class="form-control" placeholder="Institutes Address" name="<?php print $this->system_setting_m->_ORG_ADDRESS;?>" value="<?php print $this->SETTINGS[$this->system_setting_m->_ORG_ADDRESS];?>">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">			
									<div class="col-sm-4">
										<label class="text-muted">Select Institue Category</label>
										<select class="form-control select" name="<?php print $this->system_setting_m->_ORG_TYPE;?>" data-fouc>
											<option value="">Select Category</option>
											<option value="school" <?php print $this->SETTINGS[$this->system_setting_m->_ORG_TYPE]=='school' ? 'selected' : '';?>>School</option>
											<option value="college" <?php print $this->SETTINGS[$this->system_setting_m->_ORG_TYPE]=='college' ? 'selected' : '';?>>College</option>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-12">
										<button ng-click="saveSettings()" class="btn btn-success float-right">
											<span class="font-weight-bold"><i class="icon-checkmark-circle2" class="ml-2 mr-2"></i> Save Settings</span> 
										</button>
									</div>
								</div>
							</div>

						</div>
						<?php echo form_close(); ?>
					</div>
					<!-- /settings -->
		    	</div>
		    	<div class="tab-pane fade <?php print $tab=='backup' ? 'active show': '';?>" id="backup">
					<!-- Profile info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">System Backup</h5>
						</div>

						<div class="card-body">
							<p class="text-muted">You can download here database backup. If any thing ever goes wrong then you can restore the backup. It is recomended that you download backup every week.<br>
								Complete system backup may take some time to process all data.<br>
								<code>Note:-</code> Complete system backup will download all the system files from the server. These files contains your database credentials and other important data. so keep these backup files in safe &amp; secure custody.</p>
							<br><br>
							<center>
								<a href="<?php print $this->CONT_ROOT.'savebackup';?>" class="btn btn-success btn-lg font-weight-bold"><i class="icon-checkmark-circle2 mr-1"></i> Download Database Backup</a>
								<a href="<?php print $this->CONT_ROOT.'savesystembackup/1';?>" class="btn btn-danger btn-lg font-weight-bold"><i class="icon-checkmark-circle2 mr-1"></i> Download Complete System Backup</a>
							</center>
						</div>
					</div>
					<!-- /profile info -->
		    	</div>
		    	<div class="tab-pane fade <?php print $tab=='update' ? 'active show': '';?>" id="update">
					<!-- Profile info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Update Module</h5>
						</div>

						<div class="card-body">
							<p class="text-muted">Update module help you in checking &amp; installation of new updates. Click the button to check for new updates.<br>
							<code>Note:-</code> Please download a copy of <strong>complete system backup</strong> before downloading and installing new updates.
							</p>
							<br><br>
							<center>
								<button ng-click="checkUpdates()" class="btn btn-success btn-lg" ng-show="!updatesAvaileable">
									<span class="font-weight-bold"> Check New Updates</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
								</button>
								<button ng-click="installUpdates()" class="btn btn-danger btn-lg" ng-show="updatesAvaileable">
									<span class="font-weight-bold"> Download &amp; Install Updates</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
								</button>
							</center>
						</div>
					</div>
					<!-- /profile info -->
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



</div>
<!-- /main content -->