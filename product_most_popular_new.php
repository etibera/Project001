<style type="text/css">
  .slider-mp .slick-slide {
    height: auto;
    margin: 2px
    }
@keyframes ldio-60clywj1yi5 {
  0% {
    opacity: 1;
    backface-visibility: hidden;
    transform: translateZ(0) scale(1.5,1.5);
  } 100% {
    opacity: 0;
    backface-visibility: hidden;
    transform: translateZ(0) scale(1,1);
  }
}
.ldio-60clywj1yi5 div > div {
  position: absolute;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background: #3e3852;
  animation: ldio-60clywj1yi5 1s linear infinite;
}.ldio-60clywj1yi5 div:nth-child(1) > div {
  left: 148px;
  top: 88px;
  animation-delay: -0.875s;
}
.ldio-60clywj1yi5 > div:nth-child(1) {
  transform: rotate(0deg);
  transform-origin: 160px 100px;
}.ldio-60clywj1yi5 div:nth-child(2) > div {
  left: 130px;
  top: 130px;
  animation-delay: -0.75s;
}
.ldio-60clywj1yi5 > div:nth-child(2) {
  transform: rotate(45deg);
  transform-origin: 142px 142px;
}.ldio-60clywj1yi5 div:nth-child(3) > div {
  left: 88px;
  top: 148px;
  animation-delay: -0.625s;
}
.ldio-60clywj1yi5 > div:nth-child(3) {
  transform: rotate(90deg);
  transform-origin: 100px 160px;
}.ldio-60clywj1yi5 div:nth-child(4) > div {
  left: 46px;
  top: 130px;
  animation-delay: -0.5s;
}
.ldio-60clywj1yi5 > div:nth-child(4) {
  transform: rotate(135deg);
  transform-origin: 58px 142px;
}.ldio-60clywj1yi5 div:nth-child(5) > div {
  left: 28px;
  top: 88px;
  animation-delay: -0.375s;
}
.ldio-60clywj1yi5 > div:nth-child(5) {
  transform: rotate(180deg);
  transform-origin: 40px 100px;
}.ldio-60clywj1yi5 div:nth-child(6) > div {
  left: 46px;
  top: 46px;
  animation-delay: -0.25s;
}
.ldio-60clywj1yi5 > div:nth-child(6) {
  transform: rotate(225deg);
  transform-origin: 58px 58px;
}.ldio-60clywj1yi5 div:nth-child(7) > div {
  left: 88px;
  top: 28px;
  animation-delay: -0.125s;
}
.ldio-60clywj1yi5 > div:nth-child(7) {
  transform: rotate(270deg);
  transform-origin: 100px 40px;
}.ldio-60clywj1yi5 div:nth-child(8) > div {
  left: 130px;
  top: 46px;
  animation-delay: 0s;
}
.ldio-60clywj1yi5 > div:nth-child(8) {
  transform: rotate(315deg);
  transform-origin: 142px 58px;
}
.loadingio-spinner-spin-rn29k708lb {
  width: 200px;
  height: 200px;
  display: inline-block;
  overflow: hidden;
  background: #ffffff;
}
.ldio-60clywj1yi5 {
  width: 100%;
  height: 100%;
  position: relative;
  transform: translateZ(0) scale(1);
  backface-visibility: hidden;
  transform-origin: 0 0; /* see note above */
}
.ldio-60clywj1yi5 div { box-sizing: content-box; }
/* generated by https://loading.io/ */
#div_pmpn:hover { border: 1px solid #777;}
</style>
 <!--  <div class="loadingio-spinner-spin-rn29k708lb" id="pmploader">
    <div class="ldio-60clywj1yi5" >
        <div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div>
    </div>
  </div> -->
  <?php $most_popular=$home_new_mod->most_popular_new(9);?>  
  <section class="center-mp slider-mp">
    <?php foreach ($most_popular as $mp) { ?> 
    <?php $mpimg="";
      if($mp['type']==0){
        $mpimg=$mp['thumb'] ;
      }else{
        $mpimg=$mp['image'] ;
      }
    ?> 
    <div class="card-pr" style=" margin: auto;text-align: center;font-family: arial;font-size: 6px;box-shadow: 0 0.0625rem 0.125rem 0 rgba(0,0,0,.6);border-radius: 10px;background: #fff;" id="div_prn">
      <div class="">
        <div class="image-container-mp">
          <div data-toggle="tooltip" title="Click for more details" class="image">
            <a  href="<?php echo $mp['href'];?>">
              <?php if($mpimg!=""){ ?>
                  <img src="<?php echo $mpimg;?>" alt="<?php echo $mp['name'];?>" class="img-responsive" />
              <?php }else{ ?>
                <p class="no-image"><i class="fa fa-shopping-bag"></i></p>
               <?php  } ?>
            </a>
          </div>
        </div>
        <div class="caption-mp" style="height:60px;">
          <?php $name = strlen($mp['name']) > 25 ? substr($mp['name'],0,25)."..." : $mp['name'];?>
          <h6><a data-toggle="tooltip" title="<?php echo utf8_encode($mp['name']); ?>" href="<?php echo $mp['href']; ?>"><?php echo utf8_encode($name); ?></a></h6>
                <p  style="color:#e81b30; font-size: 12px"><b>₱<?php echo   number_format($mp['price'],2);?></b></p>
                </p>
        </div>
        <div  class="button-group">
          <?php if($is_log): ?>
            <a type="button" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;" class="btn btn-pink btn-addtocart_home" href="<?php echo $mp['href']; ?>"><i data-feather="shopping-cart"></i> Add to cart</a>
          <?php else: ?>
            <a type="button" data-toggle="modal" data-target="#LoginModal" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i data-feather="shopping-cart"></i> Add to cart</a>           
          <?php endif;?>
        </div>   
      </div>
    </div>
  <?php }?>
