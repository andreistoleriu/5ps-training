<?php

require_once 'common.php';

if (!$_SESSION['authenticated']) {
    header('Location: login.php');
    die();
};

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

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitize(__('Orders page')) ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>

<body>
    <?php if (empty($order)) : ?>
        <p><?= sanitize(__('No orders')) ?></p>
    <?php else : ?>

        <p><?=sanitize(__('Order')) . ' ' . $order[0]['order_id'] ?></p>
        <p><?= sanitize(__('Name')) . ': ' . $order[0]['name'] ?></p>
        <p><?= sanitize(__('Email')) . ': ' . $order[0]['contact_details'] ?></p>

        <table class="table">
            <tr>
                <th><?= sanitize(__('Product')) ?></th>
                <th><?= sanitize(__('Image')) ?></th>
                <th><?= sanitize(__('Title')) ?></th>
                <th><?= sanitize(__('Description')) ?></th>
                <th><?= sanitize(__('Price')) ?></th>
            </tr>

            <?php foreach ($order as $row) : ?>
                <tr>
                    <td><?= sanitize($row['product_id']) ?></td>
                    <td>
                        <?php if ($row['image']) : ?>
                            <img alt="<?= sanitize(__('Product image')) ?>" src="img/<?= sanitize($row['image']) ?>" width="70px" height="70px">
                        <?php else : ?>
                            <p><?= sanitize(__('No image')) ?></p>
                        <?php endif ?>
                    </td>
                    <td><?= sanitize($row['title']) ?></td>
                    <td><?= sanitize($row['description']) ?></td>
                    <td><?= sanitize($row['price']) ?></td>
                </tr>
            <?php endforeach ?>
        </table>
    <?php endif ?>
    <span><a class="btn btn-primary" href="orders.php"><?= sanitize(__('Orders')) ?></a></span>
    <span><a class="btn btn-primary" href="orders.php"><?= sanitize(__('Products')) ?></a></span>
</body>

</html>