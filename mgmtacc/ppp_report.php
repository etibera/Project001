<?php include 'template/header.php'; ?> 



<?php 
	include "model/Ppp_report.php"; 

$perm = $_SESSION['permission'];
if (!strpos($perm, "'19';") !== false){
    header("Location: landing.php");
   
}

	$model=new Ppp_report();	
	$grand_total=0;
	$results = $model->get_ppp_branch_name();
	foreach ($results as $result) {
		$branch_id= $result['customer_id'];
		$ppp_branch_name_sales_man = array();
		$results2 = $model->get_ppp_branch_name_sales_man($branch_id);
		$numberof=0;
		$total_sales_wallet=0;
		if($results2){
			foreach ($results2 as $result2) {
				$numberof+=1;
				$count_share_links=$model->get_count_share_links($result2['customer_id']);
			 	$get_count_s_sales=$model->get_count_s_sales($result2['customer_id'],1);
				$get_count_p_sales=$model->get_count_s_sales($result2['customer_id'],0);
				$get_total_sales_wallet=$model->get_total_sales_wallet($result2['customer_id']);
				if(!$get_total_sales_wallet){
					$get_total_sales_wallet=0;
				}
				$total_sales_wallet+=$get_total_sales_wallet;
				$grand_total+=$get_total_sales_wallet;

				$ppp_branch_name_sales_man[] = array(
					'salesman_name'  => $result2['name'],
					'cust_id'       =>  $result2['customer_id'],
					'count_share_links'       =>$count_share_links ,
					'get_count_s_sales'       => $get_count_s_sales,
					'get_count_p_sales'       => $get_count_p_sales,
					'total_sales_wallet'       => number_format($get_total_sales_wallet,2)
				);
			}
		}else{
			$ppp_branch_name_sales_man[] = array(
					'salesman_name'  =>"--",
					'cust_id'       =>  "--",
					'count_share_links'       =>  "--",
					'get_count_s_sales'       =>  "--",
					'get_count_p_sales'       =>  "--",
					'total_sales_wallet'       =>  "--"
				);
		}
		$ppp_branch_name[] = array(
				'name'    => $result['name'],
				'totalNUmber'    => $numberof,
				'total_sales_wallet'    =>number_format($total_sales_wallet,2),
				'customer_id'    =>  $result['customer_id'],
				'ppp_branch_name_sales_man'    => $ppp_branch_name_sales_man
		);
	}
	$resultsQH = $model->get_ppp_branch_name_sales_manHQ();
	$numberofhq=0;
	$total_sales_wallethq=0;
	if($resultsQH){
		foreach ($resultsQH as $resultQH) {
				$numberofhq+=1;
				$count_share_linkshq=$model->get_count_share_links($resultQH['customer_id']);
				$get_count_s_saleshq=$model->get_count_s_sales($resultQH['customer_id'],1);
				$get_count_p_saleshq=$model->get_count_s_sales($resultQH['customer_id'],0);
				$get_total_sales_wallethq=$model->get_total_sales_wallet($resultQH['customer_id']);
				if(!$get_total_sales_wallethq){
					$get_total_sales_wallethq=0;
				}
				$total_sales_wallethq+=$get_total_sales_wallethq;
				$grand_total+=$get_total_sales_wallethq;
				$ppp_branch_name_sales_manHQ[] = array(
					'salesman_name'  => $resultQH['name'],
						'cust_id'       =>  $resultQH['customer_id'],
						'count_share_links'       =>  $count_share_linkshq,
						'get_count_s_sales'       =>  $get_count_s_saleshq,
						'get_count_p_sales'       =>  $get_count_p_saleshq,
						'total_sales_wallet'       =>  number_format($get_total_sales_wallethq,2),
				);
		}
	}else{
		$ppp_branch_name_sales_manHQ[] = array(
				'salesman_name'  =>"--",
				'cust_id'       =>  "--",
				'count_share_links'       =>  "--",
				'get_count_s_sales'       =>  "--",
				'get_count_p_sales'       =>  "--",
				'total_sales_wallet'       =>  "--"
			);
	}
	$ppp_branch_name[] = array(
		'name'    => 'AHI HQ',
		'totalNUmber'    => $numberofhq,
		'total_sales_wallet'    =>number_format($total_sales_wallethq,2),
		'customer_id'    =>  '1568',
		'ppp_branch_name_sales_man'    => $ppp_branch_name_sales_manHQ
	);

