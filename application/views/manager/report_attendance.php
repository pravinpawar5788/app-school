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
					<span class="breadcrumb-item active">Attendace Report</span>
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
						<?php echo form_open_multipart($this->CONT_ROOT.'attendance');?> 							
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
							<div class="col-sm-2">
								<label class="text-muted">Select Month</label>
								<select class="form-control select" name="month" data-fouc>
									<option value="">Current Month</option>
									<?php for($m=1;$m<=12;$m++){ ?>
									<option value="<?php print $m;?>" <?php print $month==$m ? 'selected':''; ?>><?php print month_string($m);?></option>
									<?php }?>
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

					<div id="container-stdatendance" style="width: 100%; height: 450px;"></div>
					<?php
					$days_string="";
					$holiday_string='';
					$present_string='';
					$absent_string='';
					$leave_string='';
					$other_string='';
					$filter=array();
					//campus data filteration
					$filter['campus_id']=$this->CAMPUSID;
					$filter['year']=$year;
					$filter['month']=$month;
					$days_in_month=days_in_month($month,$year);
					$present=array();$absent=array();$leave=array();$holiday=array();$other=array();
					for ($i=0; $i <= $days_in_month; $i++) {
						if($i>0){
							switch($i){
								case 1:{$days_string.="'".$i."st'";}break;
								case 2:{$days_string.="'".$i."nd'";}break;
								case 3:{$days_string.="'".$i."rd'";}break;
								case 21:{$days_string.="'".$i."st'";}break;
								case 22:{$days_string.="'".$i."nd'";}break;
								case 23:{$days_string.="'".$i."rd'";}break;
								case 31:{$days_string.="'".$i."st'";}break;
								default:{$days_string.="'".$i."th'";}break;
							}
							if($i<$days_in_month){$days_string.=',';}
						}							
						$present[$i]=0;$absent[$i]=0;$leave[$i]=0;$holiday[$i]=0;$other[$i]=0;
					}
			    	$attendances=$this->std_attendance_m->get_rows($filter);
			    	foreach($attendances as $row){
			    		for ($i=1; $i <= $days_in_month; $i++) {				
			    			$day='d'.($i);
			    			switch ($row[$day]) {
			    				case $this->std_attendance_m->LABEL_PRESENT: {$present[$i]++;}break;
			    				case $this->std_attendance_m->LABEL_LEAVE: {$leave[$i]++;}break;
			    				case $this->std_attendance_m->LABEL_HOLIDAY: {$holiday[$i]++;}break;			    				
			    				case $this->std_attendance_m->LABEL_ABSENT: {$absent[$i]++;}break;			    				
			    				default: { $other[$i]++;}break;
			    			}

			    		}

			    	}

					for ($i=0; $i <= $days_in_month; $i++) {
						if($i>0){
							$present_string.=$present[$i];
							$absent_string.=$absent[$i];
							$leave_string.=$leave[$i];
							$holiday_string.=$holiday[$i];
							$other_string.=$other[$i];
							//add comma only valid next month
							if($i<$days_in_month){$present_string.=',';$absent_string.=',';$leave_string.=',';$holiday_string.=',';$other_string.=',';	}
						}
					}

					?>
					<script type="text/javascript">
						$(function () {
					    $('#container-stdatendance').highcharts({
					        chart: {
					            type: 'column'
					        },
					        title: {
					            text: 'Student attendance report for <?php print month_string($month);?>, <?php print $year;?>'
					        },
					        xAxis: {
					            categories: [<?php print $days_string ?>]
					        },
					        yAxis: {
					            title: {
					                text: 'Numbers'
					            }
					        },
					        series: [{
					            name: 'Present Students',
					            color: '#00d008',
					            data: [<?php print $present_string;?>]
					        },{
					            name: 'Absent Students',
					            color: '#F90000',
					            data: [<?php print $absent_string;?>]
					        },{
					            name: 'On Leave',
					            color: '#550527',
					            data: [<?php print $leave_string;?>]
					        },{
					            name: 'Holiday',
					            color: '#34435E',
					            data: [<?php print $holiday_string;?>]
					        },{
					            name: 'Attendance Not Marked',
					            color: '#df763e',
					            data: [<?php print $other_string;?>]
					        }],
					    });
						});
					</script>

			</div>
		</div>
		<div class="card" >
			<div class="card-body">

					<div id="container-stfatendance" style="width: 100%; height: 450px;"></div>
					<?php
					$days_string="";
					$holiday_string='';
					$present_string='';
					$absent_string='';
					$leave_string='';
					$other_string='';
					$filter=array();
					//campus data filteration
					$filter['campus_id']=$this->CAMPUSID;
					$filter['year']=$year;
					$filter['month']=$month;
					$days_in_month=days_in_month($month,$year);
					$present=array();$absent=array();$leave=array();$holiday=array();$other=array();
					for ($i=0; $i <= $days_in_month; $i++) {
						if($i>0){
							switch($i){
								case 1:{$days_string.="'".$i."st'";}break;
								case 2:{$days_string.="'".$i."nd'";}break;
								case 3:{$days_string.="'".$i."rd'";}break;
								case 21:{$days_string.="'".$i."st'";}break;
								case 22:{$days_string.="'".$i."nd'";}break;
								case 23:{$days_string.="'".$i."rd'";}break;
								case 31:{$days_string.="'".$i."st'";}break;
								default:{$days_string.="'".$i."th'";}break;
							}
							if($i<$days_in_month){$days_string.=',';}
						}							
						$present[$i]=0;$absent[$i]=0;$leave[$i]=0;$holiday[$i]=0;$other[$i]=0;
					}
			    	$attendances=$this->stf_attendance_m->get_rows($filter);
			    	foreach($attendances as $row){
			    		for ($i=1; $i <= $days_in_month; $i++) {				
			    			$day='d'.($i);
			    			switch ($row[$day]) {
			    				case $this->stf_attendance_m->LABEL_PRESENT: {$present[$i]++;}break;
			    				case $this->stf_attendance_m->LABEL_LEAVE: {$leave[$i]++;}break;
			    				case $this->stf_attendance_m->LABEL_HOLIDAY: {$holiday[$i]++;}break;			    				
			    				case $this->stf_attendance_m->LABEL_ABSENT: {$absent[$i]++;}break;			    				
			    				default: { $other[$i]++;}break;
			    			}

			    		}

			    	}

					for ($i=0; $i <= $days_in_month; $i++) {
						if($i>0){
							$present_string.=$present[$i];
							$absent_string.=$absent[$i];
							$leave_string.=$leave[$i];
							$holiday_string.=$holiday[$i];
							$other_string.=$other[$i];
							//add comma only valid next month
							if($i<$days_in_month){$present_string.=',';$absent_string.=',';$leave_string.=',';$holiday_string.=',';$other_string.=',';	}
						}
					}

					?>
					<script type="text/javascript">
						$(function () {
					    $('#container-stfatendance').highcharts({
					        chart: {
					            type: 'column'
					        },
					        title: {
					            text: 'Staff attendance report for <?php print month_string($month);?>, <?php print $year;?>'
					        },
					        xAxis: {
					            categories: [<?php print $days_string ?>]
					        },
					        yAxis: {
					            title: {
					                text: 'Numbers'
					            }
					        },
					        series: [{
					            name: 'Present Staff',
					            color: '#00d008',
					            data: [<?php print $present_string;?>]
					        },{
					            name: 'Absent Staff',
					            color: '#F90000',
					            data: [<?php print $absent_string;?>]
					        },{
					            name: 'On Leave',
					            color: '#550527',
					            data: [<?php print $leave_string;?>]
					        },{
					            name: 'Holiday',
					            color: '#34435E',
					            data: [<?php print $holiday_string;?>]
					        },{
					            name: 'Attendance Not Marked',
					            color: '#df763e',
					            data: [<?php print $other_string;?>]
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




