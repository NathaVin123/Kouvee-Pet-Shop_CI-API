<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DetailProdukModel extends CI_Model
{
    private $table = 'detailproduks';

    public $kode_penjualan;
    public $id_produk;
    public $tgl_transaksi_produk;
    public $jml_transaksi_produk;
    public $subtotal;

    public $rule = [
        [
            'field' => 'kode_penjualan',
            'label' => 'kode_penjualan',
            'rules' => 'required'
        ],
        [
            'field' => 'id_produk',
            'label' => 'id_produk',
            'rules' => 'required'
        ],
        [
            'field' => 'tgl_transaksi_produk',
            'label' => 'tgl_transaksi_produk',
            'rules' => 'required'
        ],
        [
            'field' => 'jml_transaksi_produk',
            'label' => 'jml_transaksi_produk',
            'rules' => 'required'
        ],
        [
            'field' => 'subtotal',
            'label' => 'subtotal',
            'rules' => 'required'
        ]
    ];

    public function Rules() { return $this->rule; }

    public function getAll() {
        return $this->db->get('detailproduks')->result();
    }

    public function store($request){
        $this->kode_penjualan = $request->kode_penjualan;
        $this->id_produk = $request->id_produk;
        $this->tgl_transaksi_produk = $request->tgl_transaksi_produk;
        $this->jml_transaksi_produk = $request->jml_transaksi_produk;
        $this->subtotal = $request->subtotal;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $kode_penjualan){
        $updateData = 
        ['kode_penjualan' => $request->kode_penjualan, 
        'id_produk' => $request->id_produk, 
        'tgl_transaksi_produk' => $request->tgl_transaksi_produk, 
        'jml_transaksi_produk' => $request->jml_transaksi_produk, 
        'subtotal' => $request->subtotal];
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