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
            if(!isset($_POST['customer']) || !isset($_POST['brand']) || 
            !isset($_POST['price'])) {
                echo "<p>You have not entered all the required details.<br />
                        Please go back and try again.</p>";
                exit;
            }

            include "connectDatabase.php";

            // create short variable names
            $customer_id = mysqli_real_escape_string($conn, $_POST['customer']);
            $price = mysqli_real_escape_string($conn, $_POST['price']);
            $brand = mysqli_real_escape_string($conn, $_POST['brand']);
            $date = date("Y-m-d");

            $sql = "INSERT INTO orders (customer_id, productBrand, totalPrice, date) VALUES 
            ('$customer_id', '$brand', '$price', '$date')";

            if($conn->query($sql) === TRUE) {
                    $order_id = $conn->insert_id;
                    echo "<b>Order created successfully!</b><br>";
                    echo "Order Id: $order_id<br>";
                    echo "Created on: $orderDate<br>";
                    echo "Customer Id: $customer_id<br>";
                    echo "Product Brand: $brand<br>";
                    echo "Total Price: $price<br>";
                    echo "<hr>";

                    $productIdArray = explode(";", $brand);
                    for($i=0; $i < count($productIdArray); $i++) {
                        $curProductId = $productIdArray[$i];

                        if(empty($curProductId))
                            continue;

                        $sql = "INSERT INTO orders (order_id, customer_id) VALUES
                        ('$order_id', '$curCustomerId')";

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
        function addProduct() {
            var brandSel = document.getElementById('brandSel');
            var brandAv = document.getElementById('brandAv');
            var productSel = document.getElementById('productSel');

            if(brandAv.options.length < 1) 
                return;

            var brandAvIndex = brandAv.selectedIndex;
            var brandAvInner = brandAv[brandAvIndex].innerHTML;
            var brandAvVal = brandAv[brandAvIndex].value;

            brandSel.options[brandSel.options.length] = new Option(
            brandAvInner, brandAvVal);

            brandAv[brandAvIndex] = null;

            sortSelect(brandSel);
            result="";
            for(i=0; i < brandSel.options.length; i++)
                result += brandSel.options[i].value + ";";

            brandSel.value = result;
            calcTotalPrice();
        }

        function removeProduct() {
            var brandSel = document.getElementById('brandSel');
            var brandAv = document.getElementById('brandAv');
            var productSel = document.getElementById('productSel');

            if(brandSel.options.length < 1) 
                return;

            var brandSelIndex = brandSel.selectedIndex;
            var brandSelInner = brandSel[brandSelIndex].innerHTML;
            var brandSelVal = brandSel[brandSelIndex].value;

            brandAv.options[brandAv.options.length] = new Option(
            brandSelInner, brandSelVal);

            brandSel[brandSelIndex] = null;

            sortSelect(brandAv);
            result="";
            for(i=0; i < brandSel.options.length; i++)
                result += brandSel.options[i].value + ";";

            productSel.value = result;
            calcTotalPrice();
        }

        function calcTotalPrice() {
            var brandSel = document.getElementById('brandSel');
            var priceOut = document.getElementById('price');
            var totalPrice = 0;

            for(i=0; i < brandSel.options.length; i++) {
                curPrice = brandSel.options[i].innerHTML.split(',')[1];
                curPrice = parseFloat(curPrice);
                totalPrice += curPrice;
            }

            priceOut.value = formatAmt(totalPrice);
        }
    </script>
</body>
</html>
