<!DOCTYPE html>
<html>

<head>
    <title>Low Stock Alert</title>
</head>

<body>
    <p>Dear Admin,</p>
    <p>The stock for <strong>{{ $product->name }}</strong> is running low.</p>
    <p>Current Stock: <strong>{{ $product->stock_quantity }}</strong></p>
    <p>Minimum Required Stock: <strong>{{ $product->min_notification_stock }}</strong></p>
    <p>Please restock as soon as possible.</p>
</body>

</html>