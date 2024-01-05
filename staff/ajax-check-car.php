<?php
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once 'authcheck.php';

if (isset($_POST['cardetail'])){
    $car_detail = $_POST['cardetail'];
    $car_id = $car_detail['car_id'];
    $sql="SELECT rental_price FROM car WHERE car_id=".$car_id;
    $query = $link->query($sql);
    while ($row = mysqli_fetch_assoc($query)) {
 
    $results[] = $row;
}
    
        echo json_encode($results);
}