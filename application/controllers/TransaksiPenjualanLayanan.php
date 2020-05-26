<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class TransaksiPenjualanLayanan extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('TransaksiPenjualanLayananModel');
        $this->load->library('form_validation');
    }

    public function getWithJoin_get() {
        $this->db->select('kode_penjualan_layanan, transaksipenjualanlayanans.id_hewan, hewans.nama "nama_hewan", hewans.id_jenisHewan, jenishewans.nama_jenisHewan "nama_jenisHewan", hewans.id_customer, customer.nama_customer "nama_customer",
                        customers.noTelp_customer "noTelp_customer",
                        transaksipenjualanlayanans.subtotal, transaksipenjualanlayanans.diskon, transaksipenjualanlayanans.total, transaksipenjualanlayanans.proses, transaksipenjualanlayanans.status_transaksi,
                        transaksipenjualanlayanans.tanggal_lunas, transaksipenjualanlayanans.createLog_at,
                        transaksipenjualanlayanans.updateLog_at');
        $this->db->from('transaksipenjualanlayanans');
        $this->db->join('hewans', 'transaksipenjualanlayanans.id_hewan = hewans.id_hewan', 'left outer');
        $this->db->join('jenishewans', 'hewans.id_jenisHewan = jenishewans.id_jenisHewan', 'left');
        $this->db->join('customers', 'hewans.id_pelanggan = customers.id_pelanggan', 'left');
        $this->db->order_by('transaksipenjualanlayanans.kode_penjualan_layanan ASC');
        return $this->returnData($this->db->get()->result(), false);
    }

    public function index_get(){
        return $this->returnData($this->db->get('transaksipenjualanlayanans')->result(), false);
    }

    public function onProgress_get(){
        return $this->returnData($this->db->get_where('transaksipenjualanlayanans', ["proses" => 'Sedang Diproses'])->result(), false);
    }

    public function progressDone_get(){
        return $this->returnData($this->db->get_where('transaksipenjualanlayanans', ["proses" => 'Layanan Selesai'])->result(), false);
    }

    public function progressDoneAndWaitingPayment_get(){
        return $this->returnData($this->db->get_where('transaksipenjualanlayanans', ["proses" => 'Layanan Selesai', "status_transaksi" => 'Menunggu Pembayaran'])->result(), false);
    }

    public function waitingPayment_get(){
        return $this->returnData($this->db->get_where('transaksipenjualanlayanans', ["status_transaksi" => 'Menunggu Pembayaran'])->result(), false);
    }

    public function paidOff_get(){
        return $this->returnData($this->db->get_where('transaksipenjualanlayanans', ["status_transaksi" => 'Lunas'])->result(), false);
    }

    public function search_get($kode_penjualan_layanan = null){
        return $this->returnData($this->db->get_where('transaksipenjualanlayanans', ["kode_penjualan_layanan" => $kode_penjualan_layanan])->row(), false);
    }

    public function index_post(){
        $validation = $this->form_validation;
        $rule = $this->TransaksiPenjualanLayananModel->rules();
        array_push($rule,
            [
                'field' => 'id_cs',
                'label' => 'id_cs',
                'rules' => 'required'
            ]
        );
        $validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }

        $user = new TransaksiPenjualanLayananData();
        $user->id_cs = $this->post('id_cs');
        $user->id_hewan = $this->post('id_hewan');
        $user->diskon = $this->post('diskon');
        $user->total = $this->post('total');

        $response = $this->TransaksiPenjualanLayananModel->store($user);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function insertAndGet_post(){
        $validation = $this->form_validation;
        $rule = $this->TransaksiPenjualanLayananModel->rules();
        array_push($rule,
            [
                'field' => 'id_cs',
                'label' => 'id_cs',
                'rules' => 'required'
            ]
        );
        $validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }

        $user = new TransaksiPenjualanLayananData();
        $user->id_cs = $this->post('id_cs');
        $user->id_hewan = $this->post('id_hewan');
        $user->diskon = $this->post('diskon');
        $user->total = $this->post('total');

        $response = $this->TransaksiPenjualanLayananModel->storeReturnObject($user);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function update_post($kode_penjualan_layanan = null){
        $validation = $this->form_validation;
        $rule = $this->TransaksiPenjualanLayananModel->rules();
        $validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }

        $user = new TransaksiPenjualanLayananData();
        $user->id_hewan = $this->post('id_hewan');
        $user->subtotal = $this->post('subtotal');
        $user->diskon = $this->post('diskon');
        $user->total = $this->post('total');
        if($kode_penjualan_layanan == null){
            return $this->returnData('Parameter ID tidak ditemukan', true);
        }
        $response = $this->TransaksiPenjualanLayananModel->update($user,$kode_penjualan_layanan);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function updateProgress_post($kode_penjualan_layanan = null){
        $validation = $this->form_validation;
        $rule = $this->TransaksiPenjualanLayananModel->rules();
        $validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }

        $user = new TransaksiPenjualanLayananData();
        if($kode_penjualan_layanan == null){
            return $this->returnData('Parameter ID tidak ditemukan', true);
        }
        $response = $this->TransaksiPenjualanLayananModel->updateProgress($user,$kode_penjualan_layanan);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function updateStatus_post($kode_penjualan_layanan = null){
        $validation = $this->form_validation;
        $rule = $this->TransaksiPenjualanLayananModel->rules();
        array_push($rule,
            [
                'field' => 'id_kasir',
                'label' => 'id_kasir',
                'rules' => 'required'
            ]          
        );
        $validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }

        $user = new TransaksiLayananData();
        $user->id_kasir = $this->post('id_kasir');
        if($kode_penjualan_layanan == null){
            return $this->returnData('Parameter ID tidak ditemukan', true);
        }
        $response = $this->TransaksiPenjualanLayananModel->updateStatus($user,$kode_penjualan_layanan);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($kode_penjualan_layanan = null){
        if($kode_penjualan_layanan == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->TransaksiLayananModel->destroy($kode_penjualan_layanan);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}

Class TransaksiPenjualanLayananData{
    public $id_hewan;
    public $id_cs;
    public $id_kasir;
    public $subtotal;
    public $diskon;
    public $total;
    public $proses;
    public $status_transaksi;
    public $tanggal_lunas;
    public $createLog_at;
    public $updateLog_at;
}
