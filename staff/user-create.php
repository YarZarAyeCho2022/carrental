<?php
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once 'authcheck.php';
$loader = new \Twig\Loader\FilesystemLoader('views');

$email_err = $register_err = $user_type_err = $password_err= $success_register=$photo_err= "";
$fullName = $passport = $contact = $email= $user_type="";

$twig = new \Twig\Environment($loader);
$twig->addGlobal('session', $_SESSION);

if (isset($_POST['create-user'])){
    $fullName = $_POST['full-name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_type = $_POST['user-type'];
    isset($_POST['passport']) ? $passport=$_POST['passport'] : $passport="";
    isset($_POST['contact']) ? $contact=$_POST['contact'] : $contact="";

    if($user_type!="staff" && $user_type!="user"){
        $user_type_err ="Please select user type.";
        $register_err = "Error!";
    }


    $sql = "SELECT user_id FROM user WHERE email ='".$email."'";
    $result = $link->query($sql);
    if (mysqli_num_rows($result)>0){
        $email_err = "Email already exist.";
        $register_err = "Error!";
    }
    if(strlen($password)<6){
        $password_err = "Password must be at least 6 characters.";
        $register_err = "Error!";
    }

    if(isset($_FILES['photo'])){
        $file_name = str_replace("@","-",$email);
        $file_size =$_FILES['photo']['size'];
        $file_tmp =$_FILES['photo']['tmp_name'];
        $file_type=$_FILES['photo']['type'];
        $file_ext=strtolower(end(explode('.',$_FILES['photo']['name'])));
        
        $extensions= array("jpeg","jpg","png");
        
        if(in_array($file_ext,$extensions)=== false){
           $photo_err="Extension not allowed, please choose a JPEG or PNG file.";
           $register_err = "Error!";
        }
        
        if($file_size > 2097152){
            $photo_err='File size must be not larger than 2 MB';
            $register_err = "Error!";
        }
        
        if(empty($errors)==true){
           move_uploaded_file($file_tmp,"../user/photos/".$file_name);
        }else{
            $photo_err="Something wrong while uploading photo. Please try again.";
            $register_err = "Error!";
        }
     }

    if (empty($register_err)){
        $sql = "INSERT INTO user(name,email,password,user_type,photo,passport_number,contact_number) VALUES (".
            "'".$fullName."',".
            "'".$email."',".
            "'".md5($password)."',".
            "'".$user_type."',".
            "'".$file_name."',".
            "'".$passport."',".
            "'".$contact."'".
            ")";
        $results = $link->query($sql);
        $link->close();
        $success_register = 'New user '.$fullName. '<'. $email.'> has been created successfully';

        if (!empty($success_register)){
            $fullName= $email=$passport=$contact=$user_type='';
        }
    }
    
}

echo $twig->render('user-create.html',array(
    "email_err"=>$email_err,
    "password_err"=>$password_err,
    "user_type_err"=>$user_type_err,
    "photo_err" => $photo_err,
    "success_register"=>$success_register,
    "name" => $fullName,
    "email" => $email,
    "passport" => $passport,
    "contact" =>$contact,
    "usertype"=>$user_type,

));