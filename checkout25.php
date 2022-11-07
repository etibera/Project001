<?php
	include "common/headertest.php";
	require_once "model/checkoutLatest.php";	
	require_once "model/cart_new.php";
	$id =isset($_SESSION['user_login']) ? $_SESSION['user_login']: 0;
	
	$cart_model = new Cart_new();
	$checkout = new CheckoutLatest();
	$total_discount_Wallet=$cart_model->total_discount_Wallet($id) ;
	$total_cash_Wallet=$cart_model->total_cash_Wallet($id) ;
	$digitalwalletr_val =isset($_SESSION['digitalwallet']) ? $_SESSION['digitalwallet']: 0; 
	$digitalwallet_cash_val =isset($_SESSION['digitalwallet_cash']) ? $_SESSION['digitalwallet_cash']: 0; 
	$custDeffAdd=0;
	$landbankacc=$checkout->Getlandbankacc($id);

	if($Getdefaultaddress['region']!=""){	
		$custDeffAdd=$Getdefaultaddress['address_id'];
	}
	if(isset($_POST['checkout_cart']) || isset($_GET['checkout_cart'])){
		if (isset($_POST['chkcart_id']) || isset($_GET['checkout_cart']) ){
			$total_amount_chk=0;
			$shipping_wallet=$checkout->GetShippingWallet($id);
			if(isset($_POST['chkcart_id'])){
				$chk_cart_id=$_POST['chkcart_id'];
			}else if(isset($_GET['checkout_cart'])){
				$getcart_id = array();
				array_push($getcart_id, $_GET['checkout_cart']);
				$chk_cart_id=$getcart_id;
			}	
			$count_pickupfromstore=$checkout->count_pickupfromstore($id,$chk_cart_id);
			$count_peso_mal=$checkout->count_peso_mall($id,$chk_cart_id);
			$count_global_product=$checkout->count_global_mall($id,$chk_cart_id);
			$local_item_selected=0;
			$global_item_selected=0;

		}else{
			
			header("location: cart.php");
			$_SESSION['error_chkcart_id']="Please Select Product To check Out!";
		}		
	}else{
		 header("location: cart.php");
	}
