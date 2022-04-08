<?php
$bg=array('bg-violet','bg-success','bg-info','bg-warning','bg-primary','bg-blue','bg-teal','bg-green','bg-slate','bg-indigo');
$teals=array(2,3,4,6,7);
$total_bg=count($bg)-1;
$total_teals=count($teals)-1;
/////////////////////////////////////////////////////////////////////////////////////////////
$classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'title ASC') );
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
					<span class="breadcrumb-item active">Salary Payroll</span>
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
							<a href="#" class="dropdown-item"><i class="icon-clipboard6"></i> Reports</a>
							<div class="dropdown-menu">
								<!-- <div class="dropdown-submenu dropdown-submenu-left">
									<a href="#" class="dropdown-item">Payments Reports</a>
									<div class="dropdown-menu">
										<a href="#" class="dropdown-item">Upaid Students</a>
										<a href="#" class="dropdown-item">Partialy Paid Students</a>
										<a href="#" class="dropdown-item">Paid Students</a>
									</div>
								</div> -->
								<a href="<?php print $this->CONT_ROOT.'printing/report/?rpt=rembalance';?>" class="dropdown-item">Remaining Balance Report</a>
								<!-- <a href="<?php print $this->CONT_ROOT.'printing/report/?rpt=stfunpaid';?>" class="dropdown-item">Pending Vouchers Report</a> -->
								<a href="<?php print $this->CONT_ROOT.'printing/report/?rpt=stfpaid';?>" class="dropdown-item">Staff Payment Report</a>
								<!-- <a href="<?php print $this->CONT_ROOT.'printing/';?>" class="dropdown-item">Daily Fee Collection</a> -->
								<!-- <a href="<?php print $this->CONT_ROOT.'printing/';?>" class="dropdown-item">Monthly Fee Collection</a> -->
							</div>
						</div>
						<a href="<?php print $this->CONT_ROOT.'printing/payslip/';?>" class="dropdown-item"><i class="icon-vcard"></i>Pay Slips</a>
						<!-- <a href="<?php print $this->CONT_ROOT.'printing/history/?usr=';?>" class="dropdown-item"><i class="icon-cash"></i> History</a> -->
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
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Finance</span> - Salary Payroll</h4>
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
							<i class="icon-coin-dollar mr-2"></i>
							Salary Payroll
						</a>
					</li>
					<li class="nav-item">
						<a href="#history" class="navbar-nav-link" data-toggle="tab" ng-click="loadHistory()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-history mr-2"></i>
							Payment History
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
					<li class="nav-item">
						<a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-gear"></i>
							<span class="ml-1"><strong>More</strong></span>
						</a>

						<?php if($this->LOGIN_USER->prm_finance>1){ ?>
						<div class="dropdown-menu dropdown-menu-right">
							<a <?php print $this->MODAL_OPTIONS;?> data-target="#create-voucher" class="dropdown-item"><i class="icon-image2"></i> Generate Salary Slips</a>
							<a <?php print $this->MODAL_OPTIONS;?> data-target="#list-sms" class="dropdown-item"><i class="icon-envelop"></i> SMS Notification</a>
							<?php if($this->LOGIN_USER->prm_finance>2){ ?>
							<div class="dropdown-divider"></div>
							<a ng-click="delVouchers()" class="dropdown-item"><i class="icon-cross"></i> Remove Salary Slips</a>
							<?php } ?>
						</div>
						<?php } ?>
					</li>
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
							<h4 class="card-title">Salary Payroll</h4>
							<span class="text-muted">Search here the staffs and pay salary to staff members.</span>
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
										<a class="btn btn-info btn-lg text-white" <?php print $this->MODAL_OPTIONS;?> data-target="#refine-search"><i class="icon-filter3 mr-2"></i> Advance Search</a>
										<a class="btn btn-danger text-white" ng-show="showFilter()" ng-click="clearFilter();loadRows();">
												<i class="icon-diff-removed"></i></a>
									</div>
								</div>
							</div>
							<div class="table-responsive">
								<p class="text-muted">Find below the salary slips of all staff. Please remember to create vouchers of every month in first week. Contact support team for more information.</p>
								<table class="table tasks-responsive table-lg">
									<thead>
										<tr>
											<th class="font-weight-bold">#</th>
											<th ng-click="sortBy('staff_name');loadRows();" class="mouse-pointer font-weight-bold" >
												<span class="m-1">Staff</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='staff_name'"></i> 
											</th>
											<th ng-click="sortBy('title');loadRows();" class="mouse-pointer font-weight-bold" >
												<span class="m-1">Title</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
											</th>
											<th ng-click="sortBy('amount');loadRows();" class="mouse-pointer font-weight-bold" >
												<span class="m-1">Payable Amount</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='amount'"></i> 
											</th>
											<th class="font-weight-bold">Paid On</th>
											<th ng-click="sortBy('status');loadRows();" class="mouse-pointer font-weight-bold" >
												<span class="m-1">Status</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='status'"></i> 
											</th>
											<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
							            </tr>
									</thead>
									<tbody>

										<tr ng-repeat="row in responseData.rows">
											<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
											<td>
												<div><a ng-href="<?php print $this->LIB_CONT_ROOT.'staff/profile/'?>{{row.staff_id}}" class="font-weight-semibold">
												<img ng-src="<?php print $this->UPLOADS_ROOT;?>images/staff/profile/{{row.image}}" class="rounded-circle m-1" width="32" height="32" alt="">
	        									{{row.staff_name}}</a></div>
											</td>
											<td>{{row.title}}</td>
											<td><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]; ?> {{row.amount}}</td>
											<td>{{row.date_paid}}</td>
											<td>
												<span class="badge bg-success" ng-show="row.status==='<?php print $this->stf_pay_voucher_m->STATUS_PAID;?>'">PAID ON {{row.date_paid}}</span>
												<span class="badge bg-info" ng-show="row.status==='<?php print $this->stf_pay_voucher_m->STATUS_PARTIAL_PAID;?>'">PARTIALLY PAID</span>
												<span class="badge bg-warning" ng-show="row.status==='<?php print $this->stf_pay_voucher_m->STATUS_UNPAID;?>'">NOT YET PAID </span>
												<span class="badge bg-danger" ng-show="row.status==='<?php print $this->stf_pay_voucher_m->STATUS_CANCELED;?>'">Canceled </span>
											</td>
											<td>
												<div class="list-icons float-right">
													<div class="btn-group list-icons-item dropdown">
								                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>

														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#view" ng-click="selectRow(row);loadVoucher(row);">
																<i class="icon-eye"></i> View Record 
															</a>
															<!-- <a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#edit" ng-click="selectRow(row)">
																<i class="icon-checkmark-circle2"></i> Pay Fee 
															</a> -->
															<a class="dropdown-item" ng-href="<?php print $this->CONT_ROOT.'printing/payslip/?vid=';?>{{row.mid}}">
																<i class="icon-printer"></i> Print Slip 
															</a>
															<a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#sms" ng-click="selectRow(row)">
																<i class="icon-envelop"></i> Send SMS 
															</a>
															<?php if($this->LOGIN_USER->prm_finance>2){ ?>
															<div class="dropdown-divider" ng-show="row.status==='<?php print $this->std_fee_voucher_m->STATUS_UNPAID;?>'"></div>
															<a class="dropdown-item" ng-click="delRow(row)" ng-show="row.status==='<?php print $this->std_fee_voucher_m->STATUS_UNPAID;?>'"><i class="icon-cross text-danger"></i> Cancel &amp; Remove Record</a>
															<?php } ?>
															
															<?php if($this->IS_DEV_LOGIN){ ?>
															<div class="dropdown-divider" ></div>
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
					
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Salary Payment History</h4>
							<span class="text-muted">System tracks and save all the important events regarding the salary payment module. Find below the history of this module.</span>
						</div>
						<div class="card-body">
							<div class="form-group">
								<div class="row">
									<!-- <div class="col-sm-3">
										<select class="form-control select" ng-model="filter.type" data-fouc>
										<option value="">All Types</option>
										</select>
									</div> -->
									<div class="col-sm-12">
										<div class="input-group mb-4">
											<div class="form-group-feedback form-group-feedback-left">
												<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadHistory()">
												<div class="form-control-feedback form-control-feedback-lg">
													<i class="icon-search4 text-muted"></i>
												</div>
											</div>

											<div class="input-group-append">
												<button ng-click="loadHistory()" class="btn btn-success btn-lg">
												<span class="font-weight-bold"> Search</span>
												<i ng-class="{'icon-spinner2':appConfig.btnClickedSearch, 'spinner':appConfig.btnClickedSearch, 'icon-circle-right2':!appConfig.btnClickedSearch}" class=" ml-2"></i>
												</button>
											</div>
										</div>
									</div>
									<!-- <div class="col-sm-2">
										<label class="text-muted"><input type="checkbox" ng-model="filter.purchaseLog" class="checkbox-inline" ng-change="loadHistory()" /> Only Purchase Log</label>
									</div> -->
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th class="font-weight-bold">Staff</th>
										<th class="font-weight-bold">Descripton</th>
										<th class="font-weight-bold">Amount</th>
										<th class="font-weight-bold">Date</th>
										<!-- <th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th> -->
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>
											<div><a ng-href="<?php print $this->LIB_CONT_ROOT.'staff/profile/'?>{{row.staff_id}}" class="font-weight-semibold">
											<img ng-src="<?php print $this->UPLOADS_ROOT;?>images/staff/profile/{{row.image}}" class="rounded-circle m-1" width="32" height="32" alt="">
        									{{row.staff_name}}</a></div>
										</td>
										<td>{{row.title}}</td>
					  					<td><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]?>{{row.amount}}</td>  
					  					<td>{{row.date}}</td> 
										
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadHistory()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadHistory()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br>
							</div>
						</div>
					</div>
					<!-- /list table -->
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
							<label class="text-muted">Status</label>
							<select class="form-control select" ng-model="filter.status" data-fouc>
								<option value="">Any Status</option>
								<option value="<?php print $this->stf_pay_voucher_m->STATUS_PAID ?>"> Paid</option>
								<option value="<?php print $this->stf_pay_voucher_m->STATUS_UNPAID ?>"> Not Yet Paid</option>
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


