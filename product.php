<?php

require_once 'common.php';
require_once 'auth.php';

$title = $description = $price = '';
$errors = [];

if (isset($_GET['edit'])) {
    $query = 'SELECT * FROM products WHERE id = ?';
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
        $errors['title'][] = __('Title is required');
    } else {
        $title = strip_tags($_POST['title']);
    }
    if (!strlen($_POST['description'])) {
        $errors['description'][] = __('Description is required');
    } else {
        $description = strip_tags($_POST['description']);
    }
    if ($_POST['price'] <= 0) {
        $errors['price'][] = __('Price cannot be negative');
    } else {
        $price = strip_tags($_POST['price']);
    }
    if (!$_FILES['image']['error']) {
        $file = $_FILES['image'];
        $fileName = $_FILES['image']['name'];
        $fileTmpName = $_FILES['image']['tmp_name'];
        $fileSize = $_FILES['image']['size'];
        $fileError = $_FILES['image']['error'];
        $fileType = $_FILES['image']['type'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
        if (in_array($fileActualExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 500000) {
                    $fileNameNew = uniqid('', true) . '.' . $fileActualExt;
                    $fileDestination = 'img/' . $fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);
                } else {
                    $errors['image'][] = 'Your file is too big!';
                }
            } else {
                $errors['image'][] = 'There was an error uploading your file!';
            }
        } else {
            $errors['image'][] = 'You cannot upload files of this type! Only jpg, jpeg, png and pdf extensions are allowed!';
        }
    }
    if (!$errors) {
        if (isset($_POST['save'])) {
            $title = strip_tags($_POST['title']);
            $description = strip_tags($_POST['description']);
            $price = strip_tags($_POST['price']);
            $image = $_FILES['image']['name'];

            $query = 'INSERT INTO products(image, title, description, price) VALUES (?, ?, ?, ?)';
            $stmt = $connection->prepare($query);
            $stmt->execute([$image, $title, $description, $price]);
            header('Location: products.php');
            die();
        }

        if (isset($_POST['edit'])) {
            $query = 'UPDATE products SET image = ?, title = ?, description = ?, price = ? WHERE products.id = ?';
            $stmt = $connection->prepare($query);
            $stmt->execute([$image, $title, $description, $price, $_GET['edit']]);
            header('Location: product.php?success=1');
            die();
        }
    }
}

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('Login Page') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>
    <div class="container" style="max-width: 30%; margin-top: 100px">
        <form method="POST" class="form-group" enctype="multipart/form-data">
            <?php if (isset($_GET['success'])) : ?>
                <p class="text-primary"><?= __('Product updated') ?></p>
            <?php endif ?>
            <input type="text" name="title" placeholder="<?= __('Title') ?>" class="form-control" value="<?= $title ?>"><br />
            <?php $errorKey = 'title' ?>
            <?php include 'errors.php' ?>
            <input type="text" name="description" placeholder="<?= __('Description') ?>" class="form-control" value="<?= $description ?>"><br />
            <?php $errorKey = 'description' ?>
            <?php include 'errors.php' ?>
            <input type="number" name="price" placeholder="<?= __('Price') ?>" class="form-control" value="<?= $price ?>"><br />
            <?php $errorKey = 'price' ?>
            <?php include 'errors.php' ?>
            <input type="file" name="image" class="form-control" value="<?= $image ?>"><br />
            <?php $errorKey = 'image' ?>
            <?php include 'errors.php' ?>
            <?php if (isset($_GET['edit'])) : ?>
                <input type="submit" class="btn btn-primary" name="edit" value="<?= __('Update') ?>"></button>
            <?php else : ?>
                <input type="submit" class="btn btn-primary" name="save" value="<?= __('Save') ?>"></button>
            <?php endif; ?>
            <span><a href="products.php" class="btn btn-warning"><?= __('Products') ?></a></span>
        </form>
    </div>
</body>

</html>