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
    <title>Sports Apparel - Delete Customer</title>
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

            <h1>Sports Store</h1>
            <h2>Delete Customer</h2>
        </header>

        <?php
            include "mainMenu.php";
        ?>
        <form action="deleteCustomer.php" class="w3-container w3-sand" method="POST">
            <fieldset>
                <label>Customer</label>
                <select name="customer" class="w3-select">
                    <option value="" disabled selected>Choose Customer</option>
                    <?php
                        include "connectDatabase.php";

                        $sql = "SELECT c.customer_id, c.firstName, c.lastName ";
                        $sql .= "FROM customer c LEFT JOIN orders o ";
                        $sql .= "ON c.customer_id = o.customer_id ";
                        $sql .= "WHERE o.customer_id IS NULL ";

                        $result = $conn->query($sql);

                        if($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $customer_id = $row['customer_id'];
                                $firstName = $row['firstName'];
                                $lastName = $row['lastName'];
                                echo "<option value='$customer_id'>$customer_id-$lastName, $firstName</option>";
                            }
                        }
                        $conn->close();
                    ?>
                </select><br>
                <b>NOTE</b>: Only customers without orders can be deleted.
            </fieldset>
            <br><input type="submit" name="submit" class="w3-btn w3-blue-grey" value="Delete customer" />
        </form>
        <div class="w3-container w3-sand">
            <?php
                if(isset($_POST['submit'])) {
                    if(!isset($_POST['customer'])) {
                        echo "You have not entered all the required information. Please go back and try again.";
                        exit;
                    }
                $customer_id = $_POST['customer'];

                include "connectDatabase.php";

                $sql = "DELETE ";
                $sql .= "FROM customer ";
                $sql .= "WHERE customer_id = $customer_id";

                if($conn->query($sql) === TRUE) {
                    echo "<b>Customer record deleted successfully!</b><br>";
                    
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
