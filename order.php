<?php

require_once 'common.php';
require_once 'auth.php';

$errors = [];
$total = 0;

$queryOrder = 'SELECT
            orders.id,
            orders.name,
            orders.contact_details
            FROM orders
            WHERE orders.id = ?';

$orderStmt = $connection->prepare($queryOrder);
$orderRes = $orderStmt->execute([$_GET['id']]);
$order = $orderStmt->fetch();

$queryProducts = 'SELECT 
                product_order.order_id,
                product_order.product_id,
                product_order.product_price,
                products.image,
                products.id,
                products.title,
                products.description
                FROM product_order
                JOIN products
                ON products.id = product_order.product_id
                WHERE product_order.order_id = ?';

$prodStmt = $connection->prepare($queryProducts);
$prodRes = $prodStmt->execute([$_GET['id']]);
$products = $prodStmt->fetchAll();

if (!$products || !$order) {
    $errors['order'][] = __('Order no longer available!');
}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('Order page') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>

<body>
    <div class="container">
        <?php if (isset($errors['order'])) : ?>
            <?php $errorKey = 'order' ?>
            <?php include 'errors.php' ?>

        <?php else : ?>
            <p><?=__('Order') . ' ' . $order['id'] ?></p>
            <p><?= __('Name') . ': ' . $order['name'] ?></p>
            <p><?= __('Contact details') . ': ' . $order['contact_details'] ?></p>

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

                <?php foreach ($products as $product) : ?>
                    <tr>
                        <td><?= $product['product_id'] ?></td>
                        <td>
                            <?php if ($product['image']) : ?>
                                <img alt="<?= __('Product image') ?>" src="img/<?= $product['image'] ?>" width="150px">
                            <?php else : ?>
                                <p><?= __('No image') ?></p>
                            <?php endif; ?>
                        </td>
                        <td><?= $product['title'] ?></td>
                        <td><?= $product['description'] ?></td>
                        <td>$<?= $product['product_price'] ?></td>
                    </tr>
                <?php
                    $total += $product['product_price'];
                    endforeach;
                ?>
                    <tr>
                        <td colspan="4" align="middle"><b><?= __('Total') ?></b></td>
                        <td colspan="1"><b>$<?= $total ?></b></td>
                    </tr>
            </table>
        <?php endif; ?>
        <span><a class="btn btn-primary" href="orders.php"><?= __('Orders') ?></a></span>
        <span><a class="btn btn-primary" href="orders.php"><?= __('Products') ?></a></span>
    </div>
</body>

</html>