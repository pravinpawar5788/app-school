<?php
$system_hooks=$this->sms_hook_m->get_hooks();

?>
<!-- Main content -->
<div class="content-wrapper" ng-controller="mozzCtrl"  ng-cloak>

<!-- Page header -->
<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Settings</span> - SMS Notification Settings</h4>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a><br>			
		</div>

		<div class="header-elements d-none">
		</div>
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php print $this->LIB_CONT_ROOT;?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<span class="breadcrumb-item active">SMS Notifications</span>
			</div>
		</div>
	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb justify-content-center">				
				<a href="<?php print $this->LIB_CONT_ROOT;?>settings/smshooks" class="breadcrumb-elements-item" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
					<i class="icon-users2 mr-2" style="color:<?php print $clr;?>;"></i> SMS Notifications</a>
			</div>

			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>

		<div class="header-elements d-none">
			<div class="breadcrumb justify-content-center">

				
				<!-- on each page -->
				<a href="<?php print $this->APP_ROOT.'docs/settings/smshooks';?>" target="_blank" class="breadcrumb-elements-item">
					<i class="icon-lifebuoy mr-2"></i>Docs
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
			<h5 class="mb-3">Register Notification</h5>
				<div class="form-group">
					<div class="row">						
						<div class="col-sm-8">
							<select class="form-control select-search" ng-model="hook.hook" data-fouc>
							<option value="">Select event to subscribe auto sms notification</option>
							<?php foreach ($system_hooks as $key=>$row){?>            
							    <option value="<?php print $key;?>" /><?php print strtoupper($row['event']);?>
						    <?php }?>
							</select>
						</div>
						<div class="col-sm-4">
							<button ng-click="loadSystemHook()" class="btn btn-success btn-lg">
								<span class="font-weight-bold"> Subscribe</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
							</button>
						</div>

					</div>
				</div>

		</div>
	</div>
	<!-- /search field -->

	<!-- List table -->
	<div class="card">
		<div class="card-header bg-transparent">
			<h4 class="card-title">Registered SMS Noticiations </h4>
			<span class="text-muted">Below are the registered sms notificaitons. On specified event if system sms sending found enable and you have enough sms balance on vendor website then the target audience get notified by system automatically.</span>
		</div>
		<div class="table-responsive">
		<table class="table tasks-responsive table-lg">
			<thead>
				<tr>
					<th class="font-weight-bold">#</th>
					<th class="font-weight-bold">Event</th>
					<th class="font-weight-bold">Message</th>
					<th class="font-weight-bold">Target</th>
					<th class="text-center text-muted" style="width: 30px;"><i class="icon-checkmark3"></i></th>
	            </tr>
			</thead>
			<tbody>

				<tr ng-repeat="row in responseData.rows">
					<td>{{$index+1+(appConfig.currentPage*appConfig.pageLimit)}}</td>
					<td>{{row.event}}</td>
  					<td>{{row.template}}</td>   
  					<td><strong>{{row.target | uppercase}}</strong></td>
					<td>
						<div class="list-icons float-right">
							<div class="btn-group list-icons-item dropdown">
		                    	<button type="button" class="btn btn-sm btn-info btn-labeled btn-labeled-right dropdown-toggle" data-toggle="dropdown"><b><i class="icon-menu7"></i></b> Options</button>
		                    	
								<div class="dropdown-menu dropdown-menu-right">
									<a <?php print $this->MODAL_OPTIONS;?> data-target="#edit" class="dropdown-item" ng-click="selectRow(row)">
										<i class="icon-compose"></i> Edit Notification
									</a>
									<a class="dropdown-item" ng-click="delRow(row)"><i class="icon-user-cancel"></i> Cancel Notification</a>
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


