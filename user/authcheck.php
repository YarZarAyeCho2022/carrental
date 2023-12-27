<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["user_type"] != "user"){
    header("location: ../401.php");
    exit;
}
?>