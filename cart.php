<?php

session_start();

require_once 'config.php';
require_once 'common.php';

$query = 'SELECT * 
          FROM `products` 
         WHERE `id` IN (' . implode(', ', $_SESSION['cart']) . ')';

$stmt = $connection->prepare($query);
$res = $stmt->execute($_SESSION['cart']);
$stmt->setFetchMode(PDO::FETCH_ASSOC);
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
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <?php foreach ($rows as $row) : ?>
                <tr>
                    <td><?= $row['title'] ?></td>
                    <td><?= $row['description'] ?></td>
                    <td><?= $row['price'] ?></td>
                    <td><a href="?delete=<?= $row['id'] ?>">Remove</a></td>
                </tr>
            <?php endforeach; ?>

        </table>

        <form class="form-group">
            <input type="text" name="name" value="" placeholder="Name" class="form-control"> <br />
            <textarea rows="2" cols="30" name="contactDetails" value="" placeholder="Contact details" class="form-control"></textarea> <br />
            <textarea rows="4" cols="30" name="comments" value="" placeholder="Comments" class="form-control"></textarea> <br />
            <button type="submit" class="btn btn-primary" name="checkout">Checkout</button>
        </form>
        <a href="index.php">Go to index</a>

        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </div>
</body>

</html>