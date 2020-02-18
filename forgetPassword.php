<?php 
$MainContent = "<div style='width:80%; margin:auto;'>";
$MainContent .= "<form method='post'>";
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<div class='col-sm-9 offset-sm-3'>";
$MainContent .= "<span class='page-title'>Forget Password</span>";
$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<label class='col-sm-3 col-form-label' for='eMail'>
                 Email Address:</label>";
$MainContent .= "<div class='col-sm-9'>";
$MainContent .= "<input class='form-control' name='eMail' id='eMail'
                        type='email' required />";
$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "<div class='form-group row'>";       
$MainContent .= "<div class='col-sm-9 offset-sm-3'>";
$MainContent .= "<button type='submit'>Submit</button>";
$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "</form>";

include("mysql.php");  
$conn = new Mysql_Driver();

// Process after user click the submit button
if (isset($_POST['eMail'])) {
	// Read email address entered by user
	$eMail = $_POST['eMail'];
	// Retrieve shopper record based on e-mail address
	$conn->connect();
	$qry = "SELECT * FROM shopper WHERE Email='$eMail'"; 
	$result = $conn->query($qry);
	$conn->close();
	if ($conn->num_rows($result) > 0) {
		// To Do 1: Update the default new password to shopper's account
		$row = $conn->fetch_array($result);
		$shopperId = $row["ShopperID"];
		$new_pwd = "password"; // Default password
		// Hash the default password
		$hashed_pwd = password_hash($new_pwd, PASSWORD_DEFAULT);
		$conn->connect();
		$qry = "UPDATE shopper SET Password='$hashed_pwd' WHERE ShopperID=$shopperId";
		$conn->query($qry);
		$conn->close();
		// End of To Do 1
		
		// To Do 2: e-Mail the new password to user
		include("myMail.php");
		// The "Send To" should be the e-mail address indicated
		// by shopper, i.e $eMail. In this case, use a testing e-mail
		// address as the shopper's e-mail address in our database
		// may not be a valid account.
		$to="dlgpuhaha@gmail.com";
		$from="dlgpuhaha@gmail.com";
		$from_name="Mamaya e-BookStore";
		$subject="Mamaya e-BookStore Login Password"; // e-mail title
		//HTML body message
		$body="<span style='color:black; font-size:12px'>Your new password is <span style='font-weight:bold'>$new_pwd</span>.<br />Do change this default password.</span>";
		//Initiate the e-mailing sending process
		if(smtpmailer($to, $from, $from_name, $subject, $body)){
			$MainContent.="<p>Your new password is sent to: <span style='font-weight:bold'>$to</span>.</p>";
		}else{
			$MainContent.="<p><span style='color:red'>Mailer Error: ".$error."</span></p>";
		}
		// End of To Do 2
	}
	else {
		$MainContent .= "<p><span style='color:red;'>Wrong E-mail address!</span>";
	}
}

$MainContent .= "</div>";
include("MasterTemplate.php");
