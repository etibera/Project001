<?php
include_once "common/headertest.php";
include_once "model/customer_activity.php";
include_once "model/product.php";

if (!isset($_GET['product_id'])) {
  redirect('home');
}
$product_id = $_GET['product_id'];
$model = new customeractivity();
$model_product = new product();
if ($is_log != 1) {
  header("Location: https://pesoapp.ph/home.php?product_id=" . $_GET['product_id'] . "&t=" . uniqid());
} else {
  $model->insertProductActivity($userid, 'Product Page', $product_id);
  $model_product->save_view_products($product_id, $userid, 0);
}
?>
<style>
  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  input[type=number] {
    -moz-appearance: textfield;
  }

  input[type=number]:focus {
    outline: 0px !important;
  }

  .btn,
  select {
    outline: 0px !important;
    -webkit-appearance: none;
    box-shadow: none !important;
  }

  .quantity {
    width: fit-content !important;
  }

  #quantity {
    min-width: 40px;
  }

  .slick-arrow::before {
    color: black;
    font-size: 25px;
  }

  button[name="buy"],
  button[name="cart"],
  button[name="wish"] {
    white-space: nowrap;
    text-align: center;
  }

  .right,
  .right select {
    font-size: .8rem;
  }

  .select:hover:not(.active) {
    color: #212529;
    border-color: #212529;
    background-color: transparent;
  }

  .store.active {
    background-color: rgba(0, 0, 0, 0.2);
  }

  #store {
    position: relative;
    overflow-y: auto;
    height: 580px;
  }

  #store.active {
    overflow: hidden;
  }

  #store.active::after {
    content: '';
    top: 0;
    width: 100%;
    height: 100vh;
    position: absolute;
    animation: bgFade 2s linear;
  }

  .fas.fa-star {
    color: #FAC917;
  }

  .fas.fa-star.not {
    color: gray;
  }

  .progress {
    width: 150px !important;
  }

  .progress-bar {
    background: #FAC917;
  }

  .progress,
  .progress-bar {
    border-radius: 8px;
  }

  .default {
    font-family: sans-serif !important;
  }

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

  #description .card-body {
    max-height: 500px;
    overflow-y: hidden;
  }

  #description .card-body.active {
    max-height: none;
    padding-bottom: 70px;
  }

  #showMore {
    background-color: #4b6ed6;
    border: #4b6ed6;
  }

  #recommended>.card>.card-body a:hover {
    box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
  }

  .ribbon {
    width: 48%;
    position: relative;
    float: left;
    margin-bottom: 30px;
    background-size: cover;
    text-transform: uppercase;
    color: white;
  }

  .ribbon3 {
    min-width: 100px;
    width: fit-content;
    white-space: nowrap;
    height: 25px;
    line-height: 25px;
    padding-left: 15px;
    position: absolute;
    left: -8px;
    top: 15px;
    background: red;
    font-size: 80%;
  }

  .ribbon3:before,
  .ribbon3:after {
    content: "";
    position: absolute;
  }

  .ribbon3:before {
    height: 0;
    width: 0;
    top: -8.5px;
    left: 0.1px;
    border-bottom: 9px solid black;
    border-left: 9px solid transparent;
  }

  .ribbon3:after {
    height: 0;
    width: 0;
    right: -17px;
    border-top: 15px solid transparent;
    border-bottom: 10px solid transparent;
    border-left: 18px solid red;
  }

  .card__holder {
    width: 215px;
    height: 300px;
    overflow: hidden;
  }



  .image {
    width: 50px;
    height: 50px;
  }

  @keyframes bgFade {
    0% {
      opacity: 1;
      background: red;
      height: 100vh;
    }

    100% {
      opacity: 0;
    }
  }
</style>

