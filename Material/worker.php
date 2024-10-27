<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "work_order_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    $work_order_no = $conn->real_escape_string(trim($_POST['work_order_no']));
    $client = $conn->real_escape_string(trim($_POST['client']));
    $work_date = $conn->real_escape_string($_POST['work_date']);
    $total_days = 0;

    $stmt = $conn->prepare("INSERT INTO work_orders (work_order_no, client, work_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $work_order_no, $client, $work_date);

    if ($stmt->execute()) {
        $work_order_id = $stmt->insert_id; 

        foreach ($_POST['material'] as $index => $material) {
            $days = $_POST['days'][$index];
            $percentage = $_POST['percentage'][$index];

            if (!is_numeric($days) || !is_numeric($percentage)) {
                echo "Days and percentage must be numeric.<br>";
                continue;
            }

            $stmt_material = $conn->prepare("INSERT INTO work_order_materials (work_order_id, material_id, percentage) VALUES (?, ?, ?)");
            $stmt_material->bind_param("iii", $work_order_id, $material, $percentage);

            if (!$stmt_material->execute()) {
                echo "Error: " . $stmt_material->error;
            }

            $total_days += (int)$days;
            $stmt_material->close();
        }

        $finish_date = date('Y-m-d', strtotime($work_date . " + $total_days days"));

        $stmt_update = $conn->prepare("UPDATE work_orders SET finish_date = ? WHERE id = ?");
        $stmt_update->bind_param("si", $finish_date, $work_order_id);

        if (!$stmt_update->execute()) {
            echo "Error: " . $stmt_update->error; 
        }

        echo "<script>alert('Order successfully added!\\nWork Order No: $work_order_no\\nClient: $client\\nWork Date: $work_date');</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
