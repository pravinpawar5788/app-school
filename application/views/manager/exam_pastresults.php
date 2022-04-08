<?php
$activeSession=$this->session_m->getActiveSession();
$sections=$this->class_section_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'name ASC') );
$classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'display_order ASC') );
$sessions=$this->session_m->get_rows(array(),array('orderby'=>'status ASC,mid DESC') );
// $classes_array=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
// $sessions_array=$this->session_m->get_values_array('mid','title',array());

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
					<a class="breadcrumb-item">Exam</a>
					<span class="breadcrumb-item active">Past Results</span>
				</div>

				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

		</div>
		<!-- /breadcrumb line -->

	
		<!-- Page header content -->
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Exam</span> - Past Results</h4>
				<a class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>			
		</div>
		<!-- /page header content -->


		<!-- Profile navigation -->
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
						<a href="#profile" class="navbar-nav-link active" data-toggle="tab" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
							<i class="icon-history mr-2" style="color:<?php print $clr;?>;"></i>
							Past Results
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

					<!-- Search field -->
					<div class="card search-area" >
						<div class="card-body">
							<h5 class="mb-3">Past Exam Results
							<span class="d-block font-size-base text-muted">Past exam results helps you update recent exam result of students. Later, you can create fee package policy for students if you are collecting the fee term wise. This result will automatically reset to zero at session migration.
							</h5>
							</span>
								<div class="form-group">
									<div class="row">
										<div class="col-sm-3">
											<label class="text-muted">Select Class</label>
											<select class="form-control select" ng-model="filter.class_id" ng-change="loadClassSections()" data-fouc>
											<option value="">Select Class</option>
											<?php foreach ($classes as $row){?>            
											    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
										    <?php }?>
											</select>
										</div>
										<div class="col-sm-3" ng-show="filter.class_id.length>0">
											<label class="text-muted">Load Students of Section</label>
											<select class="form-control select" ng-model="filter.section" ng-change="loadResult()" data-fouc>
											<option value="">All Sections</option>
											<option  ng-repeat="row in classSections" value="{{row.mid}}">{{row.name}}</option>
											</select>
										</div>
										<div class="col-sm-4 mt-2" >
											<button ng-click="loadResult()" class="btn btn-success btn-lg mt-2" >
												<span class="font-weight-bold">Load Students</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
											</button>
										</div>


									</div>
								</div>

								
						</div>
					</div>
					<!-- /search field -->

					<!-- List table -->
					<div class="card list-area" ng-show="filter.class_id.length>0 && results.rows.length>0" >
						<div class="card-header bg-transparent">
							<h4 class="card-title">Students List 
										<div class="col-sm-3 float-right">
											<div class="form-group form-group-feedback form-group-feedback-left">
												<input type="text" class="form-control" placeholder="Subject Total Marks" ng-model="entry.total_marks" ng-keyup="updateTotalMarks()">
												<div class="form-control-feedback">
													<i class="icon-calculator"></i>
												</div>
											</div>
										</div>
							</h4>
							<span class="text-muted">Below is the list of students of selected classs.</span>
						</div>
						<div class="table-responsive">
						<table class="table tasks-responsive table-lg">
							<thead>
								<tr>
									<th class="font-weight-bold">#</th>
									<th class="font-weight-bold">Name</th>
									<th class="font-weight-bold">Father Name</th>
									<th class="font-weight-bold">Roll Number</th>
									<th class="font-weight-bold">Total Marks</th>
									<th class="font-weight-bold">Obtained Marks</th>
					            </tr>
							</thead>
							<tbody>

								<tr ng-repeat="row in results.rows">
								<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
								<td>
									<div><a class="font-weight-semibold">
									<img ng-src="<?php print $this->UPLOADS_ROOT;?>images/student/profile/{{row.image}}" class="rounded-circle m-1" width="32" height="32" alt="">
        							{{row.name}}
									</a></div>
								</td>
								<td>{{row.father_name}}</td>  
								<td>{{row.roll_no}}</td>
								<td>{{row.pkg_total_marks}}</td>
			  					<td>
			  						<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control" placeholder="Obtained Marks" ng-model="row.pkg_obt_marks" ng-blur="updateResult(row)">
										<div class="form-control-feedback">
											<i class="icon-user-check"></i>
										</div>
									</div>
								</td> 
								
				            </tr>

							</tbody>
						</table>
						<br><br><br>

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
						<div class="col-sm-4">
							<label class="text-muted">Session</label>
							<select class="form-control select" ng-model="filter.session_id" data-fouc>
							<option value="">Select Session</option>
							<?php foreach ($sessions as $row){?>            
							    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
						    <?php }?>
							</select>
						</div>
						<div class="col-sm-4">
							<label class="text-muted">Class</label>
							<select class="form-control select" ng-model="filter.class_id" ng-change="loadSubjects()" data-fouc>
							<option value="">Select Class</option>
							<?php foreach ($classes as $row){?>            
							    <option value="<?=$row['mid'];?>" /><?php print strtoupper($row['title']);?>
						    <?php }?>
							</select>
						</div>
						<div class="col-sm-4">
							<label class="text-muted">Subject</label>
							<select class="form-control select form-control-lg" ng-model="filter.subject_id" data-fouc>
							<option value="">Select Subject</option>
							<option ng-repeat="row in subjects.rows" value="{{row.mid}}">{{row.name+' ('+row.code+')'}}</option>
							</select>
						</div>
						
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="loadTests()" class="btn btn-success btn-lg" data-dismiss="modal">
						<span class="font-weight-bold"> Search</span>
						<i class="icon-circle-right2 ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /refine search modal -->





</div>
<!-- /main content -->
<!-- ********************************************************************** -->
<!-- ///////////////////////////////SCRIPTS//////////////////////////////// -->
<!-- ********************************************************************** -->
<!-- <script type="text/javascript">
	
$(function(){
   
});
</script> -->