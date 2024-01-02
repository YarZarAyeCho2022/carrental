<?php
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once 'authcheck.php';

$loader = new \Twig\Loader\FilesystemLoader('views');

$twig = new \Twig\Environment($loader);
$twig->addGlobal('session', $_SESSION);



$plate_number= $model= $brand= $color= $insurance_type= $purchase_date= $insurance_number= $success_register= "";
$purchase_price = $rental_price= $capacity=0;
$plate_number_err = $insurance_type_err= $photo_err= "";
$old_photo = $new_photo = "";

if (isset($_GET['id'])){
    $car_id = $_GET['id'];
    
    $sql = 'SELECT image,plate_number,model,brand,color,capacity,insurance_number,insurance_type,rental_price,purchase_date,purchase_price FROM car WHERE car_id='.$car_id;
    
    $query=$link->query($sql);
    while ($row = mysqli_fetch_assoc($query)) {
        $cars[] = $row;
    }
    
    foreach ($cars as $car){
        $image = $car['image'];
        $plate_number=$car['plate_number'];
        $model=$car['model'];
        $brand=$car['brand'];
        $color=$car['color'];
        $capacity=$car['capacity'];
        $insurance_number=$car['insurance_number'];
        $insurance_type=$car['insurance_type'];
        $rental_price =$car['rental_price'];
        $purchase_date=$car['purchase_date'];
        $purchase_price =$car['purchase_price'];
    }
    
}

if (isset($_POST['update-car'])){
    
    $car_id= $_POST['car-id'];
    $old_photo = $_POST['old-photo'];
    $plate_number = $_POST['plate-number'];
    $old_plate_number = $_POST['old-plate-number'];
    $model = $_POST['model'];
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

    if($old_plate_number != $plate_number){
        $sql = "SELECT car_id FROM car WHERE plate_number LIKE '".$plate_number."'";
        $result = $link->query($sql);
        if (mysqli_num_rows($result)>0){
            $plate_number_err = "Plate number already exist.";
            $register_err = "Error!";
        }    
    }
    
    
    if(!empty($_FILES['photo']['name'])){
        
        $file_name = uniqid();
        echo $_FILES['photo']['name'];
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
           move_uploaded_file($file_tmp,"cars-photo/".$file_name.".".$file_ext);
           $new_photo = $file_name.".".$file_ext;
        }else{
            $photo_err="Something wrong while uploading photo. Please try again.";
            $register_err = "Error!";
        }
        
     }
     else {
        $new_photo =$old_photo;
     }

     if (empty($register_err)){
        
        $image = $new_photo;
        $sql = "UPDATE car SET "
            ."image = '".$image."',"
            ."plate_number = '".$plate_number."',"
            ."model ='" . $model . "',"
            ."brand ='" . $brand . "',"
            ."color ='" .$color."',"
            ."capacity =".$capacity.","
            ."insurance_number ='" .$insurance_number."',"
            ."insurance_type ='".$insurance_type."',"
            ."rental_price=" .$rental_price.","
            ."purchase_date='".$purchase_date."',"
            ."purchase_price=" .$purchase_price
            ." WHERE car_id=".$car_id;
        $results = $link->query($sql);
        $link->close();
        $success_register = 'The car '.$plate_number. '<'. $model.'> has been updated successfully';
    }

}


echo $twig->render('car-update.html',array(
    "car_id"=> $car_id,
    "image"=> $image,
    "plate_number"=>$plate_number,
    "model" => $model,
    "brand" => $brand,
    "color" => $color,
    "capacity" => $capacity,
    "insurance_number"=> $insurance_number,
    "insurance_type" => $insurance_type,
    "purchase_price"=>$purchase_price,
    "purchase_date" => $purchase_date,
    "rental_price" => $rental_price,
    "plate_number_err" => $plate_number_err,
    "insurance_type_err"=> $insurance_type_err,
    "photo_err" => $photo_err,
    "success_register"=> $success_register,
));