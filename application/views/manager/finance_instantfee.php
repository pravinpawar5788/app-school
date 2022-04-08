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
	<div class="page-header page-header-light border-bottom-0" ng-init="pay.date='<?php print $this->student_m->date;?>'">

		<!-- Top breadcrumb line -->
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
					<a class="breadcrumb-item">Finance</a>
					<span class="breadcrumb-item active">Instant Fee Collection</span>
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
										<div class="input-group mb-1">
											<div class="form-group-feedback form-group-feedback-left">
												<input type="text" class="form-control form-control-md alpha-grey" ng-model="filter.computer_number" placeholder="Computer Number" ng-keyup="loadFeeRecord()" tabindex="2" autofocus="true" ng-focus="appConfig.enter='load'" id="triggerInput" focus-me="focusTriggerInput">
												<div class="form-control-feedback form-control-feedback-md">
													<i class="icon-user text-muted"></i>
												</div>
											</div>

											<div class="input-group-append">
												<button ng-click="loadFeeRecord()" class="btn btn-success btn-md">
												<span class="font-weight-bold"> Search</span>
												<i ng-class="{'icon-spinner2':appConfig.btnClickedSearch, 'spinner':appConfig.btnClickedSearch, 'icon-circle-right2':!appConfig.btnClickedSearch}" class=" ml-2"></i>
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group" ng-hide="member==='' || listingVouchers.length<1">
								<hr>
								<div class="row">
									<div class="col-sm-2">
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-md datepicker" ng-model="pay.date" placeholder="Received Date">
											<div class="form-control-feedback form-control-feedback-md">
												<i class="icon-calendar"></i>
											</div>
										</div>
									</div>
									<div class="col-sm-3">
										<select class="form-control select" ng-model="pay.voucher_id" data-fouc>
											<option value="">Select Voucher</option>
											<option ng-repeat="row in listingVouchers" value="{{row.mid}}">{{row.title}}</option>
										</select>
									</div>
									<div class="col-sm-2">
										<select class="form-control select" ng-model="pay.method" data-fouc>
											<option value="cash">Received Cash</option>
											<option value="bank">Bank Deposit</option>
										</select>
									</div>
									<div class="col-sm-4">
										<div class="input-group mb-1">
											<div class="form-group-feedback form-group-feedback-left">
												<input type="text" class="form-control form-control-md alpha-grey" ng-model="pay.amount" placeholder="Received Amount" tabindex="3" ng-focus="appConfig.enter='pay'">
												<div class="form-control-feedback form-control-feedback-md">
													<i class="icon-coin-dollar text-muted"></i>
												</div>
											</div>

											<div class="input-group-append">
												<button ng-click="saveInstantPayRecord()" class="btn btn-success btn-md">
												<span class="font-weight-bold"> Save</span>
												<i ng-class="{'icon-spinner2':appConfig.btnClickedSearch, 'spinner':appConfig.btnClickedSearch, 'icon-circle-right2':!appConfig.btnClickedSearch}" class=" ml-2"></i>
												</button>
											</div>
											<button class="btn btn-info btn-md ml-2" <?php print $this->MODAL_OPTIONS;?> data-target="#add-arrear" ng-click="selectRow(row);loadVoucher(row);">
												<span class="font-weight-bold"> Add Arrears</span> <i class="icon-plus-circle2"></i>
											</button>
										</div>																			
									</div>
								</div>
							</div>
							<div class="form-group" ng-hide="member===''">
								<hr>
								<div class="row">
									<div class="col-sm-4">
										<label class="text-muted">Student Name</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" ng-model="member.name" readonly="readonly">
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-user"></i>
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<label class="text-muted">Father Name</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" ng-model="member.father_name" readonly="readonly">
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-people"></i>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<label class="text-muted">Class </label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" ng-model="member.class" readonly="readonly">
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-collaboration"></i>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<label class="text-muted">Section </label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" ng-model="member.clsection" readonly="readonly">
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-list"></i>
											</div>
										</div>
									</div>
									<div class="col-sm-3">
										<label class="text-muted">G. Mobile N0</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" ng-model="member.guardian_mobile" readonly="readonly">
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-mobile"></i>
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<label class="text-muted">Address</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" ng-model="member.address" readonly="readonly">
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-pin"></i>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<label class="text-muted">Std Fee </label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" ng-model="member.fee" readonly="readonly">
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-coin-dollar"></i>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<label class="text-muted">Trans. Fee </label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" ng-model="member.transport_fee" readonly="readonly">
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-bus"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
							<hr>
							<div class="table-responsive">
								<p class="text-muted">Student Fee Record of Last 12 months. Enter computer number to load student data.</p>
								<table class="table tasks-responsive table-xs">
									<thead>
										<tr>
											<th class="font-weight-semibold" <?php print $this->POPOVER_CELL;?> data-content="Fee Month">Month</th>
											<th class="font-weight-semibold" <?php print $this->POPOVER_CELL;?> data-content="Student Fee">Std.Fee</th>
											<th class="font-weight-semibold" <?php print $this->POPOVER_CELL;?> data-content="Previous Balance">Prev</th>
											<th class="font-weight-semibold" <?php print $this->POPOVER_CELL;?> data-content="Van Fee / Transport Fee">TrFee</th>
											<th class="font-weight-semibold" <?php print $this->POPOVER_CELL;?> data-content="Library Fee">LiFee</th>
											<th class="font-weight-semibold" <?php print $this->POPOVER_CELL;?> data-content="Admission Fee">AdFee</th>
											<th class="font-weight-semibold" <?php print $this->POPOVER_CELL;?> data-content="Re Admission Fee">ReAdm</th>
											<th class="font-weight-semibold" <?php print $this->POPOVER_CELL;?> data-content="Annual Funds">AnFun</th>
											<th class="font-weight-semibold" <?php print $this->POPOVER_CELL;?> data-content="Paper Funds">PaFun</th>
											<th class="font-weight-semibold" <?php print $this->POPOVER_CELL;?> data-content="Stationary Fee or Funds">StFun</th>
											<th class="font-weight-semibold" <?php print $this->POPOVER_CELL;?> data-content="Miscellaneous Funds">MisFun</th>
											<th class="font-weight-semibold" <?php print $this->POPOVER_CELL;?> data-content="Prospectus Fee">Prosp</th>
											<th class="font-weight-semibold" <?php print $this->POPOVER_CELL;?> data-content="Absent Fine">AbFine</th>
											<th class="font-weight-semibold"  <?php print $this->POPOVER_CELL;?> data-content="Late Fee Fine">LfFine</th>
											<th class="font-weight-semibold" <?php print $this->POPOVER_CELL;?> data-content="Miscellaneous Fine">MisFine</th>
											<th class="font-weight-semibold" <?php print $this->POPOVER_CELL;?> data-content="Others">Others</th>
											<th class="font-weight-semibold" <?php print $this->POPOVER_CELL;?> data-content="Concession">Conc.</th>
											<th class="font-weight-semibold" <?php print $this->POPOVER_CELL;?> data-content="Total">Total</th>
											<th class="font-weight-semibold" <?php print $this->POPOVER_CELL;?> data-content="Paid by student">Paid</th>
											<th class="font-weight-semibold" <?php print $this->POPOVER_CELL;?> data-content="Remaining Balance">RemBal</th>
							            </tr>
									</thead>
									<tbody>
										<tr ng-show="responseData.length<1" >
											<td colspan="8">
												<span class="text-center text-danger">No record found</span>
											</td>
										</tr>
										<tr ng-repeat="row in responseData">
											<td>{{row.month}}</td>
											<td>{{row.hd_monthfee}}</td>
											<td>{{row.hd_previous}}</td>
											<td>{{row.hd_transport}}</td>
											<td>{{row.hd_library}}</td>
											<td>{{row.hd_admission}}</td>
											<td>{{row.hd_readmission}}</td>
											<td>{{row.hd_annualfund}}</td>
											<td>{{row.hd_paperfund}}</td>
											<td>{{row.hd_stationery}}</td>
											<td>{{row.hd_miscfund}}</td>
											<td>{{row.hd_prospectus}}</td>
											<td>{{row.hd_absentfine}}</td>
											<td>{{row.hd_lffine}}</td>
											<td>{{row.hd_miscfine}}</td>
											<td>{{row.hd_other}}</td>
											<td>{{row.hd_concession}}</td>
											<td>{{row.hd_total}}</td>
											<td>{{row.hd_paid}}</td>
											<td>{{row.hd_balance}}</td>
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


