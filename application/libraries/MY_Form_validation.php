<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of MY_Form_validation
 *
 * @author juan carlos, Moises Orduño Gomez
 */
class MY_Form_validation extends CI_Form_validation {

    public function __construct($config = array()) {
        parent::__construct($config);
    }

    // --------------------------------------------------------------------

    /**
     * Is Unique
     *
     * Check if the input value doesn't already exist
     * in the specified database field.
     *
     * @param	string	$str
     * @param	string	$field
     * @return	bool
     */
    public function is_unique_update($str, $field) {
        sscanf($field, '%[^.].%[^.].%[^.]', $table, $field, $id);

        $registro = $this->CI->db->limit(1)->where($field, $str)->where('id !=', $id)->get($table)->row_array();
        if ($registro !== NULL) {
            if ($registro['status'] === '0') {
                if ($field === 'numero_plaza') {
                    $field = 'Número de Plaza';
                } else if ($field === 'clave_gabinete') {
                    $field = 'Clave de gabinete';
                } else {
                    $field = ucfirst($field);
                }
                $this->CI->form_validation->set_message('is_unique_update', 'El registro ' . $field . ' ingresado ya existe en el sistema. Actualmente se encuentra inactivo.');
            } else {
                if ($registro['id'] == $id) {
                    return TRUE;
                }
            }
            return FALSE;
        } else {
            return TRUE;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Is Unique
     *
     * Check if the input value doesn't already exist
     * in the specified database field.
     *
     * @param	string	$str
     * @param	string	$field
     * @return	bool
     */
    public function is_unique($str, $field) {
        sscanf($field, '%[^.].%[^.]', $table, $field);
        $registro = $this->CI->db->limit(1)->where($field, $str)->get($table)->row_array();
        if ($registro !== NULL) {
            if ($registro['status'] === '0') {
                if ($field === 'numero_plaza') {
                    $field = 'Número de Plaza';
                } else if ($field === 'clave_gabinete') {
                    $field = 'Clave de gabinete';
                } else {
                    $field = ucfirst($field);
                }



                $this->CI->form_validation->set_message('is_unique', 'El registro ' . $field . ' ingresado ya existe en el sistema. Actualmente se encuentra inactivo.');
            }
            return FALSE;
        } else {
            return TRUE;
        }
    }

//º˚ª
    /**
     * utf8_numbers_symbols_direccion
     *
     * cheque si el input tiene numeros y letras en formato ut8, asi como simbolos necesarios para todas las
     * direcciones
     *
     *
     * @param	string	$str
     * @return	bool
     */
    function utf8_numbers_symbols_direccion($str) {
        return (!preg_match("/^([a-zñáéíóúÑÁÉÍÓÚüÜ0-9\.\, \"\/\-\_\#\(\)\&\º\˚\ª\º\°])+$/i", $str)) ? FALSE : TRUE;
    }

    /**
     * alpha_dash_space_utf8_numbers
     *
     * cheque si el input tiene numeros y letras en formato ut8, asi como guiones y puntos para ciudades
     * y para nombres
     *
     * @param	string	$str
     * @return	bool
     */
    function alpha_dash_space_period_utf8_numbers($str) {
        return (!preg_match("/^([a-zñáéíóúÑÁÉÍÓÚüÜ0-9\.\, \-\_])+$/i", $str)) ? FALSE : TRUE;
    }

    /**
     * alpha_dash_space_utf8_numbers
     *
     * cheque si el input tiene numeros el formato para RFC
     *
     *
     * @param	string	$str
     * @return	bool
     */
    function curp($str) {
        return (!preg_match("/^([a-zA-Z]{4})([0-9]{6})([a-zA-Z]{6})([a-zA-Z0-9]{2})$/", $str)) ? FALSE : TRUE;
    }

    /**
     * date
     *
     * Check if the input value is date in the format yyyy- mm -dd
     *
     *
     * @param	string	$date
     * @return	bool
     */
    public function date($date) {
        $bd = false;
        if (is_string($date)) {
            if (preg_match("/^[[:digit:]]{4}-[[:digit:]]{2}-[[:digit:]]{2}$/", $date)) {
                $fecha = explode('-', $date);
                if (checkdate($fecha[1], $fecha[2], $fecha[0])) {
                    $bd = true;
                } else if ($date == '0000-00-00') {
                    $bd = true;
                }
            }
        }
        return $bd;
    }

    /**
     * time
     *
     * Check if the input value is time in the format HH:MM:SS
     *
     *
     * @param	string	$time
     * @return	bool
     */
    public function time($time) {
        $bd = false;
        if (is_string($time)) {
            if (preg_match("/^[[:digit:]]{2}:[[:digit:]]{2}:[[:digit:]]{2}/", $time)) {
                $hora = explode(':', $time);
                if ($this->checktime($hora[0], $hora[1], $hora[2])) {
                    $bd = true;
                }
            }
        }
        return $bd;
    }

    /**
     * date time
     *
     * Check if the input value is time in the format YYYY-MM-SS HH:MM:SS
     *
     *
     * @param	string	$date_time
     * @return	bool
     */
    public function date_time($date_time) {
        $bd = false;
        if (is_string($date_time)) {
            $array_date_time = explode(' ', $date_time);
            if ($this->date($array_date_time[0]) && $this->time($array_date_time[1])) {
                $bd = true;
            }
        }
        return $bd;
    }

    /**
     * alpha_dash_space_utf8
     *
     * Check if the input value is time in the format YYYY-MM-SS HH:MM:SS
     *
     *
     * @param	string	$str
     * @return	bool
     */
    function alpha_dash_space_utf8($str) {
        return (!preg_match("/^([a-zñáéíóúÑÁÉÍÓÚüÜ\-\_\. ])+$/i", $str)) ? FALSE : TRUE;
    }

    /**
     * alpha_dash_space_utf8_numbers
     *
     * cheque si el input tiene numeros y letras en formato ut8, asi como simbolos
     *
     *
     * @param	string	$str
     * @return	bool
     */
    function alpha_dash_space_utf8_numbers($str) {
        return (!preg_match("/^([a-zñáéíóúÑÁÉÍÓÚ\-\_\.\, 0-9])+$/i", $str)) ? FALSE : TRUE;
    }

    /**
     * alpha_dash_space_utf8_numbers
     *
     * cheque si el input tiene numeros y simbolos para telefonos
     *
     *
     * @param	string	$str
     * @return	bool
     */
    function number_dash_parentheses($str) {
        return (!preg_match("/^([0-9 \-\(\)])+$/i", $str)) ? FALSE : TRUE;
    }

    /**
     * alpha_dash_space_utf8_numbers
     *
     * cheque si el input tiene numeros el formato para RFC
     *
     *
     * @param	string	$str
     * @return	bool
     */
    function rfc($str) {
        return (!preg_match("/^([a-zA-Z]{4})([0-9]{6})([a-zA-Z0-9]{3})$/", $str)) ? FALSE : TRUE;
    }

    /**
     * checktime
     *
     * cheque si el input tiene formato de hora para MySQL HH:mm:ss
     *
     *
     * @param	string	$str
     * @return	bool
     */
    private function checktime($hour, $min, $sec) {
        if ($hour < 0 || $hour > 23 || !is_numeric($hour)) {
            return false;
        }
        if ($min < 0 || $min > 59 || !is_numeric($min)) {
            return false;
        }
        if ($sec < 0 || $sec > 59 || !is_numeric($sec)) {
            return false;
        }
        return true;
    }

    /**
     * check_fechas
     *
     * Valida que la fecha final no sea menor que la fecha de inicio solo en el formulario
     *
     *
     * @param	string	$fecha_inicio
      string	$fecha_final
     * @return	bool
     */
    function check_fechas($fecha_termino, $fecha_inicio) {
        if ($fecha_termino < $fecha_inicio) {
            return FALSE;
        }
        return TRUE;
    }

    // --------------------------------------------------------------------
}
