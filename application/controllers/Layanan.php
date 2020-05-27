<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class Layanan extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('LayananModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->get_where('layanans', ["aktif" => 1])->result(), false);
    }

    public function nonAktif_get(){
        return $this->returnData($this->db->get_where('layanans', ["aktif" => 0])->result(), false);
    }

    public function all_get(){
        return $this->returnData($this->db->get('layanans')->result(), false);
    }

    public function search_get($id_layanan){
        return $this->returnData($this->db->get_where('layanans', ["id_layanan" => $id_layanan])->row(), false);
    }

    public function index_post($id_layanan = null){
        $validation = $this->form_validation;
        $rule = $this->LayananModel->rules();
        if($id == null){
            array_push($rule,
                [
                    'field' => 'nama_layanan',
                    'label' => 'nama_layanan',
                    'rules' => 'required'
                ],
                [
                    'field' => 'updateLog_by',
                    'label' => 'updateLog_by',
                    'rules' => 'required'
                ]
            );
        }
        $validation->set_rules($rule);
        if(!$validation->run()){
            return $this->returnData($this->form_validation->error_array(), true);
        }
        $user = new LayananData();
        $user->nama_layanan = $this->post('nama_layanan');
        $user->updateLog_by = $this->post('updateLog_by');

        if($id_layanan == null){
            $response = $this->LayananModel->store($user);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function update_post($id_layanan = null){
        $validation = $this->form_validation;
        $rule = $this->LayananModel->rules();
        if($id_layanan != null){
            array_push($rule,
                [
                    'field' => 'nama_layanan',
                    'label' => 'nama_layanan',
                    'rules' => 'required'
                ],
                [
                    'field' => 'updateLog_by',
                    'label' => 'updateLog_by',
                    'rules' => 'required'
                ]
            );
        }
        $validation->set_rules($rule);
        if (!$validation->run()) {
            return $this->returnData($this->form_validation->error_array(), true);
        }
        $user = new LayananData();
        $user->nama_layanan = $this->post('nama_layanan');
        $user->updateLog_by = $this->post('updateLog_by');
        if($id_layanan != null){
            $response = $this->LayananModel->update($user,$id_layanan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function delete_post($id_layanan = null){
        // $validation = $this->form_validation;
        // $rule = $this->LayananModel->rules();
        
        // $validation->set_rules($rule);
        // if (!$validation->run()) {
        //     return $this->returnData($this->form_validation->error_array(), true);
        // }
        $user = new LayananData();
        if($id_layanan != null){
            $response = $this->LayananModel->softDelete($user,$id_layanan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id_layanan = null){
        if($id_layanan == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->LayananModel->destroy($id_layanan);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}

Class LayananData{
    public $id_layanan;
    public $nama_layanan;
    public $updateLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;
    public $aktif;
}