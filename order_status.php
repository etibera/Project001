<?php
require_once "common/headertest.php";
?>

<style>
  #status .time {
    flex: 0 0 140px;
  }

  #status li {
    min-height: 50px;
  }

  .status {
    width: 150px;
  }

  #statusSummary {
    max-width: 900px
  }
</style>

<div class="container bg-light" style="margin-top: 135px" id="orderStatus">
  <div class="pt-3 ms-1" style="font-size: 26px">Order Status</div>
  <div class="loading d-flex justify-content-center p-2 m-5">
    <div class="spinner-border p-4 m-5" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
  <div class="py-3 d-none">
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
          <div id="store">
          </div>
          <div class="d-flex align-items-center">
            <h5 class="d-inline-block my-0" id="orderNumber"></h5>
          </div>
        </div>
      </div>
      <div class=" card-body">
        <!-- Order Status -->
        <div class="d-flex justify-content-between mx-auto position-relative" id="statusSummary">
          <div class="position-absolute translate-middle start-50" style="height: 3px;width: calc(100% - 150px); background-color: #4b6ed6;margin-top: 40px"></div>
          <div class="d-flex flex-column align-items-center status">
            <div class="rounded-circle bg-white position-relative" style="width: 80px; height: 80px; border: 3px solid #4b6ed6; z-index: 1">
              <i class="fal fa-box position-absolute top-50 start-50 translate-middle" style="font-size: 35px; color: #4b6ed6"></i>
            </div>
            <div class="mt-3 text-center">
              <span class="fs-6 d-block">New Order</span>
              <small class="text-muted"></small>
            </div>
          </div>
          <div class="d-flex flex-column align-items-center status">
            <div class="rounded-circle bg-white position-relative" style="width: 80px; height: 80px; border: 3px solid #4b6ed6; z-index: 1">
              <i class="fal fa-warehouse position-absolute top-50 start-50 translate-middle" style="font-size: 35px; color: #4b6ed6"></i>
            </div>
            <div class="mt-3 text-center">
              <span class="fs-6 d-block">To Verify</span>
              <small class="text-muted"></small>
            </div>
          </div>
          <div class="d-flex flex-column align-items-center status">
            <div class="rounded-circle bg-white position-relative" style="width: 80px; height: 80px; border: 3px solid #4b6ed6; z-index: 1">
              <i class="fal fa-shipping-fast position-absolute top-50 start-50 translate-middle" style="font-size: 35px; color: #4b6ed6"></i>
            </div>
            <div class="mt-3 text-center">
              <span class="fs-6 d-block">To Ship</span>
              <small class="text-muted"></small>
            </div>
          </div>
          <div class="d-flex flex-column align-items-center status">
            <div class="rounded-circle bg-white position-relative" style="width: 80px; height: 80px; border: 3px solid #4b6ed6; z-index: 1">
              <i class="fal fa-box-check position-absolute top-50 start-50 translate-middle ms-1" style="font-size: 35px; color: #4b6ed6"></i>
            </div>
            <div class="mt-3 text-center">
              <span class="fs-6 d-block">To Received</span>
              <small class="text-muted"></small>
            </div>
          </div>
          <div class="d-flex flex-column align-items-center status">
            <div class="rounded-circle bg-white position-relative" style="width: 80px; height: 80px; border: 3px solid #4b6ed6; z-index: 1">
              <i class="fal fa-star position-absolute top-50 start-50 translate-middle" style="font-size: 35px; color: #4b6ed6"></i>
            </div>
            <div class="mt-3 text-center">
              <span class="fs-6 d-block">To Review</span>
              <small class="text-muted"></small>
            </div>
          </div>
        </div>
        <!-- Order Status -->
        <hr>

        <!-- Order Tracking -->
        <div id="tracking">
          <h2>Tracking Details</h2>
          <div class="row mt-4">
            <div class="col-4">
              <span class="lead" id="name"></span>
              <div class="mt-1">
                <span class="mb-0" id="contact"></span>
                <br>
                <span id="address"></span>
              </div>
            </div>
            <div class="col-8" id="status">
              <ul class="list-unstyled">
              </ul>
            </div>
          </div>
        </div>
        <!-- Order Tracking -->

        <!-- Product Details -->
        <div id="product">
          <div class="table-responsive">
            <table class="table mt-2">
              <tbody>
                <tr>
                  <td class="text-end border">Payment Method: </td>
                  <td class="text-end border" style="max-width: 50px;" id="paymentType"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <!-- Product Details -->
      </div>
    </div>
  </div>
</div>

<?php include_once "common/footer.php"; ?>

