<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */

namespace gamboamartin\ks_ops\controllers;

use base\controller\init;
use config\generales;
use config\google;
use gamboamartin\administrador\models\adm_calendario;
use gamboamartin\administrador\models\adm_tipo_evento;
use gamboamartin\comercial\models\com_prospecto_etapa;
use gamboamartin\errores\errores;
use gamboamartin\plugins\google_calendar_api;
use gamboamartin\template\html;
use PDO;
use stdClass;
use Throwable;

final class controlador_com_prospecto extends \gamboamartin\comercial\controllers\controlador_com_prospecto
{

    public function init_selects_inputs(): array
    {
        $keys_selects = parent::init_selects_inputs();
        $keys_selects['com_tipo_prospecto_id']->cols = 12;
        $keys_selects['com_agente_id']->cols = 12;

        $keys_selects = (new init())->key_select_txt(cols: 12, key: 'nombre',
            keys_selects: $keys_selects, place_holder: 'Nombre');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'apellido_paterno',
            keys_selects: $keys_selects, place_holder: 'Apellido Paterno');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 6, key: 'apellido_materno',
            keys_selects: $keys_selects, place_holder: 'Apellido Materno');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 4, key: 'telefono',
            keys_selects: $keys_selects, place_holder: 'Tel');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 8, key: 'correo',
            keys_selects: $keys_selects, place_holder: 'Correo');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects = (new init())->key_select_txt(cols: 12, key: 'razon_social',
            keys_selects: $keys_selects, place_holder: 'Razón Social');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        return $keys_selects;

    }

    public function etapa_bd(bool $header, bool $ws = false): array|stdClass
    {
        if (isset($_POST['generar_evento']) || isset($_SESSION['calendario']['datos']['generar_evento'])) {
            if (!isset($_SESSION['calendario']['code'])) {

                $link_alta_etapa = $this->obj_link->link_con_id(
                    accion: 'etapa_bd', link: $this->link, registro_id: $this->registro_id, seccion: $this->tabla);
                if (errores::$error) {
                    $this->retorno_error(mensaje: 'Error al generar link', data: $link_alta_etapa, header: $header, ws: $ws);
                }

                $link_redirect = (new generales())->url_base . 'vendor/gamboa.martin/acl/google_calendar_redirect.php';
                $link_alta = str_replace('./', (new generales())->url_base, $link_alta_etapa);

                $google_oauth_url = (new google_calendar_api())->get_oauth_url(google_client_id: google::GOOGLE_CLIENT_ID,
                    google_redirect_uri: $link_redirect);

                $_SESSION['calendario'] = [
                    'link_google_calendar_redirect' => $link_redirect,
                    'link_proceso' => $link_alta,
                    'datos' => $_POST
                ];

                header("Location: $google_oauth_url");
                exit();
            }

            $calendario = $this->crear_calendario_google();
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al crear calendario en google', data: $calendario);
            }
        }

        $this->link->beginTransaction();

        $com_prospecto_etapa_ins['com_prospecto_id'] = $this->registro_id;
        $com_prospecto_etapa_ins['pr_etapa_proceso_id'] = $_POST['pr_etapa_proceso_id'];
        $com_prospecto_etapa_ins['fecha'] = $_POST['fecha'];
        $com_prospecto_etapa_ins['observaciones'] = $_POST['observaciones'];

        $r_alta_com_prospecto_etapa = (new com_prospecto_etapa(link: $this->link))->alta_registro(registro: $com_prospecto_etapa_ins);
        if (errores::$error) {
            $this->link->rollBack();
            $this->retorno_error(mensaje: 'Error al insertar com_prospecto_etapa', data: $r_alta_com_prospecto_etapa, header: $header, ws: $ws);
        }
        $this->link->commit();

        if ($header) {

            $this->retorno_base(registro_id: $this->registro_id, result: $r_alta_com_prospecto_etapa, siguiente_view: 'etapa',
                ws: $ws, seccion_retorno: $this->seccion, valida_permiso: true);
        }
        if ($ws) {
            header('Content-Type: application/json');
            try {
                echo json_encode($r_alta_com_prospecto_etapa, JSON_THROW_ON_ERROR);
            } catch (Throwable $e) {
                $error = (new errores())->error(mensaje: 'Error al maquetar JSON', data: $e);
                print_r($error);
            }
            exit;
        }


        return $r_alta_com_prospecto_etapa;
    }

    public function crear_calendario_google(): array
    {
        $datos = $_SESSION['calendario']['datos'];

        $tipo_evento = (new adm_tipo_evento(link: $this->link))->registro(registro_id: $datos['adm_tipo_evento_id']);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al obtener tipo de evento', data: $tipo_evento);
        }

        $token = (new google_calendar_api())->get_access_token(client_id: google::GOOGLE_CLIENT_ID,
            redirect_uri: $_SESSION['calendario']['link_google_calendar_redirect'], client_secret: google::GOOGLE_CLIENT_SECRET,
            code: $_SESSION['calendario']['code'], ssl_verify: google::GOOGLE_SSL_VERIFY);

        $timeZone = (new google_calendar_api())->get_calendar_timezone(access_token: $token['access_token'], ssl_verify: google::GOOGLE_SSL_VERIFY);

        $summary = $tipo_evento['adm_tipo_evento_descripcion'];
        $description = "Calendario para eventos de tipo: $summary";

        $calendario = (new google_calendar_api())->crear_calendario(access_token: $token['access_token'],
            summary: $summary, description: $description, timeZone: $timeZone, ssl_verify: google::GOOGLE_SSL_VERIFY);

        $datos['titulo'] = $summary;
        $datos['descripcion'] = $description;
        $datos['calendario_id'] = $calendario['id'];
        $datos['zona_horaria'] = $calendario['timeZone'];

        $alta_calendario = $this->alta_calendario(registros: $datos);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al dar de alta calendario', data: $alta_calendario);
        }

        $_SESSION['calendario']['datos']['fecha_inicio'] = $datos['fecha'];
        $_SESSION['calendario']['datos']['fecha_fin'] = $datos['fecha'];

        $start_datetime['dateTime'] = (new \DateTime($datos['fecha']))->format(\DateTime::ATOM);
        $start_datetime['timeZone'] = $timeZone;
        $end_datetime['dateTime'] = (new \DateTime($datos['fecha']))->format(\DateTime::ATOM);
        $end_datetime['timeZone'] = $timeZone;
        $location = '';

        $evento = (new google_calendar_api())->crear_evento_calendario(access_token: $token['access_token'],
            calendar_id: $calendario['id'], summary: $summary, description: $description, location: $location,
            start_datetime: $start_datetime, end_datetime: $end_datetime,
            timeZone: $timeZone, ssl_verify: google::GOOGLE_SSL_VERIFY);

        $datos_calendario['titulo'] = $summary;
        $datos_calendario['descripcion'] = $description;
        $datos_calendario['adm_calendario_id'] = $alta_calendario->registro_id;
        $datos_calendario['evento_id'] = $evento['id'];
        $datos_calendario['zona_horaria'] = $calendario['timeZone'];

        $alta_calendario = $this->alta_evento_calendario(registros: $datos_calendario);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al dar de alta evento calendario', data: $alta_calendario);
        }

        $_POST = $_SESSION['calendario']['datos'];
        unset($_SESSION['calendario']);

        return $calendario;
    }

    public function alta_calendario(array $registros)
    {
        $calendario = new adm_calendario(link: $this->link);
        $calendario->registro = $registros;
        $alta = $calendario->alta_bd();
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al dar de alta calendario', data: $alta);
        }

        return $alta;
    }

    public function alta_evento_calendario(array $registros)
    {
        $calendario = new adm_calendario(link: $this->link);
        $calendario->registro = $registros;
        $alta = $calendario->alta_bd();
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al dar de alta calendario', data: $alta);
        }

        return $alta;
    }
}
