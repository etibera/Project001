 <style type="text/css">
   .slider-pr .slick-slide {
    height: auto;
    margin: 2px
    }
   #div_prn:hover {
      border: 1px solid #777;
    }
    
</style>
<?php $recommended_product=$home_new_mod->recommended_product_new($custid,9);?>
<!-- <div class="loadingio-spinner-spin-rn29k708lb" id="loadingprn">
    <div class="ldio-60clywj1yi5" >
        <div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div>
    </div>
</div> -->

<section class="center-pr slider-pr">
  <?php foreach ($recommended_product as $rp) { ?> 
    <?php $primg="";
      if($rp['type']==0){
        $primg=$rp['thumb'] ;
      }else{
        $primg=$rp['image'] ;
      }
    ?> 
    <div class="card-pr" style=" margin: auto;text-align: center;font-family: arial;font-size: 6px;box-shadow: 0 0.0625rem 0.125rem 0 rgba(0,0,0,.6);border-radius: 10px;background: #fff;" id="div_prn">
      <div class="">
        <div class="image-container-pr">
          <div data-toggle="tooltip" title="Click for more details" class="image">
            <a  href="<?php echo $rp['href'];?>">
              <?php if($primg!=""){ ?>
                  <img src="<?php echo $primg;?>" alt="<?php echo $rp['name'];?>"  class="img-responsive" />
              <?php }else{ ?>
                <p class="no-image"><i class="fa fa-shopping-bag"></i></p>
               <?php  } ?>
            </a>
          </div>
        </div>
        <div class="caption-pr" style="height:60px;">
          <?php $name = strlen($rp['name']) > 25 ? substr($rp['name'],0,25)."..." : $rp['name'];?>
          <h6><a data-toggle="tooltip" title="<?php echo utf8_encode($rp['name']); ?>" href="<?php echo $rp['href']; ?>"><?php echo utf8_encode($name); ?></a></h6>
                <p  style="color:#e81b30; font-size: 12px"><b>₱<?php echo   number_format($rp['price'],2);?></b></p>
                </p>
        </div>
        <div  class="button-group">
          <?php if($is_log): ?>
            <a type="button" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;" class="btn btn-pink btn-addtocart_home" href="<?php echo $rp['href']; ?>"><i data-feather="shopping-cart"></i> Add to cart</a>
          <?php else: ?>
            <a type="button" data-toggle="modal" data-target="#LoginModal" style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i data-feather="shopping-cart"></i> Add to cart</a>           
          <?php endif;?>
        </div>   
      </div>
    </div>
  <?php }?>
</section>
 <script>
  /*var custid='<?php echo $custid;?>';
  console.log(custid);
      $.ajax({
          url:'ajax_getproduct.php?action=getproduct_recommended',
          type: 'post', 
          data: 'limit=9&cust_id='+custid,
          dataType: 'json',
          success: function(jsondata) {
            if(jsondata){
          	 $("#loadingprn").css("display", "none");
            
           // console.log(jsondata['recommended_product']);
             var section_data = "";
             for (var i = 0; i < jsondata['recommended_product'].length; i++) {
              	var getimg="";
              	if(jsondata['recommended_product'][i]['type']==0){
                	getimg=jsondata['recommended_product'][i]['thumb'] ;
              	}else{
                	getimg=jsondata['recommended_product'][i]['image'] ;
              	}
               	section_data = section_data + '<div class="card-pr" style=" margin: auto;text-align: center;font-family: arial;font-size: 6px;box-shadow: 0 0.0625rem 0.125rem 0 rgba(0,0,0,.6);border-radius: 10px;background: #fff;" id="div_prn">';
               		section_data = section_data + '<div class="">';
               			section_data = section_data + '<div class="image-container-pr">';
               				section_data = section_data + '<div data-toggle="tooltip" title="Click for more details" class="image">';
               					section_data = section_data + '<a  href="'+jsondata['recommended_product'][i]['href']+'">';
               					if(getimg!=""){
                 					section_data = section_data + '<img src="'+getimg+'" alt="'+jsondata['recommended_product'][i]['name']+'" class="img-responsive" />';
               					}else{
                  					section_data = section_data + ' <p class="no-image"><i class="fa fa-shopping-bag"></i></p>';
               					}
               					section_data = section_data + '</a>';
               				section_data = section_data + '</div>';
	               		section_data = section_data + '</div>';
	               		section_data = section_data + '<div class="caption-pr" style="height:60px;">';
	               			var name="";
	               			if(jsondata['recommended_product'][i]['name'].length > 25){
	                			name=jsondata['recommended_product'][i]['name'].substring(0, 25)+"...";
	               			}else{
	                			name=jsondata['recommended_product'][i]['name'];
	               			}
	               			section_data = section_data +  '<h6><a data-toggle="tooltip" title="'+jsondata['recommended_product'][i]['name']+'" href="'+jsondata['recommended_product'][i]['href']+'">'+name+'</a></h6>';               
	               			section_data = section_data + '<p  style="color:#e81b30; font-size: 12px"><b>₱'+jsondata['recommended_product'][i]['price']+'</b></p>';
		               	section_data = section_data + '</div>';
	               		section_data = section_data + '<div>';
		               		if(custid!=0){
		                 		section_data = section_data + '<a type="button"class="btn btn-pink btn_addtocart_bg" href="'+jsondata['recommended_product'][i]['href']+'"  style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i data-feather="shopping-cart"></i> Add to cart</a>    ';
		               		}else{
		                  		section_data = section_data +'<a type="button" data-toggle="modal" data-target="#LoginModal"  style="border: none;outline: 0;padding: 12px;text-align: center;cursor: pointer;width: 100%;font-size: 12px;"><i data-feather="shopping-cart" ></i> Add to cart</a>';
		               		}
	               		section_data = section_data + '</div>';

               		section_data = section_data + '</div>';
               section_data = section_data + '</div>';
             }
             /// console.log(jsondata['recommended_product']);
            
             $('.center-pr').append(section_data);

             $(".center-pr").slick({
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
                      slidesToScroll: 2,
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

 $(".center-pr").slick({
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
        slidesToScroll: 2,
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
  
</script>    