<!-- add arrears modal -->
<div id="add-arrear" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Add Arrears to selected voucher...</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">
					<code>Arrears</code>You can collect pending arrears from student if any. Enter the arrear amount and hit save button...<br>
				</p>
				
				<hr>
				<div class="row">					
					<div class="col-md-12">	
						<div class="card card-body border-top-info">
							<div class="form-group">
								<div class="row">
									<div class="col-md-4">
										<label class="text-muted">Transport Desciption</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Van Fee" ng-model="entry.fttl_transport" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-vcard"></i>
											</div>
										</div>
									</div>	
									<div class="col-md-2">
										<label class="text-muted">Van Fee</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Amount" ng-model="entry.famt_transport" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-coin-dollar"></i>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<label class="text-muted">Stationery Desciption</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Stationery Funds" ng-model="entry.fttl_stationery" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-vcard"></i>
											</div>
										</div>
									</div>	
									<div class="col-md-2">
										<label class="text-muted">Stationery Funds</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Amount" ng-model="entry.famt_stationery" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-coin-dollar"></i>
											</div>
										</div>
									</div>						
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-4">
										<label class="text-muted">Library Fee Desciption</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Library Fee" ng-model="entry.fttl_library" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-vcard"></i>
											</div>
										</div>
									</div>	
									<div class="col-md-2">
										<label class="text-muted">Library Fee</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Amount" ng-model="entry.famt_library" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-coin-dollar"></i>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<label class="text-muted">Prospectus Desciption</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Prospectus Funds" ng-model="entry.fttl_prospectus" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-vcard"></i>
											</div>
										</div>
									</div>	
									<div class="col-md-2">
										<label class="text-muted">Prospectus Funds</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Amount" ng-model="entry.famt_prospectus" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-coin-dollar"></i>
											</div>
										</div>
									</div>						
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-4">
										<label class="text-muted">Admission Fee Desciption</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Admission Fee" ng-model="entry.fttl_admission" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-vcard"></i>
											</div>
										</div>
									</div>	
									<div class="col-md-2">
										<label class="text-muted">Admission Fee</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Amount" ng-model="entry.famt_admission" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-coin-dollar"></i>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<label class="text-muted">Re Admission Desciption</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Re Admission Fee" ng-model="entry.fttl_readmission" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-vcard"></i>
											</div>
										</div>
									</div>	
									<div class="col-md-2">
										<label class="text-muted">Re Admission Fee</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Amount" ng-model="entry.famt_readmission" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-coin-dollar"></i>
											</div>
										</div>
									</div>						
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-4">
										<label class="text-muted">Annual Funds Desciption</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Annual Funds" ng-model="entry.fttl_annualfund" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-vcard"></i>
											</div>
										</div>
									</div>	
									<div class="col-md-2">
										<label class="text-muted">Annual Funds</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Amount" ng-model="entry.famt_annualfund" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-coin-dollar"></i>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<label class="text-muted">Paper Funds Desciption</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Paper Funds" ng-model="entry.fttl_paperfund" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-vcard"></i>
											</div>
										</div>
									</div>	
									<div class="col-md-2">
										<label class="text-muted">Paper Funds</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Amount" ng-model="entry.famt_paperfund" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-coin-dollar"></i>
											</div>
										</div>
									</div>						
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-4">
										<label class="text-muted">Misc. Funds Desciption</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Misc Funds" ng-model="entry.fttl_miscfund" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-vcard"></i>
											</div>
										</div>
									</div>	
									<div class="col-md-2">
										<label class="text-muted">Misc Funds</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Amount" ng-model="entry.famt_miscfund" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-coin-dollar"></i>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<label class="text-muted">Misc Fine Desciption</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Misc Fine" ng-model="entry.fttl_miscfine" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-vcard"></i>
											</div>
										</div>
									</div>	
									<div class="col-md-2">
										<label class="text-muted">Misc Fine</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Amount" ng-model="entry.famt_miscfine" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-coin-dollar"></i>
											</div>
										</div>
									</div>						
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-4">
										<label class="text-muted">Absend Fine Desciption</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Absent Fine" ng-model="entry.fttl_absentfine" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-vcard"></i>
											</div>
										</div>
									</div>	
									<div class="col-md-2">
										<label class="text-muted">Absent Fine</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Amount" ng-model="entry.famt_absentfine" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-coin-dollar"></i>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<label class="text-muted">Late Fee Fine Desciption</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Late Fee Fine" ng-model="entry.fttl_latefeefine" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-vcard"></i>
											</div>
										</div>
									</div>	
									<div class="col-md-2">
										<label class="text-muted">Late Fee Fine</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Amount" ng-model="entry.famt_latefeefine" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-coin-dollar"></i>
											</div>
										</div>
									</div>						
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-4">
										<label class="text-muted">Others Desciption</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Others" ng-model="entry.fttl_other" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-vcard"></i>
											</div>
										</div>
									</div>	
									<div class="col-md-2">
										<label class="text-muted">Others</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Amount" ng-model="entry.famt_other" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-coin-dollar"></i>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<label class="text-muted">Concession Desciption</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Concession" ng-model="entry.fttl_concession" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-vcard"></i>
											</div>
										</div>
									</div>	
									<div class="col-md-2">
										<label class="text-muted">Concession</label>
										<div class="form-group form-group-feedback form-group-feedback-left">
											<input type="text" class="form-control form-control-sm" placeholder="Amount" ng-model="entry.famt_concession" >
											<div class="form-control-feedback form-control-feedback-sm">
												<i class="icon-coin-dollar"></i>
											</div>
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
				<button ng-click="saveInstantFeeRecord()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /add arrears modal -->


</div>
<!-- /main content -->
<!-- ********************************************************************** -->
<!-- ///////////////////////////////SCRIPTS//////////////////////////////// -->
<!-- ********************************************************************** -->
<!-- <script type="text/javascript">
	
$(function(){
   
});
</script> -->