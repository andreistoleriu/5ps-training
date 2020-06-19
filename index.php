<?php

require_once 'common.php';

if (empty($_SESSION['cart'])) {
    $query = 'SELECT * FROM products';
    $_SESSION['cart'] = [];
} else {
    $query = 'SELECT * FROM products WHERE id NOT IN (' . implode(',', array_fill(0, count($_SESSION['cart']), '?')) . ')';
}

if (isset($_POST['id']) && !in_array($_POST['id'], $_SESSION['cart'])) {
    array_push($_SESSION['cart'], $_POST['id']);
    header('Location: index.php');
    die();
};

$stmt = $connection->prepare($query);
$res = $stmt->execute(array_values($_SESSION['cart']));
$rows = $stmt->fetchAll();

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('Cart Index') ?></title>
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
                    <th scope="col"><?= __('Action') ?></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <?php foreach ($rows as $row) : ?>
                <form method="post" action="index.php">
                    <tr>
                        <td><img src="img/<?= $row['image'] ?>" style="width: 200px" alt=""></td>
                        <td><?= $row['title'] ?></td>
                        <td><?= $row['description'] ?></td>
                        <td> $ <?= $row['price'] ?></td>
                        <td><input type="submit" name="add" class="btn btn-primary" value="<?= __('Add') ?>" /></td>
                        <td><input type="hidden" name="id" value="<?= $row['id'] ?>" /></td>
                        <td><a href="product_details.php?=<?= $row['id']; ?>" class="btn btn-warning"><?= __('View Details') ?></a></td>
                    </tr>
                </form>
            <?php endforeach; ?>
        </table>
        <a href="cart.php" class="btn btn-warning"><?= __('Go to cart') ?></a>
    </div>
</body>


</html>