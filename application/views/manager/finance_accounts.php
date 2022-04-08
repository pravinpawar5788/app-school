<?php
$accounting_periods=$this->accounts_period_m->get_rows(array('campus_id'=>$this->CAMPUSID));
// $sessions=$this->session_m->get_rows(array(),array('orderby'=>'status ASC,mid DESC') );
// $classes_array=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
// $sessions_array=$this->session_m->get_values_array('mid','title',array());
// $types=$this->stationary_m->get_item_types();
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
					<a class="breadcrumb-item">Finance</a>
					<span class="breadcrumb-item active">Accounts</span>
				</div>

				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="breadcrumb justify-content-center">
					<!-- <a href="#" class="breadcrumb-elements-item sidebar-control sidebar-component-toggle d-none d-md-block">
						<i class="icon-drag-right mr-2"></i>Toggle Profile
					</a> -->

					<div class="breadcrumb-elements-item dropdown p-0  d-none d-md-block">
					<a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
						<i class="icon-printer mr-2"></i>Print
					</a>
					<div class="dropdown-menu dropdown-menu-left">
						<div class="dropdown-submenu dropdown-submenu-left">
							<a href="#" class="dropdown-item"><i class="icon-clipboard6"></i> Statements</a>
							<div class="dropdown-menu">
								<a href="<?php print $this->CONT_ROOT.'printing/report/?rpt=fstatements';?>" class="dropdown-item">Trial Balance</a>
								<a href="<?php print $this->CONT_ROOT.'printing/report/?rpt=fstatements&stmt=balance';?>" class="dropdown-item">Balance Sheet</a>
								<a href="<?php print $this->CONT_ROOT.'printing/report/?rpt=fstatements&stmt=income';?>" class="dropdown-item">Income Statement</a>
								<!-- <a href="<?php print $this->CONT_ROOT.'printing/';?>" class="dropdown-item">Daily Fee Collection</a> -->
								<!-- <a href="<?php print $this->CONT_ROOT.'printing/';?>" class="dropdown-item">Monthly Fee Collection</a> -->
							</div>
						</div>
						<!-- <a href="<?php print $this->CONT_ROOT.'printing/list/';?>" class="dropdown-item"><i class="icon-vcard"></i> Slips List</a> -->
						<!-- <div class="dropdown-divider"></div> -->
					</div>
					</div> 

				</div>
			</div>
		</div>
		<!-- /breadcrumb line -->

	
		<!-- Page header content -->
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Finance</span> - Accounts</h4>
				<a class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="d-flex justify-content-center">
					<!-- <a href="#" class="btn btn-link btn-float text-default"><i class="icon-calculator text-success"></i><span class="text-success">Feeroll</span></a> -->
					<!-- <a href="#" class="btn btn-link btn-float text-default"><i class="icon-bars-alt text-primary"></i><span>Statistics</span></a> -->
				</div>
			</div>
		</div>
		<!-- /page header content -->


		<!-- Page navigation -->
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
						<a href="#index" class="navbar-nav-link active" data-toggle="tab" ng-click="loadRows()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-credit-card mr-2" style="color:<?php print $clr;?>;"></i>
							Accounts
						</a>
					</li>
					<li class="nav-item">
						<a class="navbar-nav-link" <?php print $this->MODAL_OPTIONS;?> data-target="#add" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-plus-circle2 mr-2" style="color:<?php print $clr;?>;"></i>
							Add Account
						</a>
					</li>
				</ul>

				<ul class="nav navbar-nav ml-lg-auto">
					<!-- li class="nav-item">
						<a href="#" class="navbar-nav-link">
							<i class="icon-stack-text mr-2"></i>
							Notes
						</a>
					</li> -->

					<?php if($this->IS_DEV_LOGIN){ ?>
					<li class="nav-item">
						<a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-gear"></i>
							<span class="ml-1"><strong>More</strong></span>
						</a>

						<?php if($this->LOGIN_USER->prm_finance>1){ ?>
						<div class="dropdown-menu dropdown-menu-right">
							<a <?php print $this->MODAL_OPTIONS;?> data-target="#reset-accounts" class="dropdown-item"><i class="icon-reset"></i> Reset Accounts</a>
						</div>
						<?php } ?>
					</li>
					<?php } ?>
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


			    <div class="tab-pane fade <?php print empty($tab) || $tab=='index' ? 'active show': '';?>" id="index">
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Accounts</h4>
							<span class="text-muted">Search accounts of the campus.</span>
						</div>
						<div class="card-body">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-9">
										<div class="input-group mb-4">
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
										<a class="btn btn-info btn-lg text-white" <?php print $this->MODAL_OPTIONS;?> data-target="#refine-search">
												<i class="icon-filter3"></i> Advance Search</a>
										<a class="btn btn-danger text-white" ng-show="showFilter()" ng-click="clearFilter();loadRows();">
												<i class="icon-diff-removed"></i></a>
										<!-- <div class="list-icons m-2" ng-show="showFilter()" >
											<div class="list-icons-item dropdown">
												<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown">
													<i class="icon-menu9 mr-5"></i></a>
												<div class="dropdown-menu dropdown-menu-right">
													<a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#view"">
														<i class="icon-eye"></i> View Record 
													</a>
													<a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#edit">
														<i class="icon-compose"></i> Update Record
													</a>
												</div>
											</div>
										</div> -->

									</div>
								</div>
							</div>
							<div class="table-responsive">
								<p class="text-muted">Find below the accounts for the campus. System will automatically create neccessary accounts. You may create additional accounts for the purpose of financial transactions.</p>
								<table class="table tasks-responsive table-lg">
									<thead>
										<tr>
											<th class="font-weight-bold">#</th>
											<th class="font-weight-bold">Title</th>
											<th class="font-weight-bold">Account Balance</th>
											<th class="font-weight-bold">Type</th>
											<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
							            </tr>
									</thead>
									<tbody>

										<tr ng-repeat="row in responseData.rows">
											<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
											<td><a ng-href="<?php print $this->CONT_ROOT.'accountpro/'?>{{row.mid}}">{{row.title}}</a></td>
											<td><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?> {{row.norm_balance}}</td>
											<td>{{row.type | uppercase}}</td>
											<td class="text-center">
												<div class="list-icons" ng-show="row.is_default<1">
													<div class="list-icons-item dropdown">
														<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9 mr-5"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<!-- <a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#view" ng-click="selectRow(row);loadVoucher(row);">
																<i class="icon-eye"></i> View Record 
															</a> -->
															<a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#edit" ng-click="selectRow(row)">
																<i class="icon-compose"></i> Update Record
															</a>
															<div class="dropdown-divider"></div>
															<a class="dropdown-item" ng-click="delRow(row)" ng-show="row.norm_balance!==0"><i class="icon-cross text-danger"></i> Remove Record</a>
														</div>
													</div>
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
					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='history' ? 'active show': '';?>" id="history">
					
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


