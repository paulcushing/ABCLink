<?php
header("Access-Control-Allow-Origin: *");
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

if (empty($_POST['destinations'])) die();

$destinations = $_POST['destinations']; // Should be an array of destinations

// Settings
$baseURL = "https://abcl.ink";
error_log("Running Create");

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

// Generate new link url
function generateRandomString() {
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    return substr(str_shuffle($permitted_chars), 0, 6);
}

$shortString = generateRandomString();
$newShortLink = $baseURL.'?'.$shortString;

// Add link, Update link
// Create link in 'links' table and return record number
$shortlink_sql = "INSERT INTO links (shortlink) VALUES ('$shortString')";

if ($conn->query($shortlink_sql) === TRUE) {
  error_log("New shortlink created successfully");
  $new_id = $conn->insert_id;
  foreach ($destinations as $destination) {
    $dest_sql = "INSERT INTO destinations (shortlink_id, destination) VALUE ($new_id, '$destination')";

    if ($conn->query($dest_sql) === TRUE) {
      error_log("New destination created successfully");
    } else {
      $error = $dest_sql . "<br>" . $conn->error;
    }
  }
} else {
  $error = $shortlink_sql . "<br>" . $conn->error;
}

// Return link upon success, 0 on fail
header('Content-type: application/json');
if(isset($error)) {
  $returnMessage['Error'] = $error;
} else {
  $returnMessage['Success'] = $newShortLink;
}

echo json_encode($returnMessage);