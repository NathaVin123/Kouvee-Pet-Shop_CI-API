<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class UkuranHewan extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('UkuranHewanModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->get_where('ukuranhewans', ["aktif" => 1])->result(), false);
    }

    public function nonAktif_get(){
        return $this->returnData($this->db->get_where('ukuranhewans', ["aktif" => 0])->result(), false);
    }

    public function all_get(){
        return $this->returnData($this->db->get('ukuranhewans')->result(), false);
    }

    public function search_get($id_ukuranHewan){
        return $this->returnData($this->db->get_where('ukuranhewans', ["id_ukuranHewan" => $id_ukuranHewan])->row(), false);
    }

    public function index_post($id_ukuranHewan = null){
        $validation = $this->form_validation;
        $rule = $this->UkuranHewanModel->rules();
        if($id_ukuranHewan == null){
            array_push($rule,
                [
                    'field' => 'nama_ukuranHewan',
                    'label' => 'nama_ukuranHewan',
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
        $user = new UkuranHewanData();
        $user->nama_ukuranHewan = $this->post('nama_ukuranHewan');
        $user->updateLog_by = $this->post('updateLog_by');

        if($id_ukuranHewan == null){
            $response = $this->UkuranHewanModel->store($user);
        }
        else{
            $response = $this->UkuranHewanModel->update($user, $id_ukuranHewan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function update_post($id_ukuranHewan = null){
        $validation = $this->form_validation;
        $rule = $this->UkuranHewanModel->rules();
        if($id_ukuranHewan != null){
            array_push($rule,
                [
                    'field' => 'nama_ukuranHewan',
                    'label' => 'nama_ukuranHewan',
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
        $user = new UkuranHewanData();
        $user->nama_ukuranHewan = $this->post('nama_ukuranHewan');
        $user->updateLog_by = $this->post('updateLog_by');
        if($id_ukuranHewan != null){
            $response = $this->UkuranHewanModel->update($ukuranhewans,$id_ukuranHewan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function delete_post($id_ukuranHewan = null){
        $validation = $this->form_validation;
        $rule = $this->UkuranHewanModel->rules();
        $validation->set_rules($rule);
        if (!$validation->run()) {
            return $this->returnData($this->form_validation->error_array(), true);
        }
        $user = new UkuranHewanData();
        if($id_ukuranHewan != null){
            $response = $this->UkuranHewanModel->softDelete($ukuranhewans,$id_ukuranHewan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    // public function index_delete($id_ukuranHewan = null){
    //     if($id_ukuranHewan == null){
    //         return $this->returnData('Parameter Id Tidak Ditemukan', true);
    //     }
    //     $response = $this->UkuranHewanModel->destroy($id_ukuranHewan);
    //     return $this->returnData($response['msg'], $response['error']);
    // }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}

Class UkuranHewanData{
    public $id_ukuranHewan;
    public $nama_ukuranHewan;
    public $updateLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;
    public $aktif;
}