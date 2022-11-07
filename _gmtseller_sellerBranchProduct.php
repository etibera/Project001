<?php
  require_once("includes/init.php");
  include 'template/header.php'; 
  require_once "model/sellerBranchProduct.php";
  require_once "model/brand.php";
  $MODSBP=new SellerBranchProduct;
  $model_brand=new brand;
  if(!$session->is_signed_in_seller()){redirect("index");}
  $seller_id = isset($_SESSION['user_id_seller']) ? $_SESSION['user_id_seller']: 0;
  $sbid=$seller_branch_id;
  $get_seller_brand=$model_brand->seller_brand($seller_id);
  $BranchProductList=$MODSBP->GetBranchProductListDeff($seller_id,1,$seller_branch_id);

  if(isset($_POST['btn_search'])){
    $BranchProductList=$MODSBP->GetBranchProductList($seller_id,$_POST,$seller_branch_id);
  }
 /* echo "<pre>";
  print_r($BranchProductList);*/
 ?>
 <style>
  .switch {position: relative;display: inline-block;width: 55px;height: 25px;}
  .switch input { opacity: 0; width: 0;height: 0;}
  .slider {position: absolute;cursor: pointer;top: 0;left: 0;right: 0;bottom: 0;background-color: #ccc;-webkit-transition: .4s;transition: .4s;}
  .slider:before {position: absolute;content: "";height: 20px;width: 20px;left: 4px;bottom: 3px;background-color: white;-webkit-transition: .4s;transition: .4s;}
  input:checked + .slider {background-color: #3bc157;}
  input:focus + .slider {box-shadow: 0 0 1px #3bc157;}
  input:checked + .slider:before {-webkit-transform: translateX(26px);-ms-transform: translateX(26px);transform: translateX(26px);}
  .slider.round {border-radius: 34px;}
  .slider.round:before {border-radius: 50%;}
</style>
<div id="content">
	<div class="container-fluid">
		<div class="panel panel-default">
  		<div class="panel-heading" style="padding:20px;">
  			<div class="row">
          <div class="col-lg-12">
             <p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i> Branch / Dealer Product List</p>
          </div>		          
        </div>
        <div class="row">         
          <div class="col-lg-12">
            <a class="btn btn-danger pull-right" id="btndelfeebies" title="Delete Freebies" style="margin-left:5px; "><i data-feather="trash-2"> </i> Delete Freebies</a> 
            <a class="btn btn-primary pull-right" id="save_changes" title="Batch Product Status" style="margin-left:5px; "><i data-feather="save"></i> Batch Product Status</a> 
            <a class="btn btn-primary pull-right" id="btnBatchQTY" title="Batch Quantity" style="margin-left:5px; "><i data-feather="trello"> </i> Batch Quantity</a> 
            <a class="btn btn-primary pull-right" id="btnBatchCI" title="atch Card Installment" style="margin-left:5px; "><i data-feather="trello"> </i> Batch Card Installment</a> 
             <a class="btn btn-primary pull-right" id="btnBatchCOD" title="atch Card Installment" style="margin-left:5px; "><i data-feather="trello"> </i> Batch COD</a> 
            <a class="btn btn-primary pull-right" id="btnAddfeebies" title="Add Freebies" style="margin-left:5px; "><i data-feather="gift"> </i> Add Freebies</a> 
          </div>
        </div>
  		</div><!-- end panel-heading-->
      <div class="panel-body">
        <div class="row">
          <div class="col-xs-12 well">
             <form method="post" class="form-horizontal" action="sellerBranchProduct.php">
              <div class="form-group">               
                <div class="col-sm-3">
                  <select name="brand_id" id="d_status" class="form-control" required>
                    <option value="">--Select Brand--</option>                  
                    <?php foreach ($get_seller_brand as  $opt_brd) { ?> 
                      <?php if(isset($_POST['btn_search'])){ ?>
                        <?php if(isset($_POST['brand_id'])&&$_POST['brand_id']==$opt_brd['id']){ ?> 
                           <option value="<?php echo $opt_brd['id'];?>" selected><?php echo $opt_brd['name'];?></option>
                        <?php }else{ ?> 
                           <option value="<?php echo $opt_brd['id'];?>"><?php echo $opt_brd['name'];?></option>
                        <?php } ?>
                      <?php }else{ ?> 
                        <option value="<?php echo $opt_brd['id'];?>"><?php echo $opt_brd['name'];?></option>
                      <?php } ?>                   
                    <?php } ?>
                  </select>
                </div>
                <div class="col-sm-3">
                  <input type="submit" name="btn_search" class="btn btn-success" value="Search">
                </div>
              </div>
            </form><!-- end form -->
          </div><!-- end col-xs-12 well -->
        </div><!-- end row-->
        <div class="row">
          <div class="table-responsive">
            <table class="table table-bordered table-hover" id="seller_products">
              <?php $countdata = 0; foreach ($BranchProductList as  $pbrand) { $countdata++;?> 
                <tbody >
                  <tr >
                    <th class="text-left" colspan="4">
                      <div id="brand-div_<?php echo $pbrand['id'];?>">                      
                        <input type="checkbox" name="chk_brand_id" value="<?php echo $pbrand['id'];?>" checked/>
                        <?php if($pbrand['thumb']==""){ ?>
                          <label><?php echo $pbrand['name']; ?></label>
                        <?php }else{ ?>
                          <img src="<?php echo $pbrand['thumb']; ?>" class="img-responsive" style="width: 80px; width: 80px; display:inline-block;" data-toggle="collapse" data-target="#accordion_<?php echo $pbrand['id'];?>" class="clickable" onclick="Checkbrand(<?php echo $pbrand['id'];?>)" />
                        <?php }?>
                      </div>
                    </th>
                    <th class="text-center" colspan="6"style="vertical-align: middle;">
                      <input class="form-control pull-right" type="text" id="InputsearchVal_<?php echo $pbrand['id'];?>" onkeyup="searchProductName(<?php echo $pbrand['id'];?>)" placeholder="Search for Product Name or Model"> 
                    </th>                   
                    <th class="text-center" style="width: 120px;"style="vertical-align: middle;">
                      <a class="btn btn-success" id="select_all_<?php echo $pbrand['id'];?>" title="Select All Product" onclick="CheckAllProducts(<?php echo $pbrand['id'];?>)" style="width: 90px;">
                        <i data-feather="check-square"></i>
                      </a>
                      <span id="select_all_span<?php echo $pbrand['id'];?>"><b>Select All</b></span>
                      <a style="display: none;width: 90px;"class="btn btn-success" id="unselect_all_<?php echo $pbrand['id'];?>" title="Un Select All Product" onclick="UnCheckAllProducts(<?php echo $pbrand['id'];?>)" >
                        <i data-feather="square"></i>
                      </a>
                      <span id="unselect_all_span<?php echo $pbrand['id'];?>"style="display: none;"><b> Un Select All</b></span> 
                       <!--  for search fucntion-->
                      <a class="btn btn-success" id="select_all_src<?php echo $pbrand['id'];?>" title="Select All Product" onclick="CheckAllProducts_src(<?php echo $pbrand['id'];?>)" style="display: none;width: 90px;">
                        <i data-feather="check-square"></i>
                      </a>
                      <span id="select_all_src_span<?php echo $pbrand['id'];?>" style="display: none;"><b>Select All</b></span>
                      <a style="display: none;width: 90px;"class="btn btn-success" id="unselect_all_src<?php echo $pbrand['id'];?>" title="Un Select All Product" onclick="UnCheckAllProducts_src(<?php echo $pbrand['id'];?>)" >
                        <i data-feather="square"></i>
                      </a>
                      <span id="unselect_all_src_span<?php echo $pbrand['id'];?>"style="display: none;"><b> Un Select All</b></span> 
                       <!--  end for search fucntion-->
                    </th>
                  </tr>
                </tbody>
                <tbody id="accordion_<?php echo $pbrand['id'];?>" class="<?php if($countdata!=1){ echo "collapse";}?>">
                  <?php if (count($pbrand['product_list'])==0) { ?>
                    <tr><th class="text-center" colspan="7">***No Data Found***</th></tr>
                  <?php }else{ ?>
                    <tr>
                      <th class="text-center" colspan="2" >Image</th>
                      <th class="text-center">Product Id </th>
                      <th class="text-center">Product Name </th>
                      <th class="text-center">Model</th>
                      <th class="text-center">Price</th>
                      <th class="text-center">Quantity</th>
                      <th class="text-center">Freebies</th>
                      <th class="text-center">Card Installment</th>
                      <th class="text-center">COD</th>
                      <th class="text-center">Status</th>
                    </tr>
                    <?php foreach ($pbrand['product_list'] as  $prod_list) { ?>
                      <?php  $getimg =$prod_list['thumb']; $prdname=$get_prd_name=str_replace(array("'", "\"", "&quot;"), "", htmlspecialchars(utf8_encode($prod_list['name'])));?>
                      <tr>
                        <td >
                          <div class="image-container2 tdchkProduct_div_<?php echo $pbrand['id'];?>"  id="product-div_<?php echo $prod_list['product_id'];?>">
                            <input type="checkbox" name="chk_prod_id2[]" value="<?php echo $prod_list['product_id'];?>" />
                          </div>
                        </td>
                        <td>
                          <a class="btn" onclick="CheckProducts(<?php echo $prod_list['product_id'];?>)">
                            <?php if($getimg!=""): ?>
                              <img src="<?php echo $getimg; ?>" alt="<?php echo $prdname; ?>" class="img-responsive" />
                            <?php else: ?>
                              <i class="fa fa-shopping-bag" style="font-size: 50px;color: #333;"></i>
                            <?php endif; ?>  
                          </a>
                        </td>
                        <td><?php echo $prod_list['product_id'];?></td>
                        <td><?php echo $prod_list['name'];?></td>
                        <td><?php echo $prod_list['model'];?></td>
                        <td>
                          <p style="color:#e81b30; font-size: 12px">
                            <b>₱<?php echo number_format($prod_list['price'],2);?></b>
                          </p>
                        </td>                       
                        <td class="text-center">
                          <?php if($prod_list['selected_product']==1){ //echo $prod_list['quantity']; ?>
                            <input type="number" value="<?php echo $prod_list['quantity'];?>" onchange="UpdateProdQty(<?php echo $prod_list['product_id'];?>, event.target.value,<?php echo $seller_id;?>,'<?php echo $prdname;?>',<?php echo $pbrand['id'];?>,<?php echo $sbid;?>)"> 
                          <?php }else{ echo "0";}?>                           
                        </td>
                        <td><?php echo $prod_list['freebies'];?></td>
                        <td>
                          <?php if($prod_list['selected_product']==1){ ?>
                            <div class="chkProduct_cardInstallment<?php echo $pbrand['id'];?>"  id="product-div_cardInstallment<?php echo $prod_list['product_id'];?>">
                              <label class="switch ">
                                <input type="checkbox" name="chk_prod_idCI[]" value="<?php echo $prod_list['product_id'];?>" <?php if($prod_list['installment']==1){echo "checked"; }?>  onchange="CheckProducCI(<?php echo $prod_list['product_id'];?>,<?php echo $seller_id;?>,event,'<?php echo $prdname;?>',<?php echo $seller_id;?>,<?php echo $pbrand['id'];?>,<?php echo $sbid;?>)" >
                                <span class='slider round'></span>
                                <br>
                                <span class="stays_label_allCI<?php echo $pbrand['id'];?>" id="s-label-CI<?php echo $prod_list['product_id'];?>">
                                  <?php if($prod_list['installment']==1){echo "Enabled";}else{echo "Disabled";} ?>
                                </span>
                              </label>
                            </div>
                        <?php }else{ echo "Disabled";}?>      
                        </td>
                         <td>
                          <?php if($prod_list['selected_product']==1){ ?>
                            <div class="chkProduct_cod<?php echo $pbrand['id'];?>"  id="product-div_cod<?php echo $prod_list['product_id'];?>">
                              <label class="switch ">
                                <input type="checkbox" name="chk_prod_idcod[]" value="<?php echo $prod_list['product_id'];?>" <?php if($prod_list['cod']==1){echo "checked"; }?>  onchange="CheckProductCOd(<?php echo $prod_list['product_id'];?>,<?php echo $seller_id;?>,event,'<?php echo $prdname;?>',<?php echo $seller_id;?>,<?php echo $pbrand['id'];?>,<?php echo $sbid;?>)" >
                                <span class='slider round'></span>
                                <br>
                                <span class="stays_label_allcod<?php echo $pbrand['id'];?>" id="s-label-cod<?php echo $prod_list['product_id'];?>">
                                  <?php if($prod_list['cod']==1){echo "Enabled";}else{echo "Disabled";} ?>
                                </span>
                              </label>
                            </div>
                        <?php }else{ echo "Disabled";}?>      
                        </td>
                        <td class="text-center">
                          <div class="chkProduct_stats_<?php echo $pbrand['id'];?>"  id="product-div_stats_<?php echo $prod_list['product_id'];?>">
                            <label class="switch ">
                              <input type="checkbox" name="chk_prod_id[]" value="<?php echo $prod_list['product_id'];?>" <?php if($prod_list['selected_product']==1){echo "checked"; }?>  onchange="CheckProductStatus(<?php echo $prod_list['product_id'];?>,event,<?php echo $seller_id;?>,<?php echo $pbrand['id'];?>,<?php echo $sbid;?>)" >
                              <span class='slider round'></span>
                              <br>
                              <span class="stays_label_all<?php echo $pbrand['id'];?>" id="s-label-<?php echo $prod_list['product_id'];?>">
                                <?php if($prod_list['selected_product']==1){echo "Enabled";}else{echo "Disabled";} ?>
                              </span>
                            </label>
                          </div>
                        </td>
                      </tr>
                    <?php } ?>
                  <?php }?>
                </tbody>
              <?php } ?>
            </table><!-- end table -->
          </div><!-- END table-responsive -->
        </div><!-- end row-->
      </div><!-- end panel-body-->
		</div><!-- end panel-default -->
	</div><!-- end container-fluid -->
</div><!-- end content -->
<?php include 'template/footer.php';?>
<div  class="modal" id="PrdBatchAC" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-error-dialog">
  <!-- <form role="form"> -->
    <div class="modal-content" style="margin: auto;">
      <div class="panel-heading">
        <button class="btn" id="homecategoryclosed"  data-dismiss="modal" style="float: right;
                font-size: 18px;
                font-weight: 700;
                line-height: 1;
                color: #000;
                text-shadow: 0 1px 0 #fff;
              ">x</button>
        <h3>Product Batch Status</h3>
      </div><!-- /.panel-heading -->
      <div class="panel-body">
        <div class="form-group">
            <input type="radio" name="radioAC" value="0" checked/> <label > Disable </label></br>
            <input type="radio" name="radioAC" value="1" /> <label > Enable</label>
        </div>
        <div class="form-group navbar-right" style="margin-right: 10px;">
          <button  class="btn btn-primary" id="PrdSaveChangesAc"><i class="fa fa-save"></i> Update</button>
        </div>
      </div><!-- /.panel-body -->
    </div><!--modal-content-->
  </div><!-- modal-dialog modal-error-dialog-->
</div><!-- open_category_module_modal-->

<div  class="modal" id="PrdBatchQTYMod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-error-dialog">
  <!-- <form role="form"> -->
    <div class="modal-content" style="margin: auto;">
      <div class="panel-heading">
        <button class="btn" id="homecategoryclosed"  data-dismiss="modal" style="float: right;
                font-size: 18px;
                font-weight: 700;
                line-height: 1;
                color: #000;
                text-shadow: 0 1px 0 #fff;
              ">x</button>
        <h3>Product Batch Quantity</h3>
      </div><!-- /.panel-heading -->
      <div class="panel-body">
        <div class="form-group">
          <label ><b style="color: red">*</b>Quantity</label>
            <input type="number"  id="prdQty" class="form-control"  required>
        </div>
        <div class="form-group navbar-right" style="margin-right: 10px;">
          <button  class="btn btn-primary" id="PrdSaveChangesQty"><i class="fa fa-save"></i> Update</button>
        </div>
      </div><!-- /.panel-body -->
    </div><!--modal-content-->
  </div><!-- modal-dialog modal-error-dialog-->
</div><!-- open_category_module_modal-->
<div  class="modal" id="PrdBatchCIMod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-error-dialog">
  <!-- <form role="form"> -->
    <div class="modal-content" style="margin: auto;">
      <div class="panel-heading">
        <button class="btn" id="homecategoryclosed"  data-dismiss="modal" style="float: right;
                font-size: 18px;
                font-weight: 700;
                line-height: 1;
                color: #000;
                text-shadow: 0 1px 0 #fff;
              ">x</button>
        <h3>Product Batch Card Installment</h3>
      </div><!-- /.panel-heading -->
      <div class="panel-body">
        <div class="form-group">
            <input type="radio" name="radioPBCI" value="0" checked/> <label > Disable </label></br>
            <input type="radio" name="radioPBCI" value="1" /> <label > Enable</label>
        </div>
        <div class="form-group navbar-right" style="margin-right: 10px;">
          <button  class="btn btn-primary" id="PrdSaveChangesCI"><i class="fa fa-save"></i> Update</button>
        </div>
      </div><!-- /.panel-body -->
    </div><!--modal-content-->
  </div><!-- modal-dialog modal-error-dialog-->
</div><!-- open_category_module_modal-->
<div  class="modal" id="PrdBatchCODMod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-error-dialog">
  <!-- <form role="form"> -->
    <div class="modal-content" style="margin: auto;">
      <div class="panel-heading">
        <button class="btn" id="homecategoryclosed"  data-dismiss="modal" style="float: right;
                font-size: 18px;
                font-weight: 700;
                line-height: 1;
                color: #000;
                text-shadow: 0 1px 0 #fff;
              ">x</button>
        <h3>Product Batch COD</h3>
      </div><!-- /.panel-heading -->
      <div class="panel-body">
        <div class="form-group">
            <input type="radio" name="radioPBCOD" value="0" checked/> <label > Disable </label></br>
            <input type="radio" name="radioPBCOD" value="1" /> <label > Enable</label>
        </div>
        <div class="form-group navbar-right" style="margin-right: 10px;">
          <button  class="btn btn-primary" id="PrdSaveChangesCOD"><i class="fa fa-save"></i> Update</button>
        </div>
      </div><!-- /.panel-body -->
    </div><!--modal-content-->
  </div><!-- modal-dialog modal-error-dialog-->
</div><!-- open_category_module_modal-->

<div  class="modal" id="MODAddfeebies" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-error-dialog">
  <!-- <form role="form"> -->
    <div class="modal-content" style="margin: auto;">
      <div class="panel-heading">
        <button class="btn" id="homecategoryclosed"  data-dismiss="modal" style="float: right;
                font-size: 18px;
                font-weight: 700;
                line-height: 1;
                color: #000;
                text-shadow: 0 1px 0 #fff;
              ">x</button>
        <h3>Add Freebies</h3>
      </div><!-- /.panel-heading -->
      <div class="panel-body">
        <div class="form-group">
          <label ><b style="color: red;">*</b>Freebie Description</label>
          <textarea id="TXTFreebie" name="TXTFreebie" rows="4" cols="75"></textarea>
        </div>
        <div class="form-group navbar-right" style="margin-right: 10px;">
          <button  class="btn btn-primary" id="SaveFreebieS"><i class="fa fa-save"></i> Save</button>
        </div>
      </div><!-- /.panel-body -->
    </div><!--modal-content-->
  </div><!-- modal-dialog modal-error-dialog-->
</div><!-- open_category_module_modal-->
<script type="text/javascript">
  function UpdateProdQty(pid,val,seller_id,name,brand_id,branch_id) {
   
    $.ajax({
      url: 'ajax_SBP.php?action=branchProdUpdateQTY',
      type: 'post',
      data: 'pid=' + pid+'&qty='+val+'&seller_id='+seller_id+'&brand_id='+brand_id+'&branch_id='+branch_id,
      dataType: 'json',
      success: function(json) {
        var dialog = bootbox.dialog({
            title: name,
            message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
        });
        dialog.init(function(){
            setTimeout(function(){
                dialog.find('.bootbox-body').html(json['success']);
            }, 1000);
        });
      }
    });  
  }
  function CheckProducts(pid) {
   $("#product-div_"+pid).find('input[type=checkbox]').each(function () {        
      if(this.checked==true){
        this.checked = false;
      }else{
        this.checked = true;
      }
    });   
  }
 /* function Checkbrand(brandid) {
    $("#brand-div_"+brandid).find('input[type=checkbox]').each(function () {
      this.checked = true;
    });
  }*/
  function CheckAllProducts(brandid) {
    $("#unselect_all_"+brandid).css("display","block")
    $("#unselect_all_span"+brandid).css("display","block")
    $("#select_all_"+brandid).css("display","none")
    $("#select_all_span"+brandid).css("display","none")

    $("#unselect_all_src"+brandid).css("display","none");
    $("#unselect_all_src_span"+brandid).css("display","none");    
    $("#select_all_src"+brandid).css("display","none");
    $("#select_all_src_span"+brandid).css("display","none");

    $(".tdchkProduct_div_"+brandid).find('input[type=checkbox]').each(function () {
      this.checked = true;
    });
  }
  function CheckProductStatus(pid,event,sellerid,brand_id,branch_id) {
    var checked = event.target.checked ? 'Enabled' : 'Disabled'
    $('#s-label-'+ pid).text(checked)
    $.ajax({
      url: 'ajax_SBP.php?action=UpdateBranchProductStatus',
      type: 'post',
      data: 'pid=' + pid+'&checked='+checked+'&seller_id='+sellerid+'&branch_id='+branch_id+'&brand_id='+brand_id,
      dataType: 'json',
      success: function(json) {
        var dialog = bootbox.dialog({
            title: name,
            message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
        });
        dialog.init(function(){
            setTimeout(function(){
                dialog.find('.bootbox-body').html(json['success']);
            }, 1000);
        });
      }
    });  
  }
  function CheckProducCI(pid,sellerid,event,name,seller_id,brand_id,branch_id) {
    var checked = event.target.checked ? 'Enabled' : 'Disabled'
    $('#s-label-CI'+ pid).text(checked)
    $.ajax({
      url: 'ajax_SBP.php?action=Update_card_installment',
      type: 'post',
      data: 'pid=' + pid+'&checked='+checked+'&seller_id='+sellerid+'&seller_id='+seller_id+'&branch_id='+branch_id+'&brand_id='+brand_id,
      dataType: 'json',
      success: function(json) {
        var dialog = bootbox.dialog({
            title: name,
            message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
        });
        dialog.init(function(){
            setTimeout(function(){
                dialog.find('.bootbox-body').html(json['success']);
            }, 1000);
        });
      }
    });  
  }
  function CheckProductCOd(pid,sellerid,event,name,seller_id,brand_id,branch_id) {
    var checked = event.target.checked ? 'Enabled' : 'Disabled'
    $('#s-label-cod'+ pid).text(checked)
    $.ajax({
      url: 'ajax_SBP.php?action=Update_cod',
      type: 'post',
      data: 'pid=' + pid+'&checked='+checked+'&seller_id='+seller_id+'&branch_id='+branch_id+'&brand_id='+brand_id,
      dataType: 'json',
      success: function(json) {
        var dialog = bootbox.dialog({
            title: name,
            message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
        });
        dialog.init(function(){
            setTimeout(function(){
                dialog.find('.bootbox-body').html(json['success']);
            }, 1000);
        });
      }
    }); 
  }
  function CheckallProductStatus(bid,event) {
    $(".chkProduct_stats_"+bid).find('input[type=checkbox]').each(function () {        
      if(event.target.checked){
        this.checked = false;
      }else{
        this.checked = true;
      }
    }); 
    var checked = event.target.checked ? 'Enable All' : 'Disable all';
    var checked2 = event.target.checked ?  'Disabled' : 'Enabled';
    $('#status_all_'+ bid).text(checked)
    $('.stays_label_all'+ bid).text(checked2)
  }
  function UnCheckAllProducts(brandid) {
    $("#unselect_all_"+brandid).css("display","none")
    $("#unselect_all_span"+brandid).css("display","none")
    $("#select_all_"+brandid).css("display","block")
    $("#select_all_span"+brandid).css("display","block")

    $("#unselect_all_src"+brandid).css("display","none");
    $("#unselect_all_src_span"+brandid).css("display","none");    
    $("#select_all_src"+brandid).css("display","none");
    $("#select_all_src_span"+brandid).css("display","none");

    $(".tdchkProduct_div_"+brandid).find('input[type=checkbox]').each(function () {
      this.checked = false;
    });
  }

  function updateProduct_status() {
    var chk_brand_ids = [];
    var chk_brand_ids_all = [];
    var selected = [];   
    var  seller_branch_id='<?php echo  $seller_branch_id ; ?>';
    var  seller_id='<?php echo  $seller_id ; ?>';
   
    var radioAC =$('input[name="radioAC"]:checked').val();
    $.each($("input[name='chk_brand_id']:checked"), function(){
      chk_brand_ids.push($(this).val());
    });
    for (var i = 0; i < chk_brand_ids.length; i++) {
      var no=chk_brand_ids[i];
      var array = {brand_id:chk_brand_ids[i], value: $('#accordion_'+no+' input:checked[name="chk_prod_id2[]"]').map(function () { return $(this).val(); }).get()};
      selected.push(array)
    }
    if(radioAC==""){
      bootbox.alert("Please Select status ");
      $('#PrdSaveChangesAc').prop('disabled', false);
    }else if(chk_brand_ids.length==0){
      bootbox.alert("Please select Brand To Save ");
      $('#PrdSaveChangesAc').prop('disabled', false);
    }else if(selected[0].value.length==0){
      bootbox.alert("Please select Product First");
      $('#PrdSaveChangesAc').prop('disabled', false);
    }else{
    
      $.ajax({
        url: 'ajax_SBP.php?action=BatchProductStats',
        type: 'post',
        data: 'chk_data=' + JSON.stringify(selected)+'&radioAC='+radioAC+'&seller_branch_id='+seller_branch_id+'&seller_id='+seller_id,
        dataType: 'json',
        success: function(json) {
           //console.log(json);
          bootbox.alert(json['success'], function(){ 
            location.reload();
          });
        }
      });         
    }  
  }
  function updateProduct_QTY() {
    var chk_brand_ids = [];
    var chk_brand_ids_all = [];
    var selected = [];    
    var prdQty =$("#prdQty").val(); 
    var  seller_branch_id='<?php echo  $seller_branch_id ; ?>';
    var  seller_id='<?php echo  $seller_id ; ?>';
    $.each($("input[name='chk_brand_id']:checked"), function(){
      chk_brand_ids.push($(this).val());
    });
    for (var i = 0; i < chk_brand_ids.length; i++) {
      var no=chk_brand_ids[i];
      var array = {brand_id:chk_brand_ids[i], value: $('#accordion_'+no+' input:checked[name="chk_prod_id2[]"]').map(function () { return $(this).val(); }).get()};
      selected.push(array)
    }
    if(prdQty==""){
      bootbox.alert("Please Input Quantity ");
      $('#PrdSaveChangesQty').prop('disabled', false);
    }else if(chk_brand_ids.length==0){
      bootbox.alert("Please select Brand To Save ");
      $('#PrdSaveChangesQty').prop('disabled', false);
    }else if(selected[0].value.length==0){
      bootbox.alert("Please select Product First");
      $('#PrdSaveChangesQty').prop('disabled', false);
    }else{
      //console.log(JSON.stringify(selected));
      $.ajax({
        url: 'ajax_SBP.php?action=BatchUpdateProduct_QTY',
        type: 'post',
        data: 'chk_data=' + JSON.stringify(selected)+'&qty='+prdQty+'&seller_id='+seller_id+'&seller_branch_id='+seller_branch_id,
        dataType: 'json',
        success: function(json) {
          bootbox.alert(json['success'], function(){ 
            location.reload();
          });
        }
      });         
    }      
  }
  function updateProduct_CI() {
    var chk_brand_ids = [];
    var chk_brand_ids_all = [];
    var selected = [];    
    var  seller_branch_id='<?php echo  $seller_branch_id ; ?>';
    var  seller_id='<?php echo  $seller_id ; ?>';
    var radioPBCI =$('input[name="radioPBCI"]:checked').val();
    $.each($("input[name='chk_brand_id']:checked"), function(){
      chk_brand_ids.push($(this).val());
    });
    for (var i = 0; i < chk_brand_ids.length; i++) {
      var no=chk_brand_ids[i];
      var array = {brand_id:chk_brand_ids[i], value: $('#accordion_'+no+' input:checked[name="chk_prod_id2[]"]').map(function () { return $(this).val(); }).get()};
      selected.push(array)
    }
    if(radioPBCI==""){
      bootbox.alert("Please Select status ");
      $('#PrdSaveChangesCI').prop('disabled', false);
    }else if(chk_brand_ids.length==0){
      bootbox.alert("Please select Brand To Save ");
      $('#PrdSaveChangesCI').prop('disabled', false);
    }else if(selected[0].value.length==0){
      bootbox.alert("Please select Product First");
      $('#PrdSaveChangesCI').prop('disabled', false);
    }else{
     /* console.log(JSON.stringify(selected));
      console.log(radioPBCI);*/
      $.ajax({
        url: 'ajax_SBP.php?action=updateProduct_CI',
        type: 'post',
        data: 'chk_data=' + JSON.stringify(selected)+'&radioPBCI='+radioPBCI+'&seller_branch_id='+seller_branch_id+'&seller_id='+seller_id,
        dataType: 'json',
        success: function(json) {
           //console.log(json);
          bootbox.alert(json['success'], function(){ 
            location.reload();
          });
        }
      });         
    }      
  }
  function updateProduct_COD() {
    var chk_brand_ids = [];
    var chk_brand_ids_all = [];
    var selected = [];    
    var  seller_branch_id='<?php echo  $seller_branch_id ; ?>';
    var  seller_id='<?php echo  $seller_id ; ?>';
    var radioPBCOD =$('input[name="radioPBCOD"]:checked').val();
    $.each($("input[name='chk_brand_id']:checked"), function(){
      chk_brand_ids.push($(this).val());
    });
    for (var i = 0; i < chk_brand_ids.length; i++) {
      var no=chk_brand_ids[i];
      var array = {brand_id:chk_brand_ids[i], value: $('#accordion_'+no+' input:checked[name="chk_prod_id2[]"]').map(function () { return $(this).val(); }).get()};
      selected.push(array)
    }
    if(radioPBCOD==""){
      bootbox.alert("Please Select status ");
      $('#PrdSaveChangesCI').prop('disabled', false);
    }else if(chk_brand_ids.length==0){
      bootbox.alert("Please select Brand To Save ");
      $('#PrdSaveChangesCI').prop('disabled', false);
    }else if(selected[0].value.length==0){
      bootbox.alert("Please select Product First");
      $('#PrdSaveChangesCI').prop('disabled', false);
    }else{
     
      $.ajax({
        url: 'ajax_SBP.php?action=updateProduct_COD',
        type: 'post',
        data: 'chk_data=' + JSON.stringify(selected)+'&radioPBCOD='+radioPBCOD+'&seller_branch_id='+seller_branch_id+'&seller_id='+seller_id,
        dataType: 'json',
        success: function(json) {
           //console.log(json);
          bootbox.alert(json['success'], function(){ 
            location.reload();
          });
        }
      });         
    }      
  }
  function add_PrdSaveFreebies() {
    var chk_brand_ids = [];
    var chk_brand_ids_all = [];
    var selected = [];    
    var prddesc =$("#TXTFreebie").val(); 
    var  seller_branch_id='<?php echo  $seller_branch_id ; ?>';
    var  seller_id='<?php echo  $seller_id ; ?>';
    $.each($("input[name='chk_brand_id']:checked"), function(){
      chk_brand_ids.push($(this).val());
    });
    for (var i = 0; i < chk_brand_ids.length; i++) {
      var no=chk_brand_ids[i];
      var array = {brand_id:chk_brand_ids[i], value: $('#accordion_'+no+' input:checked[name="chk_prod_id2[]"]').map(function () { return $(this).val(); }).get()};
      selected.push(array)
    }
    if(prddesc==""){
      bootbox.alert("Please Input Freebie Description First");
      $('#SaveFreebieS').prop('disabled', false);
    }else if(chk_brand_ids.length==0){
      bootbox.alert("Please select Brand To Save ");
      $('#SaveFreebieS').prop('disabled', false);
    }else if(selected[0].value.length==0){
      bootbox.alert("Please select Product First");
      $('#SaveFreebieS').prop('disabled', false);
    }else{
     /* console.log(JSON.stringify(selected));
      console.log(prddesc);*/
      $.ajax({
        url: 'ajax_SBP.php?action=batchAddFreebies',
        type: 'post',
        data: 'chk_data=' + JSON.stringify(selected)+'&prddesc='+prddesc+'&seller_id='+seller_id+'&branch_id='+seller_branch_id,
        dataType: 'json',
        success: function(json) {
          bootbox.alert(json['success'], function(){ 
            location.reload();
          });
          //console.log(json);
        }
      });         
    }      
  }
  function deleteFreebies() {
    var chk_brand_ids = [];
    var chk_brand_ids_all = [];
    var selected = [];    
    var  seller_branch_id='<?php echo  $seller_branch_id ; ?>';
    var  seller_id='<?php echo  $seller_id ; ?>';
    $.each($("input[name='chk_brand_id']:checked"), function(){
      chk_brand_ids.push($(this).val());
    });
    for (var i = 0; i < chk_brand_ids.length; i++) {
      var no=chk_brand_ids[i];
      var array = {brand_id:chk_brand_ids[i], value: $('#accordion_'+no+' input:checked[name="chk_prod_id2[]"]').map(function () { return $(this).val(); }).get()};
      selected.push(array)
    }
    if(chk_brand_ids.length==0){
      bootbox.alert("Please select Brand To Save ");
      $('#btndelfeebies').prop('disabled', false);
    }else if(selected[0].value.length==0){
      bootbox.alert("Please select Product First");
      $('#btndelfeebies').prop('disabled', false);
    }else{
      bootbox.confirm({
        message: "Are you sure you want to delete freebies?",
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
          if(result){
            $.ajax({
              url: 'ajax_SBP.php?action=deleteFreebies',
              type: 'post',
              data: 'chk_data=' + JSON.stringify(selected)+'&seller_id='+seller_id+'&branch_id='+seller_branch_id,
              dataType: 'json',
              success: function(json) {
                bootbox.alert(json['success'], function(){ 
                  location.reload();
                });
                //console.log(json);
              }
            }); 
          }
        }
      });
    }      
  }
  function UnCheckAllProducts_src(brandid) {
    $("#unselect_all_"+brandid).css("display","none");
    $("#unselect_all_span"+brandid).css("display","none");
    $("#select_all_"+brandid).css("display","none");
    $("#select_all_span"+brandid).css("display","none");
    $("#select_all_src"+brandid).css("display","block");
    $("#select_all_src_span"+brandid).css("display","block");
    $("#unselect_all_src"+brandid).css("display","none");
    $("#unselect_all_src_span"+brandid).css("display","none"); 

    var input, filter, table, tr, td,td2,tdpid, i, txtValue,txtValue2;
    var val_brand_ids = [];
    input = document.getElementById("InputsearchVal_"+brandid);
    filter = input.value.toUpperCase().trim();
    table = document.getElementById("seller_products");
    var tBody = table.tBodies.namedItem("accordion_"+brandid);
    var tableRow = tBody.getElementsByTagName('tr');
    for (var t = 0; t < tableRow.length; t++){
        td = tableRow[t].getElementsByTagName("td")[3];
        td2 = tableRow[t].getElementsByTagName("td")[4];
        tdpid = tableRow[t].getElementsByTagName("td")[2];
       // console.log(td);

        if (td) {
          txtValue = td.textContent || td.innerText;
          txtValue2 = td2.textContent || td2.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tableRow[t].style.display = "";
            val_brand_ids.push(tdpid.innerText);
          } else if (txtValue2.toUpperCase().indexOf(filter) > -1){
              tableRow[t].style.display = "";
              val_brand_ids.push(tdpid.innerText);
          }else{
             tableRow[t].style.display = "none";
          }
        }       
        
    }
     for (var y = 0; y < val_brand_ids.length; y++){
         $("#product-div_"+val_brand_ids[y]).find('input[type=checkbox]').each(function () { this.checked = false; });   
     } 
   
  }
  function CheckAllProducts_src(brandid) {
    $("#unselect_all_src"+brandid).css("display","block")
    $("#unselect_all_src_span"+brandid).css("display","block")
    $("#unselect_all_"+brandid).css("display","none")
    $("#unselect_all_span"+brandid).css("display","none")
    $("#select_all_"+brandid).css("display","none")
    $("#select_all_span"+brandid).css("display","none")
    $("#select_all_src"+brandid).css("display","none")
    $("#select_all_src_span"+brandid).css("display","none")
   

    var input, filter, table, tr, td,td2,tdpid, i, txtValue,txtValue2;
    var val_brand_ids = [];
    input = document.getElementById("InputsearchVal_"+brandid);
    filter = input.value.toUpperCase().trim();
    table = document.getElementById("seller_products");
    var tBody = table.tBodies.namedItem("accordion_"+brandid);
    var tableRow = tBody.getElementsByTagName('tr');
    for (var t = 0; t < tableRow.length; t++){
        td = tableRow[t].getElementsByTagName("td")[3];
        td2 = tableRow[t].getElementsByTagName("td")[4];
        tdpid = tableRow[t].getElementsByTagName("td")[2];
       // console.log(td);

        if (td) {
          txtValue = td.textContent || td.innerText;
          txtValue2 = td2.textContent || td2.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tableRow[t].style.display = "";
            val_brand_ids.push(tdpid.innerText);
          } else if (txtValue2.toUpperCase().indexOf(filter) > -1){
              tableRow[t].style.display = "";
              val_brand_ids.push(tdpid.innerText);
          }else{
             tableRow[t].style.display = "none";
          }
        }       
        
    }
     for (var y = 0; y < val_brand_ids.length; y++){
         $("#product-div_"+val_brand_ids[y]).find('input[type=checkbox]').each(function () { this.checked = true; });   
     }
    //console.log(chk_brand_ids); 
   
  }
  function searchProductName(pid) {
    $("#unselect_all_"+pid).css("display","none")
    $("#unselect_all_span"+pid).css("display","none")
    $("#select_all_"+pid).css("display","none")
    $("#select_all_span"+pid).css("display","none")
    $("#select_all_src"+pid).css("display","block")
    $("#select_all_src_span"+pid).css("display","block")
    $("#unselect_all_src"+pid).css("display","none")
    $("#unselect_all_src_span"+pid).css("display","none")

  // Declare variables
  //alert(pid);
    var input, filter, table, tr, td,td2, i, txtValue,txtValue2;
    input = document.getElementById("InputsearchVal_"+pid);
    filter = input.value.toUpperCase().trim();
    table = document.getElementById("seller_products");
    var tBody = table.tBodies.namedItem("accordion_"+pid);
    var tableRow = tBody.getElementsByTagName('tr');
    for (var t = 0; t < tableRow.length; t++){
        td = tableRow[t].getElementsByTagName("td")[3];
        td2 = tableRow[t].getElementsByTagName("td")[4];
       // console.log(td);

        if (td) {
          txtValue = td.textContent || td.innerText;
          txtValue2 = td2.textContent || td2.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tableRow[t].style.display = "";
          } else if (txtValue2.toUpperCase().indexOf(filter) > -1){
              tableRow[t].style.display = "";
          }else{
             tableRow[t].style.display = "none";
          }
        }       
        
    }
  }
  $(document).ready(function() {
    $("#save_changes").click(function(){
      $('#save_changes').prop('disabled', true);
      $('#PrdBatchAC').modal('show');
    });
    $("#PrdSaveChangesAc").click(function(){
      $('#PrdSaveChangesAc').prop('disabled', true);
      updateProduct_status();
    });
    $("#PrdSaveChangesQty").click(function(){
      $('#PrdSaveChangesQty').prop('disabled', true);
      updateProduct_QTY();
    });
    $("#PrdSaveChangesCI").click(function(){
      $('#PrdSaveChangesCI').prop('disabled', true);
      updateProduct_CI();
    });
    $("#PrdSaveChangesCOD").click(function(){
      $('#PrdSaveChangesCOD').prop('disabled', true);
      updateProduct_COD();
    });
    
    $("#SaveFreebieS").click(function(){
      $('#SaveFreebieS').prop('disabled', true);
      add_PrdSaveFreebies();
    });
    $("#btndelfeebies").click(function(){
      $('#btndelfeebies').prop('disabled', true);
      deleteFreebies();
    });
    $("#btnBatchQTY").click(function(){
      $('#PrdBatchQTYMod').modal('show');
      $('#btnBatchQTY').prop('disabled', true);
    }); 
    $("#btnBatchCI").click(function(){
      $('#PrdBatchCIMod').modal('show');
      $('#btnBatchCI').prop('disabled', true);
    }); 
    $("#btnBatchCOD").click(function(){
      $('#PrdBatchCODMod').modal('show');
      $('#btnBatchCOD').prop('disabled', true);
    });
   
    $("#btnAddfeebies").click(function(){
      $('#MODAddfeebies').modal('show');
      $('#btnAddfeebies').prop('disabled', true);
    });
  });
</script>