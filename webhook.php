<?php
require 'vendor/autoload.php';

use base\conexion;
use base\controller\init;
use gamboamartin\errores\errores;

error_reporting(0);
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Pragma: no-cache');

$con = new conexion();
$link = conexion::$link;
$link->beginTransaction();

$data = json_decode(file_get_contents('php://input'), true);
if (empty($data)) {
    die('No hay datos');
}

$message = $data['message'];

if (empty($message)) {
    die('No hay mensaje');
}

$chat_id = $message['from']['id'];
$text = $message['text'];

$existe = (new \gamboamartin\administrador\models\adm_usuario($link))->filtro_and(filtro: ['adm_usuario.id_chat_telegram' => $chat_id]);
if (errores::$error) {
    $mensaje = 'Error al buscar el chat de Telegram. Por favor, intenta de nuevo.';
    (new \gamboamartin\plugins\telegram_api())->enviar_mensaje(
        bot_token: '7427554923:AAFu9G4vZ5Jcj-SvWL898sp9iJOxxModKAw',
        chat_id: $chat_id,
        mensaje: $mensaje
    );
    exit();
}

if ($existe->n_registros > 0) {
    exit();
}

$file_path = 'sessions.json';

$sessions = file_exists($file_path) ? json_decode(file_get_contents($file_path), true) : [];

if (!isset($sessions[$chat_id])) {
    $sessions[$chat_id] = ['estado' => 'inicio'];
    file_put_contents($file_path, json_encode($sessions, JSON_PRETTY_PRINT));
}

if ($sessions[$chat_id]['estado'] === 'inicio' && strtolower($text) === '/start') {
    $sessions[$chat_id]['estado'] = 'espera_usuario';
    $mensaje = '¡Bienvenido! Por favor, ingresa tu usuario:';
    (new \gamboamartin\plugins\telegram_api())->enviar_mensaje(
        bot_token: '7427554923:AAFu9G4vZ5Jcj-SvWL898sp9iJOxxModKAw',
        chat_id: $chat_id,
        mensaje: $mensaje
    );
} else if ($sessions[$chat_id]['estado'] === 'espera_usuario') {
    $sessions[$chat_id]['estado'] = 'espera_contrasena';
    $sessions[$chat_id]['usuario'] = $text;
    $mensaje = 'Usuario ingresado. Por favor, ingresa tu contraseña:';
    (new \gamboamartin\plugins\telegram_api())->enviar_mensaje(
        bot_token: '7427554923:AAFu9G4vZ5Jcj-SvWL898sp9iJOxxModKAw',
        chat_id: $chat_id,
        mensaje: $mensaje
    );
} else if ($sessions[$chat_id]['estado'] === 'espera_contrasena') {
    $filtro['adm_usuario.user'] = $sessions[$chat_id]['usuario'];
    $filtro['adm_usuario.password'] = $text;
    $usuario = (new \gamboamartin\administrador\models\adm_usuario($link))->filtro_and(filtro: $filtro);
    if (errores::$error) {
        $mensaje = 'Error al validar credenciales. Por favor, intenta de nuevo.';
        (new \gamboamartin\plugins\telegram_api())->enviar_mensaje(
            bot_token: '7427554923:AAFu9G4vZ5Jcj-SvWL898sp9iJOxxModKAw',
            chat_id: $chat_id,
            mensaje: $mensaje
        );
        exit();
    }

    if ($usuario->n_registros == 0) {
        $sessions[$chat_id]['estado'] = 'espera_usuario';
        $mensaje = 'No se encontraron registros con las credenciales ingresadas. Por favor, ingresa tu usuario nuevamente:';
    } else {
        session_start();

        $_SESSION['activa'] = 1;
        $_SESSION['grupo_id'] = $usuario->registros[0]['adm_grupo_id'];
        $_SESSION['usuario_id'] = $usuario->registros[0]['adm_usuario_id'];
        $_SESSION['nombre_usuario'] = $usuario->registros[0]['adm_usuario_nombre_completo'];
        $_SESSION['adm_grupo_root'] = $usuario->registros[0]['adm_grupo_root'];

        $data_get = (new init())->asigna_session_get();
        if(errores::$error){
            if ($link->inTransaction()) {
                $link->rollBack();
            }
            $error = (new errores())->error(mensaje: 'Error al actualizar usuario', data: $data_get);
            print_r($error);
            exit;
        }

        $sessions[$chat_id]['estado'] = 'registrado';
        $actualizar_usuario['id_chat_telegram'] = $chat_id;

        $adm_usuario = new \gamboamartin\administrador\models\adm_usuario($link);
        $adm_usuario->usuario_id = $usuario->registros[0]['adm_usuario_id'];
        $proceso = $adm_usuario->modifica_bd(
            registro: $actualizar_usuario,
            id: $usuario->registros[0]['adm_usuario_id']
        );

        if (errores::$error) {
            $mensaje = 'Error al actualizar el ID del chat de Telegram. Por favor, intenta de nuevo.';
        } else {
            $mensaje = '¡Registro completado exitosamente!';
        }
    }

    (new \gamboamartin\plugins\telegram_api())->enviar_mensaje(
        bot_token: '7427554923:AAFu9G4vZ5Jcj-SvWL898sp9iJOxxModKAw',
        chat_id: $chat_id,
        mensaje: $mensaje
    );
}

file_put_contents($file_path, json_encode($sessions, JSON_PRETTY_PRINT));


