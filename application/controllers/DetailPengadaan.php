<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class DetailPengadaan extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('DetailPengadaanModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->get('detailpengadaans')->result(), false);
    }

    public function index_post($no_order = null){
        $validation = $this->form_validation;
        $rule = $this->DetailPengadaanModel->rules();
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
        $user = new DetailPengadaanData();
        $user->no_order = $this->post('no_order');
        $user->id_produk = $this->post('id_produk');
        $user->jml_stok_pengadaan = $this->post('jml_stok_pengadaan');
        $user->status_pengadaan_produk = $this->post('status_pengadaan_produk');
        $user->subTotal = $this->post('subTotal');
        if($no_order == null){
            $response = $this->DetailPengadaanModel->store($user);
        }
        else{
            $response = $this->DetailPengadaanModel->update($user, $no_order);
        }
        return $this->returnData($response['msg'], $response['error']);
    }


    public function index_delete($no_order = null){
        if($no_order == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->DetailPengadaanModel->destroy($no_order);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}

Class DetailPengadaanData{
    public $no_order;
    public $id_produk;
    public $jml_stok_pengadaan;
    public $status_pengadaan_produk;
    public $subTotal;
}