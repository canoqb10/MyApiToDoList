<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH . 'libraries/REST_Controller.php');

class Tareas extends REST_Controller {

    public function __construct() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
        parent::__construct();
        $this->load->model('tareas_model');
        date_default_timezone_set('America/Mexico_City');
    }

    public function read_get() {
        $data = $this->tareas_model->get();
        $this->response($data, REST_Controller::HTTP_OK);
    }

    public function create_post() {

        if ($this->_validation()) {
            $data['titulo'] = $this->post('titulo');
            $data['descripcion'] = $this->post('descripcion');
            $data['urgente'] = ($this->post('urgente') == 0) ? -1 : 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['status'] = 1;

            $this->db->trans_start();
            $id = $this->tareas_model->insert($data);
            if ($id) {
                $this->db->trans_complete();
                $this->response(['id' => $id, 'message' => 'Tarea registrada con éxito'], REST_Controller::HTTP_CREATED);
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
                $data['urgente'] = ($this->post('urgente') == 0) ? -1 : 1;
                $data['updated_at'] = date('Y-m-d H:i:s');
                $this->db->trans_start();
                if ($this->tareas_model->update($data, $id)) {
                    $this->db->trans_complete();
                    $this->response(['message' => 'Tarea Actualizada con éxito'], REST_Controller::HTTP_OK);
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
            $this->db->trans_start();
            $id = $this->tareas_model->update($data, $id);
            if ($id) {
                $this->db->trans_complete();
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
            $this->response($data, REST_Controller::HTTP_OK);
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
