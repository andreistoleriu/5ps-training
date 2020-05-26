<?php

require_once 'common.php';

$name = $password = '';
$errors = [];

if (isset($_POST['login'])) {
    $name = strip_tags($_POST['username']);
    $password = strip_tags($_POST['password']);

    if (!strlen($name)) {
        $errors['username'][] = __('Please insert a username');
    } elseif ($name !== USER_ADMIN) {
        $errors['username'][] = __('Incorrect username');
    }

    if (!strlen($password)) {
        $errors['password'][] = __('Please insert a password');
    } elseif ($password !== PASS_ADMIN) {
        $errors['password'][] = __('Wrong password');
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
            <label for="username"><?= __('Username:') ?></label>
            <input type="text" name="username" placeholder="<?= __('Insert username') ?>" class="form-control" value="<?= $name ?>">
            <?php $errorKey = 'username' ?>
            <?php include 'errors.php' ?>
            <label for="password"><?= __('Password:') ?></label>
            <input type="password" name="password" placeholder="<?= __('Insert password') ?>" class="form-control" value="<?= $password ?>">
            <?php $errorKey = 'password' ?>
            <?php include 'errors.php' ?>
            <input type="submit" class="btn btn-primary" name="login" value="<?= __('Login') ?>"></button>
        </form>
    </div>
</body>

</html>