<?php

session_start();

require_once 'config.php';

$connection = new PDO(HOST, USERNAME, PASSWORD);

function __($input)
{
    $translations = [];

    return isset($translations[$input]) ? $translations[$input] : $input;
}
