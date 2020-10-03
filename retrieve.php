<?php 
session_start();

$user=$_SESSION['email'];

//This is the page that the javascript code communicates with. This just returns the exchange rate and the balance.
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
//1CeXdh7dHJKi2dkMbDHgDMPJ5UisdhXPy2

//in a real world scenario, the bitcoin address will be attached to a user and here, the SQL SELECT code will contain the address value in the WHERE field. As this is a test, i have omitted the bitcoin address field from the database.
$sql = "SELECT * FROM user WHERE email='$user'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
  	$balance=$row['balance'];
  	$exchange_rate=$row['exchange_rate'];

    echo "$balance--$exchange_rate";
  }
} else {
  // echo "0 results";
}
$conn->close();

 ?>