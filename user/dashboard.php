<?php
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once 'authcheck.php';
$loader = new \Twig\Loader\FilesystemLoader('views');

$twig = new \Twig\Environment($loader);
$twig->addGlobal('session', $_SESSION);

$user_id = $_SESSION['user_id'];
echo $twig->render('user-dashboard.html',array(
"user_id"=>$user_id,
));