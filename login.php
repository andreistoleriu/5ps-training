<?php
require_once 'common.php';

$name = $password = "";
$nameErr = $passwordErr = "";

if (isset($_POST['login'])) {

    if ($_POST['username'] === USER_ADMIN) {

        $name = USER_ADMIN;
    } elseif (empty($_POST['username'])) {

        $nameErr = __("Admin username required");
    } elseif ($_POST['username'] != USER_ADMIN) {

        $nameErr = __('Invalid username');
    }

    if ($_POST['password'] === PASS_ADMIN) {

        $password = PASS_ADMIN;
    } elseif (empty($_POST['password'])) {

        $passwordErr = __("Admin password required");
    } elseif ($_POST['password'] != PASS_ADMIN) {

        $passwordErr = __('Invalid password');
    }
}

if (isset($_POST['login'])) {

    if ($_POST['username'] === USER_ADMIN and $_POST['password'] === PASS_ADMIN) {

        $_SESSION['authenticated'] = true;
        header('Location: products.php');
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
    <div class="container" style="max-width: 30vw; margin-top: 50px">
        <form class="form-group" method="POST">
            <label for="username"><?= __('Username:') ?></label>
            <input type="text" name="username" value="" placeholder="<?= __('Insert username') ?>" class="form-control" value="">
            <p class="text-danger"> <?= $nameErr; ?></p>
            <label for="password"><?= __('Password:') ?></label>
            <input type="password" name="password" value="" placeholder="<?= __('Insert password') ?>" class="form-control" value="">
            <p class="text-danger"> <?= $passwordErr; ?></p>
            <input type="submit" class="btn btn-primary" name="login" value="<?= __('Login') ?>"></button>

        </form>
</body>
</div>

</html>