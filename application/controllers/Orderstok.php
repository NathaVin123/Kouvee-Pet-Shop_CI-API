<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class OrderStok extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('OrderStokModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->get('orderstocks')->result(), false);
    }

    public function index_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->OrderStokModel->rules();
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
        $user = new OrderStokData();
        $user->nama_stock = $this->post('nama_stok');
        $user->satuan_stock = $this->post('satuan_stock');
        $user->tgl_pesan = $this->post('tgl_pesan');
        $user->tgl_cetak = $this->post('tgl_cetak');
        if($id == null){
            $response = $this->OrderStokModel->store($user);
        }
        else{
            $response = $this->OrderStokModel->update($user, $id);
        }
        return $this->returnData($response['msg'], $response['error']);
    }


    public function index_delete($id = null){
        if($id == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->OrderStokModel->destroy($id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}

Class OrderStokData{
    public $nama_stock;
    public $satuan_stock;
    public $tgl_pesan;
    public $tgl_cetak;
}