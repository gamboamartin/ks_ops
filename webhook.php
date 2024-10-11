<?php
error_reporting(0);
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Pragma: no-cache');

$data = json_decode(file_get_contents('php://input'), true);
if (empty($data)) {
    die('No hay datos');
}

print_r($data);

