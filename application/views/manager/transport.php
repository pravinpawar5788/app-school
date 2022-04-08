<?php
// $sessions=$this->session_m->get_rows(array(),array('orderby'=>'status ASC,mid DESC') );
$classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'title ASC') );
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
					<a class="breadcrumb-item">Transport</a>
					<span class="breadcrumb-item active">Transport Module</span>
				</div>

				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="breadcrumb justify-content-center">
					<div class="breadcrumb-elements-item dropdown p-0">
						<a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
							<i class="icon-printer mr-2"></i>Print
						</a>
						<div class="dropdown-menu dropdown-menu-left">
							<!-- <div class="dropdown-divider"></div> -->
							<a href="<?php print $this->CONT_ROOT.'printing/list/?';?>" class="dropdown-item"><i class="icon-users"></i> Passengers List</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /breadcrumb line -->

	
		<!-- Page header content -->
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Transport</span> - Passengers</h4>
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
							<i class="icon-people mr-2" style="color:<?php print $clr;?>;"></i>
							Passengers
						</a>
					</li>
					<li class="nav-item">
						<a href="#add_staff" class="navbar-nav-link" data-toggle="tab" ng-click="loadStationary()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-user-tie mr-2" style="color:<?php print $clr;?>;"></i>
							Register Staff
						</a>
					</li>
					<li class="nav-item">
						<a href="#add_student" class="navbar-nav-link" data-toggle="tab" ng-click="loadStationary()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-reading mr-2" style="color:<?php print $clr;?>;"></i>
							Register Student
						</a>
					</li>
					<li class="nav-item">
						<a href="#routes" class="navbar-nav-link" data-toggle="tab" ng-click="loadRoutes()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-road mr-2" style="color:<?php print $clr;?>;"></i>
							Routes
						</a>
					</li>
					<li class="nav-item">
						<a href="#vehicles" class="navbar-nav-link" data-toggle="tab" ng-click="loadVehicles()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-bus mr-2" style="color:<?php print $clr;?>;"></i>
							Vehicles
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
				</ul>
			</div>
		</div>
		<!-- /profile navigation -->

	</div>
	<!-- /page header -->


	<!-- Content area  -->
	<div class="content">

		<!-- Inner container -->
		<div class="d-flex align-items-start flex-column flex-md-row">

			<!-- Left content -->
			<div class="tab-content w-100 overflow-auto order-2 order-md-1">


			    <div class="tab-pane fade <?php print empty($tab) || $tab=='index' ? 'active show': '';?>" id="index">
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Passengers</h4>
							<span class="text-muted">Search here passengers registered in the system.</span>
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
											<th ng-click="sortBy('passenger_name');loadRows();" class="mouse-pointer font-weight-bold" >
												<span class="m-1">Passenger</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='passenger_name'"></i> 
											</th>
											<th ng-click="sortBy('vehicle_id');loadRows();" class="mouse-pointer font-weight-bold" >
												<span class="m-1">Vehicle</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='vehicle_id'"></i> 
											</th>
											<th ng-click="sortBy('route_id');loadRows();" class="mouse-pointer font-weight-bold" >
												<span class="m-1">Route</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='route_id'"></i> 
											</th>
											<th class="font-weight-bold">Fare</th>
											<th class="font-weight-bold">Registered On</th>
											<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
							            </tr>
									</thead>
									<tbody>

										<tr ng-repeat="row in responseData.rows">
											<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
											<td ng-show="row.type==='<?php print $this->transport_passenger_m->TYPE_STUDENT;?>'">
												<div><a ng-href="<?php print $this->LIB_CONT_ROOT.'student/profile/'?>{{row.passenger_id}}" class="font-weight-semibold">
												<img ng-src="<?php print $this->UPLOADS_ROOT;?>images/student/profile/{{row.image}}" class="rounded-circle m-1" width="32" height="32" alt="">
	        									{{row.passenger_name}}</a></div>
											</td>
											<td ng-show="row.type==='<?php print $this->transport_passenger_m->TYPE_STAFF;?>'">
												<div><a ng-href="<?php print $this->LIB_CONT_ROOT.'staff/profile/'?>{{row.passenger_id}}" class="font-weight-semibold">
												<img ng-src="<?php print $this->UPLOADS_ROOT;?>images/staff/profile/{{row.image}}" class="rounded-circle m-1" width="32" height="32" alt="">
	        									{{row.passenger_name}}</a></div>
											</td>
											<td>{{row.vehicle | uppercase}}</td>
											<td>{{row.route}}</td>
											<td><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL]; ?> {{row.fee}}</td>
											<td>{{row.date}}</td>
											<td>
												<div class="list-icons float-right">
													<div class="btn-group list-icons-item dropdown">
								                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>

														<div class="dropdown-menu dropdown-menu-right">
															<?php if($this->LOGIN_USER->prm_transport>1){ ?>
															<a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#edit" ng-click="selectRow(row)">
																<i class="icon-compose"></i> Update 
															</a>
															<?php } ?>
															<?php if($this->LOGIN_USER->prm_transport>2){ ?>
															<div class="dropdown-divider"></div>
															<a class="dropdown-item" ng-click="delRow(row)"><i class="icon-cross text-danger"></i> Remove</a>
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
			    <div class="tab-pane fade <?php print $tab=='add_staff' ? 'active show': '';?>" id="add_staff">
					
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Register Staff</h4>
							<span class="text-muted">Register here the staff into transport system so that they can avail the transport fascility.</span>
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
					  						<a class="btn btn-link text-info" <?php print $this->MODAL_OPTIONS;?> data-target="#register-staff" ng-click="selectRow(row);loadAllRoutes();"> Register <i class="icon-checkmark-circle2"></i></a>
					  					</td> 
										
						            </tr>


								</tbody>
							</table>
							<br><br><br>

						</div>
					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='add_student' ? 'active show': '';?>" id="add_student">
					
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Register Student</h4>
							<span class="text-muted">Register here the students into transport system so that they can avail the transport fascility.</span>
						</div>
						<div class="card-body">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-3">
										<select class="form-control select-search" ng-model="filter.class" data-fouc>
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
					  						<a class="btn btn-link text-info" <?php print $this->MODAL_OPTIONS;?> data-target="#register-student" ng-click="selectRow(row);;loadAllRoutes();"> Register <i class="icon-checkmark-circle2"></i></a>
					  					</td> 
										
						            </tr>


								</tbody>
							</table>
							<br><br><br>

						</div>
					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='routes' ? 'active show': '';?>" id="routes">
					
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Transport Routes</h4>
							<span class="text-muted">Routes helps manage the transportation by grouping passengers as per their home stations. You can create as many routes as your want.</span>
						</div>
						<div class="card-body">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-10">
										<div class="input-group mb-4">
											<div class="form-group-feedback form-group-feedback-left">
												<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadRoutes()">
												<div class="form-control-feedback form-control-feedback-lg">
													<i class="icon-search4 text-muted"></i>
												</div>
											</div>

											<div class="input-group-append">
												<button ng-click="loadRoutes()" class="btn btn-success btn-lg">
												<span class="font-weight-bold"> Search</span>
												<i ng-class="{'icon-spinner2':appConfig.btnClickedSearch, 'spinner':appConfig.btnClickedSearch, 'icon-circle-right2':!appConfig.btnClickedSearch}" class=" ml-2"></i>
												</button>
											</div>
										</div>
									</div>

									<?php if($this->LOGIN_USER->prm_transport>1){ ?>
									<div class="col-sm-2">
										<a class="btn btn-success text-white" <?php print $this->MODAL_OPTIONS;?> data-target="#add-route">
												<i class="icon-plus-circle2 mr-2"></i> New Route</a>
									</div>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th class="font-weight-bold">Route / Stations</th>
										<th class="font-weight-bold">Fare</th>
										<th class="font-weight-bold">vehicles</th>
										<th class="font-weight-bold">Pessengers</th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.name}}</td>
										<td>{{row.fare}}</td>
										<td>{{row.vehicles}}</td>  
					  					<td>{{row.passengers}}</td> 
										<td>
											<div class="list-icons float-right">
												<div class="btn-group list-icons-item dropdown">
							                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
													<div class="dropdown-menu dropdown-menu-right">

														<?php if($this->LOGIN_USER->prm_transport>1){ ?>
														<a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#edit-route" ng-click="selectRow(row)">
															<i class="icon-compose"></i> Update 
														</a>
														<?php } ?>
														<?php if($this->LOGIN_USER->prm_transport>2){ ?>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" ng-click="delRoute(row)"><i class="icon-cross text-danger"></i> Remove</a>
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
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadRoutes()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadRoutes()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='vehicles' ? 'active show': '';?>" id="vehicles">
					
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Transport Vehicles</h4>
							<span class="text-muted">Vehicles helps in transportation to carry or drop passengers to/from their destinations. You can register as many vehicles as required.</span>
						</div>
						<div class="card-body">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-10">
										<div class="input-group mb-4">
											<div class="form-group-feedback form-group-feedback-left">
												<input type="text" class="form-control form-control-lg alpha-grey" ng-model="searchText" placeholder="Search" ng-keyup="loadVehicles()">
												<div class="form-control-feedback form-control-feedback-lg">
													<i class="icon-search4 text-muted"></i>
												</div>
											</div>

											<div class="input-group-append">
												<button ng-click="loadVehicles()" class="btn btn-success btn-lg">
												<span class="font-weight-bold"> Search</span>
												<i ng-class="{'icon-spinner2':appConfig.btnClickedSearch, 'spinner':appConfig.btnClickedSearch, 'icon-circle-right2':!appConfig.btnClickedSearch}" class=" ml-2"></i>
												</button>
											</div>
										</div>
									</div>
									<?php if($this->LOGIN_USER->prm_transport>1){ ?>
									<div class="col-sm-2">
										<a class="btn btn-success text-white" <?php print $this->MODAL_OPTIONS;?> data-target="#add-vehicle">
												<i class="icon-plus-circle2 mr-2"></i> New Vehicle</a>
									</div>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th class="font-weight-bold">Vehicle</th>
										<th class="font-weight-bold">Driver</th>
										<th class="font-weight-bold">Seating Capacity</th>
										<th class="font-weight-bold">Passengers</th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.reg_no}}</td>
										<td>{{row.driver}}</td>  
					  					<td>{{row.capacity}} x {{row.routes}} routes = {{row.capacity*row.routes}} seats</td> 
					  					<td>{{row.passengers}}</td> 
										<td>
											<div class="list-icons float-right">
												<div class="btn-group list-icons-item dropdown">
							                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
										
													<div class="dropdown-menu dropdown-menu-right">

														<?php if($this->LOGIN_USER->prm_transport>1){ ?>
														<a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#edit-vehicle" ng-click="selectRow(row)">
															<i class="icon-compose"></i> Update 
														</a>
														<a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#assign-vehicle-route" ng-click="selectRow(row);loadAllRoutes();loadVehicleRoutes();">
															<i class="icon-road"></i> Assign Route 
														</a>
														<?php } ?>
														
														<a class="dropdown-item" ng-href="<?php print $this->CONT_ROOT.'printing/list/?vehicle=';?>{{row.mid}}"><i class="icon-printer"></i> Passengers List</a>
														<?php if($this->LOGIN_USER->prm_transport>2){ ?>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" ng-click="delVehicle(row)"><i class="icon-cross text-danger"></i> Remove</a>
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
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadVehicles()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadVehicles()">
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
						<div class="col-sm-4">
							<label class="text-muted">Passenger Type</label>
							<select class="form-control select" ng-model="filter.type" data-fouc>
							<option value="">All Types</option>
							<option value="<?php print $this->transport_passenger_m->TYPE_STAFF ?>">Staff</option>
							<option value="<?php print $this->transport_passenger_m->TYPE_STUDENT ?>">Students</option>
							</select>
						</div>	
						<div class="col-sm-4">
							<label class="text-muted">Routes</label>
							<select class="form-control select" ng-model="route.route_id" ng-change="loadRouteVehicles()" data-fouc>
								<option value="">Any Route</option>
								<option ng-repeat="row in allRoutes.rows" value="{{row.mid}}">{{row.name}}</option>
							</select>
						</div>					
						<div class="col-sm-4" ng-show="route.route_id.length>0">
							<label class="text-muted">Vehicle</label>
							<select class="form-control select" ng-model="vehicle.vehicle_id" data-fouc>
								<option value="">Any Vehicle</option>
								<option ng-repeat="row in routeVehicles.rows" value="{{row.vehicle_id}}">{{row.reg_no+'('+row.driver+')'}}</option>
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


<!-- add route modal -->
<div id="add-route" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Add new route in system</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Create new route in the system...</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Route Information</h6>
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
									<label class="text-muted">Route Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g station1-station2-station3" ng-model="route.name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Route Fare</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g Route Fare" ng-model="route.fare">
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
				<button ng-click="saveRoute()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /add route modal -->

<!-- edit route modal -->
<div id="edit-route" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">update route  ({{selectedRow.name}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">update route in ths sytem...</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Route Information</h6>
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
									<label class="text-muted">Route Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g 50" ng-model="selectedRow.name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Route Fare</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g Route Fare" ng-model="selectedRow.fare">
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
				<button ng-click="updateRoute()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /edit route modal -->


<!-- add vehicle modal -->
<div id="add-vehicle" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Add new vehicle in system</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Create new vehicle record in the system...</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Vehicle Information</h6>
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
									<label class="text-muted">Regisration Number <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Registration Numebr" ng-model="vehicle.reg_no">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Driver Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Driver Name" ng-model="vehicle.driver">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user"></i>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<label class="text-muted">Seating Capacity <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Total Seats " ng-model="vehicle.capacity">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-bus"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Ownership</label>
									<select class="form-control select" ng-model="vehicle.owner" data-fouc>
										<option value="">Select Vehicle Ownership</option>
										<option value="<?php print  $this->transport_vehicle_m->OWNER_ORG; ?>">Organizaiton own vehicle</option>
										<option value="<?php print  $this->transport_vehicle_m->OWNER_OTHER; ?>">Thirdparty own this vehicle</option>
									</select>
								</div>
							</div>
							<div class="row" ng-show="vehicle.owner==='<?php print $this->transport_vehicle_m->OWNER_OTHER;?>'">
								<div class="col-sm-6">
									<label class="text-muted">Contract with thirdparty</label>
									<select class="form-control select" ng-model="vehicle.contract" data-fouc>
										<option value="">Select contract type</option>
										<option value="<?php print  $this->transport_vehicle_m->CONTRACT_FIXED; ?>">Organizaiton pay fixed amount to owner monthly</option>
										<option value="<?php print  $this->transport_vehicle_m->CONTRACT_PERCENTAGE; ?>">Thirdparty get a percentage from each passenger fare</option>
									</select>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Enter Value </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g 50" ng-model="vehicle.amount">
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
				<button ng-click="saveVehicle()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /add vehicle modal -->

<!-- edit vehicle modal -->
<div id="edit-vehicle" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">update vehicle  ({{selectedRow.reg_no}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">update vehicle in ths sytem...</p>
				
				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Vehicle Information</h6>
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
									<label class="text-muted">Regisration Number <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Registration Numebr" ng-model="selectedRow.reg_no">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Driver Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Driver Name" ng-model="selectedRow.driver">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user"></i>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<label class="text-muted">Seating Capacity <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Total Seats " ng-model="selectedRow.capacity">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-bus"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Ownership</label>
									<select class="form-control select" ng-model="selectedRow.owner" data-fouc>
										<option value="">Select Vehicle Ownership</option>
										<option value="<?php print  $this->transport_vehicle_m->OWNER_ORG; ?>">Organizaiton own vehicle</option>
										<option value="<?php print  $this->transport_vehicle_m->OWNER_OTHER; ?>">Thirdparty own this vehicle</option>
									</select>
								</div>
							</div>
							<div class="row" ng-show="selectedRow.owner==='<?php print $this->transport_vehicle_m->OWNER_OTHER;?>'">
								<div class="col-sm-6">
									<label class="text-muted">Contract with thirdparty</label>
									<select class="form-control select" ng-model="selectedRow.contract" data-fouc>
										<option value="">Select contract type</option>
										<option value="<?php print  $this->transport_vehicle_m->CONTRACT_FIXED; ?>">Organizaiton pay fixed amount to owner monthly</option>
										<option value="<?php print  $this->transport_vehicle_m->CONTRACT_PERCENTAGE; ?>">Thirdparty get a percentage from each passenger fare</option>
									</select>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Enter Value </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g 50" ng-model="selectedRow.amount">
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
				<button ng-click="updateVehicle()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /edit vehicle modal -->

<!-- assign vehicle route modal -->
<div id="assign-vehicle-route" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Assign Route To ({{selectedRow.reg_no}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">assign route to this vehicle...</p>
				

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
								<div class="col-sm-10">
									<label class="text-muted">Select Route from below list</label>
									<select class="form-control select-search" ng-model="route.route_id"data-fouc>
										<option value="">Select Route</option>
										<option ng-repeat="row in allRoutes.rows" value="{{row.mid}}">{{row.name}}</option>
									</select>
								</div>
								<div class="col-sm-2">
									<label class="text-muted">click to assign</label>
									<a class="btn btn-success text-white" ng-click="saveVehicleRoute()">
											<i class="icon-checkmark-circle2 mr-2"></i> Assign Route</a>
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
								<th class="font-weight-bold">Route</th>
								<th class="font-weight-bold">Passengers</th>
								<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
				            </tr>
						</thead>
						<tbody>

							<tr ng-repeat="row in vehicleRoutes.rows">
								<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
								<td>{{row.route}}</td>  
								<td>{{row.passengers}}</td>
			  					<td>
			  						<a class="btn btn-link text-danger" ng-click="delVehicleRoute(row)"> Remove <i class="icon-cross"></i></a>
			  					</td> 
								
				            </tr>


						</tbody>
					</table>
				</div>



			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /assign vehicle route modal -->

<!-- register staff vehicle modal -->
<div id="register-staff" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Register Staff Member ({{selectedRow.name}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">register staff member into transportation system...</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Registration Information</h6>
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
									<label class="text-muted">Select Route from below list</label>
									<select class="form-control select-search" ng-model="route.route_id" ng-change="loadRouteVehicles()" data-fouc>
										<option value="">Select Route</option>
										<option ng-repeat="row in allRoutes.rows" value="{{row.mid}}">{{row.name}}</option>
									</select>
								</div>
								<div class="col-sm-6" ng-show="route.route_id.length>0">
									<label class="text-muted">Select vehicle</label>
									<select class="form-control select-search" ng-model="vehicle.vehicle_id" data-fouc>
										<option value="">Select Route</option>
										<option ng-repeat="row in routeVehicles.rows" value="{{row.vehicle_id}}">{{row.reg_no+'('+row.driver+')'}}</option>
									</select>
								</div>
								<div class="col-sm-8">
									<label class="text-muted">Transport Fare</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Fare" ng-model="registration.fare">
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
				<button ng-click="registerStaff()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /register staff modal -->

<!-- register student vehicle modal -->
<div id="register-student" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Register Student Member ({{selectedRow.name}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">register student member into transportation system...</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Registration Information</h6>
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
									<label class="text-muted">Select Route from below list</label>
									<select class="form-control select-search" ng-model="route.route_id" ng-change="loadRouteVehicles()" data-fouc>
										<option value="">Select Route</option>
										<option ng-repeat="row in allRoutes.rows" value="{{row.mid}}">{{row.name}}</option>
									</select>
								</div>
								<div class="col-sm-6" ng-show="route.route_id.length>0">
									<label class="text-muted">Select vehicle</label>
									<select class="form-control select-search" ng-model="vehicle.vehicle_id" data-fouc>
										<option value="">Select Vehicle</option>
										<option ng-repeat="row in routeVehicles.rows" value="{{row.vehicle_id}}">{{row.reg_no+'('+row.driver+')'}}</option>
									</select>
								</div>
								<div class="col-sm-8">
									<label class="text-muted">Transport Fare</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Fare" ng-model="registration.fare">
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
				<button ng-click="registerStudent()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /register student modal -->


<!-- update fare modal -->
<div id="edit" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update Fare of Member ({{selectedRow.passenger_name}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">update fare of member...</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Fare Information</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>

						<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-8">
									<label class="text-muted">Transport Fare</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Fare" ng-model="selectedRow.fee">
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
				<button ng-click="updateRow()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /update fare modal -->



</div>
<!-- /main content -->
<!-- ********************************************************************** -->
<!-- ///////////////////////////////SCRIPTS//////////////////////////////// -->
<!-- ********************************************************************** -->
<!-- <script type="text/javascript">
	
$(function(){
   
});
</script> -->