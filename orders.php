<?php

require_once 'common.php';

if (!$_SESSION['authenticated']) {
    header('Location: login.php');
    die();
};

$query = 'SELECT 
orders.*, 
SUM(products.price) AS price
FROM orders
JOIN product_order
ON product_order.order_id = orders.id
JOIN products
ON products.id = product_order.product_id
GROUP BY orders.id';

$stmt = $connection->prepare($query);
$res = $stmt->execute();
$orders = $stmt->fetchAll();

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitize(__('Orders page')) ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>

<body>
    <?php if (empty($orders)) : ?>
        <p><?= sanitize(__('No orders')) ?></p>
    <?php else : ?>
        <table class="table">
            <tr>
                <th><?= sanitize(__('ID')) ?>
                <th><?= sanitize(__('Name')) ?>
                <th><?= sanitize(__('Price')) ?>
                <th><?= sanitize(__('Order details')) ?>
                <th><?= sanitize(__('Created at')) ?>
            </tr>

            <?php foreach ($orders as $order) : ?>
                <tr>
                    <td><?= sanitize($order['id']) ?></td>
                    <td><?= sanitize($order['name']) ?></td>
                    <td><?= sanitize($order['price']) ?></td>
                    <td><a href="order.php?id=<?= sanitize($order['id']) ?>"><?= sanitize(__('View')) ?></a></td>
                    <td><?= sanitize($order['created_at']) ?></td>
                </tr>
            <?php endforeach ?>
        </table>
    <?php endif ?>
    <span><a class="btn btn-primary" href="products.php"><?= sanitize(__('Products')) ?></a></span>
</body>

</html>