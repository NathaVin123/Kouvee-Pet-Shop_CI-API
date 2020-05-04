<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class TransaksiLayanan extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('DetailLayananModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->query('
        SELECT dl.kode_penjualan, 
        dl.id_layanan, 
        l.nama_layanan, 
        dl.tgl_transaksi_layanan, 
        tp.tgl_transaksi_penjualan, 
        dl.jml_transaksi_Layanan, 
        tp.nama_kasir, dl.subtotal , 
        tp.status_transaksi, 
        tp.status_pembayaran, 
        tp.id_customer, 
        c.nama_customer, 
        tp.id_CS, 
        tp.id_Kasir, 
        tp.total 
        FROM detaillayanans dl 
        JOIN transaksipenjualans tp 
        ON dl.kode_penjualan = tp.kode_penjualan 
        JOIN layanans l 
        ON dl.id_layanan = l.id_layanan 
        JOIN customers c 
        ON tp.id_customer = c.id_customer'
        )->result(), false);
    }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}
