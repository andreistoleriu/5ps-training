<?php

require_once 'common.php';
require_once 'auth.php';

$errors = [];
$total = 0;

$query = 'SELECT 
            orders.*,
            product_order.order_id, 
            product_order.product_id,  
            products.*
        FROM orders
        JOIN product_order
        ON product_order.order_id = orders.id
        JOIN products
        ON products.id = product_order.product_id
        WHERE orders.id = ?';

$stmt = $connection->prepare($query);
$res = $stmt->execute([$_GET['id']]);
$order = $stmt->fetchAll();

if (!$order) {
    $errors['order'][] = __('Order no longer available in the database!');
}

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
        <?php if (isset($errors['order'])) : ?>
            <?php $errorKey = 'order' ?>
            <?php include 'errors.php' ?>
        <?php else : ?>

            <p><?=__('Order') . ' ' . $order[0]['order_id'] ?></p>
            <p><?= __('Name') . ': ' . $order[0]['name'] ?></p>
            <p><?= __('Email') . ': ' . $order[0]['contact_details'] ?></p>

            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th><?= __('Product ID') ?></th>
                        <th><?= __('Image') ?></th>
                        <th><?= __('Title') ?></th>
                        <th><?= __('Description') ?></th>
                        <th><?= __('Price') ?></th>
                    </tr>
                </thead>

                <?php foreach ($order as $row) : ?>
                    <tr>
                        <td><?= $row['product_id'] ?></td>
                        <td>
                            <?php if ($row['image']) : ?>
                                <img alt="<?= __('Product image') ?>" src="img/<?= $row['image'] ?>" width="150px">
                            <?php else : ?>
                                <p><?= __('No image') ?></p>
                            <?php endif ?>
                        </td>
                        <td><?= $row['title'] ?></td>
                        <td><?= $row['description'] ?></td>
                        <td>$<?= $row['price'] ?></td>
                    </tr>
                <?php
                    $total += $row['price'];
                    endforeach; ?>
                        <tr>
                            <td colspan="4" align="middle"><b><?= __('Total') ?></b></td>
                            <td colspan="1"><b>$<?= $total ?></b></td>
                        </tr>
            </table>
        <?php endif ?>
        <span><a class="btn btn-primary" href="orders.php"><?= __('Orders') ?></a></span>
        <span><a class="btn btn-primary" href="orders.php"><?= __('Products') ?></a></span>
    </div>
</body>

</html>