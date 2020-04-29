<?php

session_start();

require_once 'config.php';

$connection = new PDO("mysql:host=" . HOST . ";dbname=" . DBNAME . "", USERNAME, PASSWORD);


function __($input)
{
    $translations = [];

    return isset($translations[$input]) ? $translations[$input] : $input;
};
