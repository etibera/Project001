<style type="text/css">
  @import url('https://fonts.googleapis.com/css?family=Lato:400,700|Montserrat:900');


 
 #timer {
   color: #f6f4f3;
   
   text-transform: uppercase;
   font-family: 'Lato', sans-serif;
   font-size: 8px;
   letter-spacing: 5px;
   margin: 0;
  
}
 .days, .hours, .minutes, .seconds {
   display: inline-block;
   padding: 2px;
   width: 70px;
   border-radius: 5px;
   text-align: center;
}
 .days {
   background: #fa1c05;
}
 .hours {
   background: #fa1c05;
}
 .minutes {
   background: #fa1c05;
}
 .seconds {
   background: #fa1c05;
}
 .numbers {
   font-family: 'Montserrat', sans-serif;
   font-size: 4em;
}

 
 
</style>

  <div id="timer"></div>
<?php $promodate="2021-10-15 23:59:59";?>
<script type="text/javascript">
  var promodate='<?php echo $promodate?>';
 var countDownDate = new Date(promodate).getTime();
 let timer = setInterval(function() {
     var now = new Date().getTime();
     var distance = countDownDate - now;
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);

      // display
      document.getElementById("timer").innerHTML =
        "<div class=\"days\"> \
        <div class=\"numbers\">" + days + "</div>days</div> \
        <div class=\"hours\"> \
        <div class=\"numbers\">" + hours + "</div>hours</div> \
        <div class=\"minutes\"> \
        <div class=\"numbers\">" + minutes + "</div>minutes</div> \
        <div class=\"seconds\"> \
        <div class=\"numbers\">" + seconds + "</div>seconds</div> \
        </div>";

  }, 1000);


</script>