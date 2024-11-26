<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require 'db.php';

$form_config = array(
    'method' => "POST",
    'submit_button_text' => "Add Product",
    'submit_button_name' => "create",
    'fields' => array(
        array(
            'placeholder' => "Product Name",
            'type' => "text",
            'name' => "product_name",
            'required' => True
        ),
        array(
            'placeholder' => "Price",
            'type' => "number",
            'name' => "price",
            'step' => "0.01",
            'required' => True
        ),
        array(
            'placeholder' => "Quantity",
            'type' => "number",
            'name' => "quantity",
            'required' => True
        )
    )
);

// CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CREATE
    if (isset($_POST['create'])) {
        $product_name = htmlspecialchars(trim($_POST['product_name']));
        $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
        $quantity = filter_var($_POST['quantity'], FILTER_VALIDATE_INT);

        if ($product_name && $price !== false && $quantity !== false) {
            // Prepare the SQL statement
            $stmt = $pdo->prepare("INSERT INTO products (product_name, price, quantity) VALUES (:product_name, :price, :quantity)");
            // Execute the statement with parameterized values
            $stmt->execute([':product_name' => $product_name, ':price' => $price, ':quantity' => $quantity]);
        }
    }

    // UPDATE
    if (isset($_POST['update'])) {
        $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
        $product_name = htmlspecialchars(trim($_POST['product_name']));
        $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
        $quantity = filter_var($_POST['quantity'], FILTER_VALIDATE_INT);

        $stmt = $pdo->prepare("UPDATE products SET product_name=:product_name, price=:price, quantity=:quantity WHERE id=:id");
        $stmt->execute([':id' => $id, ':product_name' => $product_name, ':price' => $price, ':quantity' => $quantity]);
    }

    // DELETE
    if (isset($_POST['delete'])) {
        $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);

        $stmt = $pdo->prepare("DELETE FROM products WHERE id=:id");
        $stmt->execute([':id' => $id]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require('components/global_head.php') ?>
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/table.css">
</head>
<body>
    <?php require('components/header.php') ?>
    <main>
        <h1 class="heading">Product Management</h1>

        <!-- CREATE FORM -->
        <div class="pretty-form">
            <form action="upload_products.php" method="POST" enctype="multipart/form-data">
                <a href="download_products.php">Download Products CSV</a>
                <input type="file" name="csv_file" accept=".csv" required>
                <button type="submit">Upload</button>
            </form>
            <?php require('components/form.php') ?>
        </div>

        <!-- READ -->
        <h2 class="heading">Products List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->prepare("SELECT * FROM products");
                $stmt->execute();
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($products as $product):
                ?>
                    <tr>
                        <!-- UPDATE FORM -->
                        <form method="POST" style="display: inline-block;">
                        <td><?= $product['id'] ?><input type="number" name="id" value="<?= $product['id'] ?>" hidden></td>
                        <td><input type="text" name="product_name" value="<?= $product['product_name'] ?>" required></td>
                        <td><input type="number" name="price" step="0.01" value="<?= $product['price'] ?>" required></td>
                        <td><input type="number" name="quantity" value="<?= $product['quantity'] ?>" required></td>
                        <td><button class="update" type="submit" name="update">Update</button></td>
                        </form>

                        <td>
                            <!-- DELETE FORM -->
                            <form method="POST" style="display: inline-block;">
                                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                <button class="delete" type="submit" name="delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <?php require('components/footer.php') ?>
</body>
</html>
