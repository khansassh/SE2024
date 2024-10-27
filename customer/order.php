<?php
$conn = new mysqli("localhost", "root", "", "salesorderdb");

// Fetch products
$productQuery = "SELECT * FROM products";
$productResult = $conn->query($productQuery);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_number = $_POST['order_number'];
    $customer_ref = $_POST['customer_ref'];
    $order_date = $_POST['order_date'];
    $total = 0;

    $conn->query("INSERT INTO orders (order_number, customer_ref, order_date, total) VALUES ('$order_number', '$customer_ref', '$order_date', 0)");
    $order_id = $conn->insert_id;

    foreach ($_POST['products'] as $product) {
        $product_id = $product['product_id'];
        $quantity = $product['quantity'];
        $discount = $product['discount'];
        
        $productData = $conn->query("SELECT price FROM products WHERE id='$product_id'")->fetch_assoc();
        $price = $productData['price'];
        $subtotal = $quantity * $price * ((100 - $discount) / 100);
        $total += $subtotal;

        $conn->query("INSERT INTO order_details (order_id, product_id, quantity, discount, subtotal) VALUES ('$order_id', '$product_id', '$quantity', '$discount', '$subtotal')");
    }

    $conn->query("UPDATE orders SET total='$total' WHERE id='$order_id'");
    echo "<script>alert('Order successfully'); window.location.href = 'home.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Order</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ffd1a9, #ff9190);
            padding: 20px;
        }
        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        h2 {
            text-align: center;
            color: #ff4081;
            font-size: 24px;
        }
        label {
            display: block;
            margin: 12px 0 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="date"], select, input[type="number"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        input[type="submit"], button {
            background-color: #ff6f61;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            margin-top: 15px;
            font-size: 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        }
        input[type="submit"]:hover, button:hover {
            background-color: #ff4081;
        }
        .product-row {
            background-color: #ffe0cc;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Create Order</h2>
        <label>Order Number: <input type="text" name="order_number" required></label>
        <label>Customer Ref: <input type="text" name="customer_ref" required></label>
        <label>Order Date: <input type="date" name="order_date" required></label>
        
        <div id="product-section">
            <h3>Add Products</h3>
            <div class="product-row">
                <label>Product:
                    <select name="products[0][product_id]" required>
                        <?php while ($row = $productResult->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?> - <?= $row['price'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </label>
                <label>Quantity: <input type="number" name="products[0][quantity]" required min="1"></label>
                <label>Discount(%): <input type="number" name="products[0][discount]" value="0" min="0" max="100"></label>
            </div>
        </div>
        
        <button type="button" onclick="addProduct()">Add Another Product</button><br><br>
        <input type="submit" value="Submit Order">
    </form>

    <script>
        let productCount = 1;
        function addProduct() {
            const section = document.getElementById('product-section');
            const div = document.createElement('div');
            div.className = 'product-row';
            div.innerHTML = `
                <label>Product:
                    <select name="products[${productCount}][product_id]" required>
                        <?php 
                        $productResult->data_seek(0); 
                        while ($row = $productResult->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?> - <?= $row['price'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </label>
                <label>Quantity: <input type="number" name="products[${productCount}][quantity]" required min="1"></label>
                <label>Discount(%): <input type="number" name="products[${productCount}][discount]" value="0" min="0" max="100"></label>
            `;
            section.appendChild(div);
            productCount++;
        }
    </script>
</body>
</html>
