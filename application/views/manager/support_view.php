<?php
// $classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'title ASC') );
// $sessions=$this->session_m->get_rows(array(),array('orderby'=>'status ASC,mid DESC') );

?>
<!-- Main content -->
<div class="content-wrapper" ng-controller="mozzCtrl"  ng-cloak>

<!-- Page header -->
<div class="page-header page-header-light" ng-init="rid='<?php print $record->mid;?>'">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Support</span> -Ticket Details</h4>
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
				<span class="breadcrumb-item active">Support - Ticket Details</span>
			</div>
		</div>
	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb justify-content-center">				
				<a href="<?php print $this->LIB_CONT_ROOT;?>support" class="breadcrumb-elements-item" style="color:<?php $clr=get_random_hax_color('dark'); print $clr;?>;">
					<i class="icon-bubbles10 mr-2" style="color:<?php print $clr;?>;"></i> Support Tickets</a>
			</div>

			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>

		<div class="header-elements d-none">
			<div class="breadcrumb justify-content-center">

				<!-- <div class="breadcrumb-elements-item dropdown p-0">
					<a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
						<i class="icon-printer mr-2"></i>Print
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a href="<?php print $this->CONT_ROOT.'printing/list/?';?>" class="dropdown-item"><i class="icon-users"></i> All Parents List</a>
					</div>
				</div> -->
				<!-- on each page -->
				<!-- <a href="<?php print $this->APP_ROOT.'docs/parents';?>" target="_blank" class="breadcrumb-elements-item">
					<i class="icon-lifebuoy mr-2"></i>Docs
				</a>
				<a class="breadcrumb-elements-item mouse-pointer" data-popup="popover" data-trigger="hover" data-html="true" data-placement="left" title="View Hotkeys" data-content="You can press <code>?</code> button to view the hotkeys for this module. Remember to press <code>shift</code> key while pressing <code>?</code>key.">
					<i class="icon-help mr-2"></i>Hotkeys
				</a> -->
				<!-- end on each page -->
			</div>
		</div>
	</div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">


	<?php $this->load->view($this->LIB_VIEW_DIR.'includes/alert_inc');?>

	<!-- List table -->
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-sm-12">
					<p ng-bind-html="chat_details"></p>
				</div>
			</div>
		</div>
	</div>
	<!-- /list table -->

	<!-- List table -->
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-sm-9">
					<p>Please write your reply and press <code>enter</code> to send message.</p>
					<textarea rows="4" class="form-control" placeholder="write you reply" ng-model="reply.message"></textarea>
				</div>
				<div class="col-sm-3">					
					<button ng-click="sendReply()" class="btn btn-success btn-sm mt-4">
						<span class="font-weight-bold"> Send Reply</span> <i ng-class="{'icon-spinner2':appConfig.btnClickedSave, 'spinner':appConfig.btnClickedSave, 'icon-circle-right2':!appConfig.btnClickedSave}" class=" ml-2"></i>
					</button>
					<br><p class="text-muted">or send image / document.</p>
					<?php echo form_open_multipart($this->CONT_ROOT.'upload_attachment');?> 
					<input type="hidden" name="rid" value="<?=$record->mid;?>" />
					<input id="clientImageFile" type="file" name="file" style="display: none;" onchange="showname()">
					<input type="button" value="Browse" class="btn btn-light btn-sm" onclick="document.getElementById('clientImageFile').click();" />
					<button class="btn btn-info btn-sm"><i class="icon-upload position-right"></i><strong> Upload </strong></button>   
					<?php echo form_close(); ?>
					<span class="text-muted" id="selected-file"></span>

				</div>
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


</div>
<!-- /main content -->
<!-- ********************************************************************** -->
<!-- ///////////////////////////////SCRIPTS//////////////////////////////// -->
<!-- ********************************************************************** -->
<!-- <script type="text/javascript">
	
$(function(){
   
});
</script> -->