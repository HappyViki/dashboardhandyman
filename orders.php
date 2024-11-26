<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require 'db.php';

$form_config = array(
    'method' => "POST",
    'submit_button_text' => "Add Order",
    'submit_button_name' => "create",
    'fields' => array(
        array(
            'placeholder' => "User Name",
            'type' => "select",
            'name' => "user_id",
            'required' => True
        ),
        array(
            'placeholder' => "Customer Name",
            'type' => "select",
            'name' => "customer_id",
            'required' => True
        ),
        array(
            'placeholder' => "Product Name",
            'type' => "select",
            'name' => "product_id",
            'required' => True
        ),
    )
);

// CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CREATE
    if (isset($_POST['create'])) {
        $customer_id = filter_var($_POST['customer_id'], FILTER_VALIDATE_INT);
        $product_id = filter_var($_POST['product_id'], FILTER_VALIDATE_INT);

        if ($customer_id !== false && $product_id !== false) {
            // Prepare the SQL statement
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, customer_id, product_id) VALUES (:user_id, :customer_id, :product_id)");
            // Execute the statement with parameterized values
            $stmt->execute([
                ':user_id' => $_SESSION['user_id'], 
                ':customer_id' => $customer_id, 
                ':product_id' => $product_id, 
            ]);
        }
    }

    // UPDATE
    if (isset($_POST['update'])) {
        $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
        $customer_id = filter_var($_POST['customer_id'], FILTER_VALIDATE_INT);
        $product_id = filter_var($_POST['product_id'], FILTER_VALIDATE_INT);

        $stmt = $pdo->prepare("UPDATE orders SET user_id=:user_id, customer_id=:customer_id, product_id=:product_id WHERE id=:id");
        $stmt->execute([':id' => $id, ':user_id' => $_SESSION['user_id'], ':customer_id' => $customer_id, ':product_id' => $product_id]);
    }

    // DELETE
    if (isset($_POST['delete'])) {
        $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);

        $stmt = $pdo->prepare("DELETE FROM orders WHERE id=:id");
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
        <h1 class="heading">Order Management</h1>

        <!-- CREATE FORM -->
        <div class="pretty-form">
            <?php require('components/form.php') ?>
        </div>

        <!-- READ -->
        <h2 class="heading">Orders List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Phone</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <!-- <th>Update</th> -->
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->prepare("SELECT 
                o.id, 
                o.user_id, o.customer_id, o.product_id, 
                u.username, c.phone, c.fullname, p.product_name
                FROM 
                orders o
                INNER JOIN users u ON u.id = o.user_id
                INNER JOIN customers c ON c.id = o.customer_id
                INNER JOIN products p ON p.id = o.product_id");
                $stmt->execute();
                $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($orders as $order):
                ?>
                    <tr>
                        <!-- UPDATE FORM -->
                        <form method="POST" style="display: inline-block;">
                        <td><?= $order['id'] ?><input type="number" name="id" value="<?= $order['id'] ?>" hidden></td>
                        <td><?= $order['phone'] ?></td>
                        <td><?= $order['fullname'] ?></td>
                        <td><?= $order['product_name'] ?></td>
                        <!-- <td><button class="update" type="submit" name="update">Update</button></td> -->
                        </form>

                        <td>
                            <!-- DELETE FORM -->
                            <form method="POST" style="display: inline-block;">
                                <input type="hidden" name="id" value="<?= $order['id'] ?>">
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
