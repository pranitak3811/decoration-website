<?php
echo "<h2>Step 1 - PHP Working: YES ✅</h2>";

$conn = new mysqli('localhost', 'root', '', 'decoration_db');

if ($conn->connect_error) {
    echo "<h2 style='color:red'>Step 2 - Database: FAILED ❌</h2>";
    echo "<p>Error: " . $conn->connect_error . "</p>";
} else {
    echo "<h2 style='color:green'>Step 2 - Database: CONNECTED ✅</h2>";
    
    $result = $conn->query("SELECT COUNT(*) as total FROM bookings");
    $row = $result->fetch_assoc();
    echo "<h2 style='color:green'>Step 3 - Bookings in DB: " . $row['total'] . " ✅</h2>";
}
?>