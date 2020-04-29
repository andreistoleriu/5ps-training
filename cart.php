<?php

require_once 'common.php';
require_once 'config.php';

if (isset($_GET['delete'])) {
    foreach ($_SESSION['cart'] as $key => $value) {
        if ($value == $_GET['delete']) {
            unset($_SESSION['cart'][$key]);
        }
    }
    header('Location: cart.php');
    exit();
};


$query = 'SELECT * 
        FROM `products`
         WHERE `id` IN (' . implode(', ', $_SESSION['cart']) . ')';

$stmt = $connection->prepare($query);
$res = $stmt->execute($_SESSION['cart']);
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();

if (isset($_POST['checkout'])) {

    $name = $_POST['name'];
    $contactDetails = $_POST['contactDetails'];
    $comments = $_POST['comments'];

    $to = SHOPMANAGER;
    $subject = 'Order number #';
    $headers = 'From: example@gmail.com' . "\r\n" .
        'MIME-Version: 1.0' . "r\n" .
        'Content-Type: text/html; charset=utf-8';

    $message = "
        <html>
            <head>
                <title>" . __('Order number ####') . "</title>
            </head>
            <body>
                <p>" . __('Hello. A new order from ') . " " . ($name) . "</p>
                <p>" . __('Please find the order details below:') . "</p>
                <table border='1' cellpadding='2'>
            <tr>
                <th>" . __('Name') . " </th>
                <th>" . __('Description') . " </th>
                <th>" . __('Price') . " </th>
            </tr> ";

    foreach ($rows as $row) {
        $message .= " <tr>
                        <td>" . $row['title'] . "</td>
                        <td>" . $row['description'] . "</td>
                        <td>" . $row['price'] . "</td>
                    </tr> ";
    }
    $message .= " </table>
                <p> " . __('Contact details:') . " " . $contactDetails . "</p>
                <p> " . __('Comments:') . " " . $comments . "</p>
            </body>
        </html> ";


    if (mail($to, $subject, $message, $headers)) {
        header("refresh:5;url=cart.php");
        echo "<div class='p-3 mb-2 bg-primary text-white'>The email has been sent. Thank you" . " " . $name . "</div>";
    } else {
        header("refresh:5;url=cart.php");
        echo "<div class='p-3 mb-2 bg-warning text-dark'>Something went wrong!</div>";
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
                <tr>
                    <td><img src="<?= $row['image'] ?>" style="width: 200px" alt=""></td>
                    <td><?= $row['title'] ?></td>
                    <td><?= $row['description'] ?></td>
                    <td><?= $row['price'] ?></td>
                    <td><a href="?delete=<?= $row['id'] ?>"><?= __('Action') ?></a></td>
                </tr>
            <?php endforeach; ?>

        </table>

        <form class="form-group" method="POST" action="cart.php">
            <label for="name"><?= __('Name') ?></label>
            <input type="text" name="name" value="" placeholder="<?= __('Insert your name') ?>" class="form-control" value="<?php echo $name ?>"> <br />
            <label for="contactDetails"><?= __('Contact details') ?></label>
            <textarea rows="2" cols="30" name="contactDetails" value="" placeholder="<?= __('Insert your contact details') ?>" class="form-control" value="<?php echo $contactDetails ?>"></textarea> <br />
            <label for="comments"><?= __('Comments') ?></label>
            <textarea rows="4" cols="30" name="comments" value="" placeholder="<?= __('Insert comments') ?>" class="form-control" value="<?php echo $comments ?>"></textarea> <br />
            <input type="submit" class="btn btn-primary" name="checkout" value="<?= __('Checkout') ?>"></button>
        </form>

        <a href="index.php"><?= __('Go to index') ?></a>

        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </div>
</body>

</html>