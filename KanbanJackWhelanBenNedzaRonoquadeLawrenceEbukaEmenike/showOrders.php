<?php
$document_root = $_SERVER['DOCUMENT_ROOT'];
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
    <title>Show Orders</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Pacifico">
    <style>
        .w3-pacifico { font-family: "Pacifico", serif; }
        h1 { font-family: "Pacifico", serif; }
    </style>
</head>
<body>
    <div class="w3-container w3-blue-grey"></div>
        <header class="w3-display-container w3-center">
            <h1>Sports Apparel</h1>
            <h2>Show Results</h2>
            <img src="SportsApparel.png" width="20%" height="90%"
            class="w3-display-topright">
        </header>

        <?php include "mainMenu.php"; ?>
    
        <div class="w3-container w3-grey">
            <?php
            include "connectDatabase.php";
            // Query to fetch order details along with customer and product information
            $sql  = "SELECT c.customer_id AS ID, c.firstName AS 'First Name', c.lastName AS 'Last Name', o.date AS Date,
             d.name AS product, o.totalPrice AS 'Total Price' FROM customer c JOIN orders o ON c.customer_id = o.customer_id
              JOIN productorder do ON o.order_id = do.order_id JOIN product d ON do.product_id = d.product_id ORDER BY o.date DESC ";

            $result = $conn->query($sql);

            if($result->num_rows > 0) {
                echo "<table class='w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white'>";
                echo "  <tr class='w3-teal'>";
                echo "      <th>ID</th>";
                echo "      <th>First Name</th>";
                echo "      <th>Last Name</th>";
                echo "      <th>Date</th>";
                echo "      <th>Product Name</th>";
                echo "      <th>Total Price</th>";
                echo "  </tr>";

                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "  <td>" . $row['ID'] . "</td>";
                    echo "  <td>" . $row['First Name'] . "</td>";
                    echo "  <td>" . $row['Last Name'] . "</td>";
                    echo "  <td>" . $row['Date'] . "</td>";
                    echo "  <td>" . $row['product'] . "</td>";
                    echo "  <td>$" . number_format($row['Total Price'], 2) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "0 results<br>";
            }
            $conn->close();
        ?>
        </div>
    </div>
</body>
</html>


