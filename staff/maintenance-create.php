<?php
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once 'authcheck.php';

$loader = new \Twig\Loader\FilesystemLoader('views');

$twig = new \Twig\Environment($loader);
$twig->addGlobal('session', $_SESSION);

$success_message="";
$register_err= $car_list_err ="";
$car_list=[];

$sql = "SELECT car_id, brand, model, plate_number FROM car";
$query = $link->query($sql);
while ($row = mysqli_fetch_assoc($query)) {
    $car_list[] = $row;
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
        $register_err = "Error!";
    }

    
     if (empty($register_err)){
        
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
        $success_message = 'Mantenance record has been created';
    }

}


echo $twig->render('maintenance-create.html',array(
    "success_message" => $success_message,
    "car_list_err" => $car_list_err,
    "car_list"=>$car_list,
));