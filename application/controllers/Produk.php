<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class Produk extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('ProdukModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->query('select id_produk, nama_produk, harga_produk, stok_produk, min_stok_produk, satuan_produk, updateLog_by from produks')->result(), false);
    }

    public function index_post($id_produk = null){
        $validation = $this->form_validation;
        $rule = $this->ProdukModel->rules();
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
        $user = new ProdukData();
        $user->id_produk = $this->post('id_produk');
        $user->nama_produk = $this->post('nama_produk');
        $user->harga_produk = $this->post('harga_produk');
        $user->stok_produk = $this->post('stok_produk');
        $user->min_stok_produk = $this->post('min_stok_produk');
        $user->satuan_produk = $this->post('satuan_produk');
        // $user->gambar = $this->post('gambar');
        $user->updateLog_by = $this->post('updateLog_by');

        if($id_produk == null){
            $response = $this->ProdukModel->store($user);
        }
        else{
            $response = $this->ProdukModel->update($user, $id_produk);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id_produk = null){
        if($id_produk == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->ProdukModel->destroy($id_produk);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}

Class ProdukData{
    public $id_produk;
    public $nama_produk;
    public $harga_produk;
    public $stok_produk;
    public $min_stok_produk;
    public $satuan_produk;
    // public $gambar;
    public $updateLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;
}