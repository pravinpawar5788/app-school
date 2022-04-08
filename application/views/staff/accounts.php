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
					<a class="breadcrumb-item">Accounts</a>
					<span class="breadcrumb-item active">Payroll</span>
				</div>

				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="breadcrumb justify-content-center">

				</div>
			</div>
		</div>
		<!-- /breadcrumb line -->

	
		<!-- Page header content -->
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Accounts</span> - Payroll</h4>
				<a class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="d-flex justify-content-center">
				</div>
			</div>
		</div>
		<!-- /page header content -->


		<!-- Page navigation -->
		<div class="navbar navbar-expand-lg navbar-light bg-light">
			<div class="text-center d-lg-none w-100">
				<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-second">
					<i class="icon-menu7 mr-2"></i>
					Page Navigation
				</button>
			</div>

			<div class="navbar-collapse collapse" id="navbar-second">
				<ul class="nav navbar-nav">
					<li class="nav-item">
						<a href="#index" class="navbar-nav-link active" data-toggle="tab" ng-click="loadRows()">
							<i class="icon-coin-dollar mr-2"></i>
							Payroll
						</a>
					</li>
					<li class="nav-item">
						<a href="#history" class="navbar-nav-link" data-toggle="tab" ng-click="loadHistory()">
							<i class="icon-history mr-2"></i>
							Payment History
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
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Payroll</h4>
							<span class="text-muted">You may search specific pay slip...</span>
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
									</div>
								</div>
							</div>
							<div class="table-responsive">
								<p class="text-muted">Find below your payroll and status of vouchers. Contact support team for more information.</p>
								<table class="table tasks-responsive table-lg">
									<thead>
										<tr>
											<th class="font-weight-bold">#</th>
											<th ng-click="sortBy('title');loadRows();" class="mouse-pointer font-weight-bold" >
												<span class="m-1">Title</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
											</th>
											<th ng-click="sortBy('amount');loadRows();" class="mouse-pointer font-weight-bold" >
												<span class="m-1">Amount</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='amount'"></i> 
											</th>
											<th class="font-weight-bold">Payment Date</th>
											<th ng-click="sortBy('status');loadRows();" class="mouse-pointer font-weight-bold" >
												<span class="m-1">Status</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='status'"></i> 
											</th>
											<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
							            </tr>
									</thead>
									<tbody>

										<tr ng-repeat="row in responseData.rows">
											<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
											<td>{{row.title}}</td>
											<td><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]; ?> {{row.amount}}</td>
											<td>{{row.date_paid}}</td>
											<td>
												<span class="badge bg-success" ng-show="row.status==='<?php print $this->stf_pay_voucher_m->STATUS_PAID;?>'">PAID ON {{row.date_paid}}</span>
												<span class="badge bg-warning" ng-show="row.status==='<?php print $this->stf_pay_voucher_m->STATUS_UNPAID;?>'">NOT YET PAID </span>
												<span class="badge bg-danger" ng-show="row.status==='<?php print $this->stf_pay_voucher_m->STATUS_CANCELED;?>'">Canceled </span>
											</td>
											<td class="text-center">
												<div class="list-icons">
													<div class="list-icons-item dropdown">
														<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9 mr-5"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#view" ng-click="selectRow(row);loadVoucher(row);">
																<i class="icon-eye"></i> View Record 
															</a>
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
							<h4 class="card-title">Payment History</h4>
							<span class="text-muted">Find below the payment history.</span>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th class="font-weight-bold">Descripton</th>
										<th class="font-weight-bold">Amount</th>
										<th class="font-weight-bold">Date</th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
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

				<p class="text-muted">CSMS let you search specific data by applying various filters on search. Please choose the filters you want to apply in your next search. After filter selection click the search button...</p>

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
								<option value="">Any Year</option>
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
											<li>Amount: <span class="font-weight-semibold"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]; ?>{{voucher.plus_amount}}</span></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-7">
							<div class="table-responsive">
								<h4 class="text-muted text-center">Salary Receiveable</h4>
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
							            <tr ng-repeat="row in voucher.entries" class="text-muted" ng-show="row.operation==='<?php print $this->stf_pay_entry_m->OPT_PLUS;?>'">
											<td>{{$index+1}}</td>
							                <td>
							                	<span>{{row.remarks}}</span>
						                	</td>
							                <td class="text-info"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{row.amount}}</td>
							                <td>{{row.date}}</td>
							            </tr>
							        </tbody>
							    </table>
							</div>
						</div>
						<div class="col-sm-5" ng-show="(voucher.entries|filter:{$:'minus'}).length>0">
							<div class="table-responsive">
								<h4 class="text-muted text-center">Payment History</h4>
							    <table class="table table-sm">
							        <thead>
							            <tr>
							                <th>Amount Paid</th>
							                <th>Date</th>
							                <th ng-show="voucher.status==='<?php print $this->stf_pay_voucher_m->STATUS_UNPAID;?>'">Action</th>
							            </tr>
							        </thead>
							        <tbody>
							            <tr ng-repeat="row in voucher.entries" class="text-muted" ng-show="row.operation==='<?php print $this->stf_pay_entry_m->OPT_MINUS;?>'">
							                <td class="text-success">{{row.remarks}} (<?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{row.amount}})</td>
							                <td class="text-success">{{row.date}}</td>
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
							</div>

							<div class="pt-2 mb-3 wmin-md-400 ml-auto">
								<div class="table-responsive">
									<table class="table">
										<tbody>
											<tr>
												<th>Total:</th>
												<td class="text-right"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{voucher.plus_amount}}</td>
											</tr>
										</tbody>
									</table>
								</div>

							</div>
						</div>
					</div>

					<div class="card-footer">
						<span class="text-muted">You may find the amount receiveable on left side. Right side show the payment history and fine or subtraction from your salary. Please contact accounts for more information.</span>
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
<!-- /view voucher modal -->


</div>
<!-- /main content -->