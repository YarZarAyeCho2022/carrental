<?php
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once 'authcheck.php';

$loader = new \Twig\Loader\FilesystemLoader('views');

$twig = new \Twig\Environment($loader);
$twig->addGlobal('session', $_SESSION);

$action_message = "";

$sql ='SELECT
`car`.`image`,
`car`.`plate_number`,
`car`.`model`,
`car`.`brand`,
`roadtax`.`road_tax_id`,
`roadtax`.`payment_date`,
`roadtax`.`amount`
FROM 
roadtax LEFT JOIN car ON roadtax.car_id = car.car_id
ORDER BY roadtax.payment_date DESC';
$query = $link->query($sql);

while ($row = mysqli_fetch_assoc($query)) {
    $results[] = $row;
}

if (isset($_POST['delete'])){
    $road_tax_id = $_POST['road-tax-id'];
    $sql = "SELECT COUNT(*) FROM roadtax WHERE road_tax_id = ".$road_tax_id;
    if ($query = $link->query($sql)) {

    /* Check the number of rows that match the SELECT statement */
    if (mysqli_num_rows($query)>0) {

        /* Issue the real SELECT statement and work with the results */
        $sql = "DELETE FROM roadtax WHERE road_tax_id = ".$road_tax_id;
        $query = $link->query($sql);
        echo "<meta http-equiv='refresh' content='0'>";
        $action_message = "Row has been deleted successfully.";
    }
    /* No rows matched -- do something else */
    else {
        $action_message = "No record found";
    }
    }
}



echo $twig->render('road-tax-list.html',array(
    'queryResult'=> $results,
    'action_message'=>$action_message,
    
));