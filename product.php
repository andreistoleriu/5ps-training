<html lang="en">

<?php

require_once 'common.php';

if (!$_SESSION['authenticated']) {
    header('Location: login.php');
    die();
};

$title = $description = $price = '';
$titleErr = $descriptionErr = $imgErr = $priceErr = '';

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
                    $imgErr = 'Your file is too big!';
                }
            } else {
                $imgErr = 'There was an error uploading your file!';
            }
        } else {
            $imgErr = 'You cannot upload files of this type! Only jpg, jpeg, png and pdf extensions are allowed!';
        }
    }
};

if ($titleErr == '' && $descriptionErr == '' && $priceErr == '' && $imgErr == '') {
    if (isset($_POST['save'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $image = $_FILES['image']['name'];

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
        $image = $_FILES['image']['name'];

        $query = 'UPDATE products SET image = ?, title = ?, description = ?, price = ? WHERE products.id = ?';
        $stmt = $connection->prepare($query);
        $stmt->execute([$image, $title, $description, $price, $_GET['edit']]);
        header('Location: products.php');
        die();
    }
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('Login Page') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>
    <div class="container" style="max-width: 30%; margin-top: 100px">
        <form method="POST" class="form-group" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="<?= __('Title') ?>" class="form-control" value="<?= $title ?>"><br />
            <p class="text-danger"> <?= $titleErr; ?></p>
            <input type="text" name="description" placeholder="<?= __('Description') ?>" class="form-control" value="<?= $description ?>"><br />
            <p class="text-danger"> <?= $descriptionErr; ?></p>
            <input type="number" name="price" placeholder="<?= __('Price') ?>" class="form-control" value="<?= $price ?>"><br />
            <p class="text-danger"> <?= $priceErr; ?></p>
            <input type="file" name="image" class="form-control" value="<?= $image ?>"><br />
            <p class="text-danger"> <?= $imgErr; ?></p>
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