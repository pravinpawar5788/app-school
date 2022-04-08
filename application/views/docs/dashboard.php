<!-- Main content  -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold"><?php print ucwords($this->config->item('app_doc_name'));?></span> - Dashboard</h4>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>

					<div class="header-elements d-none py-0 mb-3 mb-md-0">
						<div class="breadcrumb">
							<a href="index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<span class="breadcrumb-item active">Dashboard</span>
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
								<h5 class="card-title font-weight-bold">Campus Manager's Dashboard</h5>
							</div>

							<div class="card-body">
								<div class="mb-4">
									<p class="mb-3">Dashboard helps you getting quick overview of the system. Counting figures are available on left side panels. On top right side you can check today attendance summary. In bottom, you will see monthly income and expenses summary for your camus for the current month. and on bottom right you will see 10 most recent activities performed for this campus.</p>


									<div class="mb-4" id="dlo">
										<h3 class="font-weight-bold">Desired Outcomes</h3>
										<p>Dashboard helps:</p>
										<div class="row">
											<div class="col-md-12">
												<ul class="list mb-3">
													<li>Check total registered student along with active students for current session.</li>
													<li>Check total registered staff along with active staff.</li>
													<li>Check total classes along with sections.</li>
													<li>Check daily income along with total income of current month.</li>
													<li>Check daily expenses along with total expenses of current month.</li>
													<li>Check total students present in school today.</li>
													<li>Check total students absent from school today.</li>
													<li>Check total students on leave today.</li>
													<li>Check total students for whom attendance is not marked yet.</li>
													<li>Check total students for whom attendance is not marked yet.</li>
													<li>Check monthly income / expense summary for current month.</li>
													<li>Check recent activities performed.</li>
												</ul>
											</div>
										</div>
										<p></p>
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
										<!-- <li class="nav-item-header font-size-xs line-height-xs text-uppercase pt-0">Version history</li> -->
										<li class="nav-item nav-item-submenu">
											<a href="#" class="nav-link">Quick Jump</a>
											<ul class="nav nav-group-sub d-block">
												<li class="nav-item"><a href="#dlo" class="nav-link">Desired Outcomes</span></a></li>
												<!-- <li class="nav-item"><a href="#firefox" class="nav-link">Firefox Browser</span></a></li> -->
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