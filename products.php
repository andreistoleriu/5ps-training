<?php
require_once 'common.php';

if (!$_SESSION['authenticated']) {
    header('Location: login.php');
    die();
}

if (isset($_GET['logout'])) {
    header('Location: login.php');
    unset($_SESSION['authenticated']);
    die();
}

if (isset($_GET['delete'])) {
    $query = 'SELECT * FROM products WHERE id = ?';
    $stmt = $connection->prepare($query);
    $res = $stmt->execute([$_GET['delete']]);
    $row = $stmt->fetch();

    $deleteQuery = 'DELETE FROM products WHERE id = ?';
    $stmt = $connection->prepare($deleteQuery);
    $stmt->execute([$_GET['delete']]);

    header('Location: products.php');
    die();
}

$query = 'SELECT * FROM products';
$stmt = $connection->prepare($query);
$res = $stmt->execute();
$rows = $stmt->fetchAll();


?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('Products page') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>

<body>

    <div class="container">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col"></th>
                    <th scope="col"><?= __('Title') ?></th>
                    <th scope="col"><?= __('Description') ?></th>
                    <th scope="col"><?= __('Price') ?></th>
                    <th scope="col" colspan="2"><?= __('Action') ?></th>
                </tr>
            </thead>
            <?php foreach ($rows as $row) : ?>
                <tr>
                    <td><img src="img/<?= $row['image'] ?>" style="width: 200px" alt=""></td>
                    <td><?= $row['title'] ?></td>
                    <td><?= $row['description'] ?></td>
                    <td> $ <?= $row['price'] ?></td>
                    <td>
                        <a href="product.php?edit=<?= $row['id']; ?>" class="btn btn-warning"><?= __('Edit') ?></a>
                        <a href="?delete=<?= $row['id']; ?>" class="btn btn-danger"><?= __('Delete') ?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <a href="product.php" class="btn btn-primary"><?= __('Add') ?></a>
        <a href="orders.php" class="btn btn-primary"><?= __('Orders') ?></a>
        <a href="?logout" class="btn btn-primary"><?= __('Log out') ?></a>

</body>

</html>