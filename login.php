<?php

require_once 'common.php';

$name = $password = '';
$errors = [];

if (isset($_POST['login'])) {
    if ($_POST['username'] === USER_ADMIN) {
        $name = USER_ADMIN;
    } elseif (empty($_POST['username'])) {
        $errors['username'][] = __('Admin username required');
    } elseif ($_POST['username'] != USER_ADMIN) {
        $errors['username'][] = __('Invalid username');
    }

    if ($_POST['password'] === PASS_ADMIN) {
        $password = $_POST['password'];
    } elseif (empty($_POST['password'])) {
        $errors['password'][] = __('Admin password required');
    } elseif ($_POST['password'] != PASS_ADMIN) {
        $errors['password'][] = __('Invalid password');
    }
    if (!$errors) {
        $_SESSION['authenticated'] = true;
        header('Location: products.php');
        die();
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
    <div class="container" style="max-width: 30vw; margin-top: 50px;">
        <form class="form-group" method="POST">
            <label for="username"><?= sanitize(__('Username:')) ?></label>
            <input type="text" name="username" placeholder="<?= sanitize(__('Insert username')) ?>" class="form-control" value="<?= sanitize($name) ?>">
            <?php $errorKey = 'username' ?>
            <?php include 'errors.php' ?>
            <label for="password"><?= sanitize(__('Password:')) ?></label>
            <input type="password" name="password" placeholder="<?= sanitize(__('Insert password')) ?>" class="form-control" value="<?= sanitize($password) ?>">
            <?php $errorKey = 'password' ?>
            <?php include 'errors.php' ?>
            <input type="submit" class="btn btn-primary" name="login" value="<?= sanitize(__('Login')) ?>"></button>
        </form>
    </div>
</body>

</html>