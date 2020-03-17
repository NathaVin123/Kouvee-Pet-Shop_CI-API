<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class Supplier extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('SupplierModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->get('suppliers')->result(), false);
    }

    public function index_post($id_supplier = null){
        $validation = $this->form_validation;
        $rule = $this->SupplierModel->rules();
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
        $user = new SupplierData();
        $user->nama_supplier = $this->post('nama_supplier');
        $user->alamat_supplier = $this->post('alamat_supplier');
        $user->telepon_supplier = $this->post('telepon_supplier');
        $user->stok_supplier = $this->post('stok_supplier');
        if($id_supplier == null){
            $response = $this->SupplierModel->store($user);
        }
        else{
            $response = $this->SupplierModel->update($user, $id_supplier);
        }
        return $this->returnData($response['msg'], $response['error']);
    }


    public function index_delete($id_supplier = null){
        if($id_supplier == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->SupplierModel->destroy($id_supplier);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}

Class SupplierData{
    public $nama_supplier;
    public $alamat_supplier;
    public $telepon_supplier;
    public $stok_supplier;
}