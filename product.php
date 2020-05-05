<html lang="en">

<?php

require_once 'common.php';

if (!$_SESSION['authenticated']) {
    header('Location: login.php');
    die();
};

$title = $description = '';
$price = NULL;
$titleErr = $descriptionErr = '';
$priceErr = NULL;

if (isset($_GET['edit'])) {

    $query = 'SELECT * FROM products WHERE id= ?';
    $stmt = $connection->prepare($query);
    $res = $stmt->execute([$_GET['edit']]);
    $rows = $stmt->fetch();

    $title = $rows['title'];
    $description = $rows['description'];
    $price = $rows['price'];
    $image = $rows['image'];
}

if (isset($_POST['save']) || isset($_POST['edit'])) {

    if (!strlen($_POST['title'])) {

        $titleErr = __('Title is required');
    } else {
        $title = $_POST['title'];
    }
    if (!strlen($_POST['description'])) {

        $descriptionErr = __('Description is required');
    } else {
        $description = $_POST['description'];
    }
    if ($_POST['price'] <= 0) {
        $priceErr = __('Price cannot be negative');
    } else {
        $price = $_POST['price'];
    }
}

function pre_r($array)
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}
if ($titleErr == '' && $descriptionErr == '' && $priceErr == '') {
    if (isset($_POST['save'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $image = $_POST['image'];

        $query = 'INSERT INTO products(image, title, description, price) VALUES (?, ?, ?, ?)';
        $stmt = $connection->prepare($query);
        $stmt->execute([$image, $title, $description, $price]);
        header('Location: products.php');
        die();
    }
    if (isset($_POST['edit'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $image = $_POST['image'];

        $query = 'UPDATE products SET image = ?, title = ?, description = ?, price = ? WHERE products.id = ?';
        $stmt = $connection->prepare($query);
        $stmt->execute([$image, $title, $description, $price, $_GET['edit']]);
        header('Location: products.php');
        die();
    }
};

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('Login Page') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>
    <div class="container" style="max-width: 30%; margin-top: 100px">

        <form method="POST" class="form-group">
            <input type="text" name="title" placeholder="<?= __('Title') ?>" class="form-control" value="<?= $title ?>"><br />
            <p class="text-danger"> <?= $titleErr; ?></p>
            <input type="text" name="description" placeholder="<?= __('Description') ?>" class="form-control" value="<?= $description ?>"><br />
            <p class="text-danger"> <?= $descriptionErr; ?></p>
            <input type="text" name="price" placeholder="<?= __('Price') ?>" class="form-control" value="<?= $price ?>"><br />
            <p class="text-danger"> <?= $priceErr; ?></p>
            <input type="file" name="image" placeholder="<?= __('Image') ?>" class="form-control" value="<?= $image ?>"><br />
            <?php if (isset($_GET['edit'])) : ?>
                <input type="submit" class="btn btn-primary" name="edit" value="<?= __('Update') ?>"></button>
            <?php else : ?>
                <input type="submit" class="btn btn-primary" name="save" value="<?= __('Save') ?>"></button>
            <?php endif; ?>
            <span><a href="products.php" class="btn btn-warning">Products</a></span>
        </form>
    </div>
</body>

</html>