<!-- refine search modal -->
<div id="refine-search" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
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
							<label class="text-muted">Accounting Period</label>
							<select class="form-control select" ng-model="filter.period" data-fouc>
							<option value="">Current Period</option>
							<?php foreach($accounting_periods as $row){
								?>
								<option value="<?php print $row['mid'] ?>"><?php print $row['title']; ?></option>
								<?php
							} ?>
							</select>
						</div>	
						<div class="col-sm-6">
							<label class="text-muted">Select Type</label>
							<select class="form-control select" ng-model="filter.type" data-fouc>
							<option value="">All Types</option>
							<option value="<?php print $this->accounts_m->TYPE_REVENUE; ?>">Revenue</option>
							<option value="<?php print $this->accounts_m->TYPE_EXPENSE; ?>">Expenses</option>
							<option value="<?php print $this->accounts_m->TYPE_LIABILITY; ?>">Libility</option>
							<option value="<?php print $this->accounts_m->TYPE_ASSETS; ?>">Assets</option>
							<option value="<?php print $this->accounts_m->TYPE_CAPITAL; ?>">Capital</option>
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


<!-- view modal -->
<div id="view" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h4 class="modal-title">Information of <strong>({{selectedRow.title}})</strong></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<!-- Invoice template -->
				<div class="card">

					<div class="card-body">
						<div class="row">
							<div class="col-sm-8">
								<div class="mb-4">
									<h4 class="text-primary mb-2 mt-md-2">Amount# <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]; ?>{{selectedRow.amount}}</h4>
		 							<ul class="list list-unstyled mb-0">
										<li>Account: <span class="font-weight-semibold">{{selectedRow.account | uppercase}}</span></li>
										<li>Date: <span class="font-weight-semibold">{{selectedRow.date}}</span></li>
										<li>Title: 
											<span class="text-success" > {{selectedRow.title}}</span>
										</li>
									</ul>
								</div>
							</div>

						</div>
					</div>

					<div class="card-footer">
						<span class="text-muted">{{selectedRow.description}}</span>
					</div>
				</div>
				<!-- /invoice template -->


			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /view modal -->

