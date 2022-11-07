<?php
include 'template/header.php';
require_once "model/Product.php";
require_once "model/Sales_report.php";
$perm = $_SESSION['permission'];
if (!strpos($perm, "'18';") !== false){
    header("Location: landing.php");
   
}
$Sales_report = new Sales_report();
$product = new Product(); 

?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a class="btn btn-primary" href="excelreport.php" id="add-brand" data-toggle="tooltip" title="Print" class="btn btn-primary">Print Report</a>
      </div>
      <h2>Sales Report</h2>
     
    </div>
  </div>  
  <div class="container-fluid">
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i>Sales Report List</h3>
      </div>
      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <tbody>
               <thead>
                <tr>
                      <td class="text-center" colspan="4" ></td>
                      <td class="text-center">TOTAL Transations</td>
                      <td class="text-center">Successful Transactions</td>
                      <td class="text-center">Bank Transaction</td>
                      <td class="text-center">Total Charges</td>
                      <td class="text-center"  colspan="4">  </td>
                </tr>
                 <?php 
                  $total = 0;
                  $s_total = 0;
                  $bank = 0;
                  $total_charges = 0;
                 foreach ($Sales_report->get_sales_report_new() as $sales_rep1) { 
                    $total += $sales_rep1['total'] ;
                    if($sales_rep1['order_status_id']=="20" || $sales_rep1['order_status_id']=="49"){  
                      $s_total += $sales_rep1['total'] ; 
                    }else{  
                      $s_total += 0;
                    }                 
                    if($sales_rep1['opSystemCharge']!="0"){  
                      $bank += $sales_rep1['total'] ; 
                    }else{ 
                      $bank += 0 ;
                    } 
                     $total_charges += $sales_rep1['opSystemCharge'] ;
                  }
                  ?>
                 <tr>
                      <td class="text-center" colspan="4"></td>
                      <td class="text-center"><?php echo number_format($total,2);?></td>
                      <td class="text-center"><?php echo number_format($s_total,2);?></td>
                      <td class="text-center"><?php echo number_format($bank,2);?></td>
                      <td class="text-center"><?php echo number_format($total_charges,2);?></td>
                      <td class="text-center"  colspan="4">  </td>
                </tr>
               
             </thead>
            
             <tr> <td class="text-center" colspan="12"></td></tr>
             
                
                   <thead>
                    <tr>
                      <td class="text-center">Order ID</td>
                      <td class="text-center">Customer Name</td>
                      <td class="text-center">Status</td>
                      <td class="text-center">Mode of Payment</td>
                      <td class="text-center">Total</td>
                      <td class="text-center">Successful Sales</td>
                      <td class="text-center"> Bank Transaction </td>
                      <td class="text-center"> OP System charge  </td>
                      <td class="text-center">Date Added  </td>
                      <td class="text-center">Date of Sales </td>
                      <td class="text-center">Seller Receipt No </td>
                      <td class="text-center">Serial No.</td>
                    </tr>
                  </thead>
                  <?php foreach ($Sales_report->get_sales_report_new() as $sales_rep) { ?>
                     <tr>
                        <td class="text-center" ><?php echo $sales_rep['order_id'];?></td>
                        <td class="text-center" ><?php echo $sales_rep['fullname']; ?></td>
                        <td class="text-center" ><?php echo $sales_rep['statusName']; ?></td>
                        <td class="text-center" ><?php echo $sales_rep['payment_method']; ?></td>
                        <td class="text-center" ><?php echo number_format($sales_rep['total'],2); ?></td>
                         <td class="text-center" >
                          <?php
                              if($sales_rep['order_status_id']=="20" || $sales_rep['order_status_id']=="49"){  echo number_format($sales_rep['total'],2); } else{ echo "";}
                          ?>
                         </td>
                         <td class="text-center" >
                          <?php
                              if($sales_rep['opSystemCharge']!="0"){  echo number_format($sales_rep['total'],2); } else{ echo "";}
                          ?>
                         </td>
                         <td class="text-center" ><?php echo $sales_rep['opSystemCharge']; ?></td>
                         <td class="text-center" ><?php echo $sales_rep['date_added']; ?></td>
                         <td class="text-center" >
                          <?php
                              if($sales_rep['order_status_id']=="20" || $sales_rep['order_status_id']=="49"){  echo $sales_rep['date_modified']; } else{ echo "";}
                          ?>                        </td>
                         <td class="text-center" ><?php echo $sales_rep['wr']; ?></td>
                         <td class="text-center" >  
                            <?php foreach ($Sales_report->get_serial($sales_rep['order_id']) as $serial) { 
                             echo $serial['serial'].' ,</br>'; 
                             }?>
                          </td>
                      </tr> 
                  <?php }?>
                  
            
              <tr>
                <td class="text-center" colspan="8">No Data Found . . .</td>
              </tr>
        
            </tbody>          
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include 'template/footer.php';?>                  
  
 <script>
    $(document).ready(function() {

      if($("#status") != '')
      {
        $("#d_status").val($("#status").val());
      }

      $("#d_status").on('change', function(){
            $('#status').val($(this).val());
        });
    });
 </script>

