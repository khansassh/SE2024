<?php
$conn = new mysqli("localhost", "root", "", "salesorderdb");
$productQuery = "SELECT * FROM products";
$productResult = $conn->query($productQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #fbc2eb, #a6c1ee);
            padding: 20px;
        }
        table {
            width: 80%;
            margin: auto;
            background-color: #fff;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #5b86e5;
            color: white;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center; color: #a64ebf;">Product List</h2>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Price</th>
        </tr>
        <?php while ($product = $productResult->fetch_assoc()): ?>
            <tr>
                <td><?= $product['name'] ?></td>
                <td>$<?= number_format($product['price'], 2) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
