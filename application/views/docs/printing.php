<!-- Main content  -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold"><?php print ucwords($this->config->item('app_doc_name'));?></span> - Printing</h4>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>

					<div class="header-elements d-none py-0 mb-3 mb-md-0">
						<div class="breadcrumb">
							<a href="index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<span class="breadcrumb-item active">Printing</span>
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
								<h5 class="card-title font-weight-bold">Documents Printing</h5>
							</div>

							<div class="card-body">
								<div class="mb-4">
									<p class="mb-3">You can print reports, forms and other documents directly from browser. You will see a print menu on every page which may have have printing content like report, form etc. Final print can be customized from blue colored qustion mark( ? ) floating button on top-right location on every print page.</p>

									<p class="mb-3"><strong>Settings</strong> - Normaly, browsers are not so much printer friendly, However, we have customized the browser features to use them for printing purpose. You may need to setup the printing options for your browser one time before starting the printing tasks. We recommend latest version of <code>Chrome</code> or <code>Firefox</code> browser for printing purpose. You can setup your browser printing by following the below instructions. <br>Other browser may have same procedure of printing setup. </p>

									<div class="mb-4" id="chrome">
										<h3 class="font-weight-bold">Chrome Browser</h3>
										<p>Follow the instruction to setup chrome browser for printing:</p>
										<div class="row">
											<div class="col-md-12">
												<ul class="list mb-3">
													<li>Press <code>(ctrl+p)</code>. You will see print preview of current page in a pop-up.</li>
													<li>Select printer destination. <a href="https://www.foxitsoftware.com" target="_blank">Foxit Pdf Reader</a> can be installed for printing pdf files instead for directly printing.</li>
													<li>Set Required Layout (Recommended is <code>Portrait</code>)</li>
													<li>Set Color (Recommended is <code>Black &amp; White</code> for normal printing)</li>
													<li>Click on <code>More Settings</code></li>
													<li>Set Margins to <code>none</code></li>
													<li>Set Quality to <code>72 dpi</code></li>
													<li>Set Scale to <code>100</code></li>
													<li>Check <code>Background graphics</code></li>
													<li>Thats all! You can now start printing using chrome browser.</li>
												</ul>
											</div>
										</div>
										<p></p>
									</div>
									<div class="mb-4" id="firefox">
										<h3 class="font-weight-bold">Firefox Browser</h3>
										<p>Follow the instruction to setup firfox browser for printing:</p>
										<div class="row">
											<div class="col-md-12">
												<ul class="list mb-3">
													<li>Go to firefox menu and click on Print.</li>
													<li>Click on page setup or press <code>alt+u</code></li>
													<li>Set Orientation (Recommended is <code>Portrait</code>)</li>
													<li>Set scale to <code>100%</code></li>
													<li>Check <code>Print background graphics</code></li>
													<li>Go to <code>Margins &amp; Header/Footer</code> </li>
													<li>Set margins to <code>0</code> for top,right,bottom and left side</li>
													<li>Set all headers and footer to <code> --blank--</code></li>
													<li>Thats all! You can now start printing using firefox browser.</li>
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
												<li class="nav-item"><a href="#chrome" class="nav-link">Chrome Browser</span></a></li>
												<li class="nav-item"><a href="#firefox" class="nav-link">Firefox Browser</span></a></li>
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