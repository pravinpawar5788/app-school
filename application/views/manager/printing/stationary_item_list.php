<?php
// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////
$filter=array('campus_id'=>$this->CAMPUSID);
// if(isset($form['alumni']) ){$filter['class_id']=0;}
if(isset($form['type']) && !empty($form['type'])){$filter['type']=$form['type'];}
if(isset($form['price']) && !empty($form['price'])){$form['price']<1 ? $filter['item_price']=0 : $filter['item_price > ']=$form['price'];}
if(isset($form['stock']) && !empty($form['stock'])){$form['stock']<1 ? $filter['qty']=0 : $filter['qty > ']=$form['stock'];}
$params=array();
$params['orderby']='type ASC, item ASC, item_price ASC';
$rows=$this->stationary_m->get_rows($filter,$params);
?>
<!-- Main content  -->
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
			                <th class="font-weight-semibold text-center" width="5%">#</th>
			                <?php if(isset($form['p_type'])){?>
			                	<th class="font-weight-semibold text-center">Category</th>
			                <?php }?>
			                <th class="font-weight-semibold text-center">Item Name</th>
			                <th class="font-weight-semibold text-center">Stock Qty</th>
			                <?php if(isset($form['p_price'])){?>
			                	<th class="font-weight-semibold text-center">Item Price</th>
			                <?php }?>
			                <?php if(isset($form['p_description'])){?>
			                	<th class="font-weight-semibold text-center">Description</th>
			                <?php }?>
			            </tr>
			        </thead>
			        <tbody>

			        	<?php
			        	$i=0;
			        	$total_stock=0;
			        	$total_stock_amount=0;
			        	if(count($rows)<1){
			        		?>
			        		<tr>
				            	<td colspan="4"><span class="font-weight-semibold text-danger">No Record Found!</span></td>			                
				            </tr>
			        	<?php
			        	}else{
				        	foreach($rows as $row){
			        		$total_stock+=$row['qty'];
			        		$total_stock_amount+=$row['qty']*$row['item_price'];
				        	?>
				            <tr>
				            	<td class="text-center"><?php print ++$i;?></td>
			                	<?php if(isset($form['p_type'])){?>
			                		<td class="text-center"><?php print ucwords(strtolower($row['type']));?></td>
			                	<?php }?>
			                	<td class="text-center">
			                		<?php print ucwords(strtolower($row['item']));?></td>
			                	<td class="text-center">
			                		<?php print ucwords(strtolower($row['qty']));?></td>
			                	<?php if(isset($form['p_price'])){?>
			                		<td class="text-center"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$row['item_price'];?></td>
			                	<?php }?>
			                	<?php if(isset($form['p_description'])){?>
			                		<td class="text-center"><?php print $row['description'];?></td><?php }?>
				            </tr>
				        	<?php } 
			        	}?>
			        </tbody>
			    </table>
			    <br>
			</div>
			<?php if(isset($form['p_analytics'])){?>
			<br><br>
			<div class="row">
				<div class="col-sm-3 border-solid text-center">
					<span class="text-bold">Available Stock Items :</span>
				</div>
				<div class="col-sm-3 grd-bg-orange border-white text-center">
					<span class="text-bold"> <?php print $total_stock;?> </span>
				</div>
				<div class="col-sm-3 border-solid text-center">
					<span class="text-bold">Available Stock Worth :</span>
				</div>
				<div class="col-sm-3 grd-bg-orange border-white text-center">
					<span class="text-bold"> <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$total_stock_amount;?> </span>
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
	<?php print $this->config->item('app_print_code');?>0001 <span class="page-footer-right"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
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
							<input type="checkbox" name="p_type" 
								<?php print isset($form['p_type']) ? 'checked':'';?>>
								Show Category							
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_price" 
								<?php print isset($form['p_price']) ? 'checked':'';?>>
								Show Price							
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_description" 
								<?php print isset($form['p_description']) ? 'checked':'';?>>
								Show Desciption							
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
							<input type="checkbox" name="alumni" 
								<?php print isset($form['alumni']) ? 'checked':'';?>>
								Only Show Alumni Students							
						</div> -->					
					</div>
					<div class="row">
						<div class="col-sm-3">
							<label class="text-muted">Minimum Stock</label>          
							<select class="form-control select" name="stock" data-fouc>
						    <option value="" />Minim Stock
						    <option value="0" <?php print isset($form['stock'])&&$form['stock']=='0'?'selected':'';?>/>Out of Stock
						    <option value="5" <?php print isset($form['stock'])&&$form['stock']=='5'?'selected':'';?>/>Minimum 5 units
						    <option value="10" <?php print isset($form['stock'])&&$form['stock']=='10'?'selected':'';?>/>Minimum 10 units
							</select>
						</div>
						<div class="col-sm-3">
							<label class="text-muted">Mimmum Price</label>          
							<select class="form-control select" name="price" data-fouc>
						    <option value="" />All
						    <option value="0" <?php print isset($form['price'])&&$form['price']=='0'?'selected':'';?>/>Free Items
						    <option value="50" <?php print isset($form['price'])&&$form['price']=='50'?'selected':'';?>/>Minimum 50 
						    <option value="100" <?php print isset($form['price'])&&$form['price']=='100'?'selected':'';?>/>Minimum 100 
							</select>
						</div>	
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
