<?php
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once 'authcheck.php';

$loader = new \Twig\Loader\FilesystemLoader('views');

$twig = new \Twig\Environment($loader);
$twig->addGlobal('session', $_SESSION);

if (isset($_POST['car_change'])){
    $car_id = $_POST['car_id'];
    $sql="SELECT rental_price FROM car WHERE car_id=".$car_id;
    $query = $link->query($sql);
    while ($row = mysqli_fetch_assoc($query)) {
        $results[] = $row;
    }
    // $return = json_encode($results);
     
    echo $twig->render('ajax-check-car.html',array(
    "return"=>$results,
    ));
}
