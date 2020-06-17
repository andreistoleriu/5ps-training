<?php

require_once 'common.php';

if (isset($_POST['id'])) {
    $key = array_search($_POST['id'], $_SESSION['cart']);
    if ($key !== false) {
        unset($_SESSION['cart'][$key]);
    }
    header('Location: cart.php');
    die();
}

if (count($_SESSION['cart'])) {
    $query = 'SELECT * FROM products WHERE id IN (' . implode(',', array_fill(0, count($_SESSION['cart']), '?')) . ')';
    $stmt = $connection->prepare($query);
    $res = $stmt->execute(array_values($_SESSION['cart']));
    $rows = $stmt->fetchAll();
}

$timestamp = date('Y-m-d H:i:s');

$name = $contactDetails = $comments = '';
$errors = [];
$total = 0;

if (isset($_POST['checkout'])) {
    if (empty($_POST['name'])) {
        $errors['name'][] = __('Name is required');
    } else {
        $name = strip_tags($_POST['name']);
    }
    if (empty($_POST['contactDetails'])) {
        $errors['contactDetails'][] = __('Contact details are required');
    } else {
        $contactDetails = strip_tags($_POST['contactDetails']);
    }
    $comments = strip_tags($_POST['comments']);

    if (!$errors) {
        $to = SHOPMANAGER;
        $subject = __('Order number #');
        $headers = 'From: example@gmail.com' . "\r\n" .
            'MIME-Version: 1.0' . "r\n" .
            'Content-Type: text/html; charset=utf-8';
        ob_start();
        include 'message.php';
        $message = ob_get_contents();
        ob_end_clean();
      
        $query = 'INSERT INTO orders(name, contact_details, order_total, created_at) VALUES (?, ?, ?, ?)';
        $stmt = $connection->prepare($query);
        $stmt->execute([$name, $contactDetails, $total, $timestamp]);
        $lastId = $connection->lastInsertId();
        
        foreach ($rows as $row) {
            $query = 'INSERT INTO product_order(order_id, product_id, product_price, created_at) VALUES (?, ?, ?, ?)';
            $stmt = $connection->prepare($query);
            $stmt->execute([$lastId, $row['id'], $row['price'], $timestamp]);
        }

        mail($to, $subject, $message, $headers);
        $_SESSION['cart'] = [];
        header('Location: cart.php');
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
                <h2 class="text-primary"><?= __('Your order was sent. Thank you!'); ?></h2>
            <?php endif; ?>
            <h2 class="text-warning"> <?= __('Cart is empty') ?><h2>
        <?php else : ?>
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col"></th>
                        <th scope="col"><?= __('Title') ?></th>
                        <th scope="col"><?= __('Description') ?></th>
                        <th scope="col"><?= __('Price') ?></th>
                        <th scope="col"><?= __('Action') ?></th>
                    </tr>
                </thead>
                <?php foreach ($rows as $row) : ?>
                    <form method="post" action="cart.php">
                        <tr>
                            <td><img src="img/<?= $row['image'] ?>" style="width: 200px" alt=""></td>
                            <td><?= $row['title'] ?></td>
                            <td><?= $row['description'] ?></td>
                            <td>$<?= $row['price'] ?></td>
                            <td><input type="submit" name="delete" class="btn btn-primary" value="<?= __('Delete') ?>" /></td>
                            <td><input type="hidden" name="id" value="<?= $row['id'] ?>" /></td>
                        </tr>
                    </form>
                <?php
                $total += $row['price'];
                endforeach; ?>
                    <tr>
                        <td colspan="3"><b><?= __('Total') ?></b></td>
                        <td colspan="2"><b>$<?= $total ?></b></td>
                    </tr>
            </table>
            <form class="form-group" method="POST" action="cart.php">
                <label for="name"><?= __('Name') ?></label>
                <input type="text" name="name" placeholder="<?= __('Insert your name') ?>" class="form-control" value="<?= $name ?>">
                <?php $errorKey = 'name' ?>
                <?php include 'errors.php' ?>
                <label for="contactDetails"><?= __('Contact details') ?></label>
                <textarea rows="2" cols="30" name="contactDetails" placeholder="<?= __('Insert your contact details') ?>" class="form-control" value="<?= $contactDetails ?>"></textarea>
                <?php $errorKey = 'contactDetails' ?>
                <?php include 'errors.php' ?>
                <label for="comments"><?= __('Comments') ?></label>
                <textarea rows="4" cols="30" name="comments" placeholder="<?= __('Insert comments') ?>" class="form-control" value="<?= $comments ?>"></textarea>
                <input type="submit" class="btn btn-primary" name="checkout" value="<?= __('Checkout') ?>">
            </form>
        <?php endif; ?>
        <a href="index.php" class="btn btn-warning"><?= __('Go to index') ?></a>
    </div>
</body>

</html>