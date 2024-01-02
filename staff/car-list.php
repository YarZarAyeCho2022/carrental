<?php
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once 'authcheck.php';

$loader = new \Twig\Loader\FilesystemLoader('views');

$twig = new \Twig\Environment($loader);
$twig->addGlobal('session', $_SESSION);

$sql ='SELECT
`car`.`car_id`,
`car`.`image`,
`car`.`plate_number`,
`car`.`model`,
`car`.`brand`,
`car`.`color`,
CASE
	WHEN current_repair.car_id IS NOT NULL THEN "Repair"
	WHEN car_rental.request_status ="P" THEN "Pending"
	WHEN car_rental.request_status = "C" THEN "Approved"
    ELSE "Available"
END AS "Status",
`car`.`rental_price`,
`car`.`purchase_date`,
`car`.`purchase_price`

FROM 
`car` LEFT JOIN (SELECT car_id, start_date, end_date FROM carmaintenance WHERE CURDATE() BETWEEN start_date AND end_date)AS current_repair ON `car`.`car_id` = current_repair.car_id
LEFT JOIN (SELECT car_id,rental_start_date, rental_end_date, request_status FROM rental WHERE CURDATE() BETWEEN rental_start_date AND rental_end_date) AS car_rental ON car.car_id = car_rental.car_id
ORDER BY FIELD("Request", "Maintanance", "Available")';
$query = $link->query($sql);

while ($row = mysqli_fetch_assoc($query)) {
    $results[] = $row;
}





echo $twig->render('car-list.html',array(
    'queryResult'=> $results,
    
));