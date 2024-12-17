<?php
// Include the config.php file to get the database credentials
include('config.php');

// Create a connection to the MySQL database using the credentials from config.php
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the visitor's IP address and current time
$visitor_ip = $_SERVER['REMOTE_ADDR'];
$current_time = date("Y-m-d H:i:s");

// Insert the visitor data into the table
$sql = "INSERT INTO visitor_data (ip_address, visit_time) VALUES ('$visitor_ip', '$current_time')";
if ($conn->query($sql) === TRUE) {
    // Data inserted successfully
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Display the message with the visitor's IP and current time
echo "Hello World!<br>";
echo "Your IP address is: " . $visitor_ip . "<br>";
echo "Current time is: " . $current_time . "<br>";

// Close the connection
$conn->close();
?>

