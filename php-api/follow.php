<?php
header("Access-Control-Allow-Origin: *");
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

if (empty($_POST['shortLink'])) die();

$shortlink = $_POST['shortLink']; // Should be an array of destinations

// Settings
error_log("Looking for links");

// Initiate Composer 
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '.env.local');
$dotenv->load();

$servername = $_ENV['DB_URL'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Add link, Update link
// Create link in 'links' table and return record number
$find_shortlink_sql = "SELECT id FROM links WHERE shortlink='$shortlink'";

$shortlink_result = $conn->query($find_shortlink_sql);
if ($shortlink_result->num_rows > 0) {
  while($shortlink_row = $shortlink_result->fetch_assoc()) {
    $shortlink_id = $shortlink_row["id"];
  }

  $destinations_sql = "SELECT destination FROM destinations WHERE shortlink_id=$shortlink_id";

  $destinations_result = $conn->query($destinations_sql);

  $destinations = [];
  if ($destinations_result->num_rows > 0) {
    while($destination_row = $destinations_result->fetch_assoc()) {
      array_push($destinations, $destination_row["destination"]);
    }
  }

  $newDestination = $destinations[array_rand($destinations, 1)]; // choose one at random


} else {
  $error = "Short link not found";
}

// Return link upon success, error message on fail
header('Content-type: application/json');
if(isset($error)) {
  $returnMessage['Error'] = $error;
} else {
  $returnMessage['Success'] = $newDestination;
}

echo json_encode($returnMessage);