<?php
// Example Form Config

// $form_config = array(
//     'method' => "POST",
//     'submit_button_text' => "Login",
//     'fields' => array(
//         array(
//             'placeholder' => "Email",
//             'type' => "email",
//             'name' => "email",
//             'required' => True
//         ),
//         array(
//             'placeholder' => "Password",
//             'type' => "password",
//             'name' => "password",
//             'required' => True
//         )
//     )
// );
?>

<form method="<?= $form_config['method'] ?>">
    <?php foreach ($form_config['fields'] as $field): ?>

        <?php if ($field['type'] == "select" && $field['name'] == "customer_id"): ?>
        <?php
        // Fetch customers from the database
        $stmt = $pdo->prepare("SELECT id, fullname FROM customers");
        $stmt->execute();
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <select class="input-field" name="<?= $field['name'] ?>" required>
            <option value="" disabled selected><?= $field['placeholder'] ?></option>
            <?php foreach ($customers as $customer): ?>
                <option value="<?= htmlspecialchars($customer['id']) ?>">
                    <?= htmlspecialchars($customer['fullname']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php endif; ?>

        <?php if ($field['type'] == "select" && $field['name'] == "product_id"): ?>
        <?php
        // Fetch products from the database
        $stmt = $pdo->prepare("SELECT id, product_name FROM products");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <select class="input-field" name="<?= $field['name'] ?>" required>
            <option value="" disabled selected><?= $field['placeholder'] ?></option>
            <?php foreach ($products as $product): ?>
                <option value="<?= htmlspecialchars($product['id']) ?>">
                    <?= htmlspecialchars($product['product_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php endif; ?>

        <?php if ($field['type'] != "select"): ?>
        <input 
            class="input-field" 
            placeholder="<?= $field['placeholder'] ?>" 
            type="<?= $field['type'] ?>" 
            name="<?= $field['name'] ?>" 
            step="<?= $field['step'] ?>" 
            required="<?= $field['required'] ?>">
        <?php endif; ?>

    <?php endforeach; ?>

    <button 
        name="<?= $form_config['submit_button_name'] ?>" 
        type="submit">
        <?= $form_config['submit_button_text'] ?>
    </button>
</form>