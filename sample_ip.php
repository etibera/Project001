
<!DOCTYPE html>
<html>
<body>



</body>
</html>
<script>
$(document).ready(function() {
   setStatus();
});

  function setStatus(){
   $.post({url: "https://www.pinoyelectronicstore.com/mgmtacc/ajx_ppp_rep.php?action=sample_ip",
                success: function(result){
                 //alert(result['success']);
                  console.log(result );
                }
        });
}
</script>