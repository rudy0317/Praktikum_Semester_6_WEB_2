<?php
$env = parse_ini_file('.env');
$conn = mysqli_connect(
    $env['DB_HOST'],
    $env['DB_USER'],
    $env['DB_PASS'],
    $env['DB_NAME'],
    $env['DB_PORT'],
);

if (!defined('BASE_URL')) {
    define('BASE_URL', '/praktikum%20web%20semester%206/');
}