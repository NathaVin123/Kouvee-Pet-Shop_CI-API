<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class TransaksiProduk extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('DetailProdukModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->query('
        SELECT dp.kode_penjualan, 
        dp.id_produk, 
        p.nama_produk, 
        dp.tgl_transaksi_produk, 
        tp.tgl_transaksi_penjualan, 
        dp.jml_transaksi_produk, 
        tp.nama_kasir, dp.subtotal , 
        tp.status_transaksi, 
        tp.status_pembayaran, 
        tp.id_customer, 
        c.nama_customer, 
        tp.id_CS, 
        tp.id_Kasir, 
        tp.total 
        FROM detailproduks dp 
        JOIN transaksipenjualans tp 
        ON dp.kode_penjualan = tp.kode_penjualan 
        JOIN produks p 
        ON dp.id_produk = p.id_produk 
        JOIN customers c 
        ON tp.id_customer = c.id_customer
        ')->result(), false);
    }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}