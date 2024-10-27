<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "raw_material_work_order_db");

// Retrieve work order details
$work_orders = $conn->query("SELECT * FROM work_order");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finish Order</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Finish Order</h1>
        <?php while ($work_order = $work_orders->fetch_assoc()): ?>
            <h2>Work Order No: <?= htmlspecialchars($work_order['work_order_no']) ?></h2>
            <p>Client: <?= htmlspecialchars($work_order['client']) ?></p>
            <p>Work Date: <?= htmlspecialchars($work_order['work_date']) ?></p>
            <p>Finish Date: <?= htmlspecialchars($work_order['finish_date']) ?></p>

            <?php
            // Get materials for this work order
            $materials = $conn->query("SELECT m.material_name, wom.days, wom.percentage FROM work_order_material wom JOIN material m ON wom.material_id = m.id WHERE wom.work_order_id = " . $work_order['id']);
            $total_percentage = 0;
            ?>
            <table>
                <tr>
                    <th>Material</th>
                    <th>Days</th>
                    <th>Percentage</th>
                </tr>
                <?php while ($material = $materials->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($material['material_name']) ?></td>
                        <td><?= htmlspecialchars($material['days']) ?></td>
                        <td><?= htmlspecialchars($material['percentage']) ?></td>
                    </tr>
                    <?php $total_percentage += $material['percentage']; ?>
                <?php endwhile; ?>
            </table>
            <p>Total Percentage: <?= htmlspecialchars($total_percentage) ?>%</p>
            <?php if ($total_percentage !== 100): ?>
                <p class="error">Error: Total percentage must be 100%.</p>
            <?php endif; ?>
        <?php endwhile; ?>
    </div>
</body>
</html>
