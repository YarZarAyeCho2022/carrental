<?php
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once 'authcheck.php';

$loader = new \Twig\Loader\FilesystemLoader('views');

$twig = new \Twig\Environment($loader);
$twig->addGlobal('session', $_SESSION);




$sql = "SELECT car.car_id, car.image, car.plate_number, car.model, car.brand, car_income.totalAmount, ROUND((car_income.totalAmount/(SELECT sum(amount) FROM rental WHERE request_status NOT IN ('Rjd','P'))*100),2) AS percentage FROM car LEFT JOIN
(SELECT car_id,sum(amount)as totalAmount 
FROM rental 
WHERE request_status NOT IN ('Rjd','P')
GROUP BY car_id)car_income ON car.car_id = car_income.car_id
ORDER BY car_income.totalAmount DESC LIMIT 5";
$query = $link->query($sql);
while ($row = mysqli_fetch_assoc($query)) {
    $topFiveCars[] = $row;
}

$sql = "SELECT count(*) AS total_cars FROM car";
$query = $link->query($sql);
while ($row = mysqli_fetch_assoc($query)) {
    $total_cars[] = $row;
}

$sql = "SELECT count(*) AS total_rentals FROM rental";
$query = $link->query($sql);
while ($row = mysqli_fetch_assoc($query)) {
    $total_rentals[] = $row;
}

$sql = "SELECT count(*) AS total_maintenance FROM carmaintenance";
$query = $link->query($sql);
while ($row = mysqli_fetch_assoc($query)) {
    $total_maintenance[] = $row;
}

$sql = "SELECT count(*) AS total_users FROM user";
$query = $link->query($sql);
while ($row = mysqli_fetch_assoc($query)) {
    $total_users[] = $row;
}

$sql = "SELECT user.user_id, user.photo, user.name, user.email, user_expense.totalAmount, ROUND((user_expense.totalAmount/(SELECT sum(amount) FROM rental WHERE request_status NOT IN ('Rjd','P'))*100),2) AS percentage FROM user LEFT JOIN
(SELECT user_id,sum(amount)as totalAmount 
FROM rental 
WHERE request_status NOT IN ('Rjd','P')
GROUP BY user_id)user_expense ON user.user_id = user_expense.user_id
ORDER BY user_expense.totalAmount DESC LIMIT 5";
$query = $link->query($sql);
while ($row = mysqli_fetch_assoc($query)) {
    $topFiveUsers[] = $row;
}

$link->close();

echo $twig->render('staff-dashboard.html',array(
    
    'topFiveCars' => $topFiveCars,
    'topFiveUsers' => $topFiveUsers,
    'total_cars' => $total_cars,
    'total_rentals'=> $total_rentals,
    'total_maintenance'=> $total_maintenance,
    'total_users'=> $total_users,
));