<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH . 'libraries/REST_Controller.php');

class Tareas extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('tareas_model');
        date_default_timezone_set('America/Mexico_City');
    }

    public function create_post() {

        if ($this->_validation()) {
            $data['titulo'] = $this->post('titulo');
            $data['descripcion'] = $this->post('descripcion');
            $data['urgente'] = $this->post('urgente');
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['status'] = 1;

            $this->db->trans_start();
            $id = $this->tareas_model->insert($data);
            if ($id) {
                $this->db->trans_complete();
                $this->response(['id' => $id], REST_Controller::HTTP_CREATED);
            } else {
                $this->response(['message' => 'Error: NO se almacenaron los datos correctamente'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            $this->response(['message' => validation_errors()], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    // actualizar registro
    public function update_post() {
        $id = $this->post('id');
        if ($id != NULL) {
            if ($this->_validation()) {


                $data['titulo'] = $this->post('titulo');
                $data['descripcion'] = $this->post('descripcion');
                $data['urgente'] = $this->post('urgente');
                $data['update_at'] = date('Y-m-d H:i:s');
                $this->db->trans_start();
                if ($this->tareas_model->update($data, $id)) {
                    $this->db->trans_complete();
                    $this->response(['id' => $id], REST_Controller::HTTP_OK);
                } else {
                    $this->response(['message' => 'Error: NO se almacenaron los datos correctamente'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                $this->response(['message' => validation_errors()], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(array("No se proporciono el ID"), REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    //eliminar registro
    public function delete_get() {
        $id = $this->get('id');
        if ($id != NULL) {
            $data = array('status' => -1);
            $id = $this->tareas_model->update($data, $id);
            if ($id) {
                crear_json_tareas();
                $this->response(array('message' => 'Tarea dada de baja con éxito'), REST_Controller::HTTP_OK);
            }
        } else {
            $this->response(array("No se proporciono el ID"), REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    //leer por id
    public function read_by_id_get() {
        $id = $this->get('id');
        if ($id != NULL) {
            $data = $this->tareas_model->get_by_id($id);
            if ($data != NULL) {
                $data = $this->_generate_url_publicas($data, TRUE);
                $this->response($data, REST_Controller::HTTP_OK);
            }
        } else {
            $this->response(array("No se proporciono el ID"), REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    private function _validation() {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('titulo', 'titulo', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('urgente', 'urgente', 'trim|required');
        return $this->form_validation->run();
    }

}