<script>
  $(document).ready(async () => {
    await get().then(() => {
      $('#status li:last-child .icon .bg-secondary').remove();
      $('#status li:first-child .icon i').removeClass('text-secondary').css('color', '#4b6ed6');
      $('#orderStatus>:nth-child(2)').remove();
      $('#orderStatus .d-none').removeClass('d-none');

      let sellerId, orderId, orderNumber;
    });
  });

  $(document).on('click', '#orderReceived', () => {
    reciveOrder(sellerId, orderId, orderNumber);
  });

  const get = () => {
    return new Promise(resolve => {
      $.ajax({
        url: `ajax_order_status.php?action=get&order=<?php echo $_GET['order'] ?? 0 ?>`,
        dataType: 'json',
        success: response => {
          if (response.type === "error") {
            alert(response.message);
            location.replace('order_history.php');
          }

          sellerId = response.store.seller_id;
          orderId = response.store.order_id;
          orderNumber = response.store.order_number;

          $('#name').html(response.contact.full_name);
          $('#address').html(response.contact.address);
          $('#contact').html(response.contact.contact);
          $('#paymentType').html(response.contact.payment_method);
          $('#orderNumber').html(`Order (#${response.store.order_number})`);

          if (response.currentStatus === 'pickup') {
            $('<button class="btn btn-primary shadow-none me-2" id="orderReceived"><i class="fal fa-box-check fa-lg"></i> Item Received</button>').insertBefore('#orderNumber');
          }

          response.status?.map(res => {
            if (parseInt(res.type) != 5 && parseInt(res.order_status_id) != 0) {
              $('#status > ul').append(
                `<li class="d-flex">
                <div class="time">
                  <span>${res.date}</span>
                </div>
                <div class="icon mx-4 position-relative">
                  <i class="fas fa-circle fa-lg text-secondary"></i>
                  <div class="bg-secondary position-absolute start-50 translate-middle-x top-0" style="width: 2px; height: calc(100% - 15px); z-index: 0; margin-top: 20px"></div>
                </div>
                <div>
                  ${res.name}
                </div>
              </li>`
              );
            }
          });

          response.product?.data?.map(res => {
            let status;
            if (!$.isEmptyObject(response.status) && response.currentStatus === 'received') {
              status = `<a class="text-decoration-none position-relative" href="review.php?order_id=<?php echo $_GET['order'] ?? 0 ?>&product_id=${res.product_id}">
                Write a review
                ${res.count === "1" ? `<span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger rounded-circle">
                  <span class="visually-hidden">New alerts</span>
                </span>` : ''}
                </a>`;
            } else {
              status = "";
            }
            $(`
          <div class="border-top mt-2">
            <div class="mt-2 px-2 d-flex align-items-center">
              <a class="text-decoration-none text-dark d-flex" href="product.php?product_id=${res.product_id}" target="_blank">
                <img src="${res.image}" class="img-thumbnail" style="max-width: 70px; max-height: 70px;">
                <div class="ms-2 align-self-start">
                  <strong class="d-block">${res.product_name}</strong>
                  <small>Quantity: ${res.quantity}</small>
                </div>
              </a>
              <span class="ms-auto">
                ${status}
                <span class="d-inline-block text-end" style="min-width: 110px">${res.total}</span>
              </span>
            </div>
          </div>
          `).insertBefore('#product table');
          });

          response.total?.map(res => {
            $('#product table').prepend(`<tr>
            <td class="position-relative border"><span class="position-absolute top-50 end-0 translate-middle-y me-2">${res.title}: </span></td>
            <td class="text-end border" style="max-width: 50px;">${res.value}</td>
          </tr>`);
          });


          $('#store').append(`
        <a href="product_store.php?Y2F0X2lk=${response.store.seller_id}" class="text-decoration-none" target="_blank">
          <img src="${response.store.image}" style="width: 35px; height: 35px">
          <span>${response.store.shop_name}</span>
        </a>
        <a class="text-decoration-none text-dark ms-1" href="message.php?Y2F0X2lk=${response.store.seller_id}&branch_id=${response.store.branch_id}" target="_blank"><i class="fas fa-comment-dots"></i> Chat Shop</a>
        `);

          response.status?.map((res, key) => {
            $(`#statusSummary :nth-child(${parseInt(res.type) + 1}) .text-center .d-block`).siblings('small').html(res.date);
            $(`#statusSummary :nth-child(${parseInt(res.type) + 1}) .rounded-circle`).removeClass('bg-white').css('background-color', '#4b6ed6');
            $(`#statusSummary :nth-child(${parseInt(res.type) + 1}) i`).css('filter', 'brightness(0) invert(100)');

            switch (res.type) {
              case '2':
                $(`#statusSummary :nth-child(${parseInt(res.type) + 1}) span`).html('Order Verification');
                break;
              case '3':
                $(`#statusSummary :nth-child(${parseInt(res.type) + 1}) span`).html('Shipment Out');
                break;
              case '4':
                $(`#statusSummary :nth-child(${parseInt(res.type) + 1}) span`).html('Order Received');
                break;
              case '5':
                $(`#statusSummary :nth-child(${parseInt(res.type) + 1}) span`).html('Order Complete');
                break;
              default:
                break;
            }
          })
        }
      }).done(() => {
        resolve();
      });
    });
  }

  const reciveOrder = (seller_id, order_id, order_number) => {
    $.ajax({
      url: 'ajax_add_to_cart_latest.php?action=ReciveStoreOrder',
      type: 'POST',
      data: 'seller_id=' + seller_id + '&order_id=' + order_id + '&order_number=' + order_number,
      dataType: 'json',
      beforeSend: function() {
        bootbox.dialog({
          title: "Receiving Order",
          message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
        });
      },
      success: function(json) {
        if (json['success']) {
          bootbox.alert(json['success'], function() {
            location.reload();
          });
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  }
</script>