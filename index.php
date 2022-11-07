<?php
if(isset($_GET['route'])){
    $data = array(
        array(
            'image'=> 'https://pesoapp.ph/img/customer_message.png',
            'routeOne' => '',
            'routeTwo' => '',
            'linkId' => ''
        )
    );

    echo json_encode($data);

}else{
    header("location: home.php");
}

?>