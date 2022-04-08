<?php
// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////
$sections=$this->class_section_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'name ASC') );
$activeSession=$this->session_m->getActiveSession();
$classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
$date=$this->class_m->date;

isset($form['date'])&&!empty($form['date'])?$date=$form['date']:$date=$this->student_m->date;
$day=get_day_from_date($date);
$month=get_month_from_date($date,'-',true);
$year=get_year_from_date($date);

$params=array();
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

			<!-- class block -->
			<div class="row form-block">
				<div class="col-sm-12 form-block-head">
						<span style="height: 1.2em;">
							<center>			
								<span class="font-weight-bold" style="font-size: 1.3em">Daily Attendance Report - <?php print $day.'-'.month_string($month).'-'.$year;?></span>
							</center>
						</span>
				</div>
				<div class="col-sm-12">
					
					<div class="row">
						<div class="col-sm-12">
							<div class="card">
								<card class="body p-5 plr-5">
								    <br><br>
									<div class="row mt-2 mb-2 plr-8">
										<div class="col-md-12 col-sm-12">
										<table class="table table-sm text-center table-solid">
											<thead>
								            <tr>
								                <th class="font-weight-bold">Total</th>
								                <th class="font-weight-bold">Present</th>
								                <th class="font-weight-bold">Leave</th>
								                <th class="font-weight-bold">Absent</th>
								                <th class="font-weight-bold">Others</th>
								            </tr>
									        </thead>

								        	<?php
								        	$stf_filter=array('status'=>$this->staff_m->STATUS_ACTIVE,'campus_id'=>$this->CAMPUSID);
								        	$i=0;
								        	$total_staff=0;
								        	$total_presents=0;
								        	$total_absent=0;
								        	$total_leave=0;
								        	$total_others=0;
							        		$total_staff=$this->staff_m->get_rows($stf_filter,'',true);
							        		$staff=$this->staff_m->get_rows($stf_filter,array('select'=>'mid'));
							        		$att_filter=array('month'=>$month,'year'=>$year,'session_id'=>$activeSession->mid,'campus_id'=>$this->CAMPUSID);
							        		foreach($staff as $stf){
							        			$att_filter['staff_id']=$stf['mid'];
							        			$attendance=$this->stf_attendance_m->get_by($att_filter,true);
							        			$key='d'.intval($day);
							        			if(!empty($attendance)){
								        			switch ($attendance->$key) {
								        				case $this->stf_attendance_m->LABEL_PRESENT:{
								        					$total_presents++;
								        				}break;
								        				case $this->stf_attendance_m->LABEL_ABSENT:{
								        					$total_absent++;
								        				}break;
								        				case $this->stf_attendance_m->LABEL_LEAVE:{
								        					$total_leave++;
								        				}break;			        				
								        				default:{
								        					$total_others++;
								        				}break;
								        			}			        				
							        			}else{
							        				$total_others++;
							        			}
							        		}
								        	?>
								        <tbody>
								            <tr>
								                <td class="font-weight-semibold">
								                	<?php print $total_staff; ?></td>
								                <td class="font-weight-semibold">
								                	<?php print $total_presents; ?></td>
								                <td class="font-weight-semibold">
								                	<?php print $total_leave; ?></td>
								                <td class="font-weight-semibold">
								                	<?php print $total_absent; ?></td>
								                <td class="font-weight-semibold">
								                	<?php print $total_others; ?></td>
								            </tr>
								        </tbody>
									        
									    </table>

										</div>
									</div>


									<br><br><br>
								    <div class="row mb-2 mt-2 plr-8">
								    	<div class="col-sm-8">
								    		<!-- <span class=""> Dated: <span class="short-underline"><?php print date('d F Y')  ?></span></span> -->
								    	</div>
								    	<div class="col-sm-4 text-center">
								    		<span class="border-top-dashed w-100">
							&nbsp;&nbsp;&nbsp;&nbsp; <strong>Principal's Signature</strong> &nbsp;&nbsp;&nbsp;&nbsp;</span>
								    	</div>
								    </div>						       

								</card>
							</div>
						</div>
					</div>
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
	<?php print $this->config->item('app_print_code');?>3002 <span class="page-footer-right"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
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
			<form method="get" action="<?php print $this->CONT_ROOT.'printing/report';?>">
				<input type="hidden" name="rpt" value="<?php print isset($form['rpt'])?$form['rpt']:'';?>">
				<div class="modal-header bg-success">
					<h6 class="modal-title">Configure Visible Parameters...</h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<div class="modal-body p-3">
					<br>
					<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-eye mr-2"></i>Configure Visible Items</legend>
				
					<div class="row">
	
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
						<div class="col-sm-3">
							<label class="text-muted">Date</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-md datepicker" placeholder="Attendance Date" name="date">
								<div class="form-control-feedback form-control-feedback-md">
									<i class="icon-calendar"></i>
								</div>
							</div>
						</div>
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
