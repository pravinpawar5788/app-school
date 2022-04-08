<?php
// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////
$filter=array('campus_id'=>$this->CAMPUSID,'staff_id'=>$form['usr']);
// if(isset($form['status']) && !empty($form['status'])){$filter['status']=$form['status'];}
// if(isset($form['nstatus']) && !empty($form['nstatus'])){$filter['status <>']=$form['nstatus'];}
// if(isset($form['role']) && !empty($form['role'])){$filter['role_id']=$form['role'];}
// if(isset($form['gender']) && !empty($form['gender'])){$filter['gender']=$form['gender'];}
// if(isset($form['blood_group']) && !empty($form['blood_group'])){$filter['blood_group']=$form['blood_group'];}
// if(isset($form['salary']) && !empty($form['salary'])){$filter['salary >']=$form['salary'];}
$params=array();
$rows=$this->stf_deduction_m->get_rows($filter,$params);
$member=$this->staff_m->get_by(array('campus_id'=>$this->CAMPUSID,'mid'=>$form['usr']),true);
// $roles=$this->stf_role_m->get_rows(array('status'=>$this->stf_role_m->STATUS_ACTIVE),array('orgerby'=>'title ASC') );

?>
<!-- Main content -->
<div class="content-wrapper">

				
<!-- Content area -->
<div class="content">

	

	<!-- ------------------------------printing--------------------------------------- -->
	<div  id="printing-content">
	<?php $this->load->view($LIB_VIEW_DIR.'printing/components/styles'); ?>


	<page size="<?php print $this->PRINT_PAGE_SIZE;?>" layout="<?php print $this->PRINT_PAGE_LAYOUT;?>" class="editable">

	<div class="page-header" style="text-align: center">
		<?php $this->load->view($LIB_VIEW_DIR.'printing/components/styles/print_header'); ?>	
	</div>
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
				<div class="col-sm-12">					
				    <table class="table table-sm">
				        <thead>
				            <tr>
				                <th class="font-weight-semibold text-center" width="5%">#</th>
				                <th class="font-weight-semibold text-center">Title</th>
				                <th class="font-weight-semibold text-center">Amount</th>
				                <th class="font-weight-semibold text-center">Date</th>
				            </tr>
				        </thead>
				        <tbody>

				        	<?php
				        	$i=0;
				        	$total_amount=0;
				        	if(count($rows)<1){
				        		?>
				        		<tr>
					            	<td colspan="3"><span class="font-weight-semibold text-danger"><?php print ucwords(strtolower($member->name)); ?> has not yet received any loan!</span></td>			                
					            </tr>
				        	<?php
				        	}else{
					        	foreach($rows as $row){
					        		$total_amount+=$row['amount'];
					        	?>
					            <tr>
					            	<td class="text-center"><?php print ++$i;?></td>
					                <td class="text-center"><?php print $row['title'];?></td>
					                <td class="text-center"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$row['amount'];?></td>
					                <td class="text-center"><?php print $row['date'];?></td>
					            </tr>
					        	<?php } 
				        	}?>

				        </tbody>
				    </table>
				    <br>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4 border-solid text-center">
					<span class="text-bold"> Total Amount </span>
				</div>
				<div class="col-sm-4 grd-bg-orange border-white text-center">
					<span class="text-bold"> <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$total_amount;?> </span>
				</div>
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
	<?php print $this->config->item('app_print_code');?>1003 <span class="page-footer-right"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
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