<!-- view voucher modal -->
<div id="view" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h4 class="modal-title">Voucher Information <strong>({{selectedRow.title}})</strong></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<!-- Invoice template -->
				<div class="card">

					<div class="card-body">
						<div class="row">
							<div class="col-sm-6">
								<div class="mb-4">
									<img ng-src="<?php print $this->UPLOADS_ROOT;?>images/staff/profile/{{selectedRow.image}}" class="mb-3 mt-2" style="width: 60px;" alt="">
		 							<ul class="list list-unstyled mb-0">
										<li><strong>{{selectedRow.staff_name}}</strong></li>
										<li>ID: {{selectedRow.stf_id}}</li>
										<li>Status: 
											<span class="text-success" ng-show="voucher.status==='<?php print $this->stf_pay_voucher_m->STATUS_PAID;?>'"> Paid On {{voucher.date_paid}}</span>
											<span class="text-warning" ng-show="voucher.status==='<?php print $this->stf_pay_voucher_m->STATUS_UNPAID;?>'"> Not Yet Paid</span>
										</li>
									</ul>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="mb-4">
									<div class="text-sm-right">
										<h4 class="text-primary mb-2 mt-md-2">Voucher# {{selectedRow.voucher_id}}</h4>
										<ul class="list list-unstyled mb-0">
											<li>Date Create: <span class="font-weight-semibold">{{selectedRow.date}}</span></li>
											<li>Amount: <span class="font-weight-semibold"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]; ?>{{voucher.amount}}</span></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-7">
							<div class="table-responsive">
								<h4 class="text-muted text-center">Payments due to staff</h4>
							    <table class="table table-sm">
							        <thead>
							            <tr>
							                <th>#</th>
							                <th>Title</th>
							                <th>Amount</th>
							                <th>Date</th>
							                <th ng-show="voucher.status==='<?php print $this->stf_pay_voucher_m->STATUS_UNPAID;?>'">Action</th>
							            </tr>
							        </thead>
							        <tbody>
							            <tr ng-repeat="row in voucher.entries" class="text-muted" ng-show="row.operation==='<?php print $this->std_fee_entry_m->OPT_PLUS;?>'">
											<td>{{$index+1}}</td>
							                <td>
							                	<span>{{row.remarks}}</span>
						                	</td>
							                <td class="text-info"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{row.amount}}</td>
							                <td>{{row.date}}</td>
											<?php if($this->LOGIN_USER->prm_finance>2){ ?>
							                <td ng-show="voucher.status==='<?php print $this->stf_pay_voucher_m->STATUS_UNPAID;?>' && row.type!=='<?php print $this->std_fee_entry_m->TYPE_LATE_FEE_FINE;?>'" ng-click="delEntry(row)"><a class="mouse-pointer"><i class="icon-cross text-danger"></i></a></td>
							            	<?php } ?>
							            </tr>
							        </tbody>
							    </table>
							</div>
						</div>
						<div class="col-sm-5" ng-show="(voucher.entries|filter:{$:'minus'}).length>0">
							<div class="table-responsive">
								<h4 class="text-muted text-center">History</h4>
							    <table class="table table-sm">
							        <thead>
							            <tr>
							                <th>Amount Paid</th>
							                <th>Date</th>
							                <th ng-show="voucher.status==='<?php print $this->stf_pay_voucher_m->STATUS_UNPAID;?>'">Action</th>
							            </tr>
							        </thead>
							        <tbody>
							            <tr ng-repeat="row in voucher.entries" class="text-muted" ng-show="row.operation==='<?php print $this->std_fee_entry_m->OPT_MINUS;?>'">
							                <td class="text-success">{{row.remarks}} (<?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{row.amount}})</td>
							                <td class="text-success">{{row.date}}</td>
											<?php if($this->LOGIN_USER->prm_finance>2){ ?>
							                <td ng-show="voucher.status==='<?php print $this->stf_pay_voucher_m->STATUS_UNPAID;?>'" ng-click="delEntry(row)"><a class="mouse-pointer"><i class="icon-cross text-danger"></i></a></td>
							            	<?php } ?>
							            </tr>
							        </tbody>
							    </table>
							</div>
						</div>
					</div>

					<div class="card-body">
						<br>
						<div class="d-md-flex flex-md-wrap">
							<div class="pt-2 mb-3">
								<!-- <h6 class="mb-3">Loaded By: <?php print $this->LOGIN_USER->name ?></h6>
								<ul class="list-unstyled text-muted">
									<li><?php print date('D d-M-Y'); ?></li>
								</ul> -->
							</div>

							<div class="pt-2 mb-3 wmin-md-400 ml-auto">
								<!-- <h6 class="mb-3">Total due</h6> -->
								<div class="table-responsive">
									<table class="table">
										<tbody>
											<tr>
												<th><strong>Payable Amount:</strong></th>
												<td class="text-right text-info"><strong><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{voucher.amount}}</strong></td>
											</tr>
										</tbody>
									</table>
								</div>

							</div>
						</div>
					</div>

					<div class="card-footer">
						<div class="form-group" ng-show="entry.add===true">
							<div class="row">
								<div class="col-sm-4">
									<label class="text-muted">Record Title</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Record Title" ng-model="entry.title">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<label class="text-muted">Amount <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Amount" ng-model="entry.amount">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-coin-dollar"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-2" ng-show="entry.type==='pay'">
									<label class="text-muted">Payment Method</label>
									<select class="form-control select" ng-model="entry.method" data-fouc>
										<option value="cash">Cash</option>
										<option value="bank">Bank</option>
									</select>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Click to save record</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
									<button class="btn btn-info btn-lg" ng-click="saveRow()" ng-show="entry.type==='plus'">
										<span class="font-weight-bold"> Add Arrear</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
									</button>
									<button class="btn btn-danger btn-lg" ng-click="saveRow()" ng-show="entry.type==='minus'">
										<span class="font-weight-bold">Get Fine</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
									</button>
									<button class="btn btn-success btn-lg" ng-click="payAmount()" ng-show="entry.type==='pay'">
										<span class="font-weight-bold"> Pay Amount</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
									</button>
									</div>
								</div>
							</div>
							<hr>
						</div>

						<span class="text-muted">You may find the amount payable on left hand side. Right side show the payment history and fine or subtraction from staff. You may add more record to this voucer. Please contact support team for more information.</span>
					</div>
				</div>
				<!-- /invoice template -->


			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-lg" ng-click="entry.type='advancepay';entry.add=false;entry.method='bank';payPayment()" ng-show="voucher.status!=='<?php print $this->stf_pay_voucher_m->STATUS_PAID; ?>'">
					<span class="font-weight-bold"> Pay Via Bank Transfer</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
				<button class="btn btn-success btn-lg" ng-click="entry.type='advancepay';entry.add=false;entry.method='cash';payPayment()" ng-show="voucher.status!=='<?php print $this->stf_pay_voucher_m->STATUS_PAID; ?>'">
					<span class="font-weight-bold"> Pay Via Cash</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
				<button class="btn btn-success btn-lg" ng-click="entry.add=true;entry.type='pay';entry.title='Salary Paid'; entry.amount=selectedRow.amount" ng-show="voucher.status!=='<?php print $this->stf_pay_voucher_m->STATUS_PAID; ?>'">
					<span class="font-weight-bold"> Pay Partially </span> <i class="icon-checkmark-circle2" class=" ml-2"></i>
				</button>

				<?php if($this->LOGIN_USER->prm_finance>1){ ?>
				<button class="btn btn-info btn-lg" ng-click="entry.add=true;entry.type='plus';entry.title=''"  ng-show="voucher.status!=='<?php print $this->stf_pay_voucher_m->STATUS_PAID; ?>'">
					<span class="font-weight-bold"> Add Arrear</span> <i class="icon-plus-circle2" class=" ml-2"></i>
				</button>
				<button class="btn btn-danger btn-lg" ng-click="entry.add=true;entry.type='minus';entry.title='Fine'"  ng-show="voucher.status!=='<?php print $this->stf_pay_voucher_m->STATUS_PAID; ?>'">
					<span class="font-weight-bold"> Get Fine </span> <i class="icon-minus-circle2" class=" ml-2"></i>
				</button>
				<?php } ?>
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /view voucher modal -->