</section>  
 <script>
  $(".center-mp").slick({
      dots: true,
      infinite: true,
      slidesToShow: 8,
      nextArrow: '<i class="fa fa-angle-right icon-next"></i>',
      prevArrow: '<i class="fa fa-angle-left icon-prev"></i>',
      arrows: true,
      slidesToScroll: 8,
      responsive: [{
        breakpoint: 1023,
        settings: {
          slidesToShow: 2,
          arrows: false
        }
      },
      {
         breakpoint: 767,
         settings: {
            arrows: false,
            slidesToShow: 2,
            slidesToScroll: 2,
            arrows: false
         }
      },
       {
         breakpoint: 425,
         settings: {
            arrows: false,
            slidesToShow: 2,
            slidesToScroll: 2,
            arrows: false
         }
      }
      ]
    });
  // var ISLOG='<?php echo $custid;?>';
      /*$.ajax({
          url:'ajax_getproduct.php?action=getproduct_most_popular',
          type: 'post', 
          data: 'limit=9',
          dataType: 'json',
          success: function(jsond) {
            if(jsond){
            $("#pmploader").css("display", "none");
            // console.log(jsond['most_popular']);
             var section_data2="";
            for (var i = 0; i < jsond['most_popular'].length; i++) {
              var getimg2="";
              if(jsond['most_popular'][i]['type']==0){
                getimg2=jsond['most_popular'][i]['thumb'] ;
              }else{
                getimg2=jsond['most_popular'][i]['image'] ;
              }
              section_data2 = section_data2 + '<div class="card-mp" style=" margin: auto;text-align: center;font-family: arial;font-size: 6px;box-shadow: 0 0.0625rem 0.125rem 0 rgba(0,0,0,.6);border-radius: 10px;background: #fff;" id="div_pmpn">';
                section_data2 = section_data2 + '<div class="">';
                  section_data2 = section_data2 + '<div class="image-container-pr">';
                    section_data2 = section_data2 + '<div data-toggle="tooltip" title="Click for more details" class="image">';
                      section_data2 = section_data2 + '<a  href="'+jsond['most_popular'][i]['href']+'">';
                      if(getimg2!=""){
                        section_data2 = section_data2 + '<img src="'+getimg2+'" alt="'+jsond['most_popular'][i]['name']+'" class="img-responsive" />';
                      }else{
                        section_data2 = section_data2 + ' <p class="no-image"><i class="fa fa-shopping-bag"></i></p>';
                      }
                    section_data2 = section_data2 + '</div>';
                  section_data2 = section_data2 + '</div>';
                  section_data2 = section_data2 + '<div class="caption-mp" style="height:60px;">';
                    var name2="";
                    if(jsond['most_popular'][i]['name'].length > 25){
                      name2=jsond['most_popular'][i]['name'].substring(0, 25)+"...";
                    }else{
                      name2=jsond['most_popular'][i]['name'];
                    }
                    section_data2 = section_data2 +  '<h6><a data-toggle="tooltip" title="'+jsond['most_popular'][i]['name']+'" href="'+jsond['most_popular'][i]['href']+'">'+name2+'</a></h6>';  
                    section_data2 = section_data2 + '<p  style="color:#e81b30; font-size: 12px"><b>₱'+jsond['most_popular'][i]['price']+'</b></p>';
                  section_data2 = section_data2 + '</div>';
                  section_data2 = section_data2 + '<div>';
                      if(ISLOG!=0){
                        section_data2 = section_data2 + '<a type="button"class="btn btn-pink btn_addtocart_bg" href="'+jsond['most_popular'][i]['href']+'"  style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i data-feather="shopping-cart"></i> Add to cart</a>    ';
                      }else{
                          section_data2 = section_data2 +'<a type="button" data-toggle="modal" data-target="#LoginModal"  style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i data-feather="shopping-cart" ></i> Add to cart</a>';
                      }
                  section_data2 = section_data2 + '</div>';
                section_data2 = section_data2 + '</div>';
              section_data2 = section_data2 + '</div>';
            }
            
            $('.center-mp').append(section_data2);
            $(".center-mp").slick({
                dots: true,
                infinite: true,
                slidesToShow: 8,
                nextArrow: '<i class="fa fa-angle-right icon-next"></i>',
                prevArrow: '<i class="fa fa-angle-left icon-prev"></i>',
                arrows: true,
                slidesToScroll: 8,
                responsive: [{
                  breakpoint: 1023,
                  settings: {
                    slidesToShow: 2,
                    arrows: false
                  }
                },
                {
                   breakpoint: 767,
                   settings: {
                      arrows: false,
                      slidesToShow: 2,
                      slidesToScroll: 2,
                      arrows: false
                   }
                },
                 {
                   breakpoint: 425,
                   settings: {
                      arrows: false,
                      slidesToShow: 2,
                      slidesToScroll: 2,
                      arrows: false
                   }
                }
                ]
              });
           }
          },
          error: function(xhr, ajaxOptions, thrownError) {
              alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          }
      });*/
</script>  

