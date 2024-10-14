<?php

include 'config.php';
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
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo '<script language="javascript">alert("Record deleted successfully.");</script>';
        echo '<script language="javascript">window.location.href = "admin-products.php";</script>';
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/admin-emplist.css">
    <link rel="stylesheet" type="text/css" href="css/admin-products.css">
    <link rel="icon" href="images/logo.ico" type="image/x-icon">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <title>Edz FashionHauz</title>
</head>
<body>
    <?php include 'admin-nav.php'; ?>
    <div class="upper margin">
        <button id="addpd"><img src="icons/create.png" alt="create" >Create</button>
    </div>
    <h1 class="content-title">Product List</h1>
    <section>
        <div class="table-bg">
            <table>
                <thead>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Size</th>
                    <th>Category</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Creation Date</th>
                    <th>Action</th>
                </thead>
                <tbody>
                <?php $select = mysqli_query($conn,"SELECT * FROM products");
                    while($row=mysqli_fetch_assoc($select)){         
                ?>
                    <tr>
                        <td><img src="products/<?php echo $row['image']; ?>" alt="aaa" class="prodimg"></td>
                        <td><?php echo $row['prodname']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['size']; ?></td>
                        <td><?php echo $row['category']; ?></td>
                        <td><?php echo $row['qty']; ?></td>
                        <td>₱<?php echo $row['price']; ?></td>
                        <td><?php echo $row['date_created']; ?></td>
                        <td>
                            <div class="option">
                                <a href="form-actions/product-update.php?edit=<?php echo $row['id']; ?>" class="op1"> Update</a>
                                <a href="admin-products.php?delete=<?php echo $row['id']; ?>" class="op2" onclick="return confirm('Are you sure you want to delete this record?');"> Delete</a>
                            </div>
                        </td>
                    </tr>
                    <?php   };  ?>
                </tbody>
            </table>
        </div>
    </section>
    <div class="create-part" id="newprod">
        <div class="box-column">
            <img src="icons/close-bg.png" id="ekis" alt="asdasdas">
            <h1>Add Product</h1>
            <form action="form-actions/addproduct.php" method="post" enctype="multipart/form-data">
            <div class="flex-box">
                <div class="left-box">
                    <input type="text" placeholder="Name" name="prodname" required>
                    <input type="text" placeholder="Description" name="desc" >
                    <input type="text" placeholder="Size" name="prodsize" required>
                </div>
                <div class="right-box">
                    <input type="text" placeholder="Category" name="cate"  required>
                    <input type="text" placeholder="Quantity" name="qty" required>
                    <input type="text" placeholder="Price" name="price" required>
                    <input type="file" placeholder="Image" accept=".jpg,.jpeg,.png,.webp" name="prodimg" required>
                </div>
            </div>
            <button type="submit" name="submit"><img src="icons/create.png" alt="add">Add</button>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var texts = document.getElementById("newprod");
            
            document.getElementById("addpd").onclick = function() {
                texts.style.display = "flex"; 
            }; 
            document.getElementById("ekis").onclick = function() {
                texts.style.display = "none";
            }; 
        });
    </script>
</body>
</html>