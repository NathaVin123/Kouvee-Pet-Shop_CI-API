<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class LayananHarga extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('LayananHargaModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->get_where('layananhargas', ["aktif" => 1])->result(), false);
    }

    public function nonAktif_get(){
        return $this->returnData($this->db->get_where('layananhargas', ["aktif" => 0])->result(), false);
    }

    public function all_get(){
        return $this->returnData($this->db->get('layananhargas')->result(), false);
    }

    public function getWithJoin_get(){
        $this->db->select('id_layananHarga, layananhargas.id_layanan, layanans.nama_layanan "nama_layanan", layananhargas.id_ukuranHewan, 
                        ukuranhewans.nama_ukuranHewan "nama_ukuranHewan", layananhargas.harga,
                        layananhargas.createLog_at, layananhargas.updateLog_at, layananhargas.updateLog_by,
                        layananhargas.deleteLog_at, layananhargas.aktif');
        $this->db->from('layananhargas');
        $this->db->join('layanans', 'layananhargas.id_layanan = layanans.id_layanan');
        $this->db->join('ukuranhewans', 'layananhargas.id_ukuranHewan = ukuranhewans.id_ukuranHewan');
        $this->db->where('layananhargas.aktif',1);
        $this->db->order_by('layananhargas.id_layananHarga ASC');
        //return $this->db->get()->result();
        //return $this->returnData($this->db->get_where('layanan', ["aktif" => 1])->result(), false);
        return $this->returnData($this->db->get()->result(), false);
    }

    public function getAllWithJoin_get(){
        $this->db->select('id_layananHarga, layananhargas.id_layanan, layanans.nama_layanan "nama_layanan", layananhargas.id_ukuranHewan, 
                        ukuranhewans.nama_ukuranHewan "nama_ukuranHewan", layananhargas.harga,
                        layananhargas.createLog_at, layananhargas.updateLog_at, layananhargas.updateLog_by,
                        layananhargas.deleteLog_at, layananhargas.aktif');
        $this->db->from('layananhargas');
        $this->db->join('layanans', 'layananhargas.id_layanan = layanans.id_layanan');
        $this->db->join('ukuranhewans', 'layananhargas.id_ukuranHewan = ukuranhewans.id_ukuranHewan');
        $this->db->order_by('layananhargas.id_layananHarga ASC');
        return $this->returnData($this->db->get()->result(), false);
    }

    public function search_get($id_layananHarga){
        return $this->returnData($this->db->get_where('layananhargas', ["id_layananHarga" => $id_layananHarga])->row(), false);
    }

    public function searchByIdLayanan_get($id_layananHarga){
        return $this->returnData($this->db->get_where('layananhargas', ["id_layanan" => $id_layananHarga, "aktif" => 1])->result(), false);
    }

    public function index_post($id_layananHarga = null){
        $validation = $this->form_validation;
        $rule = $this->LayananHargaModel->rules();
        if($id_layananHarga == null){
            array_push($rule,
                [
                    'field' => 'id_layanan',
                    'label' => 'id_layanan',
                    'rules' => 'required'
                ],
                [
                    'field' => 'id_ukuranHewan',
                    'label' => 'id_ukuranHewan',
                    'rules' => 'required'
                ],
                [
                    'field' => 'harga',
                    'label' => 'harga',
                    'rules' => 'required|integer'
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
        $user = new LayananHargaData();
        $user->id_layanan = $this->post('id_layanan');
        $user->id_ukuran_hewan = $this->post('id_ukuran_hewan');
        $user->harga = $this->post('harga');
        $user->updateLog_by = $this->post('updateLog_by');

        if($id_layananHarga == null){
            $response = $this->LayananHargaModel->store($user);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function insertMultiple_post($id_layananHarga = null){
        $datahargalayanan = $this->post('layananhargas');
        if($id == null){
            $response = $this->HargaLayananModel->storeMultiple($datahargalayanan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function update_post($id_layananHarga = null){
        $validation = $this->form_validation;
        $rule = $this->LayananHargaModel->rules();
        if($id_layananHarga == null){
            array_push($rule,
                [
                    'field' => 'id_layanan',
                    'label' => 'id_layanan',
                    'rules' => 'required'
                ],
                [
                    'field' => 'id_ukuranHewan',
                    'label' => 'id_ukuranHewan',
                    'rules' => 'required'
                ],
                [
                    'field' => 'harga',
                    'label' => 'harga',
                    'rules' => 'required|integer'
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
        $user = new LayananHargaData();
        $user->id_layanan = $this->post('id_layanan');
        $user->id_ukuran_hewan = $this->post('id_ukuran_hewan');
        $user->harga = $this->post('harga');
        $user->updateLog_by = $this->post('updateLog_by');
        if($id_layananHarga != null){
            $response = $this->LayananHargaModel->update($user,$id_layananHarga);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function delete_post($id_layananHarga = null){
        $validation = $this->form_validation;
        $rule = $this->LayananHargaModel->rules();
        
        $validation->set_rules($rule);
        if (!$validation->run()) {
            return $this->returnData($this->form_validation->error_array(), true);
        }
        $user = new LayananHargaData();
        if($id_layananHarga != null){
            $response = $this->LayananHargaModel->softDelete($user,$id_layananHarga);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id_layananHarga = null){
        if($id_layananHarga == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->LayananHargaModel->destroy($id_layananHarga);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}

Class LayananHargaData{
    public $id_layananHarga;
    public $id_layanan;
    public $id_ukuranHewan;
    public $harga;
    public $updateLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;
    public $aktif;
}