<?php
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once 'authcheck.php';

$loader = new \Twig\Loader\FilesystemLoader('views');

$twig = new \Twig\Environment($loader);
$twig->addGlobal('session', $_SESSION);

$success_message="";
$car_list_err = $payment_date_err="";
$car_id= $payment_date =  $tax_amount= "";
$car_list=[];
$register_err=true;

$sql = "SELECT car_id, brand, model, plate_number FROM car";
$query = $link->query($sql);
while ($row = mysqli_fetch_assoc($query)) {
    $car_list[] = $row;
}

if (isset($_POST['road-tax-create'])){
   
    $car_id= $_POST['car-id'];
    $payment_date = $_POST['payment-date'];
    $tax_amount = $_POST['tax-amount'];

    $sql = "SELECT car_id FROM car WHERE car_id=".$car_id;
    $query = $link->query($sql);
    if (mysqli_num_rows($query)==0){
        $car_list_err = "Please select correct Car.";
        $register_err = false;
    }
    
    if ($register_err){
        
        $sql = "INSERT INTO roadtax (car_id, amount, payment_date)VALUES("
            .$car_id.","
            .$tax_amount.","
            ."'". $payment_date."'"
            .")";
        $results = $link->query($sql);
        $link->close();
        $car_id= $tax_amount= $payment_date = "";
        $success_message = 'Road Tax record has been created';
    }
    else{

    }


}


echo $twig->render('road-tax-create.html',array(
    "success_message" => $success_message,
    "car_list_err" => $car_list_err,
    "car_list"=>$car_list,
    "payment_date_err"=> $payment_date_err,
    "car_id" => $car_id,
));