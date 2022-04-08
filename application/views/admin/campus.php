
<!-- Main content -->
<div class="content-wrapper" ng-controller="mozzCtrl"  ng-cloak>

<!-- Page header -->
<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Campuses</span> - All Campuses</h4>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a><br>			
		</div>

		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php print $this->LIB_CONT_ROOT;?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<span class="breadcrumb-item active">Campuses List</span>
			</div>
		</div>
	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb justify-content-center">				
				<a href="<?php print $this->LIB_CONT_ROOT;?>campus" class="breadcrumb-elements-item"><i class="icon-office mr-2"></i> Campuses</a>
				<a href="" class="breadcrumb-elements-item" data-target="#add" <?php print $this->MODAL_OPTIONS;?>>
					<i class="icon-user-plus mr-2"></i> Add New Campus</a>
			</div>

			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
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
			<h5 class="mb-3">Search</h5>
			<div class="row">
				<div class="col-sm-9">
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
					<!-- <a class="btn btn-info text-white" <?php print $this->MODAL_OPTIONS;?> data-target="#refine-search">
							<i class="icon-filter3"></i></a>
					<a class="btn btn-danger text-white" ng-show="showFilter()" ng-click="clearFilter();loadRows();">
							<i class="icon-diff-removed"></i></a> -->

				</div>
			</div>

		</div>
	</div>
	<!-- /search field -->

	<!-- List table -->
	<div class="card">
		<div class="card-header bg-transparent">
			<h4 class="card-title">Campuses List </h4>
			<span class="text-muted">Registered campuses in the system. Hare you can add all campuses of your institute.</span>
		</div>
		<div class="table-responsive">
		<table class="table tasks-responsive table-lg">
			<thead>
				<tr>
					<th class="font-weight-bold">#</th>
					<th ng-click="sortBy('name');" class="mouse-pointer font-weight-bold" >
						<span class="m-1">Name</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='name'"></i> 
					</th>
					<th class="font-weight-bold">Contact Number</th>
					<th class="font-weight-bold">Address</th>
					<th class="font-weight-bold">Date Since</th>
					<th class="text-center text-muted" style="width: 40px;"><i class="icon-checkmark3"></i></th>
	            </tr>
			</thead>
			<tbody>

				<tr ng-repeat="row in responseData.rows">
					<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
	                <td>
	                	<div><a ng-href="<?php print $this->CONT_ROOT.'profile/'?>{{row.mid}}" class="font-weight-semibold">
                		{{row.name}}
						</a></div>
					</td> 
					<td>{{row.contact_number}}</td>
					<td>{{row.address}}</td>
					<td>{{row.date}}</td>
					<td class="text-center">
						<div class="list-icons">
							<div class="list-icons-item dropdown">
		                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
								<div class="dropdown-menu dropdown-menu-right">
									<a ng-href="<?php print $this->CONT_ROOT.'profile/';?>{{row.mid}}" class="dropdown-item"><i class="icon-vcard"></i> Details</a>
									<a <?php print $this->MODAL_OPTIONS;?> data-target="#edit" class="dropdown-item" ng-click="selectRow(row)">
										<i class="icon-compose"></i> Update Campus
									</a>
									 <div class="dropdown-divider"></div>
									<a class="dropdown-item" ng-click="delRow(row)"><i class="icon-cross"></i> Delete Campus</a>
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

				<p class="text-muted">Search specific data by applying various filters on search. Please choose the filters you want to apply in your next search. After filter selection click the search button...</p>

				<hr>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<label class="text-muted">Status</label>
							<select class="form-control select" ng-model="filter.status" data-fouc>
							<option value="">All</option>
							</select>
						</div>
						<div class="col-sm-3">
							<label class="text-muted">Filter Key</label>
							<select class="form-control select" ng-model="filter.filter_key" data-fouc>
							<option value="">All </option>
							</select>
						</div>
						<div class="col-sm-3">
							<label class="text-muted">Enter Value </label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-sm" placeholder="Enter filter value" ng-model="filter.filter_value">
								<div class="form-control-feedback form-control-feedback-sm">
									<i class="icon-vcard"></i>
								</div>
							</div>
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
				<h6 class="modal-title">Add New Campus</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Register new campus here. Please provide required information to proceed next...</p>	
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">General Information</h6>
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
									<label class="text-muted">Campus Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-sm" placeholder="Campus name" ng-model="entry.name">
										<div class="form-control-feedback form-control-feedback-sm">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Contact Number </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-sm" placeholder="Contact Number" ng-model="entry.contact_number">
										<div class="form-control-feedback form-control-feedback-sm">
											<i class="icon-mobile"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Address <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-sm" placeholder="Address" ng-model="entry.address">
										<div class="form-control-feedback form-control-feedback-sm">
											<i class="icon-pin"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label class="text-muted">Manager Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-sm" placeholder="Manager name" ng-model="entry.mname">
										<div class="form-control-feedback form-control-feedback-sm">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Manager Contact Number </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-sm" placeholder="Manager Contact Number" ng-model="entry.mobile">
										<div class="form-control-feedback form-control-feedback-sm">
											<i class="icon-mobile"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Manager Email Address<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-sm" placeholder="Manger Email Address" ng-model="entry.email">
										<div class="form-control-feedback form-control-feedback-sm">
											<i class="icon-envelope"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Account Password <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="password" class="form-control form-control-sm" placeholder="Account Password" ng-model="entry.password">
										<div class="form-control-feedback form-control-feedback-sm">
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
				<h6 class="modal-title">Update Campus ({{selectedRow.name}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Please provide required information to proceed next...</p>				
				<p class="text-muted"><code>Registered Since: </code> {{selectedRow.date}}</p>
				


				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">General Information</h6>
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
									<label class="text-muted">Campus Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-sm" placeholder="Campus name" ng-model="selectedRow.name">
										<div class="form-control-feedback form-control-feedback-sm">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Contact Number </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-sm" placeholder="Contact Number" ng-model="selectedRow.contact_number">
										<div class="form-control-feedback form-control-feedback-sm">
											<i class="icon-mobile"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Address</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-sm" placeholder="Address" ng-model="selectedRow.address">
										<div class="form-control-feedback form-control-feedback-sm">
											<i class="icon-pin"></i>
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




</div>
<!-- /main content -->