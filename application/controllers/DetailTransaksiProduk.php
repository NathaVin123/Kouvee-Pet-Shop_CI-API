<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class DetailTransaksiProduk extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('DetailTransaksiProdukModel');
        $this->load->library('form_validation');
    }

    public function getWithJoin_get() {
        $this->db->select('id_detailproduk, detailtransaksiproduks.kode_penjualan_produk,
                        detailtransaksiproduks.id_produkHarga, produkhargas.id_produk, produkhargas.id_ukuranHewan,
                        produks.nama_produk "nama_produk", ukuran_hewan.nama_ukuranHewan "nama_ukuranHewan", produkhargas.harga, detailtransaksiproduks.jml_transaksi_produk,
                        detailtransaksiproduks.total_harga');
        $this->db->from('detailtransaksiproduks');
        $this->db->join('produkhargas', 'detailtransaksiproduks.id_produkHarga = produkhargas.id_produkHarga');
        $this->db->join('produks', 'produkhargas.id_produk = produks.id_produk');
        $this->db->join('ukuran_hewan', 'produkhargas.id_ukuranHewan = ukuran_hewan.id_ukuranHewan');
        $this->db->order_by('detailtransaksiproduks.id_detailproduk ASC');
        return $this->returnData($this->db->get()->result(), false);
    }

    public function index_get(){
        return $this->returnData($this->db->get('detailtransaksiproduks')->result(), false);
    }

    public function getByTransactionId_get($id_detailproduk=null){
        if($id_detailproduk == null){
			return $this->returnData('Parameter id_detailproduk Tidak Ditemukan', true);
        }
        return $this->returnData($this->db->get_where('detailtransaksiproduks', ["kode_penjualan_produk" => $id_detailproduk])->result(), false);
    }

    public function search_get($id_detailproduk=null){
        if($id_detailproduk == null){
			return $this->returnData('Parameter id_detailproduk Tidak Ditemukan', true);
        }
        return $this->returnData($this->db->get_where('detailtransaksiproduks', ["id_detailproduk" => $id_detailproduk])->row(), false);
    }

    public function index_post($id_detailproduk = null){
        $validation = $this->form_validation;
        $rule = $this->DetailTransaksiProdukModel->rules();
        array_push($rule,
            [
                'field' => 'kode_penjualan_produk',
                'label' => 'kode_penjualan_produk',
                'rules' => 'required'
            ],
            [
                'field' => 'id_produk',
                'label' => 'id_produk',
                'rules' => 'required'
            ],
            [
                'field' => 'jml_transaksi_produk',
                'label' => 'jml_transaksi_produk',
                'rules' => 'required'
            ]
        );
        $validation->set_rules($rule);
        if(!$validation->run()){
            return $this->returnData($this->form_validation->error_array(), true);
        }
        $user = new DetailTransaksiProdukData();
        $user->kode_penjualan_produk = $this->post('kode_penjualan_produk');
        $user->id_produk = $this->post('id_produk');
        $user->jml_transaksi_produk = $this->post('jml_transaksi_produk');
        $user->total_harga = $this->post('total_harga');
            
        $response = $this->DetailTransaksiProdukModel->store($user);
        
        return $this->returnData($response['msg'], $response['error']);
    }

    public function insertMultiple_post(){
        $data = $this->post('id_detailproduk');
        //if($id_detailproduk == null){
        $response = $this->DetailTransaksiProdukModel->storeMultiple($data);
        //}
        return $this->returnData($response['msg'], $response['error']);
    }

    public function update_post($id_detailproduk = null){
        $validation = $this->form_validation;
        $rule = $this->DetailTransaksiProdukModel->rules();
        array_push($rule,
            [
                'field' => 'id_produk',
                'label' => 'id_produk',
                'rules' => 'required'
            ],
            [
                'field' => 'jml_transaksi_produk',
                'label' => 'jml_transaksi_produk',
                'rules' => 'required'
            ]
        );
        $validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }

        $user = new DetailTransaksiProdukData();
        $user->id_produk = $this->post('id_produk');
        $user->jml_transaksi_produk = $this->post('jml_transaksi_produk');
        $user->total_harga = $this->post('total_harga');
        if($id_detailproduk == null){
            return $this->returnData('Parameter id_detailproduk tidak ditemukan', true);
        }
        $response = $this->DetailTransaksiProdukModel->update($user,$id_detailproduk);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function updateMultiple_post(){
        $data = $this->post('id_detailproduk');
        //if($id_detailproduk == null){
        $response = $this->DetailTransaksiProdukModel->updateMultiple($data);
        //}
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id_detailproduk = null){
        if($id_detailproduk == null){
			return $this->returnData('Parameter id_detailproduk Tidak Ditemukan', true);
        }
        $response = $this->DetailTransaksiProdukModel->destroy($id_detailproduk);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function deleteMultiple_post(){
        $data = $this->post('id_detailproduk');
        //if($id_detailproduk == null){
        $response = $this->DetailTransaksiProdukModel->deleteMultiple($data);
        //}
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}

Class DetailTransaksiProdukData{
    public $kode_penjualan_produk;
    public $id_produk;
    public $jml_transaksi_produk;
    public $total_harga;
}