?>
<div class="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<center><h2>PESO Partner Program Report</h2></center><br>
			</div>
		</div>	
		<div class="row">
			<div class="col-sm-12">
				<div class="table-responsive">
					 <table class="table table-bordered table-hover home-cat-table">
					 	<?php foreach ($ppp_branch_name as $ppp_branch_name_l) {?>
					 		<thead>
					 			<tr>
		                    		<td class="text-center" colspan="6"><?php echo $ppp_branch_name_l['name']; ?> 
		                    	<tr>
		                    	<tr>
		                    		<td class="text-left" colspan="6"> Total Number Of Members: <?php echo $ppp_branch_name_l['totalNUmber'];?></td>
		                    	</tr>
			                    <tr>
			                      <td class="text-center">Salesman</td>
			                      <td class="text-center">Total Recommend Products</td>
			                      <td class="text-center">Number of Success Sales</td>
			                      <td class="text-center">Number of Pending Sales</td>
			                      <td class="text-center">Sales Cash Wallet</td>
			                      <td class="text-center">Details</td>
			                    </tr>
		                  	</thead>
		                  	<?php foreach ($ppp_branch_name_l['ppp_branch_name_sales_man'] as $sales_man_name) { ?>
		                  		 <tr>
			                        <td class="text-center" ><?php echo $sales_man_name['salesman_name'];?></td>
			                        <td class="text-center" ><?php echo $sales_man_name['count_share_links']; ?></td>
			                        <td class="text-center" ><?php echo $sales_man_name['get_count_s_sales']; ?></td>
			                        <td class="text-center" ><?php echo $sales_man_name['get_count_p_sales']; ?></td>
			                        <td class="text-center" ><?php echo $sales_man_name['total_sales_wallet']; ?></td>
			                        <td class="text-center" > 
			                          <button id="btn_recommed" data-toggle="tooltip" title="Details For Recommend Products" data-id="<?php echo $sales_man_name['cust_id']; ?>" data-name="<?php echo $sales_man_name['salesman_name']; ?>" type="button" class="btn btn-primary btn_recommed">
			                           <i class='fa fa-share-alt' style="font-size:15px; "></i>
			                          </button>
			                          <button id="btn_cash_wallet" data-toggle="tooltip" title="Details For Sales Cash Wallet" data-id="<?php echo $sales_man_name['cust_id']; ?>" data-name="<?php echo $sales_man_name['salesman_name']; ?>"  type="button" class="btn btn-primary btn_cash_wallet">
			                           <i class='fa fa-briefcase' style="font-size:15px; "></i>
			                          </button>
			                        </td>

			                      </tr>
		                  	<?php }?>
		                   	<thead>
			                    <td class="text-right" colspan="4">Sub Total:</td>
			                    <td class="text-center"> <?php echo $ppp_branch_name_l['total_sales_wallet']; ?></td>
			                    <td></td>
		                  	</thead>
					 	<?php }?>
					 	 <thead>
		                    <td class="text-right" colspan="4">Grand Total:</td>
		                    <td class="text-center"> <b><?php echo number_format($grand_total,2); ?></b></td>
		                    <td></td>
		                </thead>
					 </table>
				</div>
			</div>
		</div>	
	</div>
</div>
<?php include 'template/footer.php';?>

