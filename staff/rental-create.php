<?php
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once 'authcheck.php';

$loader = new \Twig\Loader\FilesystemLoader('views');

$twig = new \Twig\Environment($loader);
$twig->addGlobal('session', $_SESSION);

$success_message="";
$car_list_err = $start_date_err= $end_date_err="";
$car_id= $description = $start_date = $end_date = $cost= "";
$car_list=[];
$user_list=[];
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

if (isset($_POST['create-maintenance'])){
   
    $car_id= $_POST['car-id'];
    $description = $_POST['description'];
    $start_date = $_POST['start-date'];
    $end_date = $_POST['end-date'];
    $cost = $_POST['cost'];

    $sql = "SELECT car_id FROM car WHERE car_id=".$car_id;
    $query = $link->query($sql);
    if (mysqli_num_rows($query)==0){
        $car_list_err = "Please select correct Car.";
        $register_err = false;
    }

    $sql = "SELECT rental_id FROM rental WHERE car_id=".$car_id. " AND ('".$start_date. "' BETWEEN rental_start_date AND rental_end_date) AND request_status<>'R'";
    $query =$link->query($sql);
    if (mysqli_num_rows($query)>0){
        $register_err = false;
        $start_date_err = "The date conflict with the rental period. Please select the diffrent date.";
    }

    $sql = "SELECT rental_id FROM rental WHERE car_id=".$car_id. " AND ('".$end_date. "' BETWEEN rental_start_date AND rental_end_date) AND request_status<>'R'";
    $query =$link->query($sql);
    if (mysqli_num_rows($query)>0){
        $register_err = false;
        $end_date_err = "The date conflict with the rental period. Please select the diffrent date.";
    }

    
    if ($register_err){
        
        $sql = "INSERT INTO carmaintenance (car_id, description, start_date,end_date,cost,created_date)VALUES("
            .$car_id.","
            ."'".$description."',"
            ."'". $start_date."',"
            ."'".$end_date."',"
            ."".$cost.","
            ."'" .date('Y-m-d H:i:s')."'"
            .")";
        $results = $link->query($sql);
        $link->close();
        $car_id= $description = $start_date = $end_date = $cost= "";
        $success_message = 'Mantenance record has been created';
    }
    else{

    }


}


echo $twig->render('rental-create.html',array(
    "success_message" => $success_message,
    "car_list_err" => $car_list_err,
    "car_list"=>$car_list,
    "user_list"=>$user_list,
    "start_date_err"=> $start_date_err,
    "end_date_err" => $end_date_err,
    "car_id" => $car_id,
    "description"=>$description,
    "start_date"=> $start_date,
    "end_date"=>$end_date,
    "cost"=>$cost,
));