<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "salesorderdb");

// Fetch products
$productQuery = "SELECT * FROM products";
$productResult = $conn->query($productQuery);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $order_number = $_POST['order_number'];
    $customer_ref = $_POST['customer_ref'];
    $order_date = $_POST['order_date'];
    $total = 0;
    
    // Insert order data
    $conn->query("INSERT INTO orders (order_number, customer_ref, order_date, total) VALUES ('$order_number', '$customer_ref', '$order_date', 0)");
    $order_id = $conn->insert_id;
    
    // Process each product entry
    foreach ($_POST['products'] as $product) {
        $product_id = $product['product_id'];
        $quantity = $product['quantity'];
        $discount = $product['discount'];
        
        // Fetch product price
        $productData = $conn->query("SELECT price FROM products WHERE id='$product_id'")->fetch_assoc();
        $price = $productData['price'];
        
        // Calculate subtotal with discount
        $subtotal = $quantity * $price * ((100 - $discount) / 100);
        $total += $subtotal;
        
        // Insert order details
        $conn->query("INSERT INTO order_details (order_id, product_id, quantity, discount, subtotal) VALUES ('$order_id', '$product_id', '$quantity', '$discount', '$subtotal')");
    }
    
    // Update total in orders
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
            background-color: #f4f4f4;
            padding: 20px;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="date"], select, input[type="number"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"], button {
            background-color: #5cb85c;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover, button:hover {
            background-color: #4cae4c;
        }
        .product-row {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
        .product-row label {
            display: inline-block;
            margin: 5px 10px 5px 0;
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
                        $productResult->data_seek(0); // Reset product result for reuse
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
