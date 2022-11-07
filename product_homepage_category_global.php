<style type="text/css">
  .dropdown-submenu {
    position: relative;
}
  .dropdown-submenu>.dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: -6px;
    margin-left: -1px;
    -webkit-border-radius: 0 6px 6px 6px;
    -moz-border-radius: 0 6px 6px;
    border-radius: 0 6px 6px 6px;
}

.dropdown-submenu:hover>.dropdown-menu {
    display: block;
}
.dropdown-toggle:hover>{
    display: block;
}

.dropdown-submenu:hover>a:after {
    display: block;
    content: " ";
    float: right;
    width: 0;
    height: 0;
    border-color: transparent;
    border-style: solid;
    border-width: 5px 0 5px 5px;
    border-left-color: #ccc;
    margin-top: 5px;
    margin-right: -10px;
}

.dropdown-submenu:hover>a:after {
    border-left-color: #fff;
}

.dropdown-submenu.pull-left {
    float: none;
}

.dropdown-submenu.pull-left>.dropdown-menu {
    left: -100%;
    margin-left: 10px;
    -webkit-border-radius: 6px 0 6px 6px;
    -moz-border-radius: 6px 0 6px 6px;
    border-radius: 6px 0 6px 6px;
}
</style>
<?php 
  $sr_cat=$model_home->getCategories_global_all(0); 
 /* echo '<pre>';
  print_r($sr_cat);*/
?>

<div class="dropdown">
  <a class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Categories</button>
  <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
    <?php foreach ($sr_cat as $category) : 
      $b64catid = base64_encode($category['cat_id']);
      $b64name = base64_encode('cat_id');?>
      <?php if(count($category['child2d'])!=0){ ?>
        <!--  sub category second -->
        <li class="dropdown-submenu">
          <a  class="dropdown-item" tabindex="-1" href="#"><small><?php echo $category['cat_name'];?></small></a>
          <ul class="dropdown-menu">
            <?php foreach ($category['child2d'] as $c_second) : ?>
              <?php if(count($c_second['child3rd'])!=0){ ?>
                <!--  sub category 2nd -->
                <li class="dropdown-submenu">                          
                  <a class="dropdown-item" href="#"><small><?php echo $c_second['cat_name'];?></small></a>
                  <ul class="dropdown-menu">
                    <?php foreach ($c_second['child3rd'] as $c_3rd) : ?>
                       <!--  sub category c_3rd -->
                        <li class="dropdown-item"><a href="#"><small><?php echo $c_3rd['cat_name'];?></small></a></a></li>
                    <?php endforeach; ?><!--  end  c_3rd-->
                  </ul>
                </li>
              <?php }else{ ?>
              <!--  no second sub category  -->
                <li class="dropdown-item"><a href="#"><small><?php echo $c_second['cat_name'];?></small></a></li>
              <?php  } ?>
            <?php endforeach; ?><!--  end  c_second-->
          </ul>
        </li>
      <?php }else{ ?>
      <!--  no sub category  -->
        <li class="dropdown-item"><a href="product_category_global.php?<?php echo $b64name;?>=<?php echo $b64catid;?>"><small><?php echo $category['cat_name'];?></small></a></li>
      <?php  } ?>
    <?php endforeach; ?><!--  end  category-->
  </ul>
</div>


