<?php
// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////
$filter=array('campus_id'=>$this->CAMPUSID);
$params=array();

isset($form['detail']) ? $show_detail=true : $show_detail=false;
isset($form['month']) && !empty($form['month']) ? $month=$form['month'] : $month=$this->std_fee_voucher_m->month;
isset($form['year']) && !empty($form['year']) ? $year=$form['year'] : $year=$this->std_fee_voucher_m->year;

if(!empty($month) ){$filter['month']=$month;}
if(!empty($year)){$filter['year']=$year;}
$filter['type']=$this->std_fee_entry_m->TYPE_CONCESSION;
$filter['operation']=$this->std_fee_entry_m->OPT_MINUS;
$concession_types=$this->concession_type_m->get_rows(array());
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
	<p class="text-center">
		Student Fee Concession Report
		<?php if(!empty($month)){ print '- '.month_string($month);} ?>
		<?php if(!empty($year)){ print ' , '.$year;} ?>
	</p>
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
			                <th class="font-weight-semibold text-center" width="10%">#</th>
			                <th class="font-weight-semibold text-center">Category</th>
			                <?php if($show_detail){ ?>
			                <th class="font-weight-semibold text-center">Student Name</th>
			                <th class="font-weight-semibold text-center">Father Name</th>
			                <?php } ?>
			                <th class="font-weight-semibold text-center">Amount</th>
			            </tr>
			        </thead>
			        <tbody>

					<?php 
					if($show_detail){
						$i=0;
						// $days_in_month=days_in_month($month,$year);
						$total_amount=0;
						foreach($concession_types as $row){
			    			$amount=0;
			    			$filter['concession_type']=$row['mid'];
			    			$concessions=$this->std_fee_entry_m->get_rows($filter);
			    			foreach($concessions as $conces){
			    				$amount=$conces['amount'];
								$std=$this->student_m->get_by_primary($conces['student_id']);
								$total_amount+=$amount;
							?>
				            <tr>
				            	<td class="text-center"><?php print ++$i;?></td>
				                <td class="text-center"><?php print $row['title'];?></td>
				                <td class="text-center"><?php print $std->name;?></td>
				                <td class="text-center"><?php print $std->father_name;?></td>
				                <td class="text-center"><?php print $amount;?></td>

				            </tr>
				        <?php } 
		    			}
		    			$amount=0;
		    			$filter['concession_type']=0;
			    			$concessions=$this->std_fee_entry_m->get_rows($filter);
			    			foreach($concessions as $conces){
			    				$amount=$conces['amount'];
								$std=$this->student_m->get_by_primary($conces['student_id']);
								$total_amount+=$amount;
							?>
				            <tr>
				            	<td class="text-center"><?php print ++$i;?></td>
				                <td class="text-center">Miscellaneous</td>
				                <td class="text-center"><?php print $std->name;?></td>
				                <td class="text-center"><?php print $std->father_name;?></td>
				                <td class="text-center"><?php print $amount;?></td>

				            </tr>
				        <?php } ?>
				    <?php
					}else{
						$i=0;
						// $days_in_month=days_in_month($month,$year);
						$total_amount=0;
						foreach($concession_types as $row){
			    			$amount=0;
			    			$filter['concession_type']=$row['mid'];

							$amount=$this->std_fee_entry_m->get_column_result('amount',$filter);
							$total_amount+=$amount;


						?>
				            <tr>
				            	<td class="text-center"><?php print ++$i;?></td>
				                <td class="text-center"><?php print $row['title'];?></td>
				                <td class="text-center"><?php print $amount;?></td>

				            </tr>
				        <?php } 
		    			$amount=0;
		    			$filter['concession_type']=0;
						$amount=$this->std_fee_entry_m->get_column_result('amount',$filter);
						$total_amount+=$amount;
						?>
				            <tr>
				            	<td class="text-center"><?php print ++$i;?></td>
				                <td class="text-center">Miscellaneous</td>
				                <td class="text-center"><?php print $amount;?></td>

				            </tr>
			        <?php } ?>
			        </tbody>
			    </table>
			    <br>
			</div>

			<?php if(isset($form['p_analytics'])){ ?>
			<br><br>
			<div class="row">
				<div class="col-sm-3 border-solid text-center">
					<span class="text-bold">Total Granted Concession:</span>
				</div>
				<div class="col-sm-3 grd-bg-orange border-white text-center">
					<span class="text-bold"> <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$total_amount;?> </span>
				</div>
			</div>
			<?php } ?>


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
	<?php print $this->config->item('app_print_code');?>5012 <span class="page-footer-right"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
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
							<input type="checkbox" name="detail" 
								<?php print isset($form['detail']) ? 'checked':'';?>>
								Show Details							
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
							<input type="checkbox" name="inc_partial" 
								<?php print isset($form['inc_partial']) ? 'checked':'';?>>
								Include Partial Payment							
						</div>	 -->			
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
						<label class="text-muted">Year</label>          
						<select class="form-control select" name="year" data-fouc>
						<option value="" />Current Year
						<?php 
							$i=0;
							$y=$this->user_m->year;
							while($y>=$this->ORG->year){
								$i++;if($i>25){break;}
								?>
								<option value="<?php print $y;?>" <?php print isset($form['year'])&&$form['year']==$y?'selected':'';?>/><?php print $y;?>
								<?php
								$y--;
							}
						?>
						</select>
					</div>		
					<div class="col-sm-3">
						<label class="text-muted">Month</label>          
						<select class="form-control select" name="month" data-fouc>
						<option value="" />Current Month
						<?php 
							$mnth=$this->user_m->month;
							for($m=1;$m<=12;$m++){
								?>           
								<option value="<?php print $m;?>" <?php print isset($form['month'])&&$form['month']==$m?'selected':'';?>/><?php print month_string($m);?>
								<?php 
							} 
							?>
						</select>
					</div>		

					<!-- <div class="col-sm-3">
						<label class="text-muted">Class</label>          
						<select class="form-control select" name="class" data-fouc>
						<option value="" />All Classes
						<?php foreach ($classes as $key=>$val){?>            
							<option value="<?php print $key;?>" <?php print isset($form['class'])&&$form['class']==$key?'selected':'';?>/><?php print $val;?>
						<?php }?>
						</select>
					</div>	 -->	
					<?php if(isset($form['class'])&&!empty($form['class'])&&count($class_sections)){?>
					<!-- <div class="col-sm-3">
						<label class="text-muted">Class Section</label>          
						<select class="form-control select" name="section" data-fouc>
						<option value="" />All Sections
						<?php foreach ($class_sections as $key=>$val){?>            
							<option value="<?php print $key;?>" <?php print isset($form['section'])&&$form['section']==$key?'selected':'';?>/><?php print $val;?>
						<?php }?>
						</select>
					</div>	 -->
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

