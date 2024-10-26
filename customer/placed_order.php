<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "salesorderdb");

// Fetch orders
$orderQuery = "SELECT * FROM orders";
$orderResult = $conn->query($orderQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Placed Orders</title>
</head>
<body>
    <h2>Placed Orders</h2>
    <table border="1">
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
                <td><?= $order['total'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
