<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('crear_json_datos_generales')) {

    function crear_json_datos_generales($data) {
        $CI = & get_instance();

        $CI->load->model('procesos_model');
        unset($data['created_at']);
        $data['procesos'] = $CI->procesos_model->get_all_by_instituto_id($data['id']);
        if (is_array($data['procesos'])) {
            if (count($data['procesos']) > 0) {
                $CI->load->model('institutos_contenidos_model');
                $CI->load->helper('proceso_helper');
                foreach ($data['procesos'] as &$proceso) {
                    $aux_proceso_fecha = explode('-', $proceso['fecha']);
                    $proceso['anio'] = $aux_proceso_fecha[0];
                    $proceso['mes'] = getMes($aux_proceso_fecha[1]);
                    $proceso['carpeta'] = strtolower(substr(getMes($aux_proceso_fecha[1]), 0, 3));
                    $docs = $CI->institutos_contenidos_model->get_docs_by_proceso($proceso['id']);
                    $des = $CI->institutos_contenidos_model->get_designaciones_by_proceso($proceso['id']);
                    $per = $CI->institutos_contenidos_model->get_pefiles_by_proceso($proceso['id']);
                    $proceso['documentos'] = ($docs['docs'] !== null) ? ($docs['docs'] > 0) ? 1 : 0 : 0;
                    $proceso['designaciones'] = ($des['des'] !== null) ? ($des['des'] > 0) ? 1 : 0 : 0;
                    $proceso['perfiles'] = ($per['perf'] !== null) ? ($per['perf'] > 0) ? 1 : 0 : 0;
                }
            }
        }

        $name_json = 'datos_generales.json';
        $ruta = $CI->config->item('files_url') . $data['siglas'] . '/';
        if (file_exists($ruta . $name_json)) {
            unlink($ruta . $name_json);
        }
        $file = fopen($ruta . $name_json, 'a+')or die("error al crear archivo " . $name_json);
        fwrite($file, json_encode($data));
        fclose($file);
    }

}
if (!function_exists('get_all_institutos_json')) {

    function get_all_institutos_json() {
        $CI = & get_instance();

        $CI->load->model('institutos_model');
        $CI->load->model('procesos_model');
        $CI->load->helper('proceso_helper');

        $institutos = $CI->institutos_model->get_all_institutos_json();

        $array_institutos = Array();
        if (count($institutos) > 0) {
            foreach ($institutos as &$instituto) {
                $procesos_aux = [];
                $procesos = $CI->procesos_model->get_by_instituto_id($instituto['id']);
                if (count($procesos) > 0) {
                    $procesos_aux = [];
                    foreach ($procesos as $proceso) {
                        $aux_fecha = explode('-', $proceso['fecha']);
                        $procesos_aux[] = ['proceso' => $aux_fecha[0] . '-' . strtoupper(substr(getMes($aux_fecha[1]), 0, 3))];
                    }
                }
                $instituto['procesos'] = $procesos_aux;
            }

            $ultimos_institutos = $CI->institutos_model->get_last_institutos_json();
            foreach ($ultimos_institutos as $ins) {
                $instituto_data = $CI->institutos_model->get_instituto_by_id_json($ins['id']);

                $proceso = $CI->procesos_model->get_last_activo_by_instituto_id($ins['id']);

                if ($proceso != NULL) {
                    $aux_fecha = explode('-', $proceso['fecha']);
                    $instituto_data['proceso'] = $aux_fecha[0] . '-' . strtoupper(substr(getMes($aux_fecha[1]), 0, 3));
                } else {
                    $instituto_data['proceso'] = "";
                }
                $docs = $CI->institutos_model->get_contenidos_by_instituto_json($ins['id'], 1);
                $des = $CI->institutos_model->get_contenidos_by_instituto_json($ins['id'], 2);
                $per = $CI->institutos_model->get_contenidos_by_instituto_json($ins['id'], 3);
                $instituto_data['en_proceso'] = ($instituto_data['en_proceso'] > 0) ? 1 : 0;
                $instituto_data['documentos'] = ($docs['contents'] !== null) ? ($docs['contents'] > 0) ? 1 : 0 : 0;
                $instituto_data['designacion'] = ($des['contents'] !== null) ? ($des['contents'] > 0) ? 1 : 0 : 0;
                $instituto_data['perfiles'] = ($per['contents'] !== null) ? ($per['contents'] > 0) ? 1 : 0 : 0;
                $array_institutos[] = $instituto_data;
            }
            $archivos = ['all_instituciones.json', 'principal_instituciones.json'];
            $ruta = $CI->config->item('files_url');
            foreach ($archivos as $i => $archivo) {
                if (file_exists($ruta . $archivo)) {
                    unlink($ruta . $archivo);
                }
                $file = fopen($ruta . $archivo, 'a+')or die("error al crear archivo " . $archivo);
                if ($i === 0) {
                    fwrite($file, json_encode($institutos));
                } else {
                    fwrite($file, json_encode($array_institutos));
                }
                fclose($file);
            }
        }
    }

}
if (!function_exists('crear_json_contenidos')) {

    function crear_json_contenidos() {
        $CI = & get_instance();

        $CI->load->model('procesos_model');
        $CI->load->model('institutos_model');
        $CI->load->model('institutos_contenidos_model');
        $CI->load->helper('proceso_helper');

        $procesos = $CI->procesos_model->get();
        $names_json = ['lista_designaciones.json', 'lista_documentos.json', 'lista_perfiles.json'];

        foreach ($procesos as &$proceso) {
            $instituto = $CI->institutos_model->get_by_proceso_id($proceso['id']);
            $proceso['documentos'] = $CI->institutos_contenidos_model->get_contenidos_by_proceso_and_categoria_for_json($proceso['id'], 1);
            $proceso['designaciones'] = $CI->institutos_contenidos_model->get_contenidos_by_proceso_and_categoria_for_json($proceso['id'], 2);
            $proceso['perfiles'] = $CI->institutos_contenidos_model->get_contenidos_by_proceso_and_categoria_for_json($proceso['id'], 3);

            $aux_fecha = explode('-', $proceso['fecha']);
            $carpeta = $aux_fecha[0] . '-' . strtoupper(substr(getMes($aux_fecha[1]), 0, 3));
            $ruta = $CI->config->item('files_url') . $instituto['siglas'] . '/' . $carpeta . '/';

            $data_documentos = [
                'id_instituto' => $instituto['id'],
                'descripcion' => $proceso['descripcion'],
                'carpeta' => $carpeta,
                'contenidos' => $proceso['documentos']
            ];
            if (file_exists($ruta . $names_json[0])) {
                unlink($ruta . $names_json[0]);
            }
            $file = fopen($ruta . $names_json[0], 'a+')or die("error al crear archivo " . $names_json[1]);
            fwrite($file, json_encode($data_documentos));
            fclose($file);
            $data_designaciones = [
                'id_instituto' => $instituto['id'],
                'descripcion' => $proceso['descripcion'],
                'carpeta' => $carpeta,
                'contenidos' => $proceso['designaciones']
            ];
            if (file_exists($ruta . $names_json[1])) {
                unlink($ruta . $names_json[1]);
            }
            $file = fopen($ruta . $names_json[1], 'a+')or die("error al crear archivo " . $names_json[1]);
            fwrite($file, json_encode($data_designaciones));
            fclose($file);
            $data_perfiles = [
                'id_instituto' => $instituto['id'],
                'descripcion' => $proceso['descripcion'],
                'carpeta' => $carpeta,
                'contenidos' => $proceso['perfiles']
            ];
            if (file_exists($ruta . $names_json[2])) {
                unlink($ruta . $names_json[2]);
            }
            $file = fopen($ruta . $names_json[2], 'a+')or die("error al crear archivo " . $names_json[2]);
            fwrite($file, json_encode($data_perfiles));
            fclose($file);
        }
    }

}
if (!function_exists('crear_json_candidatos')) {

    function crear_json_candidatos() {
        $CI = & get_instance();

        $CI->load->model('procesos_model');
        $CI->load->model('institutos_model');
        $CI->load->model('candidatos_model');
        $CI->load->model('votaciones_model');
        $CI->load->helper('proceso_helper');

        $procesos = $CI->procesos_model->get();
        $name = 'lista_candidatos.json';


        foreach ($procesos as &$proceso) {
            $data = [];
            $instituto = $CI->institutos_model->get_by_proceso_id($proceso['id']);
            $proceso['candidatos'] = $CI->candidatos_model->get_by_proceso_id_for_json($proceso['id']);

            $aux_fecha = explode('-', $proceso['fecha']);
            $carpeta = $aux_fecha[0] . '-' . strtoupper(substr(getMes($aux_fecha[1]), 0, 3));
            $ruta = $CI->config->item('files_url') . $instituto['siglas'] . '/' . $carpeta . '/';
            foreach ($proceso['candidatos'] as $candidato) {
                $data[] = [
                    'id' => $candidato['id'],
                    'nombre' => $candidato['nombre'] . ' ' . $candidato['paterno'] . ' ' . $candidato['materno'],
                    'foto' => $candidato['foto'],
                    'curriculum' => $candidato['curriculum'],
                    'descripcion' => $candidato['descripcion'],
                    'like' => $candidato['like'],
                    'unlike' => $candidato['unlike'],
                    'twitter' => $candidato['twitter'],
                    'usuario_fb' => $candidato['usuario_fb'],
                    'proceso_id' => $candidato['proceso_id'],
                    'votado' => ($CI->votaciones_model->get_by_candidato_id($candidato['id']) > 0) ? 'Si' : 'No'
                ];
            }


            if (file_exists($ruta . $name)) {
                unlink($ruta . $name);
            }
            $file = fopen($ruta . $name, 'a+')or die("error al crear archivo " . $name);
            fwrite($file, json_encode($data));
            fclose($file);
        }
    }

}