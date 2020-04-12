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
        return $this->returnData($this->db->get('layanans')->result(), false);
    }

    public function index_post($id_layanan = null){
        $validation = $this->form_validation;
        $rule = $this->LayananModel->rules();
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
        $user = new LayananData();
        $user->id_layanan = $this->post('id_layanan');
        $user->nama_layanan = $this->post('nama_layanan');
        $user->harga_layanan = $this->post('harga_layanan');
        $user->id_ukuranHewan = $this->post('id_ukuranHewan');
        $user->updateLog_by = $this->post('updateLog_by');

        if($id_layanan == null){
            $response = $this->LayananModel->store($user);
        }
        else{
            $response = $this->LayananModel->update($user, $id_layanan);
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
    public $harga_layanan;
    public $id_ukuranHewan;
    public $updateLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;
}