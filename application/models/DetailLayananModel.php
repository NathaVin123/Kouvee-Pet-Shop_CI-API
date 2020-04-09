<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DetailLayananModel extends CI_Model
{
    private $table = 'detaillayanans';

    public $kode_penjualan;
    public $id_layanan;
    public $tgl_transaksi_layanan;
    public $jml_transaksi_layanan;
    public $subtotal;

    public $rule = [
        [
            'field' => 'kode_penjualan',
            'label' => 'kode_penjualan',
            'rules' => 'required'
        ],
        [
            'field' => 'id_layanan',
            'label' => 'id_layanan',
            'rules' => 'required'
        ],
        [
            'field' => 'tgl_transaksi_layanan',
            'label' => 'tgl_transaksi_layanan',
            'rules' => 'required'
        ],
        [
            'field' => 'jml_transaksi_layanan',
            'label' => 'jml_transaksi_layanan',
            'rules' => 'required'
        ],
        [
            'field' => 'subtotal',
            'label' => 'subtotal',
            'rules' => 'required'
        ]
    ];

    public function Rules() { return $this->rule; }

    // public function Rules() { return $this->rule; }

    public function getAll() {
        return $this->db->get('detaillayanans')->result();
    }

    public function store($request){
        $this->kode_penjualan = $request->kode_penjualan;
        $this->id_layanan = $request->id_layanan;
        $this->tgl_transaksi_layanan = $request->tgl_transaksi_layanan;
        $this->jml_transaksi_layanan = $request->jml_transaksi_layanan;
        $this->subtotal = $request->subtotal;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $kode_penjualan){
        $updateData = 
        ['kode_penjualan' => $request->kode_penjualan,
         'id_layanan' => $request->id_layanan, 
         'tgl_transaksi_layanan' => $request->tgl_transaksi_layanan, 
         'jml_transaksi_layanan' => $request->jml_transaksi_layanan, 
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