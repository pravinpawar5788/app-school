<?php
// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////
$filter=array('campus_id'=>$this->CAMPUSID,'student_id'=>$form['usr']);
$params=array();
$rows=$this->std_history_m->get_rows($filter,$params);
$member=$this->student_m->get_by(array('campus_id'=>$this->CAMPUSID,'mid'=>$form['usr']),true);

?>
<!-- Main content -->
<div class="content-wrapper">

				
<!-- Content area -->
<div class="content">

	<!-- -----------------------------printing------------------------------------------------------- -->
	<div  id="printing-content">
	<?php $this->load->view($LIB_VIEW_DIR.'printing/components/styles'); ?>


	<page size="<?php print $this->PRINT_PAGE_SIZE;?>" layout="<?php print $this->PRINT_PAGE_LAYOUT;?>" class="editable">
	<?php $this->load->view($LIB_VIEW_DIR.'printing/components/styles/print_header'); ?>
	<p class="text-center">Activity History of <strong><?php print ucwords(strtolower($member->name));?></strong> - <?php print date('d-F-Y');?></p>

	
	<table>
	<thead>
	  <tr>
	    <td>
	      <!--place holder for the fixed-position header-->
	      <div class="page-header-space"></div>
	    </td>
	  </tr>
	</thead>

	<tbody>
	  <tr>
	    <td>
	      <!-- <div class="page">PAGE</div> -->
	      <!--*** CONTENT STARTS HERE ***-->
	      <div class="page" style="line-height: 1;">


			<div class="row">				
			    <table class="table table-sm">
			        <thead>
			            <tr>
			                <th class="font-weight-semibold text-center" width="5%">#</th>
			                <th class="font-weight-semibold text-center" width="25%">Title</th>
			                <th class="font-weight-semibold text-center">Description</th>
			                <th class="font-weight-semibold text-center" width="15%">Date</th>
			            </tr>
			        </thead>
			        <tbody>
			        	<?php
			        	$i=0;
			        	if(count($rows)<1){
			        		?>
			        		<tr>
				            	<td colspan="4"><span class="font-weight-semibold text-danger"><?php print ucwords(strtolower($member->name)); ?> has not yet performed any action!</span></td>			                
				            </tr>
			        	<?php
			        	}else{
				        	foreach($rows as $row){
				        	?>
				            <tr>
				            	<td class="text-center"><?php print ++$i;?></td>
				                <td class="text-center"><?php print $row['title'];?></td>
				                <td class="text-center"><?php print $row['description'];?></td>
				                <td class="text-center"><?php print $row['date'];?></td>
				            </tr>
				        	<?php } 
			        	}?>
			        </tbody>
			    </table>
			    <br>
			</div>

	      </div>

	      <!--*** CONTENT ENDS HERE ***-->
	    </td>
	  </tr>
	</tbody>

	<tfoot>
	  <tr>
	    <td>
	      <!--place holder for the fixed-position footer-->
	      <div class="page-footer-space"></div>
	    </td>
	  </tr>
	</tfoot>
	</table>	
	<div class="page-footer">
	<?php print $this->config->item('app_print_code');?>2008 <span class="page-footer-right"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
	</div>	

	</page>


	</div>
	<!-- ------------------------------/printing-------------------------------------------------------- -->
</div>
<!-- /content area -->
<!-- Footer -->
<?php $this->load->view($LIB_VIEW_DIR.'includes/footer_inc');?>
<!-- /footer -->
</div>
<!-- /main content -->