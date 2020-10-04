
<?php
session_start();
$user=$_SESSION['email'];

//This is the PHP CURL function that connects to the API of Blockonomics and either gets the Bitcoin address or gets the current USD exchange rate for the application to work peoperly.

function CURL($url, $request){
$api_key = 'QFJ9cH4XK8i6VtJSXx8DjlRQhqRU1KvM9Z9PcyZirac';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request);

$header = "Authorization: Bearer " . $api_key;
$headers = array();
$headers[] = $header;
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$contents = curl_exec($ch);
if (curl_errno($ch)) {
  die();
  echo "Error:" . curl_error($ch)." Please reload the page ";
}

$responseObj = json_decode($contents);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close ($ch);

if ($status == 200) {
    // $value = $responseObj->address;
    $value = $responseObj;
} else {
  die();
    echo "ERROR: " . $status . ' ' . $responseObj->message;
}

return $value;

}

//This one here gets the current bitcoin exchange rate and calculates the exact amount of bitcoins which equals $0.2
$price = CURL('https://www.blockonomics.co/api/price?currency=USD','GET');

$price = $price->price;

$price=0.2/$price;

$price=sprintf('%f', floatval(round($price,7)));

//Conneting to the database and sets the exchange rate to the bitcoins in the $price variable
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


$sql = "UPDATE user SET exchange_rate='$price' WHERE email='$user'";
// echo $sql;
if ($conn->query($sql) === TRUE) {
  // echo "Record updated successfully";

} else {
  // echo "Error updating record: " . $conn->error;

}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Blockonomics Assignment</title>
  <meta charset="utf-8" name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="style.css">
  <script type="text/javascript" src="jquery.js"></script>
  <!-- <script type="text/javascript" src="swal.js"></script> -->
</head>
<body>
  <nav class="top_nav"><img src="logo.png" class="logo"> <div class="title">Bitcoin Art PayWall</div><a href="logout.php"><div class="logout">Logout</div></a></nav>
  
  <section class="first_text">
    Payment for "Writer" image
  </section>

  <section class="payment_process">
    <div class="card1">
      <p class="order_details">Order Details: "Writer" image (price = $0.2)</p>
      <p class="value_equator"><?php echo $price; ?> BTC ⇌ $0.2</p>
      <p class="address_intro">Bitcoin address for payment</p>

      <?php 

      //Since this is a tets, i have generated a bitcoin address and used the reset parameter so only one address is returned.

        $address = CURL("https://www.blockonomics.co/api/new_address?reset=1","POST");   

        $address= $address->address;


       ?>

      <input type="text" name="bitcoin-address" id="bitcoin_address" class="input" value="<?php echo $address; ?>">
      <p class="info_needed">Please copy the address above and send <?php echo $price; ?> Bitcoins there to proceed with the payment and get the high-quality "Writer" image.</p>
      <p class="confirmation">Checking...............<br>Once the Bitcoins is received, you will automatically be redirected from this page to the high-quality "Writer" image</p>
    </div>
  </section>

  <footer>
    &copy; 2020. Blockonomics test
  </footer>

  <script type="text/javascript">

//This function uses jQuery Ajax to check the server if the bitcoins have been sent. It uses the logic of infinite Ajax which calls the function in a recursive way infinitely. 
function checkBalance(){
$.get("retrieve.php", function(data, status){
    // alert("Data: " + data + "\nStatus: " + status);
    if(status=='success'){

      var value=data;
      var value1=value.split("--");

      var balance=parseFloat(value1[0]);
      var exchange_rate=parseFloat(value1[1]);
      

//if the balance is still zero, nothing will change
      if(balance <= 0){
        $(".confirmation").html('Checking...............<br>Once the Bitcoins is received, you will automatically be redirected from this page to the high-quality "Writer" image');
        console.log('incomplete');
      }
//if the balance is more than zero but not up to the Bitcoins required, show this message

      else if(balance < exchange_rate){
        $(".confirmation").html('Warning...............<br>Please send the exact amount of Bitcoins needed to get the image. You have only paid '+balance+' BTC and you are needed to pay <?php echo $price; ?> BTC');
        $('.confirmation').css("background","#ccad63");
        console.log('partially complete');

      }

      //if the balance is greater or equal to the bitcoins needed, it will display a succesful message and you can download the high-quality image
      else if(balance >= exchange_rate){
        $(".confirmation").html('Success...............<br>Payment completed successfully. You will now be redirected to the page...');
        $('.confirmation').css("background","#58ae4c");
        console.log('totally complete');
window.location.href='account.php';
      }
//recursion
    checkBalance();


    }else{

//recursion

    checkBalance();

    }
  });
}
//initial function call
checkBalance();

  </script>
</body>
</html>
