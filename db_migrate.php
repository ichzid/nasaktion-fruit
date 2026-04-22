<?php
$conn = new mysqli("localhost", "root", "", "db_nasaktion");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "ALTER TABLE transactions MODIFY COLUMN status ENUM('pending', 'paid', 'verified', 'shipped', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'";
if ($conn->query($sql) === TRUE) {
    echo "Table transactions altered successfully";
} else {
    echo "Error altering table: " . $conn->error;
}
$conn->close();
?>