<!-- create voucher modal -->
<div id="create-voucher" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Create salary slips for staff...</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">
					<code>Auto Pay</code>If you want to pay salary automatically to staff if you had already paid the salary then choose the auto pay to staff...<br>
				</p>
				
				<hr>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-3">
							<label class="text-muted">Auto Pay</label>
							<select class="form-control select" ng-model="newSlip.auto_pay" data-fouc>
								<option value="">Disable Auto Pay</option>
								<option value="pay">Enable Auto Pay</option>
							</select>
						</div>					
						<div class="col-sm-3">
							<label class="text-muted">Month<span class="text-danger"> * </span></label>
							<select class="form-control select" ng-model="newSlip.month" data-fouc>
							    <option value="" />Select Month
								<?php 
								$month=$this->stf_pay_voucher_m->month;
								$year=$this->stf_pay_voucher_m->year;
									$next_month=$month+1;
									$prev_month=$month-1;
									if($next_month>12){$next_month=1;}
									if($prev_month<1){$prev_month=12;}
								?>            
							    <option value="<?php print $prev_month;?>" /><?php print month_string($prev_month);?>
							    <option value="<?php print $month;?>"/><?php print month_string($month);?>
							    <option value="<?php print $next_month;?>" /><?php print month_string($next_month);?>
							</select>
						</div>				
						<div class="col-sm-3">
							<label class="text-muted">Year<span class="text-danger"> * </span></label>
							<select class="form-control select" ng-model="newSlip.year" data-fouc>
							    <option value="" />Select Year
								<?php ?>     
								<?php if($month==1) {?><option value="<?php print $year-1;?>" /><?php print $year-1;?><?php } ?>
							    <option value="<?php print $year;?>"/><?php print $year;?>
								<?php if($month==12) {?><option value="<?php print $year+1;?>" /><?php print $year+1;?><?php } ?>
							</select>
						</div>
						
					</div>
				</div>



			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="createVoucher()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Generate Slips</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /create voucher modal -->

