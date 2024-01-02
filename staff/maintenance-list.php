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
`carmaintenance`.`maintenance_id`,
`carmaintenance`.`description`,
`carmaintenance`.`start_date`,
`carmaintenance`.`end_date`,
`carmaintenance`.`cost`
FROM 
carmaintenance LEFT JOIN car ON carmaintenance.car_id = car.car_id
ORDER BY end_date DESC';
$query = $link->query($sql);

while ($row = mysqli_fetch_assoc($query)) {
    $results[] = $row;
}

if (isset($_POST['delete'])){
    $maintenance_id = $_POST['maintenance-id'];
    $sql = "SELECT COUNT(*) FROM carmaintenance WHERE maintenance_id = ".$maintenance_id;
    if ($query = $link->query($sql)) {

    /* Check the number of rows that match the SELECT statement */
    if (mysqli_num_rows($query)>0) {

        /* Issue the real SELECT statement and work with the results */
        $sql = "DELETE FROM carmaintenance WHERE maintenance_id = ".$maintenance_id;
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



echo $twig->render('maintenance-list.html',array(
    'queryResult'=> $results,
    'action_message'=>$action_message,
    
));