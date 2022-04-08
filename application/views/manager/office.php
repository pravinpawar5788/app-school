
<!-- Main content -->
<div class="content-wrapper" ng-controller="mozzCtrl"  ng-cloak>

<!-- Page header -->
<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Office</span> - Assistant Managers</h4>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a><br>			
		</div>

		<div class="header-elements d-none">
		</div>
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php print $this->LIB_CONT_ROOT;?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<span class="breadcrumb-item active">Assistant Managers</span>
			</div>
		</div>
	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb justify-content-center">				
				<a href="<?php print $this->LIB_CONT_ROOT;?>office" class="breadcrumb-elements-item" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
					<i class="icon-users2 mr-2" style="color:<?php print $clr;?>;"></i> Assistant Managers</a>
				<a href="" class="breadcrumb-elements-item" data-target="#add" <?php print $this->MODAL_OPTIONS;?> style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
					<i class="icon-user-plus mr-2" style="color:<?php print $clr;?>;"></i> Create New Account</a>
			</div>

			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>

		<div class="header-elements d-none">
			<div class="breadcrumb justify-content-center">
				<!-- on each page -->
				<a href="<?php print $this->APP_ROOT.'docs/office';?>" target="_blank" class="breadcrumb-elements-item">
					<i class="icon-lifebuoy mr-2"></i>Docs
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
			<h5 class="mb-3">Search Members</h5>
			<div class="row">
				<div class="col-sm-8">
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
				<div class="col-sm-4">
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
			<h4 class="card-title">Staff List </h4>
			<span class="text-muted">All registered staff member can login into system on behalf of you and perform permitted activities.</span>
		</div>
		<div class="table-responsive">
		<table class="table tasks-responsive table-lg">
			<thead>
				<tr>
					<th class="font-weight-bold">#</th>
					<th ng-click="sortBy('name');" class="mouse-pointer font-weight-bold" >
						<span class="m-1">Name</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='name'"></i> 
					</th>
					<th ng-click="sortBy('user_id');" class="mouse-pointer font-weight-bold" >
						<span class="m-1">Staff ID</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='user_id'"></i> 
					</th>
					<th ng-click="sortBy('mobile');" class="mouse-pointer font-weight-bold" >
						<span class="m-1">Mobile</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='mobile'"></i> 
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
	                	<div><a class="font-weight-semibold">{{row.name}}</a></div>
					</td> 
					<td>{{row.user_id}}</td>
					<td>{{row.mobile}}</td> 
	                <td>
                	<div class="btn-group">
						<a ng-show="row.status==='<?php print $this->user_m->STATUS_ACTIVE;?>'" href="#" class="badge bg-success dropdown-toggle" data-toggle="dropdown">
							<?php print strtoupper($this->user_m->STATUS_ACTIVE);?></a>
						<a ng-show="row.status==='<?php print $this->user_m->STATUS_UNACTIVE;?>'" href="#" class="badge bg-danger dropdown-toggle" data-toggle="dropdown">
							<?php print strtoupper($this->user_m->STATUS_UNACTIVE);?></a>
						
						<div class="dropdown-menu dropdown-menu-right">
							<a href="#" class="dropdown-item" ng-show="row.status!=='<?php print $this->user_m->STATUS_ACTIVE;?>'" ng-click="changeStatus(row, '<?php print $this->user_m->STATUS_ACTIVE;?>');"><span class="badge badge-mark mr-2 bg-success border-success"></span> Activate</a>
							<a href="#" class="dropdown-item" ng-show="row.status!=='<?php print $this->user_m->STATUS_UNACTIVE;?>'" ng-click="changeStatus(row, '<?php print $this->user_m->STATUS_UNACTIVE;?>');"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Disable</a>
							
						</div>
					</div>


                	</td>
					<td>
						<div class="list-icons float-right">
							<div class="btn-group list-icons-item dropdown">
		                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
								<div class="dropdown-menu dropdown-menu-right">
									<a <?php print $this->MODAL_OPTIONS;?> data-target="#edit" class="dropdown-item" ng-click="selectRow(row)">
										<i class="icon-compose"></i> Update Account
									</a>
									<div class="dropdown-divider"></div>
									<a <?php print $this->MODAL_OPTIONS;?> data-target="#password" class="dropdown-item" ng-click="selectRow(row)">
										<i class="icon-user-lock"></i> Change Password
									</a>
									<a class="dropdown-item" ng-click="delRow(row)"><i class="icon-user-cancel"></i> Delete Account</a>
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
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Refine Your Search</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">CSMS let you search specific data by applying various filters on search. Please choose the filters you want to apply in your next search. After filter selection click the search button...</p>

				<hr>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-6">
							<label class="text-muted">Status</label>
							<select class="form-control select" ng-model="filter.status" data-fouc>
							<option value="">Select Status</option>
							<option value="<?php print $this->user_m->STATUS_ACTIVE;?>" /><?php print strtoupper($this->user_m->STATUS_ACTIVE);?>
							<option value="<?php print $this->user_m->STATUS_UNACTIVE;?>" /><?php print strtoupper($this->user_m->STATUS_UNACTIVE);?>
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

				<p class="text-muted">CSMS let you register new official staff account so that they can login into system on your behalf. Later, then can perform permitted activities. Below you can manage permission for member...</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Member Data</h6>
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
										<input type="text" class="form-control form-control-lg" placeholder="Full Name" ng-model="entry.name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Mobile</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="03xxxxxxxxx" ng-model="entry.mobile">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-mobile"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Login Password<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="password to login" ng-model="entry.password">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user-lock"></i>
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
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update Account ({{selectedRow.name}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">CSMS let you update account here. Please change required data and click on save.</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Member Data</h6>
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
									<label class="text-muted">Mobile</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="03xxxxxxxxx" ng-model="selectedRow.mobile">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-mobile"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Member ID<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="member login id" ng-model="selectedRow.user_id">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user-lock"></i>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Permissions</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>
					<?php
					$prm_0="Restrict Access";
					$prm_1="View with basic activities(E.g Printing";
					$prm_2="Add &amp; update with related activities";
					$prm_3="Full Access";
					 ?>
					<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-4">
									<label class="text-muted">Student Managemnt</label>
									<select class="form-control select" ng-model="selectedRow.prm_std_info" data-fouc>
									<option value="0"><?php print $prm_0;?>
									<option value="1"><?php print $prm_1;?>
									<option value="2"><?php print $prm_2;?>
									<option value="3"><?php print $prm_3;?>
									</select>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Staff Managemnt</label>
									<select class="form-control select" ng-model="selectedRow.prm_stf_info" data-fouc>
									<option value="0"><?php print $prm_0;?>
									<option value="1"><?php print $prm_1;?>
									<option value="2"><?php print $prm_2;?>
									<option value="3"><?php print $prm_3;?>
									</select>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Class Managemnt</label>
									<select class="form-control select" ng-model="selectedRow.prm_class" data-fouc>
									<option value="0"><?php print $prm_0;?>
									<option value="1"><?php print $prm_1;?>
									<option value="2"><?php print $prm_2;?>
									<option value="3"><?php print $prm_3;?>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-4">
									<label class="text-muted">Finance Managemnt</label>
									<select class="form-control select" ng-model="selectedRow.prm_finance" data-fouc>
									<option value="0"><?php print $prm_0;?>
									<option value="1"><?php print $prm_1;?>
									<option value="2"><?php print $prm_2;?>
									<option value="3"><?php print $prm_3;?>
									</select>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Stationary Managemnt</label>
									<select class="form-control select" ng-model="selectedRow.prm_stationary" data-fouc>
									<option value="0"><?php print $prm_0;?>
									<option value="1"><?php print $prm_1;?>
									<option value="2"><?php print $prm_2;?>
									<option value="3"><?php print $prm_3;?>
									</select>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Library Managemnt</label>
									<select class="form-control select" ng-model="selectedRow.prm_library" data-fouc>
									<option value="0"><?php print $prm_0;?>
									<option value="1"><?php print $prm_1;?>
									<option value="2"><?php print $prm_2;?>
									<option value="3"><?php print $prm_3;?>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-4">
									<label class="text-muted">Transport Managemnt</label>
									<select class="form-control select" ng-model="selectedRow.prm_transport" data-fouc>
									<option value="0"><?php print $prm_0;?>
									<option value="1"><?php print $prm_1;?>
									<option value="2"><?php print $prm_2;?>
									<option value="3"><?php print $prm_3;?>
									</select>
								</div>
								<?php if($this->module_m->is_enabled($this->module_m->MODULE_PARENT_PORTAL)){?>
								<div class="col-sm-4">
									<label class="text-muted">Parent Account Managemnt</label>
									<select class="form-control select" ng-model="selectedRow.prm_parents" data-fouc>
									<option value="0"><?php print $prm_0;?>
									<option value="1"><?php print $prm_1;?>
									<option value="2"><?php print $prm_2;?>
									<option value="3"><?php print $prm_3;?>
									</select>
								</div>
								<?php } ?>
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

				<p class="text-muted">CSMS let the official staff login into their portal. You may reset the password for member if they forget the current password...</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Password Reset </h6>
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
									<label class="text-muted">New Passsword for member({{selectedRow.name}})<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Enter New Password here" ng-model="entry.password">
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



</div>
<!-- /main content -->