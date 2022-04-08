<?php
// $sessions=$this->session_m->get_rows(array(),array('orderby'=>'status ASC,mid DESC') );
// $classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'title ASC') );
// $classes_array=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
// $sessions_array=$this->session_m->get_values_array('mid','title',array());
// $types=$this->stationary_m->get_item_types();
$accounts=$this->accounts_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID,'type'=>$this->accounts_m->TYPE_EXPENSE,'period_id'=>$this->CURRENT_ACCOUNTING_PID));
$credit_accounts=$this->accounts_m->get_rows(array('campus_id'=>$this->CAMPUSID,'period_id'=>$this->CURRENT_ACCOUNTING_PID,'type'=>$this->accounts_m->TYPE_ASSETS,'is_current_asset >'=>0));
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
					<span class="breadcrumb-item active">Expenses</span>
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
					<div class="dropdown-menu dropdown-menu-right">
						<a href="<?php print $this->CONT_ROOT.'printing/report/?rpt=expenses';?>" class="dropdown-item"><i class="icon-credit-card"></i> Expenses Report</a>
						<!-- <a href="<?php print $this->CONT_ROOT.'printing/report/?rpt=expenses';?>" class="dropdown-item"><i class="icon-cash"></i> Attendance Report</a>
						<a href="<?php print $this->CONT_ROOT.'printing/history/?usr=';?>" class="dropdown-item"><i class="icon-cash"></i> History</a>
						<div class="dropdown-divider"></div> -->
					</div>
					</div>

				</div>
			</div>
		</div>
		<!-- /breadcrumb line -->

	
		<!-- Page header content -->
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Finance</span> - Expenses</h4>
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
							Expenses
						</a>
					</li>
					<?php if($this->LOGIN_USER->prm_finance>1){ ?>
					<li class="nav-item">
						<a class="navbar-nav-link" <?php print $this->MODAL_OPTIONS;?> data-target="#add" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-plus-circle2 mr-2" style="color:<?php print $clr;?>;"></i>
							Add Expense
						</a>
					</li>
					<?php } ?>
				</ul>

				<ul class="nav navbar-nav ml-lg-auto">
					<!-- li class="nav-item">
						<a href="#" class="navbar-nav-link">
							<i class="icon-stack-text mr-2"></i>
							Notes
						</a>
					</li> -->
					<!-- <li class="nav-item">
						<a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
							<i class="icon-gear"></i>
							<span class="d-lg-none ml-2">Settings</span>
						</a>

						<div class="dropdown-menu dropdown-menu-right">
							<a <?php print $this->MODAL_OPTIONS;?> data-target="#create-voucher" class="dropdown-item"><i class="icon-image2"></i> Generate Salary Slips</a>
							<a <?php print $this->MODAL_OPTIONS;?> data-target="#list-sms" class="dropdown-item"><i class="icon-envelop"></i> SMS Notification</a>
							<div class="dropdown-divider"></div>
							<a ng-click="delVouchers()" class="dropdown-item"><i class="icon-cross"></i> Remove Salary Slips</a>
						</div>
					</li> -->
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
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Expenses</h4>
							<span class="text-muted">Search expenses of the campus.</span>
						</div>
						<div class="card-body">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-8">
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
									<div class="col-sm-4">
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
								<p class="text-muted">Find below the expenses for the campus. System will automatically create expense entries. You may create manual entries alongside.</p>
								<table class="table tasks-responsive table-lg">
									<thead>
										<tr>
											<th class="font-weight-bold">#</th>
											<th class="font-weight-bold">Type</th>
											<th class="font-weight-bold">Title</th>
											<th class="font-weight-bold">Amount</th>
											<th class="font-weight-bold">Date</th>
											<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
							            </tr>
									</thead>
									<tbody>

										<tr ng-repeat="row in responseData.rows">
											<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
											<td>{{row.type | uppercase}}</td>
											<td>{{row.title}}</td>
											<td><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]; ?> {{row.amount}}</td>
											<td>{{row.date}}</td>
											<td>
												<div class="list-icons float-right">
													<div class="btn-group list-icons-item dropdown">
								                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>

														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#view" ng-click="selectRow(row);loadVoucher(row);">
																<i class="icon-eye"></i> View Record 
															</a>
															<?php if($this->LOGIN_USER->prm_finance>1){ ?>
															<a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#edit" ng-click="selectRow(row)">
																<i class="icon-compose"></i> Update Record
															</a>
															<?php } ?>
															<?php if($this->LOGIN_USER->prm_finance>2){ ?>
															<div class="dropdown-divider"></div>
															<a class="dropdown-item" ng-click="delRow(row)" ng-show="row.account_id>0"><i class="icon-cross text-danger"></i> Remove Record</a>
															<?php } ?>
															<?php if($this->IS_DEV_LOGIN){ ?>
															<a class="dropdown-item" ng-click="delRow(row)"><i class="icon-cross text-danger"></i> Strict Remove</a>
															<?php } ?>
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
						<div class="col-sm-3">
							<label class="text-muted">Select Account</label>
							<select class="form-control select" ng-model="filter.account" data-fouc>
							<option value="">All Accounts</option>
							<?php foreach($accounts as $key => $value){?>
							<option value="<?php print $key;?>" /><?php print $value;?>
							<?php }?>
							</select>
						</div>			
						<div class="col-sm-3">
							<label class="text-muted">Month</label>
							<select class="form-control select" ng-model="filter.month" data-fouc>
								<option value="">Any Month</option>
								<?php 
								for($i=1; $i<=12; $i++){?>            
								    <option value="<?php print $i;?>" /><?php print month_string($i);?>
							    <?php }?>
							</select>
						</div>				
						<div class="col-sm-3">
							<label class="text-muted">Year</label>
							<select class="form-control select" ng-model="filter.year" data-fouc>
								<option value="">Current Year</option>
								<?php 
								$year=$this->std_fee_voucher_m->year;
								while($year >= $this->SETTINGS[$this->system_setting_m->_INSTALL_YEAR]){?>            
								    <option value="<?php print $year;?>" /><?php print $year;?>
							    <?php $year--;}?>
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
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Create New Expense Entry</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you create expense entries manul to reflect outside expenses in monthly and anual reports</p>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-3">
							<label class="text-muted">Select Type/Category</label>
							<select class="form-control select" ng-model="entry.account" data-fouc>
							<option value="">Select Type</option>
							<?php foreach($accounts as $key => $value){?>
							<option value="<?php print $key;?>" /><?php print $value;?>
							<?php }?>
							</select>
						</div>	
						<div class="col-sm-3">
							<label class="text-muted">Title</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-lg" placeholder="Title" ng-model="entry.title">
								<div class="form-control-feedback form-control-feedback-lg">
									<i class="icon-vcard"></i>
								</div>
							</div>
						</div>	
						<div class="col-sm-2">
							<label class="text-muted">Select Date</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-lg datepicker" placeholder="Expense Date" ng-model="entry.date">
								<div class="form-control-feedback form-control-feedback-lg">
									<i class="icon-calendar"></i>
								</div>
							</div>
						</div>	
						<div class="col-sm-2">
							<label class="text-muted">Amount</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-lg" placeholder="Amount" ng-model="entry.amount">
								<div class="form-control-feedback form-control-feedback-lg">
									<i class="icon-coin-dollar"></i>
								</div>
							</div>
						</div>	
						<div class="col-sm-2">
							<label class="text-muted">Paid Via<span class="text-danger"> * </span></label>
							<select class="form-control select" ng-model="entry.credit_account" data-fouc>
							<option value="">Select Credit Account</option>
							<?php foreach ($credit_accounts as $acct) { ?>
							    <option value="<?php print $acct['title'];?>" /><?php print $acct['title'];?>
							<?php } ?>
							</select>
						</div> 
						
					</div>
				</div>				
				<legend class="font-weight-semibold text-uppercase font-size-sm">
					<i class="icon-envelop mr-2"></i>Write Detail...</legend>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-10">
							<textarea class="form-control" ng-model="entry.description" placeholder="Write here detail..." rows="5"></textarea>
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
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update Expense Entry</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you update expense entries...</p>
				<div class="form-group">
					<div class="row">	
						<div class="col-sm-3">
							<label class="text-muted">Select Date</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-lg datepicker" placeholder="Expense Date" ng-model="selectedRow.date">
								<div class="form-control-feedback form-control-feedback-lg">
									<i class="icon-calendar"></i>
								</div>
							</div>
						</div>	
						<div class="col-sm-3">
							<label class="text-muted">Title</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-lg" placeholder="Title" ng-model="selectedRow.title">
								<div class="form-control-feedback form-control-feedback-lg">
									<i class="icon-vcard"></i>
								</div>
							</div>
						</div>	
						<div class="col-sm-3">
							<label class="text-muted">Amount</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-lg" placeholder="Amount" ng-model="selectedRow.amount">
								<div class="form-control-feedback form-control-feedback-lg">
									<i class="icon-coin-dollar"></i>
								</div>
							</div>
						</div>	
						
					</div>
				</div>				
				<legend class="font-weight-semibold text-uppercase font-size-sm">
					<i class="icon-envelop mr-2"></i>Write Detail...</legend>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-10">
							<textarea class="form-control" ng-model="selectedRow.description" placeholder="Write here detail..." rows="5"></textarea>
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


</div>
<!-- /main content -->
<!-- ********************************************************************** -->
<!-- ///////////////////////////////SCRIPTS//////////////////////////////// -->
<!-- ********************************************************************** -->
<!-- <script type="text/javascript">
	
$(function(){
   
});
</script> -->