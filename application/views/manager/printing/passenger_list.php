<?php
/// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////
$classes_array=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID),'display_order ASC');
$section_filter=array('campus_id'=>$this->CAMPUSID);
if(isset($form['class'])&&!empty($form['class'])){$section_filter['class_id']=$form['class'];}
$class_sections=$this->class_section_m->get_values_array('mid','name',$section_filter,'name ASC');
$org_routes=$this->transport_route_m->get_values_array('mid','name',array('campus_id'=>$this->CAMPUSID),'name ASC');
$org_vehicles=$this->transport_vehicle_m->get_values_array('mid','reg_no',array('campus_id'=>$this->CAMPUSID),'reg_no ASC');


									
//////////////////////////////////////////////////////////////////

$sections=array();
$class_filter=array('campus_id'=>$this->CAMPUSID);
if(isset($form['pid']) && !empty($form['pid'])){
	if($form['pid']!='all'){
		$class_filter['mid']=$form['pid'];
		$sections=$this->class_section_m->get_rows(array('class_id'=>$form['pid'],'campus_id'=>$this->CAMPUSID),array('orderby'=>'name ASC') );		
	}
}
$classes=array();
if(isset($form['pid']) && !empty($form['pid'])){
	$classes=$this->class_m->get_rows($class_filter,array('orderby'=>'display_order ASC'));
}

$filter=array('campus_id'=>$this->CAMPUSID);
if(isset($form['type']) && !empty($form['type'])){$filter['type']=$form['type'];}
if(isset($form['route']) && !empty($form['route'])){$filter['route_id']=$form['route'];}
if(isset($form['vehicle']) && !empty($form['vehicle'])){$filter['vehicle_id']=$form['vehicle'];}
$params=array();
$params['orderby']='type ASC, route_id ASC, vehicle_id ASC';

