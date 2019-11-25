<?php
session_start(); // Detect the current session
// Create a container, 90% width of viewport
$MainContent = "<div style='width:90%; margin:auto;'>";

include("mysql.php");  // Include the class file for database access
$conn = new Mysql_Driver();  // Create an object for database access
$conn->connect(); // Open database connnection
$pid = $_GET["pid"]; // Read Product ID from query string
$qry = "SELECT * from product where ProductID=$pid";
$result = $conn->query($qry); // Execute the SQL statement

// To Do 1:  Display Product information. Starting ....
while ($row = $conn->fetch_array($result)) {
    // Display Page Header -
    // Product's name is read from the "ProductTitle" column of "product" table.
    $MainContent .= "<div class='row'>";
    $MainContent .= "<div class='col-sm-12' style='padding:5px'>";
    $MainContent .= "<span class='page-title'>$row[ProductTitle]</span>";
    $MainContent .= "</div>";
    $MainContent .= "</div>";

    $MainContent .= "<div class='row'>"; // Start a new row

    // Left column - display the product's description,
    $MainContent .= "<div class='col-sm-9' style='padding:5px'>";
    $MainContent .= "<p>$row[ProductDesc]</p>";

    // Left column - display the product's Specfication,
    $qry = "SELECT s.SpecName, ps.SpecVal from productspec ps
            INNER JOIN specification s ON ps.SpecID=s.SpecID
            WHERE ps.ProductID=$pid
            ORDER BY ps.priority";
    $result2 = $conn->query($qry);
    while ($row2 = $conn->fetch_array($result2)) {
        $MainContent .= $row2["SpecName"] . ": " . $row2["SpecVal"] . "<br />";
    }
    $MainContent .= "</div>"; // End of left column

    // Right column - display the product's image
    $img = "./Images/products/$row[ProductImage]";
    $MainContent .= "<div class='col-sm-3' style='vertical-align:top; padding:5px'>";
    $MainContent .= "<p><img src=$img /></p>";

    // Right column - display the product's price
    $formattedPrice = number_format($row["Price"], 2);
    $MainContent .= "Price:<span style='font-weight:bold; color:red;'>S$ $formattedPrice</span>";
}
// To Do 1:  Ending ....

// To Do 2:  Create a Form for adding the product to shopping cart. Starting ....
$MainContent .= "<form action='cart-functions.php' method='post'>";
$MainContent .= "<input type='hidden' name='actionA' value='add' />";
$MainContent .= "<input type='hidden' name='product_id' value='$pid' />";
$MainContent .= "Quantity: <input type='number' name='quantity'
                style='width:40px'
                value='1' min='1' max='10' required />";
$MainContent .= "<button type='submit'>Add to Cart</button>";
$MainContent .= "</form>";
$MainContent .= "</div>"; // End of right column
$MainContent .= "</div>"; // End of row
// To Do 2:  Ending ....

$conn->close(); // Close database connnection
$MainContent .= "</div>"; // End of container
include("MasterTemplate.php");
