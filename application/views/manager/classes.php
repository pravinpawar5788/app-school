<?php 
$teacher=$this->stf_role_m->get_by(array('title'=>'teacher',));
$faculty=$this->staff_m->get_rows(array('role_id'=>$teacher->mid,'status'=>$this->staff_m->STATUS_ACTIVE,'campus_id'=>$this->CAMPUSID));
$classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'title ASC') );
$sessions=$this->session_m->get_rows(array(),array('orderby'=>'status ASC,mid DESC') );

?>
<!-- Main content -->
<div class="content-wrapper" ng-controller="mozzCtrl"  ng-cloak>

<!-- Page header -->
<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Classes</span> - Registered Classes</h4>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a><br>			
		</div>

		<div class="header-elements d-none">
			<!-- <div class="d-flex justify-content-center">
				<a href="#" class="btn btn-link btn-float text-default"><i class="icon-bars-alt text-primary"></i><span>Statistics</span></a>
				<a href="#" class="btn btn-link btn-float text-default"><i class="icon-calculator text-primary"></i> <span>Invoices</span></a>
				<a href="#" class="btn btn-link btn-float text-default"><i class="icon-calendar5 text-primary"></i> <span>Schedule</span></a>
			</div> -->
		</div>
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php print $this->LIB_CONT_ROOT;?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<span class="breadcrumb-item active">Classes List</span>
			</div>
		</div>
	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb justify-content-center">				
				<a href="<?php print $this->LIB_CONT_ROOT;?>classes" class="breadcrumb-elements-item" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
					<i class="icon-users2 mr-2" style="color:<?php print $clr;?>;"></i> Classes</a>
				<?php if($this->LOGIN_USER->prm_class>1){?>
				<a href="" class="breadcrumb-elements-item" data-target="#add" <?php print $this->MODAL_OPTIONS;?> style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
					<i class="icon-user-plus mr-2" style="color:<?php print $clr;?>;"></i> Add New Class</a>
				<!-- <a <?php print $this->MODAL_OPTIONS;?> data-target="#list-sms" class="breadcrumb-elements-item mouse-pointer">
					<i class="icon-envelope mr-2"></i> Send SMS Notification
				</a> -->
				<?php } ?>
				<a href="<?php print $this->LIB_CONT_ROOT;?>classes/timetable" class="breadcrumb-elements-item" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
					<i class="icon-alarm mr-2" style="color:<?php print $clr;?>;"></i> Timetable</a>
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
						<!-- <div class="dropdown-submenu dropdown-submenu-left">
							<a href="#" class="dropdown-item"><i class="icon-clipboard6"></i> Reports</a>
							<div class="dropdown-menu">
								<div class="dropdown-submenu dropdown-submenu-left">
									<a href="#" class="dropdown-item">Attendance Reports</a>
									<div class="dropdown-menu">
										<a href="#" class="dropdown-item">Daily Attendance Report</a>
										<a href="#" class="dropdown-item">Weekkly Attendance Report</a>
										<a href="#" class="dropdown-item">Monthly Attendance Report</a>
									</div>
								</div>
								<a href="<?php print $this->CONT_ROOT.'printing/';?>" class="dropdown-item">Monthly Fee Collection</a>
								<a href="<?php print $this->CONT_ROOT.'printing/';?>" class="dropdown-item">Admissions Report</a>
							</div>
						</div> -->
						<a href="<?php print $this->CONT_ROOT.'printing/report/?';?>" class="dropdown-item"><i class="icon-vcard"></i> Classes Report</a>
						<a href="<?php print $this->CONT_ROOT.'printing/details/?';?>" class="dropdown-item"><i class="icon-users"></i> Class Wise List</a>
					</div>
				</div>
				<!-- on each page -->
				
				<a href="<?php print $this->APP_ROOT.'docs/classes';?>" target="_blank" class="breadcrumb-elements-item">
					<i class="icon-lifebuoy mr-2"></i>Docs</a>
				<a class="breadcrumb-elements-item mouse-pointer" data-popup="popover" data-trigger="hover" data-html="true" data-placement="left" title="View Hotkeys" data-content="You can press <code>?</code> button to view the hotkeys for this module. Remember to press <code>shift</code> key while pressing <code>?</code>key.">
					<i class="icon-help mr-2"></i>Hotkeys
				</a>
				<!-- end on each page -->
			</div>
		</div>
	</div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">


	<?php $this->load->view($this->LIB_VIEW_DIR.'includes/alert_inc');?>
	<!-- Search field -->
	<div class="card search-area" >
		<div class="card-body">
			<h5 class="mb-3">Search Classes</h5>
				<div class="input-group mb-3">
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

				<!-- <div class="d-md-flex align-items-md-center flex-md-wrap text-center text-md-left">
					<ul class="list-inline list-inline-condensed mb-0">
						<li class="list-inline-item">
							<a class="btn btn-link text-default" <?php print $this->MODAL_OPTIONS;?> data-target="#refine-search">
								<i class="icon-filter3 mr-2"></i> Refine your search</a>
						</li>
						<li class="list-inline-item" ng-show="showFilter()">
							<a class="btn btn-link text-default" ng-click="clearFilter();loadRows();">
								<i class="icon-reload-alt mr-2"></i> Cleare Filter</a>
						</li>
						<li class="list-inline-item" ng-show="showFilter()">
							<a class="btn btn-link text-default" ng-href="<?php print $this->CONT_ROOT.'printing/list/?'?>{{filterGetString()}}">
								<i class="icon-printer mr-2"></i> Print Filtered List</a>
						</li>
					</ul>

				</div> -->
		</div>
	</div>
	<!-- /search field -->

	<!-- List table -->
	<div class="card">
		<div class="card-header bg-transparent">
			<h4 class="card-title">Classes List </h4>
			<span class="text-muted">Registered Classes in the system. </span>
		</div>
		<div class="table-responsive">
		<table class="table tasks-responsive table-lg">
			<thead>
				<tr>
					<th class="font-weight-bold">#</th>
					<th ng-click="sortBy('title');" class="mouse-pointer font-weight-bold" >
						<span class="m-1">Name</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='title'"></i> 
					</th>
					<th ng-click="sortBy('incharge_id');" class="mouse-pointer font-weight-bold" >
						<span class="m-1">In-charge</span><i <?php print $this->SORT_ICON;?> ng-show="sortKey==='incharge_id'"></i> 
					</th>
					<th class="font-weight-bold"><span class="m-1">Class Students</span></th>
					<th class="font-weight-bold">Curriculum</th>
					<th class="text-center text-muted" style="width: 30px;"><i class="icon-checkmark3"></i></th>
	            </tr>
			</thead>
			<tbody>

				<tr ng-repeat="row in responseData.rows">
					<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
	                <td>
	                	<div>
	                		<a ng-href="<?php print $this->CONT_ROOT.'profile/'?>{{row.mid}}" class="font-weight-semibold">{{row.title}}</a>
	                	</div>
					</td> 
					<td>{{row.incharge}}</td>
  					<td>{{row.students}}</td> 
	                <td>
	                	<div>
	                		<a ng-href="<?php print $this->CONT_ROOT.'curriculum/'?>{{row.mid}}" class="font-weight-semibold">Curriculum</a>
	                	</div>
					</td> 
					<td>
						<div class="list-icons float-right">
							<div class="btn-group list-icons-item dropdown">
		                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
								
								<div class="dropdown-menu dropdown-menu-right">
									<a ng-href="<?php print $this->CONT_ROOT.'profile/';?>{{row.mid}}" class="dropdown-item"><i class="icon-user"></i> Details</a>
									<?php if($this->LOGIN_USER->prm_class>1){?>
									<a <?php print $this->MODAL_OPTIONS;?> data-target="#edit" class="dropdown-item" ng-click="selectRow(row)">
										<i class="icon-compose"></i> Update Class
									</a>
									<a <?php print $this->MODAL_OPTIONS;?> data-target="#sms" class="dropdown-item" ng-click="selectRow(row)">
										<i class="icon-envelop"></i> Send SMS
									</a>
									<a <?php print $this->MODAL_OPTIONS;?> data-target="#fee" class="dropdown-item" ng-click="selectRow(row)">
										<i class="icon-coin-dollar"></i> Add Arrear
									</a>
									<div class="dropdown-divider"></div>
									<?php } ?>
									<a ng-href="<?php print $this->CONT_ROOT.'printing/details/?pid='?>{{row.mid}}" class="dropdown-item">
										<i class="icon-printer"></i> Print List
									</a>
									<a ng-href="<?php print $this->CONT_ROOT.'printing/attendance/?pid='?>{{row.mid}}" class="dropdown-item" target="_blank">
										<i class="icon-user-check"></i> Attendance Report
									</a>
									<?php if($this->LOGIN_USER->prm_class>2){?>
									<a class="dropdown-item" ng-click="delRow(row)"><i class="icon-cross text-danger"></i> Delete Class</a>
									<?php }?>
								</div>
							</li>
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
	<!-- /list table -->

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
<!-- <div id="refine-search" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Refine Your Search</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Mozzine EMS let you search specific data by applying various filters on search. Please choose the filters you want to apply in your next search. After filter selection click the search button...</p>

				<hr>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<label class="text-muted">Session</label>
							<select class="form-control select" ng-model="filter.session" data-fouc>
							<option value="">Select Session</option>
							<?php foreach ($sessions as $row){?>            
							    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
						    <?php }?>
							</select>
						</div>
						<div class="col-sm-2">
							<label class="text-muted">Class</label>
							<select class="form-control select" ng-model="filter.class" data-fouc>
							<option value="">Select Class</option>
							<?php foreach ($classes as $row){?>            
							    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
						    <?php }?>
							</select>
						</div>
						<div class="col-sm-2">
							<label class="text-muted">Status</label>
							<select class="form-control select" ng-model="filter.status" data-fouc>
							<option value="">Select Status</option>
							<option value="<?php print $this->student_m->STATUS_ACTIVE;?>" /><?php print strtoupper($this->student_m->STATUS_ACTIVE);?>
							<option value="<?php print $this->student_m->STATUS_UNACTIVE;?>" /><?php print strtoupper($this->student_m->STATUS_UNACTIVE);?>
							<option value="<?php print $this->student_m->STATUS_EXPELLED;?>" /><?php print strtoupper($this->student_m->STATUS_EXPELLED);?>
							<option value="<?php print $this->student_m->STATUS_LEFT;?>" /><?php print strtoupper($this->student_m->STATUS_LEFT);?>
							<option value="<?php print $this->student_m->STATUS_PASSOUT;?>" /><?php print strtoupper($this->student_m->STATUS_PASSOUT);?>
							<option value="<?php print $this->student_m->STATUS_TRANSFER;?>" /><?php print strtoupper($this->student_m->STATUS_TRANSFER);?>
							</select>
						</div>
						<div class="col-sm-2">
							<label class="text-muted">Minimum Fee</label>
							<select class="form-control select" ng-model="filter.fee" data-fouc>
								<option value="">Select Minimum Fee</option>
								<option value="100">100</option>
								<option value="500">500</option>
								<option value="1000">1000</option>
								<option value="5000">5000</option>
								<option value="10000">10000</option>
								<option value="50000">50000</option>
							</select>
						</div>
						<div class="col-sm-2">
							<label class="text-muted">Gender</label>
							<select class="form-control select" ng-model="filter.gender" data-fouc>
							<option value="">Select Gender</option>
							<option value="male">Male</option>
							<option value="female">Female</option>
							<option value="other">Other</option>
							</select>
						</div>
						<div class="col-sm-2">
							<label class="text-muted">Blood Group</label>
							<select class="form-control select" ng-model="filter.blood_group" data-fouc>
							<option value="">Selec Boold Group</option>
							<option value="A+">A+</option>
							<option value="A-">A-</option>
							<option value="B+">B+</option>
							<option value="B-">B-</option>
							<option value="O+">O+</option>
							<option value="O-">O-</option>
							<option value="AB+">AB+</option>
							<option value="AB-">AB-</option>
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
</div> -->
<!-- /refine search modal -->


