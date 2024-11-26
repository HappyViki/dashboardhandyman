<script>/* Firefox Fix */</script>
<?php if (isset($_SESSION['user_id'])): ?>

<header>
    <nav>
        <ul>
            <li><img src="img/dashboard_handyman_logo_50px.png" alt="Dashboard Handyman Logo"></li>
            <li><a class="home" href="dashboard.php">Home</a></li>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="customers.php">Customers</a></li>
            <li><a href="products.php">Products</a></li>
            <li>Hello <?php echo $_SESSION['username']; ?>!</li>
        </ul>
    </nav>
</header>

<?php endif; ?>