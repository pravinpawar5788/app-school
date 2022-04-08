<?php
// $classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'title ASC') );
// $sessions=$this->session_m->get_rows(array(),array('orderby'=>'status ASC,mid DESC') );

?>
<!-- Main content -->
<div class="content-wrapper" ng-controller="mozzCtrl"  ng-cloak>

<!-- Page header -->
<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Parents</span> - Parents List</h4>
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
				<span class="breadcrumb-item active">Parents List</span>
			</div>
		</div>
	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb justify-content-center">				
				<a href="<?php print $this->LIB_CONT_ROOT;?>parents" class="breadcrumb-elements-item" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
					<i class="icon-users2 mr-2" style="color:<?php print $clr;?>;"></i> Parents</a>
				<?php if($this->LOGIN_USER->prm_parents>1){?>
				<a href="" class="breadcrumb-elements-item" data-target="#add" <?php print $this->MODAL_OPTIONS;?> style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
					<i class="icon-user-plus mr-2" style="color:<?php print $clr;?>;"></i> Create New Account</a>
				<?php } ?>
				<!-- <a <?php print $this->MODAL_OPTIONS;?> data-target="#list-sms" class="breadcrumb-elements-item mouse-pointer">
					<i class="icon-envelope mr-2"></i> Send SMS Notification
				</a> -->
			</div>

			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>

		<div class="header-elements d-none">
			<div class="breadcrumb justify-content-center">

				<!-- <div class="breadcrumb-elements-item dropdown p-0">
					<a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
						<i class="icon-printer mr-2"></i>Print
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a href="<?php print $this->CONT_ROOT.'printing/list/?';?>" class="dropdown-item"><i class="icon-users"></i> All Parents List</a>
					</div>
				</div> -->
				<!-- on each page -->
				<a href="<?php print $this->APP_ROOT.'docs/parents';?>" target="_blank" class="breadcrumb-elements-item">
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
			<h5 class="mb-3">Search Parents</h5>
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
			<h4 class="card-title">Parents List </h4>
			<span class="text-muted">All registered parents can login into parents portal.</span>
		</div>
		<div class="table-responsive">
		<table class="table tasks-responsive table-lg">
			<thead>
				<tr>
					<th class="font-weight-bold">#</th>
					<th ng-click="sortBy('name');" class="mouse-pointer font-weight-bold" >
						<span class="m-1">Name</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='name'"></i> 
					</th>
					<th ng-click="sortBy('parent_id');" class="mouse-pointer font-weight-bold" >
						<span class="m-1">Parent ID</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='parent_id'"></i> 
					</th>
					<th ng-click="sortBy('mobile');" class="mouse-pointer font-weight-bold" >
						<span class="m-1">Mobile</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='mobile'"></i> 
					</th>
					<th ng-click="sortBy('cnic');" class="mouse-pointer font-weight-bold">
						<span class="m-1">National ID</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='cnic'"></i> 
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
					<td>{{row.parent_id}}</td>
					<td>{{row.mobile}}</td>
  					<td>{{row.cnic}}</td>   
	                <td>
                	<div class="btn-group">
						<a ng-show="row.status==='<?php print $this->parent_m->STATUS_ACTIVE;?>'" href="#" class="badge bg-success dropdown-toggle" data-toggle="dropdown">
							<?php print strtoupper($this->parent_m->STATUS_ACTIVE);?></a>
						<a ng-show="row.status==='<?php print $this->parent_m->STATUS_UNACTIVE;?>'" href="#" class="badge bg-danger dropdown-toggle" data-toggle="dropdown">
							<?php print strtoupper($this->parent_m->STATUS_UNACTIVE);?></a>
						
						<?php if($this->LOGIN_USER->prm_parents>1){?>
						<div class="dropdown-menu dropdown-menu-right">
							<a href="#" class="dropdown-item" ng-show="row.status!=='<?php print $this->parent_m->STATUS_ACTIVE;?>'" ng-click="changeStatus(row, '<?php print $this->parent_m->STATUS_ACTIVE;?>');"><span class="badge badge-mark mr-2 bg-success border-success"></span> Activate</a>
							<a href="#" class="dropdown-item" ng-show="row.status!=='<?php print $this->parent_m->STATUS_UNACTIVE;?>'" ng-click="changeStatus(row, '<?php print $this->parent_m->STATUS_UNACTIVE;?>');"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Disable</a>
							
						</div>
						<?php } ?>
					</div>


                	</td>
					<td>
						<div class="list-icons float-right">
							<div class="btn-group list-icons-item dropdown">
		                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
								<div class="dropdown-menu dropdown-menu-right">

									<a <?php print $this->MODAL_OPTIONS;?> data-target="#students" class="dropdown-item" ng-click="selectRow(row);loadStudents(row)">
										<i class="icon-reading"></i> View Children
									</a>
									<?php if($this->LOGIN_USER->prm_parents>1){?>
									<a <?php print $this->MODAL_OPTIONS;?> data-target="#edit" class="dropdown-item" ng-click="selectRow(row)">
										<i class="icon-compose"></i> Update Account
									</a>
									<div class="dropdown-divider"></div>
									<a <?php print $this->MODAL_OPTIONS;?> data-target="#password" class="dropdown-item" ng-click="selectRow(row)">
										<i class="icon-key"></i> Change Portal Password
									</a>
									<?php if($this->IS_DEV_LOGIN){?>
									<a class="dropdown-item" ng-href="<?php print $this->CONT_ROOT.'login/'?>{{row.mid}}"><i class="icon-user-lock"></i> Account Login
									</a>
									<?php }?>
									<?php } ?>
									<?php if($this->LOGIN_USER->prm_parents>2){?>
									<a class="dropdown-item" ng-click="delRow(row)"><i class="icon-user-cancel"></i> Delete Account</a>
									<?php } ?>
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

				<p class="text-muted">EMS let you search specific data by applying various filters on search. Please choose the filters you want to apply in your next search. After filter selection click the search button...</p>

				<hr>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-6">
							<label class="text-muted">Status</label>
							<select class="form-control select" ng-model="filter.status" data-fouc>
							<option value="">Select Status</option>
							<option value="<?php print $this->student_m->STATUS_ACTIVE;?>" /><?php print strtoupper($this->student_m->STATUS_ACTIVE);?>
							<option value="<?php print $this->student_m->STATUS_UNACTIVE;?>" /><?php print strtoupper($this->student_m->STATUS_UNACTIVE);?>
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
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Create New Account</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you register new parents so that they could login into parents portal to view their children activities. Please make sure to match national id of father. Only students who has same father national ID will be considered as children of the parent...</p>
				

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
								<div class="col-sm-6">
									<label class="text-muted">Parent Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Full Name" ng-model="entry.name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Parent Mobile</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="03xxxxxxxxx" ng-model="entry.mobile">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-mobile"></i>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<label class="text-muted">Father National ID<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Father National ID" ng-model="entry.cnic">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Login Password</label>
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

				<p class="text-muted">EMS let you update account here. Please change required data and click on save.</p>
				

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
								<div class="col-sm-6">
									<label class="text-muted">Parent Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Full Name" ng-model="selectedRow.name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Mobile </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="03xxxxxxxxx" ng-model="selectedRow.mobile">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-mobile"></i>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<label class="text-muted">Parent Login ID<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Full Name" ng-model="selectedRow.parent_id">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user-lock"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Father National ID <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="" ng-model="selectedRow.cnic">
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

				<p class="text-muted">EMS let the parent login into their portal. You may reset the password for parent portal if they forget the current password...</p>
				

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
									<label class="text-muted">New Passsword for parent({{selectedRow.name}})<span class="text-danger"> * </span></label>
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


