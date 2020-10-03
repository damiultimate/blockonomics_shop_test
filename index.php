<?php 

//Starting the session. the session is necessary in case of a real world scenario. But because this is a test, i have included the login page just as a simple necessity

session_start();

if(isset($_POST['submit'])){
//In theory, the login form is not necessary because it is a simple test.
	$_SESSION['email']="testuser@gmail.com";

	header('Location: account.php');
}

//I assigned the session variable to the user variable to be used in some other place and this also initiates the login variable that would be used in other pages.
$user=$_SESSION['email'];

//In this section, i have written the PHP code to connect to the database and Update the user's data for the balance and exchange rate to equal zero to indicate a fresh login. Within this web application, the balance indicates the money the user will send during payment and the exchange rate represents the amount the exact amount needed for the image.

$servername = "topoffers.website";
$username = "localhost";
$password = "localhost";
$dbname = "user_db";

// Create SQL connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

//1CeXdh7dHJKi2dkMbDHgDMPJ5UisdhXPy2

//The sql string to update the values to become zero.

$sql = "UPDATE user SET balance='0', exchange_rate='0' WHERE email='$user'";

if ($conn->query($sql) === TRUE) {
  // echo "Record updated successfully";

} else {

	die('an error has occured, please refresh the page');
	//This code stops the pageload and displays the error message to the user in case of an error updating the database in the update code above.

}

//closes the open SQL connection
$conn->close();

 ?>

<!DOCTYPE html>
<html>
<head>
	<title>Blockonomics Assignment</title>
	<meta charset="utf-8" name="viewport" content="width=device-width,initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<style type="text/css">
		form{
			width: 500px;
			text-align: center;
			border:1px solid gray;
			padding: 25px;
			border-radius: 10px;
		}
		@media screen and (orientation: portrait){
			form{
				width: 90%;
			}
		}

		form p{
			font-size: 20px;
		}
		form small{
			font-size: 13px;
		}
		 form{
		 	line-height: 30px;
		 }

		 form input[type='email']{
		 	width: 95%;
		 	height: 30px;
		 	border:1px solid gray;
		 	border-radius: 5px;
		 	padding: 0 10px;
		 }
		 form input[type='submit']{
		 	border:1px solid #40a840;
		 	border-radius: 5px;
		 	padding: 9px;
		 	font-size: 19px;
		 	background: #40a840;
		 	color: white;
		 	cursor: pointer;
		 }

		 .login_form{
		 	display: flex;
		 	justify-content: center;
		 	align-items: center;
		 	margin-top: 5%;
		 }
	</style>

	<section class="login_form">
		<div class="form">
			<form method="post" action="index.php">
				<img src="logo.png">
			<p>Enter your email to login</p>
			<small>Please login with the testuser email address as this is just a test demonstration.</small>
			<input type="email" name="email" class="login" value="testuser@gmail.com">
			<br>
			<small>Because this is a test, i have omitted many normal signup fields and left only the email field for logging in</small>
			<br>
			<input type="submit" name="submit" value="Login">
			</form>
		</div>
	</section>

</body>
</html>