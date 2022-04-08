<?php
// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////
$filter=array('campus_id'=>$this->CAMPUSID);
// if(isset($form['alumni']) ){$filter['class_id']=0;}
if(isset($form['catagory']) && !empty($form['catagory'])){$filter['catagory']=$form['catagory'];}
if(isset($form['year']) && !empty($form['year'])){$filter['year']=$form['year'];}
if(isset($form['price'])){$form['price']<1 ? $filter['price']=0 : $filter['price > ']=$form['price'];}
if(isset($form['stock'])){$form['stock']<1 ? $filter['stock']=0 : $filter['stock > ']=$form['stock'];}
$params=array();
if(isset($form['search']) && !empty($form['search'])){
	$like=array();
	$fltr='';
	if(isset($form['filter'])&&!empty($form['filter']) ){$fltr=$form['filter'];}
	if(empty($fltr)){
	    $search=array('name','sub_title','author','sub_author','publisher','isbn','accession_number','ddc_number','placement_number','year');
		foreach($search as $val){$like[$val]=$form['search'];} 
	}else{
		$like[$fltr]=$form['search'];
	}
	$params['like']=$like;
}     
$params['orderby']='name ASC, placement_number ASC';
$rows=$this->lib_book_m->get_rows($filter,$params);
?>
<!-- Main content -->
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
		                <th class="font-weight-semibold text-center">Book Name</th>
		                <th class="font-weight-semibold text-center">Author</th>
		                <th class="font-weight-semibold text-center">Stock</th>
		                <?php if(isset($form['p_subtitle'])){?>
		                	<th class="font-weight-semibold text-center">Sub Title</th><?php }?>
		                <?php if(isset($form['p_subauthor'])){?>
		                	<th class="font-weight-semibold text-center">Sub Author</th><?php }?>
		                <?php if(isset($form['p_price'])){?>
		                	<th class="font-weight-semibold text-center">Price</th><?php }?>
		                <?php if(isset($form['p_cat'])){?>
		                	<th class="font-weight-semibold text-center">Catrgory</th><?php }?>
		                <?php if(isset($form['p_statement'])){?>
		                	<th class="font-weight-semibold text-center">Statement</th><?php }?>
		                <?php if(isset($form['p_publisher'])){?>
		                	<th class="font-weight-semibold text-center">Publisher</th><?php }?>
		                <?php if(isset($form['p_isbn'])){?>
		                	<th class="font-weight-semibold text-center">ISBN</th><?php }?>
		                <?php if(isset($form['p_placement'])){?>
		                	<th class="font-weight-semibold text-center">Placement Number</th><?php }?>
		                <?php if(isset($form['p_accession'])){?>
		                	<th class="font-weight-semibold text-center">Accession Number</th><?php }?>
		                <?php if(isset($form['p_ddc'])){?>
		                	<th class="font-weight-semibold text-center">DDC Number</th><?php }?>
		                <?php if(isset($form['p_volume'])){?>
		                	<th class="font-weight-semibold text-center">Volume</th><?php }?>
		                <?php if(isset($form['p_binding'])){?>
		                	<th class="font-weight-semibold text-center">Binding</th><?php }?>
		                <?php if(isset($form['p_year'])){?>
		                	<th class="font-weight-semibold text-center">Year</th><?php }?>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php
		        	$i=0;
		        	$total_stock=0;
		        	if(count($rows)<1){
		        		?>
		        		<tr>
			            	<td colspan="4"><span class="font-weight-semibold text-danger">No Record Found!</span></td>			                
			            </tr>
		        	<?php
		        	}else{
			        	foreach($rows as $row){
			        		$total_stock+=$row['stock'];
			        	?>
			            <tr>
			            	<td class="text-center"><?php print ++$i;?></td>
		                	<td class="text-center"><?php print ucwords(strtolower($row['name']));?></td>
		                	<td class="text-center"><?php print ucwords(strtolower($row['author']));?></td>
		                	<td class="text-center"><?php print ucwords(strtolower($row['stock']));?></td>
		                	<?php if(isset($form['p_subtitle'])){?>
		                		<td class="text-center"><?php print $row['sub_title'];?></td><?php }?>
		                	<?php if(isset($form['p_subauthor'])){?>
		                		<td class="text-center"><?php print $row['sub_author'];?></td><?php }?>
		                	<?php if(isset($form['p_price'])){?>
		                		<td class="text-center"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$row['price'];?></td><?php }?>
		                	<?php if(isset($form['p_cat'])){?>
		                		<td class="text-center"><?php print $row['catagory'];?></td><?php }?>
		                	<?php if(isset($form['p_statement'])){?>
		                		<td class="text-center"><?php print $row['statement'];?></td><?php }?>
		                	<?php if(isset($form['p_publisher'])){?>
		                		<td class="text-center"><?php print $row['publisher'];?></td><?php }?>
		                	<?php if(isset($form['p_isbn'])){?>
		                		<td class="text-center"><?php print $row['isbn'];?></td><?php }?>
		                	<?php if(isset($form['p_placement'])){?>
		                		<td class="text-center"><?php print $row['placement_number'];?></td><?php }?>
		                	<?php if(isset($form['p_accession'])){?>
		                		<td class="text-center"><?php print $row['accession_number'];?></td><?php }?>
		                	<?php if(isset($form['p_ddc'])){?>
		                		<td class="text-center"><?php print $row['ddc_number'];?></td><?php }?>
		                	<?php if(isset($form['p_volume'])){?>
		                		<td class="text-center"><?php print $row['volume'];?></td><?php }?>
		                	<?php if(isset($form['p_binding'])){?>
		                		<td class="text-center"><?php print $row['binding'];?></td><?php }?>
		                	<?php if(isset($form['p_year'])){?>
		                		<td class="text-center"><?php print $row['year'];?></td><?php }?>
			            </tr>
			        	<?php } 
		        	}?>
		        </tbody>
		    </table>
			    <br>
			</div>

			<?php if(isset($form['p_analytics'])){ ?>
			<br><br>
			<div class="row">
				<div class="col-sm-3 border-solid text-center">
					<span class="text-bold">Available Books :</span>
				</div>
				<div class="col-sm-3 grd-bg-orange border-white text-center">
					<span class="text-bold"> <?php print $total_stock;?> </span>
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
	<?php print $this->config->item('app_print_code');?>0003 <span class="page-footer-right"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
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
							<input type="checkbox" name="p_cat" 
								<?php print isset($form['p_cat']) ? 'checked':'';?>>
								Show Category							
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_price" 
								<?php print isset($form['p_price']) ? 'checked':'';?>>
								Show Price							
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_subauthor" 
								<?php print isset($form['p_subauthor']) ? 'checked':'';?>>
								Show Subauthor							
						</div>	
						<div class="col-md-3">
							<input type="checkbox" name="p_subtitle" 
								<?php print isset($form['p_subtitle']) ? 'checked':'';?>>
								Show Subtitle							
						</div>	
						<div class="col-md-3">
							<input type="checkbox" name="p_statement" 
								<?php print isset($form['p_statement']) ? 'checked':'';?>>
								Show Statement							
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_publisher" 
								<?php print isset($form['p_publisher']) ? 'checked':'';?>>
								Show Publisher							
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_isbn" 
								<?php print isset($form['p_isbn']) ? 'checked':'';?>>
								Show ISBN							
						</div>	
						<div class="col-md-3">
							<input type="checkbox" name="p_placement" 
								<?php print isset($form['p_placement']) ? 'checked':'';?>>
								Show Placement							
						</div>	
						<div class="col-md-3">
							<input type="checkbox" name="p_accession" 
								<?php print isset($form['p_accession']) ? 'checked':'';?>>
								Show Accession							
						</div>	
						<div class="col-md-3">
							<input type="checkbox" name="p_ddc" 
								<?php print isset($form['p_ddc']) ? 'checked':'';?>>
								Show DDc							
						</div>	
						<div class="col-md-3">
							<input type="checkbox" name="p_volume" 
								<?php print isset($form['p_volume']) ? 'checked':'';?>>
								Show Volume							
						</div>		
						<div class="col-md-3">
							<input type="checkbox" name="p_binding" 
								<?php print isset($form['p_binding']) ? 'checked':'';?>>
								Show Binding							
						</div>		
						<div class="col-md-3">
							<input type="checkbox" name="p_year" 
								<?php print isset($form['p_year']) ? 'checked':'';?>>
								Show Year							
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
