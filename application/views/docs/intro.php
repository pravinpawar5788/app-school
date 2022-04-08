<!-- Main content --> 
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold"><?php print ucwords($this->config->item('app_doc_name'));?></span> - Introduction</h4>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>

					<div class="header-elements d-none py-0 mb-3 mb-md-0">
						<div class="breadcrumb">
							<a href="index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<span class="breadcrumb-item active">Introduction</span>
						</div>
					</div>
				</div>
			</div>
			<!-- /page header -->
			

			<!-- Content area -->
			<div class="content pt-0">

				<!-- Inner container -->
				<div class="d-flex align-items-start flex-column flex-md-row">

					<!-- Left content -->
					<div class="order-2 order-md-1">

						<!-- Changelog -->
						<div class="card">
							<div class="card-header header-elements-inline">
								<h5 class="card-title">Introduction</h5>
							</div>

							<div class="card-body">
								<div class="mb-4">
									<p class="mb-3"><?php print ucwords($this->config->item('app_doc_name'));?> helps educational institutes to manage their routine work effectively. System is capable enough to handle maximum educational activities including multiple campuses at one place. CSMS has been first develolped in May 2017. After initial installation  &amp; testing in different institues, now system is much more mature. Below are basic modules of CSMS.</p>

									<p class="mb-3"><strong>CSMS</strong> - features are growing day by day. With every relese you will get some new features &amp; bug fixed. Following are the core features of <?php print ucwords($this->config->item('app_doc_name'));?>:</p>

								</div>
								<div class="row">
									<div class="col-md-12">
										<ul class="list mb-3">
											<li>Staff Module (Payroll, Advance, Salary, Activities, History, Attendance)</li>
											<li>Student Module (Fee Slips, Advance Fee, Activities, History, Attendance)</li>
											<li>Classes, Sections, Curriculum , Lesson Planning, Question Bank</li>
											<li>Attendance Moduel (Attendance of students and staff)</li>
											<li>Finance Module(Staff Payroll, Students Fee Slips, Expenses, Revenue, Financial Account, Journal, Ledgers. Finnancial Statements)</li>
											<li>Stationary Moduele (Inventory, Staff Sale, Student Sale, Purchases, History)</li>
											<li>Library Module</li>
											<li>Transport Module</li>
											<li>Exam Module (Monthly Tests &amp; Reports, Term Based Tests &amp; Reports, Final Results &amp; Marksheets, Result SMS &amp; Emails)</li>
											<li>Parents Portal (For Parents Login)</li>
											<li>Staff Portal (For Staff Members Login)</li>
										</ul>
										<p><code>Misc:-</code>Auto attendance notifications, Academic Session Management, SMS Notification Hooks, Sub Admin Accounts with limited access,  change, graphical attendance &amp; earning reports, sms history.</p>
									</div>

								</div>
							</div>
						</div>
						<!-- /changelog -->

					</div>
					<!-- /left content -->


					<!-- Right sidebar component -->
					<div class="sidebar-sticky w-100 w-md-auto order-1 order-md-2">
						<div class="sidebar sidebar-light sidebar-component sidebar-component-right sidebar-expand-md mb-3">
							<div class="sidebar-content">
								<div class="card">
									<div class="card-header bg-transparent header-elements-inline">
										<span class="text-uppercase font-size-sm font-weight-semibold">About</span>
									</div>

									<ul class="list-group list-group-flush rounded-bottom">
										<li class="list-group-item">
											<span class="font-weight-semibold">Created:</span>
											<div class="ml-auto">11-June-2019</div>
										</li>
										<li class="list-group-item">
											<span class="font-weight-semibold">Introduced In:</span>
											<div class="ml-auto"><span class="badge badge-pill bg-warning-400"><?php print ucwords($this->config->item('app_code'));?> 1.0</span></div>
										</li>
									</ul>
									
									<ul class="nav nav-sidebar nav-scrollspy">
										<li class="nav-item-header font-size-xs line-height-xs text-uppercase pt-0">Version history</li>
										<li class="nav-item nav-item-submenu">
											<a href="#" class="nav-link">Quick Jump</a>
											<ul class="nav nav-group-sub d-block">
												<li class="nav-item"><a href="#staff" class="nav-link">Staff Management</span></a></li>
											</ul>
										</li>
						            </ul>
								</div>
				            </div>
						</div>
					</div>
					<!-- /right sidebar component -->

				</div>
				<!-- /inner container -->

			</div>
			<!-- /content area -->


			<!-- Footer -->
			<?php $this->load->view($LIB_VIEW_DIR.'includes/footer_inc');?>
			<!-- /footer -->

		</div>
		<!-- /main content