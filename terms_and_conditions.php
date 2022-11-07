<?php 
include "common/header_new.php";
	 

?>

<style type="text/css">
</style>
			<!-- Large modal -->
<div class="modal fade bd-example-modal" id="TermsModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
    	 	<div class="modal-header">
    	 		<p style="font-size: 20px" class="modal-title"><strong>
	    	 	<?php if(isset($_GET['data'])){
	    	 		if($_GET['data']=="PESOTermsofService"){
	      				echo "Terms of Service";
	      			}else if($_GET['data']=="PESOPrivacyPolicy"){
	      				echo "Privacy Policy";
	      			}
	    	 	}else{
	        		echo "Terms and Conditions";
	        	}?>
        			</strong></p>
      		</div>
      		<div class="modal-body">
      			<?php if(isset($_GET['data'])){
      				if($_GET['data']=="PESOTermsofService"){
      					include "PESOTermsofService.html";
      				}else if($_GET['data']=="PESOPrivacyPolicy"){
	      				include "PESOPrivacyPolicy.html";
	      			}
      			}else{?>
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
	        	<?php }?>
	      	</div>
    	</div>
 	</div>							
</div>
<?php
include "common/footer.php";
?>
<script type="text/javascript">
	$(document).ready(function() {
    	
    	$('#TermsModal').modal('show');
    });
</script>