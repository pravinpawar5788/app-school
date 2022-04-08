<!-- Main content  -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold"><?php print ucwords($this->config->item('app_doc_name'));?></span> - Changelog</h4>
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

						<!-- Initial release -->
						<div class="card" id="1_0">
							<div class="card-header header-elements-inline">
								<h5 class="card-title">Version 1.0</h5>
								<div class="header-elements">
									<span class="text-muted">June 11, 2019</span>

									<div class="list-icons ml-3">
				                		<a class="list-icons-item" data-action="collapse"></a>
				                	</div>
			                	</div>
							</div>

							<div class="card-body">
								<p class="mb-3"><?php print ucwords($this->config->item('app_doc_name'));?> launched with basic modules. Following are the highlights:</p>

								<div class="row mb-3">
									<div class="col-sm-12">
										<ul class="list">
											<li>Staff Members Management</li>
											<li>Student Management</li>
											<li>Attendance Activities</li>
											<li>Library Module</li>
											<li>Stationary Module</li>
											<li>Transport Module</li>
											<li>Classes, Sections &amp; Curriculum Management</li>
											<li>Monthly Test, Term &amp; Final Exam</li>
											<li>Finance Module with Fee &amp; Payroll</li>
											<li>Parents Portal (for parents login)</li>
											<li>Staff Portal (for staff login)</li>
										</ul>
									</div>
								</div>

							</div>
						</div>
						<!-- /initial release -->

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
									</ul>

									<ul class="nav nav-sidebar nav-scrollspy">
										<li class="nav-item-header font-size-xs line-height-xs text-uppercase pt-0">Version history</li>
										<li class="nav-item nav-item-submenu">
											<a href="#" class="nav-link">Version <?php print ucwords($this->config->item('app_version'));?> <span class="text-muted font-weight-normal ml-auto"><?php print date('d.m.Y'); ?></span></a>
											<ul class="nav nav-group-sub d-block">
												<li class="nav-item"><a href="#1_0" class="nav-link">Initial release  
													<span class="text-muted font-weight-normal ml-auto">11.06.2019</span></a></li>
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