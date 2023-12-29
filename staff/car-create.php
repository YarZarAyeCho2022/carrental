<?php
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once 'authcheck.php';

$loader = new \Twig\Loader\FilesystemLoader('views');

$twig = new \Twig\Environment($loader);
$twig->addGlobal('session', $_SESSION);


$plate_number= $model= $brand= $color= $insurance_type= $purchase_date= $insurance_number= "";
$purchase_price = $rental_price= $capacity=0;
$plate_number_err = $insurance_type_err= "";

if (isset($_POST['create-car'])){
    $plate_number = $_POST['plate-number'];
    $model = $_POST['plate-number'];
    $brand = $_POST['brand'];
    $color = $_POST['color'];
    $capacity = $_POST['capacity'];
    $insurance_number = $_POST['insurance'];
    $insurance_type = $_POST['insurance-type'];
    $purchase_price = $_POST['purchase-price'];
    $purchase_date = $_POST['purchase-date'];
    $rental_price = $_POST['rental-price'];

    if($insurance_type!="full" && $insurance_type!="fire" && $insurance_type!="theft"){
        $insurance_type_err ="Please select insurance type.";
        $register_err = "Error!";
    }

    $sql = "SELECT car_id FROM car WHERE plate_number LIKE '".$plate_number."'";
    $result = $link->query($sql);
    if (mysqli_num_rows($result)>0){
        $plate_number_err = "Plate number already exist.";
        $register_err = "Error!";
    }
    if(isset($_FILES['photo'])){
        $file_name = str_replace(".","-",trim(str_replace("@","-",$plate_number)));
        $file_size =$_FILES['photo']['size'];
        $file_tmp =$_FILES['photo']['tmp_name'];
        $file_type=$_FILES['photo']['type'];
        $exploded = explode('.', $_FILES['photo']['name']);
        $file_ext = strtolower(end($exploded));
        
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
           move_uploaded_file($file_tmp,"../user/photos/".$file_name.".".$file_ext);
        }else{
            $photo_err="Something wrong while uploading photo. Please try again.";
            $register_err = "Error!";
        }
     }

     if (empty($register_err)){
        $sql = "INSERT INTO car(image,plate_number,model,brand,color,capacity,insurance_number,insurance_type,rental_price,purchase_date,purchase_price,created_date) VALUES (".
            "'".$file_name.".".$file_ext."',".
            "'".$plate_number."',".
            "'".$model."',".
            "'".$brand."',".
            "'".$color."',".
            "".$capacity.",".
            "'".$insurance_number."',".
            "'".$insurance_type."',".
            "".$rental_price.",".
            "'".date('Y-m-D',strtotime($purchase_date))."',".
            "".$purchase_price.",".
            "'".date('Y-m-d H:i:s')."'".
            ")";
        $results = $link->query($sql);
        $link->close();
        $success_register = 'New car '.$plate_number. '<'. $model.'> has been created successfully';

        if (!empty($success_register)){
            $plate_number= $model= $brand= $color= $insurance_type= $purchase_date= $insurance_number= "";
            $purchase_price = $rental_price= $capacity=0;
        }
    }

}

echo $twig->render('car-create.html',array(
    "plate_number"=>$plate_number,
    "model" => $model,
    "brand" => $brand,
    "color" => $color,
    "capacity" => $capacity,
    "insurance_type" => $insurance_type,
    "purchase_price"=>$purchase_price,
    "purchase_date" => $purchase_date,
    "rental_price" => $rental_price,
    "plate_number_err" => $plate_number_err,
    "insurance_type_err"=> $insurance_type_err,
));