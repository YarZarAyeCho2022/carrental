<?php
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once 'authcheck.php';

$loader = new \Twig\Loader\FilesystemLoader('views');

$twig = new \Twig\Environment($loader);
$twig->addGlobal('session', $_SESSION);

$rental_detail=[];
$maintenance_detail=[];
$roadtax_detail=[];

if (isset($_GET['id'])){
    $car_id = $_GET['id'];
    $sql ='SELECT
            `car`.`car_id`,
            `car`.`image`,
            `car`.`plate_number`,
            `car`.`model`,
            `car`.`brand`,
            `car`.`color`,
            CASE
                WHEN current_repair.car_id IS NOT NULL THEN "Repair"
                WHEN car_rental.request_status ="P" THEN "Request pending"
                WHEN car_rental.request_status = "C" THEN "Approved"
                ELSE "Available"
            END AS "Status",
                `car`.`rental_price`,
                `car`.`purchase_date`,
                `car`.`purchase_price`
            FROM 
                `car` LEFT JOIN (SELECT car_id, start_date, end_date FROM carmaintenance WHERE CURDATE() BETWEEN start_date AND end_date)AS current_repair ON `car`.`car_id` = current_repair.car_id
                LEFT JOIN (SELECT car_id,rental_start_date, rental_end_date, request_status FROM rental WHERE CURDATE() BETWEEN rental_start_date AND rental_end_date) AS car_rental ON car.car_id = car_rental.car_id
            WHERE `car`.`car_id`='.$car_id ;

    $query = $link->query($sql);
    
    while ($row = mysqli_fetch_assoc($query)) {
        $results[] = $row;
    }


    $sql ='SELECT  
                rental.rental_id,
                rental.rental_start_date,
                rental.rental_end_date,
                user.user_id,
                user.name,
                user.email,
                user.contact_number,
                rental.rental_price,
                CASE
                WHEN rental.request_status="C" THEN "Approved"
                WHEN rental.request_status="P" THEN "Pending"
                WHEN rental.request_status="Rjd" THEN "Rejected"
                WHEN rental.request_status="Rtn" THEN "Returned"
                END as request_status,
                rental.amount
            FROM 
	            car INNER JOIN rental ON car.car_id = rental.car_id
                INNER JOIN user ON rental.user_id = user.user_id
            WHERE car.car_id='.$car_id
            ;
    $query = $link->query($sql);
    while ($row = mysqli_fetch_assoc($query)) {
        $rental_detail[] = $row;
    }

    $sql = "SELECT 
                SUM(IFNULL(actual_rental.amount,0)) AS Profit,
                SUM(IFNULL(carmaintenance.cost,0)) + car.purchase_price AS Loss,
                SUM(IFNULL(actual_rental.amount,0))-(SUM(IFNULL(carmaintenance.cost,0))+car.purchase_price) AS PNL
            FROM 
                car LEFT JOIN (SELECT car_id,amount FROM rental WHERE request_status NOT IN ('Rjd','P'))actual_rental ON car.car_id=actual_rental.car_id
                LEFT JOIN carmaintenance ON car.car_id=carmaintenance.car_id
                WHERE car.car_id=".$car_id;
    $query = $link->query($sql);
    while ($row = mysqli_fetch_assoc($query)) {
        $pnl[] = $row;
    }

    $sql ='SELECT  
                carmaintenance.maintenance_id,
                carmaintenance.description,
                carmaintenance.start_date,
                carmaintenance.end_date,
                carmaintenance.cost
            FROM 
                car INNER JOIN carmaintenance ON car.car_id = carmaintenance.car_id
            WHERE car.car_id='.$car_id;
    $query = $link->query($sql);
    while ($row = mysqli_fetch_assoc($query)) {
        $maintenance_detail[] = $row;
    }

    $sql ='SELECT 
                roadtax.road_tax_id,
                roadtax.amount,
                roadtax.payment_date
            FROM
            car INNER JOIN roadtax ON car.car_id=roadtax.car_id
            WHERE car.car_id='.$car_id;
    $query = $link->query($sql);
    while ($row = mysqli_fetch_assoc($query)) {
        $roadtax_detail[] = $row;
    }

}
else{
    header("location: car-list.php");
}
echo $twig->render('car-view.html',array(
    'queryResult'=> $results,
    'rental_detail'=>$rental_detail,
    'pnl'=>$pnl,
    'maintenance_detail'=>$maintenance_detail,
    'roadtax_detail' => $roadtax_detail,
));