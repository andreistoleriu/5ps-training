<?php

require_once 'common.php';
require_once 'auth.php';

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
    <title><?= __('Orders page') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>

<body>
    <div class="container">
        <?php if (empty($orders)) : ?>
            <p><?= __('No orders') ?></p>
        <?php else : ?>
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th><?= __('ID') ?>
                        <th><?= __('Name') ?>
                        <th><?= __('Total price') ?>
                        <th><?= __('Order details') ?>
                        <th><?= __('Created at') ?>
                    </tr>
                </thead>
                    
                <?php foreach ($orders as $order) : ?>
                    <tr>
                        <td><?= $order['id'] ?></td>
                        <td><?= $order['name'] ?></td>
                        <td>$<?= $order['price'] ?></td>
                        <td><a href="order.php?id=<?= $order['id'] ?>"><?= __('View') ?></a></td>
                        <td><?= $order['created_at'] ?></td>
                    </tr>
                <?php endforeach ?>
            </table>
        <?php endif ?>
        <span><a class="btn btn-primary" href="products.php"><?= __('Products') ?></a></span>
    </div>
</body>

</html>