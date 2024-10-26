<?php
include 'db.php';

$result = $conn->query("SELECT o.order_number, o.customer_ref, o.order_date, SUM(i.subtotal) AS total
                        FROM orders o
                        JOIN order_items i ON o.order_id = i.order_id
                        GROUP BY o.order_id");

while ($row = $result->fetch_assoc()) {
    echo "Order Number: " . $row['order_number'] . "<br>";
    echo "Customer Ref: " . $row['customer_ref'] . "<br>";
    echo "Order Date: " . $row['order_date'] . "<br>";
    echo "Total: " . $row['total'] . "<br><br>";
}
?>
