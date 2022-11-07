<?php
include 'common/headertest.php';
require_once "model/message.php";
// Insert Activity
require_once "model/customer_activity.php";
$customerActivity = new CustomerActivity();
$customerActivity->insertactivity($userid, 'Seller Message');
$model_msg = new message;
$imessageseller = 0;
$dataSellerName = "";
$dataSellerId = 0;
$dataBranchId = 0;
if (isset($_GET['Y2F0X2lk'])) {
  $imessageseller++;
  $DataGetsellerInfo = $model_msg->GetsellerInfo($_GET['Y2F0X2lk'], $_GET['branch_id']);
  $dataSellerName = $DataGetsellerInfo['b_name'];
  $dataSellerId = $DataGetsellerInfo['seller_id'];
  $dataBranchId = $_GET['branch_id'];
}
$customer_id = $userid;
$MessageList = $model_msg->getCustomerMessageList($customer_id);
$keys = array_column($MessageList, 'timestamp');
array_multisort($keys, SORT_DESC, $MessageList);
/* echo "<pre>";
 print_r($DataGetsellerInfo);*/

?>
<link rel="stylesheet" href="./assets/css/acc_nav.css">
<script type="text/javascript">
  var islog = '<?php echo $is_log; ?>';
  if (islog == "0") {
    location.replace("home.php");
  }
</script>
<style>
  .notification {
    position: relative;
    display: inline-block;
  }

  .notification .badge {
    position: absolute !important;
    top: -10px !important;
    right: -10px !important;
    padding: 5px 10px !important;
    border-radius: 100% !important;
    background: red !important;
    color: white !important;
  }
