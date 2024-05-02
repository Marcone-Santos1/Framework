<?php

require_once __DIR__ . "/../../vendor/autoload.php";


function dd(mixed $value, mixed ...$values): void
{
    var_dump($value, ...$values);
    die();
}

