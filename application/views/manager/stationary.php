<?php
// $sessions=$this->session_m->get_rows(array(),array('orderby'=>'status ASC,mid DESC') );
$classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'title ASC') );
$accounts=$this->accounts_m->get_rows(array('campus_id'=>$this->CAMPUSID,'type'=>$this->accounts_m->TYPE_LIABILITY));
$cash_account=$this->accounts_m->get_by(array('campus_id'=>$this->CAMPUSID,'title'=>$this->accounts_m->_CASH),true);
$bank_account=$this->accounts_m->get_by(array('campus_id'=>$this->CAMPUSID,'title'=>$this->accounts_m->_BANK),true);
// $classes_array=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
// $sessions_array=$this->session_m->get_values_array('mid','title',array());
$types=$this->stationary_m->get_item_types();
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
					<a class="breadcrumb-item">Stationary</a>
					<span class="breadcrumb-item active">Stationary Items</span>
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
						<a href="<?php print $this->CONT_ROOT.'printing/list/';?>" class="dropdown-item"><i class="icon-profile"></i> Items List</a>
						<a href="<?php print $this->CONT_ROOT.'printing/history/';?>" class="dropdown-item"><i class="icon-history"></i> History</a>
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
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Stationary</span> - Items</h4>
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
							<i class="icon-profile mr-2" style="color:<?php print $clr;?>;"></i>
							Stationary Items
						</a>
					</li>
					<?php if($this->LOGIN_USER->prm_stationary>1){ ?>
					<li class="nav-item">
						<a href="#add" class="navbar-nav-link" data-toggle="tab" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-plus-circle2 mr-2" style="color:<?php print $clr;?>;"></i>
							Register Item
						</a>
					</li>
					<?php } ?>
					<li class="nav-item">
						<a href="#issue_staff" class="navbar-nav-link" data-toggle="tab" ng-click="loadStationary()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-user-tie mr-2" style="color:<?php print $clr;?>;"></i>
							Staff Issue
						</a>
					</li>
					<li class="nav-item">
						<a href="#issue_student" class="navbar-nav-link" data-toggle="tab" ng-click="loadStationary()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-reading mr-2" style="color:<?php print $clr;?>;"></i>
							Student Issue
						</a>
					</li>
					<?php if($this->LOGIN_USER->prm_stationary>1){ ?>
					<li class="nav-item">
						<a href="#history" class="navbar-nav-link" data-toggle="tab" ng-click="loadHistory()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-history mr-2" style="color:<?php print $clr;?>;"></i>
							History
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
							<h4 class="card-title">Stationary Items</h4>
							<span class="text-muted">Search Stationary Items.</span>
						</div>
						<div class="card-body">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-3">
										<select class="form-control select" ng-model="filter.type" ng-change="loadRows()" data-fouc>
										<option value="">All Types</option>
										<?php foreach($types as $key => $value){?>
										<option value="<?php print $key;?>" /><?php print $value;?>
										<?php }?>
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
												<i class="icon-filter3 mr-2"></i> Advance Search</a>
									</div>
								</div>
							</div>
							<div class="table-responsive">
								<p class="text-muted">Find below the stationary items registered in the system. Stock shows the item availability in the warehouse. You can add the stock to warehouse.</p>
								<table class="table tasks-responsive table-lg">
									<thead>
										<tr>
											<th class="font-weight-bold">#</th>
											<th ng-click="sortBy('item');loadRows();" class="mouse-pointer font-weight-bold" >
												<span class="m-1">Product</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='item'"></i> 
											</th>
											<th ng-click="sortBy('item_price');loadRows();" class="mouse-pointer font-weight-bold">
												<span class="m-1">Sale Price</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='item_price'"></i> 
											</th>
											<th ng-click="sortBy('qty');loadRows();" class="mouse-pointer font-weight-bold">
												<span class="m-1">Available Stock</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='qty'"></i> 
											</th>
											<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
							            </tr>
									</thead>
									<tbody>

										<tr ng-repeat="row in responseData.rows">
											<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
											<td>{{row.item}}</td>
						  					<td>{{row.item_price}}</td>  
						  					<td>{{row.qty}}</td> 
											<td>
												<div class="list-icons float-right">
													<div class="btn-group list-icons-item dropdown">
								                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>

														<div class="dropdown-menu dropdown-menu-right">
															<?php if($this->LOGIN_USER->prm_stationary>1){ ?>
															<a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#edit" ng-click="selectRow(row)">
																<i class="icon-compose"></i> Update Item
															</a>
															<a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#add-stock" ng-click="selectRow(row)">
																<i class="icon-plus-circle2"></i> Add Stock
															</a>
															<?php } ?>
															<?php if($this->LOGIN_USER->prm_stationary>2){ ?>
															<div class="dropdown-divider"></div>
															<a class="dropdown-item" ng-click="delRow(row)"><i class="icon-cross"></i> Remove Item</a>
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
			    <div class="tab-pane fade <?php print $tab=='add' ? 'active show': '';?>" id="add">
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Register Stationary Item
							<span class="d-block font-size-base text-muted">Stationary helps you manage stationary items/stocks. System will create stationary reports at the end of month. 
							</span>
							</h5>
						</div>

						<div class="card-body">
							<div class="form-group">
							<div class="row">
								<div class="col-sm-3">
									<label class="text-muted">Item Type<span class="text-danger"> *</span></label>
									<select class="form-control select" ng-model="item.type" data-fouc>
									<option value="">Selet Stationary Type</option>
									<?php foreach($types as $key => $value){?>
									<option value="<?php print $key;?>" /><?php print $value;?>
									<?php }?>
									</select>
								</div>
								<div class="col-md-6">
									<label class="text-muted">Name<span class="text-danger"> *</span></label>
									<input type="text" class="form-control" placeholder="E.g Uniform 8th class" ng-model="item.name">
								</div>
								<div class="col-md-3">
									<label class="text-muted">Item Price<span class="text-danger"> * </span></label>
									<input type="text" class="form-control" placeholder="1500" ng-model="item.price">
								</div>
								<div class="col-md-9">
									<label class="text-muted">Description</label>
									<textarea class="form-control" placeholder="description (optional)" ng-model="item.description"></textarea>
								</div>
								<div class="col-md-3">
									<button ng-click="saveRow()" class="btn btn-success btn-lg ml-2 mt-3">
										<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-checkmark-circle2':!appConfig.btnClickedSave}" class="ml-2"></i>
									</button>
								</div>
							</div>
							</div>

						</div>
					</div>
					<!-- /save info -->

		    	</div>
			    <div class="tab-pane fade <?php print $tab=='issue_staff' ? 'active show': '';?>" id="issue_staff">
					
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Issue Item</h4>
							<span class="text-muted">Search staff in order to issue item to a member.</span>
						</div>
						<div class="card-body">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-12">
										<div class="input-group mb-4">
											<div class="form-group-feedback form-group-feedback-left">
												<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Enter Staff name, mobile or cnic of staff" ng-keyup="loadStaff()">
												<div class="form-control-feedback form-control-feedback-lg">
													<i class="icon-search4 text-muted"></i>
												</div>
											</div>

											<div class="input-group-append">
												<button ng-click="loadStaff()" class="btn btn-success btn-lg">
												<span class="font-weight-bold"> Search Staff</span>
												<i ng-class="{'icon-spinner2':appConfig.btnClickedSearch, 'spinner':appConfig.btnClickedSearch, 'icon-circle-right2':!appConfig.btnClickedSearch}" class=" ml-2"></i>
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th class="font-weight-bold">Staff Name</th>
										<th class="font-weight-bold">Mobile</th>
										<th class="font-weight-bold">Staff ID</th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in staffs.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>
											<div><a ng-href="<?php print $this->LIB_CONT_ROOT.'staff/profile/'?>{{row.mid}}" class="font-weight-semibold">
											<img ng-src="<?php print $this->UPLOADS_ROOT;?>images/staff/profile/{{row.image}}" class="rounded-circle m-1" width="32" height="32" alt="">
                							{{row.name}}
											</a></div>
										</td>
										<td>{{row.mobile}}</td>  
										<td>{{row.staff_id}}</td>
					  					<td>
					  						<a class="btn btn-link text-info" <?php print $this->MODAL_OPTIONS;?> data-target="#issue-item-staff" ng-click="selectRow(row)"> Issue Items <i class="icon-checkmark-circle2"></i></a>
					  					</td> 
										
						            </tr>


								</tbody>
							</table>
							<br><br><br>

						</div>
					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='issue_student' ? 'active show': '';?>" id="issue_student">
					
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Issue Item</h4>
							<span class="text-muted">Search sutdent in order to issue item to a student.</span>
						</div>
						<div class="card-body">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-3">
										<select class="form-control select" ng-model="filter.class" data-fouc>
										<option value="">Any Class</option>
										<?php foreach($classes as $cls){?>
										<option value="<?php print $cls['mid'];?>" /><?php print $cls['title'];?>
										<?php }?>
										</select>
									</div>
									<div class="col-sm-9">
										<div class="input-group mb-4">
											<div class="form-group-feedback form-group-feedback-left">
												<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Enter Student name, roll number or student id" ng-keyup="loadStudents()">
												<div class="form-control-feedback form-control-feedback-lg">
													<i class="icon-search4 text-muted"></i>
												</div>
											</div>

											<div class="input-group-append">
												<button ng-click="loadStudents()" class="btn btn-success btn-lg">
												<span class="font-weight-bold"> Search Student</span>
												<i ng-class="{'icon-spinner2':appConfig.btnClickedSearch, 'spinner':appConfig.btnClickedSearch, 'icon-circle-right2':!appConfig.btnClickedSearch}" class=" ml-2"></i>
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th class="font-weight-bold">Student Name</th>
										<th class="font-weight-bold">Father Name</th>
										<th class="font-weight-bold">Student ID</th>
										<th class="font-weight-bold">Class</th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in students.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>
											<div><a ng-href="<?php print $this->LIB_CONT_ROOT.'student/profile/'?>{{row.mid}}" class="font-weight-semibold">
											<img ng-src="<?php print $this->UPLOADS_ROOT;?>images/student/profile/{{row.image}}" class="rounded-circle m-1" width="32" height="32" alt="">
                							{{row.name}}
											</a></div>
										</td>
										<td>{{row.father_name}}</td>  
										<td>{{row.student_id}}</td>
					  					<td>{{row.class}}</td> 
					  					<td>
					  						<a class="btn btn-link text-info" <?php print $this->MODAL_OPTIONS;?> data-target="#issue-item-student" ng-click="selectRow(row);loadStudentVouchers(row.mid)"> Issue Items <i class="icon-checkmark-circle2"></i></a>
					  					</td> 
										
						            </tr>


								</tbody>
							</table>
							<br><br><br>

						</div>
					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='history' ? 'active show': '';?>" id="history">
					
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Stationary History</h4>
							<span class="text-muted">System tracks and save all the important events regarding the stationary module. Find below the history of this module.</span>
						</div>
						<div class="card-body">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-3">
										<select class="form-control select" ng-model="filter.type" data-fouc>
										<option value="">All Types</option>
										<?php foreach($types as $key => $value){?>
										<option value="<?php print $key;?>" /><?php print $value;?>
										<?php }?>
										</select>
									</div>
									<div class="col-sm-7">
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
									<div class="col-sm-2">
										<label class="text-muted"><input type="checkbox" ng-model="filter.purchaseLog" class="checkbox-inline" ng-change="loadHistory()" /> Only Purchase Log</label>
									</div>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th class="font-weight-bold">Description</th>
										<th class="font-weight-bold">Quantity</th>
										<th class="font-weight-bold">Price</th>
										<th class="font-weight-bold">Date</th>
										<!-- <th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th> -->
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.description}}</td>
										<td>{{row.qty}}</td>
					  					<td>{{row.qty+'x'+row.item_price}}=<strong><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]?>{{row.item_price*row.qty}}</strong></td>  
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
							<label class="text-muted">Minimum Price</label>
							<select class="form-control select" ng-model="filter.price" data-fouc>
								<option value="">Select Minimum Price</option>
								<option value="100">100</option>
								<option value="200">200</option>
								<option value="500">500</option>
								<option value="1000">1000</option>
								<option value="2000">2000</option>
								<option value="5000">5000</option>
							</select>
						</div>					
						<div class="col-sm-6">
							<label class="text-muted">Minimum Stock</label>
							<select class="form-control select" ng-model="filter.stock" data-fouc>
								<option value="">Select Minimum Stock</option>
								<option value="10">10</option>
								<option value="20">20</option>
								<option value="50">50</option>
								<option value="100">100</option>
								<option value="200">200</option>
								<option value="500">500</option>
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

