<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class JenisHewan extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('JenisHewanModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->get('jenishewans')->result(), false);
    }

    public function index_post($id_jenisHewan = null){
        $validation = $this->form_validation;
        $rule = $this->JenisHewanModel->rules();
        /*if($id == null){
            array_push($rule, [
                'field' => 'password',
                'label' => 'password',
                'rules' => 'required'
            ],
            [
                'field' => 'email',
                'label' => 'email',
                'rules' => 'required|valid_email|is_unique[users.email]'
            ]);
        }
        else{
            array_push($rule, 
            [
                'field' => 'email',
                'label' => 'email',
                'rules' => 'required|valid_email'
            ]);
            }*/
        $validation->set_rules($rule);
        if(!$validation->run()){
            return $this->returnData($this->form_validation->error_array(), true);
        }
        $user = new JenisHewanData();
        $user->nama_jenisHewan = $this->post('nama_jenisHewan');

        if($id_jenisHewan == null){
            $response = $this->JenisHewanModel->store($user);
        }
        else{
            $response = $this->JenisHewanModel->update($user, $id_jenisHewan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id_jenisHewan = null){
        if($id_jenisHewan == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->JenisHewanModel->destroy($id_jenisHewan);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}

Class JenisHewanData{
    public $nama_jenisHewan;
    
}