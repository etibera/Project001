
<?php 
  require_once 'model/footer.php'; 
    $mod_footer=new footer();
    $get_informations=$mod_footer->get_informations();
   //echo html_entity_decode($get_informations[3]['description'], ENT_QUOTES, 'UTF-8');
 ?>
 
<footer style="padding-bottom:70px;background: #666;">
	<div class="container">
	    <hr>
	    <div class="row">
	      
	      <div class="col-sm-3">
	        <h5 style="color: #FFFFFF;">Information</h5>
	        <ul class="list-unstyled">
	        	 <li><a id="BtnAboutUs">About Us</a></li>
	        	 <li><a  data-bs-toggle="modal" data-bs-target="#modal_info_PESOTermsnc">Terms &amp; Conditions</a></li>
	        	 <li><a  id="modal_info_ShippingPolicy">Shipping and Delivery Policy</a></li>
	        	 <li><a  data-bs-toggle="modal" data-bs-target="#modal_info_PESOTermsofService">Terms of Service</a></li>
	        	 <li><a  data-bs-toggle="modal" data-bs-target="#modal_info_Privacy_Policy">Privacy Policy</a></li>
	        	 <li><a  data-bs-toggle="modal" data-bs-target="#ModalWarranty">Warranty Information</a></li>
	        	 <li><a  id="modal_info_Return">Return and Refund</a></li>
	        </ul>
	      </div>
	      <div class="col-sm-3">
	        <h5 style="color: #FFFFFF;">Customer Service</h5>
	        <ul class="list-unstyled" id="get_informations">	        	
	        	<li>
	        		<div class="accordion" id="accordionExample">
					  <div class="accordion-item">
					    <h2 class="accordion-header" id="headingOne">
					      <a class="accordion-button text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#accordiontbl_Contact_us" aria-expanded="false" aria-controls="collapseOne">
					       Contact Us
					      </a>
					    </h2>
					    <div id="accordiontbl_Contact_us" class="accordion-collapse collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
					      <div class="accordion-body">
					        <div class="panel panel-default">
		        				<div class="panel-body">
		        				 	<b>Our Location : </b><br>
		        				 	<small>4F 201 Delmonte Ave. Brgy. Masambong Quezon City</small><br>
		        				 	<b>Email Address : </b><br>
		        				 	<small>support@pinoyelectronicstore.com</small>
		        				</div>
		        			</div>
					      </div>
					    </div>
					  </div>					   
					</div>
	        	</li>
	           
	           <?php if($is_log){?>
	              
	              <li><a href="#">Returns</a></li>
	              <li><a href="#">Site Map</a></li>
	           <?php }else{ ?> 
	             
	              <li><a data-bs-toggle="modal" data-bs-target="#LoginModal">Returns</a></li>
	              <li><a data-bs-toggle="modal" data-bs-target="#LoginModal">Site Map</a></li> 
	           <?php } ?>
	          
	        </ul>
	      </div>	      
	      <div class="col-sm-6">
	      	 <img src="assets/footerpaymentmethod.jpg"  class="rounded-3 bg-light   img-fluid" />
	      </div>
	    </div>
	    
	  	<hr>
	  	<?php
	  	$text_powered     = 'Pinoy Electronic Store Online &copy; '.date('Y').' <br /> Powered By <a href="http://digitaldoorssoftware.com">PCVILL Inc.</a>  ';
		$text_powered2     = 'Pinoy Electronic Store Online &copy; '.date('Y').'<br /> Powered By PCVILL Inc ';
		 if($is_log){?> 

       <p style="color: #FFFFFF;"><?php echo $text_powered; ?></p>
      <?php }else{ ?> 
       <p style="color: #FFFFFF;"><?php echo $text_powered2; ?></p>  
      <?php } ?>
		
	</div>
</footer>
</body></html>

<div class="modal fade bd-example-modal" id="modal_info_PESOTermsnc" tabindex="-1" role="dialog" aaria-labelledby="myLargeModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
    	 	<div class="modal-header">
    	 		<a type="button"  data-bs-dismiss="modal"   style="float: right;
                  font-size: 25px;
                  font-weight: 700;
                  line-height: 1;
                  color: #000;
                  text-shadow: 0 1px 0 #fff;
                "><i class="fa fa-times-circle " style="color: black;font-size: 25px;" ></i></a>
    	 		<p style="font-size: 20px" class="modal-title"><strong>Terms and Conditions</strong></p>
      		</div>
      		<div class="modal-body">
      			<h4><strong>Shipping and Delivery Policy</strong></h4>
		        	<p style="text-align: justify;">Once your order is checked and completed, Pinoy Electronic Store Online will send a shipping dispatch notice to our internal delivery team. Depending on the location of the client the team will determine if the method of shipping will be thru our in house team or thru our delivery couriers and partners.</p>
		        	<br>
		         	<h4><strong>Time of Delivery :</strong></h4>
		        	<p style="text-align: justify;">The time of delivery starting from a message notification on your website account is estimated to be 2-3 days depending on the customer’s location and address. The delivery lead  time does not include fortuitous events such as weather conditions, natural disasters, public disturbances, civil disobedience and etc. Pinoy Electronic Store Online relies on our shipping partners for fulfilment and will act representing the customer until the item has been fully delivered to the end user. Lead time status is available for view by the end user under “Accounts/delivery Tracking” page.</p>
		        	<br>
		         	<h4><strong>Delivery Receiving Condition :</strong></h4>
		        	<p style="text-align: justify;">Items delivered must meet the “agreement upon delivery condition” as part of our standard process. The said upon delivery condition means that the item must be properly packed, properly sealed and checked for unofficial opening or tampering before the shipper picks the item and as well the end user confirms the received item.</p>
		        	<br>
		         	<h4><strong>Shipper’s Responsibility :</strong></h4>
		        	<p style="text-align: justify;">The agreed delivery condition above will be under the responsibility of the shipper or partner courier company once the item has been picked up from our warehouse. The Shipper as part of their service responsibility guarantees that the item will be delivered to the end user in perfect condition as to how they picked it up from Pinoy Electronic Store. Damaged, complete loss, incomplete parts/accessories or incorrect item delivery will be fully covered by Pinoy Electronic Store Online granting that the item has been cleared and checked for tampering by the customer.</p>
		        	<br>
		         	<h4><strong>Customer’s Responsibility :</strong></h4>
		        	<p style="text-align: justify;">It is the customers or end user’s responsibility to check and asses for package damage, opening or unauthorised tampering before they sign a receiving confirmation from the shipper or partner courier company. Once the customer signs the receiving paper, Pinoy Electronic Store Online will consider the delivery as “item received in complete and good working condition”. Under no circumstances will Pinoy Electronic Store Online honor any customer complain regarding bad delivery or related issues and will therefore interpret the case as part of our regular warranty or return and exchange case. <em>(*** Manufacturing warranty conditions apply)</em></p>
		        	<br>
		         	<h4><strong>Disputes and Complain :</strong></h4>
		        	<p style="text-align: justify;">For any legal dispute or complains regarding the topic above you may inform us thru our dedicated mail service account at legal@pinoyelectronicstore.com.</p>
      		</div>
    	</div>
 	</div>							
