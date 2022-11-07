<?php
include_once "common/headertest.php";
?>

<style>
  .fas.fa-star {
    color: gray;
    cursor: pointer;
  }

  .fas.fa-star.active {
    color: #FAC917;
  }
</style>

<div class="container bg-white py-3" style="margin-top: 135px">
  <div class="card">
    <div class="card-header">
      <span style="font-size: 26px">Write a Review</span>
    </div>
    <div class="card-body">
      <div class="row">
        <!-- Product Review -->
        <div class="col-12 col-md-8">
          <div class="d-flex align-items-center">
            <img id="productImage" class="img-thumbnail" src="https://via.placeholder.com/70" style="max-width: 70px; max-height: 70px">
            <div class="ms-2 my-1 w-100">
              <h6 id="productName"></h6>
              <div class="mt-auto row align-items-center">
                <div class="col-12 col-xl-7">
                  <div class="star d-inline-block">
                    <i class="fas fa-star fa-2x active"></i>
                    <i class="fas fa-star fa-2x active"></i>
                    <i class="fas fa-star fa-2x active"></i>
                    <i class="fas fa-star fa-2x active"></i>
                    <i class="fas fa-star fa-2x active"></i>
                  </div>
                  <span class="ms-2 col-sm my-0" id="starText" style="white-space: nowrap">Very Satisfied</span>
                </div>
                <div class="col-12 col-xl-5">
                  <input id="reviewFile" class="form-control shadow-none filestyle mt-md-2" type="file" accept=".jpg, .jpeg, .png" multiple>
                </div>
              </div>
            </div>
          </div>
          <div class="form-floating mt-2">
            <textarea id="reviewText" class="form-control shadow-none" placeholder="Write your product review here..." style="resize:none; height: 150px"></textarea>
            <label for="reviewText">Product Review</label>
          </div>
        </div>
        <!-- Product Review -->

        <!-- Store -->
        <div class="col-12 col-md-4 d-flex justify-content-between flex-column">
          <div>
            <p class="mb-1">Sold by</p>
            <a id="storeLink" class="d-block bg-light text-decoration-none text-reset" target="_blank">
              <img id="sellerImage" class="img-thumbnail" src="https://via.placeholder.com/70" style="max-width: 70px; max-height: 70px">
              <span id="sellerName" class="h6"></span>
            </a>
          </div>
          <div class="d-grid mt-2">
            <button id="reviewButton" class="btn btn-danger py-3 rounded-0 shadow-none">Submit Review</button>
          </div>
        </div>
        <!-- Store -->
      </div>
    </div>
  </div>
</div>

<?php include_once "common/footer.php"; ?>

<script>
  $(document).ready(() => {
    getDetails();
  })
  $('.star i').click(function(e) {
    $('.star i').removeClass('active');

    $(this).toggleClass('active');
    $(this).prevAll().toggleClass('active');

    checkStarText($(this).parent().children('.active').length);
  });

  $('#reviewButton').click(() => {
    const countFile = $('#reviewFile').get(0).files.length;

    if (countFile > 5) {
      bootbox.alert('Please upload no morethan 5 photos');
      $("#reviewFile").val(null);
    } else {
      submitReview();
    }
  });

  const checkStarText = count => {
    let text;

    if (count === 1) text = "Very Unsatisfied";
    else if (count === 2) text = "Unsatisfied";
    else if (count === 3) text = "Neutral";
    else if (count === 4) text = "Satisfied";
    else if (count === 5) text = "Very Satisfied";

    $('#starText').html(text);
  }

  const getDetails = () => {
    $.ajax({
      url: 'ajax_review.php?action=get&product_id=<?php echo $_GET['product_id'] ?? 0; ?>&order_id=<?php echo $_GET['order_id'] ?? 0; ?>',
      dataType: 'json',
      success: response => {
        if (response.exist) {
          $('#reviewText').val(response.exist.text);

          $('.star i').removeClass('active');
          $(`.star :nth-child(${response.exist.rating})`).addClass('active');
          $(`.star :nth-child(${response.exist.rating})`).prevAll().addClass('active');

          checkStarText(parseInt(response.exist.rating));
        }
        if (!response.type) {
          $('#sellerImage').attr({
            'src': response.branch_image,
            'alt': response.branch_name
          });

          $('#sellerName').html(response.branch_name);
          $('#productImage').attr({
            'src': response.product_image,
            'alt': response.product_name
          });

          $('#productName').html(response.product_name);

          $('#storeLink').attr('href', `product_store.php?Y2F0X2lk=${response.seller_id}`)
        } else {
          location.replace('order_history.php');
        }
      }
    })
  }

  const submitReview = () => {
    var myFormData = new FormData();
    myFormData.append('rating', $('.star i.active').length);
    myFormData.append('review', $('#reviewText').val());

    $($("#reviewFile")[0].files).each(function(i, file) {
      myFormData.append('image[]', file);
    });

    $.ajax({
      url: 'ajax_review.php?action=submit&product_id=<?php echo $_GET['product_id'] ?? 0; ?>&order_id=<?php echo $_GET['order_id'] ?? 0; ?>',
      method: 'POST',
      dataType: 'json',
      contentType: false,
      processData: false,
      data: myFormData,
      success: response => {
        if (response.type === 'success') {
          bootbox.alert(response.message, () => {
            window.history.back();
          });
        } else {
          bootbox.alert(response.message);
        }
      }
    });
  }
</script>