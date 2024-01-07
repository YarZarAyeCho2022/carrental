<?php
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once 'authcheck.php';

$loader = new \Twig\Loader\FilesystemLoader('views');

$twig = new \Twig\Environment($loader);
$twig->addGlobal('session', $_SESSION);

$success_message="";
$start_date_err= $end_date_err="";
$car_id= $description = $start_date = $end_date = $cost= "";
$car_list=[];
$user_list=[];
$user_err= $car_err="";
$register_err=true;

$sql = "SELECT car_id, brand, model, plate_number FROM car";
$query = $link->query($sql);
while ($row = mysqli_fetch_assoc($query)) {
    $car_list[] = $row;
}

$sql = "SELECT user_id, name, email FROM user";
$query = $link->query($sql);
while ($row = mysqli_fetch_assoc($query)) {
    $user_list[] = $row;
}

if (isset($_POST['create-rental'])){
   
    $car_id= $_POST['car-id'];
    $user_id= $_POST['user-id'];
    $rental_start_date = $_POST['rental-start-date'];
    $rental_end_date = $_POST['rental-end-date'];
    $daily_price = $_POST['daily-price'];
    $total_amount = $_POST['total-amount'];
    
    $sql="SELECT user_id FROM user WHERE user_id =".$user_id;
    $query = $link->query($sql);
    if(mysqli_num_rows($query)==0){
        $register_err=false;
        $user_err = "Username not found.";
    }

    $sql="SELECT car_id FROM car WHERE car_id =".$car_id;
    $query = $link->query($sql);
    if(mysqli_num_rows($query)==0){
        $register_err=false;
        $car_err = "Car not found.";
    }

    if ($register_err){
        $sql = "INSERT INTO rental (user_id,car_id,rental_start_date, rental_end_date,rental_price, amount, request_status, request_date)VALUES("
            .$user_id.","
            .$car_id.","
            ."'". $rental_start_date."',"
            ."'". $rental_end_date."',"
            .$daily_price.","
            .$total_amount.","
            ."'P',"
            ."'".date('Y-m-d H:i:s')."'"
            .")";

        $results = $link->query($sql);
        $link->close();
        
        $success_message = 'Rental record has been created';
    }
    else{

    }


}


echo $twig->render('rental-create.html',array(
    "success_message" => $success_message,
    "car_list"=>$car_list,
    "user_list"=>$user_list,
));