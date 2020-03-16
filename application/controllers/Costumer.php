<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class Costomer extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('CostumerModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->get('costumers')->result(), false);
    }

    public function index_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->CostumerModel->rules();
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
        $user = new CostumerData();
        $user->nama_costumer = $this->post('nama_costumer');
        $user->alamat_costumer = $this->post('alamat_costumer');
        $user->tglLahir_costumer = $this->post('tglLahir_costumer');
        $user->noTelp_costumer = $this->post('noTelp_costumer');


        if($id == null){
            $response = $this->CostumerModel->store($user);
        }
        else{
            $response = $this->CostumerModel->update($user, $id);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id = null){
        if($id == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->LyricModel->destroy($id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}

Class CostumerData{
    public $nama_costumer;
    public $alamat_costumer;
    public $tglLahir_costumer;
    public $noTelp_costumer;
}