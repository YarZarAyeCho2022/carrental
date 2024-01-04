<?php
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once 'authcheck.php';

$loader = new \Twig\Loader\FilesystemLoader('views');

$twig = new \Twig\Environment($loader);
$twig->addGlobal('session', $_SESSION);

$return =true;

if (isset($_POST['calendar-start-check'])){
    $car_id = $_POST['car_id'];
    $rental_start_date = $_POST['rental-start-date'];
    $sql="SELECT rental_id FROM rental WHERE car_id=".$car_id. " AND '".$rental_start_date."' BETWEEN rental_start_date AND rental_end_date";
    $query = $link->query($sql);
    if (mysqli_num_rows($query)>0){
        $return = false;
    }


     
    echo $twig->render('ajax-check-date.html',array(
    "return"=>$return,
    ));
}


if (isset($_POST['calendar-end-check'])){
    $car_id = $_POST['car_id'];
    $rental_end_date = $_POST['rental-end-date'];
    $sql="SELECT rental_id FROM rental WHERE car_id=".$car_id. " AND '".$rental_end_date."' BETWEEN rental_start_date AND rental_end_date";
    $query = $link->query($sql);
    if (mysqli_num_rows($query)>0){
        $return = false;
    }


     
    echo $twig->render('ajax-check-date.html',array(
    "return"=>$return,
    ));
}