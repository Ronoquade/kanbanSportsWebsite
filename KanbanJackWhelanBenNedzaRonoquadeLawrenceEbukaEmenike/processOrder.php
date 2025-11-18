<?php
    $productsArray = [
        0 => ['Swimsuits', 50.00],
        1 => ['Ski Suits', 65.00],
        2 => ['Yoga Pants', 45.00],
        3 => ['Jerseys', 55.00],
        4 => ['Cycling Helmets', 40.00],
        5 => ['Sports Bras', 60.00],
        6 => ['Athletic Shoes', 35.00],
        7 => ['T-shirts', 25.00],
        8 => ['Shorts', 25.00]
    ];

    $productIndex = (int) $_POST['product'];
    $productSelected = $productsArray[$productIndex];
    $productName = $productSelected[0];
    $productPrice = $productSelected[1];
    $quantity = (int) $_POST['quantity'];
    $document_root = $_SERVER['DOCUMENT_ROOT'];
    $date = date('d/m/Y h:i:s');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Results</title>
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
        <h2>Order Results</h2>
        <img src="SportsApparel.png" width="20%" height="22%"
        class="w3-display-topright">
    </header>

    <?php include "mainMenu.php"; ?>
    
    <div class="w3-container w3-light-grey">
        <?php
        if($quantity <= 0) {
            echo "You did not order anything on the previous page!<br>";
            echo "Please complete the form and try again.<br>";
            exit;
        }

        echo "Order processed at ".date('H:i, jS F Y')."<br>";
        echo "Your order is as follows: <br>";
        echo "Item ordered: $productName<br>";
        echo "Quantity: ".$quantity."<br>";
        echo "Unit Price: $productPrice<br>";

        $totalAmount = $quantity * $productPrice;
        echo "Total: $".number_format($totalAmount, 2)."<br>";
        $outputString = $date.";".$productName.";".$quantity.";"
        .$totalAmount."\n";

        @$fp = fopen("orders.txt", 'ab');
        if(!$fp) {
            echo "<p><strong> Your order could not be processed at this time.
                 Please try again later.</strong></p>";
            exit;
        }
        
        flock($fp, LOCK_EX);
        fwrite($fp, $outputString, strlen($outputString));
        flock($fp, LOCK_UN);
        fclose($fp);

        echo "<p>Order written!</p>";
        ?>
    </div>
</body>
</html>