?>
<style type="text/css">
	 #div-hs{
    background: #fff;
    border-radius: 10px;
    margin: 5px 5px;
    height: 110px;
    box-shadow: 0px 0px 9px -2px rgb(0 0 0 / 35%)
  }
   #div-hs:hover {
  border: 1px solid #777;
}
</style>
<div class="container">
	<div class="" style="margin-top: 131px;">
	<?php if($_SESSION['mobile_statuscode']==0){?> 
		<div class="row-home">  
		    <div class="row p-0">     
		      <div class="col-sm-12 p-1"> 
				<?php $customer_detals=$checkout->getCustomerTelephone($id);
				if($customer_detals['telephone']=="" || $customer_detals['firstname']=="" || $customer_detals['lastname']==""){ ?>					
			        <div class="card border-0" style="background: #D3D3D3;">
			          	<div class="card-header text-center text-dark border-0" >
			          		<h2>Update Personal Information First</h2>
			          	</div>
			           	<div class="card-body border-0 bg-light" >  
			            	 <div class="d-flex justify-content-between">
			            	 	<div>
			            	 		 <p><strong>Already have an account?</strong> <a href="#"  data-bs-toggle="modal" data-bs-target="#LoginModalexist">Log in</a></p>
			            	 	</div>
			            	 	<div>
			            	 		<a href="account.php?checkout=1" class="btn btn-primary" role="button">Update Information</a>
			            	 	</div>
						    </div>
			           </div>
			        </div>  
				<?php }else{ ?>
					<div class="card border-0" style="background: #D3D3D3;">
			          	<div class="card-header text-center text-dark border-0 " >
			          		<h2>Verify Your Mobile No.first</h2>
			          	</div>
			           	<div class="card-body border-0 bg-light" >  
			            	 <div class="d-flex justify-content-between">
			            	 	<div>
			            	 		 < <p><strong>Already have an account?</strong> <a href="#"  data-bs-toggle="modal" data-bs-target="#LoginModalexist">Log in</a></p>
			            	 	</div>
			            	 	<div>
			            	 		<a href="reg_activatemobile.php?RegIdVal=<?php echo $id;?>&checkout=1" class="btn btn-primary" role="button">Verify</a>
			            	 	</div>
						    </div>
			           </div>
			        </div> 
				<?php } ?>
		       </div>
		    </div>
	  	</div>
	<?php }else if($Getdefaultaddress['region']==""){?>
		<div class="row-home">  
		    <div class="row p-0">     
		      	<div class="col-sm-12 p-1"> 
		      		<div class="card border-0" style="background: #D3D3D3;">
			          	<div class="card-header text-center text-dark border-0" >
			          		<h2>Set your Address First</h2>
			          	</div>
			           	<div class="card-body border-0 bg-light">  
			            	 <div class="d-flex justify-content-between">
			            	 	<div>
			            	 		 <p><strong>Already have an account?</strong> <a href="#"  data-bs-toggle="modal" data-bs-target="#LoginModalexist">Log in</a></p>
			            	 	</div>
			            	 	<div>
			            	 		<a href="address_mod_update.php?aid=0&checkout=1" class="btn btn-primary" role="button">Update Address</a>
			            	 	</div>
						    </div>
			           </div>
			        </div> 
		 		</div>
		    </div>
	  	</div>
	<?php }else{?>
		<div class="row-home">  
		    <div class="row p-0 ">     
		      	<div class="col-sm-12 p-1">
		      		<div class="card border-0" style="background: #D3D3D3;">
			          	<div class="card-header text-center text-dark border-0" >
			          		<h2>Payment</h2>
			          	</div>
			           	<div class="card-body border-0 bg-light" >  
			           		<div class="d-flex justify-content-end mb-3">
			           			<div ><a href="address_mod_update.php?aid=0&chk=0" class="btn btn-primary" role="button" >Add Address</a></div>
			           		</div>

							<form action="submit_new.php" method="post" enctype="multipart/form-data">
								<input type="hidden" name="customer_id" value="<?php echo $id;?>">
								<!-- for wallet -->
				            		<?php if(isset($_GET['checkout_cart'])){ ?>
				            			<div class="accordion accordion-flush" id="accordionFlushExample">
				            				<!-- for  Use Cash Walle -->
				            				<?php if($total_cash_Wallet!=0){ ?>	
											<div class="accordion-item">										  	
											    <h2 class="accordion-header" id="flush-headingOne">
											     	<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
											       Use Cash Wallet
											      </button>
											    </h2>
										    	<div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
										      		<div class="accordion-body">
										      			<div class="panel-body">
										      				<div class="row mb-2">
							         			 	 			<label class="col-sm-2 control-label" for="input-voucher" style="text-align: right;">Total Amount :</label>
							         			 	 			<label class="col-sm-4 control-label" for="input-voucher">
							         			 	 				<span style="font-weight: bolder;"><?php echo number_format($total_cash_Wallet, 2); ?></span>
							         			 	 			</label>         			 	 
									         			 	</div>
									         			 	<div class="row mb-2">
									         			 		<label class="col-sm-2 control-label" for="input-voucher" style="text-align: right;">Preferred Amount:</label>
									         			 	 	<div class="col-sm-4 input-group" >
									         			 	 		<input  type="number" name="CASHwallet" value="<?php echo $digitalwallet_cash_val; ?>" placeholder="Input your Preferred Amount"  class="form-control" />
									         			 	 		<span class="input-group-btn">
									         			 	 			<?php if($digitalwallet_cash_val==0){ ?>
									         			 	 				<a  id="button-wallet_CASH" class="btn btn-primary" data-toggle="tooltip" title="Apply"><i class="fas fa-save"></i></a>
									         			 	 			<?php }else{ ?>
									         			 	 				<a  id="button-wallet_CASH-cancel" class="btn btn-danger" data-toggle="tooltip" title="Cancel"><i class="fas fa-times-circle"></i></a>
									         			 	 			<?php }?>   
									         			 	 		</span>
									         			 	 	</div>
									         			 	</div>	
										      			</div><!-- end for panel-body -->	
										      		</div><!-- end for accordion-body -->	
											    </div><!-- end for accordion-collapse -->	
										  	</div><!-- end for accordion-item -->	
											<?php }?><!--end for  Use Cash Walle -->
											<!--for  Use discount Walle -->											
											<?php if($total_discount_Wallet!=0){ ?>
										  	<div class="accordion-item">
											    <h2 class="accordion-header" id="flush-headingTwo">
											      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
											       Use Discount Wallet
											      </button>
											    </h2>
											    <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
											      	<div class="accordion-body">
											      		<div class="panel-body">
											      			<div class="row mb-2">
									         			 	 	<label class="col-sm-2 control-label" for="input-voucher" style="text-align: right;">Total Amount :</label>
									         			 	 	<label class="col-sm-4 control-label" for="input-voucher">
									         			 	 		<span style="font-weight: bolder;"><?php echo number_format($total_discount_Wallet, 2); ?></span>
									         			 	 	</label>         			 	 
									         			 	</div>
									         			 	<div class="row mb-2">
										         			 	<label class="col-sm-2 control-label" for="input-voucher" style="text-align: right;">Preferred Amount:</label>
									         			 	 	<div class="col-sm-4 input-group" >
									         			 	 		<input type="number" name="digitalwallet" value="<?php echo $digitalwalletr_val; ?>" placeholder="Input your Preferred Amount"  class="form-control" />
									         			 	 		<span class="input-group-btn">
									         			 	 				<?php if($digitalwalletr_val==0){ ?>
										         			 	 				<a  id="button-wallet"  class="btn btn-primary" data-toggle="tooltip" title="Apply"><i class="fas fa-save"></i></a>
										         			 	 			<?php }else{ ?>
										         			 	 				<a  id="button-wallet_cancel" class="btn btn-danger" data-toggle="tooltip" title="Cancel"><i class="fas fa-times-circle"></i></a>
										         			 	 			<?php }?>  
									         			 	 		</span>
									         			 	 	</div>         			 	 	
										         			</div>
										         			<div class="row mb-2">
									         			 		<label class="col-sm-12 control-label text-danger" for="input-voucher" style="text-align: left;">*Note: Discount wallet must not exceed 50% of the total purchase amount.</label>
									         			 	</div>
											      		</div><!-- end for panel-body -->	
											     	</div><!-- end for accordion-body -->	
											    </div><!-- end for accordion-collapse -->	
										  	</div><!-- end for accordion-item -->
										  	<?php }?><!--end for  Use discount Walle -->		
										</div><!-- end for accordion -->										
				            		<?php }?> 
				            	<!--end for wallet -->
				            	<!-- for Shipping Adress -->
				            	<div class="form-group" style="display: none;">
									<label for="">Payment Address</label>
									<select name="b_address" class="form-control" required>
										<option value="">--Select Payment Adress--</option>
										<?php foreach($checkout->address($id) as $address):?>
											<?php if($Getdefaultaddress['region']!=""){ ?> 
												<?php if($Getdefaultaddress['address_id']==$address['address_id']){ ?>
													 <option value="<?php echo $address['address_id']; ?>" selected><?php echo $address['text'];?></option>		
												<?php }else{ ?>
													<option value="<?php echo $address['address_id']; ?>"><?php echo $address['text'];?></option>
												<?php }?>
											<?php }else{?> 
												<option value="<?php echo $address['address_id']; ?>"><?php echo $address['text'];?></option>
											<?php }?>							
										<?php endforeach;?>
									</select>
								</div>
			            		<div class="mb-3 mt-3">
								  	<label for="shippingAdress" class="form-label">Shipping Adress:</label>
									<select name="d_address" id="shippingAdress" class="form-control" onChange='selectshippingAdress(this,<?php echo $id; ?>,<?php echo json_encode($chk_cart_id);?>)'required>
										<option value="">--Select Shipping Adress--</option>
										<?php foreach($checkout->address($id) as $address2):?>
											<?php if($Getdefaultaddress['region']!=""){ ?> 
												<?php if($Getdefaultaddress['address_id']==$address2['address_id']){ ?>
													 <option value="<?php echo $address2['address_id']; ?>" selected><?php echo $address2['text'];?></option>		
												<?php }else{ ?>
													<option value="<?php echo $address2['address_id']; ?>"><?php echo $address2['text'];?></option>
												<?php }?>
											<?php }else{?> 
												<option value="<?php echo $address2['address_id']; ?>"><?php echo $address2['text'];?></option>
											<?php }?>
										<?php endforeach;?>
									</select>
								</div>
								<!--end for Shipping Adress -->
								<!-- For Local Mall -->
								<?php if($count_peso_mal!=0){ ?>	
									<div class="table-responsive bg-light">
							 			<table class="table table-hover table-light table-striped" >
						 					<?php $localDataStores=$checkout->get_cart_local_perstore($id,$chk_cart_id,$custDeffAdd);
											$delstats=0;
											$pickupstats=0;
											/*echo"<pre>";
											print_r($localDataStores);*/ ?>
							 				<?php $items_row = 0; ?>
							 				<?php $orderNO = 1; ?>
											<?php $total = 0; ?>
											<?php $totalflatrate = 0; ?>
											<?php $insurance_feetotal = 0; ?>
											<tr class="table-active">
												<thead>
												<th colspan="4" >PESO Mall</th>	
												<th colspan="5" style="vertical-align: middle;text-align: center;" >	
												</th>
												<th colspan="2" >
												</th>
												</thead>
											</tr>
											<?php if(count($localDataStores)){?>
											<tr class="table-active">							
													<th colspan="2">Store</th>
													<th colspan="2">Image</th>
													<th>Product Name</th>
													<th>Original Price</th>
													<th>Promo/Discount</th>
													<th>Freebies</th>
													<th>Quantity</th>
													<th>Price w/ Discount</th>
													<th>Shipping Fee</th>
													<th>Total Price</th>
											</tr>
												
												<?php foreach($localDataStores as $local_stores): ?>
													<?php $total_amount_chk+=$local_stores['total_price'];?>
													<?php $insurance_feetotal+=$local_stores['insurance_fee'];?>
													<?php $totalflatrate+=$local_stores['flatrate'];?>
													<?php if($local_stores['delstats']!='Yes'){$delstats++;}?>
													<?php if($local_stores['pickupstats']!='Yes'){$pickupstats++;} ?>
													<!-- for store orders -->
													<input type="hidden" name="store_orders[<?php echo $items_row; ?>][seller_id]" value="<?php echo $local_stores['seller_id'];?>"  class="form-control" />
													<input type="hidden" name="store_orders[<?php echo $items_row; ?>][branch_id]" value="<?php echo $local_stores['branch_id'];?>"  class="form-control" />
													<input type="hidden" name="store_orders[<?php echo $items_row; ?>][orderNO]" value="<?php echo $orderNO;?>"  class="form-control" />
													<!-- end for store orders -->
													<!--  for shipping_per_store -->
													<input type="hidden" name="shipping_per_store[<?php echo $items_row; ?>][orderNo]" value="<?php echo $orderNO;?>"  class="form-control" />	
													<input type="hidden" name="shipping_per_store[<?php echo $items_row; ?>][seller_id]" value="<?php echo $local_stores['seller_id'];?>"  class="form-control" />	
													<input type="hidden" name="shipping_per_store[<?php echo $items_row; ?>][branch_id]" value="<?php echo $local_stores['branch_id'];?>"  class="form-control" />	
													<input type="hidden" name="shipping_per_store[<?php echo $items_row; ?>][val]" value="<?php echo $local_stores['flatrate'];?>" id="val_ship_<?php echo $local_stores['cart_id'];?>" class="form-control shipping_per_storeValFlat" />
													<input type="hidden" name="shipping_per_store[<?php echo $items_row; ?>][other_shipping]" value="0.00" id="val_shipother_<?php echo $local_stores['seller_id'];?>_<?php echo $local_stores['branch_id'];?>" class="form-control shipping_per_storeValother" />	
													<input type="hidden" name="shipping_per_store[<?php echo $items_row; ?>][title]" value="Flat Shipping Rate " id="val_ship_title_<?php echo $local_stores['seller_id'];?>_<?php echo $local_stores['branch_id'];?>" class="form-control val_ship_title_val"/>	
													<!-- end for shipping_per_store -->
													<!-- prod_item -->
													<input type="hidden" name="prod_item[<?php echo $items_row; ?>][orderNo]" value="<?php echo $orderNO;?>"  class="form-control" />	
													<input type="hidden" name="prod_item[<?php echo $items_row; ?>][branch_id]" value="<?php echo $local_stores['branch_id'];?>"  class="form-control" />	
													<input type="hidden" name="prod_item[<?php echo $items_row; ?>][cart_id]" value="<?php echo $local_stores['cart_id'];?>"  class="form-control" />	
													<input type="hidden" name="prod_item[<?php echo $items_row; ?>][p_type]" value="<?php echo $local_stores['p_type'];?>"  class="form-control" />	
													<input type="hidden" name="prod_item[<?php echo $items_row; ?>][product_id]" value="<?php echo  utf8_encode($local_stores['product_id']);?>"  class="form-control" />
													<input type="hidden" name="prod_item[<?php echo $items_row; ?>][name]" value="<?php echo  utf8_encode($local_stores['name']);?>"  class="form-control" />
													<input type="hidden" name="prod_item[<?php echo $items_row; ?>][model]" value="<?php echo  utf8_encode($local_stores['model']);?>"  class="form-control" />
													<input type="hidden" name="prod_item[<?php echo $items_row; ?>][quantity]" value="<?php echo  utf8_encode($local_stores['quantity']);?>"  class="form-control" />
													<input type="hidden" name="prod_item[<?php echo $items_row; ?>][price]" value="<?php echo   number_format($local_stores['price'], 2, '.', '');?>"  class="form-control" />	
													<input type="hidden" name="prod_item[<?php echo $items_row; ?>][shipping_code]" value="<?php echo $local_stores['shipping_code'];?>" class="form-control" />
													<input type="hidden" name="prod_item[<?php echo $items_row; ?>][shipping_name]" value="<?php echo $local_stores['shipping_name'];?>" class="form-control" />	
													<input type="hidden" name="prod_item[<?php echo $items_row; ?>][shipping_fee]" value="<?php echo $local_stores['shipping_fee'];?>" class="form-control" />
													<input type="hidden" name="prod_item[<?php echo $items_row; ?>][total_price]" value="<?php echo   number_format($local_stores['total_price'], 2, '.', '');?>"  class="form-control" />
													<input type="hidden" name="prod_item[<?php echo $items_row; ?>][poa_name]" value=""  class="form-control" />	
													<input type="hidden" name="prod_item[<?php echo $items_row; ?>][warehouse_code]" value=""  class="form-control" />	
													<input type="hidden" name="prod_item[<?php echo $items_row; ?>][poa_ids]" value=""  class="form-control" />	
													<input type="hidden" name="prod_item[<?php echo $items_row; ?>][seller_id]" value="<?php echo $local_stores['seller_id'];?>"  class="form-control" />	
													<input type="hidden" name="prod_item[<?php echo $items_row; ?>][discount_details]" value="<?php echo $local_stores['discount_details'];?>"  class="form-control" />	
													<input type="hidden" name="prod_item[<?php echo $items_row; ?>][freebies]" value="<?php echo $local_stores['freebies'];?>"  class="form-control" />
													<!-- end prod_item -->
													<!--  total_per_store -->
													<input type="hidden" name="total_per_store[<?php echo $items_row; ?>][orderNo]" value="<?php echo $orderNO;?>"  class="form-control" />	
													<input type="hidden" name="total_per_store[<?php echo $items_row; ?>][branch_id]" value="<?php echo $local_stores['branch_id'];?>"  class="form-control" />
													<input type="hidden" name="total_per_store[<?php echo $items_row; ?>][seller_id]" value="<?php echo $local_stores['seller_id'];?>"  class="form-control" />
													<input type="hidden" name="total_per_store[<?php echo $items_row; ?>][sub_total]" value="<?php echo $local_stores['total_price'];?>" id="val_sub_total_per_store<?php echo $local_stores['seller_id'];?>_<?php echo $local_stores['branch_id'];?>" class="form-control" />	
													<input type="hidden" name="total_per_store[<?php echo $items_row; ?>][system_charges_credit_card]" value="<?php echo $local_stores['credit_card'];?>" class="form-control" />
													<input type="hidden" name="total_per_store[<?php echo $items_row; ?>][system_charges_maxx_payment]" value="<?php echo $local_stores['maxx_payment'];?>" class="form-control" />											
													<input type="hidden" name="total_per_store[<?php echo $items_row;?>][flatrate]"  id="inputflatrate_<?php echo $local_stores['cart_id'];?>"value="<?php echo $local_stores['flatrate'];?>"  class="form-control" />
													<input type="hidden" name="total_per_store[<?php echo $items_row;?>][other_shipping]" value="0.00" class="form-control" />
													<input type="hidden" name="total_per_store[<?php echo $items_row; ?>][total]" value="<?php echo $local_stores['total_price'];?>" class="form-control" />
													<input type="hidden" name="total_per_store[<?php echo $items_row; ?>][insurance_fee]" value="<?php echo $local_stores['insurance_fee'];?>"  class="form-control insuranceFeePerStore" />
													<input type="hidden" name="total_per_store[<?php echo $items_row; ?>][insurance_fee_sp]" value="0"  class="form-control insuranceFeePerStore_sp" />
													<!-- end total_per_store -->

													<tr id="items-row<?php echo $items_row; ?>">
														<td colspan="2" style="vertical-align: middle;text-align: center;">
															<div id="div-hs">
														        <div data-toggle="tooltip" title="<?php echo $local_stores['shop_name'];?>" class="image-hps">											          
														            <img src="<?php echo 'img/'.$local_stores['branch_logo'];?>" style="width: 70px;height: 70px;margin:auto"/>
														        </div>
														        <div style="text-align: center;">
														           <small> <?php echo $local_stores['shop_name'];?></small>
														        </div>
														      </div>
														</td>
														<td class="text-center" colspan="2">	
															<img src="<?php echo "img/".$local_stores['img']; ?>"  class="img-responsive" style="width: 100px; width: 100px;" />	
														</td>
														<td style="color: blue"><?php echo  utf8_encode($local_stores['name']);?></td>
														<td><?php echo  number_format($local_stores['oprice'],2);?></td>
														<td><?php echo  utf8_encode($local_stores['discount_details']);?></td>
														<td><?php echo  utf8_encode($local_stores['freebies']);?></td>
														<td><?php echo  utf8_encode($local_stores['quantity']);?></td>
														<td class="text-right">
															<?php echo   number_format($local_stores['price'], 2);?>
														</td>
														<td class="text-right">
															<p class="StandardShippingRate" id="StandardShippingRate_<?php echo $local_stores['cart_id'];?>"><?php echo   number_format($local_stores['flatrate'], 2);?></p>
															<p class="OtherShippingRate" id="OtherShippingRate_<?php echo $local_stores['cart_id'];?>" style="display: none;">0.00</p>
															
														</td>
														<td class="text-right">
															<p class="SSRTotal" id="SSRTotal_<?php echo $local_stores['cart_id'];?>"><?php echo   number_format($local_stores['total_price']+$local_stores['flatrate'], 2);?></p>
															<p class="OtherSRTotal" id="OtherSRTotal_<?php echo $local_stores['cart_id'];?>" style="display: none;"><?php echo   number_format($local_stores['total_price'], 2);?></p>
															
														</td>

													</tr>
													<?php $total+=$local_stores['total_price']; ?>
													<?php $orderNO ++ ;$items_row++ ?>								
												<?php endforeach;?>	
											<?php }?>
											<?php $localDataStoresSmall=$checkout->get_cart_local_perstoreSmall($id,$chk_cart_id,$custDeffAdd);							
												/*echo"<pre>";
												print_r($localDataStoresSmall);*/ ?>
											<?php if(count($localDataStoresSmall)){?>
												<?php foreach($localDataStoresSmall as $local_storesS):  
													$newitems_row=0;
													$newitems_row=$items_row; ?>	

													<?php $total_amount_chk+=$local_storesS['subtotal'];?>
													<?php $insurance_feetotal+=$local_storesS['insurance_fee'];?>
													<?php $totalflatrate+=$local_storesS['flatrate'];?>
													<?php if($local_storesS['delstats']!='Yes'){$delstats++;}?>
													<?php if($local_storesS['pickupstats']!='Yes'){$pickupstats++;} ?>
												  <!-- for store orders small -->
													<input type="hidden" name="store_orders[<?php echo $items_row; ?>][seller_id]" value="<?php echo $local_storesS['seller_id'];?>"  class="form-control" />
													<input type="hidden" name="store_orders[<?php echo $items_row; ?>][branch_id]" value="<?php echo $local_storesS['branch_id'];?>"  class="form-control" />
													<input type="hidden" name="store_orders[<?php echo $items_row; ?>][orderNO]" value="<?php echo $orderNO;?>"  class="form-control" /><br>
													<!-- end for store orders small-->
													<!--  for shipping_per_store small-->
													<input type="hidden" name="shipping_per_store[<?php echo $items_row; ?>][orderNo]" value="<?php echo $orderNO;?>"  class="form-control" />	
													<input type="hidden" name="shipping_per_store[<?php echo $items_row; ?>][seller_id]" value="<?php echo $local_storesS['seller_id'];?>"  class="form-control" />	
													<input type="hidden" name="shipping_per_store[<?php echo $items_row; ?>][branch_id]" value="<?php echo $local_storesS['branch_id'];?>"  class="form-control" />	
													<input type="hidden" name="shipping_per_store[<?php echo $items_row; ?>][val]" value="<?php echo $local_storesS['flatrate'];?>" id="val_shipS_<?php echo $local_storesS['seller_id'].$local_storesS['branch_id'];?>" class="form-control shipping_per_storeValFlat" />
													<input type="hidden" name="shipping_per_store[<?php echo $items_row; ?>][other_shipping]" value="0.00" id="val_shipotherS_<?php echo $local_storesS['seller_id'].$local_storesS['branch_id'];?>" class="form-control shipping_per_storeValother" />	
													<input type="hidden" name="shipping_per_store[<?php echo $items_row; ?>][title]" value="Flat Shipping Rate "  class="form-control val_ship_title_val"/>
													<!-- end for shipping_per_store small-->

													<!--  total_per_store -->
													<input type="hidden" name="total_per_store[<?php echo $items_row; ?>][orderNo]" value="<?php echo $orderNO;?>"  class="form-control" />	
													<input type="hidden" name="total_per_store[<?php echo $items_row; ?>][branch_id]" value="<?php echo $local_storesS['branch_id'];?>"  class="form-control" />
													<input type="hidden" name="total_per_store[<?php echo $items_row; ?>][seller_id]" value="<?php echo $local_storesS['seller_id'];?>"  class="form-control" />
													<input type="hidden" name="total_per_store[<?php echo $items_row; ?>][sub_total]" value="<?php echo $local_storesS['subtotal'];?>" id="val_sub_total_per_store<?php echo $local_storesS['seller_id'];?>_<?php echo $local_storesS['branch_id'];?>" class="form-control" />	
													<input type="hidden" name="total_per_store[<?php echo $items_row; ?>][system_charges_credit_card]" value="<?php echo $local_storesS['credit_card'];?>" class="form-control" />
													<input type="hidden" name="total_per_store[<?php echo $items_row; ?>][system_charges_maxx_payment]" value="<?php echo $local_storesS['maxx_payment'];?>" class="form-control" />											
													<input type="hidden" name="total_per_store[<?php echo $items_row;?>][flatrate]"  id="inputflatrate_<?php echo $local_storesS['seller_id'].$local_storesS['branch_id'];?>"value="<?php echo $local_storesS['flatrate'];?>"  class="form-control" />
													<input type="hidden" name="total_per_store[<?php echo $items_row;?>][other_shipping]" value="0.00" class="form-control" />
													<input type="hidden" name="total_per_store[<?php echo $items_row; ?>][total]" value="<?php echo $local_storesS['subtotal'];?>" class="form-control" />
													<input type="hidden" name="total_per_store[<?php echo $items_row; ?>][insurance_fee]" value="<?php echo $local_storesS['insurance_fee'];?>"  class="form-control insuranceFeePerStore" />
													<input type="hidden" name="total_per_store[<?php echo $items_row; ?>][insurance_fee_sp]" value="0"  class="form-control insuranceFeePerStore_sp" />
													<!-- end total_per_store -->
													<tr id="items-row<?php echo $items_row; ?>" style="border-top:2px solid #333;">
														<td colspan="3" style="vertical-align: middle;text-align: center;">
															<div id="div-hs">
														        <div data-toggle="tooltip" title="<?php echo $local_storesS['shop_name'];?>" class="image-hps">											          
														            <img src="<?php echo 'img/'.$local_storesS['branch_logo'];?>" style="width: 70px;height: 70px;margin:auto"/>
														        </div>
														        <div style="text-align: center;">
														           <small> <?php echo $local_storesS['shop_name'];?></small>
														        </div>
														      </div>
														</td>									
														<td colspan="4" style="vertical-align: middle;text-align: center;"> 
															<label class="ShippinFeeFlat" >
																<span class="flatRatelabel"> Flat Shipping Rate</span>  :
																<span class="StandardShippingRate" id="StandardShippingRate_<?php echo $local_storesS['seller_id'].$local_storesS['branch_id'];?>"><?php echo   number_format($local_storesS['flatrate'], 2);?></span>
																<span class="OtherShippingRate" id="OtherShippingRate_<?php echo $local_storesS['seller_id'].$local_storesS['branch_id'];?>" style="display: none;">0.00</span>
															</label>
														</td>
														<td colspan="5"></td>
														
													</tr>
													<tr >									
														<th colspan="3">Image</th>
														<th colspan="2">Product Name</th>
														<th>Original Price</th>
														<th>Promo/Discount</th>
														<th>Freebies</th>
														<th>Quantity</th>
														<th>Price w/ Discount</th>
														<th colspan="2">Total Price</th>
													</tr>								
													<?php  foreach($local_storesS['details'] as $local_Sprd): ?>
														<!-- prod_item Small-->

														<input type="hidden" name="prod_item[<?php echo $newitems_row; ?>][orderNo]" value="<?php echo $orderNO;?>"  class="form-control" />	
														<input type="hidden" name="prod_item[<?php echo $newitems_row; ?>][branch_id]" value="<?php echo $local_storesS['branch_id'];?>"  class="form-control" />	
														<input type="hidden" name="prod_item[<?php echo $newitems_row; ?>][cart_id]" value="<?php echo $local_Sprd['cart_id'];?>"  class="form-control" />	
														<input type="hidden" name="prod_item[<?php echo $newitems_row; ?>][p_type]" value="<?php echo $local_Sprd['p_type'];?>"  class="form-control" />	
														<input type="hidden" name="prod_item[<?php echo $newitems_row; ?>][product_id]" value="<?php echo  utf8_encode($local_Sprd['product_id']);?>"  class="form-control" />
														<input type="hidden" name="prod_item[<?php echo $newitems_row; ?>][name]" value="<?php echo  utf8_encode($local_Sprd['name']);?>"  class="form-control" />
														<input type="hidden" name="prod_item[<?php echo $newitems_row; ?>][model]" value="<?php echo  utf8_encode($local_Sprd['model']);?>"  class="form-control" />
														<input type="hidden" name="prod_item[<?php echo $newitems_row; ?>][quantity]" value="<?php echo  utf8_encode($local_Sprd['quantity']);?>"  class="form-control" />
														<input type="hidden" name="prod_item[<?php echo $newitems_row; ?>][price]" value="<?php echo   number_format($local_Sprd['price'], 2, '.', '');?>"  class="form-control" />	
														<input type="hidden" name="prod_item[<?php echo $newitems_row; ?>][shipping_code]" value="<?php echo $local_Sprd['shipping_code'];?>" class="form-control" />
														<input type="hidden" name="prod_item[<?php echo $newitems_row; ?>][shipping_name]" value="<?php echo $local_Sprd['shipping_name'];?>" class="form-control" />	
														<input type="hidden" name="prod_item[<?php echo $newitems_row; ?>][shipping_fee]" value="<?php echo $local_Sprd['shipping_fee'];?>" class="form-control" />
														<input type="hidden" name="prod_item[<?php echo $newitems_row; ?>][total_price]" value="<?php echo   number_format($local_Sprd['total_price'], 2, '.', '');?>"  class="form-control" />
														<input type="hidden" name="prod_item[<?php echo $newitems_row; ?>][poa_name]" value=""  class="form-control" />	
														<input type="hidden" name="prod_item[<?php echo $newitems_row; ?>][warehouse_code]" value=""  class="form-control" />	
														<input type="hidden" name="prod_item[<?php echo $newitems_row; ?>][poa_ids]" value=""  class="form-control" />	
														<input type="hidden" name="prod_item[<?php echo $newitems_row; ?>][seller_id]" value="<?php echo $local_storesS['seller_id'];?>"  class="form-control" />	
														<input type="hidden" name="prod_item[<?php echo $newitems_row; ?>][discount_details]" value="<?php echo $local_Sprd['discount_details'];?>"  class="form-control" />	
														<input type="hidden" name="prod_item[<?php echo $newitems_row; ?>][freebies]" value="<?php echo $local_Sprd['freebies'];?>"  class="form-control" />
														<!-- end prod_item small-->
														<tr >										
															<td class="text-center" colspan="3">	
																<img src="<?php echo "img/".$local_Sprd['img']; ?>"  class="img-responsive" style="width: 100px; width: 100px;" />	
															</td>
															<td style="color: blue" colspan="2"><?php echo  utf8_encode($local_Sprd['name']);?></td>
															<td><?php echo  number_format($local_Sprd['oprice'],2);?></td>
															<td><?php echo  utf8_encode($local_Sprd['discount_details']);?></td>
															<td><?php echo  utf8_encode($local_Sprd['freebies']);?></td>
															<td><?php echo  utf8_encode($local_Sprd['quantity']);?></td>
															<td class="text-right">
																<?php echo   number_format($local_Sprd['price'], 2);?>
															</td>
															<td class="text-right" colspan="2">
																<?php echo   number_format($local_Sprd['total_price'], 2);?>	
															</td>
														</tr>
														<?php $newitems_row++; ?>
													<?php endforeach;?>	
												<?php $total+= $local_storesS['subtotal']; ?>
												<?php $orderNO ++ ; $items_row=$newitems_row; ?>	

												<?php endforeach;?>
											<?php }?>		
							 			</table>
								 	</div>
								 	<?php 
								 		$StorePFS=0;
										if($count_pickupfromstore=="0"){
											$StorePFS=20;
										}			 		
								 		if($delstats==0&&$pickupstats==0){
											$shipMethoData=$checkout->shipMethodQuadx($StorePFS);
										}else{
											$shipMethoData=$checkout->shipMethspecialdel($StorePFS);
										} ?>
								<?php } ?><!--For Local Mall  -->
								<?php if($shipping_wallet!=0){ ?>
									<div class="form-group">
										<input type="checkbox" id="useSW" name="useSW" onclick="UnCheckuseSW(event,<?php echo $shipping_wallet;?>)">
											<label for="scales"> Use Shipping Wallet - (<?php echo number_format($shipping_wallet,2)?> remaining)</label>
									</div>
								<?php }?>
								<!-- End For shipping_wallet -->
								<!--  For Payment Method -->
								<div class="mb-3 mt-3">
									<label for="payment_method" class="form-label">Payment Method</label>
									<select name="payment_method"  id="payment_method" class="form-control" required>
										<option value="">--Select Payment Method--</option>
										<?php foreach($checkout->paymentMethodLates($landbankacc['landbankacc'],$landbankacc['fgivesacc']) as $pm):
											if($pm['disable']!=true) { ?>
												<?php if($pm['name']=="Cash on Delivery") {?>
													<?php if($delstats==0&&$pickupstats==0) {?> 
														<?php if($total_amount_chk<=50000) { ?> 
															<?php if($checkout->get_cart_product_cod($id,$chk_cart_id)==0) { ?> 
																<option value="<?php echo $pm['value']; ?>"><?php echo $pm['name'];?></option>
															<?php }?>
														<?php }?>												
													<?php } ?>
												<?php }else if($pm['name']=="BDO Card Installment"){ ?>	
													<?php if($checkout->get_cart_product_installment($id,$chk_cart_id)==0) { ?> 
														<option value="<?php echo $pm['value']; ?>"><?php echo $pm['name'];?></option>
													<?php } ?> 
												<?php }else { ?>
													<option value="<?php echo $pm['value']; ?>"><?php echo $pm['name'];?></option>
												<?php } ?> 
										<?php } endforeach;?>
									</select>
								</div>
								<!-- End For Payment Method -->
								<!--For shipping_method   -->
								<div class="mb-3 mt-3">
								  	<label for="shipping_method" class="form-label">Shipping Method <span ><i id="ShippingMethodlabel"></i></span></label>
									<select name="shipping_method" id="shipping_method" class="form-control" onChange="update_total_shipping(this,<?php echo $shipping_wallet;?>)" required >
										<option value="">--Select Shipping Method--</option>
										<?php foreach($shipMethoData as $sm):
											if($sm['disable']!=true) { ?>
												<option value="<?php echo $sm['code']; ?>" ><?php echo $sm['name'];?></option>
										<?php } endforeach;?>
									</select>
								</div>	
								<!--End For shipping_method   -->
								<!--  For Shipping Insurance -->
								<div class="mb-3 mt-3">
									<input type="checkbox" id="useshipINs" name="useshipINs" onclick="UnCheckShipINS(event)" checked >
									<label for="useshipINs" class="form-label">Shipping Insurance</label>
								</div>	
								<!-- End For Shipping Insurance -->
								<div class="row justify-content-md-end">
									<div class="col-sm-4 ">
										<table class="table table-hover table-light table-striped">
											<?php 		            
								            $total = $total - ($digitalwalletr_val + $digitalwallet_cash_val); 
							                ?>
							                <?php if ($digitalwalletr_val!=0 ){ ?>
							                	<tr>
									                <td class="text-right" colspan="4"><strong>Discount Wallet:</strong></td>
									                <td class="text-right">-<?php echo number_format($digitalwalletr_val,2) ?></td>
								                </tr>
							                <?php } ?>	
							                <?php if ($digitalwallet_cash_val!=0 ){ ?>
							                	<tr>
									                <td class="text-right" colspan="4"><strong>Cash Wallet:</strong></td>
									                <td class="text-right">-<?php echo number_format($digitalwallet_cash_val,2) ?></td>
								                </tr>
							                <?php } ?>
											<tr>
												<td class="text-right" colspan="4"><b>Sub-Total :</b></td>
												<td class="text-right" id="psubtotal"><?php echo number_format($total,2) ?></td>
											</tr>
											<tr>
												<td class="text-right" colspan="4"><b id="pflatrate_text">Total Shipping:</b></td>
												<td class="text-right" id="pflatrate"><?php echo number_format($totalflatrate,2) ?></td>
											</tr>
											<?php if ($shipping_wallet!=0 ){ ?>
												<tr>
													<td class="text-right" colspan="4"><b id="swdeducTXT"></b></td>
													<td class="text-right" id="swdeductVal"></td>
												</tr>
											<?php } ?>
											<?php if ($total_discount_Wallet!=0 ){ ?>
												<tr>
													<td class="text-right" colspan="4"><b id="disdeducTXT"></b></td>
													<td class="text-right" id="disdeductVal"></td>
												</tr>
											<?php } ?>
											<?php if ($total_cash_Wallet!=0 ){ ?>
												<tr>
													<td class="text-right" colspan="4"><b id="cwdeducTXT"></b></td>
													<td class="text-right" id="cwdeductVal"></td>
												</tr>
											<?php } ?>
											<?php if ($insurance_feetotal!=0 ){ ?>
												<tr>
													<td class="text-right" colspan="4"><b>Shipping Insurance :</b></td>
													<td class="text-right" id="insurance_feetotallabel"> <?php echo number_format($insurance_feetotal,2) ?></td>
												</tr>
											<?php } ?>
											<tr>
												<td class="text-right" colspan="4"><b id="OPtxt"></b></td>
												<td class="text-right" id="OPVAl"></td>
											</tr>
											<tr>
												<td class="text-right" colspan="4"><b>Total :</b></td>
												<td class="text-right" id="ptotal"><?php echo number_format($total+$insurance_feetotal+$totalflatrate,2) ?></td>
											</tr>
											<!-- for cash_wallet totals -->
											<input type="hidden" name="totals[0][value]" id="cash_wallet" value="<?php echo   number_format($digitalwallet_cash_val, 2, '.', '');?>"   class="form-control" />
											<input type="hidden" name="totals[0][code]" id="cash_wallet_code" value="redeem"   class="form-control" />
											<input type="hidden" name="totals[0][title]" id="cash_wallet_title" value="Cash Wallet"   class="form-control" />
											<input type="hidden" name="totals[0][sort_order]" id="cash_wallet_sort_order" value="1"   class="form-control" />
											<!-- for discount_wallet totals -->
											<input type="hidden" name="totals[1][value]" id="discount_wallet" value="<?php echo   number_format($digitalwalletr_val, 2, '.', '');?>"  class="form-control" />
											<input type="hidden" name="totals[1][code]" id="discount_walletode" value="redeem"   class="form-control" />
											<input type="hidden" name="totals[1][title]" id="discount_wallettitle" value="Discount Wallet"   class="form-control" />
											<input type="hidden" name="totals[1][sort_order]" id="discount_wallet_sort_order" value="2"   class="form-control" />
											<!-- for sub_total totals -->
											<input type="hidden" name="totals[2][value]" id="sub_total" value="<?php echo   number_format($total, 2, '.', '');?>" class="form-control" />
											<input type="hidden" name="totals[2][code]" id="sub_total_ode" value="sub_total"   class="form-control" />
											<input type="hidden" name="totals[2][title]" id="sub_total_title" value="Sub-Total"   class="form-control" />
											<input type="hidden" name="totals[2][sort_order]" id="sub_total_sort_order" value="3"   class="form-control" />
											<!-- for system_charges totals -->
											<input type="hidden" name="totals[3][value]" id="system_charges" value=""  class="form-control" />
											<input type="hidden" name="totals[3][code]" id="system_charges_code" value="other_charges"   class="form-control" />
											<input type="hidden" name="totals[3][title]" id="system_charges_title" value="Convenience Fee"   class="form-control" />
											<input type="hidden" name="totals[3][sort_order]" id="system_charges_sort_order" value="5"   class="form-control" />
											<!-- for system_charges totals -->
											<input type="hidden" name="totals[4][value]" id="local_shipping_val" value="<?php echo   number_format($totalflatrate, 2, '.', '');?>"  class="form-control" />	
											<input type="hidden" name="totals[4][code]" id="ls_code" value="shipping_fee"   class="form-control" />
											<input type="hidden" name="totals[4][title]" id="local_shipping_name" value=""  class="form-control" />
											<input type="hidden" name="totals[4][sort_order]" id="ls_sort_order" value="4"   class="form-control" />
											<!-- for total totals -->
											<!-- for shipping_wallet_val totals -->
											<input type="hidden" name="totals[5][value]" id="shipping_wallet_val" value="0"  class="form-control" />
											<input type="hidden" name="totals[5][code]"  value="shipping_wallet"   class="form-control" />
											<input type="hidden" name="totals[5][title]"  value="Shipping Wallet"  class="form-control" />
											<input type="hidden" name="totals[5][sort_order]" id="ls_sort_order" value="6"   class="form-control" />
											<!-- for insurance_fee totals -->
											<input type="hidden" name="totals[6][value]" id="insurance_fee_grantotal" value="<?php echo   number_format($insurance_feetotal, 2, '.', '');?>"  class="form-control" />
											<input type="hidden" name="totals[6][code]"  value="insurance_fee"   class="form-control" />
											<input type="hidden" name="totals[6][title]"  value="Shipping Insurance"  class="form-control" />
											<input type="hidden" name="totals[6][sort_order]"  value="7"   class="form-control" />
											<!-- for total totals -->
											<input type="hidden" name="totals[7][value]" id="total" value=""  class="form-control" />	
											<input type="hidden" name="totals[7][code]" id="t_code" value="total"   class="form-control" />
											<input type="hidden" name="totals[7][title]" id="lt_name" value="Total"  class="form-control" />
											<input type="hidden" name="totals[7][sort_order]" id="t_sort_order" value="8"   class="form-control" />	
										</table>
									</div>
								</div>
								<div class="row" id="bank_transfer" style="display:none">
									<div class="col-sm-5 mb-2">
										<table class="table table-hover table-light table-striped">
											<tbody>
												<tr>
													<th> Bank Transfer Instructions</th>
												<tr>
													<td>Account Number : 7-590-68106-8</td>
												</tr>
												<tr>
													<td>Account Name: PC VILL, INC.</td>
												</tr>
												<tr>
													<td>Bank Name: RCBC </td>
												</tr>
												<tr>
												 	<th>Your order will not ship until we receive payment.</th>
												</tr>
											</tbody>
										</table>	
									</div>
								</div>
								<button class="btn btn-primary" type="submit" name="add_order">Place Order</button>
							</form>							
			           </div>
			        </div> 

		      	</div>
		    </div>
	  	</div>
	<?php }?>
	</div>
