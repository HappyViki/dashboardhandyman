<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require 'db.php';

// Query to fetch products
$query = "SELECT * FROM products";
$stmt = $pdo->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Define the CSV filename
$filename = "products_" . date('Y-m-d') . ".csv";

// Set headers to download the file
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Open output stream
$output = fopen('php://output', 'w');

// Write the header row
if (!empty($products)) {
    fputcsv($output, array_keys($products[0]));
}

// Write product rows
foreach ($products as $product) {
    fputcsv($output, $product);
}

// Close output stream
fclose($output);  
exit();
?>