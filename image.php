<?php 
session_start();

$user=$_SESSION['email'];

$image_id =  $_GET['image'];
$type= $_GET['type'];


//This is where the magic happens. if the image have not been paid for, the image shown will be the lower quality but when purchased for, it will display the full image.

if($type == 'preview'){

// loads the low-quality image
$im = imagecreatefromjpeg("low-quality/$image_id.jpg");
  
}
else{

	//Connects to the database and retrieves all the information linked to the user
$servername = "example.com";
$username = "example";
$password = "password";
$dbname = "user_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM user WHERE email='$user'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {

  //gets the balance and the exchange rate from the database
  	
  	$balance=$row['balance'];
  	$exchange_rate=$row['exchange_rate'];

  }
} else {
  // echo "0 results";
}

$conn->close();

//if the balance is zero, do nothing and show the low-quality image
if(floatval($balance) <= 0 ){

	$im = imagecreatefromjpeg("low-quality/$image_id.jpg");

}
//if the balance is greater or equal to the exchange rate, show the high-quality image
else if(floatval($balance) >= floatval($exchange_rate)){
	
	$im = imagecreatefromjpeg("high-quality/$image_id.jpg");

}

//else, just show the low-quality image
else{

	$im = imagecreatefromjpeg("low-quality/$image_id.jpg");

}

}
// View the loaded image in browser using imagejpeg() function 
header('Content-type: image/jpg');   
imagejpeg($im); 
imagedestroy($im); 



?>
