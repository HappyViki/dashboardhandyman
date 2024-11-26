<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require 'db.php';

$form_config = array(
    'method' => "POST",
    'submit_button_text' => "Add Customer",
    'submit_button_name' => "create",
    'fields' => array(
        array(
            'placeholder' => "Full Name",
            'type' => "text",
            'name' => "fullname",
            'required' => True
        ),
        array(
            'placeholder' => "Address",
            'type' => "text",
            'name' => "address",
            'required' => True
        ),
        array(
            'placeholder' => "Email",
            'type' => "email",
            'name' => "email",
            'required' => True
        ),
        array(
            'placeholder' => "Phone",
            'type' => "number",
            'name' => "phone",
            'required' => True
        )
    )
);

// CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CREATE
    if (isset($_POST['create'])) {
        $fullname = htmlspecialchars(trim($_POST['fullname']));
        $address = htmlspecialchars(trim($_POST['address']));
        $email = htmlspecialchars(trim($_POST['email']));
        $phone = filter_var($_POST['phone'], FILTER_VALIDATE_INT);

        if ($fullname !== false && $address !== false) {
            // Prepare the SQL statement
            $stmt = $pdo->prepare("INSERT INTO customers (fullname, address, email, phone) VALUES (:fullname, :address, :email, :phone)");
            // Execute the statement with parameterized values
            $stmt->execute([
                ':fullname' => $fullname, 
                ':address' => $address, 
                ':email' => $email, 
                ':phone' => $phone
            ]);
        }
    }

    // UPDATE
    if (isset($_POST['update'])) {
        $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
        $fullname = htmlspecialchars($_POST['fullname']);
        $address = htmlspecialchars($_POST['address']);
        $email = htmlspecialchars($_POST['email']);
        $phone = filter_var($_POST['phone'], FILTER_VALIDATE_INT);

        $stmt = $pdo->prepare("UPDATE customers SET fullname=:fullname, address=:address, email=:email, phone=:phone WHERE id=:id");
        $stmt->execute([':id' => $id, ':fullname' => $fullname, ':address' => $address, ':email' => $email, ':phone' => $phone]);
    }

    // DELETE
    if (isset($_POST['delete'])) {
        $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);

        $stmt = $pdo->prepare("DELETE FROM customers WHERE id=:id");
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
        <h1 class="heading">Customer Management</h1>

        <!-- CREATE FORM -->
        <div class="pretty-form">
            <?php require('components/form.php') ?>
        </div>

        <!-- READ -->
        <h2 class="heading">Customer List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query to fetch customers
                $query = "SELECT * FROM customers";
                $stmt = $pdo->prepare($query);
                $stmt->execute();
                $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($customers as $customer):
                ?>
                    <tr>
                        <!-- UPDATE FORM -->
                        <form method="POST" style="display: inline-block;">
                        <td><?= $customer['id'] ?><input type="number" name="id" value="<?= $customer['id'] ?>" hidden></td>
                        <td><input type="text" name="fullname" value="<?= $customer['fullname'] ?>" required></td>
                        <td><input type="text" name="address" value="<?= $customer['address'] ?>" required></td>
                        <td><input type="email" name="email" value="<?= $customer['email'] ?>" required></td>
                        <td><input type="number" name="phone" value="<?= $customer['phone'] ?>" required></td>
                        <td><button class="update" type="submit" name="update">Update</button></td>
                        </form>

                        <td>
                            <!-- DELETE FORM -->
                            <form method="POST" style="display: inline-block;">
                                <input type="hidden" name="id" value="<?= $customer['id'] ?>">
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
