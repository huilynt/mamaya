<?php
// Detect the current session
session_start();
// Check if user logged in 
if (!isset($_SESSION["ShopperID"])) {
	// redirect to login page if the session variable shopperid is not set
	header("Location: login.php");
	exit;
}

// Include the class file for database access
include("mysql.php");
$conn = new Mysql_Driver();
$conn->connect();

if (isset($_SESSION["Cart"])) {
	// To Do 1 (Practical 4): 
	// Retrieve from database and display shopping cart in a table
	$qry = "SELECT ProductID, Name, Price, Quantity, (Price*Quantity) AS Total 
			FROM shopcartitem WHERE ShopCartID=$_SESSION[Cart]";
	$result = $conn->query($qry);

	if ($conn->num_rows($result) > 0) {
		// To Do 2 (Practical 4): Format and display 
		// the page header and header row of shopping cart page
		$MainContent = "<p class='page-title' style='text-align:center'>Shopping Cart</p>";
		$MainContent .= "<div class='table-responsive'>";
		$MainContent .= "<table class='table table-hover'>";
		$MainContent .= "<thead class='cart-header'>";
		$MainContent .= "<tr>";
		$MainContent .= "<th width='40px'>Product ID</th>";
		$MainContent .= "<th width='250px'>Name</th>";
		$MainContent .= "<th width='90px'>Price (S$)</th>";
		$MainContent .= "<th width='60px'>Quantity</th>";
		$MainContent .= "<th width='120px'>Total (S$)</th>";
		$MainContent .= "<th>&nbsp;</th>";
		$MainContent .= "<th>&nbsp;</th>";
		$MainContent .= "</tr>";
		$MainContent .= "</thead>";


		// To Do 5 (Practical 5):
		// Declare an array to store the shopping cart items in session variable 
		$_SESSION["Items"] = array();

		// To Do 3 (Practical 4): 
		// Display the shopping cart content
		$MainContent .= "<tbody>";
		while ($row = $conn->fetch_array($result)) {
			$MainContent .= "<tr>";
			$MainContent .= "<td>$row[ProductID]</td>";
			$MainContent .= "<td>$row[Name]</td>";
			$formattedPrice = number_format($row["Price"], 2);
			$MainContent .= "<td>$formattedPrice</td>";
			$MainContent .= "<form action='cart-functions.php' method='post'>";
			$MainContent .= "<td>";
			$MainContent .= "<input type='number' name='quantity' style='width:40px' value='$row[Quantity]' min='1' max='10' required />";
			$MainContent .= "</td> ";
			$formattedTotal = number_format($row["Total"], 2);
			$MainContent .= "<td>$formattedTotal</td>";
			$MainContent .= "<td>";
			$MainContent .= "<input type='hidden' name='actionU' value='update'>";
			$MainContent .= "<input type='hidden' name='product_id' value='$row[ProductID]'/>";
			$MainContent .= "<button type='submit'>Update</button>";
			$MainContent .= "</td>";
			$MainContent .= "</form>";
			$MainContent .= "<form action='cart-functions.php' method='post'>";
			$MainContent .= "<td>";
			$MainContent .= "<input type='hidden' name='actionR' value='remove'>";
			$MainContent .= "<input type='hidden' name='product_id' value='$row[ProductID]'/>";
			$MainContent .= "<button type='submit'>Remove</button>";
			$MainContent .= "</td>";
			$MainContent .= "</form> ";
			$MainContent .= "</tr> ";
		}
		$MainContent .= "</tbody>";
		$MainContent .= "</table>";
		$MainContent .= "</div>";

		// To Do 6 (Practical 5):
		// Store the shopping cart items in session variable as an associate array
		$_SESSION["Items"][] = array(
			"productId" => $row["ProductID"],
			"name" => $row["Name"],
			"price" => $row["Price"],
			"quantity" => $row["Quantity"]
		);

		// To Do 4 (Practical 4): 
		// Display the subtotal at the end of the shopping cart
		$qry = "SELECT SUM(Price*Quantity) as SubTotal FROM shopcartitem WHERE ShopCartID=$_SESSION[Cart]";
		$result = $conn->query($qry);
		$row = $conn->fetch_array($result);
		$MainContent .= "<p style='text-align:right'> Subtotal = S$" . number_format($row["SubTotal"], 2);
		$_SESSION["SubTotal"] = round($row["SubTotal"], 2);
		$MainContent .= "</p>";

		// To Do 7 (Practical 5):
		// Add PayPal Checkout button on the shopping cart page
		$MainContent .= "<form method='post' action='process.php'>";
		$MainContent .= "<input type='image' style='float:right;'
						 src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>";
		$MainContent .= "</form></p>";
	} else {
		$MainContent = "<span style='font-weight:bold; color:red;'>
		                Empty shopping cart!</span>";
	}
} else {
	$MainContent = "<span style='font-weight:bold; color:red;'>
	                Empty shopping cart!</span>";
}

$conn->close();
include("MasterTemplate.php");