<!-- Container -->
<div class="container bg-white" style="margin-top: 135px">

  <!-- Loaded -->
  <div class="unload d-none">
    <div class="row">

      <!-- Product Image -->
      <div class="col-12 col-md-7 col-lg-6 col-xl-4 pt-3" style="font-size: .7rem">
        <div id="productImageDisplay" class="slider-for">
        </div>
        <center>
          <div id="productImageNav" class="slider-nav mt-3" style="width: 90%;">
          </div>
        </center>
      </div>
      <!-- Product Image -->

      <!-- Product Details -->
      <div class="col-12 col-md-5 col-lg-6 col-xl-5 pt-3" id="details">
        <div class="row flex-column">
          <div class="col">
            <h2 id="productName"></h2>
            <p class="mt-3"><strong>Model:</strong> <span id="productModel"></span></p>
          </div>
          <div class="col">
            <h4 id="productPrice" class="mt-4"></h4>
            <hr>
          </div>
          <div class="col mt-3 d-flex flex-md-column flex-lg-row">
            <button name="buy" class="btn btn-danger rounded-0 py-2 w-100 me-1 mt-md-1 me-md-0 me-lg-1">Buy Now</button>
            <button name="cart" class="btn btn-danger rounded-0 py-2 w-100 me-1 mt-md-1 me-md-0 me-lg-1">Add to Cart</button>
            <button name="wish" class="btn btn-primary rounded-0 py-2 w-100 mt-md-1">Add to Wishlist</button>
          </div>
        </div>
      </div>
      <!-- Product Details -->

      <!-- Store List -->
      <div class="col-12 col-xl-3 right bg-light p-0 mt-sm-2 mt-xl-0" id="store">
      </div>
      <!-- Store List -->

    </div>

    <!-- Product Description -->
    <div class="mt-3 pb-3">
      <div id="description" class="card rounded">
        <div class="card-header bg-light py-3">
          <h5 class="m-0">Product Details of <span id="descriptionTitle"></span></h5>
        </div>
        <div class="card-body position-relative">
        </div>
      </div>
    </div>
    <!-- Product Description -->

    <!-- Product Review -->
    <div class="mt-3 pb-3">
      <div id="review" class="card rounded">
        <div class="card-header bg-light py-3">
          <h5 class="m-0">Product Reviews of <span id="reviewTitle"></span></h5>
        </div>
        <div class="card-body">
          <div class="row border-bottom border-dark border-2 pb-3">
            <div class="col-3">
              <h2><span id="allRating"></span>/5</h2>
              <div class="star">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="42px" height="42px">
                  <defs>
                    <linearGradient id="grad1">
                      <stop offset="0%" stop-color="#FAC917" />
                      <stop offset="0%" stop-color="grey" />
                    </linearGradient>
                  </defs>
                  <path fill="url(#grad1)" d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="42px" height="42px">
                  <defs>
                    <linearGradient id="grad2">
                      <stop offset="0%" stop-color="#FAC917" />
                      <stop offset="0%" stop-color="grey" />
                    </linearGradient>
                  </defs>
                  <path fill="url(#grad2)" d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="42px" height="42px">
                  <defs>
                    <linearGradient id="grad3">
                      <stop offset="0%" stop-color="#FAC917" />
                      <stop offset="0%" stop-color="grey" />
                    </linearGradient>
                  </defs>
                  <path fill="url(#grad3)" d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="42px" height="42px">
                  <defs>
                    <linearGradient id="grad4">
                      <stop offset="0%" stop-color="#FAC917" />
                      <stop offset="0%" stop-color="grey" />
                    </linearGradient>
                  </defs>
                  <path fill="url(#grad4)" d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="42px" height="42px">
                  <defs>
                    <linearGradient id="grad5">
                      <stop offset="0%" stop-color="#FAC917" />
                      <stop offset="0%" stop-color="grey" />
                    </linearGradient>
                  </defs>
                  <path fill="url(#grad5)" d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z" />
                </svg>
              </div>
              <p class="mt-2" id="allRatingCount"></p>
            </div>
            <div class="col-3" id="ratingCount">
              <div class="d-flex align-items-center">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <div class="progress mx-2">
                  <div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span></span>
              </div>
              <div class="d-flex align-items-center">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star not"></i>
                <div class="progress mx-2">
                  <div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span></span>
              </div>
              <div class="d-flex align-items-center">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star not"></i>
                <i class="fas fa-star not"></i>
                <div class="progress mx-2">
                  <div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span></span>
              </div>
              <div class="d-flex align-items-center">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star not"></i>
                <i class="fas fa-star not"></i>
                <i class="fas fa-star not"></i>
                <div class="progress mx-2">
                  <div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span></span>
              </div>
              <div class="d-flex align-items-center">
                <i class="fas fa-star"></i>
                <i class="fas fa-star not"></i>
                <i class="fas fa-star not"></i>
                <i class="fas fa-star not"></i>
                <i class="fas fa-star not"></i>
                <div class="progress mx-2">
                  <div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span></span>
              </div>
            </div>
            <div class="col-6">
              <div class="d-flex justify-content-between">
                <button type="button" class="filter btn btn-sm rounded-0 btn-outline-dark w-100 mx-1 active" data-value="all">All</button>
                <button type="button" class="filter btn btn-sm rounded-0 btn-outline-dark w-100 mx-1" data-value="5">5 Star</button>
                <button type="button" class="filter btn btn-sm rounded-0 btn-outline-dark w-100 mx-1" data-value="4">4 Star</button>
                <button type="button" class="filter btn btn-sm rounded-0 btn-outline-dark w-100 mx-1" data-value="3">3 Star</button>
                <button type="button" class="filter btn btn-sm rounded-0 btn-outline-dark w-100 mx-1" data-value="2">2 Star</button>
                <button type="button" class="filter btn btn-sm rounded-0 btn-outline-dark w-100 mx-1" data-value="1">1 Star</button>
              </div>
              <div class="mt-2 ms-1">
                <label class="form-label" for="filterStore">Filter by Store</label>
                <select class="form-select w-auto rounded-0 border-dark" id="filterStore">
                  <option value="all">All</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div id="pagination" class="d-flex justify-content-end pb-3">

        </div>
      </div>
    </div>
    <!-- Product Review -->

    <!-- Recommended Products -->
    <div id="recommended" class="mt-2 pb-3">
      <div class="card">
        <div class="card-header bg-light py-3">
          <h5 class="m-0">Recommended Products</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div id="recommendedLoading" class="text-center my-5 p-5">
              <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
          </div>
        </div>
        <div id="loadRecommended">
          <input type="hidden">
        </div>
      </div>
    </div>
    <!-- Recommended Products -->

  </div>
  <!-- Loaded -->

  <!-- Loading -->
  <div class="row placeholder-glow loading">
    <div class="col-12 col-md-7 col-lg-6 col-xl-4 col-xxl-4 pt-3">
      <div class="placeholder w-100" style="height: 416px"></div>
    </div>
    <div class="col-12 col-md-5 col-lg-6 col-xl-5 col-xxl-5 pt-3">
      <div class="row flex-column placeholder-glow">
        <div class="col">
          <h2 class="placeholder col-10"></h2>
          <p class="mt-3 placeholder col-7"></p>
        </div>
        <div class="col">
          <h4 class="placeholder col-3 mt-4"></h4>
          <hr class="placeholder w-100">
        </div>
        <div class="col mt-3">
          <h1 class="placeholder col-4 py-3"></h1>
        </div>
        <div class="col mt-3">
          <h1 class="placeholder col-12 py-4"></h1>
        </div>
      </div>
    </div>
    <div class="col-12 col-xl-3 col-xxl-3 right bg-light p-0 mt-sm-2 mt-lg-0">
      <div class="row pb-2 placeholder-glow mx-1">
        <div class="col-12 pt-2">
          <h4 class="placeholder col-5"></h4>
        </div>
        <div class="col-12 pt-2">
          <div class="d-flex align-items-start">
            <div class="placeholder" style="width: 70px; height: 70px"></div>
            <h5 class="placeholder ms-2 col-6"></h5>
            <div class="placeholder ms-auto" style="width: 42px; height: 42px;"></div>
          </div>
        </div>
        <div class="col-12 pt-3">
          <h4 class="placeholder col-6"></h4>
          <h3 class="select-control placeholder w-100"></h3>
        </div>
        <div class="col-12 pt-3">
          <h1 class="select-control placeholder w-100 py-4"></h1>
        </div>
        <hr class="placeholder w-100 mt-3">
      </div>
      <div class="row pb-2 placeholder-glow mx-1">
        <div class="col-12 pt-2">
          <h4 class="placeholder col-5"></h4>
        </div>
        <div class="col-12 pt-2">
          <div class="d-flex align-items-start">
            <div class="placeholder" style="width: 70px; height: 70px"></div>
            <h5 class="placeholder ms-2 col-6"></h5>
            <div class="placeholder ms-auto" style="width: 42px; height: 42px;"></div>
          </div>
        </div>
        <div class="col-12 pt-3">
          <h4 class="placeholder col-6"></h4>
          <h3 class="select-control placeholder w-100"></h3>
        </div>
      </div>
    </div>
  </div>
  <!-- Loading -->