<!-- send list sms -->
<div id="list-sms" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Send SMS Notification regarding salary</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you send sms notification to staff regarding salary updates. Write here the message and choose target audience. System will automatically send sms to target audience. <?php print $this->SMS_HOST_NOTE; ?></p>
				<legend class="font-weight-semibold text-uppercase font-size-sm">
					<i class="icon-filter3 mr-2"></i>Choose dynamic data...</legend>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<label class="text-muted">WHOME TO SEND SMS?</label>
							<select class="form-control select" ng-model="sms.target" data-fouc>
							<option value="">Choose Receiver</option>
							<option value="<?php print $this->sms_hook_m->TARGET_GAURDIAN;?>">Guardian's</option>
							<option value="<?php print $this->sms_hook_m->TARGET_STAFF;?>">Staff's</option>
							</select>
						</div>	
						<div class="col-sm-2">
							<label class="text-muted">Salary Status</label>
							<select class="form-control select" ng-model="filter.status" data-fouc>
								<option value="">Any Status</option>
								<option value="<?php print $this->stf_pay_voucher_m->STATUS_PAID ?>">Received Salary</option>
								<option value="<?php print $this->stf_pay_voucher_m->STATUS_UNPAID ?>">Not Yet Received Salary</option>
							</select>
						</div>					
						<div class="col-sm-2">
							<label class="text-muted">Fee Month</label>
							<select class="form-control select" ng-model="filter.month" data-fouc>
								<option value="">Current Month</option>
								<?php 
								for($i=1; $i<=12; $i++){?>            
								    <option value="<?php print $i;?>" /><?php print month_string($i);?>
							    <?php }?>
							</select>
						</div>				
						<div class="col-sm-2">
							<label class="text-muted">Fee Year</label>
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
				<hr>			
				<div class="form-group">
					<div class="row">					
						<div class="col-sm-12" ng-show="sms.target.length>0">
							<p>
								<button type="button" class="btn btn-outline-success btn-sm" ng-click="addkey('NAME')"><i class="icon-user mr-2"></i> Staff Name</button>
								<button type="button" class="btn btn-outline-info btn-sm" ng-click="addkey('GUARDIAN')"><i class="icon-people mr-2"></i> Guardian Name</button>
								<button type="button" class="btn btn-outline-warning btn-sm" ng-click="addkey('AMOUNT')"><i class="icon-coin-dollar mr-2"></i> Voucher Amount</button>
								<button type="button" class="btn btn-outline-primary btn-sm" ng-click="addkey('VOUCHER')"><i class="icon-compose mr-2"></i> Voucher ID</button>
							</p>
						</div>
					</div>
				</div>				
				<legend class="font-weight-semibold text-uppercase font-size-sm">
					<i class="icon-envelop mr-2"></i>Write Message...</legend>
				<div class="form-group">
					<label class="text-muted">Dynamic keys will be changed into actual values before sending sms.</label>
					<div class="row">
						<div class="col-sm-10">
							<textarea class="form-control" ng-model="sms.message" placeholder="Write your message..." rows="5"></textarea>
						</div>
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="sendListSms()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Send SMS</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /send list sms-->

