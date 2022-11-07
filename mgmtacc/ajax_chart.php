<?php
require 'model/Chart.php';
global $chart;
if(isset($_GET['type'])){
    $type = $_GET['type'];
    if($type == 'order'){
        if(isset($_GET['period'])){
            switch($_GET['period']){
                case 'daily':
                    echo json_encode($chart->getOrderDaily());
                break;
                case 'monthly':
                    echo json_encode($chart->getOrderMonthly());
                break;
                case 'yearly':
                    echo json_encode($chart->getOrderYearly());
                break;
            }
          
        }
    }else if($type == 'member'){
        if(isset($_GET['period'])){
            switch($_GET['period']){
                case 'daily':
                    echo json_encode($chart->getMemberDaily());
                break;
                case 'monthly':
                    echo json_encode($chart->getMemberMonthly());
                break;
                case 'yearly':
                    echo json_encode($chart->getMemberYearly());
                break;
            }
          
        }
    }else if($type == 'view'){
        if(isset($_GET['period'])){
            switch($_GET['period']){
                case 'daily':
                    echo json_encode($chart->getViewDaily());
                break;
                case 'hourly':
                    echo json_encode($chart->getViewHourly());
                break;
                case 'hourly/m':
                    echo json_encode($chart->getViewHourlyM());
                break;
                case 'hourly/d':
                    echo json_encode($chart->getViewHourlyD());
                break;
                case 'hourly/y':
                    echo json_encode($chart->getViewHourlyY());
                break;
                case 'monthly':
                    echo json_encode($chart->getViewMonthly());
                break;
                case 'yearly':
                    echo json_encode($chart->getViewYearly());
                break;
              
            }
          
        }
    }
}
