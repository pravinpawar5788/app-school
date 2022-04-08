<?php
// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////

$classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID),'display_order ASC');
$section_filter=array('campus_id'=>$this->CAMPUSID);
if(isset($form['class'])&&!empty($form['class'])){$section_filter['class_id']=$form['class'];}
$class_sections=$this->class_section_m->get_values_array('mid','name',$section_filter,'name ASC');
/////////////////////////////////////////////////////////////////
$sessions=$this->session_m->get_rows(array(),array('orderby'=>'year DESC') );
$activeSession=$this->session_m->getActiveSession();

isset($form['session']) && !empty($form['session']) ? $session_id=$form['session'] : $session_id=$activeSession->mid;
isset($form['class']) && !empty($form['class']) ? $class_id=$form['class'] : $class_id='';
isset($form['section']) && !empty($form['section']) ? $section_id=$form['section'] : $section_id='';
isset($form['count']) && !empty($form['count']) ?$count=$form['count'] :$count=75;
$sections_filter=array('campus_id'=>$this->CAMPUSID);
if(!empty($class_id)){$sections_filter['class_id']=$class_id;}
$sections=$this->class_section_m->get_rows($sections_filter,array('orderby'=>'name ASC') );

$session=$this->session_m->get_by_primary($session_id);
$params=array();

$std_filter=array('campus_id'=>$this->CAMPUSID,'class_id'=>$class_id,'session_id'=>$activeSession->mid,'status'=>$this->student_m->STATUS_ACTIVE);
if(!empty($section_id) && $this->class_section_m->get_rows(array('campus_id'=>$this->CAMPUSID,'mid'=>$form['section']))>0){$std_filter['section_id']=$section_id;}
$students=$this->student_m->get_rows($std_filter,array('orderby'=>'section_id ASC, roll_no ASC, mid ASC'));
$class=$this->class_m->get_by_primary($class_id);

?>
<!-- Main content -->
<div class="content-wrapper">
<!-- Content area -->
<div class="content" >

	
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
				<div class="col-sm-12 grd-bg-default border-white text-center">
					<span class="text-bold">Student Award List / Result Collection Form <?php print empty($class->title) ? '' : '- '.$class->title.' Class'; ?></span>
				</div>
			</div>	
			<br>
			<div class="row">
				<div class="col-sm-6" >
					<span class="form-line">Class Name:<span class="form-line-fill line-70"> </span></span>
					<span class="form-line">Section Name:<span class="form-line-fill line-70"> </span></span>
					<span class="form-line">Subject Name:<span class="form-line-fill line-70"> </span></span>
					<span class="form-line">Total Marks:<span class="form-line-fill line-70"> </span></span>
				</div>
				<div class="col-sm-6" >
					<span class="form-line">Test Title:<span class="form-line-fill line-70"> </span></span>
					<span class="form-line">Test Type:<span class="form-line-fill line-70"> </span> <span class="ml-5">monthly / term / final</span></span>
					<span class="form-line">Prepared By:<span class="form-line-fill line-70"> </span></span>
					<span class="form-line">Date:<span class="form-line-fill line-70"> </span></span>
				</div>
			</div>
			<br>
			<div class="row">				
			    <table class="table table-sm">
			        <thead>
			            <tr>
			                <!-- <th class="font-weight-semibold text-center" style="width: 20%"> -->
			                <!-- #</th> -->
			                <th class="text-center" width="10%">Roll No</th>
			                <th class="text-center" width="25%">Student Name</th>
			                <th class="text-center" width="15%">Obt. Marks</th>

			                <th class="text-center" width="10%">Roll No</th>
			                <th class="text-center" width="25%">Student Name</th>
			                <th class="text-center" width="15%">Obt. Marks</th>

			                <!-- <th class="text-center" width="7%">Roll No</th>
			                <th class="text-center" width="15%">Student Name</th>
			                <th class="text-center" width="11%">Obt. Marks</th> -->

			            </tr>
			        </thead>
			        <tbody>
			        	<tr>
			        	<?php 
						$i=0;
						$tr=0;
						if(count($students)>0 && !empty($class_id)){
							foreach($students as $row){
								$i++;
								$tr++;
							?>
				                <td class="text-center"><?php print $row['roll_no'];?></td>
				                <td class="text-center"><?php print $row['name'];?></td>
				            	<td class="text-center"> </td>
					        <?php 
					        if($tr>1){$tr=0; print '</tr><tr>';}
					    	}
					    	$remaining=$count-$i;
					    	if($remaining<$count){
								for($j=0; $j<=$remaining;$j++){
									$tr++;
								?>
					                <td class="text-center" style="height: 16pt;"></td>
					                <td class="text-center"> </td>
					            	<td class="text-center"> </td>
						        <?php 
						        if($tr>1){$tr=0; print '</tr><tr>';}
						    	}
						    	?>
					    		<td class="text-center" style="height: 16pt;"></td>
				                <td class="text-center"> </td>
				            	<td class="text-center"> </td>
				            	<?php 
					    	}
				    	}else{
							for($j=0; $j<=$count;$j++){
								$tr++;
							?>
				                <td class="text-center" style="height: 16pt;"></td>
				                <td class="text-center"> </td>
				            	<td class="text-center"> </td>
					        <?php 
					        if($tr>1){$tr=0; print '</tr><tr>';}
					    	}
					    	?>
				    		<td class="text-center" style="height: 16pt;"></td>
			                <td class="text-center"> </td>
			            	<td class="text-center"> </td>
					    <?php } ?>
					    </tr>
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
	<?php print $this->config->item('app_print_code');?>4001 <span class="page-footer-right"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
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
<!-- /main content --->

<!-- create voucher modal -->
<div id="view" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="get" action="<?php print $this->CONT_ROOT.'printing/form';?>">
				<input type="hidden" name="frm" value="<?php print isset($form['frm'])?$form['frm']:'';?>">
				<div class="modal-header bg-success">
					<h6 class="modal-title">Configure Visible Parameters...</h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<div class="modal-body p-3">
					<br>
					<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-eye mr-2"></i>Configure Visible Items</legend>
				
					<div class="row">

						<div class="col-md-3">
							<input type="checkbox" name="p_img" 
								<?php print isset($form['p_img']) ? 'checked':'';?>>
								Show Profile Image							
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_computer" 
								<?php print isset($form['p_computer']) ? 'checked':'';?>>
								Show Computer Number							
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
						
					<div class="col-sm-3">
						<label class="text-muted">Class</label>          
						<select class="form-control select" name="class" data-fouc>
						<option value="" />All Classes
						<?php foreach ($classes as $key=>$val){?>            
							<option value="<?php print $key;?>" <?php print isset($form['class'])&&$form['class']==$key?'selected':'';?>/><?php print $val;?>
						<?php }?>
						</select>
					</div>		
					<?php if(isset($form['class'])&&!empty($form['class'])&&count($class_sections)){?>
					<div class="col-sm-3">
						<label class="text-muted">Class Section</label>          
						<select class="form-control select" name="section" data-fouc>
						<option value="" />All Sections
						<?php foreach ($class_sections as $key=>$val){?>            
							<option value="<?php print $key;?>" <?php print isset($form['section'])&&$form['section']==$key?'selected':'';?>/><?php print $val;?>
						<?php }?>
						</select>
					</div>	
					<?php } ?>			
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
