<?php
include '../config.php';
function isValidUser($conn, $username) {
    $stmt = $conn->prepare("SELECT uname FROM users WHERE uname = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0; // Returns true if the user exists
}
if (isset($_COOKIE['username'])) {
    $username = htmlspecialchars($_COOKIE['username']);

    // Validate the cookie against the database
    if (isValidUser($conn, $username)) {
        
    } else {
        echo "Invalid session. Please log in again.";
        // Optionally, delete the cookie if invalid
        setcookie("username", "", time() - 604800, "/");
        setcookie("category", "", time() - 604800, "/");
        setcookie("loggedin", "", time() - 604800, "/");
        setcookie("id", "", time() - 604800, "/");
    }
} else {
    echo "Hello, Guest! Please log in.";
    header("location: index.php");//palitan to pag may login page na
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = $_POST["id"];
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

    $stmt = $conn->prepare("SELECT userID FROM users WHERE email = ? AND userID != ?");
    $stmt->bind_param("si", $email, $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo '<script language="javascript">alert("Email already exists! Please use a different email address.");</script>';
        exit;
    }

    $stmt->close();

    $stmt = $conn->prepare("UPDATE users SET fname = ?, mname = ?, lname = ?, age = ?, bday = ?, gender = ?, 
    cnumber = ?, email = ?, uname = ?, pword = ? WHERE userID = ?");
    $stmt->bind_param("sssisssssss", $fname, $mname, $lname, $age, $bday, $gender, $cnumber, $email, $usern, $passw, $id);
    if ($stmt->execute()) {
        echo '<script language="javascript">alert("Account Successfully Updated!");</script>';
        echo '<script language="javascript">window.location.href = "../admin-employee.php";</script>';
        
    } else {
        echo "Error: " . $stmt->error;
    }
}
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $id = (int)$id;
    $stmt = $conn->prepare("SELECT * FROM users WHERE userID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        $row = $result->fetch_assoc();
        $id = $row['userID'];
        $fname = $row['fname'];
        $mname = $row['mname'];
        $lname = $row['lname'];
        $age = $row['age'];
        $bday = $row['bday'];
        $gender = $row['gender'];
        $cnumber = $row['cnumber'];
        $email = $row['email'];
        $uname = $row['uname'];
        $pword = $row['pword'];
        $category = $row['category'];
        
    } else {
        echo "No results found.";
    }
    
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/admin-update-account.css">
    <link rel="icon" href="../images/logo.ico" type="image/x-icon">
    <title>Edz FashionHauz</title>
</head>
<body>
    <?php include 'admin-nav.php'; ?>
    <div class="create-part" id="changethis">
        <div class="box-column">
            <h1>Update Account</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="flex-box">
                <div class="left-box">
                    <input type="hidden" name="id" value="<?php echo $id; ?>" required>
                    <input type="text" placeholder="First Name" name="fname" value="<?php echo $fname; ?>" required>
                    <input type="text" placeholder="Middle Name" name="mname" value="<?php echo $mname; ?>" >
                    <input type="text" placeholder="Last Name" name="lname" value="<?php echo $lname; ?>" required>
                    <input type="number" placeholder="Age" name="age" value="<?php echo $age; ?>" required>
                    <input type="date" id="bday" placeholder="Birthday" name="bday" value="<?php echo $bday; ?>"  required>
                </div>
                <div class="right-box">
                <div class="sex-part">
                    <label for="sex">Sex: </label>
                    <label for="male">Male</label>
                    <input type="radio" id="male" name="sex" value="male"<?php if ($gender === 'male') echo 'checked'; ?>>
                    <label for="female">Female</label>
                    <input type="radio" id="female" name="sex" value="female"<?php if ($gender === 'female') echo 'checked'; ?>>
                    <label for="other">Other</label>
                    <input type="radio" id="other" name="sex" value="other" <?php if ($gender === 'other') echo 'checked'; ?>>
                </div>
                <input type="text" placeholder="Contact" name="cnumber" oninput="validateInput(event)" value="<?php echo htmlspecialchars($cnumber); ?>" required>
                <input type="email" placeholder="Email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                <input type="text" placeholder="Username" name="userr" value="<?php echo htmlspecialchars($uname); ?>" required>
                <input type="password" placeholder="New Password" name="passw" required>
                </div>
            </div>
            <button type="submit" name="submit"><img src="../icons/create.png" alt="add">Add</button>
            </form>
        </div>
    </div>
    
</body>
</html>