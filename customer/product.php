<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "salesorderdb");

// Fetch products
$productQuery = "SELECT * FROM products";
$productResult = $conn->query($productQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product List</title>
</head>
<body>
    <h2>Product List</h2>
    <table border="1">
        <tr>
            <th>Product Name</th>
            <th>Price</th>
        </tr>
        <?php while ($row = $productResult->fetch_assoc()): ?>
            <tr>
                <td><?= $row['name'] ?></td>
                <td><?= $row['price'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
