<?php
require_once "../include/database.php";
class Chart {
    private $conn;
    private $orderReceive = [20, 43, 49, 50];
    private $orderCancel = [27,31, 48];
    public function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }

    public function getAllDay(){
        $order_data = array();
        for ($i = 1; $i <= date('t'); $i++) {
            $date = date('Y') . '-' . date('m') . '-' . $i;
            $order_data[] = date('d', strtotime($date));
        }
        return $order_data;
    }
    public function getAllMonth(){
        $order_data = array();
        for ($i = 1; $i <= 12; $i++) {
            $order_data[] = date('M', mktime(0, 0, 0, $i));
        }
        return $order_data;
    }
    public function getAllYear(){
        $data = array();
        $stmt = $this->conn->prepare("SELECT DISTINCT YEAR(date_added) as year from oc_customer");
        $stmt->execute();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $res){
            $data[] = $res['year'];
        }
        return $data;
    }
    public function getAllHourForCustomerView(){
        $data = array();
        for ($i = 0; $i < 24; $i++) {
            $data[] = $i;
        }
        return $data;

        
    }
    public function getAllYearForCustomerView(){
        $data = array();
        $stmt = $this->conn->prepare("SELECT DISTINCT YEAR(date_viewed) as year from customer_views");
        $stmt->execute();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $res){
            $data[] = $res['year'];
        }
        return $data;
    }
    public function getOrderDaily(){
        $data = array(
                'type' => 'bar',
                'data' => array(
                'labels' => $this->getAllDay(),
                'datasets' => array(
                        array(
                        'label' => 'Order Received',
                        'data' => $this->getTotalOrderByDaily($this->orderReceive),
                        'backgroundColor' => '#1ABC9C',
                        ),
                        array(
                        'label' => 'Order Cancelled',
                        'data' => $this->getTotalOrderByDaily($this->orderCancel),
                        'backgroundColor' => '#E74C3C',
                        ),
                )
                ),

        );  
        return $data;
    }
    public function getOrderMonthly(){
        $data = array(
                'type' => 'bar',
                'data' => array(
                'labels' => $this->getAllMonth(),
                'datasets' => array(
                        array(
                        'label' => 'Order Received',
                        'data' => $this->getTotalOrderByMonthly($this->orderReceive),
                        'backgroundColor' => '#1ABC9C',
                        ),
                        array(
                        'label' => 'Order Cancelled',
                        'data' => $this->getTotalOrderByMonthly($this->orderCancel),
                        'backgroundColor' => '#E74C3C',
                        ),
                )
                ),

        );  
        return $data;
    }
    public function getOrderYearly(){
        $data = array(
                'type' => 'bar',
                'data' => array(
                'labels' => $this->getAllYear(),
                'datasets' => array(
                        array(
                        'label' => 'Order Received',
                        'data' => $this->getTotalOrderByYearly($this->orderReceive),
                        'backgroundColor' => '#1ABC9C',
                        ),
                        array(
                        'label' => 'Order Cancelled',
                        'data' => $this->getTotalOrderByYearly($this->orderCancel),
                        'backgroundColor' => '#E74C3C',
                        ),
                )
                ),

        );  
        return $data;
    }
    public function getMemberDaily(){
        $data = array(
            'type' => 'line',
            'data' => array(
            'labels' => $this->getAllDay(),
            'datasets' => array(
                    array(
                    'label' => 'Member Registered',
                    'data' => $this->getTotalMemberByDaily(),
                    'backgroundColor' => '#3498DB',
                    )
            )
            ),

        );  
        return $data;
    }
    public function getMemberMonthly(){
        $data = array(
            'type' => 'line',
            'data' => array(
            'labels' => $this->getAllMonth(),
            'datasets' => array(
                    array(
                    'label' => 'Member Registered',
                    'data' => $this->getTotalMemberByMonthly(),
                    'backgroundColor' => '#3498DB',
                    )
            )
            ),

        );  
        return $data;
    }
    public function getMemberYearly(){
        $data = array(
            'type' => 'line',
            'data' => array(
            'labels' => $this->getAllYear(),
            'datasets' => array(
                    array(
                    'label' => 'Member Registered',
                    'data' => $this->getTotalMemberByYearly(),
                    'backgroundColor' => '#3498DB',
                    )
            )
            ),

        );  
        return $data;
    }
    public function getViewDaily(){
        $data = array(
            'type' => 'line',
            'data' => array(
            'labels' => $this->getAllDay(),
            'datasets' => array(
                    array(
                    'lineTension' => 0,
                    'label' => 'Website Users',
                    'data' => $this->getTotalViewByDaily('web'),
                    'borderColor' => '#00B5EF',
                    'backgroundColor' => 'rgba(0,0,0,0)'
                    ),
                    array(
                    'lineTension' => 0,
                    'label' => 'Android Users',
                    'data' => $this->getTotalViewByDaily('android'),
                    'borderColor' => '#3BC157',
                    'backgroundColor' => 'rgba(0,0,0,0)'
                    ),
                    array(
                    'lineTension' => 0,
                    'label' => 'IOS Users',
                    'data' => $this->getTotalViewByDaily('ios'),
                    'borderColor' => '#A5A5A5',
                    'backgroundColor' => 'rgba(0,0,0,0)'
                    )
            )
            )

        );  
        return $data;
    }
    public function getViewMonthly(){
        $data = array(
            'type' => 'line',
            'data' => array(
            'labels' => $this->getAllMonth(),
            'datasets' => array(
                    array(
                    'lineTension' => 0,
                    'label' => 'Website Users',
                    'data' => $this->getTotalViewByMonthly('web'),
                    'borderColor' => '#00B5EF',
                    'backgroundColor' => 'rgba(0,0,0,0)'
                    ),
                    array(
                    'lineTension' => 0,
                    'label' => 'Android Users',
                    'data' => $this->getTotalViewByMonthly('android'),
                    'borderColor' => '#3BC157',
                    'backgroundColor' => 'rgba(0,0,0,0)'
                    ),
                    array(
                    'lineTension' => 0,
                    'label' => 'IOS Users',
                    'data' => $this->getTotalViewByMonthly('ios'),
                    'borderColor' => '#A5A5A5',
                    'backgroundColor' => 'rgba(0,0,0,0)'
                    )
            )
            )

        );  
        return $data;
    }
    public function getViewYearly(){
        $data = array(
            'type' => 'line',
            'data' => array(
            'labels' => $this->getAllYearForCustomerView(),
            'datasets' => array(
                    array(
                    'lineTension' => 0,
                    'label' => 'Website Users',
                    'data' => $this->getTotalViewByYearly('web'),
                    'borderColor' => '#00B5EF',
                    'backgroundColor' => 'rgba(0,0,0,0)'
                    ),
                    array(
                    'lineTension' => 0,
                    'label' => 'Android Users',
                    'data' => $this->getTotalViewByYearly('android'),
                    'borderColor' => '#3BC157',
                    'backgroundColor' => 'rgba(0,0,0,0)'
                    ),
                    array(
                    'lineTension' => 0,
                    'label' => 'IOS Users',
                    'data' => $this->getTotalViewByYearly('ios'),
                    'borderColor' => '#A5A5A5',
                    'backgroundColor' => 'rgba(0,0,0,0)'
                    )
            )
            )

        );  
        return $data;
    }

    public function getViewHourly(){
        $data = array(
            'type' => 'line',
            'data' => array(
            'labels' => $this->getAllHourForCustomerView(),
            'datasets' => array(
                    array(
                    'lineTension' => 0,
                    'label' => 'Website Users',
                    'data' => $this->getTotalViewByHourly('web'),
                    'borderColor' => '#00B5EF',
                    'backgroundColor' => 'rgba(0,0,0,0)'
                    ),
                    array(
                    'lineTension' => 0,
                    'label' => 'Android Users',
                    'data' => $this->getTotalViewByHourly('android'),
                    'borderColor' => '#3BC157',
                    'backgroundColor' => 'rgba(0,0,0,0)'
                    ),
                    array(
                    'lineTension' => 0,
                    'label' => 'IOS Users',
                    'data' => $this->getTotalViewByHourly('ios'),
                    'borderColor' => '#A5A5A5',
                    'backgroundColor' => 'rgba(0,0,0,0)'
                    )
            )
            )

        );  
        return $data;
    }

    public function getViewHourlyM(){
        $data = array(
            'type' => 'line',
            'data' => array(
            'labels' => $this->getAllHourForCustomerView(),
            'datasets' => array(
                    array(
                    'lineTension' => 0,
                    'label' => 'Website Users',
                    'data' => $this->getTotalViewByHourlyM('web'),
                    'borderColor' => '#00B5EF',
                    'backgroundColor' => 'rgba(0,0,0,0)'
                    ),
                    array(
                    'lineTension' => 0,
                    'label' => 'Android Users',
                    'data' => $this->getTotalViewByHourlyM('android'),
                    'borderColor' => '#3BC157',
                    'backgroundColor' => 'rgba(0,0,0,0)'
                    ),
                    array(
                    'lineTension' => 0,
                    'label' => 'IOS Users',
                    'data' => $this->getTotalViewByHourlyM('ios'),
                    'borderColor' => '#A5A5A5',
                    'backgroundColor' => 'rgba(0,0,0,0)'
                    )
            )
            )

        );  
        return $data;
    }
    public function getViewHourlyY(){
        $data = array(
            'type' => 'line',
            'data' => array(
            'labels' => $this->getAllHourForCustomerView(),
            'datasets' => array(
                    array(
                    'lineTension' => 0,
                    'label' => 'Website Users',
                    'data' => $this->getTotalViewByHourlyY('web'),
                    'borderColor' => '#00B5EF',
                    'backgroundColor' => 'rgba(0,0,0,0)'
                    ),
                    array(
                    'lineTension' => 0,
                    'label' => 'Android Users',
                    'data' => $this->getTotalViewByHourlyY('android'),
                    'borderColor' => '#3BC157',
                    'backgroundColor' => 'rgba(0,0,0,0)'
                    ),
                    array(
                    'lineTension' => 0,
                    'label' => 'IOS Users',
                    'data' => $this->getTotalViewByHourlyY('ios'),
                    'borderColor' => '#A5A5A5',
                    'backgroundColor' => 'rgba(0,0,0,0)'
                    )
            )
            )

        );  
        return $data;
    }
    public function getViewHourlyD(){
        $data = array(
            'type' => 'line',
            'data' => array(
            'labels' => $this->getAllHourForCustomerView(),
            'datasets' => array(
                    array(
                    'lineTension' => 0,
                    'label' => 'Website Users',
                    'data' => $this->getTotalViewByHourlyD('web'),
                    'borderColor' => '#00B5EF',
                    'backgroundColor' => 'rgba(0,0,0,0)'
                    ),
                    array(
                    'lineTension' => 0,
                    'label' => 'Android Users',
                    'data' => $this->getTotalViewByHourlyD('android'),
                    'borderColor' => '#3BC157',
                    'backgroundColor' => 'rgba(0,0,0,0)'
                    ),
                    array(
                    'lineTension' => 0,
                    'label' => 'IOS Users',
                    'data' => $this->getTotalViewByHourlyD('ios'),
                    'borderColor' => '#A5A5A5',
                    'backgroundColor' => 'rgba(0,0,0,0)'
                    )
            )
            )

        );  
        return $data;
    }
    public function getTotalOrderByDaily($order_status_id){
        $order_data = array();
        $data = array();

        for ($i = 1; $i <= date('t'); $i++) {
            $date = date('Y') . '-' . date('m') . '-' . $i;
            $order_data[date('j', strtotime($date))] = array(
                'day'   => date('d', strtotime($date)),
                'total' => 0
            );
        }
        $implode = array();
        if(is_array($order_status_id)){
            foreach($order_status_id as $id){
                $implode[] = (int)$id;
            }
            $sql = "o.order_status_id IN(:order_status_id)";
            $param = implode(",", $implode);
        }else{
            $sql = 'o.order_status_id = :order_status_id';
            $param = (int) $order_status_id;
        }
        $query = "SELECT SUM((SELECT oot.value from oc_order_total oot where oot.order_id= o.order_id and title='Total')) AS total, DATE(o.date_added) as date_added FROM oc_order o WHERE o.order_status_id IN($param) AND DATE(o.date_added) >= :date_added GROUP BY DATE(o.date_added)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':date_added', date('Y') . '-' . date('m') . '-1');
        $stmt->execute();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $res){
            $order_data[date('j', strtotime($res['date_added']))] = array(
                'day'   => date('d', strtotime($res['date_added'])),
                'total' => $res['total']
            );
        }
        foreach($order_data as $d){
            $data[] = $d['total'];
        }
        return $data;
    }
    public function getTotalOrderByMonthly($order_status_id){
        $order_data = array();
        $data = array();

        for ($i = 1; $i <= 12; $i++) {
            // $date = date('Y') . '-' . date('m') . '-' . $i;
            $order_data[$i] = array(
                'month'   => $i,
                'total' => 0
            );
        }
        $implode = array();
        if(is_array($order_status_id)){
            foreach($order_status_id as $id){
                $implode[] = $id;
            }
            $sql = "o.order_status_id IN(:order_status_id)";
            $param = implode(",", $implode);
        }else{
            $sql = 'o.order_status_id = :order_status_id';
            $param = (int) $order_status_id;
        }
        
        $stmt = $this->conn->prepare("SELECT SUM((SELECT oot.value from oc_order_total oot where oot.order_id= o.order_id and title='Total')) AS total, DATE(o.date_added) as date_added FROM oc_order o WHERE YEAR(o.date_added) = YEAR(convert_tz(utc_timestamp(),'-08:00','+0:00')) AND o.order_status_id IN($param) GROUP BY DATE(o.date_added)");
        $stmt->execute();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $res){
            $monthNumber = date('n', strtotime($res['date_added']));
            if(array_key_exists($monthNumber, $order_data)){
                $order_data[$monthNumber]['total'] += $res['total'];
            }else{
                $order_data[$monthNumber] = array(
                'month' => date('M', strtotime($res['date_added'])),
                'total' => $res['total']
                );
            }
        }
        foreach($order_data as $d){
            $data[] = number_format($d['total'], 2, '.', '');
        }
        return $data;
    }
    public function getTotalOrderByYearly($order_status_id){
        $data = array();
        foreach($this->getAllYear() as $year){
            $implode = array();
            if(is_array($order_status_id)){
                foreach($order_status_id as $id){
                    $implode[] = (int)$id;
                }
                $sql = "o.order_status_id IN(:order_status_id)";
                $param = implode(",", $implode);
            }else{
                $sql = 'o.order_status_id = :order_status_id';
                $param = (int) $order_status_id;
            }
            $stmt = $this->conn->prepare("SELECT SUM((SELECT oot.value from oc_order_total oot where oot.order_id= o.order_id and title='Total')) total 
            from oc_order o where YEAR(o.date_added) = :year AND o.order_status_id IN($param) GROUP BY YEAR(o.date_added)");
            $stmt->bindValue(':year', (int) $year);
            // $stmt->bindValue(':order_status_id', (int) $param);
            $stmt->execute();
            $data[] = $stmt->fetch()['total'];
        }
        return $data;
       
    }
    public function getTotalMemberByDaily(){
        $customer_data = array();

        for ($i = 1; $i <= date('t'); $i++) {
            $date = date('Y') . '-' . date('m') . '-' . $i;

            $customer_data[date('j', strtotime($date))] = array(
                'day'   => date('d', strtotime($date)),
                'total' => 0
            );
        }

        $query = $this->conn->prepare("SELECT COUNT(customer_id) AS total, DATE(date_added) as date_added FROM oc_customer WHERE DATE(date_added) >=  :date_added GROUP BY DATE(date_added)");
        $query->bindValue(':date_added', date('Y') . '-' . date('m') . '-1');
        $query->execute();

        foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $result) {
            $customer_data[date('j', strtotime($result['date_added']))] = array(
                'day'   => date('d', strtotime($result['date_added'])),
                'total' => $result['total']
            );
        }
        foreach($customer_data as $d){
            $data[] = $d['total'];
        }
        return $data;
    }
    public function getTotalMemberByMonthly(){
        $customer_data = array();
        $data = array();
        for ($i = 1; $i <= 12; $i++) {
            $customer_data[$i] = array(
                'month' => date('M', mktime(0, 0, 0, $i)),
                'total' => 0
            );
        }

        $query = $this->conn->prepare("SELECT COUNT(*) AS total, DATE(date_added) as date_added FROM oc_customer WHERE YEAR(date_added) = YEAR(convert_tz(utc_timestamp(),'-08:00','+0:00')) GROUP BY DATE(date_added)");
        $query->execute();

        foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $res) {
            $monthNumber = date('n', strtotime($res['date_added']));
            if(array_key_exists($monthNumber, $customer_data)){
                $customer_data[$monthNumber]['total'] += $res['total'];
            }else{
                $order_data[$monthNumber] = array(
                'month' => date('M', strtotime($res['date_added'])),
                'total' => $res['total']
                );
            }
            
            
            
            // $customer_data[date('n', strtotime($result['date_added']))] = array(
            //     'month' => date('M', strtotime($result['date_added'])),
            //     'total' => $result['total']
            // );
        }
        foreach($customer_data as $d){
            $data[] = $d['total'];
        }
        return $data;
    }
    public function getTotalMemberByYearly(){
        $data = array();
        foreach($this->getAllYear() as $year){
            $stmt = $this->conn->prepare("SELECT COUNT(*) as total 
            from oc_customer where YEAR(date_added) = :year");
            $stmt->bindValue(':year', (int) $year);
            $stmt->execute();
            $data[] = $stmt->fetch()['total'];
        }
        return $data;
    }
    public function getTotalViewByDaily($platform){
        $customer_data = array();

        for ($i = 1; $i <= date('t'); $i++) {
            $date = date('Y') . '-' . date('m') . '-' . $i;

            $customer_data[date('j', strtotime($date))] = array(
                'day'   => date('d', strtotime($date)),
                'total' => 0
            );
        }

        $query = $this->conn->prepare("SELECT COUNT(id) AS total, DATE(date_viewed) as date_viewed FROM customer_views WHERE DATE(date_viewed) >= :date_viewed AND platform = :platform GROUP BY DATE(date_viewed), id");
        $query->bindValue(':date_viewed', date('Y') . '-' . date('m') . '-1');
        $query->bindValue(':platform', $platform);
        $query->execute();

        foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $result) {
            $customer_data[date('j', strtotime($result['date_viewed']))] = array(
                'day'   => date('d', strtotime($result['date_viewed'])),
                'total' => $result['total']
            );
        }
        foreach($customer_data as $d){
            $data[] = $d['total'];
        }
        return $data;
    }
    public function getTotalViewByMonthly($platform){

        $customer_data = array();
        $data = array();
        for ($i = 1; $i <= 12; $i++) {
            $customer_data[$i] = array(
                'month' => date('M', mktime(0, 0, 0, $i)),
                'total' => 0
            );
        }

        $query = $this->conn->prepare("SELECT COUNT(*) AS total, DATE(date_viewed) as date_viewed FROM customer_views WHERE  YEAR(date_viewed) = YEAR(convert_tz(utc_timestamp(),'-08:00','+0:00')) AND platform = :platform GROUP BY DATE(date_viewed)");
        $query->bindValue(':platform', $platform);
        $query->execute();

        foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $res) {
            $monthNumber = date('n', strtotime($res['date_viewed']));
            if(array_key_exists($monthNumber, $customer_data)){
                $customer_data[$monthNumber]['total'] += $res['total'];
            }else{
                $order_data[$monthNumber] = array(
                'month' => date('M', strtotime($res['date_viewed'])),
                'total' => $res['total']
                );
            }
        }
        foreach($customer_data as $d){
            $data[] = $d['total'];
        }
        return $data;
    }
    public function getTotalViewByYearly($platform){
        $data = array();
        foreach($this->getAllYearForCustomerView() as $year){
            $stmt = $this->conn->prepare("SELECT COUNT(*) as total 
            from customer_views_hourly where YEAR(date_viewed) = :year AND platform = :platform");
            $stmt->bindValue(':year', (int) $year);
            $stmt->bindValue(':platform', $platform);
            $stmt->execute();
            $data[] = $stmt->fetch()['total'];
        }
        return $data;
    }
    public function getTotalViewByHourly($platform){
        $data = array();
        $dataarray = array();
        for ($i = 0; $i < 24; $i++) {
            $dataarray[] = $i;
        }
        foreach($dataarray as $hour){
            $stmt = $this->conn->prepare("SELECT COUNT(*) as total 
            from customer_views_hourly where HOUR(date_viewed) = :hour AND platform = :platform");
            $stmt->bindValue(':hour', (int) $hour);
            $stmt->bindValue(':platform', $platform);
            $stmt->execute();
            $data[] = $stmt->fetch()['total'];
            //echo $hour . '-';
        }
        return $data;
    }
    public function getTotalViewByHourlyM($platform){
        $data = array();
        $dataarray = array();
        for ($i = 0; $i < 24; $i++) {
            $dataarray[] = $i;
        }
        foreach($dataarray as $hour){
            $stmt = $this->conn->prepare("SELECT COUNT(*) as total 
            from customer_views_hourly where HOUR(date_viewed) = :hour AND platform = :platform AND MONTH(date_viewed)=MONTH(convert_tz(utc_timestamp(),'-08:00','+0:00'))");
            $stmt->bindValue(':hour', (int) $hour);
            $stmt->bindValue(':platform', $platform);
            $stmt->execute();
            $data[] = $stmt->fetch()['total'];
            //echo $hour . '-';
        }
        return $data;
    }
    public function getTotalViewByHourlyD($platform){
        $data = array();
        $dataarray = array();
        for ($i = 0; $i < 24; $i++) {
            $dataarray[] = $i;
        }
        foreach($dataarray as $hour){
            $stmt = $this->conn->prepare("SELECT COUNT(*) as total 
            from customer_views_hourly where HOUR(date_viewed) = :hour AND platform = :platform AND DATE(date_viewed)=DATE(convert_tz(utc_timestamp(),'-08:00','+0:00'))");
            $stmt->bindValue(':hour', (int) $hour);
            $stmt->bindValue(':platform', $platform);
            $stmt->execute();
            $data[] = $stmt->fetch()['total'];
            //echo $hour . '-';
        }
        return $data;
    }
    public function getTotalViewByHourlyY($platform){
        $data = array();
        $dataarray = array();
        for ($i = 0; $i < 24; $i++) {
            $dataarray[] = $i;
        }
        foreach($dataarray as $hour){
            $stmt = $this->conn->prepare("SELECT COUNT(*) as total 
            from customer_views_hourly where HOUR(date_viewed) = :hour AND platform = :platform AND YEAR(date_viewed)=YEAR(convert_tz(utc_timestamp(),'-08:00','+0:00'))");
            $stmt->bindValue(':hour', (int) $hour);
            $stmt->bindValue(':platform', $platform);
            $stmt->execute();
            $data[] = $stmt->fetch()['total'];
            //echo $hour . '-';
        }
        return $data;
    }
}
$chart = new Chart();