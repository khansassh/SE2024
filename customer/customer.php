<?php
include_once("db.php");

if (isset($_POST['Order_Form'])) {
    $order_ID= $_POST['order_id'];  // ID of the related committee
    $order_Number = $_POST['order_number'];
    $customer_ref= $_POST['customer_ref'];
    $order_date = $_POST['order_date'];
    $quantity = $_POST['quantity'];
    $discount = $_POST['discount'];

    $sql = "INSERT INTO orders (order_id, order_number, customer_ref, order_date, quantity, discount) 
            VALUES ('$order_id', '$order_number', '$customer_ref', '$order_date', '$quantity', '$discount')";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Customer Order added successfully.');window.location.href = 'displayorder.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function addProduct() {
            // JavaScript code to duplicate the product selection fields
            const productDiv = document.getElementById("product-template").cloneNode(true);
            productDiv.style.display = "block"; // Show the new product div
            document.getElementById("products").appendChild(productDiv);
        }
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h3 {
            margin-top: 20px;
        }
        input[type="text"],
        input[type="date"],
        input[type="number"],
        select {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"],
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover,
        button:hover {
            background-color: #45a049;
        }
        .product {
            display: none; /* Hidden by default */
        }
    </style>
</head>
<body>

<form action="process_order.php" method="POST">
    <!-- Your input fields here -->
    <label for="order number">order number<input type="text" name="order_number" required></label>
    <input type="text" name="customer_ref">
    <input type="date" name="order_date" required>
    <h3>Products</h3>
    <div id="products">
        <div class="product" id="product-template">
            <select name="product_id[]" required>
                <?php
                $result = $conn->query("SELECT * FROM products");
                while($row = $result->fetch_assoc()) {
                    echo "<option value='".$row['product_id']."'>".$row['name']."</option>";
                }
                ?>
            </select>
            <label>Quantity:</label>
            <input type="number" name="quantity[]" min="1" required>
            <label>Discount (%):</label>
            <input type="number" name="discount[]" min="0" max="100" required>
            <br>
        </div>
    </div>
    <button type="button" onclick="addProduct()">Add Another Product</button>
    <input type="submit" name="Submit" value="Submit" class="btn btn-primary btn-block">
</form>


</body>
</html>
