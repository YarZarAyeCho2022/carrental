<?php
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once 'authcheck.php';

$loader = new \Twig\Loader\FilesystemLoader('views');

$twig = new \Twig\Environment($loader);
$twig->addGlobal('session', $_SESSION);

$register_err = true;
$photo_err= $user_type_err=$email_err= $success_register ='';
$new_photo="";

if (isset($_GET['id'])){
    $user_id = $_GET['id'];
    
    $sql = 'SELECT user_id,name,email,user_type,photo,passport_number,contact_number FROM user WHERE user_id='.$user_id;
    
    $query=$link->query($sql);
    while ($row = mysqli_fetch_assoc($query)) {
        $users[] = $row;
    }
    
    foreach ($users as $user){
        $name = $user['name'];
        $email = $user['email'];
        $user_type = $user['user_type'];
        $new_photo = $user['photo'];
        $passport_number = $user['passport_number'];
        $contact_number = $user['contact_number'];
    }
    
}

if (isset($_POST['update-user'])){
    
    $user_id= $_POST['user-id'];
    $user_name=$_POST['user-name'];
    $new_email = $_POST['new-email'];
    $old_email = $_POST['old-email'];
    $user_type = $_POST['user-type'];
    $passport_number = $_POST['passport-number'];
    $contact_number = $_POST['contact-number'];
    $old_photo = $_POST['old-photo'];

    if(!in_array($user_type, array("staff","user"))){
        $user_type_err ="Please select user type";
        $register_err = false;
    }

    if($old_email != $new_email){
        $sql = "SELECT user_id FROM user WHERE email LIKE '".$new_email."'";
        $result = $link->query($sql);
        if (mysqli_num_rows($result)>0){
            $email_err = "Email already exist.";
            $register_err = false;
        }    
    }
    
    
    if(!empty($_FILES['photo']['name'])){
        
        $file_name = uniqid();
        // echo $_FILES['photo']['name'];
        $file_size =$_FILES['photo']['size'];
        $file_tmp =$_FILES['photo']['tmp_name'];
        $file_type=$_FILES['photo']['type'];
        $exploded = explode('.', $_FILES['photo']['name']);
        $file_ext = strtolower(end($exploded));
        
        $extensions= array("jpeg","jpg","png");
        
        if(in_array($file_ext,$extensions)=== false){
           $photo_err="Extension not allowed, please choose a JPEG or PNG file.";
           $register_err = false;
        }
        
        if($file_size > 2097152){
            $photo_err='File size must be not larger than 2 MB';
            $register_err =false;
        }
        
        if(empty($errors)==true){
           move_uploaded_file($file_tmp,"../user/photos/".$file_name.".".$file_ext);
           $new_photo = $file_name.".".$file_ext;
        }else{
            $photo_err="Something wrong while uploading photo. Please try again.";
            $register_err =false;
        }
        
     }
     else {
        $new_photo =$old_photo;
     }

     if ($register_err){
        
        $image = $new_photo;
        $sql = "UPDATE user SET "
            ."name = '".$user_name."',"
            ."email = '".$new_email."',"
            ."user_type ='" . $user_type . "',"
            ."photo ='" . $new_photo . "',"
            ."passport_number ='" .$passport_number."',"
            ."contact_number ='".$contact_number."'"
            ." WHERE user_id=".$user_id;
        $results = $link->query($sql);
        $link->close();
        $name=$user_name;
        $success_register = 'The user '.$user_name. '<'. $new_email.'> has been updated successfully';
    }

}


echo $twig->render('user-update.html',array(
    "user_id"=> $user_id,
    "user_name"=> $name,
    "email"=> $email,
    "user_type"=>$user_type,
    "photo" => $new_photo,
    "photo_err" => $photo_err,
    "email_err" => $email_err,
    "user_type_err"=>$user_type_err,
    "passport_number" => $passport_number,
    "contact_number" => $contact_number,
    "success_register"=> $success_register,
));