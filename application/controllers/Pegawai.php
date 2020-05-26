<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class Pegawai extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('PegawaiModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->get_where('pegawais', ["aktif" => 1])->result(), false);
    }

    public function nonAktif_get(){
        return $this->returnData($this->db->get_where('pegawais', ["aktif" => 0])->result(), false);
    }

    public function all_get(){
        return $this->returnData($this->db->get('pegawais')->result(), false);
    }

    public function search_get($NIP){
        return $this->returnData($this->db->get_where('pegawais', ["NIP" => $NIP])->row(), false);
    }

    public function index_post($NIP = null){
        $validation = $this->form_validation;
        $rule = $this->PegawaiModel->rules();
        if($NIP == null){
            array_push($rule,
                [
                    'field' => 'NIP',
                    'label' => 'NIP',
                    'rules' => 'required'
                ],
                [
                    'field' => 'nama_pegawai',
                    'label' => 'nama_pegawai',
                    'rules' => 'required'
                ],
                [
                    'field' => 'alamat_pegawai',
                    'label' => 'alamat_pegawai',
                    'rules' => 'required'
                ],
                [
                    'field' => 'tglLahir_pegawai',
                    'label' => 'tglLahir_pegawai',
                    'rules' => 'required'
                ],
                [
                    'field' => 'noTelp_pegawai',
                    'label' => 'noTelp_pegawai',
                    'rules' => 'required|numeric'
                ],
                [
                    'field' => 'stat',
                    'label' => 'stat',
                    'rules' => 'required'
                ],
                [
                    'field' => 'password',
                    'label' => 'password',
                    'rules' => 'required'
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
        $user = new PegawaiData();
        $user->NIP = $this->post('NIP');
        $user->nama_pegawai = $this->post('nama_pegawai');
        $user->alamat_pegawai = $this->post('alamat_pegawai');
        $user->tglLahir_pegawai = $this->post('tglLahir_pegawai');
        $user->noTelp_pegawai = $this->post('noTelp_pegawai');
        $user->stat = $this->post('stat');
        $user->password = $this->post('password');
        $user->gambar = $this->post('gambar');
        $user->updateLog_by = $this->post('updateLog_by');
        
        if($NIP == null){
            $response = $this->PegawaiModel->store($user);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function update_post($NIP = null){
        $validation = $this->form_validation;
        $rule = $this->PegawaiModel->rules();
        if($NIP != null){
            array_push($rule,
                [
                    'field' => 'NIP',
                    'label' => 'NIP',
                    'rules' => 'required'
                ],
                [
                    'field' => 'nama_pegawai',
                    'label' => 'nama_pegawai',
                    'rules' => 'required'
                ],
                [
                    'field' => 'alamat_pegawai',
                    'label' => 'alamat_pegawai',
                    'rules' => 'required'
                ],
                [
                    'field' => 'tglLahir_pegawai',
                    'label' => 'tglLahir_pegawai',
                    'rules' => 'required'
                ],
                [
                    'field' => 'noTelp_pegawai',
                    'label' => 'noTelp_pegawai',
                    'rules' => 'required|numeric'
                ],
                [
                    'field' => 'stat',
                    'label' => 'stat',
                    'rules' => 'required'
                ],
                [
                    'field' => 'password',
                    'label' => 'password',
                    'rules' => 'required'
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
        $user = new PegawaiData();
        $user->NIP = $this->post('NIP');
        $user->nama_pegawai = $this->post('nama_pegawai');
        $user->alamat_pegawai = $this->post('alamat_pegawai');
        $user->tglLahir_pegawai = $this->post('tglLahir_pegawai');
        $user->noTelp_pegawai = $this->post('noTelp_pegawai');
        $user->stat = $this->post('stat');
        $user->password = $this->post('password');
        $user->gambar = $this->post('gambar');
        $user->updateLog_by = $this->post('updateLog_by');
        if($NIP != null){
            $response = $this->PegawaiModel->update($user,$NIP);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function delete_post($NIP = null){
        $validation = $this->form_validation;
        $rule = $this->PegawaiModel->rules();
        
        $validation->set_rules($rule);
        if (!$validation->run()) {
            return $this->returnData($this->form_validation->error_array(), true);
        }
        $user = new PegawaiData();
        if($NIP != null){
            $response = $this->PegawaiModel->softDelete($user,$NIP);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    // public function index_delete($NIP = null){
    //     if($NIP == null){
    //         return $this->returnData('Parameter NIP Tidak Ditemukan', true);
    //     }
    //     $response = $this->PegawaiModel->destroy($NIP);
    //     return $this->returnData($response['msg'], $response['error']);
    // }

    public function updatepass_post($NIP = null){
        $validation = $this->form_validation;
        $rule = $this->PegawaiModel->rules();
        if($NIP != null){
            array_push($rule,
                [
                    'field' => 'password',
                    'label' => 'password',
                    'rules' => 'required'
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
        $user = new PegawaiData();
        $user->password = $this->post('password');
        $user->updateLog_by = $this->post('updateLog_by');
        if($NIP != null){
            $response = $this->PegawaiModel->change_password($user,$NIP);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function auth_post(){
        $validation = $this->form_validation;
        $rule = $this->PegawaiModel->rules();
        array_push($rule,
            [
                'field' => 'NIP',
                'label' => 'NIP',
                'rules' => 'required'
            ],
            [
                'field' => 'password',
                'label' => 'password',
                'rules' => 'required'
            ]
        );
        $validation->set_rules($rule);
        if (!$validation->run()) {
            return $this->returnData($this->form_validation->error_array(), true);
        }        

        $user = new PegawaiData();
        $user->NIP = $this->post('NIP');
        $user->password = $this->post('password');

        $response = $this->PegawaiModel->verify($user);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}

Class PegawaiData{
    public $NIP;
    public $nama_pegawai;
    public $alamat_pegawai;
    public $tglLahir_pegawai;
    public $noTelp_pegawai;
    public $stat;
    public $password;
    public $gambar;
    public $updateLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;
}