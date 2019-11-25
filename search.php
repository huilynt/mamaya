<?php
// Detect the current session
session_start();

// HTML Form to collect search keyword and submit it to the same page 
// in server
$MainContent = "<div style='width:80%; margin:auto;'>"; // Container
$MainContent .= "<form name='frmSearch' method='get' action=''>";
$MainContent .= "<div class='form-group row'>"; // 1st row
$MainContent .= "<div class='col-sm-9 offset-sm-3'>";
$MainContent .= "<span class='page-title'>Product Search</span>";
$MainContent .= "</div>";
$MainContent .= "</div>"; // End of 1st row
$MainContent .= "<div class='form-group row'>"; // 2nd row
$MainContent .= "<label for='keywords' 
                  class='col-sm-3 col-form-label'>Product Title:</label>";
$MainContent .= "<div class='col-sm-6'>";
$MainContent .= "<input class='form-control' name='keywords' id='keywords' 
                  type='search' />";
$MainContent .= "</div>";
$MainContent .= "<div class='col-sm-3'>";
$MainContent .= "<button type='submit'>Search</button>";
$MainContent .= "</div>";
$MainContent .= "</div>";  // End of 2nd row
$MainContent .= "</form>";

// The search keyword is sent to server
if (isset($_GET['keywords'])) {
    $SearchText = $_GET["keywords"];

    // To Do (DIY): Retrieve list of product records with "ProductTitle" 
    // contains the keyword entered by shopper, and display them in a table.
    include("mysql.php");  // Include the class file for database access
    $conn = new Mysql_Driver();  // Create an object for database access
    $conn->connect(); // Open database connnection

    $qry = "SELECT * from product where ProductTitle LIKE '%$SearchText%' OR ProductDesc LIKE '%$SearchText%'";
    $result = $conn->query($qry); // Execute the SQL statement

    $MainContent .= "<div style='font-weight:bold;'>Search results for $SearchText:</div>";

    if (mysqli_num_rows($result) > 0) {
        while ($row = $conn->fetch_array($result)) {
            $product = "productDetails.php?pid=$row[ProductID]";

            $MainContent .= "<div><a href=$product>$row[ProductTitle]</a> <br /> $row[ProductDesc]</div>";
        }
    } else {
        $MainContent .= "<div>No records found.</div>";
    }
    // Close database connection
    $conn->close();
    // To Do (DIY): End of Code
}

$MainContent .= "</div>"; // End of Container
include("MasterTemplate.php");
