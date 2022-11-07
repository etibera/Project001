<?php
include_once "common/headertest.php";
?>

<style>
  .light-theme>* {
    padding: 2px 12px !important;
    font-size: 1rem !important;
    border-radius: 3px !important;
    border: 1px solid #dee2e6 !important;
    box-shadow: none !important;
  }

  .light-theme .current {
    background-color: #212529 !important;
  }

  .light-theme a:hover {
    color: #666 !important;
  }
</style>
<div class="container bg-light py-3" style="margin-top: 135px">
  <span class="ps-1" style="font-size: 26px">Order History</span>
  <div class="mt-3 shadow-sm d-flex justify-content-around">
    <button class="filter bg-white border-0 w-100 py-3 text-primary border-bottom border-3 border-primary active" data-value="all">
      <span class="d-block text-center">All</span>
    </button>
    <button class="filter bg-white border-0 w-100 py-3" data-value="1">
      <span class="d-block text-center">To Pay</span>
    </button>
    <button class="filter bg-white border-0 w-100 py-3" data-value="2">
      <span class="d-block text-center">To Ship</span>
    </button>
    <button class="filter bg-white border-0 w-100 py-3" data-value="3">
      <span class="d-block text-center">To Receive</span>
    </button>
    <button class="filter bg-white border-0 w-100 py-3" data-value="4">
      <span class="d-block text-center position-relative">Delivered
        <div id="allReviewCount" class="position-absolute top-0 end-0 translate-middle rounded-pill badge bg-danger"></div>
      </span>
    </button>
  </div>
  <div id="products">
    <div class="loading p-5 mt-4 d-flex justify-content-center">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
  </div>
  <div class="d-flex justify-content-end py-3" id="pagination"></div>
</div>

