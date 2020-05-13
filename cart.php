<?php

require_once 'common.php';

if (isset($_GET['id'])) {
    $key = array_search($_GET['id'], $_SESSION['cart']);
    if ($key !== false) {
        unset($_SESSION['cart'][$key]);
    }
    header('Location: cart.php');
    die();
}

if(count($_SESSION['cart'])) {
    $query = 'SELECT * FROM products WHERE id IN (' . implode(',', array_fill(0, count($_SESSION['cart']), '?')) . ')';
} else {
    $query = 'SELECT * FROM products';
}

$stmt = $connection->prepare($query);
$res = $stmt->execute(array_values($_SESSION['cart']));
$rows = $stmt->fetchAll();

$timestamp = date('Y-m-d H:i:s');

$name = $contactDetails = $comments = '';
$errors = [];
$total = 0;

if (isset($_POST['checkout'])) {

    if (empty($_POST['name'])) {
        $errors['name'][] = __('Name is required');
    } else {
        $name = $_POST['name'];
    }
    if (empty($_POST['contactDetails'])) {
        $errors['contactDetails'][] = __('Contact details are required');
    } else {
        $contactDetails = $_POST['contactDetails'];
    }
    $comments = $_POST['comments'];

    if (!$errors) {

        $to = SHOPMANAGER;
        $subject = 'Order number #';
        $headers = 'From: example@gmail.com' . "\r\n" .
            'MIME-Version: 1.0' . "r\n" .
            'Content-Type: text/html; charset=utf-8';        
        include 'message.php';

        $query = 'INSERT INTO orders(name, contact_details, created_at) VALUES (?, ?, ?)';
        $stmt = $connection->prepare($query);
        $stmt->execute([$name, $contactDetails, $timestamp]);
        $lastId = $connection->lastInsertId();

        foreach ($_SESSION['cart'] as $product) {
            $query = 'INSERT INTO product_order(order_id ,product_id, created_at) VALUES (?, ?, ?)';
            $stmt = $connection->prepare($query);
            $stmt->execute([$lastId, $product, $timestamp]);
        }
        
        mail($to, $subject, $message, $headers);
        $_SESSION['cart'] = [];
        header('Location: cart.php?sent=1');
        die();
    }
}

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('Cart') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>

<body>
    <div class="container">
        <?php if (empty($_SESSION['cart'])) : ?>
            <?php if (isset($_GET['sent'])) : ?>
                <p><?= sanitize(__('Your order was sent. Thank you!')); ?></p>
            <?php endif; ?>
            <h2 class="text-warning"> <?= sanitize(__('Cart is empty')) ?><h2>
        <?php else: ?>
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col"></th>
                        <th scope="col"><?= sanitize(__('Title')) ?></th>
                        <th scope="col"><?= sanitize(__('Description')) ?></th>
                        <th scope="col"><?= sanitize(__('Price')) ?></th>
                        <th scope="col"><?= sanitize(__('Action')) ?></th>
                    </tr>
                </thead>
                <?php foreach ($rows as $row) : ?>
                    <tr>
                        <td><img src="img/<?= sanitize($row['image']) ?>" style="width: 200px" alt=""></td>
                        <td><?= sanitize($row['title']) ?></td>
                        <td><?= sanitize($row['description']) ?></td>
                        <td>$<?= sanitize($row['price']) ?></td>
                        <td><a href="?id=<?= sanitize($row['id']) ?>"><?= __('Delete') ?></a></td>
                    </tr>
                <?php
                $total += $row['price'];
                endforeach; ?>
                    <tr>
                        <td colspan="3"><b><?= sanitize(__('Total')) ?></b></td>
                        <td colspan="2"><b>$<?= sanitize($total) ?></b></td>
                    </tr>
            </table>
            <form class="form-group" method="POST" action="cart.php">
                <label for="name"><?= sanitize(__('Name')) ?></label>
                <input type="text" name="name" placeholder="<?= sanitize(__('Insert your name')) ?>" class="form-control" value="<?= sanitize($name) ?>">
                <?php $errorKey = 'name' ?>
                <?php include 'errors.php' ?>
                <label for="contactDetails"><?= sanitize(__('Contact details')) ?></label>
                <textarea rows="2" cols="30" name="contactDetails" placeholder="<?= sanitize(__('Insert your contact details')) ?>" class="form-control" value="<?= sanitize($contactDetails) ?>"></textarea>
                <?php $errorKey = 'contactDetails' ?>
                <?php include 'errors.php' ?>
                <label for="comments"><?= sanitize(__('Comments')) ?></label>
                <textarea rows="4" cols="30" name="comments" placeholder="<?= sanitize(__('Insert comments')) ?>" class="form-control" value="<?= sanitize($comments) ?>"></textarea>
                <input type="submit" class="btn btn-primary" name="checkout" value="<?= sanitize(__('Checkout')) ?>">
            </form>
        <?php endif; ?>
        <a href="index.php" class="btn btn-warning"><?= sanitize(__('Go to index')) ?></a>
    </div>
</body>

</html>