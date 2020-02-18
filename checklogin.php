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
$qry = "SELECT * FROM Shopper WHERE email = '$email'";
// Execute the SQL statement
$result = $conn->query($qry);

if ($conn->num_rows($result) > 0) {
	$row = $conn->fetch_array($result);
	// Get the hashed password from database
	$hashed_pwd = $row["Password"];
	if (password_verify($pwd, $hashed_pwd)) {
		//Save user's info in session variables
		$_SESSION["ShopperID"] = $row["ShopperID"];
		$_SESSION["ShopperName"] = $row["Name"];

		// To Do 2 (Practical 4): Get active shopping cart
		$qry = "SELECT * FROM shopcart WHERE ShopperID=$_SESSION[ShopperID] AND OrderPlaced=0";
		$result = $conn->query($qry);

		if ($conn->num_rows($result) > 0) {
			$row = $conn->fetch_array($result);
			$_SESSION["Cart"] = $row["ShopCartID"];

			$qry = "SELECT * FROM shopcartitem WHERE ShopCartID=$_SESSION[Cart]";
			$result = $conn->query($qry);
			if ($conn->num_rows($result) > 0) {
				$count = mysqli_num_rows($result);
				$_SESSION["NumCartItem"] = $count;
			}
		}

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
