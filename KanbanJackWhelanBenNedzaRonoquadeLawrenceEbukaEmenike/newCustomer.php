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
    <title>Sports Apparel - New Customer</title>
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
            <h2>New Customer</h2>
        </header>

        <?php
            include "mainMenu.php";
        ?>
        <form action="newCustomer.php" class="w3-container w3-sand" method="POST">
            <fieldset>
                <label>First Name</label>
                <input class="w3-input w3-border" type="text" name="fName" required>

                <label>Last Name</label>
                <input class="w3-input w3-border" type="text" name="lName" required>

                <label>Address</label>
                <input class="w3-input w3-border" type="text" name="address" required>

                <label>City</label>
                <input class="w3-input w3-border" type="text" name="city" required>

                <label>State</label>
                <input class="w3-input w3-border" type="text" name="state" required>

                <label>Zip</label>
                <input class="w3-input w3-border" type="text" name="zip" required>
            </fieldset>
            <br><input type="submit" name="submit" class="w3-btn w3-blue-grey" value="Add New Customer">
        </form>
        <div class="w3-container w3-sand">
            <?php
                if(isset($_POST['submit'])) {
                    if(!isset($_POST['fName']) || !isset($_POST['lName']) || !isset($_POST['address']) || !isset($_POST['city']) || !isset($_POST['state']) || !isset($_POST['zip'])) {
                        echo "You have not entered all the required information. Please go back and try again.";
                        exit;
                    }
                
                include "connectDatabase.php";

                // create short variable names
                $fName = mysqli_real_escape_string($conn, $_POST['fName']);
                $lName = mysqli_real_escape_string($conn, $_POST['lName']);
                $address = mysqli_real_escape_string($conn, $_POST['address']);
                $city = mysqli_real_escape_string($conn, $_POST['city']);
                $state = mysqli_real_escape_string($conn, $_POST['state']);
                $zip = mysqli_real_escape_string($conn, $_POST['zip']);

                $sql = "INSERT INTO customer (firstName, lastName, address, city, state, zip)
                        VALUES ('$fName', '$lName', '$address', '$city', '$state', '$zip')";
                if($conn->query($sql) === TRUE) {
                    $customer_id = $conn->insert_id;
                    echo "<b>Customer created successfully!</b><br>";
                    echo "Customer ID: $customer_id<br>";
                    echo "First Name: $fName<br>";
                    echo "Last Name: $lName<br>";
                    echo "Address: $address<br>";
                    echo "City: $city<br>";
                    echo "State: $state<br>";
                    echo "Zip: $zip<br>";
                }
                $conn->close();
            }
            ?>
        </div>
    </div>
</body>
</html>
