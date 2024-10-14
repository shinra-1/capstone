<?php 
session_start();
require '../config.php';

if(isset($_POST['submit'])){
    $fname = trim($_POST["fname"]);
    $mname = trim($_POST["mname"]);
    $lname = trim($_POST["lname"]);
    $age = $_POST["age"];
    $bday = $_POST["bday"];
    $gender = $_POST["sex"];
    $cnumber = $_POST["cnumber"];
    $email = $_POST["email"];
    $usern = $_POST["userr"];
    $passw = $_POST["passw"];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script language="javascript">alert("Invalid email format!");</script>';
        exit;
    }

    if (!preg_match("/^09\d{9}$/", $cnumber)) {
        echo '<script language="javascript">alert("Invalid contact number! It should start with 09 and be 11 digits long.");</script>';
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO users (fname, mname, lname, age, bday, gender, 
    cnumber, email, uname, pword, category) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'staff')");
    $stmt->bind_param("sssissssss", $fname, $mname, $lname, $age, $bday, $gender, $cnumber, $email, $usern, $passw);
    if ($stmt->execute()) {
        echo '<script language="javascript">alert("Account Successfully created!");</script>';
        echo '<script language="javascript">window.location.href = "../admin-employee.php";</script>';
        
    } else {
        echo "Error: " . $stmt->error;
    }

}else{
    echo '<script language="javascript">alert("err!");</script>';
        echo '<script language="javascript">window.location.href = "../admin-employee.php";</script>';
}

?>