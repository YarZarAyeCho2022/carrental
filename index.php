<?php
require_once 'vendor/autoload.php';
require_once 'includes/config.php';
session_start();

$loader = new \Twig\Loader\FilesystemLoader('views');

$twig = new \Twig\Environment($loader);
$twig->addGlobal('session', $_SESSION);




$success_register ='';
$login_err= $register_err= '';
$r_email_err = $r_password1_err = $r_password2_err = '';
if (isset(($_POST['login']))){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT user_id,name,email,user_type,photo,passport_number,contact_number FROM user WHERE email='".$email."' AND password ='".md5($password)."'";
    $result = $link->query($sql);

    if(mysqli_num_rows($result)==0){
        $login_err="Email and password do not match.";
    }
    else{
        session_start();
        while($row = $result->fetch_assoc()) {
            $_SESSION["loggedin"] = true;
            $_SESSION["user_id"] = $row["user_id"];
            $_SESSION['name'] = $row['name'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['user_type'] = $row['user_type'];
            $_SESSION['photo'] = $row['photo'];
            $_SESSION['passport'] = $row['passport_number'];
            $_SESSION['contact'] = $row['contact_number'];
        }
        if($_SESSION['user_type']=='user'){
            header("location: user/dashboard.php");
            exit;
        }
        elseif($_SESSION['user_type']=='staff'){
            header("location: staff/dashboard.php");
            exit;
        }
    }


}

if (isset($_POST['register'])){
    $fullName = $_POST['full-name'];
    $email = $_POST['r-email'];
    $password1 = $_POST['r-password1'];
    $password2 = $_POST['r-password2'];
    isset($_POST['passport']) ? $passport=$_POST['passport'] : $passport="";
    isset($_POST['contact']) ? $contact=$_POST['contact'] : $contact="";

    $sql = "SELECT user_id FROM user WHERE email ='".$email."'";
    $result = $link->query($sql);
    // $link->close();

    if (mysqli_num_rows($result)>0){
        $r_email_err = "Email already exist.";
        $register_err = "Error!";
    }
    if(strlen($password1)<6){
        $r_password1_err = "Password must be at least 6 characters.";
        $register_err = "Error!";
    }
    if($password1 != $password2){
        $r_password2_err = "Password do not match.";
        $register_err = "Error!";
    }

    if (empty($register_err)){
        $sql = "INSERT INTO user(name,email,password,user_type,passport_number,contact_number) VALUES (".
            "'".$fullName."',".
            "'".$email."',".
            "'".md5($password1)."',".
            "'user',".
            "'".$passport."',".
            "'".$contact."'".
            ")";
        $results = $link->query($sql);
        $link->close();
        $success_register = 'Registration success. Login now?';
    }
}
echo $twig->render('index.html',array(
    'name' => 'Yar Zar',
    'login_err'=> $login_err,
    'register_err'=> $register_err,
    'r_email_err' => $r_email_err,
    'r_password1_err'=> $r_password1_err,
    'r_password2_err'=> $r_password2_err,
    'success_register' => $success_register,


))
?>