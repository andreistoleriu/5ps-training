<?php

require_once 'common.php';

if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST["add"])) {
    if (isset($_GET['id']) && !in_array($_GET['id'], $_SESSION['cart'])) {
        array_push($_SESSION['cart'], $_GET['id']);
        header("Location: index.php");
        die();
    }
};

$query =
    'SELECT * FROM products' . (count($_SESSION['cart']) ?
        ' WHERE id 
        NOT IN (' . implode(',', array_fill(0, count($_SESSION['cart']), '?')) . ')' :
        '');

$stmt = $connection->prepare($query);
$res = $stmt->execute($_SESSION['cart']);
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
                </tr>
            </thead>
            <?php foreach ($rows as $row) : ?>
                <form method="post" action="index.php?action=add&id=<?= $row["id"]; ?>">
                    <tr>
                        <td><img src="<?= $row['image'] ?>" style="width: 200px" alt=""></td>
                        <td><?= $row['title'] ?></td>
                        <td><?= $row['description'] ?></td>
                        <td><?= '$' . $row['price'] ?></td>
                        <td><input type="submit" name="add" class="btn btn-primary" value="<?= __('Add') ?>" /></td>
                    </tr>
                </form>
            <?php endforeach; ?>
        </table>

        <a href="cart.php"><?= __('Go to cart') ?></a>
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </div>
</body>

</html>