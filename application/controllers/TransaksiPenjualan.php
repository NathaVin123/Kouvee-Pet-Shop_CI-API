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

    public function index_post($kode_penjualan = null){
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
        $user->kode_penjualan = $this->post('kode_penjualan');
        $user->tgl_transaksi_penjualan = $this->post('tgl_transaksi_penjualan');
        $user->nama_kasir = $this->post('nama_kasir');
        $user->total = $this->post('total');
        $user->status_transaksi = $this->post('status_transaksi');
        $user->status_pembayaran = $this->post('status_pembayaran');
        $user->id_customer = $this->post('id_customer');
        $user->id_CS = $this->post('id_CS');
        $user->id_kasir = $this->post('id_kasir');
        if($kode_penjualan == null){
            $response = $this->TransaksiPenjualanModel->store($user);
        }
        else{
            $response = $this->TransaksiPenjualanModel->update($user, $kode_penjualan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }


    public function index_delete($kode_penjualan = null){
        if($kode_penjualan == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->TransaksiPenjualanModel->destroy($kode_penjualan);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}

Class TransaksiPenjualanData{
    public $kode_penjualan;
    public $tgl_transaksi_penjualan;
    public $nama_kasir;
    public $total;
    public $status_transaksi;
    public $status_pembayaran;
    public $id_customer;
    public $id_CS;
    public $id_kasir;
}