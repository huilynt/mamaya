<?php
// Detect the current session
session_start();
$MainContent = "";

// Read the data input from previous page
$name = $_POST["name"];
$address = $_POST["address"];
$country = $_POST["country"];
$phone = $_POST["phone"];
$email = $_POST["email"];

// Create a password hash using the default bcrypt algorithm
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);

// Include the utility class file for MySQL database access
include("mysql.php");
// Create an  object for MySQL database access
$conn = new Mysql_Driver();
// Connect to the MySQL database
$conn->connect();
// Define the INSERT SQL statement
$qry = "INSERT INTO Shopper (name, address, country, phone, email, password) VALUES ('$name', '$address', '$country', '$phone', '$email', '$password')";
// Execute the SQL statement
$result = $conn->query($qry);

if ($result == true) { // SQL statement executed successfully
    // Retrieve the Shopper ID assigned to the new shopper
    $qry = "SELECT LAST_INSERT_ID() AS ShopperID";
    $result = $conn->query($qry);
    // Save the Shopper ID in a session variable
    while ($row = $conn->fetch_array($result)) {
        $_SESSION["ShopperID"] = $row["ShopperID"];
    }
    // Display successful message and Shopper ID
    $MainContent .= "Registration successful!<br/>";
    $MainContent .= "Your ShopperID is $_SESSION[ShopperID]<br/>";
    // Save the Shopper Name in a session variable
    $_SESSION["ShopperName"] = $name;
} else { // Display error message
    $MainContent .= "<h3 style='color:red'>Error in inserting record</h3>";
}

// Close database connection
$conn->close();

// Include the master template file for this page
include("MasterTemplate.php");
