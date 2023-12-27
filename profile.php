<?php
require_once 'vendor/autoload.php';
require_once 'includes/config.php';

session_start();
if(!isset($_SESSION["loggedin"])){
    header("location: ../401.php");
    exit;
}

$loader = new \Twig\Loader\FilesystemLoader('views');
$twig = new \Twig\Environment($loader);
$twig->addGlobal('session', $_SESSION);


echo $twig->render('profile.html',array(
))
?>