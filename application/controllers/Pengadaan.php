<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class Pengadaan extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('PengadaanModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->get('pengadaans')->result(), false);
    }

    public function index_post($no_order = null){
        $validation = $this->form_validation;
        $rule = $this->PengadaanModel->rules();
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
        $user = new PengadaanData();
        $user->no_order = $this->post('no_order');
        $user->tgl_pesan = $this->post('tgl_pesan');
        $user->tgl_Cetak = $this->post('tgl_Cetak');
        $user->nama_stock = $this->post('nama_stock');
        $user->satuan_stock = $this->post('satuan_stock');
        $user->id_supplier = $this->post('id_supplier');
        $user->status_pengadaan = $this->post('status_pengadaan');
        $user->total_harga = $this->post('total_harga');
        if($no_order == null){
            $response = $this->PengadaanModel->store($user);
        }
        else{
            $response = $this->PengadaanModel->update($user, $no_order);
        }
        return $this->returnData($response['msg'], $response['error']);
    }


    public function index_delete($no_order = null){
        if($no_order == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->PengadaanModel->destroy($no_order);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}

Class PengadaanData{
    public $no_order;
    public $tgl_pesan;
    public $tgl_Cetak;
    public $nama_stock;
    public $satuan_stock;
    public $id_supplier;
    public $status_pengadaan;
    public $total_harga;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;
}