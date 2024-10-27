<?php

$conn = new mysqli("localhost", "root", "", "raw_material_work_order_db");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $work_order_no = $_POST['work_order_no'];
    $client = $_POST['client'];
    $work_date = $_POST['work_date'];
    $materials = $_POST['material'];
    $percentages = $_POST['percentage']; 
    $total_days = 0;

    $conn->query("INSERT INTO work_order (work_order_no, client, work_date) VALUES ('$work_order_no', '$client', '$work_date')");
    $work_order_id = $conn->insert_id;

    foreach ($materials as $index => $material_id) {
        $result = $conn->query("SELECT days FROM material WHERE id=$material_id");
        $material = $result->fetch_assoc();
        $days = $material['days'];
        $total_days += $days;

        $percentage = $percentages[$index]; 
        $conn->query("INSERT INTO work_order_material (work_order_id, material_id, days, percentage) VALUES ($work_order_id, $material_id, $days, $percentage)");
    }

    $finish_date = date('Y-m-d', strtotime("$work_date + $total_days days"));
    $conn->query("UPDATE work_order SET finish_date='$finish_date' WHERE id=$work_order_id");

    header("Location: index.php");
    exit;
}

$materials_result = $conn->query("SELECT * FROM material");
$materials_options = "";

while ($row = $materials_result->fetch_assoc()) {
    $materials_options .= '<option value="' . $row['id'] . '">' . htmlspecialchars($row['material_name']) . ' (' . $row['days'] . ' days)</option>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Work Order</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        const materialsOptions = `<?= $materials_options ?>`; // Store material options in a variable

        function addMaterial() {
            const materialContainer = document.getElementById('material-container');
            const newMaterial = document.createElement('div');
            newMaterial.className = 'material-row';
            newMaterial.innerHTML = `
                <select name="material[]" required>
                    ${materialsOptions} <!-- Use the variable here -->
                </select>
                <input type="number" name="percentage[]" min="0" max="100" step="1" required placeholder="Percentage">
                <button type="button" onclick="removeMaterial(this)">Remove</button>
            `;
            materialContainer.appendChild(newMaterial);
        }

        function removeMaterial(button) {
            const materialRow = button.parentElement;
            materialRow.parentElement.removeChild(materialRow);
        }
    </script>
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

            <div id="material-container">
                <div class="material-row">
                    <label for="material">Material:</label>
                    <select name="material[]" required>
                        <?= $materials_options ?> 
                    </select>
                    <input type="number" name="percentage[]" min="0" max="100" step="1" required placeholder="Percentage">
                </div>
            </div>

            <button type="button" onclick="addMaterial()">Add Another Material</button>
            <input type="submit" value="Submit" class="btn">
        </form>
    </div>
</body>
</html>
