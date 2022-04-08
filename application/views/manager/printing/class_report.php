<?php
// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////
$filter=array('campus_id'=>$this->CAMPUSID);
if(isset($form['alumni']) ){$filter['class_id']=0;}
if(isset($form['status']) && !empty($form['status'])){$filter['status']=$form['status'];}
if(isset($form['nstatus']) && !empty($form['nstatus'])){$filter['status <>']=$form['nstatus'];}
if(isset($form['session']) && !empty($form['session'])){$filter['session_id']=$form['session'];}
if(isset($form['class']) && !empty($form['class'])){$filter['class_id']=$form['class'];}
if(isset($form['gender']) && !empty($form['gender'])){$filter['gender']=$form['gender'];}
if(isset($form['blood_group']) && !empty($form['blood_group'])){$filter['blood_group']=$form['blood_group'];}
if(isset($form['fee']) && !empty($form['fee'])){$filter['fee >']=$form['fee'];}
$params=array();
if(isset($form['search']) && !empty($form['search'])){
	$like=array();
    $search=array('title');
	foreach($search as $val){$like[$val]=$form['search'];} 
	$params['like']=$like;
}
$params['orderby']='display_order ASC';
$rows=$this->class_m->get_rows($filter,$params);
$classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
$sessions=$this->session_m->get_values_array('mid','title',array());
$activeSession=$this->session_m->getActiveSession();
?>
<!-- Main content -->
<div class="content-wrapper">

				
<!-- Content area -->
<div class="content">

	
	<!-- Top right menu -->
	<ul class="fab-menu fab-menu-absolute fab-menu-top-right" <?php print $this->MODAL_OPTIONS;?> data-target="#view">
		<li>
			<a class="fab-menu-btn btn bg-danger-400 btn-float rounded-round btn-icon">
				<i class="fab-icon-open icon-printer"></i></a>
		</li>
	</ul>
	<!-- /top right menu -->



	<!-- ------------------------------printing------------------------------------------------------- -->
	<div  id="printing-content">
	<?php $this->load->view($LIB_VIEW_DIR.'printing/components/styles'); ?>

	<page size="<?php print $this->PRINT_PAGE_SIZE;?>" layout="<?php print $this->PRINT_PAGE_LAYOUT;?>" class="editable">
	<?php $this->load->view($LIB_VIEW_DIR.'printing/components/styles/print_header'); ?>	
	<p class="text-center"> Availeable strength of institues classes.</p>
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
		                <th class="font-weight-semibold text-center">Class Name</th>
		                <th class="font-weight-semibold text-center">Incharge</th>
		                <?php if(isset($form['p_std'])){?>
		                	<th class="font-weight-semibold text-center">Students</th>
		                <?php }?>
		                <?php if(isset($form['p_sub'])){?>
		                	<th class="font-weight-semibold text-center">Subjects</th>
		                <?php }?>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php
		        	$i=0;
		        	$std_filter=array('session_id'=>$activeSession->mid,'campus_id'=>$this->CAMPUSID);
		        	$sub_filter=array('campus_id'=>$this->CAMPUSID);
		        	if(count($rows)<1){
		        		?>
		        		<tr>
			            	<td colspan="3"><span class="font-weight-semibold text-danger">No classes registered yet!</span></td>			                
			            </tr>
		        	<?php
		        	}else{
			        	foreach($rows as $row){
			        	?>
			            <tr>
			            	<td class="text-center"><?php print ++$i;?></td>
			                <td class="text-center">
			                	<strong><?php print ucwords(strtolower($row['title']));?></strong>
		                	</td>
		                	<td class="text-center"><?php print ucwords(strtolower($this->staff_m->get_by_primary($row['incharge_id'])->name ));?></td>
		                	<?php if(isset($form['p_std'])){
		                		$std_filter['class_id']=$row['mid'];
		                		?><td class="text-center">
		                		<?php print $this->student_m->get_rows($std_filter,'',true);?></td>
		                	<?php }?>
		                	<?php if(isset($form['p_sub'])){
		                		$sub_filter['class_id']=$row['mid'];
		                		?><td class="text-center">
		                		<?php print $this->class_subject_m->get_rows($sub_filter,'',true);?></td>
		                	<?php }?>
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
	<?php print $this->config->item('app_print_code');?>2006 <span style="float: right;"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
	</div>	

	</page>



	</div>
	<!-- ------------------------------/printing-------------------------------------------------------- -->
</div>
<!-- /content area -->
<!-- Footer -->
<?php
$this->load->view($LIB_VIEW_DIR.'includes/footer_inc');
?>
<!-- /footer -->
</div>
<!-- /main content -->

<!-- create voucher modal -->
<div id="view" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="get" action="<?php print $this->CONT_ROOT.'printing/report';?>">
				<div class="modal-header bg-success">
					<h6 class="modal-title">Configure Visible Parameters...</h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<div class="modal-body p-3">
					<br>
					<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-eye mr-2"></i>Configure Visible Items</legend>
				
					<div class="row">

						<div class="col-md-3">
							<input type="checkbox" name="p_std" 
								<?php print isset($form['p_std']) ? 'checked':'';?>>
								Show Students							
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_sub" 
								<?php print isset($form['p_sub']) ? 'checked':'';?>>
								Show Subjects						
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_analytics" 
								<?php print isset($form['p_analytics']) ? 'checked':'';?>>
								Show Analytics Footer							
						</div>						
					</div>
					<br>
					<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-filter3 mr-2"></i>Configure Data Filters</legend>
					<div class="row">
						<!-- <div class="col-md-3">
							<input type="checkbox" name="alumni" 
								<?php print isset($form['alumni']) ? 'checked':'';?>>
								Only Show Alumni Students							
						</div> -->					
					</div>
					<div class="row">
						<!-- <div class="col-sm-3">
							<label class="text-muted">Gender</label>          
							<select class="form-control select" name="gender" data-fouc>
						    <option value="" />All Genders
						    <option value="male" <?php print isset($form['gender'])&&$form['gender']=='male'?'selected':'';?>/>Male
						    <option value="female" <?php print isset($form['gender'])&&$form['gender']=='female'?'selected':'';?>/>Female
							</select>
						</div>	 -->		
					</div>
					<br>
					<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-printer mr-2"></i>Adjust Page/Printer Parameters</legend>
					<div class="row">
						<div class="col-md-3">
							<input type="checkbox" name="layout" value="landscape" 
								<?php print isset($form['layout']) ? 'checked':'';?>>
								Landscape Mode							
						</div>	
						<div class="col-sm-3">
							<!-- <label class="text-muted">Font Scale</label> -->
							<select class="form-control select" name="scale" data-fouc>          
						    <option value="" />Default Scale
							<?php for($s=0;$s<5;$s++){ ?>
							<option value="<?php print $s+1; ?>" <?php print isset($form['scale'])&&$form['scale']==($s+1)?'selected':'';?>> Font Scale <?php print $s+1; ?></option>
							<?php } ?>
							</select>
						</div>			
					</div>

				</div>
				<div class="modal-footer">
					<a class="btn btn-link" data-dismiss="modal">Close</a>
					<button type="submit" class="btn btn-success"><span class="font-weight-bold"> Process</span>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- /create voucher modal -->
