<?php
require_once 'config.php';

$connection = new PDO("mysql:host=" . HOST . ";dbname=" . DBNAME . "", USERNAME, PASSWORD);

function pre_r($array)
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}
