<div class="row h-100">
<div class="container">
<div class="col-md-6">
<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#order">Customer Orders</a></li>
    <li><a data-toggle="tab" href="#membership">Membership</a></li>
    <li><a data-toggle="tab" href="#customer">Customer Views</a></li>
  </ul>
  <div class="tab-content" style="margin-top: 20px">
		<div id="order" class="tab-pane fade in active">
		<div class="container">
        <div class="row">
            <div class="col-md-2 pull-right">
                    <select class="form-control" id="orderGraph">
                    <option value="daily">Daily</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
        </div>
		<canvas id="orderDaily" width="100" height="30"></canvas>
		</div>
		</div>
		<div id="membership" class="tab-pane fade">
        <div class="container">
        <div class="row">
            <div class="col-md-2 pull-right">
                    <select class="form-control" id="memberGraph">
                    <option value="daily">Daily</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
        </div>
		<canvas id="memberChart" width="100" height="30"></canvas>
		</div>
		</div>
        <div id="customer" class="tab-pane fade">
        <div class="container">
        <div class="row">
            <div class="col-md-2 pull-right">
                    <select class="form-control" id="viewGraph">
                    <option value="daily">Daily</option>
                    <option value="hourly">Hourly</option>
                    <option value="hourly/y">Hourly/Year</option>
                    <option value="hourly/m">Hourly/Month</option>
                    <option value="hourly/d">Hourly/Today</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
        </div>
		<canvas id="viewChart" width="100" height="30"></canvas>
		</div>
		</div>
  </div>

</div>
</div>
</div>
<script>
$(document).ready(function() {
    
    graph('orderDaily','order', 'daily');
    graph('memberChart','member', 'daily');
    graph('viewChart','view', 'daily');

    $('#orderGraph').on('change', function(){
        graph('orderDaily','order', $(this).val())
    });
    $('#memberGraph').on('change', function(){
        graph('memberChart','member', $(this).val())
    });
    $('#viewGraph').on('change', function(){
        graph('viewChart','view', $(this).val())
    });

    function graph(id, type, period){
      $.ajax({
            url: 'ajax_chart.php',
            type: 'GET',
            data: 'type='+type+'&period='+period+'&t='+new Date().getTime(),
            dataType: 'text',
            success: function(json) {
                if(type =='order'){
                    if(window.myOrderChart != undefined){
                        window.myOrderChart.destroy();
                    }
                        window.myOrderChart = new Chart($('#'+ id), JSON.parse(json));
                }else if(type == 'member'){
                    if(window.myMemberChart != undefined){
                        window.myMemberChart.destroy();
                    }
                        window.myMemberChart = new Chart($('#'+ id), JSON.parse(json));
                }
                else if(type == 'view'){
                    if(window.myViewChart != undefined){
                        window.myViewChart.destroy();
                    }
                        window.myViewChart = new Chart($('#'+ id), JSON.parse(json));
                }
              
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
          	});
    }
});

</script>