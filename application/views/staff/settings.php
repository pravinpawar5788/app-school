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
					Settings navigation
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


			   
			    <div class="tab-pane fade <?php print empty($tab) || $tab=='index' ? 'active show': '';?>" id="index">

					<!-- Account settings -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Account settings</h5>
						</div>

						<div class="card-body">
								<div class="form-group">
									<div class="row">
										<div class="col-md-6">
											<label>Name</label>
											<input type="text" value="<?php print $this->LOGIN_USER->name ?>" readonly="readonly" class="form-control">
										</div>

										<div class="col-md-6">
											<label>LOGIN ID</label>
											<input type="text" value="<?php print $this->LOGIN_USER->staff_id ?>" readonly="readonly" class="form-control">
										</div>
									</div>
								</div>

								<div class="form-group">
									<div class="row">
										<div class="col-md-6">
											<label>Current password</label>
											<input type="password" placeholder="Enter new password" class="form-control" ng-model="entry.password">
										</div>

										<div class="col-md-6">
											<label>New Password</label>
											<input type="password" placeholder="Repeat new password" class="form-control" ng-model="entry.new_password">
										</div>
									</div>
								</div>


		                        <div class="text-right">
		                        	<button ng-click="changePassword()" class="btn btn-success btn-lg">
										<span class="font-weight-bold"> Change Password</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
									</button>
		                        </div>
						</div>
					</div>
					<!-- /account settings -->

		    	</div>
			    <div class="tab-pane fade <?php print $tab=='discipline' ? 'active show': '';?>" id="discipline">
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Issued Notices</h4>
							<span class="text-muted">Following are the notices issued to this student. </span>
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
										<th ng-click="sortBy('remarks');loadDiscipline();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Remarks</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='remarks'"></i> 
										</th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.title}}</td>
					  					<td>{{row.remarks}}</td>  
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
							<?php }?>
							<hr>
				    		<h6 class="font-weight-semibold mb-0"><?php print ucwords(strtolower($this->LOGIN_USER->name));?></h6>
				    		<span class="d-block text-muted"><span class="m-2">Staff ID:</span><?php print $this->LOGIN_USER->staff_id;?></span>
				    		<span class="d-block text-muted">
				    			<span class="m-2">Mobile:</span><?php print $this->LOGIN_USER->mobile;?></span>

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