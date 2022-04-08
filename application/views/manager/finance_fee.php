<?php
$bg=array('bg-violet','bg-success','bg-info','bg-warning','bg-primary','bg-blue','bg-teal','bg-green','bg-slate','bg-indigo');
$teals=array(2,3,4,6,7);
$total_bg=count($bg)-1;
$total_teals=count($teals)-1;
/////////////////////////////////////////////////////////////////////////////////////////////
$classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'title ASC') );
$fee_types=$this->std_fee_entry_m->get_fee_types();
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
					<span class="breadcrumb-item active">Fee Collection</span>
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
								<div class="dropdown-submenu dropdown-submenu-left">
									<a href="#" class="dropdown-item">Fee Reports</a>
									<div class="dropdown-menu">
										<a href="<?php print $this->CONT_ROOT.'printing/report/?rpt=detfeecollection&layout=landscape&p_analytics';?>" class="dropdown-item">Detailed Fee Collection Report</a>
										<a href="<?php print $this->CONT_ROOT.'printing/report/?rpt=dailyfeecollection';?>" class="dropdown-item">Daily Fee Collection Report</a>
										<a href="<?php print $this->CONT_ROOT.'printing/report/?rpt=feecollection';?>" class="dropdown-item">Fee Collection Report</a>
										<a href="<?php print $this->CONT_ROOT.'printing/report/?rpt=feedefaulter';?>" class="dropdown-item">Fee Defaulter's Report</a>
										<a href="<?php print $this->CONT_ROOT.'printing/report/?rpt=stdpaid';?>" class="dropdown-item">Fee Received Report</a>
									</div>
								</div>
								<div class="dropdown-submenu dropdown-submenu-left">
									<a href="#" class="dropdown-item">Advance Reports</a>
									<div class="dropdown-menu">
										<a href="<?php print $this->CONT_ROOT.'printing/report/?rpt=dailyadvancecollection';?>" class="dropdown-item">Daily Advance Collection Report</a>
										<a href="<?php print $this->CONT_ROOT.'printing/report/?rpt=advancecollection';?>" class="dropdown-item">Advance Fee Collection Report</a>
									</div>
								</div>
								<a href="<?php print $this->CONT_ROOT.'printing/report/?rpt=stdunpaid';?>" class="dropdown-item">Pending Vouchers Report</a>
								<a href="<?php print $this->CONT_ROOT.'printing/report/?rpt=stdconcession';?>" class="dropdown-item">Concession Report</a>
							</div>
						</div>
						<div class="dropdown-submenu dropdown-submenu-left">
							<a href="#" class="dropdown-item"><i class="icon-clipboard3"></i> Forms</a>
							<div class="dropdown-menu">
								<a href="<?php print $this->CONT_ROOT.'printing/form/?frm=feecollection';?>" class="dropdown-item">Fee Collection Form</a>
								<a href="<?php print $this->CONT_ROOT.'printing/form/?frm=blankfeeslip';?>" class="dropdown-item">Empty Fee Voucher</a>
							</div>
						</div>
						<a href="<?php print $this->CONT_ROOT.'printing/feeslip/';?>" class="dropdown-item"><i class="icon-vcard"></i> Fee Slips</a>
						<!-- <a href="<?php print $this->CONT_ROOT.'printing/unifeeslip/';?>" class="dropdown-item"><i class="icon-user"></i>Omni Fee Slips</a> -->
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
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Finance</span> - Fee Collection</h4>
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
						<a href="#index" class="navbar-nav-link active" data-toggle="tab"  ng-click="clearRows()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-coin-dollar mr-2" style="color:<?php print $clr;?>;"></i>
							Fee Collection
						</a>
					</li>
					<li class="nav-item">
						<a href="#vouchers" class="navbar-nav-link" data-toggle="tab" ng-click="loadRows()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-cash mr-2" style="color:<?php print $clr;?>;"></i>
							Fee Vouchers
						</a>
					</li>
					<li class="nav-item">
						<a href="#fee_history" class="navbar-nav-link" data-toggle="tab" ng-click="loadHistory()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-history mr-2" style="color:<?php print $clr;?>;"></i>
							Fee History
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
							<a <?php print $this->MODAL_OPTIONS;?> data-target="#create-voucher" class="dropdown-item"><i class="icon-image2"></i> Generate Fee Slips</a>
							<a <?php print $this->MODAL_OPTIONS;?> data-target="#list-sms" class="dropdown-item"><i class="icon-envelop"></i> SMS Notification</a>
							<?php if($this->LOGIN_USER->prm_finance>5){ ?>

							<?php //if($this->LOGIN_USER->prm_finance>2){ ?>
							<div class="dropdown-divider"></div>
							<a ng-click="delVouchers()" class="dropdown-item"><i class="icon-cross"></i> Remove Fee Slips</a>
							<?php //} ?>

							<a ng-click="mergeVouchers()" class="dropdown-item text-warning"><i class="icon-git-merge"></i> Merge Vouchers</a>

							<a <?php print $this->MODAL_OPTIONS;?> data-target="#remove-voucher" class="dropdown-item text-danger"><i class="icon-cross"></i> Remove Other Month Slips</a>
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
							<h4 class="card-title">Instant Fee Collection</h4>
							<span class="text-muted">Search here the students and receive fee from students.</span>
						</div>
						<div class="card-body">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-3">
										<select class="form-control select" ng-model="entry.filter" data-fouc>
											<option value="">Computer or Family Number</option>
											<option value="computer_number">Via Computer Number</option>
											<option value="family_number">Via Family Number</option>
											<option value="admission_no">Via Admission Number</option>

										</select>
									</div>
									<div class="col-sm-5">
										<div class="input-group mb-4">
											<div class="form-group-feedback form-group-feedback-left">
												<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Enter Computer Number or Family Number" ng-keyup="loadFeeVuchers()" tabindex="2">
												<div class="form-control-feedback form-control-feedback-lg">
													<i class="icon-search4 text-muted"></i>
												</div>
											</div>

											<div class="input-group-append">
												<button ng-click="loadFeeVuchers()" class="btn btn-success btn-lg">
												<span class="font-weight-bold"> Search</span>
												<i ng-class="{'icon-spinner2':appConfig.btnClickedSearch, 'spinner':appConfig.btnClickedSearch, 'icon-circle-right2':!appConfig.btnClickedSearch}" class=" ml-2"></i>
												</button>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<select class="form-control select" ng-model="entry.method" data-fouc>
											<option value="cash">Received Cash</option>
											<option value="bank">Bank Deposit</option>
										</select>
									</div>
									<div class="col-sm-2">
										<a class="btn btn-danger text-white" ng-show="showFilter()" ng-click="clearFilter();loadRows();">
												<i class="icon-diff-removed"></i></a>
									</div>
								</div>
							</div>
							<div class="table-responsive">
								<p class="text-muted">Below are remaining fee of student. If you want to edit the voucher record. Contact support team for more information.</p>
								<table class="table tasks-responsive table-lg">
									<thead>
										<tr>
											<th class="font-weight-bold">#</th>
											<th class="font-weight-bold">Student</th>
											<th class="font-weight-bold">Class</th>
											<th class="font-weight-bold">Voucher ID</th>
											<th class="font-weight-bold">Rem/Amount</th>
											<th class="font-weight-bold">Due Date</th>
											<th class="font-weight-bold">Status</th>
											<th class="font-weight-bold">Action</th>
											<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
							            </tr>
									</thead>
									<tbody>
										<tr ng-show="responseData.rows.length<1" >
											<td colspan="8">
												<span class="text-center text-danger">No pending fee record found</span>
											</td>
										</tr>
										<tr ng-repeat="row in responseData.rows">
											
											<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
											<td>
												<div><a ng-href="<?php print $this->LIB_CONT_ROOT.'student/profile/'?>{{row.student_id}}" class="font-weight-semibold">
												<img ng-src="<?php print $this->UPLOADS_ROOT;?>images/student/profile/{{row.image}}" class="rounded-circle m-1" width="32" height="32" alt="">
	        									{{row.student_name}}</a></div>
											</td>
											<td>{{row.class}}</td>
											<td>{{row.voucher_id}}</td>
											<td><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]; ?> {{row.amount}}</td>
											<td>{{row.due_date}}</td>
											<td>
												<span class="badge bg-success" ng-show="row.status==='<?php print $this->std_fee_voucher_m->STATUS_PAID;?>'">Received</span>
												<span class="badge bg-info" ng-show="row.status==='<?php print $this->std_fee_voucher_m->STATUS_PARTIAL_PAID;?>'">Received <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{row.amount_paid}} On {{row.date_paid}}</span>
												<span class="badge bg-warning" ng-show="row.status==='<?php print $this->std_fee_voucher_m->STATUS_UNPAID;?>'">Not Received </span>
												<span class="badge bg-danger" ng-show="row.status==='<?php print $this->std_fee_voucher_m->STATUS_CANCELED;?>'">Canceled </span>
											</td>
											<td>
												<button ng-hide="row.status==='<?php print $this->std_fee_voucher_m->STATUS_PAID;?>'" class="btn btn-success btn-sm" ng-click="receiveFee(row)" tabindex="3">
												<span class="font-weight-bold"> Receive Fee</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
												</button>
											</td>
											<td>
												<div class="list-icons float-right">
													<div class="btn-group list-icons-item dropdown">
								                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
								
														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#view" ng-click="selectRow(row);loadVoucher(row);">
																<i class="icon-eye"></i> View Fee 
															</a>
															<!-- <a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#edit" ng-click="selectRow(row)">
																<i class="icon-checkmark-circle2"></i> Pay Fee 
															</a> -->
															<a class="dropdown-item" ng-href="<?php print $this->CONT_ROOT.'printing/feeslip/?vid=';?>{{row.mid}}" target="_blank">
																<i class="icon-printer"></i> Print Slip 
															</a>
															<a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#sms" ng-click="selectRow(row)">
																<i class="icon-envelop"></i> Send SMS 
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
			    <div class="tab-pane fade <?php print $tab=='vouchers' ? 'active show': '';?>" id="vouchers">
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Fee Vouchers</h4>
							<span class="text-muted">Search here the students and receive fee from students.</span>
						</div>
						<div class="card-body">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-3">
										<select class="form-control select" ng-model="entry.filter" data-fouc>
											<option value="">Via General Data</option>
											<option value="computer_number">Via Computer Number</option>
											<option value="family_number">Via Family Number</option>
											<option value="admission_no">Via Admission Number</option>

										</select>
									</div>
									<div class="col-sm-5">
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
												<i class="icon-filter3 mr-1"></i> Advance Search</a>
										<a class="btn btn-danger text-white" ng-show="showFilter()" ng-click="clearFilter();loadRows();">
												<i class="icon-diff-removed"></i></a>
										<input type="checkbox" class="form-checkbox" ng-model="bulkpay"/>Bulk Pay
									</div>
								</div>
							</div>
							<div class="table-responsive">
								<p class="text-muted">Find below the fee vouchers of all students. Please remember to create vouchers of every month in first week. Contact support team for more information.</p>
								<table class="table tasks-responsive table-lg">
									<thead>
										<tr>
											<th class="font-weight-bold">#</th>
											<th ng-click="sortBy('student_name');loadRows();" class="mouse-pointer font-weight-bold" >
												<span class="m-1">Student</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='student_name'"></i> 
											</th>
											<th ng-click="sortBy('class_id');loadRows();" class="mouse-pointer font-weight-bold" >
												<span class="m-1">Class</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='class_id'"></i> 
											</th>
											<th ng-click="sortBy('title');loadRows();" class="mouse-pointer font-weight-bold" >
												<span class="m-1">Narration</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
											</th>
											<th ng-click="sortBy('amount');loadRows();" class="mouse-pointer font-weight-bold" >
												<span class="m-1">Rec.Amount</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='amount'"></i> 
											</th>
											<th class="font-weight-bold">Due Date</th>
											<th ng-click="sortBy('status');loadRows();" class="mouse-pointer font-weight-bold" >
												<span class="m-1">Status</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='status'"></i> 
											</th>
											<th ng-show='bulkpay'>Bulk Pay</th>
											<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
							            </tr>
									</thead>
									<tbody>

										<tr ng-repeat="row in responseData.rows">
											<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
											<td>
												<div><a ng-href="<?php print $this->LIB_CONT_ROOT.'student/profile/'?>{{row.student_id}}" class="font-weight-semibold">
												<img ng-src="<?php print $this->UPLOADS_ROOT;?>images/student/profile/{{row.image}}" class="rounded-circle m-1" width="32" height="32" alt="">
	        									{{row.student_name}}</a></div>
											</td>
											<td>{{row.class}}</td>
											<td>{{row.title}}</td>
											<td><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]; ?> {{row.amount}}</td>
											<td>{{row.due_date}}</td>
											<td>
												<span class="badge bg-success" ng-show="row.status==='<?php print $this->std_fee_voucher_m->STATUS_PAID;?>'">Received On {{row.date_paid}}</span>
												<span class="badge bg-info" ng-show="row.status==='<?php print $this->std_fee_voucher_m->STATUS_PARTIAL_PAID;?>'">Partially Received</span>
												<span class="badge bg-warning" ng-show="row.status==='<?php print $this->std_fee_voucher_m->STATUS_UNPAID;?>'">Not Received </span>
												<span class="badge bg-danger" ng-show="row.status==='<?php print $this->std_fee_voucher_m->STATUS_CANCELED;?>'">Canceled </span>
											</td>
											<td ng-show='bulkpay'>
												<button ng-hide="row.status==='<?php print $this->std_fee_voucher_m->STATUS_PAID;?>'"  class="btn btn-success btn-sm ml-1" ng-click="receiveFee(row)" >
												<span class="font-weight-bold"> Receive Full</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
												</button>
											</td>
											<td>
												<div class="list-icons float-right">
													<div class="btn-group list-icons-item dropdown">
								                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
								
														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#view" ng-click="selectRow(row);loadVoucher(row);">
																<i class="icon-eye"></i> View Fee 
															</a>
															<!-- <a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#edit" ng-click="selectRow(row)">
																<i class="icon-checkmark-circle2"></i> Pay Fee 
															</a> -->
															<a class="dropdown-item" ng-href="<?php print $this->CONT_ROOT.'printing/feeslip/?vid=';?>{{row.mid}}">
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
			    <div class="tab-pane fade <?php print $tab=='fee_history' ? 'active show': '';?>" id="fee_history">
					
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Fee History</h4>
							<span class="text-muted">System tracks and save all the important events regarding the fee collection module. Find below the history of this module.</span>
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
										<th class="font-weight-bold">Student</th>
										<th class="font-weight-bold">Class</th>
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
											<div><a ng-href="<?php print $this->LIB_CONT_ROOT.'student/profile/'?>{{row.student_id}}" class="font-weight-semibold">
											<img ng-src="<?php print $this->UPLOADS_ROOT;?>images/student/profile/{{row.image}}" class="rounded-circle m-1" width="32" height="32" alt="">
        									{{row.student_name}}</a></div>
										</td>
										<td>{{row.class}}</td>
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
							<label class="text-muted">Class</label>
							<select class="form-control select" ng-model="filter.class" data-fouc>
							<option value="">Any Class</option>
							<?php foreach ($classes as $row){?>            
							    <option value="<?php print $row['mid'];?>" /><?php print strtoupper($row['title']);?>
						    <?php }?>
							</select>
						</div>	
						<div class="col-sm-3">
							<label class="text-muted">Status</label>
							<select class="form-control select" ng-model="filter.status" data-fouc>
								<option value="">Any Status</option>
								<option value="<?php print $this->std_fee_voucher_m->STATUS_PAID ?>"> Received</option>
								<option value="<?php print $this->std_fee_voucher_m->STATUS_UNPAID ?>"> Not Received</option>
								<option value="<?php print $this->std_fee_voucher_m->STATUS_PARTIAL_PAID ?>"> Partialy Received</option>
								<option value="<?php print $this->std_fee_voucher_m->STATUS_CANCELED ?>"> Canceled</option>
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
				<div class="row">
					<div class="col-sm-5">	
						<div class="card card-body border-top-info">
							<legend class="font-weight-semibold text-uppercase font-size-sm text-center">
								<i class="icon-vcard mr-2"></i>Student &amp; Voucher Information</legend>
							<!-- <p class="mb-3 text-muted">further details.</p> -->
									<div class="card card-body bg-light mb-0">
							<div class="row">
								<div class="col-sm-3">
									<img ng-src="<?php print $this->UPLOADS_ROOT;?>images/student/profile/{{selectedRow.image}}" class="img img-rounded img-responsive mr-1" style="width: 72px; height: 72px;" alt="profile photo">
								</div>
								<div class="col-sm-9">
										<dl class="row">

											<dt class="col-sm-6">Date Created</dt>
											<dd class="col-sm-6">{{selectedRow.date}}</dd>

											<dt class="col-sm-6">Due Date</dt>
											<dd class="col-sm-6">{{selectedRow.due_date}}</dd>

											<dt class="col-sm-6">Status</dt>
											<dd class="col-sm-6">
												<span class="text-success" ng-show="voucher.status==='<?php print $this->std_fee_voucher_m->STATUS_PAID;?>'"> Received On {{voucher.date_paid}}</span>
												<span class="text-info" ng-show="voucher.status==='<?php print $this->std_fee_voucher_m->STATUS_PARTIAL_PAID;?>'"> Received {{voucher.amount_paid}} On {{voucher.date_paid}}</span>
												<span class="text-warning" ng-show="voucher.status==='<?php print $this->std_fee_voucher_m->STATUS_UNPAID;?>'"> Not Yet Received</span>
												<span class="text-danger" ng-show="voucher.status==='<?php print $this->std_fee_voucher_m->STATUS_CANCELED;?>'"> Canceled</span>
											</dd>


										</dl>
								</div>
							</div>
									</div>
							<br>
							<div class="row">
								<div class="col-sm-12">							
									<div class="card card-body bg-light mb-0">
										<dl class="row mb-0">
											<dt class="col-sm-6">Voucher</dt>
											<dd class="col-sm-6 text-primary font-weight-bold">{{selectedRow.voucher_id}}</dd>
											
											<dt class="col-sm-6">Student Name</dt>
											<dd class="col-sm-6">{{selectedRow.student_name}}</dd>

											<dt class="col-sm-6">Father Name</dt>
											<dd class="col-sm-6">{{selectedRow.father_name}}</dd>

											<dt class="col-sm-6">Computer Number</dt>
											<dd class="col-sm-6">{{selectedRow.computer_number}}</dd>

											<dt class="col-sm-6">Class</dt>
											<dd class="col-sm-6">{{selectedRow.class}}</dd>

											<dt class="col-sm-6">Section</dt>
											<dd class="col-sm-6">{{selectedRow.section}}</dd>

											<dt class="col-sm-6">Roll Number</dt>
											<dd class="col-sm-6">{{selectedRow.roll_no}}</dd>

											<dt class="col-sm-6">Advance Fee Balance</dt>
											<dd class="col-sm-6"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]; ?>{{selectedRow.advance_amount}}</dd>


										</dl>
									</div>
								</div>
							</div>
						</div>	
					</div>
					<div class="col-sm-7">
						<!-- Invoice template -->
						<div class="card border-top-info">
							<div class="card-body">															
								<div class="row">
									<div class="col-sm-12">
										<legend class="font-weight-semibold text-uppercase font-size-sm text-center">
											<i class="icon-credit-card mr-2"></i>Receiveable Amount From Student</legend>
										<div class="table-responsive">
										    <table class="table table-xs">
										        <!-- <thead>
										            <tr>
										                <th>#</th>
										                <th>Title</th>
										                <th>Amount</th>
										                <th>Date</th>
										                <th ng-show="voucher.status==='<?php print $this->std_fee_voucher_m->STATUS_UNPAID;?>' || '<?php print $this->IS_DEV_LOGIN ? 'yes':'no';?>'==='yes'">Action</th>
										            </tr>
										        </thead> -->
										        <tbody>
										            <tr ng-repeat="row in voucher.entries" class="text-muted" ng-show="row.operation==='<?php print $this->std_fee_entry_m->OPT_PLUS;?>'">
														<?php if($this->LOGIN_USER->prm_finance>2){ ?>
										                <td ng-show="voucher.status==='<?php print $this->std_fee_voucher_m->STATUS_UNPAID;?>' || '<?php print $this->LOGIN_USER->prm_finance>5 ? 'yes':'no';?>'==='yes'" ng-click="delEntry(row)"><a class="mouse-pointer"><i class="icon-cross text-danger"></i></a></td>
										            	<?php } ?>
														<!-- <td>{{$index+1}}</td> -->
										                <td>
										                	<span>{{row.remarks}}</span>
									                	</td>
										                <td>{{row.date}}</td>
										                <td class="text-info"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{row.amount}}</td>

										            </tr>
										        </tbody>
										        <tfoot>
										        	<tr>
										        		<td ng-show="voucher.status==='<?php print $this->std_fee_voucher_m->STATUS_UNPAID;?>' || '<?php print $this->LOGIN_USER->prm_finance>5 ? 'yes':'no';?>'==='yes'"></td>
										        		<td class="font-weight-bold text-center" colspan="2">Sub Total</td>
										        		<td>
										        			<h5 class="font-weight-semibold"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{voucher.amount | number:2}}</h5>
										        		</td>
										        	</tr>
										        </tfoot>
										    </table>
											<legend class="font-weight-semibold text-uppercase font-size-sm text-center"  ng-show="(voucher.entries|filter:{$:'minus'}).length>0">
												<i class="icon-coin-dollar mr-2"></i>Payment Adjustments</legend>
										    <table class="table table-xs" ng-show="(voucher.entries|filter:{$:'minus'}).length>0">
										        <tbody>
										            <tr ng-repeat="row in voucher.entries" class="text-muted" ng-show="row.operation==='<?php print $this->std_fee_entry_m->OPT_MINUS;?>'">
														<?php if($this->LOGIN_USER->prm_finance>2){ ?>
										                <td ng-show="voucher.status==='<?php print $this->std_fee_voucher_m->STATUS_UNPAID;?>' || '<?php print $this->LOGIN_USER->prm_finance>5 ? 'yes':'no';?>'==='yes'" ng-click="delEntry(row)"><a class="mouse-pointer"><i class="icon-cross text-danger"></i></a></td>
										            	<?php } ?>
														<!-- <td>{{$index+1}}</td> -->
										                <td>
										                	<span>{{row.remarks}}</span>
									                	</td>
										                <td>{{row.date}}</td>
										                <td class="text-success"> - <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{row.amount}}</td>

										            </tr>
										        </tbody>
										    </table>
										</div>
									</div>
								</div>
							</div>

							<div class="card-body">
								<div class="d-md-flex flex-md-wrap">
									<div class="pt-2 mb-3 wmin-md-400 ml-auto">
										<div class="table-responsive">
											<table class="table table-xs">
												<tbody>
													<tr ng-show="voucher.concession>0">
														<th>Concession:<span class="font-weight-normal"></span></th>
														<td class="text-right"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{voucher.concession}}</td>
													</tr>
													<tr ng-show="voucher.amount_paid>0">
														<th>Received: {{voucher.date_paid}}<span class="font-weight-normal"></span></th>
														<td class="text-right"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{voucher.amount_paid}}</td>
													</tr>
													<tr ng-show="voucher.amount_cf>0">
														<th>C/Forward:<span class="font-weight-normal"></span></th>
														<td class="text-right"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{voucher.amount_cf}}</td>
													</tr>
													<tr>
														<th>Receivable Amount:</th>
														<td class="text-right text-primary">
															<h5 class="font-weight-semibold"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL];?>{{voucher.remaining_amount | number:2}}</h5></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>

						</div>
						<!-- /invoice template -->						
					</div>
				</div>

				<div class="row" ng-show="entry.add===true">
					<div class="col-sm-12">											
					<!-- Invoice template -->
					<div class="card">
						<div class="card-footer">
							<div class="form-group" ng-show="entry.add===true">
								<div class="row">
									<div class="col-sm-3" ng-show="entry.type==='plus'">
										<label class="text-muted">Category</label>
										<select class="form-control select" ng-model="entry.feetype" data-fouc>
									    <option value=""/>Select Category
										<?php foreach ($fee_types as $key=>$val){ ?>
										    <option value="<?php print $key;?>"/><?php print ucwords($val);?>
									    <?php }?>
										</select>
									</div>	
									<div class="col-sm-3">
										<label class="text-muted">Title</label>
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
									<div class="col-sm-3">

										<label class="text-muted">Click to save record</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
										<button class="btn btn-info btn-lg" ng-click="saveRow()" ng-show="entry.type==='plus'">
											<span class="font-weight-bold"> Add Arrear</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
										</button>
										<button class="btn btn-danger btn-lg" ng-click="saveRow()" ng-show="entry.type==='minus'">
											<span class="font-weight-bold">Give Concession</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
										</button>
										<button class="btn btn-success btn-lg" ng-click="payFee()" ng-show="entry.type==='pay'">
											<span class="font-weight-bold"> Receive Fee</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
										</button>
										<button class="btn btn-default btn-lg" ng-click="entry.add=false;entry.type='';entry.title=''; entry.amount=''">
											<span class="font-weight-bold"> Cancel</span> <i class="icon-cancel-circle2" class=" ml-1"></i>
										</button>
										</div>
									</div>
								</div>
								<hr>
							</div>

							<span class="text-muted">You may find the amount receiveable on left hand side. Right side show the payment history and concessions given to the student. You may add more record to this voucer. Please contact support team for more information.</span>
						</div>
					</div>
					<!-- /invoice template -->	
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-lg" ng-click="entry.type='advancepay';entry.add=false;payFee()" ng-show="voucher.status!=='<?php print $this->std_fee_voucher_m->STATUS_PAID; ?>'">
					<span class="font-weight-bold"> Pay From Advance</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
				<button class="btn btn-success btn-lg" ng-click="entry.add=true;entry.type='pay';entry.title='Fee received'; entry.amount=voucher.remaining_amount" ng-show="voucher.status!=='<?php print $this->std_fee_voucher_m->STATUS_PAID; ?>'">
					<span class="font-weight-bold"> Pay Voucher</span> <i class="icon-checkmark-circle2" class=" ml-2"></i>
				</button>
				<?php if($this->LOGIN_USER->prm_finance>1){ ?>
				<button class="btn btn-info btn-lg" ng-click="entry.add=true;entry.type='plus';entry.title=''"  ng-show="voucher.status!=='<?php print $this->std_fee_voucher_m->STATUS_PAID; ?>'">
					<span class="font-weight-bold"> Add Arrear</span> <i class="icon-plus-circle2" class=" ml-2"></i>
				</button>
				<button class="btn btn-danger btn-lg" ng-click="entry.add=true;entry.type='minus';entry.title='Concession'"  ng-show="voucher.status!=='<?php print $this->std_fee_voucher_m->STATUS_PAID; ?>'">
					<span class="font-weight-bold"> Concession </span> <i class="icon-minus-circle2" class=" ml-2"></i>
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
				<h6 class="modal-title">Create fee slips for students...</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">
					<code>Class Arrears</code>If you want to collect funds or special fee from whole class, you may add class arrears before generating these slips...<br>
					<code>Auto Fee Collection</code>If you want to collect fee automatically from students who have deposited the advance fee then choose the <b>Collect Fee Automatically</b> from dropdown...<br>
					<!-- <code>Voucher Mode</code>If you want to create new vouchers for every student then choose <b> Only Create New Vouchers</b> from drop down...<br> -->
					<code>Create Mode</code>If you do not want create vouchers with zero balance then choose <b> Skip Free Students</b> from drop down...<br>
					<?php if($this->IS_COLLEGE && $this->CAMPUSSETTINGS[$this->campus_setting_m->_STD_FEE_TYPE]==$this->campus_setting_m->TYPE_FIXED){?>					
					<code>Term Fee Vouchers</code>Create vouchers for all students once in a session...<br>
					<code>Monthly Fee Vouchers</code>Proceed vouchers to next month for all students and update remaining fee...<br>
					<?php } ?>
				</p>
				
				<hr>
				<div class="form-group">
					<div class="row">
						<?php if($this->IS_COLLEGE && $this->CAMPUSSETTINGS[$this->campus_setting_m->_STD_FEE_TYPE]==$this->campus_setting_m->TYPE_FIXED){?>
							<div class="col-sm-3">
								<label class="text-muted">Target Students</label>
								<select class="form-control select" ng-model="newSlip.target" data-fouc>
									<option value="monthly">Monthly Fee Vouchers</option>
									<option value="fixed">Term Fee Vouchers</option>
								</select>
							</div>	
						<?php }else{ ?>						
							<div class="col-sm-5">
								<label class="text-muted">Voucher Mode</label>
								<select class="form-control select" ng-model="newSlip.update_voucher" data-fouc>
									<option value="existing">Transfer Balance to New Vouchers</option>
									<option value="new">Only Create New Vouchers</option>
								</select>
							</div>	
						<?php } ?>
						<div class="col-sm-4">
							<label class="text-muted">Specified Class?</label>
							<select class="form-control select" ng-model="newSlip.class_id" data-fouc>
							<option value="">Select an option</option>
							<option value="0">All Classes</option>
							<?php foreach ($classes as $row){?>            
							    <option value="<?php print $row['mid'];?>" /><?php print strtoupper($row['title']);?>
						    <?php }?>
							</select>
						</div>	

						<?php if($this->IS_COLLEGE && $this->CAMPUSSETTINGS[$this->campus_setting_m->_STD_FEE_TYPE]==$this->campus_setting_m->TYPE_FIXED && $this->class_feepackage_m->get_rows(array('campus_id'=>$this->CAMPUSID),'',true)>0){ ?>
						<div class="col-sm-4" ng-show="newSlip.target==='fixed'">
							<label class="text-muted">Apply Fee Package Policy?</label>
							<select class="form-control select" ng-model="newSlip.feepkgpolicy" data-fouc>
							<option value="">Select an option</option>
							<option value="0">No, Do Not Apply Fee Package Policy</option>
							<option value="1">Yes, Apply Fee Package Policy</option>
							</select>
						</div>	
						<?php } ?>

						
					</div>
				</div>
				<div class="form-group" ng-show="newSlip.feepkgpolicy>0 && newSlip.target==='fixed'">
					<div class="row">
						<p class="text-muted">
							<code>Fee Package Policy</code>Please make sure to check fee package policy in settings and Last exam marks for all classes in exam menu before applying fee package policy.
						</p>
					</div>
				</div>
				<div class="form-group">
					<div class="row">	
						<div class="col-sm-3">
							<label class="text-muted">Due Date</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-md datepicker" placeholder="Due Date" ng-model="newSlip.due_date">
								<div class="form-control-feedback form-control-feedback-lg">
									<i class="icon-calendar"></i>
								</div>
							</div>
						</div>			
						<div class="col-sm-3" ng-show="newSlip.target==='monthly'">
							<label class="text-muted">Fee Month<span class="text-danger"> * </span></label>
							<select class="form-control select" ng-model="newSlip.month" data-fouc>
							    <option value="" />Select Month
								<?php 
								$month=$this->std_fee_voucher_m->month;
								$year=$this->std_fee_voucher_m->year;
									$next_month=$month+1;
									$prev_month=$month-1;
									if($next_month>12){$next_month=1;}
									if($prev_month<1){$prev_month=12;}
								?>            
							    <option value="<?php print $prev_month;?>" /><?php print month_string($prev_month);?>
							    <option value="<?php print $month;?>"/><?php print month_string($month);?>
							    <option value="<?php print $next_month;?>" /><?php print month_string($next_month);?>
							    <?php if($month+2 <=12 ){ ?>
							    <option value="<?php print $month+2;?>" /><?php print month_string($month+2);?>
								<?php } ?>
							    <?php if($month+3 <=12 ){ ?>
							    <option value="<?php print $month+3;?>" /><?php print month_string($month+3);?>
								<?php } ?>
							    <?php if($month+4 <=12 ){ ?>
							    <option value="<?php print $month+4;?>" /><?php print month_string($month+4);?>
								<?php } ?>
							</select>
						</div>				
						<div class="col-sm-3" ng-show="newSlip.target==='monthly'">
							<label class="text-muted">Fee Year<span class="text-danger"> * </span></label>
							<select class="form-control select" ng-model="newSlip.year" data-fouc>
							    <option value="" />Select Year
								<?php ?>     
								<?php if($month==1) {?><option value="<?php print $year-1;?>" /><?php print $year-1;?><?php } ?>
							    <option value="<?php print $year;?>"/><?php print $year;?>
								<?php if($month==12) {?><option value="<?php print $year+1;?>" /><?php print $year+1;?><?php } ?>
							</select>
						</div>			
						<div class="col-sm-3">
							<label class="text-muted">Create Mode</label>
							<select class="form-control select" ng-model="newSlip.skipZero" data-fouc>
							    <option value=""/>select an option
							    <option value="no"/>Create For All
							    <option value="yes"/>Skip Free Students
							</select>
						</div>
						<div class="col-sm-6">
							<label class="text-muted">Auto Fee Collection</label>
							<select class="form-control select" ng-model="newSlip.auto_collection" data-fouc>
							    <option value=""/>select an option
								<option value="disable">Disable Auto Fee Collection</option>
								<option value="receive">Collect Fee Automatically (From Advance Fee)</option>
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

<!-- remove voucher modal -->
<div id="remove-voucher" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Remove fee slips for students...</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">
					<code>Remove Other Month Slips</code>Select Month & Year fo which fee slips to be removed. Please note that all fee vouchers will be removed from system...<br>
				</p>
				
				<hr>
				<div class="form-group">
					<div class="row">				
						<div class="col-sm-4">
							<label class="text-muted">Month<span class="text-danger"> * </span></label>
							<select class="form-control select" ng-model="exSlip.month" data-fouc>
							    <option value="" />Select Month
								<?php 
								$month=$this->std_fee_voucher_m->month;
								$year=$this->std_fee_voucher_m->year;
									$next_month=$month+1;
									$prev_month=$month-1;
									if($next_month>12){$next_month=1;}
									if($prev_month<1){$prev_month=12;}
								?>            
							    <option value="<?php print $prev_month;?>" /><?php print month_string($prev_month);?>
							    <option value="<?php print $month;?>"/><?php print month_string($month);?>
							    <option value="<?php print $next_month;?>" /><?php print month_string($next_month);?>
							    <?php if($month+2 <=12 ){ ?>
							    <option value="<?php print $month+2;?>" /><?php print month_string($month+2);?>
								<?php } ?>
							    <?php if($month+3 <=12 ){ ?>
							    <option value="<?php print $month+3;?>" /><?php print month_string($month+3);?>
								<?php } ?>
							    <?php if($month+4 <=12 ){ ?>
							    <option value="<?php print $month+4;?>" /><?php print month_string($month+4);?>
								<?php } ?>
							</select>
						</div>				
						<div class="col-sm-4">
							<label class="text-muted">Year<span class="text-danger"> * </span></label>
							<select class="form-control select" ng-model="exSlip.year" data-fouc>
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
				<button ng-click="delCustomVouchers()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Remove Slips</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /remove voucher modal -->

<!-- send list sms -->
<div id="list-sms" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Send SMS Notification regarding fee</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you send sms notification to students regarding fee updates. Write here the message and choose target audience. System will automatically send sms to target audience. <?php print $this->SMS_HOST_NOTE; ?></p>
				<legend class="font-weight-semibold text-uppercase font-size-sm">
					<i class="icon-filter3 mr-2"></i>Choose dynamic data...</legend>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<label class="text-muted">WHOME TO SEND SMS?</label>
							<select class="form-control select" ng-model="sms.target" data-fouc>
							<option value="">Choose Receiver</option>
							<option value="<?php print $this->sms_hook_m->TARGET_GAURDIAN;?>">Guardian's</option>
							<option value="<?php print $this->sms_hook_m->TARGET_STUDENT;?>">Student's</option>
							</select>
						</div>	
						<div class="col-sm-2">
							<label class="text-muted">Student Of Class</label>
							<select class="form-control select" ng-model="filter.class" data-fouc>
							<option value="">All Classes</option>
							<?php foreach ($classes as $row){?>            
							    <option value="<?php print $row['mid'];?>" /><?php print strtoupper($row['title']);?>
						    <?php }?>
							</select>
						</div>	
						<div class="col-sm-2">
							<label class="text-muted">Fee Status</label>
							<select class="form-control select" ng-model="filter.status" data-fouc>
								<option value="">Any Status</option>
								<option value="<?php print $this->std_fee_voucher_m->STATUS_PAID ?>">Who Paid Fee</option>
								<option value="<?php print $this->std_fee_voucher_m->STATUS_UNPAID ?>">Who Not Yet Paid Fee</option>
								<option value="<?php print $this->std_fee_voucher_m->STATUS_PARTIAL_PAID ?>">Who Partialy Paid Fee</option>
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
								<button type="button" class="btn btn-outline-success btn-sm" ng-click="addkey('NAME')"><i class="icon-user mr-2"></i> Student Name</button>
								<button type="button" class="btn btn-outline-info btn-sm" ng-click="addkey('GUARDIAN')"><i class="icon-people mr-2"></i> Guardian Name</button>
								<button type="button" class="btn btn-outline-warning btn-sm" ng-click="addkey('AMOUNT')"><i class="icon-coin-dollar mr-2"></i> Voucher Amount</button>
								<button type="button" class="btn btn-outline-danger btn-sm" ng-click="addkey('DUEDATE')"><i class="icon-calendar mr-2"></i> Due Date</button>
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
				<h6 class="modal-title">Send SMS Notification to {{selectedRow.student_name}}</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you send sms notification to students regarding fee updates. Write here the message and choose target audience. System will automatically send sms to target audience. <?php print $this->SMS_HOST_NOTE; ?></p>
				<legend class="font-weight-semibold text-uppercase font-size-sm">
					<i class="icon-filter3 mr-2"></i>Choose dynamic data...</legend>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<label class="text-muted">WHOME TO SEND SMS?</label>
							<select class="form-control select" ng-model="sms.target" data-fouc>
							<option value="">Choose Receiver</option>
							<option value="<?php print $this->sms_hook_m->TARGET_GAURDIAN;?>">Guardian's</option>
							<option value="<?php print $this->sms_hook_m->TARGET_STUDENT;?>">Student's</option>
							</select>
						</div>	
						
					</div>
				</div>
				<hr>			
				<div class="form-group">
					<div class="row">					
						<div class="col-sm-12" ng-show="sms.target.length>0">
							<p>
								<button type="button" class="btn btn-outline-success btn-sm" ng-click="addkey('NAME')"><i class="icon-user mr-2"></i> Student Name</button>
								<button type="button" class="btn btn-outline-info btn-sm" ng-click="addkey('GUARDIAN')"><i class="icon-people mr-2"></i> Guardian Name</button>
								<button type="button" class="btn btn-outline-warning btn-sm" ng-click="addkey('AMOUNT')"><i class="icon-coin-dollar mr-2"></i> Voucher Amount</button>
								<button type="button" class="btn btn-outline-danger btn-sm" ng-click="addkey('DUEDATE')"><i class="icon-calendar mr-2"></i> Due Date</button>
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