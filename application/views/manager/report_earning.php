<?php
$bg=array('bg-violet','bg-success','bg-info','bg-warning','bg-primary','bg-blue','bg-teal','bg-green','bg-slate','bg-indigo');
$teals=array(2,3,4,6,7);
$total_bg=count($bg)-1;
$total_teals=count($teals)-1;
//////////////////////////////////////////////////////////////////////////////////////////////

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
					<a class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
					<a class="breadcrumb-item">Reports</a>
					<span class="breadcrumb-item active">Income/Expenses Report</span>
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
						<?php echo form_open_multipart($this->CONT_ROOT.'earning');?> 							
						<div class="row">
							<div class="col-sm-4">
								<label class="text-muted">Select Year</label>
								<select class="form-control select" name="year" data-fouc>
									<option value="">Current Year</option>
									<?php 
									$loop_year=$this->user_m->year;
									while ($loop_year >= $this->SETTINGS[$this->system_setting_m->_INSTALL_YEAR]) {
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

					<div id="container-earning" style="width: 100%; height: 450px;"></div>
					<?php
					$income_string='';
					$expense_string='';
					$filter=array();
					//campus data filteration
					$filter['campus_id']=$this->CAMPUSID;
					$filter['year']=$year;
					for($i=0;$i<12;$i++){
						$mnth=$i+1;	
						$filter['month']=$mnth;
						$month_income=$this->income_m->get_column_result('amount',$filter);
						$month_expense=$this->expense_m->get_column_result('amount',$filter);
						$income_string.=intval($month_income);
						$expense_string.=intval($month_expense);
						//add comma only valid next month
						if($mnth<=11){	$income_string.=',';$expense_string.=',';	}

					}
					?>
					<script type="text/javascript">
						$(function () {
					    $('#container-earning').highcharts({
					        chart: {
					            type: 'column'
					        },
					        title: {
					            text: 'Earning Analysis for year - <?php print $year;?>'
					        },
					        xAxis: {
					            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May','June','July','Aug','Sep','Oct','Nov','Dec']
					        },
					        yAxis: {
					            title: {
					                text: 'Amount'
					            }
					        },
					        series: [{
					            name: 'Income',
					            color: '<?php print get_random_hax_color();?>',
					            data: [<?php print $income_string;?>]
					        },{
					            name: 'Expenses',
					            color: '<?php print get_random_hax_color();?>',
					            data: [<?php print $expense_string;?>]
					        }],
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




