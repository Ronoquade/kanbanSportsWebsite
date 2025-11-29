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
    <title>Sports Apparel - Delete Product</title>
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
            <h2>Delete Product</h2>
        </header>

        <?php
            include "mainMenu.php";
        ?>
        <form action="deleteProduct.php" class="w3-container w3-sand" method="POST">
            <fieldset>
                <label>Product</label>
                <select name="product" class="w3-select">
                    <option value="" disabled selected>Choose Product</option>
                    <?php
                        include "connectDatabase.php";
                        //fetch only products that are not associated with any orders
                        $sql = "SELECT * ";
                        $sql .= "FROM product p LEFT JOIN productorder po ";
                        $sql .= "ON p.product_id = po.product_id ";
                        $sql .= "WHERE po.product_id IS NULL ";

                        $result = $conn->query($sql);

                        if($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $product_id = $row['product_id'];
                                $productName = $row['name'];
                                
                                echo "<option value='$product_id'>$product_id- $productName</option>";
                            }
                        }
                        $conn->close();
                    ?>
                </select><br>
                <b>NOTE</b>: Only products without orders can be deleted.
            </fieldset>
            <br><input type="submit" name="submit" class="w3-btn w3-blue-grey" value="Delete product" />
        </form>
        <div class="w3-container w3-sand">
            <?php
                if(isset($_POST['submit'])) {
                    if(!isset($_POST['product'])) {
                        echo "You have not entered all the required information. Please go back and try again.";
                        exit;
                    }
                $product_id = $_POST['product'];

                include "connectDatabase.php";

                $sql = "DELETE ";
                $sql .= "FROM product ";
                $sql .= "WHERE product_id = $product_id";

                if($conn->query($sql) === TRUE) {
                    echo "<b>Customer record for product deleted successfully!</b><br>";
                    
                }
                else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
                $conn->close();

                //refresh the page to update the customer list
                header("Refresh:0");
            }
            ?>
        </div>
    </div>
</body>
</html>
