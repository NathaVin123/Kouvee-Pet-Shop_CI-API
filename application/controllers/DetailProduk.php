<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class DetailProduk extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('DetailProdukModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->get('detailproduks')->result(), false);
    }

    public function index_post($kode_penjualan = null){
        $validation = $this->form_validation;
        $rule = $this->DetailProdukModel->rules();
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
        $user = new DetailProdukData();
        $user->kode_penjualan = $this->post('kode_penjualan');
        $user->id_produk = $this->post('id_produk');
        $user->tgl_transaksi_produk = $this->post('tgl_transaksi_produk');
        $user->jml_transaksi_produk = $this->post('jml_transaksi_produk');
        $user->subtotal = $this->post('subtotal');
        if($kode_penjualan == null){
            $response = $this->DetailProdukModel->store($user);
        }
        else{
            $response = $this->DetailProdukModel->update($user, $kode_penjualan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }


    public function index_delete($kode_penjualan = null){
        if($kode_penjualan == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->DetailProdukModel->destroy($kode_penjualan);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}

Class DetailProdukData{
    public $kode_penjualan;
    public $id_produk;
    public $tgl_transaksi_produk;
    public $jml_transaksi_produk;
    public $subtotal;
}