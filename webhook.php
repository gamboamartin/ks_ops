<?php
require 'vendor/autoload.php';
error_reporting(0);
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Pragma: no-cache');

$data = json_decode(file_get_contents('php://input'), true);
if (empty($data)) {
    die('No hay datos');
}

$message = $data['message'];

if (empty($message)) {
    die('No hay mensaje');
}

$chat_id = $message['from']['id'];

$mensaje = 'Hola ' . $message['from']['first_name'] . ' ' . $message['from']['last_name'] . ', bienvenido al bot de prueba.';

$respuesta = (new \gamboamartin\plugins\telegram_api())->enviar_mensaje(bot_token: '7427554923:AAFu9G4vZ5Jcj-SvWL898sp9iJOxxModKAw', chat_id: $chat_id, mensaje: $mensaje);
