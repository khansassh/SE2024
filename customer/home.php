<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #a1c4fd, #c2e9fb);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .container:hover {
            transform: scale(1.03);
        }
        h1 {
            color: #ff4081;
            font-size: 28px;
            margin-bottom: 20px;
        }
        button {
            background-color: #ff6f61;
            color: white;
            padding: 15px 25px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px;
            transition: background-color 0.3s, box-shadow 0.3s;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }
        button:hover {
            background-color: #ff4081;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎉 Welcome to the Order Management System 🎉</h1>
        <button onclick="window.location.href='order.php'">Order Product</button>
        <button onclick="window.location.href='product.php'">Product</button>
        <button onclick="window.location.href='placed_order.php'">Placed Orders</button>
    </div>
</body>
</html>
