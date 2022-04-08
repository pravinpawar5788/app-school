<?php

$bg=array('bg-violet','bg-success','bg-info','bg-warning','bg-primary','bg-blue','bg-teal','bg-green','bg-slate','bg-indigo');
$teals=array(2,3,4,6,7);
$total_bg=count($bg)-1;
$total_teals=count($teals)-1;
/////////////////////////////////////////////////////////////////////////////////////////////
$filter=array('user_id'=>$record->mid);
$total_books_issued=$this->book_issued_m->get_rows($filter,'',true);
$total_books_read=$this->book_issue_history_m->get_rows($filter,'',true);
$total_books_overdue=$this->book_issued_m->get_rows(array('user_id'=>$record->mid,'due_jd'<$this->book_issued_m->todayjd),'',true);

?>
<!-- Main content -->
<div class="content-wrapper" ng-controller="mozzCtrl"  ng-cloak>
	<!-- Page header -->
	<div class="page-header page-header-light border-bottom-0" ng-init="rid='<?php print $record->mid;?>'">

		<!-- Top breadcrumb line -->
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
					<a class="breadcrumb-item">Members</a>
					<span class="breadcrumb-item active">Profile</span>
				</div>

				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="breadcrumb justify-content-center">
					<a href="#" class="breadcrumb-elements-item sidebar-control sidebar-component-toggle d-none d-md-block">
						<i class="icon-drag-right mr-2"></i>Toggle Sidebar
					</a>


				</div>
			</div>
		</div>
		<!-- /breadcrumb line -->

	
		<!-- Page header content -->
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Members</span> - Profile</h4>
				<a class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="d-flex justify-content-center">
				</div>
			</div>
		</div>
		<!-- /page header content -->


		<!-- Profile navigation -->
		<div class="navbar navbar-expand-lg navbar-light bg-light">
			<div class="text-center d-lg-none w-100">
				<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-second">
					<i class="icon-menu7 mr-2"></i>
					Page navigation
				</button>
			</div>

			<div class="navbar-collapse collapse" id="navbar-second">
				<ul class="nav navbar-nav">
					<li class="nav-item">
						<a href="#profile" class="navbar-nav-link active" data-toggle="tab">
							<i class="icon-profile mr-2"></i>
							Dashboard
						</a>
					</li>
					<li class="nav-item">
						<a href="#books" class="navbar-nav-link" data-toggle="tab" ng-click="loadIssuedBooks()">
							<i class="icon-home mr-2"></i>
							Issued Books
						</a>
					</li>
					<li class="nav-item">
						<a href="#history" class="navbar-nav-link" data-toggle="tab" ng-click="loadHistory()">
							<i class="icon-history mr-2"></i>
							History
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
		<?php $this->load->view($this->LIB_VIEW_DIR.'includes/alert_inc');?>



		<!-- Inner container -->
		<div class="d-flex align-items-start flex-column flex-md-row">

			<!-- Left content -->
			<div class="tab-content w-100 overflow-auto order-2 order-md-1">


			    <div class="tab-pane fade <?php print empty($tab) || $tab=='profile' ? 'active show': '';?>" id="profile">
					<!-- Profile info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Dashboard</h5>
							<p class="text-muted">Member dashboard</p>
						</div>

						<div class="card-body">

							<!-- Dashboard content -->
							<div class="row">
								<div class="col-xl-12">
									<!-- Quick stats boxes -->
									<div class="row">
										<div class="col-sm-4">
											<!-- Members online -->
											<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
											<div class="card <?php print $bg_color.$t_color;?>">
												<div class="card-body">
													<div class="d-flex">
														<h3 class="font-weight-semibold mb-0"><?php print $total_books_issued;?></h3>
														<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto">Books</span>
								                	</div>					                	
								                	<div>
														<strong>Issued Books</strong>
													</div>
												</div>

												<div class="container-fluid">
													<div id="members-online"></div>
												</div>
											</div>
											<!-- /members online -->
										</div>
										<div class="col-sm-4">
											<!-- Members online -->
											<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
											<div class="card <?php print $bg_color.$t_color;?>">
												<div class="card-body">
													<div class="d-flex">
														<h3 class="font-weight-semibold mb-0"><?php print $total_books_overdue;?></h3>
														<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto">Books</span>
								                	</div>					                	
								                	<div>
														<strong>Overdue Books</strong>
													</div>
												</div>

												<div class="container-fluid">
													<div id="members-online"></div>
												</div>
											</div>
											<!-- /members online -->
										</div>
										<div class="col-sm-4">
											<!-- Members online -->
											<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
											<div class="card <?php print $bg_color.$t_color;?>">
												<div class="card-body">
													<div class="d-flex">
														<h3 class="font-weight-semibold mb-0"><?php print $total_books_read;?></h3>
														<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto">Books</span>
								                	</div>					                	
								                	<div>
														<strong>Total Read</strong>
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
							<!-- /dashboard content -->



						</div>
					</div>
					<!-- /profile info -->
		    	</div>

			    <div class="tab-pane fade <?php print $tab=='books' ? 'active show': '';?>" id="books">
					<!-- save info -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Issued Books 
							<span class="d-block font-size-base text-muted">Find below the list of books issued to this member.
							</span>
							</h5>
						</div>
						
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th class="font-weight-bold">
											<span class="m-1">Book Name</span></th>
										<th class="font-weight-bold">
											<span class="m-1">Issue Date</span></th>
										<th class="font-weight-bold">
											<span class="m-1">Return Date</span></th>
										<th class="font-weight-bold">
											<span class="m-1">Details</span></th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.book.title}}</td>
					  					<td>{{row.date}}</td>  
					  					<td>{{row.due_date}}</td>  
										<td><a data-target="#details" <?php print $this->MODAL_OPTIONS;?> ng-click="selectRow(row)"><i class="icon-eye"></i> Details</a>
										</td>
						            </tr>


								</tbody>
							</table>
							<br><br><br>

							<div>
							<button ng-show="appConfig.showBtnBack===true" class="btn btn-success m-3 font-weight-bold " ng-click="moveBack();loadIssuedBooks()">
							<i class="icon-arrow-left52"></i> Back Page</button>
							<button ng-show="appConfig.showBtnNext===true" class="btn btn-success m-3 float-right " ng-click="moveNext();loadIssuedBooks()">
							 Next Page <i class="icon-arrow-right6"></i></button>
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>
			    <div class="tab-pane fade <?php print $tab=='history' ? 'active show': '';?>" id="history">
					
					<!-- List table -->
					<div class="card">
						<div class="card-header bg-transparent">
							<h4 class="card-title">History</h4>
							<span class="text-muted">Find below the history of this member.</span>
						</div>
						<div class="table-responsive">
							<table class="table tasks-responsive table-lg">
								<thead>
									<tr>
										<th class="font-weight-bold">#</th>
										<th ng-click="sortBy('info');loadHistory();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Description</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='info'"></i> 
										</th>
										<th ng-click="sortBy('date');loadHistory();" class="mouse-pointer font-weight-bold">
											<span class="m-1">Date</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='date'"></i> 
										</th>
						            </tr>
								</thead>
								<tbody>

									<tr ng-repeat="row in responseData.rows">
										<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
										<td>{{row.message}}</td>
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
							<br><br><br>
							</div>
						</div>
					</div>
					<!-- /list table -->
		    	</div>




			</div>
			<!-- /left content -->


			<!-- Right sidebar component -->
			<div class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-right wmin-300 border-0 shadow-0 order-1 order-md-2 sidebar-expand-md">

				<!-- Sidebar content -->
				<div class="sidebar-content">

					<!-- User card -->
					<div class="card">
						<div class="card-body text-center">
							<div class="card-img-actions d-inline-block mb-3">
								<img class="img-fluid rounded-circle" src="<?php print $this->UPLOADS_ROOT.'images/profile/'.$record->image;?>" width="170" height="170" alt="">
							</div>
								
							<?php echo form_open_multipart($this->CONT_ROOT.'upload_picture');?> 
							<input type="hidden" name="usr" value="<?php print $record->mid;?>" />
							<input id="clientImageFile" type="file" name="file" style="display: none;" onchange="showname()">
							<input type="button" value="Browse" class="btn btn-light btn-sm" onclick="document.getElementById('clientImageFile').click();" />
							<button class="btn btn-success btn-sm"><i class="icon-upload position-right"></i><strong> Upload </strong></button>   
							<?php echo form_close(); ?>
							<span class="text-muted" id="selected-file"></span>
							<br>
				    		<h6 class="font-weight-semibold mb-0"><?php print ucwords(strtolower($record->name));?></h6>
				    		<span class="d-block text-muted">
				    			<span class="m-2">Email Address:</span><?php print ucwords(strtolower($record->email));?>
				    		</span>
				    		<span class="d-block text-muted">
				    			<span class="m-2">Mobile:</span><?php print ucwords(strtolower($record->mobile));?>
				    		</span>
				    		<span class="d-block text-muted">
				    			<span class="m-2">City:</span>
				    			<?php print ucwords(strtolower($record->city));?>,
				    			<?php print ucwords(strtolower($record->state));?>,
				    			<?php print ucwords(strtolower($record->country));?> 
				    		</span>
				    		<span class="d-block text-muted">
				    			<span class="m-2">Address:</span><?php print ucwords(strtolower($record->address));?>
				    		</span>
				    		<span class="d-block text-muted">
				    			<span class="m-2">Member Since:</span><?php print ucwords(strtolower($record->date));?>
				    		</span>

				    	</div>
			    	</div>
			    	<!-- /user card -->

						

				</div>
				<!-- /sidebar content -->

			</div>
			<!-- /right sidebar component -->

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

<!-- edit modal -->
<div id="details" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Book Details({{selectedRow.book.title}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Update member account information here. Please provide required information to proceed next...</p>	
				<br>
				<div class="row">
					<div class="col-sm-6">
						<p class="text-muted"><code>Book Title: </code>
							{{selectedRow.book.title}}</p>
						<p class="text-muted"><code>Issued By: </code>
							{{selectedRow.issuer.name}}</p>
						
					</div>
					<div class="col-sm-6">
						<p class="text-muted"><code>ISBN: </code>
							{{selectedRow.book.isbn}}</p>
						<p class="text-muted"><code>Accession Number: </code>
							{{selectedRow.book.accession_number}}</p></div>
				</div>			
				





			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /edit modal -->



</div>
<!-- /main content -->