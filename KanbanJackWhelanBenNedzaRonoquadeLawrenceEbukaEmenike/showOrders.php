<?php
$document_root = $_SERVER['DOCUMENT_ROOT'];
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
            <img src="SportsApparel.png" width="20%" height="22%"
            class="w3-display-topright">
        </header>

        <?php include "mainMenu.php"; ?>
    
        <div class="w3-container w3-blue-grey">
            <?php
            include "connectDatabase.php";

            $sql  = "SELECT o.order_id, c.customer_id, c.firstName, c.lastName,
            o.date, p.name, o.totalPrice ";
            $sql .= "FROM orders o ";
            $sql .= "JOIN customer c ON c.customer_id = o.customer_id ";
            $sql .= "JOIN product p ON p.product_id = o.product_id ";
            $sql .= "ORDER BY o.order_id ";

            $result = $conn->query($sql);

            if($result->num_rows > 0) {
                echo "<table class='w3-table w3-striped'>";
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
                    echo "  <td>".$row['order_id']."</td>";
                    echo "  <td>".$row['firstName']."</td>";
                    echo "  <td>".$row['lastName']."</td>";
                    echo "  <td>".$row['date']."</td>";
                    echo "  <td>".$row['name']."</td>";
                    echo "  <td>".$row['totalPrice']."</td>";
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


