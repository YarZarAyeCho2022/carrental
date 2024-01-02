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
$register_err=true;

$sql = "SELECT car_id, brand, model, plate_number FROM car";
$query = $link->query($sql);
while ($row = mysqli_fetch_assoc($query)) {
    $car_list[] = $row;
}

if (isset($_GET['id'])){
    $maintenance_id = $_GET['id'];

    $sql = "SELECT maintenance_id, description, start_date, end_date, cost, car_id, brand, model, plate_number  FROM carmaintenance LEFT JOIN car ON carmaintenance.car_id = car.car_id WHERE maintenance_id=".$maintenance_id;
    $query = $link->query($sql);
    while ($row = mysqli_fetch_assoc($query)) {
        $items[] = $row;
    }
    
    foreach ($items as $item){
        $image = $car['image'];
        $plate_number=$car['plate_number'];
        $model=$car['model'];
        $brand=$car['brand'];
        $color=$car['color'];
        $capacity=$car['capacity'];
        $insurance_number=$car['insurance_number'];
        $insurance_type=$car['insurance_type'];
        $rental_price =$car['rental_price'];
        $purchase_date=$car['purchase_date'];
        $purchase_price =$car['purchase_price'];
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


echo $twig->render('maintenance-update.html',array(
    "success_message" => $success_message,
    "car_list_err" => $car_list_err,
    "car_list"=>$car_list,
    "start_date_err"=> $start_date_err,
    "end_date_err" => $end_date_err,
    "car_id" => $car_id,
    "description"=>$description,
    "start_date"=> $start_date,
    "end_date"=>$end_date,
    "cost"=>$cost,
));