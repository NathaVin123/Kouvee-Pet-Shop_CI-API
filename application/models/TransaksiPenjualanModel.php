<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TransaksiPenjualanModel extends CI_Model
{
    private $table = 'transaksipenjualans';

    public $id_transaksi_penjualan;
    public $id_pegawai_fk;
    public $id_hewan_fk;
    public $nama_kasir;
    public $subtotal;
    public $diskon;
    public $total;
    public $status_transaksi;
    public $createLog_by;
    public $updateLog_by;
    public $deleteLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;

    public $rule = [
        [
            'field' => 'title',
            'label' => 'title',
            'rules' => 'required'
        ],
    ];

    public function Rules() { return $this->rule; }

    public function getAll() {
        return $this->db->get('transaksipenjualans')->result();
    }

    public function store($request){
        $this->nama_kasir = $request->nama_kasir;
        $this->subtotal = $request->subtotal;
        $this->diskon = $request->diskon;
        $this->total = $request->total;
        $this->status_transaksi = $request->status_transaksi;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_transaksi_penjualan){
        $updateData = ['nama_kasir' => $request->nama_kasir, 'subtotal' => $request->subtotal, 'diskon' => $request->diskon, 'total' => $request->total, 'status_transaksi' => $request->status_transaksi];
        if($this->db->where('id_transaksi_penjualan', $id_transaksi_penjualan)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function destroy($id_transaksi_penjualan){
        if(empty($this->db->select('*')->where(array('id_transaksi_penjualan' => $id_transaksi_penjualan))->get($this->table)->row()))
            return ['msg' => 'Id tidak ditemukan', 'error' => true];

        if($this->db->delete($this->table, array('id_transaksi_penjualan' => $id_transaksi_penjualan))){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }
}
?>