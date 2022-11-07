<?php
include "common/headertest.php";
require_once 'model/home_new.php';
require_once 'model/customer_activity.php';

$home_new_mod = new home_new();
$custid = isset($_SESSION['user_login']) ? $_SESSION['user_login'] : 0;
$GetCutomerType = $home_new_mod->GetCutomerType($custid);

$model = new CustomerActivity();
$model->insertactivity($custid, "Search Page");

?>
<style>
  body {
    overflow-x: hidden;
  }

  @media only screen and (max-width: 2560px) {
    .flCardBody {
      height: 275px !important;
    }

    .dlCardimg {
      height: 277px !important;
      width: 321px !important;
    }

    .cardFS {
      padding-bottom: 0px !important;
    }
  }

  @media only screen and (max-width: 1400px) {
    .flCardBody {
      height: 239px !important;
    }

    .cardFS {
      padding-bottom: 6px !important;
    }
  }

  .swiper {
    width: 100%;
    height: 100%;
  }

  .swiper-slide img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .card>a:hover {
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

<div class="container bg-light">
  <div style="margin-top: 203px;">
    <!-- Carousel -->
    <div class="row">
      <div class="col-sm-9 p-1">
        <!-- Size 830X320 -->
        <div style="margin-top: -79px;"><?php include "homepageMainCarousel.php"; ?></div>
      </div>
      <div class="col-sm-3 p-1 justify-content-center" style="margin-top: -79px;padding: 0;">
        <?php $GetFeaturedBrand = $home_new_mod->GetFeaturedPromo(3, 330, 390); ?>
        <?php if ($GetCutomerType == "2") { ?>
          <!--  for 4gives Customers -->
          <?php if ($GetFeaturedBrand[0]['exclusive_for'] == "1") { ?>
            <!--  exclusive_for lanbank Customers -->
            <a data-bs-toggle="modal" data-bs-target="#LAnbankUserOnly">
            <?php } else { ?>
              <a href="product_category_new.php?promo_id=<?php echo $GetFeaturedBrand[0]['id']; ?>&t=<?php echo  uniqid(); ?>">
              <?php } ?>

            <?php } else if ($GetCutomerType == "1") { ?>
              <!--  for lanbank Customers -->
              <?php if ($GetFeaturedBrand[0]['exclusive_for'] == "2") { ?>
                <!--  exclusive_for lanbank Customers -->
                <a data-bs-toggle="modal" data-bs-target="#FgivesUserOnly">
                <?php } else { ?>
                  <a href="product_category_new.php?promo_id=<?php echo $GetFeaturedBrand[0]['id']; ?>&t=<?php echo  uniqid(); ?>">
                  <?php } ?>
                <?php } else { ?>
                  <!-- for regular Customers -->
                  <?php if ($GetFeaturedBrand[0]['exclusive_for'] == "1") { ?>
                    <!--  exclusive_for lanbank Customers -->
                    <a data-bs-toggle="modal" data-bs-target="#LAnbankUserOnly">
                    <?php } else if ($GetFeaturedBrand[0]['exclusive_for'] == "2") { ?>
                      <!--  exclusive_for lanbank Customers -->
                      <a data-bs-toggle="modal" data-bs-target="#FgivesUserOnly">
                      <?php } else { ?>
                        <a href="product_category_new.php?promo_id=<?php echo $GetFeaturedBrand[0]['id']; ?>&t=<?php echo  uniqid(); ?>">
                        <?php } ?>
                      <?php } ?>
                      <img src="<?php echo $GetFeaturedBrand[0]['thumb']; ?>" class="img-fluid" style="border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;">
                        </a>
      </div>
    </div>
    <!-- Carousel -->
  </div>
  <div id="products">
    <div class="unload d-none">
      <!-- Search Products -->
      <div id="searchProducts">
        <em class="ms-1 my-1">Search result for: <span class="fw-bold"><?php echo $_GET['searchvalue_h'] ?? "" ?></span></em>
        <div class="container-fluid">
          <div class="row">

          </div>
          <div class="d-flex justify-content-end py-3" id="pagination"></div>
        </div>
      </div>
      <!-- Search Products -->
      <!-- Recommended Products -->
      <div id="recommendedProducts">
        <div class="card">
          <div class="card-header bg-light py-3">
            <h5 class="m-0">Recommended Products</h5>
          </div>
          <div class="card-body">
            <div class="row">

            </div>
          </div>
        </div>
      </div>
      <!-- Recommended Products -->
    </div>
    <div class="loading placeholder-glow">
      <div class="placeholder ms-1" style="width: 180px; height: 19px"></div>
      <div class="container-fluid">
        <div class="row">
          <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
            <div class="card placeholder-glow">
              <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
              <div class="card-footer p-2 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="w-100">
                    <div class="placeholder" style="width: 75%; height: 10px"></div>
                    <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                  </div>
                  <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
            <div class="card">
              <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
              <div class="card-footer p-2 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="w-100">
                    <div class="placeholder" style="width: 75%; height: 10px"></div>
                    <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                  </div>
                  <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
            <div class="card placeholder-glow">
              <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
              <div class="card-footer p-2 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="w-100">
                    <div class="placeholder" style="width: 75%; height: 10px"></div>
                    <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                  </div>
                  <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
            <div class="card placeholder-glow">
              <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
              <div class="card-footer p-2 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="w-100">
                    <div class="placeholder" style="width: 75%; height: 10px"></div>
                    <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                  </div>
                  <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
            <div class="card placeholder-glow">
              <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
              <div class="card-footer p-2 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="w-100">
                    <div class="placeholder" style="width: 75%; height: 10px"></div>
                    <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                  </div>
                  <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
            <div class="card placeholder-glow">
              <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
              <div class="card-footer p-2 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="w-100">
                    <div class="placeholder" style="width: 75%; height: 10px"></div>
                    <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                  </div>
                  <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
            <div class="card placeholder-glow">
              <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
              <div class="card-footer p-2 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="w-100">
                    <div class="placeholder" style="width: 75%; height: 10px"></div>
                    <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                  </div>
                  <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
            <div class="card placeholder-glow">
              <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
              <div class="card-footer p-2 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="w-100">
                    <div class="placeholder" style="width: 75%; height: 10px"></div>
                    <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                  </div>
                  <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
            <div class="card placeholder-glow">
              <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
              <div class="card-footer p-2 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="w-100">
                    <div class="placeholder" style="width: 75%; height: 10px"></div>
                    <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                  </div>
                  <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
            <div class="card placeholder-glow">
              <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
              <div class="card-footer p-2 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="w-100">
                    <div class="placeholder" style="width: 75%; height: 10px"></div>
                    <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                  </div>
                  <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
            <div class="card placeholder-glow">
              <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
              <div class="card-footer p-2 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="w-100">
                    <div class="placeholder" style="width: 75%; height: 10px"></div>
                    <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                  </div>
                  <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
            <div class="card placeholder-glow">
              <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
              <div class="card-footer p-2 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="w-100">
                    <div class="placeholder" style="width: 75%; height: 10px"></div>
                    <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                  </div>
                  <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
            <div class="card placeholder-glow">
              <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
              <div class="card-footer p-2 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="w-100">
                    <div class="placeholder" style="width: 75%; height: 10px"></div>
                    <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                  </div>
                  <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
            <div class="card">
              <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
              <div class="card-footer p-2 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="w-100">
                    <div class="placeholder" style="width: 75%; height: 10px"></div>
                    <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                  </div>
                  <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
            <div class="card placeholder-glow">
              <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
              <div class="card-footer p-2 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="w-100">
                    <div class="placeholder" style="width: 75%; height: 10px"></div>
                    <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                  </div>
                  <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
            <div class="card placeholder-glow">
              <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
              <div class="card-footer p-2 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="w-100">
                    <div class="placeholder" style="width: 75%; height: 10px"></div>
                    <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                  </div>
                  <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
            <div class="card placeholder-glow">
              <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
              <div class="card-footer p-2 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="w-100">
                    <div class="placeholder" style="width: 75%; height: 10px"></div>
                    <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                  </div>
                  <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
            <div class="card placeholder-glow">
              <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
              <div class="card-footer p-2 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="w-100">
                    <div class="placeholder" style="width: 75%; height: 10px"></div>
                    <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                  </div>
                  <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
            <div class="card placeholder-glow">
              <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
              <div class="card-footer p-2 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="w-100">
                    <div class="placeholder" style="width: 75%; height: 10px"></div>
                    <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                  </div>
                  <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
            <div class="card placeholder-glow">
              <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
              <div class="card-footer p-2 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="w-100">
                    <div class="placeholder" style="width: 75%; height: 10px"></div>
                    <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                  </div>
                  <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
            <div class="card placeholder-glow">
              <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
              <div class="card-footer p-2 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="w-100">
                    <div class="placeholder" style="width: 75%; height: 10px"></div>
                    <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                  </div>
                  <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
            <div class="card placeholder-glow">
              <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
              <div class="card-footer p-2 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="w-100">
                    <div class="placeholder" style="width: 75%; height: 10px"></div>
                    <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                  </div>
                  <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
            <div class="card placeholder-glow">
              <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
              <div class="card-footer p-2 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="w-100">
                    <div class="placeholder" style="width: 75%; height: 10px"></div>
                    <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                  </div>
                  <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
            <div class="card placeholder-glow">
              <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
              <div class="card-footer p-2 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="w-100">
                    <div class="placeholder" style="width: 75%; height: 10px"></div>
                    <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                  </div>
                  <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card mt-5">
        <div class="card-header py-3">
          <div class="m-0 placeholder fs-5">Recommended Products</div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
              <div class="card placeholder-glow">
                <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
                <div class="card-footer p-2 pb-4">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="w-100">
                      <div class="placeholder" style="width: 75%; height: 10px"></div>
                      <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                    </div>
                    <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
              <div class="card">
                <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
                <div class="card-footer p-2 pb-4">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="w-100">
                      <div class="placeholder" style="width: 75%; height: 10px"></div>
                      <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                    </div>
                    <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
              <div class="card placeholder-glow">
                <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
                <div class="card-footer p-2 pb-4">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="w-100">
                      <div class="placeholder" style="width: 75%; height: 10px"></div>
                      <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                    </div>
                    <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
              <div class="card placeholder-glow">
                <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
                <div class="card-footer p-2 pb-4">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="w-100">
                      <div class="placeholder" style="width: 75%; height: 10px"></div>
                      <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                    </div>
                    <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
              <div class="card placeholder-glow">
                <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
                <div class="card-footer p-2 pb-4">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="w-100">
                      <div class="placeholder" style="width: 75%; height: 10px"></div>
                      <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                    </div>
                    <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
              <div class="card placeholder-glow">
                <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
                <div class="card-footer p-2 pb-4">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="w-100">
                      <div class="placeholder" style="width: 75%; height: 10px"></div>
                      <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                    </div>
                    <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
              <div class="card placeholder-glow">
                <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
                <div class="card-footer p-2 pb-4">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="w-100">
                      <div class="placeholder" style="width: 75%; height: 10px"></div>
                      <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                    </div>
                    <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
              <div class="card placeholder-glow">
                <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
                <div class="card-footer p-2 pb-4">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="w-100">
                      <div class="placeholder" style="width: 75%; height: 10px"></div>
                      <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                    </div>
                    <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
              <div class="card placeholder-glow">
                <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
                <div class="card-footer p-2 pb-4">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="w-100">
                      <div class="placeholder" style="width: 75%; height: 10px"></div>
                      <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                    </div>
                    <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
              <div class="card placeholder-glow">
                <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
                <div class="card-footer p-2 pb-4">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="w-100">
                      <div class="placeholder" style="width: 75%; height: 10px"></div>
                      <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                    </div>
                    <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
              <div class="card placeholder-glow">
                <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
                <div class="card-footer p-2 pb-4">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="w-100">
                      <div class="placeholder" style="width: 75%; height: 10px"></div>
                      <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                    </div>
                    <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
              <div class="card placeholder-glow">
                <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
                <div class="card-footer p-2 pb-4">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="w-100">
                      <div class="placeholder" style="width: 75%; height: 10px"></div>
                      <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
                    </div>
                    <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once "common/footer.php"; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.4/jquery.simplePagination.js" integrity="sha512-D8ZYpkcpCShIdi/rxpVjyKIo4+cos46+lUaPOn2RXe8Wl5geuxwmFoP+0Aj6wiZghAphh4LNxnPDiW4B802rjQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.4/simplePagination.css" integrity="sha512-emkhkASXU1wKqnSDVZiYpSKjYEPP8RRG2lgIxDFVI4f/twjijBnDItdaRh7j+VRKFs4YzrAcV17JeFqX+3NVig==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<script>
  $(document).ready(async () => {
    await get('search');
    await get('recommended');
    $('.loading').remove();
    $('.unload').removeClass('d-none');
  });

  $(window).on('hashchange', async () => {
    $('#searchProducts').addClass('placeholder-glow');
    $('#searchProducts em').addClass('d-none');
    $('<div class="placeholder ms-1" style="width: 180px; height: 19px"></div>').insertAfter('#searchProducts em');
    $('#searchProducts .row').html('');
    placeholder('#searchProducts .row', 24);
    $('#pagination').addClass('d-none');
    $(`<div class="d-flex justify-content-end py-3">
        <div class="placeholder" style="width: 380px; height: 30px"></div>
      </div>`).insertAfter('#pagination');
    await get('search').then(() => {
      $('#searchProducts').removeClass('placeholder-glow');
      $('#searchProducts em').removeClass('d-none');
      $('#searchProducts em').next().remove();
      $('#pagination').removeClass('d-none');
      $('#pagination').next().remove();
    });
  });

  const get = type => {
    return new Promise(resolve => {
      const page = location.hash.substr(6) ? location.hash.substr(6) : 1;
      $.ajax({
        url: `ajax_search.php?action=get&searchvalue_h=<?php echo $_GET['searchvalue_h'] ?? "" ?>&t=${new Date().getTime()}`,
        method: 'POST',
        data: {
          page
        },
        dataType: 'json',
        success: response => {
          if (!response.search || response.search.products.length === 0) {
            $('#searchProducts .row').html(`
            <div class="d-flex align-items-center py-5 flex-column text-secondary">
              <i class="fal fa-file-search fa-4x pt-5"></i>
              <h5 class="mt-2">No result found</h5>
            </div>
            `);
          } else if (type === "search") {
            if (response.page) {
              generatePagination(response.count, response.limit);
            }
            $('#searchProducts .row').html('');
            response.search?.products?.map(res => {
              $('#searchProducts .row').append(`
                <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
                  <div class="card position-relative">
                    ${res.promo === 1 ? `<div class="ribbon position-absolute">
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
                            <span class="fw-bold">${res.promo === 1 ? res.price.new : res.price}</span>
                          </div>
                          <span class="text-muted small text-decoration-line-through">${res.promo === 1 ? res.price.old : '<br>'}</span>
                        </div>
                        <div class="flex-grow-1" style="width: 50px; height: 50px">
                          <img style="object-fit: contain; width: inherit; height: inherit" class="ms-auto d-block" src="${res.branch_logo}" alt="">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                `)
            });
          }

          if (type === "recommended") {
            response.recommended?.products?.map(res => {
              $('#recommendedProducts .row').append(`
                <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
                  <div class="card position-relative">
                    ${res.promo === 1 ? `<div class="ribbon position-absolute">
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
                            <span class="fw-bold">${res.promo === 1 ? res.price.new : res.price}</span>
                          </div>
                          <span class="text-muted small text-decoration-line-through">${res.promo === 1 ? res.price.old : '<br>'}</span>
                        </div>
                        <div class="flex-grow-1" style="width: 50px; height: 50px">
                          <img style="object-fit: contain; width: inherit; height: inherit" class="ms-auto d-block" src="${res.branch_logo}" alt="">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                `)
            });
          }
        }
      }).done(() => {
        resolve();
      })
    })
  }

  const generatePagination = (count, limit) => {
    $('#pagination').pagination({
      items: count,
      itemsOnPage: limit,
      edges: 1,
      currentPage: location.hash.substr(6) ? location.hash.substr(6) : 1,
      prevText: '<i class="fas fa-angle-left"></i>',
      nextText: '<i class="fas fa-angle-right"></i>',
      cssStyle: 'light-theme'
    });
  }

  const placeholder = (element, count) => {
    for (let i = 0; i < count; i++) {
      $(element).append(`
      <div class="col-xl-2 col-md-3 col-sm-4 col-12 p-1">
        <div class="card placeholder-glow">
          <div class="card-body placeholder" style="min-width: 200px; min-height: 200px;"></div>
          <div class="card-footer p-2 pb-4">
            <div class="d-flex justify-content-between align-items-center">
              <div class="w-100">
                <div class="placeholder" style="width: 75%; height: 10px"></div>
                <div class="placeholder mt-2" style="width: 50%; height: 20px"></div>
              </div>
              <div class="placeholder" style="min-width: 50px; min-height: 50px"></div>
            </div>
          </div>
        </div>
      </div>
      `);
    }
  }
</script>