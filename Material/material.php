<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "raw_material_work_order_db");

// Retrieve materials
$materials = $conn->query("SELECT * FROM material");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Material List</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Material List</h1>
        <table>
            <tr>
                <th>Material</th>
                <th>Days</th>
            </tr>
            <?php while ($row = $materials->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['material_name']) ?></td>
                    <td><?= htmlspecialchars($row['days']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