<!-- send list sms -->
<div id="sms" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Send SMS Notification to {{selectedRow.staff_name}}</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you send sms notification to staff regarding salary updates. Write here the message and choose target audience. System will automatically send sms to target audience. <?php print $this->SMS_HOST_NOTE; ?></p>
				<legend class="font-weight-semibold text-uppercase font-size-sm">
					<i class="icon-filter3 mr-2"></i>Choose dynamic data...</legend>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<label class="text-muted">WHOME TO SEND SMS?</label>
							<select class="form-control select" ng-model="sms.target" data-fouc>
							<option value="">Choose Receiver</option>
							<option value="<?php print $this->sms_hook_m->TARGET_GAURDIAN;?>">Guardian's</option>
							<option value="<?php print $this->sms_hook_m->TARGET_STAFF;?>">Staff's</option>
							</select>
						</div>	
						
					</div>
				</div>
				<hr>			
				<div class="form-group">
					<div class="row">					
						<div class="col-sm-12" ng-show="sms.target.length>0">
							<p>
								<button type="button" class="btn btn-outline-success btn-sm" ng-click="addkey('NAME')"><i class="icon-user mr-2"></i> Staff Name</button>
								<button type="button" class="btn btn-outline-info btn-sm" ng-click="addkey('GUARDIAN')"><i class="icon-people mr-2"></i> Guardian Name</button>
								<button type="button" class="btn btn-outline-warning btn-sm" ng-click="addkey('AMOUNT')"><i class="icon-coin-dollar mr-2"></i> Voucher Amount</button>
								<button type="button" class="btn btn-outline-primary btn-sm" ng-click="addkey('VOUCHER')"><i class="icon-compose mr-2"></i> Voucher ID</button>
							</p>
						</div>
					</div>
				</div>				
				<legend class="font-weight-semibold text-uppercase font-size-sm">
					<i class="icon-envelop mr-2"></i>Write Message...</legend>
				<div class="form-group">
					<label class="text-muted">Dynamic keys will be changed into actual values before sending sms.</label>
					<div class="row">
						<div class="col-sm-10">
							<textarea class="form-control" ng-model="sms.message" placeholder="Write your message..." rows="5"></textarea>
						</div>
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="sendSingleSms()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Send SMS</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /send list sms-->


</div>
<!-- /main content -->
<!-- ********************************************************************** -->
<!-- ///////////////////////////////SCRIPTS//////////////////////////////// -->
<!-- ********************************************************************** -->
<!-- <script type="text/javascript">
	
$(function(){
   
});
</script> -->