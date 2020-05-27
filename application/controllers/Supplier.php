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
        return $this->returnData($this->db->get_where('suppliers', ["aktif" => 1])->result(), false);
    }

    public function nonAktif_get(){
        return $this->returnData($this->db->get_where('suppliers', ["aktif" => 0])->result(), false);
    }

    public function all_get(){
        return $this->returnData($this->db->get('suppliers')->result(), false);
    }

    public function search_get($id_supplier){
        return $this->returnData($this->db->get_where('suppliers', ["id_supplier" => $id_supplier])->row(), false);
    }

    public function index_post($id_supplier = null){
        $validation = $this->form_validation;
        $rule = $this->SupplierModel->rules();
        if($id_supplier == null){
            array_push($rule,
                [
                    'field' => 'nama_supplier',
                    'label' => 'nama_supplier',
                    'rules' => 'required'
                ],
                [
                    'field' => 'alamat_supplier',
                    'label' => 'alamat_supplier',
                    'rules' => 'required'
                ],
                [
                    'field' => 'telepon_supplier',
                    'label' => 'telepon_supplier',
                    'rules' => 'required|numeric'
                ],
                [
                    'field' => 'updateLog_by',
                    'label' => 'updateLog_by',
                    'rules' => 'required'
                ]
            );
        }
        $validation->set_rules($rule);
        if(!$validation->run()){
            return $this->returnData($this->form_validation->error_array(), true);
        }
        $user = new SupplierData();
        $user->nama_supplier = $this->post('nama_supplier');
        $user->alamat_supplier = $this->post('alamat_supplier');
        $user->telepon_supplier = $this->post('telepon_supplier');
        $user->updateLog_by = $this->post('updateLog_by');
        if($id_supplier == null){
            $response = $this->SupplierModel->store($user);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function update_post($id_supplier = null){
        $validation = $this->form_validation;
        $rule = $this->SupplierModel->rules();
        if($id_supplier == null){
            array_push($rule,
                [
                    'field' => 'nama_supplier',
                    'label' => 'nama_supplier',
                    'rules' => 'required'
                ],
                [
                    'field' => 'alamat_supplier',
                    'label' => 'alamat_supplier',
                    'rules' => 'required'
                ],
                [
                    'field' => 'telepon_supplier',
                    'label' => 'telepon_supplier',
                    'rules' => 'required|numeric'
                ],
                [
                    'field' => 'updateLog_by',
                    'label' => 'updateLog_by',
                    'rules' => 'required'
                ]
            );
        }
        $validation->set_rules($rule);
        if (!$validation->run()) {
            return $this->returnData($this->form_validation->error_array(), true);
        }
        $user = new SupplierData();
        $user->nama_supplier = $this->post('nama_supplier');
        $user->alamat_supplier = $this->post('alamat_supplier');
        $user->telepon_supplier = $this->post('telepon_supplier');
        $user->updateLog_by = $this->post('updateLog_by');
        if($id_supplier != null){
            $response = $this->SupplierModel->update($user,$id_supplier);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function delete_post($id_supplier = null){
        // $validation = $this->form_validation;
        // $rule = $this->SupplierModel->rules();
        // if($id_supplier != null){
        //     array_push($rule,
        //         [
        //             'field' => 'updateLog_by',
        //             'label' => 'updateLog_by',
        //             'rules' => 'required'
        //         ]
        //     );
        // }
        // $validation->set_rules($rule);
        // if (!$validation->run()) {
        //     return $this->returnData($this->form_validation->error_array(), true);
        // }
        $user = new SupplierData();
        // $user->updateLog_by = $this->post('updateLog_by');
        if($id_supplier != null){
            $response = $this->SupplierModel->softDelete($user,$id_supplier);
        }
        return $this->returnData($response['msg'], $response['error']);
    }


    // public function index_delete($id_supplier = null){
    //     if($id_supplier == null){
    //         return $this->returnData('Parameter Id Tidak Ditemukan', true);
    //     }
    //     $response = $this->SupplierModel->destroy($id_supplier);
    //     return $this->returnData($response['msg'], $response['error']);
    // }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}

Class SupplierData{
    public $id_supplier;
    public $nama_supplier;
    public $alamat_supplier;
    public $telepon_supplier;
    public $updateLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;
    public $aktif;
}