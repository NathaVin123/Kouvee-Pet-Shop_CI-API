<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class JenisHewan extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('JenisHewanModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->get_where('jenishewans', ["aktif" => 1])->result(), false);
    }

    public function nonAktif_get(){
        return $this->returnData($this->db->get_where('jenishewans', ["aktif" => 0])->result(), false);
    }

    public function all_get(){
        return $this->returnData($this->db->get('jenishewans')->result(), false);
    }

    public function search_get($id_jenisHewan){
        return $this->returnData($this->db->get_where('jenishewans', ["id_jenisHewan" => $id_jenisHewan])->row(), false);
    }

    public function index_post($id_jenisHewan = null){
        $validation = $this->form_validation;
        $rule = $this->JenisHewanModel->rules();
        if($id_jenisHewan == null){
            array_push($rule,
                [
                    'field' => 'nama_jenisHewan',
                    'label' => 'nama_jenisHewan',
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
        $user = new JenisHewanData();        
        $user->nama_jenisHewan = $this->post('nama_jenisHewan');
        $user->updateLog_by = $this->post('updateLog_by');
        if($id_jenisHewan == null){
            $response = $this->JenisHewanModel->store($user);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function update_post($id_jenisHewan = null){
        $validation = $this->form_validation;
        $rule = $this->JenisHewanModel->rules();
        if($id_jenisHewan != null){
            array_push($rule,
                [
                    'field' => 'nama_jenisHewan',
                    'label' => 'nama_jenisHewan',
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
        $user = new JenisHewanData();
        $user->nama_jenisHewan = $this->post('nama_jenisHewan');
        $user->updateLog_by = $this->post('updateLog_by');
        if($id_jenisHewan != null){
            $response = $this->JenisHewanModel->update($user,$id_jenisHewan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    // public function index_delete($id_jenisHewan = null){
    //     if($id_jenisHewan == null){
    //         return $this->returnData('Parameter Id Tidak Ditemukan', true);
    //     }
    //     $response = $this->JenisHewanModel->destroy($id_jenisHewan);
    //     return $this->returnData($response['msg'], $response['error']);
    // }

    public function delete_post($id_jenisHewan = null){
        // $validation = $this->form_validation;
        // $rule = $this->JenisHewanModel->rules();
        // if($id_jenisHewan != null){
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
        $user = new JenisHewanData();
        // $user->updateLog_by = $this->post('updateLog_by');
        if($id_jenisHewan != null){
            $response = $this->JenisHewanModel->softDelete($user,$id_jenisHewan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}

Class JenisHewanData{
    public $id_jenisHewan;
    public $nama_jenisHewan;
    public $updateLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;
    public $aktif;
}