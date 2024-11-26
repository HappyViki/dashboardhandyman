<?php
try {
    // SQL queries to insert data
    $sql = [
        // Users
        "INSERT INTO users (role, username, email, password) VALUES
        ('admin', 'admin_user', 'admin@example.com', 'hashedpassword1'),
        ('handyman', 'handyman1', 'handyman1@example.com', 'hashedpassword2'),
        ('handyman', 'handyman2', 'handyman2@example.com', 'hashedpassword3'),
        ('support', 'support_user1', 'support1@example.com', 'hashedpassword4'),
        ('support', 'support_user2', 'support2@example.com', 'hashedpassword5');",

        // Customers
        "INSERT INTO customers (fullname, address, email, phone) VALUES
        ('John Doe', '123 Street Rd, City', 'johndoe@example.com', 1234567890),
        ('Jane Smith', '604 S Gay St, Knoxville', 'janesmith@example.com', 1234567890),
        ('Mike Brown', '713 S 17th St #1, Knoxville', 'mikebrown@example.com', 1234567890),
        ('Lucy Gray', '32 Market Square, Knoxville', 'lucygray@example.com', 1234567890),
        ('Steve Black', '8001 Kingston Pike, Knoxville', 'steveblack@example.com', 1234567890);",

        // Products
        "INSERT INTO products (product_name, price, quantity) VALUES
        ('Washer/Dryer', 999.99, 10),
        ('Washer', 599.99, 25),
        ('Dryer', 299.99, 15);",

        // Orders
        "INSERT INTO orders (user_id, customer_id, product_id) VALUES
        (2, 1, 1),
        (3, 2, 2),
        (2, 3, 1),
        (4, 4, 1),
        (5, 5, 3);"
    ];
    // Execute table creation
    foreach ($sql as $query) {
        $stmt = $pdo->prepare($query);
        $stmt->execute();
    }
} catch (PDOException $e) {
    die( "Error: " . $e->getMessage());
}
?>
