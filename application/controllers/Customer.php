<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class Customer extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('CustomerModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->get_where('customers', ["aktif" => 1])->result(), false);
    }

    public function nonAktif_get(){
        return $this->returnData($this->db->get_where('customers', ["aktif" => 0])->result(), false);
    }

    public function all_get(){
        return $this->returnData($this->db->get('customers')->result(), false);
    }

    public function search_get($id_customer){
        return $this->returnData($this->db->get_where('customers', ["id_pelanggan" => $id_customer])->row(), false);
    }

    public function index_post($id_customer = null){
        $validation = $this->form_validation;
        $rule = $this->CustomerModel->rules();
        if($id_customer == null){
            array_push($rule,
                [
                    'field' => 'nama_customer',
                    'label' => 'nama_customer',
                    'rules' => 'required'
                ],
                [
                    'field' => 'alamat_customer',
                    'label' => 'alamat_customer',
                    'rules' => 'required'
                ],
                [
                    'field' => 'tglLahir_customer',
                    'label' => 'tglLahir_customer',
                    'rules' => 'required'
                ],
                [
                    'field' => 'noTelp_customer',
                    'label' => 'noTelp_customer',
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
        $user = new CustomerData();
        $user->nama_customer = $this->post('nama_customer');
        $user->alamat_customer = $this->post('alamat_customer');
        $user->tglLahir_customer = $this->post('tglLahir_customer');
        $user->noTelp_customer = $this->post('noTelp_customer');
        $user->updateLog_by = $this->post('updateLog_by');

        if($id_customer == null){
            $response = $this->CustomerModel->store($user);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    // public function update_post($id_customer = null){
    //     $validation = $this->form_validation;
    //     $rule = $this->CustomerModel->rules();
    //     if($id_customer == null){
    //         array_push($rule,
    //             [
    //                 'field' => 'nama_customer',
    //                 'label' => 'nama_customer',
    //                 'rules' => 'required'
    //             ],
    //             [
    //                 'field' => 'alamat_customer',
    //                 'label' => 'alamat_customer',
    //                 'rules' => 'required'
    //             ],
    //             [
    //                 'field' => 'tglLahir_customer',
    //                 'label' => 'tglLahir_customer',
    //                 'rules' => 'required'
    //             ],
    //             [
    //                 'field' => 'noTelp_customer',
    //                 'label' => 'noTelp_customer',
    //                 'rules' => 'required|numeric'
    //             ],
    //             [
    //                 'field' => 'updateLog_by',
    //                 'label' => 'updateLog_by',
    //                 'rules' => 'required'
    //             ]
    //         );
    //     }
    //     $validation->set_rules($rule);
    //     if (!$validation->run()) {
    //         return $this->returnData($this->form_validation->error_array(), true);
    //     }
    //     $user = new CustomerData();
    //     $user->nama_customer = $this->post('nama_customer');
    //     $user->alamat_customer = $this->post('alamat_customer');
    //     $user->tglLahir_customer = $this->post('tglLahir_customer');
    //     $user->noTelp_customer = $this->post('noTelp_customer');
    //     $user->updateLog_by = $this->post('updateLog_by');
    //     if($id_customer != null){
    //         $response = $this->CustomerModel->update($user,$id_customer);
    //     }
    //     return $this->returnData($response['msg'], $response['error']);
    // }

    public function update_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->CustomerModel->rules();
        if($id != null){
            array_push($rule,
                [
                    'field' => 'nama_customer',
                    'label' => 'nama_customer',
                    'rules' => 'required'
                ],
                [
                    'field' => 'alamat_customer',
                    'label' => 'alamat_customer',
                    'rules' => 'required'
                ],
                [
                    'field' => 'tglLahir_customer',
                    'label' => 'tglLahir_customer',
                    'rules' => 'required'
                ],
                [
                    'field' => 'noTelp_customer',
                    'label' => 'noTelp_customer',
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
        $pelanggan = new CustomerData();
        $pelanggan->nama_customer = $this->post('nama_customer');
        $pelanggan->alamat_customer = $this->post('alamat_customer');
        $pelanggan->tglLahir_customer = $this->post('tglLahir_customer');
        $pelanggan->noTelp_customer = $this->post('noTelp_customer');
        $pelanggan->updateLog_by = $this->post('updateLog_by');
        if($id != null){
            $response = $this->CustomerModel->update($pelanggan,$id);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function delete_post($id_customer = null){
        // $validation = $this->form_validation;
        // $rule = $this->CustomerModel->rules();
        // if($id_customer != null){
        //     array_push($rule,
        //         [
        //             'field' => 'updateLog_by',
        //             'label' => 'updateLog_by',
        //             'rules' => 'required'
        //         ]
        //     );
        // }
        // $validation->set_rules($rule);
        // if (!$validatioan->run()) {
        //     return $this->returnData($this->form_validation->error_array(), true);
        // }
        $customer = new CustomerData();
        // $customer->updateLog_by = $this->post('updateLog_by');
        if($id_customer != null){
            $response = $this->CustomerModel->softDelete($customer,$id_customer);
        }
        return $this->returnData($response['msg'], $response['error']);
    }


    // public function index_delete($id_customer = null){
    //     if($id_customer == null){
    //         return $this->returnData('Parameter Id Tidak Ditemukan', true);
    //     }
    //     $response = $this->CustomerModel->destroy($id_customer);
    //     return $this->returnData($response['msg'], $response['error']);
    // }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}

Class CustomerData{
    public $id_customer;
    public $nama_customer;
    public $alamat_customer;
    public $tglLahir_customer;
    public $noTelp_customer;
    public $updateLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;
    public $aktif;
}

