<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if a file was uploaded
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        // Validate the file type
        $fileMimeType = mime_content_type($_FILES['csv_file']['tmp_name']);
        $allowedTypes = ['text/plain', 'text/csv'];
        if (!in_array($fileMimeType, $allowedTypes)) {
            die("Invalid file type. Only CSV files are allowed.");
        }

        // Define upload directory and ensure it's writable
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Move uploaded file to a secure location
        $filePath = $uploadDir . basename($_FILES['csv_file']['name']);
        if (!move_uploaded_file($_FILES['csv_file']['tmp_name'], $filePath)) {
            die("Failed to move uploaded file.");
        }

        // Open the file securely
        if (($handle = fopen($filePath, 'r')) !== false) {
            // Skip the header row (if it exists)
            $header = fgetcsv($handle);

            // Ensure the CSV has the correct columns
            if (count($header) !== 4 || $header !== ['product_name', 'price', 'quantity', 'created_at']) {
                fclose($handle);
                unlink($filePath);
                die("Invalid CSV format. Ensure the header is: product_name, price, quantity, created_at");
            }

            // Prepare the SQL statement
            $stmt = $pdo->prepare("INSERT INTO products (product_name, price, quantity) VALUES (:product_name, :price, :quantity)");

            // Process each row securely
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                // Validate data
                $product_name = htmlspecialchars(trim($row[0]));
                $price = filter_var($row[1], FILTER_VALIDATE_FLOAT);
                $quantity = filter_var($row[2], FILTER_VALIDATE_INT);

                if ($product_name && $price !== false && $quantity !== false) {
                    // Execute the statement with parameterized values
                    $stmt->execute([':product_name' => $product_name, ':price' => $price, ':quantity' => $quantity]);
                } else {
                    // Skip invalid rows and log an error
                    error_log("Skipped invalid row: " . implode(',', $row));
                }
            }

            fclose($handle);

            // Remove the uploaded file after processing
            unlink($filePath);
        } else {
            echo "Error opening the file.";
        }
    } else {
        echo "Error uploading the file.";
    }
}
?>

<form action="" method="POST" enctype="multipart/form-data">
    <input type="file" name="csv_file" accept=".csv" required>
    <button type="submit">Upload and Insert</button>
</form>