</div>


<!-- Large modal -->
  <div class="modal fade" id="LoginModalexist" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content" style="background: #4b6ed6; border-radius: 20px;">
        <div class="modal-header">
          <h5 class="modal-title text-light" id="staticBackdropLabel">This is an exclusive membership store</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group m-1">
                <input type="text" id="txtuserExist" name="txtuserExist" placeholder="Username or Mobile No." class="full-text form-control " />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group m-1">
                <input type="password" id="txtpasswordExist" name="txtpasswordExist" placeholder="Password" class="full-text form-control " />
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-lg-12 ">
              <button class="btn btn-primary p-1" id="btnloginExist" style="font-size: 15px;">Login</button>
              <button class="btn btn-info p-1" ata-bs-dismiss="modal" style="font-size: 15px;">Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
 <!-- Large modal -->
<?php echo"<br><br><br>" ;include "common/footer.php"; ?>
<script type="text/javascript">
	$(document).ready(function() {
		computetotal();	
    	$('#button-wallet_CASH').on('click', function() {
			$.ajax({
			    url: 'ajx_wallet.php?action=CW',
			    type: 'post',
			    data: 'CASHwallet=' + encodeURIComponent($('input[name=\'CASHwallet\']').val()),
			    dataType: 'json',
			    success: function(json) {
			     	if (json['success']) {
		            	bootbox.alert(json['success'], function(){ 
			                window.location.reload();
			            });
		          	}	  
			    }
			});
		});
		$('#button-wallet').on('click', function() {
			var total = $("#total").val();
			var wallet= encodeURIComponent($('input[name=\'digitalwallet\']').val());
			var total2 = parseFloat(total.replace(/,/g, ''))
			var totalval=total2/2;
			var validate=totalval-wallet;
			if(validate<0){
				bootbox.alert("Warning: Digital Wallet must not exceed 50% of the total purchase amount!");
	        	return false;
			}
			$.ajax({
			    url: 'ajx_wallet.php?action=DW',
			    type: 'post',
			    data: 'digitalwallet=' + encodeURIComponent($('input[name=\'digitalwallet\']').val()),
			    dataType: 'json',
			    success: function(json) {
			     	if (json['success']) {
		            	bootbox.alert(json['success'], function(){ 
			                 window.location.reload();
			            });
		          	}	  
			    }
			});
		});
		$('#button-wallet_cancel').on('click', function() {
			$.ajax({
			    url: 'ajx_wallet.php?action=DWcancel',
			    type: 'post',
			    data: 'digitalwallet=' + encodeURIComponent($('input[name=\'digitalwallet\']').val()),
			    dataType: 'json',
			    success: function(json) {
			     	if (json['success']) {
		            	bootbox.alert(json['success'], function(){ 
			                 window.location.reload();
			            });
		          	}	  
			    }
			});
		});
		$('#button-wallet_CASH-cancel').on('click', function() {
			$.ajax({
			    url: 'ajx_wallet.php?action=CWcancel',
			    type: 'post',
			    data: 'CASHwallet=' + encodeURIComponent($('input[name=\'CASHwallet\']').val()),
			    dataType: 'json',
			    success: function(json) {
			     	if (json['success']) {
		            	bootbox.alert(json['success'], function(){ 
			                  window.location.reload();
			            });
		          	}	  
			    }
			});
		});
	});
	function selectshippingAdress(addr_id,cust_id,cart_ids) {
		var addr_idval = addr_id.options[addr_id.selectedIndex].value 
	    var addrText = addr_id.options[addr_id.selectedIndex].text 
	   /* alert(addr_idval);*/
	    $.ajax({
            url: 'ajaxAddress.php?action=selectshippingAdress&t=' + new Date().getTime(),
            type: 'POST',
            data: 'addr_idval='+addr_idval+'&cart_ids=' + JSON.stringify(cart_ids)+'&customer_id='+cust_id,
            dataType: 'json',
            beforeSend: function() {
		        	bootbox.dialog({
			            title: "Updating Shipping Address",
			            message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
				    });
			    },	           
            success: function(json) {	            	
            	console.log(json['success']);	
            	console.log(json['success2']);	
            	var delstats=0;
            	var pickupstats=0;
            	var total_price=0;
            	var total_pricefl=0;
            	var totalshippflat2=0;
            	if(json['success'].length!=0){
            		for (var i = 0; i < json['success'].length; i++) {
	            		var cart_id=json['success'][i].cart_id;
	        	 		if(json['success'][i].delstats!='Yes'){
	        	 			delstats++;
	        	 		}
						if(json['success'][i].pickupstats!='Yes'){
							pickupstats++;
						}
						total_price=parseFloat(json['success'][i].total_price);
						total_pricefl=total_price+parseFloat(json['success'][i].flatrate);
						$("#val_ship_"+cart_id).val(json['success'][i].flatrate);
						$("#inputflatrate_"+cart_id).val(json['success'][i].flatrate);
						$("#StandardShippingRate_"+cart_id).text(json['success'][i].flatrate); 
						$("#SSRTotal_"+cart_id).text(total_pricefl); 
						$("#OtherSRTotal_"+cart_id).text(total_price);
						totalshippflat2+= parseFloat(json['success'][i].flatrate);
	            	}
            	}
            	if(json['success2'].length!=0){
            		for (var x = 0; x < json['success2'].length; x++) {
            			var branch_id=json['success2'][x].branch_id;
            			var seller_id=json['success2'][x].seller_id;
            			if(json['success2'][x].delstats!='Yes'){
	        	 			delstats++;
	        	 		}
						if(json['success2'][x].pickupstats!='Yes'){
							pickupstats++;
						}
						total_price=parseFloat(json['success2'][x].subtotal);
						total_pricefl=total_price+parseFloat(json['success2'][x].flatrate);
						$("#val_shipS_"+seller_id+branch_id).val(json['success2'][x].flatrate);
						$("#inputflatrate_"+seller_id+branch_id).val(json['success2'][x].flatrate);						
						$("#StandardShippingRate_"+seller_id+branch_id).text(json['success2'][x].flatrate); 
						totalshippflat2+= parseFloat(json['success2'][x].flatrate);
            		}
            	}            	
            	if(delstats==0&&pickupstats==0){
            		$("#shipping_method").find("option").each(function () {
					    if ($(this).val() == "flat_rate") {
					        $(this).prop("disabled", false);
					    }
					});	
            	}else{
            		$("#shipping_method").find("option").each(function () {
					    if ($(this).val() == "flat_rate") {
					        $(this).prop("disabled", true);
					    }
					});	
            	}
            	$("#pflatrate").text(parseFloat(totalshippflat2)); 
            	$("#local_shipping_val").val(totalshippflat2);
            	bootbox.hideAll(); 
            }
	    })
	    
	  computetotal()
	}
	function UnCheckuseSW(event,swval) {
 		if(event.target.checked){
 			var totalshippflat = 0; 
 			var values = $("input.shipping_per_storeValFlat").map(function(){return $(this).val();}).get();
			for (var i = 0; i < values.length; i++) {
				totalshippflat+=parseFloat(values[i].replace(/,/g, ''));
			}

			var shipping_method=$("#shipping_method").val(); 
			if(shipping_method=="flat_rate"){
				if(swval>=totalshippflat){
					$("#swdeducTXT").text("Shiping Wallet"); 
		    		$("#swdeductVal").text("-"+totalshippflat);
		    		$("#shipping_wallet_val").val("-"+totalshippflat);
				}else{
					$("#swdeducTXT").text("Shiping Wallet"); 
		    		$("#swdeductVal").text("-"+swval);
		    		$("#shipping_wallet_val").val("-"+totalshippflat);
				}
				
			}
	    }else{
	    	$("#swdeducTXT").text(""); 
		    $("#swdeductVal").text("");
		    $("#shipping_wallet_val").val(0);
	    }
	    computetotal()
 		
  	}
	function update_total_shipping(optShip,swval) {
	    var optShipval = optShip.options[optShip.selectedIndex].value 
	    var optShip_text = optShip.options[optShip.selectedIndex].text 
	    var totalshipp = 0; 
	    var totalshippflat = 0; 
	    $("#ShippingMethodlabel").text(" "); 
	    if(optShipval!="flat_rate"){
	    	if(optShipval=="special_del"){
	    		$("#ShippingMethodlabel").text("(Customer handles shipping / Approximately Same day or next day delivery With higher rate.)");	    		
	    	}else{
	    		$("#ShippingMethodlabel").text(""); 
	    	}
	    	$("#swdeducTXT").text(""); 
		    $("#swdeductVal").text("");
		    $("#shipping_wallet_val").val(0);		    
	    	$(".StandardShippingRate").css("display","none")
	    	$(".OtherShippingRate").css("display","block")
	    	$(".SSRTotal").css("display","none")
	    	$(".OtherSRTotal").css("display","block")
	    	if(optShipval!=""){
	    		$(".val_ship_title_val").val(optShipval);
	    		$("#local_shipping_name").val(optShipval);	
	    		$(".ShippinFeeOther").text(optShip_text+" : 0.00");	
	    	}
	    	var values = $("input.shipping_per_storeValother").map(function(){return $(this).val();}).get();
			for (var i = 0; i < values.length; i++) {
				  totalshipp+=parseFloat(values[i].replace(/,/g, ''));
			}
			$("#pflatrate").text(totalshipp);        
	    	$("#local_shipping_val").val(totalshipp);
		    computetotal();
	    }else{
	        $("#ShippingMethodlabel").text("(Approximately 3-7 Days delivery.)");
	    	$(".StandardShippingRate").css("display","block")
	    	$(".OtherShippingRate").css("display","none")
	    	$(".SSRTotal").css("display","block")
	    	$(".OtherSRTotal").css("display","none")
	    	$(".flatRatelabel").text(optShip_text+" ");	
	    	var values = $("input.shipping_per_storeValFlat").map(function(){return $(this).val();}).get();
			for (var i = 0; i < values.length; i++) {
				totalshippflat+=parseFloat(values[i].replace(/,/g, ''));
			}
			$("#pflatrate").text(totalshippflat);        
	    	$("#local_shipping_val").val(totalshippflat);
	    	var useSW=0;
	    	$.each($("input[name='useSW']:checked"), function(){
		      useSW++;
		    });
	    	if(useSW!=0){
	    		if(swval>=totalshippflat){
					$("#swdeducTXT").text("Shiping Wallet"); 
		    		$("#swdeductVal").text("-"+totalshippflat);
		    		$("#shipping_wallet_val").val("-"+totalshippflat);
				}else{
					$("#swdeducTXT").text("Shiping Wallet"); 
		    		$("#swdeductVal").text("-"+swval);
		    		$("#shipping_wallet_val").val("-"+swval);
				}
	    	}	    	 
		    computetotal();

	    }
	    
	}
	$('#payment_method').on('change', function(){
			computetotal();
			var payment_method = $(this).val();	
			if(payment_method=="cod"){
				$("#shipping_method").find("option").each(function () {
				    if ($(this).val() == "special_del") {
				        $(this).prop("disabled", true);
				    }
				});	
				$('#bank_transfer').hide();			
			}else if(payment_method=="bank_transfer"){
				$("#shipping_method").find("option").each(function () {
				    if ($(this).val() == "special_del") {
				        $(this).prop("disabled", false);
				    }
				});
				$('#bank_transfer').show();
			}else{
				$("#shipping_method").find("option").each(function () {
				    if ($(this).val() == "special_del") {
				        $(this).prop("disabled", false);
				    }
				});
				$('#bank_transfer').hide();

			}			
		});

	 function computetotal(){
    		var subtotal = $("#psubtotal").text();
	    	var flatrate = $("#pflatrate").text();
	    	var shipping_wallet_val = $("#shipping_wallet_val").val();
	    	var total = parseFloat(subtotal.replace(/,/g, '')) + parseFloat(flatrate.replace(/,/g, '')) ;
    		var ntotal = total + parseFloat(shipping_wallet_val);
	    	var op= $( "#payment_method option:selected" ).val();	
	    	if(op=="credit_card"){
				$('#bank_transfer').hide();
				var subtotal2 = $("#psubtotal").text();
				var total2 = (parseFloat(subtotal2.replace(/,/g, ''))/.972)-parseFloat(subtotal2.replace(/,/g, ''));
				var totatxt2=numberWithCommas(parseFloat(total2).toFixed(2));
				$("#OPtxt").text("Convenience fee: ");
				$("#OPVAl").text(totatxt2);
				$("#system_charges").val(total2);
				ntotal=parseFloat(ntotal)+ parseFloat(total2) ;
			}else if(op=="maxx_payment"){
				$('#bank_transfer').hide();	
			
				var subtotal22 = $("#psubtotal").text();
				var total22 = parseFloat(subtotal22.replace(/,/g, ''))*0.015;
				var totatxt=numberWithCommas(parseFloat(total22).toFixed(2));

				$("#OPtxt").text("Convenience fee: ");
				$("#OPVAl").text(totatxt);
				$("#system_charges").val(total22);
				ntotal=parseFloat(ntotal)+ parseFloat(total22) ;
			}else if(op=="bank_transfer"){
				$('#bank_transfer').show();
				$("#system_charges").val(0);
				$("#OPtxt").text("");
				$("#OPVAl").text("");
			}else{
				$('#bank_transfer').hide();
				$("#OPtxt").text("");
				$("#OPVAl").text("");
				$("#system_charges").val(0);
			}		
			var useIF=0;
	    	$.each($("input[name='useshipINs']:checked"), function(){
		      useIF++;
		    });
			var totalinsurance = 0; 
			var totalWithInsurance = 0;
			var shipping_method= $( "#shipping_method option:selected" ).val();
			if(useIF!=0){
				if(shipping_method=="flat_rate"){
					var values = $("input.insuranceFeePerStore").map(function(){return $(this).val();}).get();
					for (var i = 0; i < values.length; i++) {
						totalinsurance+=parseFloat(values[i].replace(/,/g, ''));									
					}
					$("#insurance_feetotallabel").text(numberWithCommas(parseFloat(totalinsurance).toFixed(2))); 
				}else{
					var values = $("input.insuranceFeePerStore_sp").map(function(){return $(this).val();}).get();
					for (var i = 0; i < values.length; i++) {
						totalinsurance+=parseFloat(values[i].replace(/,/g, ''));									
					}
					$("#insurance_feetotallabel").text("0.00"); 
				}
			}else{
				var values = $("input.insuranceFeePerStore_sp").map(function(){return $(this).val();}).get();
				for (var i = 0; i < values.length; i++) {
					totalinsurance+=parseFloat(values[i].replace(/,/g, ''));									
				}
				$("#insurance_feetotallabel").text("0.00"); 
			}
			
			
			totalWithInsurance=totalinsurance+ntotal;
			var ntotalfinal = numberWithCommas(parseFloat(totalWithInsurance).toFixed(2));
			$("#insurance_fee_grantotal").val(totalinsurance);
			$("#ptotal").text(ntotalfinal);
			$("#total").val(totalWithInsurance);
			
     }
    function UnCheckShipINS(event) { 		
	    computetotal() 		
  	}
  
	function numberWithCommas(x) {
    	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}

	$(document).delegate('#btnloginExist', 'click', function() {
      var username = document.getElementById('txtuserExist').value
      var email = document.getElementById('txtuserExist').value
      var password = document.getElementById('txtpasswordExist').value
      if (username == "") {
        bootbox.alert("Please Enter Username or Mobile Number.");
        return false;
      } else if (password == "") {
        bootbox.alert("Please Enter Username or Mobile Number.");
        return false;
      } else {
        $.ajax({
          url: 'ajax_landbankreg.php?action=manualLogin&t=' + new Date().getTime(),
          type: 'POST',
          data: 'username=' + username + '&password=' + password,
          dataType: 'json',
          success: function(json) {
            if (json['status'] == 300) {
              bootbox.alert(json['success']);
            } else {
            	var customer_id=json['customer_id'];
    			var guestId='<?php echo $id;?>';
            	CartTransfer(guestId,customer_id);
	            bootbox.alert(json['success'], function() {
	                location.replace("lanbankLogin.php?regExistCustid=" + json['customer_id'] + "&t=" + new Date().getTime());
	            });
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          }
        });
      }
    });
    const CartTransfer = (guestId,customer_id) => {
	    return new Promise(resolve => {
	      $.ajax({
	        url: `ajax_checkout.php?action=CartTransfer&guestId=${guestId}&customer_id=${customer_id}&t=${ new Date().getTime()}`,
	        dataType: 'json',
	        success: response => {      
	        }
	      }).done(() => {
	        resolve();
	      })
	    })
  	}
  
</script>