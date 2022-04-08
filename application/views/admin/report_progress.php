<?php
$bg=array('bg-violet','bg-success','bg-info','bg-warning','bg-primary','bg-blue','bg-teal','bg-green','bg-slate','bg-indigo');
$teals=array(2,3,4,6,7);
$total_bg=count($bg)-1;
$total_teals=count($teals)-1;
?>
<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header page-header-light">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Home</span> - Reports</h4>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>

				</div>

				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php print $this->LIB_CONT_ROOT;?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<span class="breadcrumb-item active">Progress Report</span>
						</div>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>

				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content">
			<?php $this->load->view($this->LIB_VIEW_DIR.'includes/alert_inc');?>


			<!-- Search field -->
			<div class="card search-area" >
				<div class="card-header">
					<h5 class="mb-3"></h5>
					<div class="form-group">							
						<?php echo form_open_multipart($this->CONT_ROOT.'');?> 							
						<div class="row">
							<div class="col-sm-4">
								<label class="text-muted">Select Year</label>
								<select class="form-control select" name="year" data-fouc>
									<option value="">Select Year</option>
									<?php 
									$loop_year=$this->user_m->year;
									while ($loop_year >= 2019 ) {
										?>
									<option value="<?php print $loop_year;?>" <?php print $year==$loop_year ? 'selected':''; ?>><?php print $loop_year;?></option>
									<?php $loop_year--; }?>
								</select>
							</div>						
							<div class="col-sm-4">
								<button class="btn btn-success mt-4">
									<span class="font-weight-bold"> Load</span> <i class="icon-circle-right2 ml-2"></i>
								</button> 
							</div>
						</div>
						<?php echo form_close(); ?>
					</div>

				</div>
				<div class="card-body">

					<div id="container" style="width: 100%; height: 450px;"></div>
					<?php
					$staff_string='';
					$std_string='';
					$campus_string='';
					$admin_string='';
					for($i=0;$i<12;$i++){
						$mnth=$i+1;	
						$usrs=$this->staff_m->get_rows(array('month'=>$mnth,'year'=>$year),'',true);
						$students=$this->student_m->get_rows(array('month'=>$mnth,'year'=>$year),'',true);
						$staff_string.=$usrs;$std_string.=$students;
						//add comma only valid next month
						if($mnth<=11){$staff_string.=',';$std_string.=',';}

					}
					?>
					<script type="text/javascript">
						$(function () {
					    $('#container').highcharts({
					        chart: {
					            type: 'column'
					        },
					        title: {
					            text: 'Registration Analysis for year - <?php print $year;?>'
					        },
					        xAxis: {
					            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May','June','July','Aug','Sep','Oct','Nov','Dec']
					        },
					        yAxis: {
					            title: {
					                text: 'Numbers'
					            }
					        },
					        series: [{
					            name: 'Staff',
					            color: '<?php print get_random_hax_color();?>',
					            data: [<?php print $staff_string;?>]
					        },{
					            name: 'Students',
					            color: '<?php print get_random_hax_color();?>',
					            data: [<?php print $std_string;?>]
					        },],
					    });
						});
					</script>
				</div>
			</div>
			<!-- /search field -->



			</div>
			<!-- /content area -->


			<!-- Footer -->
			<?php
			$this->load->view($LIB_VIEW_DIR.'includes/footer_inc');
			?>
			<!-- /footer -->

		</div>
		<!-- /main content -->