<!-- add -->
<div id="add" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Create New Account</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">
				
				<legend class="font-weight-semibold text-uppercase font-size-sm">
					<i class="icon-vcard mr-2"></i>Please provide account details...</legend>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-6">
							<label class="text-muted">Select Type</label>
							<select class="form-control select" ng-model="entry.type" data-fouc>
							<option value="">Select Account Type</option>
							<option value="<?php print $this->accounts_m->TYPE_REVENUE; ?>">Revenue</option>
							<option value="<?php print $this->accounts_m->TYPE_EXPENSE; ?>">Expenses</option>
							<option value="<?php print $this->accounts_m->TYPE_LIABILITY; ?>">Libility</option>
							<option value="<?php print $this->accounts_m->TYPE_ASSETS; ?>">Assets</option>
							<option value="<?php print $this->accounts_m->TYPE_CAPITAL; ?>">Capital</option>
							</select>
						</div>	
						<div class="col-sm-6">
							<label class="text-muted">Title</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-lg" placeholder="Title" ng-model="entry.title">
								<div class="form-control-feedback form-control-feedback-lg">
									<i class="icon-vcard"></i>
								</div>
							</div>
						</div>	
						<div class="col-sm-6" ng-show="entry.type==='<?php print $this->accounts_m->TYPE_ASSETS; ?>'">
							<label class="text-muted">Is this a current asset?</label>
							<select class="form-control select" ng-model="entry.is_current_asset" data-fouc>
							<option value="">No, This is Fixed Asset Accoount</option>
							<option value="1">Yes, This is Current Asset Accoount</option>
							</select>
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
<!-- /add-->

<!-- edit-->
<div id="edit" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update Expense Entry</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<legend class="font-weight-semibold text-uppercase font-size-sm">
					<i class="icon-vcard mr-2"></i>Please provide account details...</legend>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-6">
							<label class="text-muted">Title</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-lg" placeholder="Title" ng-model="selectedRow.title">
								<div class="form-control-feedback form-control-feedback-lg">
									<i class="icon-vcard"></i>
								</div>
							</div>
						</div>	
						<div class="col-sm-6" ng-show="selectedRow.type==='<?php print $this->accounts_m->TYPE_ASSETS; ?>'">
							<label class="text-muted">Is this a current asset?</label>
							<select class="form-control select" ng-model="selectedRow.is_current_asset" data-fouc>
							<option value="0">No, This is Fixed Asset Accoount</option>
							<option value="1">Yes, This is Current Asset Accoount</option>
							</select>
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
<!-- /edit-->

<!-- reset -->
<div id="reset-accounts" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Reset Accounts For this Campus</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">
				<p class="text-danger"><code><b>Be Careful!</b></code> All the financial accounting data of this campus will be removed from the system. The removed data is not recoverable. So do this only after the discussion with administrator.</p>
				<br>
				<legend class="font-weight-semibold text-uppercase font-size-sm">
					<i class="icon-lock mr-2"></i>Please provide necessary details...</legend>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-12">
							<label class="text-muted">Reset Password</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-lg" placeholder="Enter Data Reset Password" ng-model="entry.key">
								<div class="form-control-feedback form-control-feedback-lg">
									<i class="icon-key"></i>
								</div>
							</div>
						</div>	
						
					</div>
				</div>	

				<br>
				<p class="text-success">We recommend download some of necessary backups before this operation.</p>
				<p><a ng-href="<?php print $this->CONT_ROOT.'accountsbackup';?>/{{entry.key}}/accounts" target="_blank">download accounts backup</a></p>
				<p><a ng-href="<?php print $this->CONT_ROOT.'accountsbackup';?>/{{entry.key}}/ledger" target="_blank">download journal backup</a></p>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="resetAccounts()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Reset Accounts</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /add-->


</div>
<!-- /main content -->
<!-- ********************************************************************** -->
<!-- ///////////////////////////////SCRIPTS//////////////////////////////// -->
<!-- ********************************************************************** -->
<!-- <script type="text/javascript">
	
$(function(){
   
});
</script> -->