<?php

/**
 * Sesion
 *
 * @package Helpers
 * @subpackage
 * @category Sesion
 * @author Juan Carlos Arevalo Morales
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

@session_start();

if (!function_exists('get_type')) {

    function get_type() {
        $CI = & get_instance();
        return $_SESSION['u_designaciones']['type'];
    }

}

//funcion que verifica si la session tiene el id del usuario 
if (!function_exists('check_id')) {

    function check_id() {
        $CI = & get_instance();
        return (isset($_SESSION['u_designaciones']['id'])) ? $_SESSION['u_designaciones']['id'] : FALSE;
    }

}
//funcion que verifica si la session tiene el usuario 
if (!function_exists('check_user')) {

    function check_user() {
        $CI = & get_instance();
        return (isset($_SESSION['u_designaciones']['username'])) ? $_SESSION['u_designaciones']['username'] : FALSE;
    }

}


