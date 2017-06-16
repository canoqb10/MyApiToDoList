<?php

class Tareas_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get() {
        $this->db->select('id,titulo,descripcion,urgente');
        $this->db->order_by('urgente', 'DESC');
        $this->db->where('status', 1);
        $data = $this->db->get('tareas');
        return $data->result_array();
    }

    public function get_by_id($id) {
        $this->db->select('id,titulo,descripcion,urgente');
        $this->db->where('id', $id);
        $etapa = $this->db->get('tareas');
        return $etapa->row_array();
    }

//
    public function update($data, $id) {
        $this->db->where('id', $id);
        return $this->db->update('tareas', $data);
    }

    public function insert($data) {
        return ($this->db->insert('tareas', $data)) ? $this->db->insert_id() : FALSE;
    }

}
