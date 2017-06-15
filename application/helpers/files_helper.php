<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('move_file')) {

    function move_file($name, $path) {
//        $CI = & get_instance();
        $source = 'temp/' . $name;
        if (copy($source, $path)) {
            unlink($source);
            $exito = true;
        } else {
            $exito = false;
        }
        return $exito;
    }

}
if (!function_exists('normalize_name')) {

    function normalize_name($file) {

        $simbolosNoPermitidos = array(' ', '_', '.',
            'á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä',
            'é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë', 'í',
            'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î',
            'ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô',
            'ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü',
            'ñ', 'Ñ', 'ç', 'Ç', '\\', '¨', 'º', '~',
            '#', '@', '|', '!', '\"', '·', '$', '%', '&', '/',
            '(', ')', '?', '¿', "'", '¡', '¿', '[', '^', '`', ']',
            '+', '}', '{', '¨', '´', '>', '<', ';', ',', ':'
        );
        $simbolosReemplazantes = array('-', '-', '', 'a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A', 'e', 'e', 'e', 'e', 'E', 'E', 'E', 'E', 'i', 'i', 'i', 'i', 'I', 'I', 'I', 'I', 'o', 'o', 'o', 'o', 'O', 'O', 'O', 'O', 'u', 'u', 'u', 'u', 'U', 'U', 'U', 'U', 'n', 'N', 'c', 'C', '-');
        $file = str_replace($simbolosNoPermitidos, $simbolosReemplazantes, $file);
        $file = preg_replace('([^A-Za-z0-9_])', '-', $file);
        return $file;
    }

}
if (!function_exists('getMes')) {

    function getMes($numMes) {
        switch ($numMes) {
            case '01': {
                    $mes_esp = 'Enero';
                    break;
                }
            case '02': {
                    $mes_esp = 'Febrero';
                    break;
                }
            case '03': {
                    $mes_esp = 'Marzo';
                    break;
                }
            case '04': {
                    $mes_esp = 'Abril';
                    break;
                }
            case '05': {
                    $mes_esp = 'Mayo';
                    break;
                }
            case '06': {
                    $mes_esp = 'Junio';
                    break;
                }
            case '07': {
                    $mes_esp = 'Julio';
                    break;
                }
            case '08': {
                    $mes_esp = 'Agosto';
                    break;
                }
            case '09': {
                    $mes_esp = 'Septiembre';
                    break;
                }
            case '10': {
                    $mes_esp = 'Octubre';
                    break;
                }
            case '11': {
                    $mes_esp = 'Noviembre';
                    break;
                }
            case '12': {
                    $mes_esp = 'Diciembre';
                    break;
                }
            default :
                $mes_esp = "";
                break;
        }
        return $mes_esp;
    }

}
if (!function_exists('resize_image')) {

//parametros: nombre imagen,ancho,alto,ruta donde se encuentra la imagen a redimensionar ahi mismo se va a aguardar,por ultimo se especifica si es cuadrada con FALSE , TRUE code redimensiona como mejor convenga
    function resize_image($file, $ancho, $alto, $ruta, $cuadrada = TRUE) {
        $CI = & get_instance();
        $nombreAux = explode('.', $file);
        $nuevonombre = $nombreAux[0] . '_' . $ancho . '.' . $nombreAux[1];

        $config['image_library'] = 'GD2';
        $config['source_image'] = $ruta . $file;
        $config['new_image'] = $nuevonombre;
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = $cuadrada;
        $config['width'] = $ancho;
        $config['height'] = $alto;
        $config['quality'] = 100;
        $CI->load->library('image_lib');
        $CI->image_lib->initialize($config);
        if (!$CI->image_lib->resize()) {
            return $CI->image_lib->display_errors();
        } else {
            $CI->image_lib->clear();
            unset($config);
            return $nuevonombre;
        }
    }

}
if (!function_exists('cut_image')) {

    //parametros: nombre imagen,ancho,alto,ruta donde se encuentra la imagen a redimensionar ahi mismo se va a aguardar,por ultimo se especifica si es cuadrada con FALSE , TRUE code redimensiona como mejor convenga
    function cut_image($file, $ancho = 0, $alto = 0, $ruta, $cuadrada = TRUE) {
        $CI = & get_instance();
        //http://escodeigniter.com/guia_usuario/libraries/image_lib.html
        $medidas = getimagesize($ruta . $file);
        $ancho_x = ($medidas[0] / 2) - 80;
        $ancho_y = ($medidas[1] / 2) - 80;
        $nombreAux = explode('.', $file);
        $nuevonombre = $nombreAux[0] . '_' . $ancho . '.' . $nombreAux[1];
        $config['image_library'] = 'GD2';
        $config['source_image'] = $ruta . $file;
        $config['new_image'] = $nuevonombre;
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = $cuadrada;
        $config['dynamic_output'] = TRUE;
        $config['x_axis'] = $ancho_x;
        $config['y_axis'] = $ancho_y;
        $config['width'] = $ancho;
        $config['height'] = $alto;
        $config['quality'] = 100;
        $CI->load->library('image_lib');
        $CI->image_lib->initialize($config);
        if (!$CI->image_lib->crop()) {
            return $CI->image_lib->display_errors();
        } else
            return $medidas;
        unset($config);
        $CI->image_lib->clear();
    }

}
if (!function_exists('save_file')) {

    function save_file($datos) {
        $CI = & get_instance();

        $data = array(
            'ruta' => $datos['ruta'],
            'descripcion' => $datos['descripcion'],
            'usuarios_id' => $datos['usuarios_id'],
            'tipo' => $datos['tipo'],
            'copias' => $datos['copias'],
            'dimensiones' => $datos['dimensiones']
        );
        // if(file_exists($datos['path'].$datos['name']))
        //{

        $CI->load->model('media_model');
        $id = $CI->media_model->insert($data);
        /* }
          else{
          $id=false;
          } */
        return $id;
    }

}
if (!function_exists('upload_file')) {

    function upload_file($path, $name, $name_field_form, $allowed_types = "pdf", $overwirite = FALSE) {
        $CI = & get_instance();
        unset($config);


        $config['upload_path'] = $path;
        $config['file_name'] = $name;
        $config['allowed_types'] = $allowed_types;
        $config['remove_spaces'] = TRUE;
        $config['max_size'] = FILE_MAX_SIZE;
        $config['max_width'] = FILE_MAX_WIDTH;
        $config['max_height'] = FILE_MAX_HEIGHT;
        $config['overwrite'] = $overwirite;

        $CI->load->library('upload');
        $CI->upload->initialize($config);


        if (!$CI->upload->do_upload($name_field_form)) {
            $result['success'] = false;
            $result['errors'] = $CI->upload->display_errors();
        } else {

            $result['success'] = true;
            $result['upload_data'] = $CI->upload->data();
        }
        //regresa un arreglo
        return $result;
    }

}

function generate_url($titulo) {
    $t = $titulo;
    $simbolosNoPermitidos = array('@', '#', ' ', '_', '.',
        'á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä',
        'é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë', 'í',
        'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î',
        'ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô',
        'ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü',
        'ñ', 'Ñ', 'ç', 'Ç', '\\', '¨', 'º', '~',
        '|', '!', '\"', '·', '$', '%', '&', '/',
        '(', ')', '?', "'", '¡', '¿', '[', '^', '`', ']',
        '+', '}', '{', '¨', '´', '>', '<', ';', ',', ':'
    );
    $simbolosReemplazantes = array('', '', '-', '-', '', 'a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A', 'e', 'e', 'e', 'e', 'E', 'E', 'E', 'E', 'i', 'i', 'i', 'i', 'I', 'I', 'I', 'I', 'o', 'o', 'o', 'o', 'O', 'O', 'O', 'O', 'u', 'u', 'u', 'u', 'U', 'U', 'U', 'U', 'n', 'N', 'c', 'C', '');
    $url_tmp = str_replace($simbolosNoPermitidos, $simbolosReemplazantes, $t);
    $url = preg_replace('([^A-Za-z0-9_#@])', '-', $url_tmp);
    return $url;
}
