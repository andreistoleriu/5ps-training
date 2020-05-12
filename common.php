<?php

session_start();

require_once 'config.php';

$connection = new PDO(HOST, USERNAME, PASSWORD);

$implode = implode(',', $_SESSION['cart']);

function __($input)
{
    $translations = [];

    return isset($translations[$input]) ? $translations[$input] : $input;
};

function sanitize($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}