<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class Hewan extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('HewanModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->get_where('hewans', ["aktif" => 1])->result(), false);
    }

    public function nonAktif_get(){
        return $this->returnData($this->db->get_where('hewans', ["aktif" => 0])->result(), false);
    }

    public function all_get(){
        return $this->returnData($this->db->get('hewans')->result(), false);
    }

    public function getWithJoin_get(){
        $this->db->select('id_hewan, hewans.id_customer, customers.nama_customer "nama_customer", customers.alamat_customer "alamat_customer", 
                        customers.tglLahir_customer "tglLahir_customer", customers.noTelp_customer "noTelp_customer",
                        hewans.id_jenisHewan, jenishewans.nama_jenisHewan "nama_jenisHewan", hewans.nama_hewan "nama_hewan", hewans.tglLahir_hewan "tglLahir_hewan", 
                        hewans.createLog_at, hewans.updateLog_at, hewans.deleteLog_at, hewans.aktif');
        $this->db->from('hewans');
        $this->db->join('customers', 'hewans.id_customer = customers.id_customer');
        $this->db->join('jenishewans', 'hewans.id_jenisHewan = jenishewans.id_jenisHewan');
        $this->db->where('hewans.aktif',1);
        $this->db->order_by('hewans.id_hewan ASC');
        return $this->returnData($this->db->get()->result(), false);
    }

    public function getAllWithJoin_get(){
        $this->db->select('id_hewan, hewans.id_customer, customers.nama_customer "nama_customers", customers.alamat_customer "alamat_customer", 
                        customers.tglLahir_customer "tglLahir_customer", customers.noTelp_customer "noTelp_customer",
                        hewans.id_jenisHewan, jenishewans.nama_jenisHewan "nama_jenisHewan", hewans.nama_hewan "nama_hewan", hewans.tglLahir_hewan "tglLahir_hewan", 
                        hewans.createLog_at, hewans.updateLog_at, hewans.deleteLog_at, hewans.aktif');
        $this->db->from('hewans');
        $this->db->join('customers', 'hewans.id_customer = customers.id_customer');
        $this->db->join('jenishewans', 'hewans.id_jenisHewan = jenishewans.id_jenisHewan');
        $this->db->order_by('hewans.id_hewan ASC');
        return $this->returnData($this->db->get()->result(), false);
    }

    public function search_get($id_hewan){
        return $this->returnData($this->db->get_where('hewans', ["id_hewan" => $id_hewan])->row(), false);
    }

    public function index_post($id_hewan = null){
        $validation = $this->form_validation;
        $rule = $this->HewanModel->rules();
        if($id_hewan == null){
            array_push($rule,
                [
                    'field' => 'nama_hewan',
                    'label' => 'nama_hewan',
                    'rules' => 'required'
                ],
                [
                    'field' => 'tglLahir_hewan',
                    'label' => 'tglLahir_hewan',
                    'rules' => 'required'
                ],
                [
                    'field' => 'id_customer',
                    'label' => 'id_customer',
                    'rules' => 'required'
                ],
                [
                    'field' => 'id_jenisHewan',
                    'label' => 'id_jenisHewan',
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
        $user = new HewanData();
        $user->nama_hewan = $this->post('nama_hewan');
        $user->id_jenisHewan = $this->post('id_jenisHewan');
        $user->tglLahir_hewan = $this->post('tglLahir_hewan');
        $user->id_customer = $this->post('id_customer');
        $user->updateLog_by = $this->post('updateLog_by');
        if($id_hewan == null){
            $response = $this->HewanModel->store($user);
        }
        // else{
        //     $response = $this->HewanModel->update($user, $id_hewan);
        // }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function update_post($id_hewan = null){
        $validation = $this->form_validation;
        $rule = $this->HewanModel->rules();
        if($id_hewan != null){
            array_push($rule,
            [
                'field' => 'nama_hewan',
                'label' => 'nama_hewan',
                'rules' => 'required'
            ],
            [
                'field' => 'tglLahirHewan',
                'label' => 'tglLahirHewan',
                'rules' => 'required'
            ],
            [
                'field' => 'id_customer',
                'label' => 'id_customer',
                'rules' => 'required'
            ],
            [
                'field' => 'id_jenisHewan',
                'label' => 'id_jenisHewan',
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
        $user = new userData();
        $user->nama_hewan = $this->post('nama_hewan');
        $user->tglLahir_hewan = $this->post('tglLahir_hewan');
        $user->id_customer = $this->post('id_customer');
        $user->id_jenisHewan = $this->post('id_jenisHewan');
        $user->updateLog_by = $this->post('updateLog_by');
        if($id_hewan != null){
            $response = $this->userModel->update($user,$id_hewan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function delete_post($id_hewan = null){
        // $validation = $this->form_validation;
        // $rule = $this->HewanModel->rules();
        // if($id_hewan != null){
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
        $user = new HewanData();
        // $user->updateLog_by = $this->post('updateLog_by');
        if($id_hewan != null){
            $response = $this->HewanModel->softDelete($user,$id_hewan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    // public function index_delete($id_hewan = null){
    //     if($id_hewan == null){
    //         return $this->returnData('Parameter Id Tidak Ditemukan', true);
    //     }
    //     $response = $this->HewanModel->destroy($id_hewan);
    //     return $this->returnData($response['msg'], $response['error']);
    // }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}

Class HewanData{
    public $id_hewan;
    public $nama_hewan;
    public $tglLahir_hewan;
    public $id_customer;
    public $id_jenisHewan;
    public $updateLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;
    public $aktif;
}