</style>
<div class="container bg-white p-sm-3" style="margin-top: 135px">
  <div class="form-group acc" style="margin:10px">
    <ul>
      <li><a class="active" href="message.php">Seller</a></li>
      <li><a href="message_admin.php">Admin</a></li>
    </ul>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading" style="padding:20px;">
        <div class="row">
          <div class="col-lg-6">
            <p style="font-weight: 700;" class="panel-title"> <i class="fa fa-envelope"></i> Messages</p>
          </div>
        </div>
      </div>
      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover msg-table">
            <thead>
              <thead>
                <th class="text-center">Seller Name</th>
                <th class="text-center">Last Message</th>
                <th class="text-center">Date</th>
                <th class="text-center">Action</th>
              </thead>
            <tbody id="tblTbody">
              <?php foreach ($MessageList as $ml) { ?>
                <tr>
                  <td><?php echo $ml['fullname']; ?></td>
                  <td><?php echo $ml['message']; ?></td>
                  <td><?php echo $ml['timestampval']; ?></td>
                  <td>
                    <a class="btn btn-warning notification btn-msg-list" data-bs-toggle="modal" data-bs-target="#MessageModal" data-seller_id="<?php echo $ml['seller_id']; ?>" data-branch_id="<?php echo $ml['branch_id']; ?>" data-customer_id="<?php echo $customer_id; ?>" data-fullname="<?php echo $ml['fullname']; ?>" title="messages">
                      <i class="fas fa-envelope"></i>
                      <?php if ($ml['no_unreadmsg']) { ?>
                        <span class="badge"><?php echo $ml['no_unreadmsg']; ?></span>
                      <?php } ?>
                    </a>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="MessageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-error-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #4b6ed6;">
        <h4 class="modal-title" style="float:left;color:white;" id="modal-title">Loading...</h4>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body bg-light">
        <input type="hidden" id="seller_id">
        <input type="hidden" id="customer_id">
        <input type="hidden" id="branch_id">
        <input type="hidden" id="convo_length" value="0">
        <div class="row">
          <div class="col-lg-12 message-content" id="message-content" style="height: 250px; overflow: auto;">
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12 message-text-area" style="margin-top:10px">
            <textarea id="text_message" class="form-control" placeholder="Type a message..." cols="40" style="resize: none;"></textarea>
          </div>

        </div>
        <div class="row">
          <div class="col-lg-12 message-send" style="margin-top:10px">
            <button class="btn btn-primary btn-block btn-send" title="Send"><i class="fas fa-paper-plane"></i> Send</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
</div>
</div>


<script>
  var msg_interval;
  var new_msg_interval;

  function doLoadMessage() {

    var seller_id = $('#seller_id').val();
    var customer_id = $('#customer_id').val();
    var branch_id = $('#branch_id').val();
    if (customer_id != '') {
      GetConversation(seller_id, customer_id, branch_id);
      //console.log(parseFloat($('#convo_length').val()) + ' : '+ flagId);
      if (parseFloat($('#convo_length').val()) != flagId) {
        isNewMessage();
      }
      flagId = parseFloat($('#convo_length').val());
    }
  }

  var flagId = 0;
  var productId = 0;

  function isNewMessage() {
    var element = document.getElementById('message-content');
    if (!atBottom(element)) {
      $('.message-content').animate({
        scrollTop: 999999
      }, 'fast');
    }
  }


  $(document).ready(function() {
    var customer_id = <?php echo $customer_id; ?>;
    var imessageseller = <?php echo $imessageseller; ?>;
    if (imessageseller == 1) {
      $('#MessageModal').modal('show');
      var dataSellerName = "<?php echo $dataSellerName; ?>";
      var seller_id = <?php echo $dataSellerId; ?>;
      var customer_id = <?php echo $customer_id; ?>;
      var branch_id = <?php echo $dataBranchId; ?>;
      $('#customer_id').val(customer_id);
      $('#seller_id').val(seller_id);
      $('#branch_id').val(branch_id);
      $('.modal-title').html(dataSellerName);
      UpdateToIsRead(seller_id, customer_id, branch_id);
      GetConversation(seller_id, customer_id, branch_id);
      $('.message-content').animate({
        scrollTop: 999999
      }, 'fast');
    }
    //   GetCustomerMessages(customer_id);

    $('.msg-table').on('click', '.btn-msg-list', function() {
      var fullname = $(this).data('fullname');
      var seller_id = $(this).data('seller_id');
      var branch_id = $(this).data('branch_id');
      var customer_id = $(this).data('customer_id');
      var message_id = $(this).data('message_id');
      $('#customer_id').val(customer_id);
      $('#seller_id').val(seller_id);
      $('#branch_id').val(branch_id);
      $('.modal-title').html(fullname);
      UpdateToIsRead(seller_id, customer_id, branch_id);
      GetConversation(seller_id, customer_id, branch_id);
      $('.message-content').animate({
        scrollTop: 999999
      }, 'fast');
    });

    $('.close').on('click', function() {
      // GetCustomerMessages($('#customer_id').val());
      clearInterval(msg_interval);
      $('.message-content').empty();
    });

    $('.btn-send').on('click', function() {
      var seller_id = $('#seller_id').val();
      var customer_id = $('#customer_id').val();
      var message = $('#text_message').val();
      var branch_id = $('#branch_id').val();
      InsertMessage(seller_id, customer_id, message, branch_id);
      $('#text_message').val('');
    });

    $('#text_message').on('click', function() {
      $('.message-content').animate({
        scrollTop: 999999
      }, 'fast');
      msg_interval = setInterval(function() {
        doLoadMessage();
      }, 1000);
    });

  });



  function GetConversation(seller_id, customer_id, branch_id) {
    $.ajax({
      url: 'ajax_message.php?action=GetConversations&t=' + new Date().getTime(),
      data: {
        seller_id: seller_id,
        customer_id: customer_id,
        branch_id: branch_id,
      },
      type: "GET",
      datatype: "json"
    }).done(function(data) {
      const url = new URL(window.location.href);
      if (url.searchParams.get('prdval') !== null) {
        productId = url.searchParams.get('prdval');
      }
      //alert(JSON.stringify(url.searchParams.get('prdval')))

      var list = JSON.parse(data);
      $('.message-content').empty();
      for (var i = 0; i < list.length; i++) {

        if (list[i].product_id != 0) {
          $('.message-content').append(
            '<div style="display: flex; float: right; width: 90%;">' +
            '<div style="width: 50%; padding: 10px; display: inline-flex; border-radius: 10px; background-color: #fff; margin-left: auto; margin-top: 30px">' +
            '<div class="col-md-6"><img src="' + list[i].product.image + '" width="100"/></div>' +
            '<div class="col-md-6">' +
            '<div style="height: 40px; overflow: hidden">' + list[i].product.name + '</div>' +
            '<div style="color: red">₱' + parseFloat(list[i].product.price).toFixed(2) + '</div>' +
            '<div><a href="product.php?product_id=' + list[i].product_id + '" class="btn btn-primary" style="font-size: 10px">View Product</a></div>' +
            '</div>' +
            '</div>' +
            '</div>'
          );

        }
        if (list[i]['receiver'] != customer_id) {

          $('.message-content').append(
            '<div style="width:100%";display: inline-block;">' +
            '<div style="border:1px solid blue;clear:right;float:right;background-color:blue;color:white;border-radius:10px 10px 0px 10px;padding:10px;overflow-wrap:break-word;margin-bottom:10px;max-width:75%;">' + list[i]['message'] + '</div><br><p style="clear:right;float:right;color:gray;font-size:9px">' + list[i]['timestamp'] + '</p>' +
            '</div><br>'
          );
        } else {

          $('.message-content').append(

            '<div style="width:100%;display:inline-block;">' +
            '<div style="border:1px solid gray;clear:left;float:left;background-color:white;border-radius:10px 10px 10px 0px;padding:10px;overflow-wrap:break-word;margin-bottom:10px;max-width:75%;">' + list[i]['message'] +
            '</div><br><p style="clear:left;float:left;color:gray;font-size:9px">' + list[i]['timestamp'] + '</p>' +
            '</div><br>'
          );
        }
      }
      $('#convo_length').val(list.length);

    });

  }

  function UpdateToIsRead(seller_id, customer_id, branch_id) {
    $.ajax({
      url: 'ajax_message.php?action=UpdateToIsRead&t=' + new Date().getTime(),
      data: {
        seller_id: seller_id,
        customer_id: customer_id,
        branch_id: branch_id,
      },
      type: "POST",
      datatype: "json"
    }).done(function(data) {
      var status = JSON.parse(data);

    });
  }

  function InsertMessage(seller_id, customer_id, message, branch_id) {
    $.ajax({
      url: 'ajax_message.php?action=InsertMessage&t=' + new Date().getTime(),
      data: {
        seller_id: seller_id,
        customer_id: customer_id,
        message: message,
        product_id: productId,
        branch_id: branch_id
      },
      type: "POST",
      datatype: "json"
    }).done(function(data) {
      var status = JSON.parse(data);
      removeParam('prdval', window.location.href)
      productId = 0;
    });
  }

  function removeParam(key, sourceURL) {
    var rtn = sourceURL.split("?")[0],
      param,
      params_arr = [],
      queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
    if (queryString !== "") {
      params_arr = queryString.split("&");
      for (var i = params_arr.length - 1; i >= 0; i -= 1) {
        param = params_arr[i].split("=")[0];
        if (param === key) {
          params_arr.splice(i, 1);
        }
      }
      if (params_arr.length) rtn = rtn + "?" + params_arr.join("&");
    }
    return rtn;
  }

  function atBottom(ele) {
    var sh = ele.scrollHeight;
    var st = ele.scrollTop;
    var ht = ele.offsetHeight;
    if (ht == 0) {
      return true;
    }
    if (st == sh - ht) {
      return true;
    } else {
      return false;
    }
  }
</script>
<?php include 'common/footer.php'; ?>