<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class Hewan extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('HewanModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->get('hewans')->result(), false);
    }

    public function index_post($id_hewan = null){
        $validation = $this->form_validation;
        $rule = $this->HewanModel->rules();
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
        $user = new HewanData();
        $user->nama_hewan = $this->post('nama_hewan');
        $user->tglLahir_hewan = $this->post('tglLahir_hewan');
        $user->nama_costumer = $this->post('nama_costumer');
        $user->nama_cs = $this->post('nama_cs');
        if($id_hewan == null){
            $response = $this->HewanModel->store($user);
        }
        else{
            $response = $this->HewanModel->update($user, $id_hewan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }


    public function index_delete($id_hewan = null){
        if($id_hewan == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->HewanModel->destroy($id_hewan);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}

Class HewanData{
    public $nama_hewan;
    public $tglLahir_hewan;
    public $nama_costumer;
    public $nama_cs;
}