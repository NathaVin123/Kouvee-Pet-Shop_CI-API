<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class TransaksiPenjualan extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('TransaksiPenjualanModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->get('transaksipenjualans')->result(), false);
    }

    public function index_post($id_transaksi_penjualan = null){
        $validation = $this->form_validation;
        $rule = $this->TransaksiPenjualanModel->rules();
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
        $user = new TransaksiPenjualanData();
        $user->nama_kasir = $this->post('nama_kasir');
        $user->subtotal = $this->post('subtotal');
        $user->diskon = $this->post('diskon');
        $user->total = $this->post('total');
        $user->status_transaksi = $this->post('status_transaksi');
        if($id_transaksi_penjualan == null){
            $response = $this->TransaksiPenjualanModel->store($user);
        }
        else{
            $response = $this->TransaksiPenjualanModel->update($user, $id_transaksi_penjualan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }


    public function index_delete($id_transaksi_penjualan = null){
        if($id_transaksi_penjualan == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->TransaksiPenjualanModel->destroy($id_transaksi_penjualan);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}

Class TransaksiPenjualanData{
    public $nama_kasir;
    public $subtotal;
    public $diskon;
    public $total;
    public $status_transaksi;
}