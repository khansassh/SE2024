<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "raw_material_work_order_db");

// Handling form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $work_order_no = $_POST['work_order_no'];
    $client = $_POST['client'];
    $work_date = $_POST['work_date'];
    $materials = $_POST['material'];
    $total_days = 0;

    // Insert the main work order details
    $conn->query("INSERT INTO work_order (work_order_no, client, work_date) VALUES ('$work_order_no', '$client', '$work_date')");
    $work_order_id = $conn->insert_id;

    // Insert each material and calculate total days
    foreach ($materials as $material_id) {
        $result = $conn->query("SELECT days FROM material WHERE id=$material_id");
        $material = $result->fetch_assoc();
        $days = $material['days'];
        $total_days += $days;

        $conn->query("INSERT INTO work_order_material (work_order_id, material_id, days) VALUES ($work_order_id, $material_id, $days)");
    }

    // Calculate finish date
    $finish_date = date('Y-m-d', strtotime("$work_date + $total_days days"));
    $conn->query("UPDATE work_order SET finish_date='$finish_date' WHERE id=$work_order_id");

    header("Location: index.php");
    exit;
}

// Get materials
$materials = $conn->query("SELECT * FROM material");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Work Order</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Create Work Order</h1>
        <form method="post">
            <div class="form-group">
                <label for="work_order_no">Work Order No:</label>
                <input type="text" id="work_order_no" name="work_order_no" required>
            </div>

            <div class="form-group">
                <label for="client">Client:</label>
                <input type="text" id="client" name="client" required>
            </div>

            <div class="form-group">
                <label for="work_date">Work Date:</label>
                <input type="date" id="work_date" name="work_date" required>
            </div>

            <div class="form-group">
                <label for="material">Material:</label>
                <select id="material" name="material[]" multiple required>
                    <?php while ($row = $materials->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['material_name']) ?> (<?= $row['days'] ?> days)</option>
                    <?php endwhile; ?>
                </select>
            </div>

            <input type="submit" value="Submit" class="btn">
        </form>
    </div>
</body>
</html>
