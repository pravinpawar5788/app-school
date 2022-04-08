<?php
$bg=array('bg-violet','bg-success','bg-info','bg-warning','bg-primary','bg-blue','bg-teal','bg-green','bg-slate','bg-indigo');
$teals=array(2,3,4,6,7);
$total_bg=count($bg)-1;
$total_teals=count($teals)-1;
/////////////////////////////////////////////////////////////////////////////////////////////
// $sessions=$this->session_m->get_rows(array(),array('orderby'=>'status ASC,mid DESC') );
$classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'title ASC') );
// $classes_array=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
// $sessions_array=$this->session_m->get_values_array('mid','title',array());
$types=$this->lib_book_m->get_book_cats();

$total_books=$this->lib_book_m->get_rows(array('campus_id'=>$this->CAMPUSID),'',true);
$total_books_issued=$this->lib_book_issue_m->get_rows(array('campus_id'=>$this->CAMPUSID),'',true);
$total_books_late=$this->lib_book_issue_m->get_rows(array('campus_id'=>$this->CAMPUSID,'due_jd <'=>$this->lib_book_issue_m->todayjd),'',true);
$today_issued=$this->lib_book_issue_m->get_rows(array('campus_id'=>$this->CAMPUSID,'jd'=>$this->lib_book_issue_m->todayjd),'',true);

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
					<a class="breadcrumb-item">Library</a>
					<span class="breadcrumb-item active">Library Books</span>
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
						<a href="<?php print $this->CONT_ROOT.'printing/list/';?>" class="dropdown-item"><i class="icon-vcard"></i> Books List</a>
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
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Library</span> - Books</h4>
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
							Library Books
						</a>
					</li>

					<?php if($this->LOGIN_USER->prm_library>1){ ?>
					<li class="nav-item">
						<a href="#add" class="navbar-nav-link" data-toggle="tab">
							<i class="icon-plus-circle2 mr-2" style="color:<?php print $clr;?>;" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;"></i>
							Register Book
						</a>
					</li>
					<?php } ?>
					<li class="nav-item">
						<a href="#history" class="navbar-nav-link" data-toggle="tab" ng-click="loadHistory()" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-reading mr-2" style="color:<?php print $clr;?>;"></i>
							Issued Books
						</a>
					</li>
					<?php if($this->LOGIN_USER->prm_library>2){ ?>
					<li class="nav-item">
						<a href="#analytics" class="navbar-nav-link" data-toggle="tab" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-stats-bars2 mr-2" style="color:<?php print $clr;?>;"></i>
							Analytics
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
							<h4 class="card-title">Library Book</h4>
							<span class="text-muted">Search library books.</span>
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
									<div class="col-sm-2">
										<select class="form-control select" ng-model="filter.filter" data-fouc>
										<option value="">Search By Anything</option>
										<option value="isbn">ISBN</option>
										<option value="accession_number">Accession Number</option>
										<option value="placement_number">Placement Number</option>
										</select>
									</div>
									<div class="col-sm-4">
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
												<i class="icon-filter3 mr-2"></i> Advance Search</a>
												<a class="btn btn-danger text-white" ng-show="showFilter()" ng-click="clearFilter();loadRows();">
														<i class="icon-diff-removed"></i></a>
												<div class="list-icons m-2" ng-show="showFilter()" >
													<div class="list-icons-item dropdown">
														<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown">
															<i class="icon-menu9 mr-5"></i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item" ng-href="<?php print $this->CONT_ROOT.'printing/list/?catagory='?>{{filter.type}}&filter={{filter.filter}}&search={{searchText}}">
																<i class="icon-printer mr-2"></i> Print List 
															</a>
														</div>
													</div>
												</div>
									</div>
								</div>
							</div>
							<div class="table-responsive">
								<p class="text-muted">Find below the library books registered in the system. Stock shows the item availability in the library. You can update the stock in update item.</p>
								<table class="table tasks-responsive table-lg">
									<thead>
										<tr>
											<th class="font-weight-bold">#</th>
											<th ng-click="sortBy('name');loadRows();" class="mouse-pointer font-weight-bold" >
												<span class="m-1">Title</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='name'"></i> 
											</th>
											<th ng-click="sortBy('author');loadRows();" class="mouse-pointer font-weight-bold">
												<span class="m-1">Author</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='author'"></i> 
											</th>
											<th ng-click="sortBy('publisher');loadRows();" class="mouse-pointer font-weight-bold">
												<span class="m-1">Publisher</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='publisher'"></i> 
											</th>
											<th ng-click="sortBy('ddc_number');loadRows();" class="mouse-pointer font-weight-bold">
												<span class="m-1">DDC Number</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='ddc_number'"></i> 
											</th>
											<th ng-click="sortBy('placement_number');loadRows();" class="mouse-pointer font-weight-bold">
												<span class="m-1">Placement Number</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='placement_number'"></i> 
											</th>
											<th ng-click="sortBy('stock');loadRows();" class="mouse-pointer font-weight-bold">
												<span class="m-1">Stock</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='stock'"></i> 
											</th>
											<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
							            </tr>
									</thead>
									<tbody>

										<tr ng-repeat="row in responseData.rows">
											<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
											<td>{{row.name}}</td>
						  					<td>{{row.author}}</td>  
						  					<td>{{row.publisher}}</td> 
						  					<td>{{row.ddc_number}}</td> 
						  					<td>{{row.placement_number}}</td> 
						  					<td>{{row.stock}}</td>
											<td>
												<div class="list-icons float-right">
													<div class="btn-group list-icons-item dropdown">
								                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
								                    	
														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#view" ng-click="selectRow(row)">
																<i class="icon-eye"></i> View Information
															</a>

															<?php if($this->LOGIN_USER->prm_library>1){ ?>
															<a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#edit" ng-click="selectRow(row)">
																<i class="icon-compose"></i> Update Book
															</a>
															<?php } ?>
															<div class="dropdown-divider"></div>
															<a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#load-student" ng-click="selectRow(row)">
																<i class="icon-user"></i> Issue to Student
															</a>
															<a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#load-staff" ng-click="selectRow(row)">
																<i class="icon-user-tie"></i> Issue to Staff
															</a>

															<?php if($this->LOGIN_USER->prm_library>2){ ?>
															<div class="dropdown-divider"></div>
															<a class="dropdown-item" ng-click="delRow(row)"><i class="icon-cross"></i> Remove Book</a>
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
							<h5 class="card-title">Register Library Book
							<span class="d-block font-size-base text-muted">Library helps you manage your library books/stocks. System will create library reports at the end of month. 
							</span>
							</h5>
						</div>

						<div class="card-body">
							<div class="form-group" enter-as-tab>
							<div class="row">
								<div class="col-sm-3">
									<label class="text-muted">Book Category<span class="text-danger"> *</span></label>
									<select class="form-control select" ng-model="item.type" data-fouc>
									<option value="">Selet Book Category</option>
									<?php foreach($types as $key => $value){?>
									<option value="<?php print $key;?>" /><?php print $value;?>
									<?php }?>
									</select>
								</div>
								<div class="col-md-5">
									<label class="text-muted">Name<span class="text-danger"> *</span></label>
									<input type="text" class="form-control" placeholder="Book Name" ng-model="item.name">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Sub title</label>
									<input type="text" class="form-control" placeholder="Sub Title" ng-model="item.sub_title">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Author</label>
									<input type="text" class="form-control" placeholder="Author" ng-model="item.author">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Sub Author</label>
									<input type="text" class="form-control" placeholder="Sub Author" ng-model="item.sub_author">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Statement</label>
									<input type="text" class="form-control" placeholder="Statement Title" ng-model="item.statement">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Publisher</label>
									<input type="text" class="form-control" placeholder="Publisher" ng-model="item.publisher">
								</div>
								<div class="col-md-4">
									<label class="text-muted">ISBN</label>
									<input type="text" class="form-control" placeholder="ISBN" ng-model="item.isbn">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Accession Number</label>
									<input type="text" class="form-control" placeholder="Accession Number" ng-model="item.accession_number">
								</div>
								<div class="col-md-4">
									<label class="text-muted">DDC Number</label>
									<input type="text" class="form-control" placeholder="ddc_number" ng-model="item.ddc_number">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Placement Number</label>
									<input type="text" class="form-control" placeholder="Placement Number" ng-model="item.placement_number">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Publishing Place</label>
									<input type="text" class="form-control" placeholder="Place Published" ng-model="item.place">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Volume</label>
									<input type="text" class="form-control" placeholder="Volume" ng-model="item.volume">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Binding</label>
									<input type="text" class="form-control" placeholder="Binding" ng-model="item.binding">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Publishing Year</label>
									<input type="text" class="form-control" placeholder="Year" ng-model="item.year">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Pages</label>
									<input type="text" class="form-control" placeholder="Pages" ng-model="item.pages">
								</div>
								<div class="col-md-4">
									<label class="text-muted">Available Stock</label>
									<input type="text" class="form-control" placeholder="Stock" ng-model="item.stock">
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
			    <div class="tab-pane fade <?php print $tab=='history' ? 'active show': '';?>" id="history">
					
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">Book Issue History</h4>
							<span class="text-muted">Find below the books issued to students and staffs.</span>
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
										<label class="text-muted"><input type="checkbox" ng-model="filter.late" class="checkbox-inline" ng-change="loadHistory()" /> Over Due Date</label>
									</div>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th class="font-weight-bold">User</th>
										<th class="font-weight-bold">User ID</th>
										<th class="font-weight-bold">Book</th>
										<th class="font-weight-bold">Issued On</th>
										<th class="font-weight-bold">Due Date</th>
										<th class="text-center text-muted" style="width: 50px;"><i class="icon-checkmark3 mr-5"></i></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td ng-show="row.user_type==='<?php print $this->lib_book_issue_m->USER_STUDENT;?>'">
											<div><a ng-href="<?php print $this->LIB_CONT_ROOT.'student/profile/'?>{{row.user_id}}" class="font-weight-semibold">
											<img ng-src="<?php print $this->UPLOADS_ROOT;?>images/student/profile/{{row.user_image}}" class="rounded-circle m-1" width="32" height="32" alt="">
        									{{row.user}}</a></div>
										</td>
										<td ng-show="row.user_type==='<?php print $this->lib_book_issue_m->USER_STAFF;?>'">
											<div><a ng-href="<?php print $this->LIB_CONT_ROOT.'staff/profile/'?>{{row.user_id}}" class="font-weight-semibold">
											<img ng-src="<?php print $this->UPLOADS_ROOT;?>images/staff/profile/{{row.user_image}}" class="rounded-circle m-1" width="32" height="32" alt="">
        									{{row.user}}</a></div>
										</td>
										<td>{{row.user_pid}}</td>
					  					<td>{{row.book}}</td> 
					  					<td>{{row.date}}</td> 
					  					<td>{{row.due_date}}</td> 
										<td class="text-center">
											<div class="list-icons">
												<div class="list-icons-item dropdown">
													<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9 mr-5"></i></a>
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item" ng-click="receiveBook(row)"><i class="icon-checkmark-circle2"></i> Receive Book</a>
														<!-- <div class="dropdown-divider"></div>
														<a class="dropdown-item" <?php print $this->MODAL_OPTIONS;?> data-target="#sms" ng-click="selectRow(row)">
															<i class="icon-envelope"></i> Send SMS
														</a> -->
													</div>
												</div>
											</div>
											</td>
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

			    <div class="tab-pane fade <?php print $tab=='analytics' ? 'active show': '';?>" id="analytics">
					
					<!-- Quick stats boxes -->
						<div class="row">
							<div class="col-sm-3">
								<!-- Members online -->
								<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
								<div class="card <?php print $bg_color.$t_color;?>">
									<div class="card-body">
										<div class="d-flex">
											<h3 class="font-weight-semibold mb-0"><?php print $total_books;?></h3>
											<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto">As of <?php print $this->lib_book_m->date;?></span>
					                	</div>					                	
					                	<div>
											<strong>Total Books</strong>
											<div class="font-size-sm opacity-85">Total books registered</div>
										</div>
									</div>

									<div class="container-fluid">
										<div id="members-online"></div>
									</div>
								</div>
								<!-- /members online -->
							</div>
							<div class="col-sm-3">
								<!-- Members online -->
								<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
								<div class="card <?php print $bg_color.$t_color;?>">
									<div class="card-body">
										<div class="d-flex">
											<h3 class="font-weight-semibold mb-0"><?php print $total_books_issued;?></h3>
											<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto">As of <?php print $this->lib_book_m->date;?></span>
					                	</div>					                	
					                	<div>
											<strong>Issued Books</strong>
											<div class="font-size-sm opacity-85">Total books issued</div>
										</div>
									</div>

									<div class="container-fluid">
										<div id="members-online"></div>
									</div>
								</div>
								<!-- /members online -->
							</div>
							<div class="col-sm-3">
								<!-- Members online -->
								<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
								<div class="card <?php print $bg_color.$t_color;?>">
									<div class="card-body">
										<div class="d-flex">
											<h3 class="font-weight-semibold mb-0"><?php print $total_books_late;?></h3>
											<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto">As of <?php print $this->lib_book_m->date;?></span>
					                	</div>					                	
					                	<div>
											<strong>Overdue Books</strong>
											<div class="font-size-sm opacity-85">Total books not yet received back</div>
										</div>
									</div>

									<div class="container-fluid">
										<div id="members-online"></div>
									</div>
								</div>
								<!-- /members online -->
							</div>
							<div class="col-sm-3">
								<!-- Members online -->
								<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
								<div class="card <?php print $bg_color.$t_color;?>">
									<div class="card-body">
										<div class="d-flex">
											<h3 class="font-weight-semibold mb-0"><?php print $today_issued;?></h3>
											<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto"><?php print $this->lib_book_m->date;?></span>
					                	</div>					                	
					                	<div>
											<strong>Today Issued</strong>
											<div class="font-size-sm opacity-85">Total books issued today</div>
										</div>
									</div>

									<div class="container-fluid">
										<div id="members-online"></div>
									</div>
								</div>
								<!-- /members online -->
							</div>


						</div>
						<!-- /quick stats boxes -->

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
							<label class="text-muted">Year</label>
							<select class="form-control select" ng-model="filter.year" data-fouc>
								<option value="">Select Year</option>
								<?php 
								$year=date('Y');
								while ($year > 1957) {
									?>
									<option value="<?php print $year; ?>"><?php print $year; ?></option>
									<?php
									$year--;
								}
								 ?>
							</select>
						</div>					
						<div class="col-sm-6">
							<label class="text-muted">Having Stock</label>
							<select class="form-control select" ng-model="filter.stock" data-fouc>
								<option value="">Any Stock</option>
								<?php 
								$stk=4;
								while ($stk <30) {
									?>
									<option value="<?php print $stk; ?>">More then <?php print $stk; ?></option>
									<?php
									$stk+=4;
								}
								 ?>
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
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update Book ({{selectedRow.name}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Update here the library book...</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Book Information</h6>
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
									<label class="text-muted">Book Category<span class="text-danger"> *</span></label>
									<select class="form-control select" ng-model="selectedRow.catagory" data-fouc>
									<option value="">Selet Book Category</option>
									<?php foreach($types as $key => $value){?>
									<option value="<?php print $key;?>" /><?php print $value;?>
									<?php }?>
									</select>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Name <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Book Name" ng-model="selectedRow.name">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Sub Title</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Sub Title" ng-model="selectedRow.sub_title">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Publisher</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Publisher" ng-model="selectedRow.publisher">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user"></i>
										</div>
									</div>
								</div>

							</div>
							<div class="row">	
								<div class="col-sm-3">
									<label class="text-muted">Author </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Author" ng-model="selectedRow.author">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user-tie"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Sub Author </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Sub Author" ng-model="selectedRow.sub_author">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-user"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Statement</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="statement" ng-model="selectedRow.statement">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-notebook"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Publishing Place</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Publishing Place" ng-model="selectedRow.place">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-location3"></i>
										</div>
									</div>
								</div>

							</div>
							<div class="row">	
								<div class="col-sm-3">
									<label class="text-muted">Publishing Year </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Publishing Year" ng-model="selectedRow.year">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-calendar"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">DDC Number </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="DDC Number" ng-model="selectedRow.ddc_number">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-notebook"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Accession Number</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Accession Number" ng-model="selectedRow.accession_number">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-cabinet"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Placement Number</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Placement Number" ng-model="selectedRow.placement_number">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-versions"></i>
										</div>
									</div>
								</div>

							</div>
							<div class="row">	
								<div class="col-sm-3">
									<label class="text-muted">Volume </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Volume" ng-model="selectedRow.volume">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-notebook"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Binding </label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Binding" ng-model="selectedRow.binding">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-git"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Pages</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Pages" ng-model="selectedRow.pages">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-stack4"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="text-muted">Available Stock</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Availeable Stock" ng-model="selectedRow.stock">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-loop3"></i>
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
<!-- /edit modal -->


<!-- edit modal -->
<div id="view" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Information of Book (<strong>{{selectedRow.name}}</strong>)</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<div class="row">
					<div class="col-sm-4">
						<span class="text-muted"> <span >Book Name : </span>
							<span class="font-weight-bold">{{selectedRow.name}}</span></span><hr>
						<span class="text-muted"> <span >Sub Title : </span>
							<span class="font-weight-bold">{{selectedRow.sub_title}}</span></span><hr>
						<span class="text-muted"> <span >Publisher : </span>
							<span class="font-weight-bold">{{selectedRow.publisher}}</span></span><hr>
						<span class="text-muted"> <span >Publishing Place : </span>
							<span class="font-weight-bold">{{selectedRow.place}}</span></span><hr>
						<span class="text-muted"> <span >Statement : </span>
							<span class="font-weight-bold">{{selectedRow.statement}}</span></span><hr>
						
					</div>
					<div class="col-sm-4">
						<span class="text-muted"> <span >Author : </span>
							<span class="font-weight-bold">{{selectedRow.author}}</span></span><hr>
						<span class="text-muted"> <span >Sub Author : </span>
							<span class="font-weight-bold">{{selectedRow.sub_author}}</span></span><hr>
						<span class="text-muted"> <span >Volume : </span>
							<span class="font-weight-bold">{{selectedRow.volume}}</span></span><hr>
						<span class="text-muted"> <span >Binding : </span>
							<span class="font-weight-bold">{{selectedRow.binding}}</span></span><hr>
						<span class="text-muted"> <span >Pages : </span>
							<span class="font-weight-bold">{{selectedRow.pages}}</span></span><hr>
								
					</div>
								
					<div class="col-sm-4">
						<span class="text-muted"> <span >Available Stock : </span>
							<span class="font-weight-bold">{{selectedRow.stock}}</span></span><hr>
						<span class="text-muted"> <span >Publisher Year : </span>
							<span class="font-weight-bold">{{selectedRow.year}}</span></span><hr>
						<span class="text-muted"> <span >Accession Number : </span>
							<span class="font-weight-bold">{{selectedRow.accession_number}}</span></span><hr>
						<span class="text-muted"> <span >DDC Number : </span>
							<span class="font-weight-bold">{{selectedRow.ddc_number}}</span></span><hr>
						<span class="text-muted"> <span >Placement Number : </span>
							<span class="font-weight-bold">{{selectedRow.placement_number}}</span></span><hr>
					</div>
				</div>


			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /edit modal -->

<!-- load staff modal -->
<div id="load-staff" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Issue book (<strong>{{selectedRow.name}}</strong>) to staff</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">
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
			  						<a class="btn btn-link text-success" <?php print $this->MODAL_OPTIONS;?> data-target="#issue-item-staff" ng-click="selectMember(row)"> Issue Book <i class="icon-checkmark-circle2"></i></a>
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
<!-- /load staff modal -->

<!-- issue item modal -->
<div id="issue-item-staff" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-info">
				<h6 class="modal-title">Issue Book(<strong>{{selectedRow.name}}</strong>) to <strong>{{selectedMember.name}}</strong></h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Please provide the issuence information below to issue the book...</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Issuence Information</h6>
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
									<label class="text-muted">For how many days you want to issue the book?<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g 50" ng-model="issue.days">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-calendar"></i>
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
					<span class="font-weight-bold"> Issue Book</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /issue item modal -->

<!-- load student modal -->
<div id="load-student" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Issue book (<strong>{{selectedRow.name}}</strong>) to student</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">
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
			  						<a class="btn btn-link text-info" <?php print $this->MODAL_OPTIONS;?> data-target="#issue-item-student" ng-click="selectMember(row);"> Issue Items <i class="icon-checkmark-circle2"></i></a>
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
<!-- /load student modal -->

<!-- issue item modal -->
<div id="issue-item-student" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-info">
				<h6 class="modal-title">Issue Book(<strong>{{selectedRow.name}}</strong>) to <strong>{{selectedMember.name}}</strong></h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Please provide the issuence information below to issue the book...</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Issuence Information</h6>
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
									<label class="text-muted">For how many days you want to issue the book?<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="E.g 50" ng-model="issue.days">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-calendar"></i>
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
				<button ng-click="issueItemStudent()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Issue Book</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /issue item modal -->




</div>
<!-- /main content -->
<!-- ********************************************************************** -->
<!-- ///////////////////////////////SCRIPTS//////////////////////////////// -->
<!-- ********************************************************************** -->
<!-- <script type="text/javascript">
	
$(function(){
   
});
</script> -->