</div>
<!-- Container -->

<?php include_once "common/footer.php"; ?>

<script>
  let invalidChars = [
    "-",
    "+",
    "e",
  ];

  let customerType = "";

  $(document).ready(async () => {
    await generate();
    await getProductDetails().then(() => {
      $('.loading').addClass('d-none');
      $('.unload').removeClass('d-none');
      slick();
      viewMoreButton();
    });
    await getReviews('all', location.hash.substr(6) ? location.hash.substr(6) : 1, 'all');
    await getPagination("all");
    await getRecommendedProducts().then(() => $('#recommendedLoading').remove());
  });

  $(document).on('click', '.select', function() {
    $('.select').removeClass('active');
    $('.select').html('Select Store');

    $(this).addClass('active');
    $(this).html('Selected Store');

    $('.store').removeClass('active')
    $(this).parent('div').parent('.store').addClass('active');

    $('.store select').attr('disabled', true);
    $(`#${$(this).attr('data-select-id')}`).attr('disabled', false);
    getDiscountedPrice($(`#${$(this).attr('data-select-id')}`).val(), $(`#${$(this).attr('data-select-id')}`).attr('data-seller-id'), this);
    checkStore();
    $('#quantity').val('1');
  });

  $(document).on('click', '#add', function(e) {
    $('#quantity').val(parseInt($('#quantity').val()) + 1);

    checkQuantity(e, $('#quantity'));
  });

  $(document).on('click', '#sub', function(e) {
    $('#quantity').val(parseInt($('#quantity').val()) - 1);

    checkQuantity(e, $('#quantity'));
  });

  $(document).on('keydown', "#quantity", function(e) {
    if (invalidChars.includes(e.key)) {
      e.preventDefault();
    }
  });

  $(document).on('keyup', "#quantity", function(e) {
    checkQuantity(e, this);
  });

  $(document).on('change', '.store.active select', function() {
    getDiscountedPrice(this.value, $(this).attr('data-seller-id'), $(this).attr('data-product-price'), this)
  });

  $(document).on('click', 'button[name="cart"]', () => {
    checkStore();

    if (checkStore()) return addToCart('add');
  });

  $(document).on('click', 'button[name="buy"]', () => {
    $('#store').removeClass('active');
    checkStore();

    if (checkStore()) return addToCart('buy');
  });

  $(document).on('click', 'button[name="wish"]', () => {
    $.ajax({
      url: 'ajax_products.php?action=wish&t=' + new Date().getTime(),
      method: 'POST',
      dataType: 'json',
      data: {
        product_id: <?php echo $_GET['product_id'] ?? 0 ?>,
      },
      success: response => {
        bootbox.alert(response.message);
      }
    })
  });

  $('.filter').click(function() {
    $('.filter').removeClass('active');
    $(this).addClass('active');
    $('#review .card-body>:not(:first-child)').remove();
    $('#review .card-body').append(`
    <div class="text-center my-5 p-5">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    `);
    getReviews($(this).attr('data-value'), 1, $('#filterStore').val());
    getPagination(1);
  });

  $('#filterStore').on('change', function() {
    $('#review .card-body>:not(:first-child)').remove();
    $('#review .card-body').append(`
    <div class="text-center my-5 p-5">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    `);
    getReviews($('.filter.active').attr('data-value'), 1, $(this).val());
    getPagination(1);
  });

  $(window).on('hashchange', function(e) {
    generatePagination();
  });

  $(document).on('click', '#showMore', function() {
    $('#description .card-body').toggleClass('active');
    if ($(this).parent().hasClass('active')) {
      $(this).html('Show Less');
    } else {
      $(this).html('Show More');
    }
  });

  $(document).on('click', '[name="reviewImage"]', function(e) {
    $('[name="reviewImage"]').not(this).removeClass('border border-dark show');
    $(this).toggleClass('border border-dark show');
    $('[name="reviewPreview"]').remove();
    if ($(this).hasClass('show')) {
      $(`<img name="reviewPreview" class="img-fluid d-block mt-3 border border-3 border-secondary rounded rounded-5" src="${$(this).attr('src')}" style="max-height: 500px">`).insertBefore($(this).siblings('hr'));
    }
  });

  $(window).scroll(async function() {
    if ($(window).scrollTop() >= $(document).height() - $(window).height()) {
      if ($('#loadRecommended').children().length === 0) return;
      // $(this).addClass('d-none');
      $('#recommended>.card>.card-body>.row').append(
        `<div id="recommendedLoading" class="text-center my-5 p-5">
          <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>`
      );
      await getRecommendedProducts().then(() => $('#recommendedLoading').remove());
    }
  });

  const generate = () => {
    return new Promise(resolve => {
      $.ajax({
        url: 'ajax_review.php?action=generate&product_id=<?php echo $_GET['product_id'] ?? 0 ?>&t=' + new Date().getTime()
      }).done(() => {
        resolve();
      })
    })

  };

  const viewMoreButton = () => {
    if ($('#description .card-body').outerHeight() === 500) {
      $('#description .card-body').append(`<button id="showMore" class="btn btn-dark rounded-0 position-absolute bottom-0 start-50 translate-middle-x px-5 py-2 mb-3">Show More</button>`);
    }
  }

  const generatePagination = () => {
    $('#review .card-body>:not(:first-child)').remove();
    $('#review .card-body').append(`
    <div class="text-center my-5 p-5">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    `);
    getReviews($('.filter.active').attr('data-value'), location.hash.substr(6), $('#filterStore').val());
  }

  const addToCart = type => {
    $.ajax({
      url: 'ajax_products.php?action=cart&t=' + new Date().getTime(),
      method: 'POST',
      dataType: 'json',
      data: {
        product_id: $('.store.active input[name="product_id"]').val(),
        seller_id: $('.store.active input[name="seller_id"]').val(),
        branch_id: $('.store.active input[name="branch_id"]').val(),
        discount_id: $('.store.active select option:selected').val(),
        quantity: $('#quantity').val(),
        type,
        customerType: customerType
      },
      success: response => {
        if (type === 'buy') return location.replace(`checkout.php?checkout_cart=${response.message}`);
        bootbox.alert(response.message);
      }
    })
  }

  const getDiscountedPrice = (id, seller_id, that) => {
    $.ajax({
      url: 'ajax_products.php?action=getDiscountedPrice&product_id=' + <?php echo $_GET['product_id'] ?? 0 ?> + '&t=' + new Date().getTime(),
      method: 'POST',
      dataType: 'json',
      data: {
        id,
        seller_id
      },
      success: response => {
        if ($('#productPrice').siblings('p').length === 0) {
          if ($('#details').children().children().length === 3) {
            $(`<div class="col mt-3">
              <strong>Available: </strong>
              <span id="quantityValue"></span>
            </div>`).insertAfter($('#productPrice').parent());

            $(`<div class="col mt-3">
                <div class="input-group border border-dark mt-3 quantity">
                  <button id="sub" class="btn btn-outline-secondary border-0 rounded-0 bg-danger text-white"><i class="fas fa-minus fa-sm"></i></button>
                  <input id="quantity" class="text-center border-0 " type="number" min="1" max="50" step="1" value="1">
                  <button id="add" class="btn btn-outline-secondary border-0 rounded-0 bg-danger text-white"><i class="fas fa-plus fa-sm"></i></button>
                </div>
              </div>`).insertAfter($('#quantityValue').parent());
          }
          if (response.comingsoon) {
            $(`<p class="mt-4 mb-1 fw-bolder"><span id="origPrice"></span> <span id="discountedPrice" class="text-danger"></span></p>
              <p class="text-danger small fst-italic fw-bolder" id="exclusive"></p>`).insertAfter('#productPrice');
          }
        }
        customerType = response.exclusive ?? "all";
        if (response.exclusive === "landbank") {
          $('#exclusive').html('This Promo is exclusive to Landbank Members');
          $('#productPrice').html(response.origPrice);
          $('#origPrice').html(response.price);
          $('#origPrice').removeClass('text-decoration-line-through');
        } else if (response.exclusive === "4gives") {
          $('#exclusive').html('This Promo is exclusive to 4Gives Members');
          $('#productPrice').html(response.origPrice);
          $('#origPrice').html(response.price);
          $('#origPrice').removeClass('text-decoration-line-through');
        } else {
          if (parseInt(response.comingsoon) === 0) {
            $('#exclusive').html('<span>From: </span><span id="from"></span> To: <span id="to"></span>');
            $('#from').html(response.from);
            $('#productPrice').html(response.price);
            $('#origPrice').html(response.origPrice);
            $('#origPrice').addClass('text-decoration-line-through');
          } else if (parseInt(response.comingsoon) === 1) {
            $('#exclusive').html('<span>Start From: </span><span id="from"></span> To: <span id="to"></span>');
            $('#from').html(response.from);
            $('#productPrice').html(response.origPrice);
            $('#origPrice').html(response.price);
            $('#origPrice').removeClass('text-decoration-line-through');
          } else {
            $('#productPrice').nextAll('p').remove();
            $('#productPrice').html(response.origPrice);
          }
        }
        $('#discountedPrice').html(response.discount);
        $('#quantity').attr('max', $(that).attr('data-quantity'));
        $('#quantityValue').html($(that).attr('data-quantity'))
        $('#to').html(response.to);
      }

    })
  }

  const getProductDetails = () => {
    return new Promise(resolve => {
      $.ajax({
        url: `ajax_products.php?action=getProducts&product_id=<?php echo $_GET['product_id'] ?? 0 ?>&store_id=<?php echo $_GET['store_id'] ?? 0 ?>&t=${new Date().getTime()}`,
        dataType: 'json',
        success: response => {
          if (response.type === "error") return location.replace('home.php');
          if (response[0]?.type === "nostore") {
            $('button[name="buy"]').css('display', 'none');
            $('button[name="cart"]').css('display', 'none');
          }
          $('#productName').html(response.name);
          $('#productModel').html(response.model);
          $('#productPrice').html(response.price);
          $('#origPrice').html(response.price);
          $('#description .card-body').html(response.description);
          $('#descriptionTitle').html(response.name);
          $('#reviewTitle').html(response.name);

          response.image.map((res, key) => {
            $('#productImageDisplay').append(`
          <div>
            <img class="mx-auto img-fluid rounded-0" src="${res}" alt="${response.name}-${key+1}" style="max-height: 416px;">
          </div>`);

            $('#productImageNav').append(`
          <div class="mx-1">
            <img class="img-thumbnail" src="${res}" alt="${response.name}-${key+1}" style="max-height: 76px;">
          </div>
          `);
          });

          response.store.map((store, key) => {
            $('#store').append(`
          <div class="store pb-2">
            <div class="mx-2 pt-2">
            <h6>Store Name</h6>
              <div class="d-flex">
                <div class="flex-grow-1">
                  <div class="d-flex" style="height: fit-content !important">
                    <img class="img-thumbnail" src="${store.details.image}" alt="${store.details.shop_name}">
                    <div class="ms-2 d-flex flex-column justify-content-start py-1">
                      <span style="font-weight: 500">${store.details.shop_name}</span>
                      <a href="message.php?Y2F0X2lk=${store.details.seller_id}&prdval=${store.details.product_id}&branch_id=${store.details.branch_id}" target="_blank" class="btn btn-sm text-white rounded-0 align-self-start mt-auto mb-2" style="background: #4B6ED6;">
                        <i class="fal fa-comments-alt"></i> Chat Now
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="mx-2 pt-2">
              <div id="discount${key}">
              </div>
            </div>
            ${store.freebies ? `<div class="mx-2 pt-2">
              <h6>Freebies</h6>
              <p>${store.freebies}</p>
            </div>`:''}
            <div class="mx-2 pt-2">
              <button data-quantity="${store.details.quantity}" data-select-id="promo${key}" class="btn btn-outline-dark rounded-0 select w-100">Select Store</button>
            </div>
            <input type="hidden" name="product_id" value="${store.details.product_id}">
            <input type="hidden" name="seller_id" value="${store.details.seller_id}">
            <input type="hidden" name="branch_id" value="${store.details.branch_id}">
          </div>
          <hr>
          `);
            if (store.discount) {
              $(`#discount${key}`).append(`
              <h6>Promo/Discount</h6>
              <select id="promo${key}" data-seller-id="${store.details.seller_id}" data-product-price="${response.price}" class="form-select" aria-label="Promo/Discount" disabled></select>
              `);
              store.discount.map(discount => {
                $(`#promo${key}`).append(`<option value="${discount.id}">${discount.name}</option>`);
              })
            }
          })
          const params = new Proxy(new URLSearchParams(window.location.search), {
            get: (searchParams, prop) => searchParams.get(prop),
          });
          let value = params.store_id;

          if (value != null) {
            $('.store').addClass('active');
            $('.store .select').addClass('active');
            $('.store .select').html('Selected Store');
            $('.store select').attr('disabled', false);
            $('#quantity').attr('max', $('.select').attr('data-quantity'));
            getDiscountedPrice($('.store select').val(), $('.store select').attr('data-seller-id'), $('.store .select'));
          }
        }
      }).done(() => {
        resolve();
      })

      if ($('.select').hasClass('active')) {
        $('.select.active').html('Selected Store');
      }
    })
  };

  const getReviews = (rating, page, store) => {
    return new Promise(resolve => {
      $.ajax({
        url: `ajax_products.php?action=review&product_id=<?php echo $_GET['product_id'] ?? 0 ?>&rating=${rating}&page=${page}&store=${store}&t=${ new Date().getTime()}`,
        dataType: 'json',
        success: response => {
          response.reviews?.list?.map(res => {
            let image = "";
            res.image?.map(name => {
              image += `<img name="reviewImage" src="${name}" class="img-thumbnail mx-1" style="cursor: pointer; object-fit: contain; height: 70px; width: 70px">`;
            });

            $('#review .card-body').append(`
            <div class="mt-3">
              ${res.branch_name ? `<span class="float-end">Sold by <a class="text-decoration-none fw-bolder" href="product_store.php?Y2F0X2lk=${res.seller_id}" target="_blank"><img class="img-thumbnail" src="${res.branch_logo}" style="width: 40px; height: 40px;"> ${res.branch_name}</a></span>`: ''}
              <h6 class="my-0">${res.author}</h6>
              <div class="my-2">
               ${res.rating}
              </div>
              <p class="text-muted">${res.date_added}</p>
              <p class="default">${res.text}</p>
              ${image}
              <hr>
            </div>
            `);
          });

          if ($('#filterStore').children().length === 1) {
            response.reviews?.store?.map(res => {
              $('#filterStore').append(`
            <option value="${res.seller_id}">${res.shop_name}</option>
          `);
            });
          }

          $('#ratingCount :nth-child(1) .progress-bar').css('width', response.average.five + '%').attr('aria-valuenow', response.average.five);
          $('#ratingCount :nth-child(1) span').html(response.rating.five);
          $('#ratingCount :nth-child(2) .progress-bar').css('width', response.average.four + '%').attr('aria-valuenow', response.average.four);
          $('#ratingCount :nth-child(2) span').html(response.rating.four);
          $('#ratingCount :nth-child(3) .progress-bar').css('width', response.average.three + '%').attr('aria-valuenow', response.average.three);
          $('#ratingCount :nth-child(3) span').html(response.rating.three);
          $('#ratingCount :nth-child(4) .progress-bar').css('width', response.average.two + '%').attr('aria-valuenow', response.average.two);
          $('#ratingCount :nth-child(4) span').html(response.rating.two);
          $('#ratingCount :nth-child(5) .progress-bar').css('width', response.average.one + '%').attr('aria-valuenow', response.average.one);
          $('#ratingCount :nth-child(5) span').html(response.rating.one);
          $('#allRatingCount').html(response.rating.all + ' ratings');
          $('#allRating').html(response.average.all);

          let rating = response.rating.allRating / 5 * 100;

          switch (true) {
            case (rating <= 20):
              $('#grad1 :nth-child(1)').attr('offset', ((rating) / 20) * 100 + '%');
              $('#grad2 :nth-child(1)').attr('offset', '0%');
              $('#grad3 :nth-child(1)').attr('offset', '0%');
              $('#grad4 :nth-child(1)').attr('offset', '0%');
              $('#grad5 :nth-child(1)').attr('offset', '0%');
              break;
            case (rating <= 40):
              $('#grad1 :nth-child(1)').attr('offset', '100%');
              $('#grad2 :nth-child(1)').attr('offset', ((rating - 20) / 20) * 100 + '%');
              $('#grad3 :nth-child(1)').attr('offset', '0%');
              $('#grad4 :nth-child(1)').attr('offset', '0%');
              $('#grad5 :nth-child(1)').attr('offset', '0%');
              break;
            case (rating <= 60):
              $('#grad1 :nth-child(1)').attr('offset', '100%');
              $('#grad2 :nth-child(1)').attr('offset', '100%');
              $('#grad3 :nth-child(1)').attr('offset', ((rating - 40) / 20) * 100 + '%');
              $('#grad4 :nth-child(1)').attr('offset', '0%');
              $('#grad5 :nth-child(1)').attr('offset', '0%');
              break;
            case (rating <= 80):
              $('#grad1 :nth-child(1)').attr('offset', '100%');
              $('#grad2 :nth-child(1)').attr('offset', '100%');
              $('#grad3 :nth-child(1)').attr('offset', '100%');
              $('#grad4 :nth-child(1)').attr('offset', ((rating - 60) / 20) * 100 + '%');
              $('#grad5 :nth-child(1)').attr('offset', '0%');
              break;
            case (rating <= 100):
              $('#grad1 :nth-child(1)').attr('offset', '100%');
              $('#grad2 :nth-child(1)').attr('offset', '100%');
              $('#grad3 :nth-child(1)').attr('offset', '100%');
              $('#grad4 :nth-child(1)').attr('offset', '100%');
              $('#grad5 :nth-child(1)').attr('offset', ((rating - 80) / 20) * 100 + '%');
              break;
            default:
              break;
          }
        }
      }).done(() => {
        $('#review .card-body .spinner-border').parent().remove();
        resolve();
      })
    })
  }

  const getPagination = page => {
    return new Promise(resolve => {
      if (page === "all") page = location.hash.substr(6) ? location.hash.substr(6) : 1;
      $.ajax({
        url: `ajax_products.php?action=getPagination&product_id=<?php echo $_GET['product_id'] ?? 0 ?>&rating=${$('.filter.active').attr('data-value')}&t=${ new Date().getTime() }`,
        dataType: 'json',
        success: response => {
          if (response.bool) {
            $('#pagination').pagination({
              items: response.count,
              itemsOnPage: 5,
              edges: 1,
              currentPage: page,
              prevText: '<i class="fas fa-angle-left"></i>',
              nextText: '<i class="fas fa-angle-right"></i>',
              cssStyle: 'light-theme'
            }).removeClass('d-none')
          } else {
            $('#pagination').addClass('d-none');
          }
        }
      }).done(() => {
        resolve();
      })
    })
  }

  const getRecommendedProducts = () => {
    return new Promise(resolve => {
      $.ajax({
        url: `ajax_products.php?action=getRecommendedProducts&t=${new Date().getTime()}`,
        method: 'POST',
        data: {
          product_id: "<?php echo $_GET['product_id'] ?>",
          page: $('#loadMore').attr('data-value'),
          button_id: $('#loadMore').attr('data-id')
        },
        dataType: 'json',
        success: response => {
          if (parseInt($('#loadMore').attr('data-value')) >= response.last) return $('#loadRecommended').remove();

          response.products?.map(res => {
            $('#recommended>.card>.card-body>.row').append(`
              <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
                <div class="card position-relative">
                  ${parseInt(res.promo) === 1 ? `<div class="ribbon position-absolute">
                    <span class="ribbon3">
                      ${res.price.off}
                    </span>
                  </div>` : ''}
                  <a class="position-absolute w-100 h-100" href="${res.href}" data-toggle="tooltip" data-placement="bottom" title="${res.name}"></a>
                  <div class="card-body p-0 m-auto" style="max-width: 200px; max-height: 200px;">
                    <img src="${res.image}" alt="${res.name}" class="img-fluid d-block w-100 h-100 m-auto" style="object-fit: contain; max-width: inherit; max-height: inherit">
                  </div>
                  <div class="card-footer p-2 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <div class="overflow-hidden" style="height: 18px">
                          <span class="d-inline-block w-100" style="font-size: 12px;">
                            ${res.name}
                          </span>
                        </div>
                        <div class="text-danger overflow-hidden my-1" style="height: 18px">
                          <span class="fw-bold">${parseInt(res.promo) === 1 ? res.price.new: res.price}</span>
                        </div>
                        <span class="text-muted small text-decoration-line-through">${parseInt(res.promo) === 1 ? res.price.old : '<br>'}</span>
                      </div>
                      <div class="flex-grow-1" style="width: 50px; height: 50px">
                        <img style="object-fit: contain; width: inherit; height: inherit" class="ms-auto d-block" src="${res.branch_logo}" alt="">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              `);
          });
          $('#loadRecommended').html(response.button);
        }
      }).done(res => {
        resolve();
      })
    })
  }

  const slick = () => {
    $('.slider-for').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: false,
      fade: true,
      autoplay: true,
      autoplaySpeed: 5000
    });
    $('.slider-nav').slick({
      slidesToShow: 4,
      slidesToScroll: 4,
      focusOnSelect: true,
      infinite: false,
      arrows: true,
    });

    $('.slider-nav').on('mouseenter', '.slick-slide', function(e) {
      var $currTarget = $(e.currentTarget),
        index = $currTarget.data('slick-index'),
        slickObj = $('.slider-for').slick('getSlick');

      slickObj.slickGoTo(index);
    });
  }

  const checkQuantity = (e, that) => {
    if (parseInt($(that).val()) <= 1 || $(that).val() == '') {
      $(that).val('1');
    }

    if (parseInt($(that).val()) >= parseInt($(that).attr('max'))) {
      $(that).val(parseInt($(that).attr('max')));
    }
  }

  const checkStore = () => {
    if (!$('#store .store').hasClass('active')) {
      $('#store').addClass('border border-3 border-danger');
      $('#store').addClass('active');
      if ($('#store>h5.text-danger').length === 0) {
        $('#store').prepend(`<h5 class="text-center text-danger">Please select store first</h5>`);
        return false;
      };
    } else {
      $('#store').removeClass('border border-3 border-danger');
      $('#store>h5').remove();
      return true;
    }
    setTimeout(() => {
      $('#store').removeClass('active');
    }, 2000);
  }
</script>