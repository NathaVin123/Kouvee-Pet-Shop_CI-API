<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TransaksiPenjualanModel extends CI_Model
{
    private $table = 'transaksipenjualans';

    public $kode_penjualan;
    public $tgl_transaksi_penjualan;
    public $nama_kasir;
    public $total;
    public $status_transaksi;
    public $status_pembayaran;
    public $id_customer;
    public $id_CS;
    public $id_kasir;
    public $createLog_at  = '';
    public $updateLog_at  = '';

    public $rule = [
        [
            'field' => 'kode_penjualan',
            'label' => 'kode_penjualan',
            'rules' => 'required'
        ],
        [
            'field' => 'tgl_transaksi_penjualan',
            'label' => 'tgl_transaksi_penjualan',
            'rules' => 'required'
        ],
        [
            'field' => 'nama_kasir',
            'label' => 'nama_kasir',
            'rules' => 'required'
        ],
        [
            'field' => 'total',
            'label' => 'total',
            'rules' => 'required'
        ],
        [
            'field' => 'status_transaksi',
            'label' => 'status_transaksi',
            'rules' => 'required'
        ],
        [
            'field' => 'status_pembayaran',
            'label' => 'status_pembayaran',
            'rules' => 'required'
        ],
        [
            'field' => 'id_customer',
            'label' => 'id_customer',
            'rules' => 'required'
        ],
        [
            'field' => 'id_CS',
            'label' => 'id_CS',
            'rules' => 'required'
        ],
        [
            'field' => 'id_kasir',
            'label' => 'id_kasir',
            'rules' => 'required'
        ]
    ];

    public function Rules() { return $this->rule; }

    public function getAll() {
        return $this->db->get('transaksipenjualans')->result();
    }

    public function store($request){
        $this->kode_penjualan = $request->kode_penjualan;
        $this->tgl_transaksi_penjualan = $request->tgl_transaksi_penjualan;
        $this->nama_kasir = $request->nama_kasir;
        $this->total = $request->total;
        $this->status_transaksi = $request->status_transaksi;
        $this->status_pembayaran = $request->status_pembayaran;
        $this->id_customer = $request->id_customer;
        $this->id_CS = $request->id_CS;
        $this->id_kasir = $request->id_kasir;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $kode_penjualan){
        $updateData = 
        ['kode_penjualan' => $request->kode_penjualan, 
        'tgl_transaksi_penjualan' => $request->tgl_transaksi_penjualan, 
        'nama_kasir' => $request->nama_kasir, 
        'total' => $request->total, 
        'status_transaksi' => $request->status_transaksi, 
        'status_pembayaran' => $request->status_pembayaran, 
        'id_customer' => $request->id_customer,
        'id_CS' => $request->id_CS,
        'id_kasir' => $request->id_kasir,];
        if($this->db->where('kode_penjualan', $kode_penjualan)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function destroy($kode_penjualan){
        if(empty($this->db->select('*')->where(array('kode_penjualan' => $kode_penjualan))->get($this->table)->row()))
            return ['msg' => 'Id tidak ditemukan', 'error' => true];

        if($this->db->delete($this->table, array('kode_penjualan' => $kode_penjualan))){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }
}
?>