<?php
# initialize the session
session_start();

# check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: newOrder.php");
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
        .w3-pacifico { font-family: "Pacifico", serif; }
        h1 { font-family: "Pacifico", serif; }
    </style>
</head>
<body class="w3-theme">
    <header class="w3-container w3-center w3-blue-gray">
        <h1>Sports Apparel</h1>
        <h2>Delete Results</h2>
        <img src="SportsApparel.png" width="20%" height="22%"
        class="w3-display-topright">
    </header>

    <?php include "mainMenu.php"; ?>
    
    <form action="newOrder.php" class="w3-container w3-blue-grey" method="POST">
        <fieldset>
            <label>Customer</label>
        </fieldset>
    </form>
    <div class="w3-container w3-light-grey">
        <?php
        $orders = file("orders.txt");
        $number_of_orders = count($orders);
        if($number_of_orders == 0) {
            echo "<p><strong>No orders pending!<br>
                 Please try again later.</strong></p>";
        } else {
            echo "<table class='w3-table w3-striped w3-border'>";
            echo "  <tr class='w3-blue-gray'>";
            echo "      <th>Datetime</th>";
            echo "      <th>Product</th>";
            echo "      <th>Quantity</th>";
            echo "      <th>Total</th>";
            echo "  </tr>";

            for($i = 0; $i < $number_of_orders; $i++) {
                $curOrder = explode(';', $orders[$i]);
                echo "<tr>";
                for($j = 0; $j < count($curOrder); $j++) {
                    echo "<td>".$curOrder[$j]."</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        }
        ?>
    </div>
</body>
</html>
