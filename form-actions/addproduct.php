<?php 
session_start();
require '../config.php';

if(isset($_POST['submit'])){
    $prodname = $_POST["prodname"];
    $desc = $_POST["desc"];
    $prodsize = $_POST["prodsize"];
    $cate = $_POST["cate"];
    $qty = $_POST["qty"];
    $price = $_POST["price"];
    $prodimg = $_FILES["prodimg"]["name"];
    $prodimg_tmp_name = $_FILES["prodimg"]["tmp_name"];
    $prodimg_folder = '../products/'.$prodimg;
    
    if (move_uploaded_file($prodimg_tmp_name, $prodimg_folder)) {
        $stmt = $conn->prepare("INSERT INTO products (`prodname`, `description`, `size`, `category`, `qty`, `price`,`image`) VALUES ( ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssids", $prodname, $desc, $prodsize, $cate, $qty, $price, $prodimg);
        if ($stmt->execute()) {
            echo '<script language="javascript">alert("Product Successfully Added!");</script>';
            echo '<script language="javascript">window.location.href = "../admin-products.php";</script>';
            
        } else {
            echo "Error: " . $stmt->error;
        }
    }else{
        echo '<script language="javascript">alert("Error uploading the image file.");</script>';
    }
}else{
    echo '<script language="javascript">alert("err!");</script>';
        echo '<script language="javascript">window.location.href = "../admin-products.php";</script>';
}

?>