<!-- edit modal -->
<div id="edit" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update Item ({{selectedRow.item}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Update here the stationary item...</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Item Data</h6>
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
									<label class="text-muted">Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Full Name" ng-model="selectedRow.item">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Price <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Guardian Name" ng-model="selectedRow.item_price">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-coin-dollar"></i>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-10">
									<label class="text-muted">Any Other Information</label>
									<textarea rows="2" class="form-control" placeholder="if any" ng-model="selectedRow.description"></textarea>
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


<!-- add stock modal -->
<div id="add-stock" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Add quantity to stock ({{selectedRow.item}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Add stock of stationary items...</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Purchase Data</h6>
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
									<label class="text-muted">Purchased Quantity <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g 50" ng-model="stock.qty">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Billed Amount <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Total amount spent to purchase the qunatity" ng-model="stock.amount">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-coin-dollar"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Credit Account<span class="text-danger"> * </span></label>
									<select class="form-control select" ng-model="stock.credit_account" data-fouc>
									<option value="">Select Credit Account</option>
								    <option value="<?php print $cash_account->title ?>" /><?php print $cash_account->title; ?>
								    <option value="<?php print $bank_account->title ?>" /><?php print $bank_account->title; ?>
									<?php foreach ($accounts as $account) { ?>
									    <option value="<?php print $account['title'];?>" /><?php print $account['title'];?>
									<?php } ?>
									</select>
								</div>

							</div>
						</div>

					</div>
				</div>



			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="updateStock()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /add stock modal -->

<!-- issue item modal -->
<div id="issue-item-student" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Issue items to ({{selectedRow.name}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Issue stationary items to this student...</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Item Data</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>

						<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-4">
									<label class="text-muted">Select Item</label>
									<select class="form-control select" ng-model="issue.item" ng-change="selectItem()" data-fouc>
										<option value="">Select Stationary item</option>
										<option ng-repeat="row in stationary.rows" value="{{row}}">{{row.item}}</option>
									</select>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Quantity <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g 50" ng-model="issue.qty">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Total Amount</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Total amount" value="<?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]; ?>{{selectedItem.item_price*issue.qty}}" readonly="readonly">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-coin-dollar"></i>
										</div>
									</div>
								</div>

							</div>
						</div>

					</div>
				</div>
				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Payment Slip</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>

						<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-9">
									<label class="text-muted">Select an existing voucher to collect the payment. If you wanna receive urgent payment then create a new voucher and add all the items to newly created voucher.</label>
									<select class="form-control select" ng-model="issue.voucher_id" data-fouc>
										<option value="">Create new voucher of student for stationary items</option>
										<option ng-repeat="row in vouchers.rows" value="{{row.mid}}">{{row.title}} ({{row.voucher_id}})</option>
									</select>
								</div>

							</div>
						</div>

					</div>
				</div>



			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="issueItemStudent()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Issue Item</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /add stock modal -->

<!-- issue item modal -->
<div id="issue-item-staff" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Issue items to ({{selectedRow.name}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Issue stationary items to this staff...</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Item Data</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>

						<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-4">
									<label class="text-muted">Select Item</label>
									<select class="form-control select" ng-model="issue.item" ng-change="selectItem()" data-fouc>
										<option value="">Select Stationary item</option>
										<option ng-repeat="row in stationary.rows" value="{{row}}">{{row.item}}</option>
									</select>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Quantity <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g 50" ng-model="issue.qty">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Total Amount</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Total amount" value="<?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]; ?>{{selectedItem.item_price*issue.qty}}" readonly="readonly">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-coin-dollar"></i>
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
				<button ng-click="issueItemStaff()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Issue Item</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /add stock modal -->



</div>
<!-- /main content -->
<!-- ********************************************************************** -->
<!-- ///////////////////////////////SCRIPTS//////////////////////////////// -->
<!-- ********************************************************************** -->
<!-- <script type="text/javascript">
	
$(function(){
   
});
</script> -->