<?php
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once 'authcheck.php';

$loader = new \Twig\Loader\FilesystemLoader('views');

$twig = new \Twig\Environment($loader);
$twig->addGlobal('session', $_SESSION);

$user_detail=[];
$rental_detail=[];


if (isset($_GET['id'])){
    $user_id = $_GET['id'];
    $sql ='SELECT
                user.user_id,
                user.name,
                user.email,
                user.user_type,
                user.photo,
                user.passport_number,
                user.contact_number
            FROM 
                user
            WHERE user_id='.$user_id ;

    $query = $link->query($sql);
    while ($row = mysqli_fetch_assoc($query)) {
        $user_detail[] = $row;
    }

    $sql ='SELECT
                car.plate_number,
                car.brand,
                car.model,
                car.image,
                rental.rental_id,
                rental.rental_start_date,
                rental.rental_end_date,
                DATEDIFF(rental_end_date,rental_start_date)+1 AS total_days,
                rental.request_date,
                rental.rental_price AS daily_price,
                rental.amount AS total_amount,
                CASE 
                    WHEN rental.request_status = "C" THEN "Approved"
                    WHEN rental.request_status = "Rjd" THEN "Rejected"
                    WHEN rental.request_status = "P" THEN "Waiting approval"
                    WHEN rental.request_status = "Rtn" THEN "Returned"
                    ELSE ""
                END AS rental_stauts
            FROM 
	            user INNER JOIN rental ON user.user_id = rental.user_id
                INNER JOIN car ON rental.car_id = car.car_id
            WHERE user.user_id='.$user_id
            ;
    $query = $link->query($sql);
    while ($row = mysqli_fetch_assoc($query)) {
        $rental_detail[] = $row;
    }
}
else{
    header("location: user-list.php");
}
echo $twig->render('user-view.html',array(
    'user_detail'=> $user_detail,
    'rental_detail'=>$rental_detail,
));