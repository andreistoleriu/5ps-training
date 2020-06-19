<?php

require_once 'common.php';

$timestamp = date('Y-m-d H:i:s');

if (isset($_GET['view_details']) && $_GET['view_details']) {
    $query = 'SELECT * FROM products WHERE id = ?';
    $stmt = $connection->prepare($query);
    $res = $stmt->execute([$_GET['view_details']]);
    $row = $stmt->fetch();
}

if (isset($_POST['add_a_comment'])) {
    $comment = strip_tags($_POST['comment']);
    $addCommQuery = 'INSERT INTO comments(product_id, message, date) VALUES (?, ?, ?)';
    $stmt = $connection->prepare($addCommQuery);
    $stmt->execute([$_GET['view_details'], $comment, $timestamp]);
    header("Location: product_details.php?view_details={$_GET['view_details']}");
}

$commQuery = 'SELECT
                comments.*,
                products.id
                FROM comments
                JOIN products 
                ON products.id = comments.product_id
                WHERE comments.product_id = ?';


$commStmt = $connection->prepare($commQuery);
$commStmt->execute([$_GET['view_details']]);
$comments = $commStmt->fetchAll(PDO::FETCH_ASSOC);

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('Product details page') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>

<body>
    <div class='container'>
        <h1><?= ('Product details') ?></h1>
        <hr>
        <div class="card" style="width: 30rem">
            <img src="img/<?= $row['image'] ?>" class="card-img-top" alt="">
            <div class="card-body">
                <h5 class="card-title"><?= $row['title'] ?></h5>
                <p class="card-text"><?= $row['description'] ?></p>
                <h5>$<?= $row['price'] ?></h5>
            </div>
        </div>
        <a style="margin-top:15px" href="index.php" class="btn btn-warning"><?= __('Go to index') ?></a>
        <h5 style="margin-top: 15px"><?= ('Comments:') ?> </h5>
        <hr style="background-color: black; height: 1px;">
        <?php foreach ($comments as $comment) : ?>
            <p><?= __('Date: ') ?><?= $comment['date'] ?></p>
            <p><?= $comment['message'] ?></p>
            <hr style="background-color: black; height: 1px;">
        <?php endforeach; ?>
        <h6><? __('Add a comment:') ?></h6>
        <form method="POST" class="form-group" action="">
            <label for="add_comment"></label>
            <textarea name="comment" id="comment" cols="5" rows="5" class="form-control"></textarea>
            <td><input type="submit" name="add_a_comment" class="btn btn-primary" value="<?= __('Add') ?>" /></td>
        </form>
    </div>
    
</body>

</html>