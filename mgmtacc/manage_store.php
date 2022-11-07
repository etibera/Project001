<?php
include 'template/header.php';
include "model/manage_store.php";
$store = new manage_store(); 
$storeList = $store->GetAllStore();
?>
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 55px;
  height: 25px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 20px;
  width: 20px;
  left: 4px;
  bottom: 3px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #3bc157;
}

input:focus + .slider {
  box-shadow: 0 0 1px #3bc157;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<div id="content">
  <div class="page-header">
    <h2 class="text-center">Manage Store</h2>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading" style="padding:20px;">
        <div class="row">
          <div class="col-lg-3">
            <p style="font-weight: 700;" class="panel-title"> <i class="fa fa-list"></i>  Store List
            </p>
          </div>
     </div>
      </div>
      <div class="panel-body">
        <?php if(isset($errorMsg)){ ?>
            <div class="alert alert-danger">
                <?php foreach ($errorMsg as $error) : ?>  
                        <strong><?php echo $error['name']?></strong></br>
                <?php  endforeach;?>
            </div>
        <?php } ?>
            <?php if(isset($sMsg)){ ?>
            <div class="alert alert-success">
                <strong><?php echo $sMsg;?></strong></br>
            </div>
        <?php } ?>
        <?php if(isset($sMsg_failed)){ ?>
            <div class="alert alert-danger">
                <strong><?php echo $sMsg_failed;?></strong></br>
            </div>
        <?php } ?>
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="deliverytb">
            <thead>
              <tr>
                <th>Store Logo </th>
                <th>Store Name</th>
                <th>Email</th>
                <th>Status</th>
                <th><!-- <input type="checkbox"   onchange="setStatus2(1,1,event)" > --></th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($storeList as $sl): $unread=$store->getSellerUnreadMessage($sl['seller_id']);?>
              <tr>
                <td class="text-left" ><img src="<?php echo $sl['thumb'];?>" style="width: 50px;height: 50px" style="margin:auto"/></td>
                <td class="text-left" ><?php echo $sl['shop_name'];?></td>
                <td class="text-left" ><?php echo $sl['email'];?></td>                 
                <td  class="text-center">
                  <label class="switch">
                  <input type="checkbox"  <?php if($sl['status']==1){echo "checked"; }?>  onchange="setStatus(<?php echo $sl['seller_id'];?>,<?php echo $sl['seller_id'];?>,event)" >
                  <span class='slider round'></span><br><span id="s-label-<?php echo $sl['seller_id'];?>"><?php if($sl['status']==1){echo "Enabled";}else{echo "Disabled";} ?></span>
                 </label>
                </td> 
                <?php $perm = $_SESSION['permission']; if (strpos($perm, "'252525';")){ ?>
                   <td>
                    <a class="btn btn-sm btn-primary" title="Login" href="../mgmtseller/authadmin.php?seller_id=<?php echo $sl['seller_id'];?>&admin=<?php echo uniqid();?>" target="_blank">
                          <i data-feather="key"></i>
                    </a>
                    <a class="btn btn-sm btn-warning notification" title="Messages" href="sellerMessagesList.php?seller_id=<?php echo $sl['seller_id'];?>&admin=<?php echo uniqid();?>">Messages
                      <?php if($unread){ ?>
                        <span class="badge"><?php echo $unread;?></span>
                      <?php }?> 
                    </a>
                   </td>   
                <?php  }?>
              </tr>
            <?php endforeach;?>
            </tbody>          
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include 'template/footer.php';?>
<script type="text/javascript">
  function setStatus(id, index, event){
    setTimeout(() => {
        var checked = event.target.checked ? 'Enabled' : 'Disabled'
        $('#s-label-'+ index).text(checked)
        $.post({url: "ajx_ppp_rep.php?action=update_store_status", 
                data: {seller_id: id, status: (event.target.checked ? 1 : 0)},
                success: function(result){
                  location.reload();
                }
        });
    }, 500);
}
 /*function setStatus2(id, index, event){
    setTimeout(() => {
       
        $.post({url: "https://www.pinoyelectronicstore.com/mgmtacc/ajx_ppp_rep.php?action=sample_ip", 
                data: {seller_id: id, status: (event.target.checked ? 1 : 0)},
                success: function(result){
                   console.log(result );
                }
        });
    }, 500);
}*/
</script>