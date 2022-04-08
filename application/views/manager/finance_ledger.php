<?php
// $sessions=$this->session_m->get_rows(array(),array('orderby'=>'status ASC,mid DESC') );
// $classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'title ASC') );
// $classes_array=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
$accounts=$this->accounts_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
$accounting_periods=$this->accounts_period_m->get_rows(array('campus_id'=>$this->CAMPUSID));
// $types=$this->stationary_m->get_item_types();
// $types=$this->income_m->get_income_types();
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
					<span class="breadcrumb-item active">Journal</span>
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
						<a href="<?php print $this->CONT_ROOT.'printing/report/?rpt=ledger';?>" class="dropdown-item"><i class="icon-credit-card"></i> Journal Report</a>
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
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Finance</span> - Journal</h4>
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
							Journal
						</a>
					</li>
					<li class="nav-item">
						<a class="navbar-nav-link" <?php print $this->MODAL_OPTIONS;?> data-target="#add" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-plus-circle2 mr-2" style="color:<?php print $clr;?>;"></i>
							Add Manual Record
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
							<h4 class="card-title">Journal</h4>
							<span class="text-muted">Search below the history of general ledger of the campus.</span>
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
								<p class="text-muted">Find below the general ledger of the campus. System will automatically create ledger entries.</p>
								<table class="table tasks-responsive table-lg">
									<thead>
										<tr>
											<th class="font-weight-bold">#</th>
											<th class="font-weight-bold">Title</th>
											<th class="font-weight-bold">Debit</th>
											<th class="font-weight-bold">Credit</th>
											<th class="font-weight-bold">Date</th>
											<th class="text-center text-muted" style="min-width: 140px;"><i class="icon-checkmark3 mr-5"></i></th>
							            </tr>
									</thead>
									<tbody>

										<tr ng-repeat="row in responseData.rows">
											<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
											<td>{{row.title}}</td>
											<td>{{row.debit_account}} (<?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]; ?> {{row.debit_amount}})</td>
											<td>{{row.credit_account}} (<?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]; ?> {{row.credit_amount}})</td>
											<td>{{row.date}}</td>
											<td class="text-center">
												<a class="btn" <?php print $this->MODAL_OPTIONS;?> data-target="#view" ng-click="selectRow(row);"><i class="icon-eye text-info"></i></a>	
												<a  class="btn" <?php print $this->MODAL_OPTIONS;?> data-target="#edit" ng-click="selectRow(row);" ng-show="row.is_manual>0"><i class="icon-compose text-info"></i></a>	
												<!-- <a  class="btn" ng-click="delRow(row);" ng-show="row.is_manual>0">
													<i class="icon-cross text-danger"></i></a> -->	
												<?php if($this->IS_DEV_LOGIN){ ?>
												<!-- <a  class="btn" ng-click="delRow(row);" title="Strict Remove"><i class="icon-cross text-warning"></i></a> -->	
															
												<?php } ?>										
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
						<div class="col-sm-3">
							<label class="text-muted">Debit Account</label>
							<select class="form-control select" ng-model="filter.debit_account" data-fouc>
							<option value="">Any Account</option>
							<?php foreach($accounts as $key => $value){?>
							<option value="<?php print $key;?>" /><?php print $value;?>
							<?php }?>
							</select>
						</div>	
						<div class="col-sm-3">
							<label class="text-muted">Credit Account</label>
							<select class="form-control select" ng-model="filter.credit_account" data-fouc>
							<option value="">Any Account</option>
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
				<h4 class="modal-title">Detail information of record</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<!-- Invoice template -->
				<div class="card">

					<div class="card-body">
						<div class="table-responsive">
								<h4 class="text-primary mb-2 mt-md-2 text-center">{{selectedRow.title}}</h4>
								<table class="table tasks-responsive table-lg">
									<thead>
										<tr>
											<th class="font-weight-bold">#</th>
											<th class="font-weight-bold">Description</th>
											<th class="font-weight-bold">Account</th>
											<th class="font-weight-bold">Debit</th>
											<th class="font-weight-bold">Credit</th>
											<th class="font-weight-bold">Date</th>
							            </tr>
									</thead>
									<tbody>

										<tr>
											<td>1</td>
											<td>{{selectedRow.debit_reference}}</td>
											<td>{{selectedRow.debit_account}}</td>
											<td><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]; ?> {{selectedRow.debit_amount}}</td>
											<td>0.00</td>
											<td>{{selectedRow.date}}</td>
							            </tr>
							            <tr>
											<td>2</td>
											<td>{{selectedRow.debit_reference}}</td>
											<td>{{selectedRow.credit_account}}</td>
											<td>0.00</td>
											<td><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]; ?> {{selectedRow.credit_amount}}</td>
											<td>{{selectedRow.date}}</td>
							            </tr>
									</tbody>
								</table>
								<br>
						</div>
					</div>

					<div class="card-footer">
						<span class="text-muted"><?php print 'Last Loaded: '.date('D d-F-Y h:i:s A'); ?></span>
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
				<h6 class="modal-title">Create New Entry</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you create new entries manually to reflect in monthly and anual reports</p>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<label class="text-muted">Select Debit Account</label>
							<select class="form-control select" ng-model="entry.account" data-fouc>
							<option value="">Select Debit Account</option>
							<?php foreach($accounts as $key => $value){?>
							<option value="<?php print $key;?>" /><?php print $value;?>
							<?php }?>
							</select>
						</div>		
						<div class="col-sm-2">
							<label class="text-muted">Credit Account</label>
							<select class="form-control select" ng-model="entry.credit_account" data-fouc>
							<option value="">Select Credit Account</option>
							<?php foreach($accounts as $key => $value){?>
							<option value="<?php print $key;?>" /><?php print $value;?>
							<?php }?>
							</select>
						</div>	
						<div class="col-sm-2">
							<label class="text-muted">Select Date</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-lg datepicker" placeholder="Date" ng-model="entry.date">
								<div class="form-control-feedback form-control-feedback-lg">
									<i class="icon-calendar"></i>
								</div>
							</div>
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
						<div class="col-sm-3">
							<label class="text-muted">Amount</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-lg" placeholder="Amount" ng-model="entry.amount">
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
				<h6 class="modal-title">Update Entry</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you update entries...</p>
				<div class="form-group">
					<div class="row">	
						<div class="col-sm-4">
							<label class="text-muted">Select Date</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-lg datepicker" placeholder="Expense Date" ng-model="selectedRow.date">
								<div class="form-control-feedback form-control-feedback-lg">
									<i class="icon-calendar"></i>
								</div>
							</div>
						</div>	
						<div class="col-sm-8">
							<label class="text-muted">Title</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-lg" placeholder="Title" ng-model="selectedRow.title">
								<div class="form-control-feedback form-control-feedback-lg">
									<i class="icon-vcard"></i>
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