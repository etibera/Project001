<?php
require_once("includes/init.php"); 
if(!$session->is_signed_in()){redirect("index");}
include "model/cash_out_request.php";

if(isset($_GET['cashid'])&&isset($_GET['cashtype']))
{
  $in = new cashout();
  $order_details = $in->getcashoutrequestdetails($_GET['cashid']);
}     
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">
<title>PESO</title>
    
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<script src="../js/jquery-1.12.4-jquery.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>  

<body class="font-print" onload="window.print()">
<div class="container">
   <div style="page-break-after: always;">
    
    <div class="mt-5">
      <span style="font-size: 35px">Pinoy Electronic Store Online</span><span style="font-size: 25px; color:gray;">  Request # <?php echo $_GET['cashid'] ?></span>
    </div>
    <br><br>
    <table class="table table-bordered table-hover"> 
                </tbody>
                       <tbody>
                  <tr>
                    <th colspan="2">
                      Full Name
                    </th>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <input style="width: 100%" id="txtamount" type="text" value="<?php echo $order_details['firstname'].' '.$order_details['lastname'] ?>" name="" readonly>
                    </td>
                  
                  </tr>
                </tbody>
                <tbody>
                  <tr>
                    <th colspan="2">
                      Cash Out Type
                    </th>
                  </tr>
                  <tr>
                    <td  colspan="2">
                      <select name="cashouttype" id="cashouttype" class="form-control" value="1" disabled>
                      <option value="0">Select Cash Out Type</option>
                      <option value="1">Bank Deposit (BDO, Union and BPI only)</option>
                      <option value="2">GCash</option>
                      <option value="3">Palawan Express</option>
                      <option value="4">Cebuana Lhuillier</option>
                    </select>
                    </td>
                  </tr>
                  </tbody>
                  <tbody>
                  <tr>
                    <th>
                      Account Name
                    </th>
                    <th>
                      Account Number
                    </th>
                  </tr>
                  <tr>
                    <td>
                      <input style="width: 100%" type="text" name="" id="accountname" value="<?php echo $order_details['account_name'] ?>" placeholder="Account Name" readonly>
                    </td>
                    <td>
                      <input style="width: 100%" type="text" name="" id="accountnumber" value="<?php echo $order_details['account_number'] ?>" placeholder="Account Number" readonly>
                    </td>
                  </tr>
                </tbody>
                  <tbody>
                  <tr>
                    <th colspan="2">
                      Amount
                    </th>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <input style="width: 100%" id="txtamount" type="number" value="<?php echo $order_details['amount'] ?>" name="" readonly>
                    </td>
                  
                  </tr>
                </tbody>
                       <tbody>
                  <tr>
                    <th colspan="2">
                      Reference Number
                    </th>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <input style="width: 100%" id="txtamount" type="text" value="<?php echo $order_details['remarks'] ?>" name="" readonly>
                    </td>
                  
                  </tr>
                </tbody>
              </table>
  
  </div>
</div>
</body>
</html>

<script type="text/javascript">
  $( document ).ready(function() {
    
    $("#cashouttype").val('<?php echo $_GET['cashtype'] ?>');



  });
</script>