<?php

require_once 'common.php';
require_once 'auth.php';

if (isset($_GET['logout'])) {
    $_SESSION['authenticated'] = 0;
    header('Location: index.php');
    die();
}

if (isset($_POST['delete'])) {
    $query = 'SELECT * FROM products WHERE id = ?';
    $stmt = $connection->prepare($query);
    $res = $stmt->execute([$_POST['id']]);
    $row = $stmt->fetch();

    $deleteQuery = 'DELETE FROM products WHERE id = ?';
    $stmt = $connection->prepare($deleteQuery);
    $stmt->execute([$_POST['id']]);

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
                <form method="post" action="products.php">
                        <tr>
                            <td><img src="img/<?= $row['image'] ?>" style="width: 200px" alt=""></td>
                            <td><?= $row['title'] ?></td>
                            <td><?= $row['description'] ?></td>
                            <td>$<?= $row['price'] ?></td>
                            <td>
                                <a href="product.php?edit=<?= $row['id']; ?>" class="btn btn-warning"><?= __('Edit') ?></a>
                                <input type="submit" name="delete" class="btn btn-danger" value="<?= __('Delete') ?>" />
                            </td>
                            <td><input type="hidden" name="id" value="<?= $row['id'] ?>" /></td>
                        </tr>
                    </form>
            <?php endforeach; ?>
        </table>
        <hr style="background-color: black; height: 1px;">
        <div class="d-flex justify-content-around">
            <a href="?logout" class="btn btn-warning"><?= __('Log out') ?></a>
            <a href="orders.php" class="btn btn-primary"><?= __('Orders') ?></a>
            <a href="product.php" class="btn btn-primary"><?= __('Add new product') ?></a>
        </div>
    </div>
</body>

</html>