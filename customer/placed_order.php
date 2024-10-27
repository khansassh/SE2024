<?php
$conn = new mysqli("localhost", "root", "", "salesorderdb");
$orderQuery = "SELECT * FROM orders";
$orderResult = $conn->query($orderQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Placed Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ffb6c1, #ffa07a);
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
            background-color: #ff6f61;
            color: white;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center; color: #ff4081;">Placed Orders</h2>
    <table>
        <tr>
            <th>Order Number</th>
            <th>Customer Ref</th>
            <th>Order Date</th>
            <th>Total</th>
        </tr>
        <?php while ($order = $orderResult->fetch_assoc()): ?>
            <tr>
                <td><?= $order['order_number'] ?></td>
                <td><?= $order['customer_ref'] ?></td>
                <td><?= $order['order_date'] ?></td>
                <td>$<?= number_format($order['total'], 2) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
