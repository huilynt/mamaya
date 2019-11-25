<?php
// Detect the current session
session_start();

// Reading inputs entered in previous page
$email = $_POST["email"];
$pwd = $_POST["password"];

// To Do 1 (Practical 2): Validate login credentials with database

// Include the utility class file for MySQL database access
include("mysql.php");
// Create an  object for MySQL database access
$conn = new Mysql_Driver();
// Connect to the MySQL database
$conn->connect();
// Define the INSERT SQL statement
$qry = "SELECT * FROM Shopper WHERE email = '$email' AND password = '$pwd'";
// Execute the SQL statement
$result = $conn->query($qry);

if ($result == true) { // SQL statement executed successfully

	if (mysqli_num_rows($result)) {
		while ($row = $conn->fetch_array($result)) {
			$_SESSION["ShopperID"] = $row["ShopperID"];
			$_SESSION["ShopperName"] = $row["Name"];
		}
		// To Do 2 (Practical 4): Get active shopping cart

		// Redirect to home page
		header("Location: index.php");
		exit;
	} else {
		$MainContent = "<h3 style='color:red'>Invalid Login Credentials</h3>";
	}
} else {
	$MainContent = "<h3 style='color:red'>Connection failed</h3>";
}

// Close database connection
$conn->close();

include("MasterTemplate.php");
