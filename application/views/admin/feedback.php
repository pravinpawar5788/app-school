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
					<a class="breadcrumb-item">Feedback</a>
					<span class="breadcrumb-item active">Send your feedback</span>
				</div>

				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

		</div>
		<!-- /breadcrumb line -->

	
		<!-- Page header content -->
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">System</span> - Feedback</h4>
				<a class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

		</div>
		<!-- /page header content -->


		<!-- Profile navigation -->
		<div class="navbar navbar-expand-lg navbar-light bg-light">
			<div class="text-center d-lg-none w-100">
				<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-second">
					<i class="icon-menu7 mr-2"></i>
					Page Navigation
				</button>
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
					<!-- settings -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Feedback
							<span class="d-block font-size-base text-muted">Your feedback regarding this software is very precious for us. It will help us make more usefull for you. Please write to us time to time to leave your positive or negative comments. You can also write us if you face any error/difficulty while using this software.
							</span>
							</h5>
						</div>						
						
						<div class="card-body">
							<div class="form-group">
							<div class="row">
								<div class="col-md-10">
									<label class="text-muted">Subject</label>
									<input type="text" class="form-control" placeholder="Feedback Subject" ng-model="entry.subject">
								</div>
							</div>
							</div>
							<div class="form-group">
							<div class="row">
								<div class="col-md-12">
									<label class="text-muted">Message</label>
									<textarea class="form-control" placeholder="Feedback Subject" ng-model="entry.message" rows="6"></textarea>
								</div>
							</div>
							</div>
							<div class="form-group">
							<div class="row">
								<div class="col-md-12">
									<button ng-click="sendMessage()" class="btn btn-success btn-lg float-right">
											<span class="font-weight-bold"> Send Feedback</span>
											<i class="icon-circle-right2 ml-2"></i>
									</button>
								</div>
							</div>
							</div>

						</div>
					</div>
					<!-- /settings -->
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





</div>
<!-- /main content -->