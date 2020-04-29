<?php

session_start();

require_once 'config.php';
require_once 'common.php';

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
    $headers = 'From: orders@example.com' . "\r\n" .
        'MIME-Version: 1.0' . "r\n" .
        'Content-Type: text/html; charset=utf-8';

    $message = "
        <html>
            <head>
                <title>Order</title>
            </head>
            <body>
                <p>Hello " . $name . "</p>
                <p>You can find the order details below: </p>
                <table border='1'>
            <tr>
                <th> Name </th>
                <th> Description </th>
                <th> Price </th>
            </tr> ";

    foreach ($rows as $row) {
        $message .= " <tr>
                        <td>" . $row['title'] . "</td>
                        <td>" . $row['description'] . "</td>
                        <td>" . $row['price'] . "</td>
                    </tr> ";
    }
    $message .= " </table>
                <p> Contact details: " . $contactDetails . "</p>
                <p> Comments: " . $comments . "</p>
            </body>
        </html> ";


    if (mail($to, $subject, $message, $headers)) {
        echo "<h1>The email has been sent. Thank you" . " " . $name . "</h1>";
    } else {
        echo "Something went wrong!";
    }
}
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
                    <th></th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <?php foreach ($rows as $row) : ?>
                <tr>
                    <td><img src="<?php echo $row['image'] ?>" style="width: 200px" alt=""></td>
                    <td><?= $row['title'] ?></td>
                    <td><?= $row['description'] ?></td>
                    <td><?= $row['price'] ?></td>
                    <td><a href="?delete=<?= $row['id'] ?>">Remove</a></td>
                </tr>
            <?php endforeach; ?>

        </table>

        <form class="form-group" method="POST" action="cart.php">
            <label for="name">Name</label>
            <input type="text" name="name" value="<?php $name ?>" placeholder="Insert your name" class="form-control"> <br />
            <label for="contactDetails">Contact details</label>
            <textarea rows="2" cols="30" name="contactDetails" value="<?php $contactDetails ?>" placeholder="Insert your contact details" class="form-control"></textarea> <br />
            <label for="comments">Comments</label>
            <textarea rows="4" cols="30" name="comments" value="<?php $comments ?>" placeholder="Insert your comments" class="form-control"></textarea> <br />
            <input type="submit" class="btn btn-primary" name="checkout" value="Checkout"></button>
        </form>

        <a href="index.php">Go to index</a>

        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </div>
</body>

</html>