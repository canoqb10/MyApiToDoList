<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
  if (!function_exists('have_permission_to_controller')) {

  function have_permission_to_controller($type_user = '', $controller = '') {
  return isset($users[$type_user][$controller]);
  }

  } */

if (!function_exists('date_spanish')) {

    function date_spanish($date) {
        $meses = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");
        $timestamp = strtotime($date);
        return date("d", $timestamp) . ' de ' . $meses[date("n", $timestamp) - 1] . ' de ' . date("Y", $timestamp);
    }

}