<?php
# initialize the session
session_start();

# check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Order</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Pacifico">
    <style>
        h1 { font-family: "Pacifico", serif; }
    </style>
</head>
<body class="w3-theme">
    <header class="w3-container w3-center w3-blue-gray">
        <h1>Sports Apparel</h1>
        <h2>Delete Order</h2>
        <img src="SportsApparel.png" width="20%" height="22%"
        class="w3-display-topright">
    </header>

    <?php include "mainMenu.php"; ?>
    
    <form action="deleteOrder.php" class="w3-container w3-blue-grey" method="POST">
        <fieldset>
            <label>Order</label>
            <select name="order_id" class="w3-select" required>
                <option value="" disabled selected>Choose Order</option>
                <?php

                include "connectDatabase.php";

                $sql  = "SELECT o.order_id, o.totalPrice, o.date, 
                c.firstName, c.lastName ";
                $sql .= "FROM orders o ";
                $sql .= "JOIN customer c ON c.customer_id = o.customer_id ";
                $sql .= "ORDER BY o.order_id ";

                $result = $conn->query($sql);

                while($row = $result->fetch_assoc()) {
                    $orderId = $row['order_id'];
                    $customerFirstName = $row['firstName'];
                    $customerLastName = $row['lastName'];

                    echo "<option value='$orderId'>
                    Order $orderId - $customerLastName, 
                    $customerFirstName - $$price - $date
                    </option>";
                }

                $conn->close();
                ?>
            </select><br><br>
        </fieldset>
        <br>
        <input type="submit" name="delete" class="w3-btn w3-blue-grey"
        value="Delete Order">
    </form>

    <div class="w3-container w3-blue-grey">
        <?php
        if(isset($_POST['delete'])) {
            
            $order_id = $_POST['order_id'];

            include "connectDatabase.php";

            $sql = "DELETE FROM orders WHERE order_id = '$order_id'";
            
            if($conn->query($sql) === TRUE) {
                echo "<b>Order $order_id deleted successfully!</b>";
            } else {
                echo "Error: " . $conn->error;
            }

            $conn->close();
        }   
        ?>
    </div>

</body>
</html>
