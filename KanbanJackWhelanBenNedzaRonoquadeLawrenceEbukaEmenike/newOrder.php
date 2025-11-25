<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order</title>
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
        <h2>New Order</h2>
        <img src="SportsApparel.png" width="20%" height="22%"
        class="w3-display-topright">
    </header>

    <?php include "mainMenu.php"; ?>
    
    <form action="newOrder.php" class="w3-container w3-blue-grey" method="POST">
        <fieldset>
            <label>Customer</label>
            <select name="customer" class="w3-select">
                <option value="" disabled selected>Choose Customer</option>
                <?php
                include "connectDatabase.php";
                $sql  = "SELECT c.customer_id, c.firstName, c.lastName ";
                $sql .= "FROM customer c ";

                $result = $conn->query($sql);

                if($result->num_rows > 0) 
                    while($row = $result->fetch_assoc()) {
                        $customerId = $row['customer_id'];
                        $customerFirstName = $row['firstName'];
                        $customerLastName = $row['lastName'];

                        echo "<option value='$customerId'>$customerId-$customerLastName,
                        $customerFirstName</option>";
                    }

                $conn->close();
                ?>
            </select><br><br>

            <div class="container w3-khaki w3-padding w3-border-blue-grey 
            w3-leftbar w3-topbar w3-bottombar w3-rightbar">
            <input id="sportsWearSel" name="sportsWearSel" value="None" type="hidden">
            <label>Selected Sports Wear</label>
                <select class="w3-select" name="listSportsWear" id="listSportsWear">

                </select><br>
                <input class="w3-button w3-teal w3-round-large"
                value="Remove Sportswear" onclick='removeSportsWear()'><br>

                <label>Available Sports Wear</label>
                <select class="w3-select" name="listSportsWearAv" id="listSportsWearAv">
                    <?php
                    include "connectDatabase.php";
                    $sql  = "SELECT p.product_id, p.name, p.brand, p.price ";
                    $sql .= "FROM product p ";

                    $result = $conn->query($sql);

                    if($result->num_rows > 0)
                        while($row = $result->fetch_assoc()) {
                            $productId = $row['product_id'];
                            $productName = $row['name'];
                            $productBrand = $row['brand'];
                            $productPrice = $row['price'];

                            echo "<option value='$productId'>$productId-$productName,
                            $productBrand, $productPrice</option>";
                        }
                    $conn->close();
                    ?>
                </select><br>
                <input class="w3-button w3-teal w3-round-large" value="Add Sportswear"
                onclick='addSportsWear()'><br>
            </div>
            <br>
            <label>Total Price: $</label>
            <input name="price" id="price">
        </fieldset>
        <br><input type="submit" name="submit" class="w3-btn w3-blue-grey"
        value="Add New Sportswear">
    </form>
    <div class="w3-container w3-blue-grey">
        <?php
        if(isset($_POST['submit'])) {
            if(!isset($_POST['customer']) || !isset($_POST['price'])) {
                echo "<p>You have not entered all the required details.<br />
                        Please go back and try again.</p>";
                exit;
            }

            include "connectDatabase.php";

            // create short variable names
            $customer_id = mysqli_real_escape_string($conn, $_POST['customer']);
            $price = mysqli_real_escape_string($conn, $_POST['price']);
            $brandString = mysqli_real_escape_string($conn, $_POST['sportsWearSel']);
            $date = date("Y-m-d");

            $sql = "INSERT INTO orders (customer_id, totalPrice, date) VALUES 
            ('$customer_id', '$price', '$date')";

            if($conn->query($sql) === TRUE) {
                    $order_id = $conn->insert_id;
                    echo "<b>Order created successfully!</b><br>";
                    echo "Order Id: $order_id<br>";
                    echo "Customer Id: $customer_id<br>";
                    echo "Total Price: $" . number_format($price,2) . "<br>";
                    echo "<hr>";

                    $productIdArray = explode(";", $brandString);
                    foreach($productIdArray as $curProductId) {
                        if(empty($curProductId))
                            continue;

                        $sql = "INSERT INTO orders (order_id, product_id) VALUES
                        ('$order_id', '$curProductId')";

                        if($conn->query($sql) === TRUE)
                            echo "Product Id: $curProductId added successfully!<br>";
                        else
                            echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
                $conn->close();
            }
        ?>
    </div>
    <script>
        function addSportsWear() {
            var listSel = document.getElementById('listSportsWear');
            var listAv = document.getElementById('listSportsWearAv');
            var hidden = document.getElementById('sportsWearSel');

            if(listAv.options.length < 1) 
                return;

            var listIndex = listAv.selectedIndex;
            var listText = listAv.options[listIndex].text;
            var listVal = listAv.options[listIndex].value;

            listSel.options[listSel.options.length] = new Option(
            listText, listVal);
            listAv.remove(listIndex);

            updateHiddenField();
            calcTotalPrice();
        }

        function removeSportsWear() {
            var listSel = document.getElementById('listSportsWear');
            var brandAv = document.getElementById('listSportsWearAv');
            var hidden = document.getElementById('sportsWearSel');

            if(listSel.options.length < 1) 
                return;

            var listIndex = listSel.selectedIndex;
            var listText = listSel.options[listIndex].text;
            var listVal = listSel.options[listIndex].value;

            listAv.options[listAv.options.length] = new Option(
            listText, listVal);
            listSel.remove(listIndex);

            updateHiddenField();
            calcTotalPrice();
        }

        function updateHiddenField() {
            let listSel = document.getElementById('listSportsWear');
            let hidden = document.getElementById('sportsWearSel');

            let ids = [];

            for(let i=0; i < listSel.options.length; i++) {
                ids.push(listSel.options[i].value);
            }

            hidden.value = ids.join(';');
        }

        function calcTotalPrice() {
            var listSel = document.getElementById('listSportsWear');
            var priceOut = document.getElementById('price');
            let totalPrice = 0;

            for(let i=0; i < listSel.options.length; i++) {
                let parts = listSel.options[i].text.split(',');
                let price = parseFloat(parts[2]);
                totalPrice += price;
            }

            priceOut.value = totalPrice.toFixed(2);
        }
    </script>
</body>
</html>
