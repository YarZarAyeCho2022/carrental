<?php
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once 'authcheck.php';

$loader = new \Twig\Loader\FilesystemLoader('views');

$twig = new \Twig\Environment($loader);
$twig->addGlobal('session', $_SESSION);


$rental_detail=[];
$return_message='';

if (isset($_GET['id'])){
    $rental_id = $_GET['id'];
    $sql ='SELECT
            rental_id,
            car.plate_number,
            car.image AS car_image,
            car.plate_number,
            car.brand,
            car.model,
            car.color,
            user.user_id,
            user.name,
            user.photo,
            user.email,
            user.contact_number,
            rental.rental_start_date,
            rental.rental_end_date,
            DATEDIFF(rental_end_date,rental_start_date)+1 AS total_days,
            rental.rental_price AS daily_price,
            rental.amount AS total_amount,
            rental.request_status AS rental_status,
            CASE 
                WHEN rental.request_status = "C" THEN "Approved"
                WHEN rental.request_status = "Rjd" THEN "Rejected"
                WHEN rental.request_status = "P" THEN "Waiting approval"
                WHEN rental.request_status = "Rtn" THEN "Returned"
                ELSE ""
            END AS rental_status
            FROM 
            rental LEFT JOIN user ON rental.user_id = user.user_id
            LEFT JOIN car on rental.car_id = car.car_id
            WHERE rental.rental_id='.$rental_id;

    $query = $link->query($sql);
    while ($row = mysqli_fetch_assoc($query)) {
        $rental_detail[] = $row;
    }
}
else{
    header("location: rental-list.php");
} //$_GET

if(isset($_POST['change'])){
    $rental_id = $_POST['rental-id'];
    $new_rental_status = $_POST['new-rental-status'];
    $array = array("Approved" => "C", "Rejected" => "Rjd", "Waiting approval" => "P", "Returned"=>"Rtn");
    $new_rental_status = $array[$new_rental_status];

    $sql="UPDATE rental SET request_status='".$new_rental_status."' WHERE rental_id=".$rental_id;
    $query = $link->query($sql);
    $link->close();
    if($query){
        
        echo "<meta http-equiv='refresh' content='0'>";
        $return_message = "Rental status has bee updated successfully.";
    }
    else {
        $return_message = "Something went wrong.";
    }
    
}
echo $twig->render('rental-view.html',array(
    'rental_detail'=>$rental_detail,
    'return_message'=>$return_message,
));