<!-- load student modal -->
<div id="students" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Registered Children of (<strong>{{selectedRow.name}}</strong>) to student</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">
				<div class="table-responsive">
					<table class="table tasks-responsive table-lg">
						<thead>
							<tr>
								<th class="font-weight-bold">#</th>
								<th class="font-weight-bold">Student Name</th>
								<th class="font-weight-bold">Student ID</th>
								<th class="font-weight-bold">Class</th>
								<th class="font-weight-bold">Fee</th>
				            </tr>
						</thead>
						<tbody>

							<tr ng-repeat="row in students">
								<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
								<td>
									<div><a ng-href="<?php print $this->LIB_CONT_ROOT.'student/profile/'?>{{row.mid}}" class="font-weight-semibold">
									<img ng-src="<?php print $this->UPLOADS_ROOT;?>images/student/profile/{{row.image}}" class="rounded-circle m-1" width="32" height="32" alt="">
        							{{row.name}}
									</a></div>
								</td>  
								<td>{{row.student_id}}</td>
			  					<td>{{row.class}}</td> 
			  					<td><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]; ?>{{row.fee}}</td> 
								
				            </tr>


						</tbody>
					</table>					
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /load student modal -->



</div>
<!-- /main content -->
<!-- ********************************************************************** -->
<!-- ///////////////////////////////SCRIPTS//////////////////////////////// -->
<!-- ********************************************************************** -->
<!-- <script type="text/javascript">
	
$(function(){
   
});
</script> -->