<!-- add sms hook -->
<div id="add" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Subscribe Auto SMS Notification <strong>({{systemHook.event}})</strong></h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">CSMS let you subscribe auto sms notitifications. Write here the message and choose target audience. System will automatically send sms on specified event. </p>
				<legend class="font-weight-semibold text-uppercase font-size-sm">
					<i class="icon-filter3 mr-2"></i>Choose dynamic data...</legend>
								
				<div class="form-group">
					<div class="row">
						<div class="col-sm-3">
							<select class="form-control select" ng-model="hook.target" data-fouc>
							<option value="">WHOME TO SEND SMS?</option>
							<option ng-repeat="row in systemHook.target" value="{{row}}">{{row |uppercase }}</option>
							</select>
						</div>						
						<div class="col-sm-9">
							<p ng-show="hook.target.length<1">
								<span class="text-info">Select target audience to see dynamic keys...</span>
							</p>
							<p ng-show="hook.target==='<?php print $this->sms_hook_m->TARGET_STUDENT;?>'">
								<button type="button" ng-repeat="key in systemHook.keys.student" class="btn btn-outline-success btn-sm m-1" ng-click="addkey(key)">{{key}}</button>
							</p>
							<p ng-show="hook.target==='<?php print $this->sms_hook_m->TARGET_STAFF;?>'">
								<button type="button" ng-repeat="key in systemHook.keys.staff" class="btn btn-outline-info btn-sm m-1" ng-click="addkey(key)">{{key}}</button>
							</p>
							<p ng-show="hook.target==='<?php print $this->sms_hook_m->TARGET_GAURDIAN;?>'">
								<button type="button" ng-repeat="key in systemHook.keys.guardian" class="btn btn-outline-warning btn-sm m-1" ng-click="addkey(key)">{{key}}</button>
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
							<textarea class="form-control" ng-model="message" placeholder="Write your message..." rows="5"></textarea>
						</div>
					</div>
				</div>

			<p class="text-muted">
				<code>NAME</code> Name of student or staff as per selected target.<br>
				<code>GUARDIAN</code> Guardian name of student or staff as per selected target.<br>
			</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="saveRow()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Subscribe Auto SMS</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /add sms hook-->

<!-- add sms hook -->
<div id="edit" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Subscribe Auto SMS Notification <strong>({{systemHook.event}})</strong></h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<p class="text-muted">CSMS let you subscribe auto sms notitifications. Write here the message and choose target audience. System will automatically send sms on specified event. </p>
				<legend class="font-weight-semibold text-uppercase font-size-sm">
					<i class="icon-filter3 mr-2"></i>Choose dynamic data...</legend>
								
				<div class="form-group">
					<div class="row">
						<div class="col-sm-3">
							<select class="form-control select" ng-model="selectedRow.target" data-fouc>
							<option value="">WHOME TO SEND SMS?</option>
							<option ng-repeat="row in systemHook.target" value="{{row}}">{{row |uppercase }}</option>
							</select>
						</div>						
						<div class="col-sm-9">
							<p ng-show="selectedRow.target.length<1">
								<span class="text-info">Select target audience to see dynamic keys...</span>
							</p>
							<p ng-show="selectedRow.target==='<?php print $this->sms_hook_m->TARGET_STUDENT;?>'">
								<button type="button" ng-repeat="key in systemHook.keys.student" class="btn btn-outline-success btn-sm m-1" ng-click="addkey(key)">{{key}}</button>
							</p>
							<p ng-show="selectedRow.target==='<?php print $this->sms_hook_m->TARGET_STAFF;?>'">
								<button type="button" ng-repeat="key in systemHook.keys.staff" class="btn btn-outline-info btn-sm m-1" ng-click="addkey(key)">{{key}}</button>
							</p>
							<p ng-show="selectedRow.target==='<?php print $this->sms_hook_m->TARGET_GAURDIAN;?>'">
								<button type="button" ng-repeat="key in systemHook.keys.guardian" class="btn btn-outline-warning btn-sm m-1" ng-click="addkey(key)">{{key}}</button>
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
							<textarea class="form-control" ng-model="message" placeholder="Write your message..." rows="5"></textarea>
						</div>
					</div>
				</div>

			<p class="text-muted">
				<code>NAME</code> Name of student or staff as per selected target.<br>
				<code>GUARDIAN</code> Guardian name of student or staff as per selected target.<br>
			</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button ng-click="updateRow()" class="btn btn-success btn-lg">
					<span class="font-weight-bold"> Subscribe Auto SMS</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- /add sms hook-->



</div>
<!-- /main content -->