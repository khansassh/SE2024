<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include 'db.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $order_number = $_POST['order_number'];
    $customer_ref = $_POST['customer_ref'];
    $order_date = $_POST['order_date'];
    $product_ids = $_POST['product_id'];
    $quantities = $_POST['quantity'];
    $discounts = $_POST['discount'];
    $subtotal = $_POST['subtotal'];

    // Prepare statement for inserting the order
    $stmt = $conn->prepare("INSERT INTO orders (order_number, customer_ref, order_date) VALUES (?, ?, ?)");
    
    // Bind parameters
    $stmt->bind_param("sss", $order_number, $customer_ref, $order_date);
    
    // Execute and check for errors
    if ($stmt->execute()) {
        $order_id = $stmt->insert_id; // Get the last inserted order ID

        // Prepare statement for order items
        $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, discount) VALUES (?, ?, ?, ?)");

        foreach ($product_ids as $index => $product_id) {
            $quantity = $quantity[$index];
            $discount = $discount[$index];

            // Bind parameters for order items
            $stmt_items->bind_param("iiid", $order_id, $product_id, $quantity, $discount);
            
            // Execute and check for errors
            if (!$stmt_items->execute()) {
                echo "Error: " . $stmt_items->error; // Log the error if it fails
            }
        }

        // Close the item statement
        $stmt_items->close();

        // Success message
        echo "<script>alert('Order added successfully!'); window.location.href = 'displayorder.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the main statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
