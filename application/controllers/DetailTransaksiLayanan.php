<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class DetailTransaksiLayanan extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('DetailTransaksiLayananModel');
        $this->load->library('form_validation');
    }

    public function getWithJoin_get() {
        $this->db->select('id_detaillayanan, detailtransaksilayanans.kode_penjualan_layanan,
                        detailtransaksilayanans.id_layananHarga, layananhargas.id_layanan, layananhargas.id_ukuranHewan,
                        layanans.nama_layanan "nama_layanan", ukuranhewans.nama_ukuranHewan "nama_ukuranHewan", layananhargas.harga, 
                        detailtransaksilayanans.jml_transaksi_layanan,
                        detailtransaksilayanans.total_harga');
        $this->db->from('detailtransaksilayanans');
        $this->db->join('layananhargas', 'detailtransaksilayanans.id_layananHarga = layananhargas.id_layananHarga');
        $this->db->join('layanans', 'layananhargas.id_layanan = layanans.id_layanan');
        $this->db->join('ukuranhewans', 'layananhargas.id_ukuranHewan = ukuranhewans.id_ukuranHewan');
        $this->db->order_by('detailtransaksilayanans.id_detaillayanan ASC');
        return $this->returnData($this->db->get()->result(), false);
    }

    public function index_get(){
        return $this->returnData($this->db->get('detailtransaksilayanans')->result(), false);
    }

    public function getByTransactionId_get($id_detaillayanan=null){
        if($id_detaillayanan == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        return $this->returnData($this->db->get_where('detailtransaksilayanans', ["kode_penjualan_layanan" => $id_detaillayanan])->result(), false);
    }

    public function search_get($id_detaillayanan=null){
        if($id_detaillayanan == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        return $this->returnData($this->db->get_where('detailtransaksilayanans', ["id_detaillayanan" => $id_detaillayanan])->row(), false);
    }

    public function index_post($kode_penjualan = null){
        $validation = $this->form_validation;
        $rule = $this->DetailTransaksiLayananModel->rules();
        array_push($rule,
            [
                'field' => 'kode_penjualan_layanan',
                'label' => 'kode_penjualan_layanan',
                'rules' => 'required'
            ],
            [
                'field' => 'id_layananHarga',
                'label' => 'id_layananHarga',
                'rules' => 'required'
            ],
            [
                'field' => 'jml_transaksi_layanan',
                'label' => 'jml_transaksi_layanan',
                'rules' => 'required'
            ]
        );
        $validation->set_rules($rule);
        if(!$validation->run()){
            return $this->returnData($this->form_validation->error_array(), true);
        }
        $user = new DetailTransaksiLayananData();
        $user->kode_penjualan_layanan = $this->post('kode_penjualan_layanan');
        $user->id_layananHarga = $this->post('id_layananHarga');
        $user->jml_transaksi_layanan = $this->post('jml_transaksi_layanan');
        $user->total_harga = $this->post('total_harga');
        if($kode_penjualan_layanan == null){
            $response = $this->DetailTransaksiLayananModel->store($user);
        }
        else{
            $response = $this->DetailTransaksiLayananModel->update($user, $kode_penjualan_layanan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function insertMultiple_post(){
        $data = $this->post('detailtransaksilayanans');
        //if($id == null){
        $response = $this->DetailTransaksiLayananModel->storeMultiple($data);
        //}
        return $this->returnData($response['msg'], $response['error']);
    }

    public function update_post($id_detaillayanan = null){
        $validation = $this->form_validation;
        $rule = $this->DetailTransaksiLayananModel->rules();
        array_push($rule,
            [
                'field' => 'id_layananHarga',
                'label' => 'id_layananHarga',
                'rules' => 'required'
            ],
            [
                'field' => 'jml_transaksi_layanan',
                'label' => 'jml_transaksi_layanan',
                'rules' => 'required'
            ]
        );
        $validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }

        $transaksi = new DetailTransaksiLayananData();
        $transaksi->id_harga_layanan = $this->post('id_harga_layanan');
        $transaksi->jml_transaksi_layanan = $this->post('jml_transaksi_layanan');
        $transaksi->total_harga = $this->post('total_harga');
        if($id_detaillayanan == null){
            return $this->returnData('Parameter ID tidak ditemukan', true);
        }
        $response = $this->DetailTransaksiLayananModel->update($transaksi,$id_detaillayanan);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function updateMultiple_post(){
        $data = $this->post('detailtransaksilayanans');
        //if($id == null){
        $response = $this->DetailTransaksiLayananModel->updateMultiple($data);
        //}
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id_detaillayanan = null){
        if($id_detaillayanan == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->DetailTransaksiLayananModel->destroy($id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function deleteMultiple_post(){
        $data = $this->post('id_detaillayanan');
        //if($id == null){
        $response = $this->DetailTransaksiLayananModel->deleteMultiple($data);
        //}
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}

Class DetailTransaksiLayananData{
    public $kode_penjualan_layanan;
    public $id_layananHarga;
    public $jml_transaksi_layanan;
    public $total_harga;
}