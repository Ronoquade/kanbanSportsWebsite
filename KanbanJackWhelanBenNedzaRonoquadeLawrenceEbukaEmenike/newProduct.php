<?php
    session_start();
    include "utilFunctions.php";

    # check if the user is already logged in, if yes then redirect to login page
    if(!isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"]) !== true){
        header("location: index.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sports Apparel - New Product</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Pacifico">
    <style>
        .w3-pacifico { font-family: "Pacifico", serif; }
        h1 { font-family: "Pacifico", serif; }
    </style>
</head>
<body>
    <div class="w3-container w3-blue-grey">
        <header class="w3-display-container w3-center">
            <div class="w3-display-right w3-container">
                <img src="SportsApparel.png" alt="" style="width:20%">
            </div>

            <h1>Sports Apparel</h1>
            <h2>New Product</h2>
        </header>

        <?php
            include "mainMenu.php";
        ?>
        <form action="newProduct.php" class="w3-container w3-sand" method="POST">
            <fieldset>
                <label>Name</label>
                <input class="w3-input w3-border" type="text" name="productName" required>

                <label>Brand</label>
                <input class="w3-input w3-border" type="text" name="brand" required>

                <label>Price</label>
                <input class="w3-input w3-border" type="text" name="price" required>
            </fieldset>
            <br><input type="submit" name="submit" class="w3-btn w3-blue-grey" value="Add New Product">
        </form>
        <div class="w3-container w3-sand">
            <?php
                if(isset($_POST['submit'])) {
                    if(!isset($_POST['productName']) || !isset($_POST['brand']) || !isset($_POST['price'])) {
                        echo "You have not entered all the required information. Please go back and try again.";
                        exit;
                    }
                
                include "connectDatabase.php";

                // create short variable names
                $productName = mysqli_real_escape_string($conn, $_POST['productName']);
                $brand = mysqli_real_escape_string($conn, $_POST['brand']);
                $price = mysqli_real_escape_string($conn, $_POST['price']);

                $sql = "INSERT INTO product (name, brand, price)
                        VALUES ('$productName', '$brand', '$price')";
                if($conn->query($sql) === TRUE) {
                    $product_id = $conn->insert_id;
                    echo "<b>Product created successfully!</b><br>";
                    echo "Product ID: $product_id<br>";
                    echo "Name: $productName<br>";
                    echo "Brand: $brand<br>";
                    echo "Price: $price<br>";
                }
                $conn->close();
            }
            ?>
        </div>
    </div>
</body>
</html>
