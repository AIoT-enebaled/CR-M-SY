<?php

// Database credentials
$servername = "localhost"; // server
$username = "root"; // replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "crime_reporting"; // Replace with your database name

// Create connection

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}