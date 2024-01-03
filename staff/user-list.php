<?php
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once 'authcheck.php';

$loader = new \Twig\Loader\FilesystemLoader('views');

$twig = new \Twig\Environment($loader);
$twig->addGlobal('session', $_SESSION);

$action_message ="";
$sql ='SELECT
user_id,
name,
email,
user_type,
photo,
passport_number,
contact_number
FROM 
user';
$query = $link->query($sql);

while ($row = mysqli_fetch_assoc($query)) {
    $results[] = $row;
}



if (isset($_POST['delete'])){
    $user_id = $_POST['user-id'];
    $sql = "SELECT COUNT(*) FROM user WHERE user_id = ".$user_id;
    if ($query = $link->query($sql)) {

    /* Check the number of rows that match the SELECT statement */
    if (mysqli_num_rows($query)>0) {

        /* Issue the real SELECT statement and work with the results */
        $sql = "DELETE FROM user WHERE user_id = ".$user_id;
        $query = $link->query($sql);
        echo "<meta http-equiv='refresh' content='0'>";
        $action_message = "USer has been deleted successfully.";
    }
    /* No rows matched -- do something else */
    else {
        $action_message = "No record found";
    }
    }
}


echo $twig->render('user-list.html',array(
    'queryResult'=> $results,
    'action_message'=>$action_message,
));