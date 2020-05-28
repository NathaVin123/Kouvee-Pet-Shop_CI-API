<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class Pengadaan extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('PengadaanModel');
        $this->load->library('form_validation');
        $this->load->library('pdf');
        include_once APPPATH . '/third_party/fpdf/fpdf.php';
    }

    public function getPengadaanByMonth_get($param){
        $i = 1;
        $totalPengeluaran = 0;
        $produk = array();
        $cekNama = array();
        $bulan = explode("-", $param);
        $data = "SELECT pengadaans.no_order , pengadaans.total_harga  from pengadaans
        WHERE month(pengadaans.updateLog_at)=? AND year(pengadaans.updateLog_at)=? AND pengadaans.status_pengadaan = 'Pesanan Selesai'
        GROUP BY pengadaans.no_order";
        $hasil = $this->db->query($data,[$bulan[1],$bulan[0]])->result();
        $detailPengadaan = "SELECT produks.nama_produk, detailpengadaans.total_harga from detailpengadaans
                INNER JOIN produks USING(id_produk)
                WHERE detailpengadaans.no_order = ?
                GROUP BY produks.nama_produk";
        for($k = 0;$k <sizeof($hasil); $k++ ){
                $hasil2[$k] = $this->db->query($detailPengadaan,[$hasil[$k]->no_order])->result();
            }

        for($l = 0 ; $l < count($hasil2) ; $l++){
            for($m = 0 ; $m < count($hasil2) ; $m++){
                if(isset($hasil2[$l][$m])){

                    array_push($produk,$hasil2[$l][$m]); 
                }
                }
            }
        for($o = 0; $o<count($produk);$o++){
            for($p = $o +1; $p<count($produk); $p++){
                if($produk[$o]->nama_produk == $produk[$p]->nama_produk){
                    $produk[$o]->total_harga = $produk[$o]->total_harga + $produk[$p]->total_harga;
                    \array_splice($produk, $p, 1);
                }
            }
        }
        for($q = 0; $q< count($hasil); $q++){
            $totalPengeluaran = $totalPengeluaran + $hasil[$q]->total_harga;
        }
            print_r($produk);
            print_r($totalPengeluaran);
    }

    public function getWithJoin_get() {
        $this->db->select('pengadaans.no_order,pengadaans.id_supplier, pengadaans.total_harga, pengadaans.status_pengadaan,
                        pengadaans.createLog_at, pengadaans.updateLog_at, suppliers.nama_supplier "nama_supplier"');
        $this->db->from('pengadaans');
        $this->db->join('suppliers', 'pengadaans.id_supplier = suppliers.id_supplier');
        $this->db->order_by('pengadaans.no_order ASC');
        return $this->returnData($this->db->get()->result(), false);
    }

    public function index_get(){
        return $this->returnData($this->db->get('pengadaans')->result(), false);
    }

    public function unconfirmed_get(){
        return $this->returnData($this->db->get_where('pengadaans', ["status_pengadaan" => 'Belum Datang'])->result(), false);
    }

    public function confirmed_get(){
        return $this->returnData($this->db->get_where('pengadaans', ["status_pengadaan" => 'Pesanan Diproses'])->result(), false);
    }

    public function processed_get(){
        return $this->returnData($this->db->get_where('pengadaans', ["status_pengadaans" => 'Pesanan Diproses'])->result(), false);
    }

    public function completed_get(){
        return $this->returnData($this->db->get_where('pengadaans', ["status_pengadaans" => 'Pesanan Selesai'])->result(), false);
    }

    public function search_get($no_order = null){
        return $this->returnData($this->db->get_where('pengadaans', ["no_order" => $no_order])->row(), false);
    }

    public function getMonth_get(){
        return $this->returnData($this->db->get_where('pengadaans', [date('F',strtotime('createLog_at')) => date('F',strtotime(date('Y-m-d')))])->result(), false);
    }

    public function index_post(){
        $validation = $this->form_validation;
        $rule = $this->PengadaanModel->rules();
        array_push($rule,
            [
                'field' => 'id_supplier',
                'label' => 'id_supplier',
                'rules' => 'required'
            ]
            
        );
        $validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }

        $transaksi = new PengadaanData();
        $transaksi->id_supplier = $this->post('id_supplier');
        $transaksi->total_harga = $this->post('total_harga');

        $response = $this->PengadaanModel->store($transaksi);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function insertAndGet_post(){
        $validation = $this->form_validation;
        $rule = $this->PengadaanModel->rules();
        array_push($rule,
            [
                'field' => 'id_supplier',
                'label' => 'id_supplier',
                'rules' => 'required'
            ]
        );
        $validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }

        $transaksi = new PengadaanData();
        $transaksi->id_supplier = $this->post('id_supplier');
        $transaksi->total_harga = $this->post('total_harga');

        $response = $this->PengadaanModel->storeReturnObject($transaksi);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function update_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->PengadaanModel->rules();
        array_push($rule,
            [
                'field' => 'id_supplier',
                'label' => 'id_supplier',
                'rules' => 'required'
            ]
        );
        $validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }

        $transaksi = new PengadaanData();
        $transaksi->id_supplier = $this->post('id_supplier');
        $transaksi->total = $this->post('total');
        if($id == null){
            return $this->returnData('Parameter ID tidak ditemukan', true);
        }
        $response = $this->PengadaanModel->update($transaksi,$id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function updateStatusToProses_post($id = null){
        // $validation = $this->form_validation;
        // $rule = $this->PengadaanModel->rules();
        // $validation->set_rules($rule);
		// if (!$validation->run()) {
		// 	return $this->returnData($this->form_validation->error_array(), true);
        // }

        $transaksi = new PengadaanData();
        if($id == null){
            return $this->returnData('Parameter ID tidak ditemukan', true);
        }
        $response = $this->PengadaanModel->updateStatusToProses($transaksi,$id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function updateStatusToSelesai_post($id = null){
        // $validation = $this->form_validation;
        // $rule = $this->PengadaanModel->rules();
        // $validation->set_rules($rule);
		// if (!$validation->run()) {
		// 	return $this->returnData($this->form_validation->error_array(), true);
        // }

        $transaksi = new PengadaanData();
        // $transaksi->modified_by = $this->post('modified_by');
        if($id == null){
            return $this->returnData('Parameter ID tidak ditemukan', true);
        }
        $response = $this->PengadaanModel->updateStatusToSelesai($transaksi,$id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id = null){
        if($id == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->PengadaanModel->destroy($id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }

    function cetakStruk_get($no_order = null){
        // $this->load->helper('directory'); //load directory helper
        $dir = "controllers/PDF/"; // Your Path to folder
        // $map = directory_map($dir); /* This function reads the directory path specified in the first parameter and builds an array representation of it and all its contained files. */
        $pdf = new FPDF('p','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();
    
        // $dataTransaksi = null;
        // $dataDetailTransaksi = null;
        $pengadaan_produk_data = null;
        $detail_pengadaan_data = null;

        $this->db->select('pengadaans.no_order, pengadaans.id_supplier, 
                        suppliers.nama_supplier "nama_supplier", suppliers.alamat_supplier "alamat_supplier", suppliers.telepon_supplier "telepon_supplier", 
                        pengadaans.total_harga, pengadaans.status_pengadaan,
                        pengadaans.createLog_at, pengadaans.updateLog_at');
        $this->db->from('pengadaans');
        $this->db->join('suppliers', 'pengadaans.id_supplier = suppliers.id_supplier', 'left');
        $this->db->where('no_order',$no_order);
        $resultTransaksi = $this->db->get();

        if($resultTransaksi->num_rows()!=0){
            $pengadaan_produk_data = $resultTransaksi->row();

            $this->db->select('detailpengadaans.id_detailpengadaan, detailpengadaans.no_order, detailpengadaans.id_produk, 
                            produks.nama_produk "nama_produk", produks.satuan_produk "satuan_produk", 
                            detailpengadaans.jumlah_stok_pengadaan, detailpengadaans.harga, detailpengadaans.total_harga, 
                            detailpengadaans.createLog_at, detailpengadaans.updateLog_at');
            $this->db->from('detailpengadaans');
            $this->db->join('produks','detailpengadaans.id_produk = produks.id_produk', 'left');
            $this->db->where('no_order',$no_order);
            $detail_pengadaan_data = $this->db->get()->result();
        }else{
            $this->returnData("Nomor Order tidak ditemukan!",true);
        }

        $month_name = array("Januari", "Februari", "Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
        $tanggal_dibuat = date("j",strtotime($pengadaan_produk_data->createLog_at))." ".
                            $month_name[date("n",strtotime($pengadaan_produk_data->createLog_at))-1]." ".
                            date("Y",strtotime($pengadaan_produk_data->createLog_at));
        $tanggal_cetak = date("j")." ".$month_name[date("n")-1]." ".date("Y");

        $pdf->Image(APPPATH.'controllers/PDF/Logo/kouvee.png',10,10,-200);
        $pdf->Cell(10,50,'',0,1);// Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(70);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(50,7,'Surat Pemesanan',0,1,'C');
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(190,8,'NO : '.$pengadaan_produk_data->no_order,0,1, 'R');
        $pdf->Cell(190,8,'Tanggal : '.$tanggal_dibuat,0,0, 'R');
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(10,10,'',0,1);
        $pdf->Cell(45,6,'Kepada Yth :',0,1);
        $pdf->Cell(45,6,$pengadaan_produk_data->nama_supplier,0,1);
        $pdf->Cell(45,6,$pengadaan_produk_data->alamat_supplier,0,1);
        $pdf->Cell(45,6,$pengadaan_produk_data->telepon_supplier,0,1);
        $pdf->Cell(10,10,'',0,1);
        //$pdf->Cell(70,10);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(50,7,'Mohon untuk disediakan produk-produk berikut ini :',0,1);
        $pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,6,'NO',1,0,'C');
        $pdf->Cell(60,6,'NAMA PRODUK',1,0,'C');
        $pdf->Cell(25,6,'SATUAN',1,0,'C');
        $pdf->Cell(35,6,'HARGA',1,0,'C');
        $pdf->Cell(20,6,'JUMLAH',1,0,'C');
        $pdf->Cell(40,6,'TOTAL HARGA',1,1,'C');
        $pdf->SetFont('Arial','',10);
        $i = 1;
        foreach ($detail_pengadaan_data as $item){    
            $pdf->Cell(10,10,$i,1,0,'C');
            $pdf->Cell(60,10,$item->nama_produk,1,0,'L');
            $pdf->Cell(25,10,$item->satuan_produk,1,0,'C');
            $pdf->Cell(35,10,'Rp. '.$item->harga,1,0,'C');
            $pdf->Cell(20,10,$item->jumlah_stok_pengadaan,1,0,'C');
            $pdf->Cell(40,10,'Rp. '.$item->total_harga,1,1,'C');
            $i++;
        }
        $pdf->Cell(10,10,'',0,1);
        $pdf->SetFont('Arial','B',13);
        $pdf->Cell(65,10,'Total Biaya Dikeluarkan: Rp. '.$pengadaan_produk_data->total_harga,0,1);
        $pdf->Cell(10,20,'',0,1);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(190,7,'Dicetak tanggal '.$tanggal_cetak,0,1,'R');
        $pdf->Output($pengadaan_produk_data->no_order.'.pdf','D');
    }
}

Class PengadaanData{
    public $no_order;
    public $id_supplier;
    public $status_pengadaan;
    public $total_harga;
    public $createLog_at;
    public $updateLog_at;
}