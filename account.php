
<?php 
//starts a session
session_start();


//this piece of code here checks if the session variable is set which indicates if a person is loggedin or not
if(!isset($_SESSION['email'])){
	header('Location: index.php');
}


//assigns the session variable to the user variable

$user=$_SESSION['email'];

//This piece of code splits the email and uses the first part before the '@' symbol as the guest's name
$name=explode("@", $_SESSION['email']);
$name=$name[0];



//creates the database connection
$servername = "topoffers.website";
$username = "localhost";
$password = "localhost";
$dbname = "user_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
//http://profinance.epizy.com/postback.php
//1CeXdh7dHJKi2dkMbDHgDMPJ5UisdhXPy2


//This piece of code selects data from the sql database and the ones that would be needed is the balance and the exchange rate.
$sql = "SELECT * FROM user WHERE email='$user'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
  	$balance=$row['balance'];
  	$exchange_rate=$row['exchange_rate'];


  }
} else {
  // echo "0 results";
}

//closes the connection
$conn->close();


//checks if the user has paid for the image, if the payment has been made, it will assign a true value to the variable $purchased
	if(floatval($balance) <= 0){
		$purchased = 'false';
	}
	else if(floatval($balance) > 0 && floatval($balance) >= floatval($exchange_rate)){
		$purchased='true';
	}

 ?>

<!DOCTYPE html>
<html>
<head>
	<title>Blockonomics Assignment</title>
	<meta charset="utf-8" name="viewport" content="width=device-width,initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<nav class="top_nav"><img src="logo.png" class="logo"> <div class="title">Bitcoin Art PayWall</div><a href="logout.php"><div class="logout">Logout</div></a></nav>
	<section class="main_text">
		<div class="main_text_child">

<?php 
if($purchased == 'true'){
	?>

			Hey <?php echo $name; ?>, You can go ahead to download the high-quality image.

	<?php
}
else {
 
 ?>
			Welcome <?php echo $name; ?>, Please select an image you would like to purchase and get the high-quality version of the image
<?php 

		}

 ?>
		</div>
	</section>
	<section class="images">
		<div class="card">
			<div class="card_image_par">
				<img src="image.php?image=1001&type=preview" class="card_image">
			</div>
			<div class="card_text">
				<span class="text_left">Writer </span><span class="text_right"><?php if($purchased == 'true'){echo '$0.00'; } else { ?>$0.20 <?php } ?></span><br>
				The image of a writer
			</div>
			<div class="button_par">
			<a href="purchase.php?image=1001"><button class="card_button">
				<?php 
					//This section switches between the download image text and the purchase text. if the image has been paid for, the download image button is revealed.

				 ?>
				<?php if($purchased == 'true'){ ?> <a href="image.php?image=1001" style="color: white; ">Download Image</a>
			<?php } else { ?>
				Purchase
			<?php } ?>
			</button></a>
			</div>
		</div>
	</section>

	<footer>
		&copy; 2020. Blockonomics test
	</footer>
</body>
</html>