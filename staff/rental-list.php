<?php
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once 'authcheck.php';

$loader = new \Twig\Loader\FilesystemLoader('views');

$twig = new \Twig\Environment($loader);
$twig->addGlobal('session', $_SESSION);

$action_message ="";
$sql ='SELECT
rental_id,
car.plate_number,
car.image AS car_image,
car.plate_number,
user.user_id,
user.name,
user.photo,
user.email,
rental.rental_start_date,
rental.rental_end_date,
DATEDIFF(rental_end_date,rental_start_date)+1 AS total_days,
rental.rental_price AS daily_price,
rental.amount AS total_amount,
CASE 
    WHEN rental.request_status = "C" THEN "Approved"
    WHEN rental.request_status = "Rjd" THEN "Rejected"
    WHEN rental.request_status = "P" THEN "Waiting approval"
    WHEN rental.request_status = "Rtn" THEN "Returned"
    ELSE ""
END AS rental_status
FROM 
rental LEFT JOIN user ON rental.user_id = user.user_id
LEFT JOIN car on rental.car_id = car.car_id';
$query = $link->query($sql);

while ($row = mysqli_fetch_assoc($query)) {
    $results[] = $row;
}



if (isset($_POST['delete'])){
    $rental_id = $_POST['rental-id'];
    $sql = "SELECT COUNT(*) FROM rental WHERE rental_id = ".$rental_id;
    if ($query = $link->query($sql)) {

    /* Check the number of rows that match the SELECT statement */
    if (mysqli_num_rows($query)>0) {

        /* Issue the real SELECT statement and work with the results */
        $sql = "DELETE FROM rental WHERE rental_id = ".$rental_id;
        $query = $link->query($sql);
        echo "<meta http-equiv='refresh' content='0'>";
        $action_message = "Rental record has been deleted successfully.";
    }
    /* No rows matched -- do something else */
    else {
        $action_message = "No record found";
    }
    }
}


echo $twig->render('rental-list.html',array(
    'queryResult'=> $results,
    
));