$rows=$this->transport_passenger_m->get_rows($filter,$params);
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
			                <th class="font-weight-bold text-center" width="5%">#</th>
			                <th class="font-weight-bold">Name</th>
			                <th class="font-weight-bold">Route</th>
			                <th class="font-weight-bold">Vehicle</th>
			                <th class="font-weight-bold">Fare</th>
			                <?php if(isset($form['p_cat'])){?>
			                	<th class="font-weight-bold text-center">Category</th>
			                <?php }?>
			                <?php if(isset($form['p_class'])){?>
			                	<th class="font-weight-bold text-center">Class</th>
			                <?php }?>
			                <?php if(isset($form['p_section'])){?>
			                	<th class="font-weight-bold text-center">Section</th>
			                <?php }?>
			                <?php if(isset($form['p_gname'])){?>
			                	<th class="font-weight-bold text-center">Guardian Name</th>
			                <?php }?>
			                <?php if(isset($form['p_mobile'])){?>
			                	<th class="font-weight-bold text-center">Mobile</th>
			                <?php }?>
			                <?php if(isset($form['p_gmobile'])){?>
			                	<th class="font-weight-bold text-center">Guardian Mobile</th>
			                <?php }?>
			                <?php if(isset($form['p_computer'])){?>
			                	<th class="font-weight-bold text-center">Computer Number</th>
			                <?php }?>
			                <?php if(isset($form['p_regd'])){?>
			                	<th class="font-weight-bold text-center">Date</th>
			                <?php }?>
			            </tr>
			        </thead>
			        <tbody>
			        	<?php
			        	$i=0;
			        	$total_fare=0;
			        	if(count($rows)<1){
			        		?>
			        		<tr>
				            	<td colspan="3"><span class="font-weight-semibold text-danger">No Record Found!</span></td>			                
				            </tr>
			        	<?php
			        	}else{
				        	foreach($rows as $row){
				        		$fare=0;
				        		switch ($row['type']) {
				        			case $this->transport_passenger_m->TYPE_STUDENT:{
				        				$record=$this->student_m->get_by_primary($row['passenger_id']);
				        				$img_url=$this->UPLOADS_ROOT.'images/student/profile/'.$record->image;
				        				$class_title=$this->class_m->get_by_primary($record->class_id)->title;
				        				$section=$this->class_section_m->get_by_primary($record->section_id)->name;
				        				$computer_number=$record->computer_number;

										
				        			}
				        			break;
				        			case $this->transport_passenger_m->TYPE_STAFF:{
				        				$record=$this->staff_m->get_by_primary($row['passenger_id']);
				        				$img_url=$this->UPLOADS_ROOT.'images/staff/profile/'.$record->image;
				        				$class_title='N/A';
				        				$section='N/A';
				        				$computer_number='N/A';
				        			}
				        			break;
				        		}
				        		if(isset($form['freepsngr']) && $record->transport_fee>0){
				        			continue;
								}
				        		if($row['type']==$this->transport_passenger_m->TYPE_STUDENT){
				        			if(isset($form['pid']) && !empty($form['pid'])){
										if($form['pid']!=$record->class_id){continue;}
										if(isset($form['section']) && !empty($form['section'])){
											if($form['section']!=$record->section_id){
												continue;
											}
										}
									}
				        		}
		        				$route=$this->transport_route_m->get_by_primary($row['route_id']);
		        				$vehicle=$this->transport_vehicle_m->get_by_primary($row['vehicle_id']);
		        				$fare=$record->transport_fee;
		        				$total_fare+=$fare;
				        	?>
				            <tr>
				            	<td class="text-center"><?php print ++$i;?></td>
				                <td class="text-left">
				                	<?php if(isset($form['p_img'])){?>
				                		<img src="<?php print $img_url;?>" class="rounded mr-1" width="28" height="26" alt="">
				                	<?php }?>
				                	<strong><?php print ucwords(strtolower($record->name));?></strong>
			                	</td>
			                	<td class="text-center"><?php print $route->name;?></td>
			                	<td class="text-center"><?php print $vehicle->reg_no;?></td>
			                	<td class="text-center"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$fare;?></td>
				                <?php if(isset($form['p_cat'])){?>
				                	<td class="text-center"><?php print ucwords($row['type']);?></td>
			                	<?php }?>
				                <?php if(isset($form['p_class'])){?>
				                	<td class="text-center"><?php print $class_title;?></td>
			                	<?php }?>
				                <?php if(isset($form['p_section'])){?>
				                	<td class="text-center"><?php print $section;?></td>
			                	<?php }?>
			                	<?php if(isset($form['p_gname'])){?>
			                		<td class="text-center"><?php print $record->guardian_name;?></td>
			                	<?php }?>
			                	<?php if(isset($form['p_mobile'])){?>
			                		<td class="text-center"><?php print $record->mobile;?></td>
			                	<?php }?>
			                	<?php if(isset($form['p_gmobile'])){?>
			                		<td class="text-center"><?php print $record->guardian_mobile;?></td>
			                	<?php }?>
			                	<?php if(isset($form['p_computer'])){?>
			                		<td class="text-center"><?php print $computer_number;?></td>
			                	<?php }?>
			                	<?php if(isset($form['p_regd'])){?>
			                		<td class="text-center"><?php print $row['date'];?></td>
			                	<?php }?>
				            </tr>
				        	<?php } 
			        	}?>
			        </tbody>
			    </table>
			    <br>
			</div>

			<div class="row">
				<div class="col-sm-8 grd-bg-default border-white text-center">
					<span class="text-bold">Total Fare:</span>
				</div>
				<div class="col-sm-4 grd-bg-orange border-white text-center">
					<span class="text-bold"> <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$total_fare;?> </span>
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
	<?php print $this->config->item('app_print_code');?>0004 <span class="page-footer-right"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
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
			<form method="get" action="<?php print $this->CONT_ROOT.'printing/list';?>">
				<div class="modal-header bg-success">
					<h6 class="modal-title">Configure Visible Parameters...</h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<div class="modal-body p-3">
					<br>
					<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-eye mr-2"></i>Configure Visible Items</legend>
				
					<div class="row">

						<div class="col-md-3">
							<input type="checkbox" name="p_cat" 
								<?php print isset($form['p_cat']) ? 'checked':'';?>>
								Show Category							
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_img" 
								<?php print isset($form['p_img']) ? 'checked':'';?>>
								Show Profile Image							
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_class" 
								<?php print isset($form['p_class']) ? 'checked':'';?>>
								Show Class						
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_section" 
								<?php print isset($form['p_section']) ? 'checked':'';?>>
								Show Section						
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_computer" 
								<?php print isset($form['p_computer']) ? 'checked':'';?>>
								Show Computer Number							
						</div>	
						<div class="col-md-3">
							<input type="checkbox" name="p_mobile" 
								<?php print isset($form['p_mobile']) ? 'checked':'';?>>
								Show Mobile							
						</div>		
						<div class="col-md-3">
							<input type="checkbox" name="p_gmobile" 
								<?php print isset($form['p_gmobile']) ? 'checked':'';?>>
								Show Guardian Mobile							
						</div>		
						<div class="col-md-3">
							<input type="checkbox" name="p_gname" 
								<?php print isset($form['p_gname']) ? 'checked':'';?>>
								Show Guardian Name							
						</div>		
						<div class="col-md-3">
							<input type="checkbox" name="p_regd" 
								<?php print isset($form['p_regd']) ? 'checked':'';?>>
								Show Registration Date							
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
						<div class="col-md-3">
							<input type="checkbox" name="freepsngr" 
								<?php print isset($form['freepsngr']) ? 'checked':'';?>>
								Only Free Passengers							
						</div>					
					</div>
					<div class="row">
						<div class="col-sm-3">
							<label class="text-muted">Type</label>          
							<select class="form-control select" name="type" data-fouc>
						    <option value="" />All Genders
						    <option value="staff" <?php print isset($form['type'])&&$form['type']=='staff'?'selected':'';?>/>Staff Members
						    <option value="student" <?php print isset($form['type'])&&$form['type']=='student'?'selected':'';?>/>Students
							</select>
						</div>
						<div class="col-sm-3">
							<label class="text-muted">Route</label>          
							<select class="form-control select" name="route" data-fouc>
						    <option value="" />All routes
							<?php foreach ($org_routes as $key=>$val){?>            
							    <option value="<?php print $key;?>" <?php print isset($form['route'])&&$form['route']==$key?'selected':'';?>/><?php print $val;?>
						    <?php }?>
							</select>
						</div>
						<div class="col-sm-3">
							<label class="text-muted">Vehicle</label>          
							<select class="form-control select" name="vehicle" data-fouc>
						    <option value="" />All routes
							<?php foreach ($org_vehicles as $key=>$val){?>            
							    <option value="<?php print $key;?>" <?php print isset($form['vehicle'])&&$form['vehicle']==$key?'selected':'';?>/><?php print $val;?>
						    <?php }?>
							</select>
						</div>
						<?php if(isset($form['type']) && $form['type']=='student'){ ?>
							<div class="col-sm-3">
								<label class="text-muted">Class</label>          
								<select class="form-control select" name="pid" data-fouc>
							    <option value="" />All Classes
								<?php foreach ($classes_array as $key=>$val){?>            
								    <option value="<?php print $key;?>" <?php print isset($form['pid'])&&$form['pid']==$key?'selected':'';?>/><?php print $val;?>
							    <?php }?>
								</select>
							</div>		
							<?php if(isset($form['pid'])&& !empty($form['pid'])&&count($class_sections)){?>
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
