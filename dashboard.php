<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require 'db.php';

$stmt = $pdo->prepare("SELECT 
o.id, 
u.username, c.fullname, c.phone, c.address
FROM 
orders o
INNER JOIN users u ON u.id = o.user_id
INNER JOIN customers c ON c.id = o.customer_id");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <?php require('components/global_head.php') ?>
    <link rel="stylesheet" href="css/table.css">
    <link rel="stylesheet" href="css/chart.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php require('components/header.php') ?>
    <main>
        <section id="orders">
            <h2>Orders</h2>
            <p>Notify customers of your arrival!</p>
            <table>
                <thead>
                    <tr>
                        <th>Notify</th>
                        <th>Customer</th>
                        <th>Address</th>
                        <th>Phone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><button class="update">Notify</button></td>
                            <td><?= $order['fullname'] ?></td>
                            <td><a href="https://www.google.com/maps/place/<?= $order['address'] ?>"/><?= $order['address'] ?></a></td>
                            <td><a href="tel:<?= $order['phone'] ?>"><?= $order['phone'] ?></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
        <section id="charts">
            <h2>Analytics</h2>
            <p>View the big picture of your company operations here.</p>
            <div class="charts">
                <div class="chart"><canvas id="ordersPieChart"></canvas></div>
                <div class="chart"><canvas id="ordersBarChart"></canvas></div>
                <div class="chart"><canvas id="ordersLineChart"></canvas></div>
            </div>
        </section>
    </main>
    <?php require('components/footer.php') ?>
    <script src="js/charts.js"></script>
</body>
</html>
