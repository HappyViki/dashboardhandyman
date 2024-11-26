<?php
header("Content-Type: application/json");
require_once "../db.php";

// Get HTTP method and request data
$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents("php://input"), $input);

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Get a single product
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            // Get all products
            $stmt = $pdo->query("SELECT 
                            p.id AS product_id,
                            p.product_name,
                            p.quantity,
                            COUNT(o.product_id) AS order_count
                        FROM 
                            products p
                        INNER JOIN 
                            orders o
                        ON 
                            p.id = o.product_id
                        GROUP BY 
                            p.id, p.product_name");
            $order_count = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = $pdo->query("SELECT created_at, COUNT(created_at) AS created_at_count FROM orders GROUP BY created_at");
            $orders_created_at = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = $pdo->query("SELECT u.username, COUNT(o.id) AS order_count FROM users u INNER JOIN orders o ON u.id = o.user_id GROUP BY u.id");
            $user_order_count = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $result = array(
                'order_count' => $order_count,
                'orders_created_at' => $orders_created_at,
                'user_order_count' => $user_order_count
            );
        }
        echo json_encode($result);
        break;

    // case 'POST':
    //     // Add a new product
    //     $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock) VALUES (?, ?, ?, ?)");
    //     $stmt->execute([$input['name'], $input['description'], $input['price'], $input['stock']]);
    //     echo json_encode(["id" => $pdo->lastInsertId()]);
    //     break;

    // case 'PUT':
    //     // Update an existing product
    //     $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ? WHERE id = ?");
    //     $stmt->execute([$input['name'], $input['description'], $input['price'], $input['stock'], $input['id']]);
    //     echo json_encode(["updated" => $stmt->rowCount()]);
    //     break;

    // case 'DELETE':
    //     // Delete a product
    //     $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    //     $stmt->execute([$_GET['id']]);
    //     echo json_encode(["deleted" => $stmt->rowCount()]);
    //     break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(["error" => "Method not allowed"]);
}
?>
