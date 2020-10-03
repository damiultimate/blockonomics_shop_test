<?php 
//connects to the database
$servername = "topoffers.website";
$username = "localhost";
$password = "localhost";
$dbname = "user_db";

//the values sent from the Blockonomics servers which will be used in the database.
$txid = $_GET['txid'];
$value = $_GET['value'];
$status = $_GET['status'];
$addr = $_GET['addr'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
//1CeXdh7dHJKi2dkMbDHgDMPJ5UisdhXPy2

if($status != "2" ){
	die();
}
//Since the value is sent from Blockonomics as satoshis, i have converted it to bitcoins by dividing it by a hundred million
$value=floatval($value)/100000000;


//Because the Blockonomics API does not allow me to send bitcoins to a real address i generated for payment, i omitted the bitcoins address field in the database and used the reset=1 GET variable. Normally, i would initially use the SELECT query and compare the $addr variable sent from Blockonomics with the one in the database and check if they are the same. If they are the same, the bitcoin balance of the user will be updated and if they aren't equal, i will use the 'die()' function. So with this code, any address associated with my account can receive the bitcoins and update the database. This might seem like a bug but i purposefully let it be. I would advice Blockonomics that they should implement a way for the API used to generate an address take an id variable in the request so when the address is generated, the unique id used during creation is returned as this would assist the developer easily differentiate between users attached to bitcoin addresses. Also, in the API, it does not state how long an address lasts, if it expires or if it does not expire at all. The API needs adjusting and a little bit more documentation.


$sql = "UPDATE user SET balance='$value' WHERE email='testuser@gmail.com'";
// echo $sql;
if ($conn->query($sql) === TRUE) {
  // echo "Record updated successfully";

} else {
  // echo "Error updating record: " . $conn->error;

}

$conn->close();

 ?>