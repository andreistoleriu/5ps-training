<?php

require_once 'common.php';
require_once 'auth.php';

$commEdit = '';

$commQuery = 'SELECT
                products.*,
                comments.cid,
                comments.product_id,
                comments.message,
                comments.date
                FROM products
                JOIN comments 
                ON comments.product_id = products.id
                ORDER BY comments.date';

$commStmt = $connection->prepare($commQuery);
$commStmt->execute();
$comments = $commStmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['delete'])) {
    $deleteQuery = 'DELETE FROM comments WHERE cid = ?';
    $stmt = $connection->prepare($deleteQuery);
    $stmt->execute([$_POST['cid']]);

    header('Location: comments.php');
    die();
}

if (isset($_GET['edit'])) {
    $query = 'SELECT * FROM comments WHERE cid = ?';
    $stmt = $connection->prepare($query);
    $res = $stmt->execute([$_GET['edit']]);
    $row = $stmt->fetch();

    if (!$row) {
        $errors['editComment'][] = __('Comment no longer available in the database!');
    } else {
        $commEdit = $row['message'];
    }
}

if (isset($_POST['edit_comm'])) {
    $editComment = $_POST['commEdit'];
    $query = 'UPDATE comments SET message = ? WHERE cid = ?';
    $stmt = $connection->prepare($query);
    $stmt->execute([$editComment, $_GET['id']]);
    header('Location: comments.php');
    die();
}

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title><?= ('Comments page') ?></title>
</head>

<body>
    <div class="container">
        <?php if (isset($errors['editComment'])) : ?>
            <?php $errorKey = 'editComment' ?>
            <?php include 'errors.php' ?>
        <?php else : ?>
            <h5 style="margin-top: 15px"><?= __('Comments:') ?> </h5>
            <hr style="background-color: black; height: 1px;">
            <?php foreach ($comments as $comment) : ?>
                <form method="post" action="comments.php?id=<?= $comment['cid']; ?>" class="form-group">
                    <td><input type="hidden" name="cid" value="<?= $comment['cid'] ?>" /></td>
                    <img src="img/<?= $comment['image'] ?>" style="width:100px" alt="">
                    <h5><?= $comment['title'] ?></h5>
                    <p><?= __('Date:') ?> <?= $comment['date'] ?></p>
                    <p><?= __('Message: ') ?><?= $comment['message'] ?>
                    <a href="comments.php?edit=<?= $comment['cid']; ?>" class="btn btn-warning"><?= __('Edit') ?></a>
                    <input type="submit" name="delete" class="btn btn-danger" value="<?= __('Delete') ?>" />
                    <hr style="background-color: black; height: 1px;">
            <?php endforeach; ?>
                    <?php if(isset($_GET['edit'])) : ?>
                        <input style="margin: 10px 0" type="text" name="commEdit" placeholder="<?= __('Edit a comment') ?>"
                            class="form-control" value="<?= $commEdit ?>">
                        <input type="submit" name="edit_comm" class="btn btn-warning" value="<?= __('Edit comment')  ?>" />
                    <?php endif; ?>
                </form>
        <?php endif; ?>
    </div>
</body>

</html>