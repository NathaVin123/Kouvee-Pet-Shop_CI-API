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
        // $user->id_hewan = $this->post('id_hewan');
        $user->nama_hewan = $this->post('nama_hewan');
        $user->tglLahir_hewan = $this->post('tglLahir_hewan');
        $user->id_customer = $this->post('id_customer');
        $user->id_jenisHewan = $this->post('id_jenisHewan');
        $user->updateLog_by = $this->post('updateLog_by');
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
    public $id_hewan;
    public $nama_hewan;
    public $tglLahir_hewan;
    public $id_customer;
    public $id_jenisHewan;
    public $updateLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;
}