</div>
<div class="modal fade bd-example-modal" id="modal_info_PESOTermsofService" tabindex="-1" role="dialog" aaria-labelledby="myLargeModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
    	 	<div class="modal-header">
    	 		<a type="button"  data-bs-dismiss="modal"   style="float: right;
                  font-size: 25px;
                  font-weight: 700;
                  line-height: 1;
                  color: #000;
                  text-shadow: 0 1px 0 #fff;
                "><i class="fa fa-times-circle " style="color: black;font-size: 25px;" ></i></a>
    	 		<p style="font-size: 20px" class="modal-title"><strong>Terms of Service</strong></p>
      		</div>
      		<div class="modal-body"><?php include "PESOTermsofService.html";?> </div>
    	</div>
 	</div>							
</div>
<div class="modal fade bd-example-modal" id="modal_info_Privacy_Policy" tabindex="-1" role="dialog" aaria-labelledby="myLargeModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
    	 	<div class="modal-header">
    	 		<a type="button"  data-bs-dismiss="modal"   style="float: right;
                  font-size: 25px;
                  font-weight: 700;
                  line-height: 1;
                  color: #000;
                  text-shadow: 0 1px 0 #fff;
                "><i class="fa fa-times-circle " style="color: black;font-size: 25px;" ></i></a>
    	 		<p style="font-size: 20px" class="modal-title"><strong>Privacy Policy</strong></p>
      		</div>
      		<div class="modal-body"><?php include "PESOPrivacyPolicy.html";?> </div>
    	</div>
 	</div>							
</div>
<div class="modal fade bd-example-modal" id="ModalWarranty" tabindex="-1" role="dialog" aaria-labelledby="myLargeModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
    	 	<div class="modal-header">
    	 		<a type="button"  data-bs-dismiss="modal"   style="float: right;
                  font-size: 25px;
                  font-weight: 700;
                  line-height: 1;
                  color: #000;
                  text-shadow: 0 1px 0 #fff;
                "><i class="fa fa-times-circle " style="color: black;font-size: 25px;" ></i></a>
    	 		<p style="font-size: 20px" class="modal-title"><strong>Warranty</strong></p>
      		</div>
      		<div class="modal-body">
      		   <h4><strong>Warranty Information:</strong></h4>
		        	<p style="text-align: justify;"> 7 day replacement from the date of purchase for factory defect & 1 year warranty for normal wear & tear. It does not cover damages due to abuse, third party accessories or improper modifications (to hardware and software)</p>
		        	<br>
      		</div>
    	</div>
 	</div>							
</div>

<div class="modal fade bd-example-modal-lg" id="modal_info_data" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
  	<div class="modal-dialog ">
    	<div class="modal-content">
    	 	<div class="modal-header">
    	 		<a type="button"  data-bs-dismiss="modal"   style="float: right;
                  font-size: 25px;
                  font-weight: 700;
                  line-height: 1;
                  color: #000;
                  text-shadow: 0 1px 0 #fff;
                "><i class="fa fa-times-circle " style="color: black;font-size: 25px;" ></i></a>
        		<br>
        		<div id="About_Us_head">
        			<p style="font-size: 20px" class="modal-title"  ><strong>About Us</strong></p>
        		</div>
        		<div id="TandC_head">
        	     	<p style="font-size: 20px" class="modal-title" ><strong>Terms &amp; Conditions</strong></p> 
        		</div>  
      		</div>
      		<div class="modal-body">
	        	<div class="panel panel-default" id="info_div_AboutUs">
				  	<div class="panel-body" >				 	
				 		<?php echo html_entity_decode($get_informations[0]['description'], ENT_QUOTES, 'UTF-8');?>
				  	</div>
			    </div>
			    <div class="panel panel-default" id="info_div_TandC">
				  	<div class="panel-body" >				 	
				 		<?php echo html_entity_decode($get_informations[1]['description'], ENT_QUOTES, 'UTF-8');?>
				  	</div>
			    </div>
	      	</div>
    	</div>
 	</div>							
</div>
