<?php
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once 'authcheck.php';

$loader = new \Twig\Loader\FilesystemLoader('views');

$twig = new \Twig\Environment($loader);
$twig->addGlobal('session', $_SESSION);

// $return ='';


if (isset($_POST['rentalstartdate'])){
    $rentailstartdate = $_POST['rentalstartdate'];
    $car_id = $rentailstartdate['car_id'];
    $rental_start_date = $rentailstartdate['rental_start_date'];
    $sql="SELECT rental_id FROM rental WHERE car_id=".$car_id. " AND '".$rental_start_date."' BETWEEN rental_start_date AND rental_end_date";
    $query = $link->query($sql);
    $return =array();
    if (mysqli_num_rows($query)>0){
        $return=array(
            "valid" => false,
            "err_msg" => "Date already occupided",
        );
    }
    else{
        $return=array(
            "valid" => true,
            "err_msg" => "",
        );
    }
     echo json_encode($return);
}
if (isset($_POST['rentalenddate'])){
    $rentalenddate = $_POST['rentalenddate'];
    $car_id = $rentalenddate['car_id'];
    $rental_end_date = $rentalenddate['rental_end_date'];
    $sql="SELECT rental_id FROM rental WHERE car_id=".$car_id. " AND '".$rental_end_date."' BETWEEN rental_start_date AND rental_end_date";
    $query = $link->query($sql);
    $return =array();
    if (mysqli_num_rows($query)>0){
        $return=array(
            "valid" => false,
            "err_msg" => "Date already occupided",
        );
    }
    else{
        $return=array(
            "valid" => true,
            "err_msg" => "",
        );
    }
     echo json_encode($return);
}
 
        





if (isset($_POST['calendar-end-check'])){
    $car_id = $_POST['car_id'];
    $rental_end_date = $_POST['rental-end-date'];
    $sql="SELECT rental_id FROM rental WHERE car_id=".$car_id. " AND '".$rental_end_date."' BETWEEN rental_start_date AND rental_end_date";
    $query = $link->query($sql);
    if (mysqli_num_rows($query)>0){
        $return = "The date is already occupied.";
    }


     
    echo $twig->render('ajax-check-date.html',array(
    "return"=>$return,
    ));
}