<!-- add modal -->
<div id="add" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Create New Class</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Create here the new classes. Once you create the classes, you can then enroll the students in the class.</p>
				<p class="text-muted"><code>Incharge</code> can manage class attendance &amp; related activities in his/her portal.</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Required Data</h6>
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
									<label class="text-muted">Class Name<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Class Name" ng-model="class.title">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Class Fee</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Class Fee" ng-model="class.fee">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-coin-dollar"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Select Class In-charge</label>
									<select class="form-control select" ng-model="class.incharge_id" data-fouc>
									<option value="">Select Incharge</option>
									<?php foreach ($faculty as $row){?>            
									    <option value="<?=$row['mid'];?>" /><?php print ucwords(strtolower($row['name'])).'('.$row['staff_id'].')';?>
								    <?php }?>
									</select>
								</div>

							</div>
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
<!-- /add modal -->

<!-- edit modal -->
<div id="edit" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Update Class ({{selectedRow.title}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">Update here the class of this campus. Later you can then enroll the students in the class.</p>
				<p class="text-muted"><code>Incharge</code> can manage class attendance &amp; related activities in his/her portal.</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Required Data</h6>
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
									<label class="text-muted">Class Name<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Class Name" ng-model="selectedRow.title">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Class Fee</label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Class Fee" ng-model="selectedRow.fee">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-coin-dollar"></i>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Select Class In-charge</label>
									<select class="form-control select" ng-model="selectedRow.incharge_id" data-fouc>
									<!-- <option value="">Select Incharge</option> -->
									<?php foreach ($faculty as $row){?>            
									    <option value="<?=$row['mid'];?>" /><?php print ucwords(strtolower($row['name'])).'('.$row['staff_id'].')';?>
								    <?php }?>
									</select>
								</div>

							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-8">
									<label class="text-muted">Promotion Policy (Student promotion class at session end)<span class="text-danger"> * </span></label>
									<select class="form-control select" ng-model="selectedRow.promotion_id" data-fouc>
									<?php foreach ($classes as $row){?>            
									    <option value="<?=$row['mid'];?>" /><?php print ucwords(strtolower($row['title']));?>
								    <?php }?>
									<option value="0">Alumni</option>
									</select>
								</div>
								<div class="col-sm-4">
									<label class="text-muted">Display Order<span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Class Display Order" ng-model="selectedRow.display_order">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-stack"></i>
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


<!-- fee voucher modal -->
<div id="fee" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Create Voucher Record For ({{selectedRow.title}})</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you create voucher record for students of this class. These record will be added in the upcomming voucher automatically. Please provide required data to create voucher record...</p>
				

				<div class="card <?php print $card_border;?>">
					<div class="card-header <?php print $card_heading_bg;?> text-white header-elements-inline">
						<h6 class="card-title">Required Data</h6>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                	</div>
	                	</div>
					</div>

						<div class="card-body <?php print $card_bg;?>">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-12">
									<label class="text-muted">Record Title <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Record Title" ng-model="entry.title">
										<div class="form-control-feedback form-control-feedback-lg">
											<i class="icon-vcard"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<label class="text-muted">Category<span class="text-danger"> * </span></label>
									<select class="form-control select" ng-model="entry.type" data-fouc>
									<option value="">Select Category</option>
								    <option value="fee" />Fee - Default
								    <option value="fine" />Fine
								    <option value="fund" />Fund
									</select>
								</div>
								<div class="col-sm-6">
									<label class="text-muted">Amount <span class="text-danger"> * </span></label>
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control form-control-lg" placeholder="Total amount" ng-model="entry.amount">
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
				<button ng-click="createFeeRow()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Save Record</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /fee voucher modal -->



<!-- send sms -->
<div id="sms" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Send SMS Notification To Class{{selectedRow.title}}</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">EMS let you send sms notitification to all students of the class at once. You need to enable sms notification feature from settings before sending the sms notification. <?php print $this->SMS_HOST_NOTE; ?></p>
				<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-filter3 mr-2"></i>Whome to send sms?</legend>
				<div class="form-group">
					<div class="row">
						
						<div class="col-sm-4">
							<label class="text-muted">Send sms to...</label>
							<select class="form-control select" ng-model="filter.target" data-fouc>
							<option value="">Both Guardian &amp; Student</option>
							<option value="guardian">Only Guardian's</option>
							<option value="student">Only Student's</option>
							</select>
						</div>
					</div>
				</div>
				<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-envelop mr-2"></i>Write Message...</legend>
				<p>
					<button type="button" class="btn btn-outline-success btn-sm" ng-click="addkey('NAME')"><i class="icon-user mr-2"></i> Student Name</button>
					<button type="button" class="btn btn-outline-info btn-sm" ng-click="addkey('GUARDIAN')"><i class="icon-people mr-2"></i> Guardian</button>
				</p>
				<div class="form-group">
					<label class="text-muted">Dynamic keys will be changed into actual values before sending sms.</label>
					<div class="row">
						<div class="col-sm-10">
							<textarea class="form-control" ng-model="message" placeholder="Write your message..." rows="5"></textarea>
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
<!-- /send sms -->





</div>
<!-- /main content -->
<!-- ********************************************************************** -->
<!-- ///////////////////////////////SCRIPTS//////////////////////////////// -->
<!-- ********************************************************************** -->
<!-- <script type="text/javascript">
	
$(function(){
   
});
</script> -->