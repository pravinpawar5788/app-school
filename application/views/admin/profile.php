<?php

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
					<a class="breadcrumb-item">Settings</a>
					<span class="breadcrumb-item active">Profile</span>
				</div>

				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="breadcrumb justify-content-center">
					<a href="#" class="breadcrumb-elements-item sidebar-control sidebar-component-toggle d-none d-md-block">
						<i class="icon-drag-right mr-2"></i>Toggle Profile
					</a>


				</div>
			</div>
		</div>
		<!-- /breadcrumb line -->

	
		<!-- Page header content -->
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Settings</span> - Profile</h4>
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
					Profile navigation
				</button>
			</div>

			<div class="navbar-collapse collapse" id="navbar-second">
				<ul class="nav navbar-nav">
					<li class="nav-item">
						<a href="#profile" class="navbar-nav-link <?php print $tab=='index' || empty($tab) ? 'active': '';?>" data-toggle="tab">
							<i class="icon-profile mr-2"></i>
							Profile
						</a>
					</li>
					<li class="nav-item">
						<a href="#password" class="navbar-nav-link <?php print $tab=='password' ? 'active': '';?>" data-toggle="tab">
							<i class="icon-lock mr-2"></i>
							Password
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
							<h5 class="card-title">Profile Information</h5>
							<p class="text-muted">Update profile information for your account.</p>
						</div>

						<?php echo form_open_multipart($this->CONT_ROOT.'save/profile');?> 
						<div class="card-body">
							<div class="form-group">
							<div class="row">
								<div class="col-md-4">
									<label class="text-muted">Administrator ID</label>
									<input type="text" value="<?php print $this->LOGIN_USER->user_id;?>" class="form-control" name="user_id">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Name</label>
									<input type="text" value="<?php print $this->LOGIN_USER->name;?>" class="form-control" name="name">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Mobile Number</label>
									<input type="text" value="<?php print $this->LOGIN_USER->mobile;?>" class="form-control" name="mobile">
								</div>
							</div>
							</div>

							<div class="form-group">
							<div class="row">
								<div class="col-md-12">									
									<button type="submit" class="btn btn-success btn-lg ml-2 mt-3 float-right">
										<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-checkmark-circle2':!appConfig.btnClickedSave}" class="ml-2"></i>
									</button>
								</div>
							</div>
							</div>


						</div>
						<?php echo form_close(); ?>
					</div>
					<!-- /profile info -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='password' ? 'active show': '';?>" id="password">
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Update Account Password
							<span class="d-block font-size-base text-muted">Please provide existing password and new password to proceed. 
							</span>
							</h5>
						</div>

						<?php echo form_open_multipart($this->CONT_ROOT.'save/password');?> 
						<div class="card-body">
							<div class="form-group">
							<div class="row">
								<div class="col-md-4">
									<label class="text-muted">Existing Password<span class="text-danger"> *</span></label>
									<input type="text" class="form-control" placeholder="Current Password" name="password">
								</div>
								<div class="col-md-4">
									<label class="text-muted">New Password <span class="text-danger"> * </span></label>
									<input type="text" class="form-control" placeholder="New Account Password" name="npassword">
								</div>
								<div class="col-md-4">
									<button type="submit" class="btn btn-success btn-lg ml-2 mt-3">
										<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-checkmark-circle2':!appConfig.btnClickedSave}" class="ml-2"></i>
									</button>
								</div>
							</div>
							</div>

						</div>
						<?php echo form_close(); ?>
					</div>
					<!-- /save info -->

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
								<img class="img-fluid rounded-circle" src="<?php print $this->UPLOADS_ROOT.'images/user/'.$this->LOGIN_USER->image;?>" width="170" height="170" alt="">
							</div>
								
							<?php echo form_open_multipart($this->CONT_ROOT.'upload_picture');?> 
							<input id="clientImageFile" type="file" name="file" style="display: none;" onchange="showname()">
							<input type="button" value="Browse" class="btn btn-light btn-sm" onclick="document.getElementById('clientImageFile').click();" />
							<button class="btn btn-success btn-sm"><i class="icon-upload position-right"></i><strong> Upload </strong></button>   
							<?php echo form_close(); ?>
							<span class="text-muted" id="selected-file"></span>

				    		<h6 class="font-weight-semibold mb-0"><?php print ucwords(strtolower($this->LOGIN_USER->name));?></h6>
				    		<span class="d-block text-muted">
				    			<span class="m-2">Email:</span><?php print ucwords(strtolower($this->LOGIN_USER->email));?></span>
				    		<span class="d-block text-muted">
				    			<span class="m-2">Registered Since:</span><?php print $this->LOGIN_USER->date;?>
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




</div>
<!-- /main content -->