<?php include_once "common/footer.php"; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.4/jquery.simplePagination.js" integrity="sha512-D8ZYpkcpCShIdi/rxpVjyKIo4+cos46+lUaPOn2RXe8Wl5geuxwmFoP+0Aj6wiZghAphh4LNxnPDiW4B802rjQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.4/simplePagination.css" integrity="sha512-emkhkASXU1wKqnSDVZiYpSKjYEPP8RRG2lgIxDFVI4f/twjijBnDItdaRh7j+VRKFs4YzrAcV17JeFqX+3NVig==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<script>
  $(document).ready(() => {
    $('.filter:not(.active)').attr('disabled', true);
    getCount().then(({
      type,
      count
    }) => {
      if (type) {
        get("all", location.hash.substr(6) ? location.hash.substr(6) : 1).then(res => {
          if ($.isEmptyObject(res.order)) {
            $('#products').html(`
            <div class="d-flex align-items-center flex-column pt-5 text-secondary">
              <i class="fal fa-boxes-alt fa-3x my-3"></i>
              <h5 class="m-0">No Placed Order</h5>
            </div>
            `);
          } else {
            generatePagination(count);
            $('.loading').remove();
          }
          $('.filter:not(.active)').attr('disabled', false);
        });
      } else {
        get().then(res => {
          if ($.isEmptyObject(res.order)) {
            $('#products').html(`
            <div class="d-flex align-items-center flex-column pt-5 text-secondary">
              <i class="fal fa-boxes-alt fa-3x my-3"></i>
              <h5 class="m-0">No Placed Order</h5>
            </div>
            `);
          } else {
            $('.loading').remove();
          }
          $('.filter:not(.active)').attr('disabled', false);
        });
      }
    });

  });

  $(document).on('click', '.filter:not(.active)', function() {
    $('.filter').not(this).attr('disabled', true);
    $('.filter').removeClass('text-primary border-bottom border-3 border-primary active');
    $(this).addClass('text-primary border-bottom border-3 border-primary active');

    $('#products').html(`
    <div class="loading p-5 mt-4 d-flex justify-content-center">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>`);

    $('#pagination').html('');

    getCount().then(({
      type,
      count
    }) => {
      if (type) {
        get($('.filter.active').attr('data-value'), location.hash.substr(6) ? location.hash.substr(6) : 1).then(res => {
          if ($.isEmptyObject(res.order)) {
            $('#products').html(`
            <div class="d-flex align-items-center flex-column pt-5 text-secondary">
              <i class="fal fa-boxes-alt fa-3x my-3"></i>
              <h5 class="m-0">No Placed Order</h5>
            </div>
            `);
          } else {
            generatePagination(count);
            $('.loading').remove();
          }
          $('.filter:not(.active)').attr('disabled', false);
        });
      } else {
        get($('.filter.active').attr('data-value')).then(res => {
          if ($.isEmptyObject(res.order)) {
            $('#products').html(`
            <div class="d-flex align-items-center flex-column pt-5 text-secondary">
              <i class="fal fa-boxes-alt fa-3x my-3"></i>
              <h5 class="m-0">No Placed Order</h5>
            </div>
            `);
          } else {
            $('.loading').remove();
          }
          $('.filter:not(.active)').attr('disabled', false);
        });
      }
    });

  });

  $(window).on('hashchange', function() {
    $('#products').html(`
    <div class="loading p-5 mt-4 d-flex justify-content-center">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>`);
    $('#pagination').addClass('visually-hidden');
    getCount().then(({
      type,
      count
    }) => {
      if (type) {
        get($('.filter.active').attr('data-value'), location.hash.substr(6) ? location.hash.substr(6) : 1).then(res => {
          if ($.isEmptyObject(res.order)) {
            $('#products').html(`
            <div class="d-flex align-items-center flex-column pt-5 text-secondary">
              <i class="fal fa-boxes-alt fa-3x my-3"></i>
              <h5 class="m-0">No Placed Order</h5>
            </div>
            `);
          } else {
            generatePagination(count);
            $('#pagination').removeClass('visually-hidden');
            $('.loading').remove();
          }
        });
      } else {
        get($('.filter.active').attr('data-value')).then(res => {
          if ($.isEmptyObject(res.order)) {
            $('#products').html(`
            <div class="d-flex align-items-center flex-column pt-5 text-secondary">
              <i class="fal fa-boxes-alt fa-3x my-3"></i>
              <h5 class="m-0">No Placed Order</h5>
            </div>
            `);
          } else {
            $('.loading').remove();
          }
        });
      }
    });
  });

  $(document).on('click', '#cancelOrder', function() {
    const orderNumber = $(this).attr('data-value');
    bootbox.confirm("Are you sure you want to cancel this Order?", result => {
      if (result) {
        $(this).remove();
        $.ajax({
          url: `ajax_orders_history.php?action=cancelOrder&t=${new Date().getTime()}`,
          method: 'POST',
          dataType: 'json',
          data: {
            orderNumber
          },
          success: response => {
            bootbox.alert(response.message, () => {
              $('#products').html(`
              <div class="loading p-5 mt-4 d-flex justify-content-center">
                <div class="spinner-border" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
              </div>`);

              $('#pagination').html('');

              getCount().then(({
                type,
                count
              }) => {
                if (type) {
                  get($('.filter.active').attr('data-value'), location.hash.substr(6) ? location.hash.substr(6) : 1).then(res => {
                    if ($.isEmptyObject(res.order)) {
                      $('#products').html(`
                      <div class="d-flex align-items-center flex-column pt-5 text-secondary">
                        <i class="fal fa-boxes-alt fa-3x my-3"></i>
                        <h5 class="m-0">No Placed Order</h5>
                      </div>
                      `);
                    } else {
                      generatePagination(count);
                      $('.loading').remove();
                    }
                    $('.filter:not(.active)').attr('disabled', false);
                  });
                } else {
                  get($('.filter.active').attr('data-value')).then(res => {
                    if ($.isEmptyObject(res.order)) {
                      $('#products').html(`
                      <div class="d-flex align-items-center flex-column pt-5 text-secondary">
                        <i class="fal fa-boxes-alt fa-3x my-3"></i>
                        <h5 class="m-0">No Placed Order</h5>
                      </div>
                      `);
                    } else {
                      $('.loading').remove();
                    }
                    $('.filter:not(.active)').attr('disabled', false);
                  });
                }
              });
            });
          }
        })
      }
    });
  })

  const get = (filter = "all", page = "all") => {
    return new Promise(resolve => {
      $.ajax({
        url: `ajax_orders_history.php?action=get&filter=${filter}&page=${page}&t=${new Date().getTime()}`,
        dataType: 'json',
        success: response => {
          $('#allReviewCount').html(parseInt(response.review) != 0 ? response.review : '');
          $('#products').html('');
          response.order?.map(order => {
            $('#products').append(
              `
            <div class="products">
              <div class="card mt-3">
                <div class="card-header position-relative">
                  <div class="position-absolute top-0 start-100 translate-middle rounded-pill badge bg-danger">${parseInt(order.review) > 0 ? order.review : ''}</div>
                  <div class="d-flex align-items-center">
                    <div>
                      <a href="product_store.php?Y2F0X2lk=${order.store.seller_id}" class="text-decoration-none" target="_blank">
                        <img src="${order.store.branch_logo}" style="width: 35px; height: 35px">
                        <span>${order.store.shop_name}</span>
                      </a>
                      <a class="text-decoration-none text-dark ps-2" href="message.php?Y2F0X2lk=${order.store.seller_id}&branch_id=${order.store.branch_id}" target="_blank"><i class="fas fa-comment-dots"></i> Chat Shop</a>
                    </div>
                    <div class="d-inline-block ms-auto">
                      ${parseInt(order.store.status_type) === 4 ?`<a class="btn btn-warning shadow-none me-1 position-relative" href="invoice.php?orderid=${order.store.order_number}" target="_blank">Print Invoice</a>` : ''}
                      ${parseInt(order.store.order_status_id) === 17 ? `<button class="btn btn-danger shadow-none me-1" id="cancelOrder" data-value="${order.store.order_number}">Cancel Order</button>` : ''}
                      <span>Order (#${order.store.order_number})</span>
                      ${order.store.status ? `<span class="text-success fw-bold border-start border-2 border-secondary ps-1">${order.store.status}</span>` : ''}
                    </div>
                  </div>
                </div>
                <div class="card-body pb-2 position-relative" id="${order.store.order_number}">
                  <a href="order_status.php?order=${order.store.order_number}" class="position-absolute w-100 h-100 top-0 start-0"></a>
                </div>
                <div class="card-footer bg-white">
                  <span class="d-block text-end p-0 m-0">Order Total:<span class="fw-bold fs-4 ms-2">${order.store.total}</span></span>
                </div>
              </div>
            </div>
            `
            );

            order.product.map(product => {
              $(`#${order.store.order_number}`).append(`
              <div class="border-bottom pb-2 mb-2">
                <div class="px-2 d-flex align-items-center">
                  <img src="${product.image}" class="img-thumbnail" style="max-width: 70px; max-height: 70px;">
                  <div class="ms-2 align-self-start">
                    <strong class="d-block">${product.product_name}</strong>
                    <small>Quantity: ${product.quantity}</small>
                  </div>
                  <span class="ms-auto">
                    <span>${product.total}</span>
                  </span>
                </div>
              </div>
            `);
            });

          })
        }
      }).done(res => {
        resolve(res);
      });
    });
  }

  const getCount = () => {
    return new Promise(resolve => {
      $.ajax({
        url: `ajax_orders_history.php?action=getRowCount&filter=${$('.filter.active').attr('data-value')}&t=${new Date().getTime()}`,
        dataType: 'json',
      }).done(res => {
        resolve(res);
      })
    });
  }

  const generatePagination = count => {
    $('#pagination').pagination({
      items: count,
      itemsOnPage: 5,
      edges: 1,
      currentPage: location.hash.substr(6) ? location.hash.substr(6) : 1,
      prevText: '<i class="fas fa-angle-left"></i>',
      nextText: '<i class="fas fa-angle-right"></i>',
      cssStyle: 'light-theme'
    });
  }
</script>