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
    $sql = 'SELECT * FROM products WHERE id = ?';
    $stmt = $connection->prepare($sql);
    $res = $stmt->execute([$_GET['delete']]);
    $row = $stmt->fetch();

    $deleteSql = 'DELETE FROM products WHERE id = ?';
    $stmt = $connection->prepare($deleteSql);
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
    <title>Document</title>
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
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <?php foreach ($rows as $row) : ?>
                <tr>
                    <td><img src="<?= $row['image'] ?>" style="width: 200px" alt=""></td>
                    <td><?= $row['title'] ?></td>
                    <td><?= $row['description'] ?></td>
                    <td> $ <?= $row['price'] ?></td>
                    <td><input type="submit" name="edit" class="btn btn-primary" value="<?= __('Edit') ?>" /></td>
                    <td><a href="?delete=<?= $row['id']; ?>" class="btn btn-danger">Delete</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <a href="product.php" class="btn btn-warning">Add</a>
        <a href="?logout" class="btn btn-warning">Log out</a>

</body>

</html>