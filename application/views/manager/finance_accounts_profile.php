<?php
// $sessions=$this->session_m->get_rows(array(),array('orderby'=>'status ASC,mid DESC') );
// $classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'title ASC') );
// $classes_array=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
$accounts=$this->accounts_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
// $types=$this->stationary_m->get_item_types();
// $types=$this->income_m->get_income_types();
?>
<!-- Main content -->
<div class="content-wrapper" ng-controller="mozzCtrl"  ng-cloak>
	<!-- Page header -->
	<div class="page-header page-header-light border-bottom-0" ng-init="rid='<?php print $account->mid;?>'">

		<!-- Top breadcrumb line -->
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
					<a class="breadcrumb-item">Finance</a>
					<span class="breadcrumb-item active">Account Detail</span>
				</div>

				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="breadcrumb justify-content-center">
					<!-- <a href="#" class="breadcrumb-elements-item sidebar-control sidebar-component-toggle d-none d-md-block">
						<i class="icon-drag-right mr-2"></i>Toggle Profile
					</a> -->

					<!-- <div class="breadcrumb-elements-item dropdown p-0  d-none d-md-block">						
					<a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
						<i class="icon-printer mr-2"></i>Print
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a href="<?php print $this->CONT_ROOT.'printing/list/';?>" class="dropdown-item"><i class="icon-vcard"></i> Slips List</a>
						<a href="<?php print $this->CONT_ROOT.'printing/attendance/?usr=';?>" class="dropdown-item"><i class="icon-cash"></i> Attendance Report</a>
						<a href="<?php print $this->CONT_ROOT.'printing/history/?usr=';?>" class="dropdown-item"><i class="icon-cash"></i> History</a>
						<div class="dropdown-divider"></div>
					</div>
					</div> -->

				</div>
			</div>
		</div>
		<!-- /breadcrumb line -->

	
		<!-- Page header content -->
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Finance</span> - Account Detail</h4>
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
							Account Details
						</a>
					</li>
					<li class="nav-item">
						<a href="<?php print $this->CONT_ROOT.'accounts';?>" class="navbar-nav-link" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-arrow-left52 mr-2" style="color:<?php print $clr;?>;"></i>
							Go Back To Accounts
						</a>
					</li> 
					<!-- <li class="nav-item">
						<a class="navbar-nav-link" <?php print $this->MODAL_OPTIONS;?> data-target="#add" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-plus-circle2 mr-2" style="color:<?php print $clr;?>;"></i>
							Add Record
						</a>
					</li> -->
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
		<?php $this->load->view($this->LIB_VIEW_DIR.'includes/alert_inc');?>

		<!-- Inner container -->
		<div class="d-flex align-items-start flex-column flex-md-row">

			<!-- Left content -->
			<div class="tab-content w-100 overflow-auto order-2 order-md-1">


			    <div class="tab-pane fade <?php print empty($tab) || $tab=='index' ? 'active show': '';?>" id="index">
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Account Details <strong>(<?php print $account->title;?>)</strong> having category(<?php print ucwords($account->type); ?>)</h4>

							<button class="btn btn-default float-right">
								<span class="font-weight-bold"> Account Balance </span>
								<span>
									<?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].' '.$this->accounts_m->get_normalized_amount($account->type,$account->balance);
									?>
								</span> 
							</button>
							<span class="text-muted">A <strong>positive balance</strong> in <i>Asset or Expense</i> category accounts is a <span class="text-success">debit balance</span>. A <strong>positive balance</strong> in a <i>Liability account, Equity account, or a Revenue account</i> is a <span class="text-warning">Credit balance</span>. Search below the account details.</span>
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
								<p class="text-muted">Find below the activities detail for this account.</p>
								<table class="table tasks-responsive table-lg">
									<thead>
										<tr>
											<th class="font-weight-bold">#</th>
											<th class="font-weight-bold">Date</th>
											<th class="font-weight-bold">Title</th>
											<th class="font-weight-bold">Debit</th>
											<th class="font-weight-bold">Credit</th>
											<th class="font-weight-bold">Balance</th>
							            </tr>
									</thead>
									<tbody>

										<tr ng-repeat="row in responseData.rows">
											<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
											<td>{{row.date}}</td>
											<td>{{row.title}}</td>
											<td><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]; ?> {{row.d_amount}}</td>
											<td><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]; ?> {{row.c_amount}}</td>
											<td><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]; ?> {{row.balance}}</td>
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
							<label class="text-muted">Transaction Type</label>
							<select class="form-control select" ng-model="filter.type" data-fouc>
							<option value="">Any Type</option>
							<option value="dr">Debit Transactions</option>
							<option value="cr">Credit Transactions</option>
							</select>
						</div>	
						<div class="col-sm-3">
							<label class="text-muted">Date</label>							
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-sm datepicker" placeholder="Transaction Date" ng-model="filter.date">
								<div class="form-control-feedback form-control-feedback-sm">
									<i class="icon-calendar"></i>
								</div>
							</div>
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



</div>
<!-- /main content -->
<!-- ********************************************************************** -->
<!-- ///////////////////////////////SCRIPTS//////////////////////////////// -->
<!-- ********************************************************************** -->
<!-- <script type="text/javascript">
	
$(function(){
   
});
</script> -->