<div  class="modal" id="detailsModal_cash_wallet" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-error-dialog">
    <div class="modal-content" >
      <div class="col-sm-12">
        <div class="well">

          <a type="button" style="float: right;
              font-size: 25px;
              font-weight: 700;
              line-height: 1;
              color: #000;
              text-shadow: 0 1px 0 #fff;"
             data-dismiss="modal" ><i class="fa fa-times-circle " style="color: black;font-size: 25px;" ></i></a>
          <h2 id="hedername1"></h2>
           <div class="table-responsive">
            <table id="seeder_tbl_list1" class="table table-striped table-bordered">
              <thead>
                <tr>
                    <th>No.</th>
                    <th>Order Id</th>
                    <th>Product Name</th>
                    <th>Date of Conversion</th>
                    <th>Amount</th>
                </tr>
              </thead>
              <tbody></tbody>
              <tfoot> 
                <tr>
                    <th colspan="4" style="text-align: right;"><B id="totalwallet_det1"></B></th>
                    <th><B id="totalwallet1"></B></th>
                   
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
       
    </div><!--modal-content-->
  </div><!-- modal-dialog modal-error-dialog-->
</div><!-- addItemModal2-->

<div  class="modal" id="modal_share" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-error-dialog">
    <div class="modal-content" >
      <div class="col-sm-12">
        <div class="well">

          <a type="button" style="float: right;
              font-size: 25px;
              font-weight: 700;
              line-height: 1;
              color: #000;
              text-shadow: 0 1px 0 #fff;"
             data-dismiss="modal" ><i class="fa fa-times-circle " style="color: black;font-size: 25px;" ></i></a>
          <h2 id="hedername"></h2>
          <div class="table-responsive">
            <table id="seeder_tbl_list" class="table table-striped table-bordered">
              <thead>
                <tr>
                    <th>No.</th>
                    <th>Product Name</th>
                    <th>Date</th>
                    <th>Mode of Share</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>

        </div>
      </div>
       
    </div><!--modal-content-->
  </div><!-- modal-dialog modal-error-dialog-->
</div><!-- addItemModal2-->

<script>
	$(document).ready(function() {
      	$('.btn_cash_wallet').on('click', function () {
		   	$('#detailsModal_cash_wallet').modal('show'); 
	       	var id=$(this).data('id'); 
	       	var name=$(this).data('name');
	       	$("#hedername1").html(name+" Sales Cash Wallet Details");
           	$("#seeder_tbl_list1 tbody").empty();
		    $.ajax({
			    url: 'ajx_ppp_rep.php?action=cash_wallet',
			    type: 'post',
			    data: 'custgetId=' + id,
			    dataType: 'json',
			    success: function(json) {
			     	for (var i = 0; i < json['customers'].length; i++) {
		                $("#seeder_tbl_list1 tbody").append("<tr><td>"+json['customers'][i].count+"</td><td>"+json['customers'][i].order_id+"</td><td>"+json['customers'][i].product_name+"</td><td>"+json['customers'][i].date_added+"</td><td>"+json['customers'][i].amount+"</td></tr>");		                
		            }
	              	if(json['totalwallet']){
	                 	$("#totalwallet1").html(json['totalwallet']);
	                 	$("#totalwallet_det1").html("Total Wallet");
	              	}else{
	                	$("#totalwallet_det1").html(" ");
	                	$("#totalwallet1").html(" ");
	              	}
			    }
			});
      	});
      	$('.btn_recommed').on('click', function () {
		    $('#modal_share').modal('show'); 
	        var id=$(this).data('id'); 
	        var name=$(this).data('name'); 
	        $("#hedername").html(name+"  Recommend Products Details");
	        $("#seeder_tbl_list tbody").empty();
	        $.ajax({
			    url: 'ajx_ppp_rep.php?action=btn_recommed',
			    type: 'post',
			    data: 'custgetId=' + id,
			    dataType: 'json',
			    success: function(json) {
			     	for (var i = 0; i < json['customers'].length; i++) {
		                $("#seeder_tbl_list tbody").append("<tr><td>"+json['customers'][i].count+"</td><td>"+json['customers'][i].name+"</td><td>"+json['customers'][i].date_added+"</td><td>"+json['customers'][i].type+"</td></tr>");
		               
		            }
			    }
			});
      	});
    });
</script>