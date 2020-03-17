<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends CI_Model
{
    private $table = 'suppliers';

    public $id_supplier;
    public $id_pegawai_fk;
    public $id_stock_fk;
    public $nama_supplier;
    public $alamat_supplier;
    public $telepon_supplier;
    public $stok_supplier;
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
        return $this->db->get('suppliers')->result();
    }

    public function store($request){
        $this->nama_supplier = $request->nama_supplier;
        $this->alamat_supplier = $request->alamat_supplier;
        $this->telepon_supplier = $request->telepon_supplier;
        $this->stok_supplier = $request->stok_supplier;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_supplier){
        $updateData = ['nama_supplier' => $request->nama_supplier, 'alamat_supplier' => $request->alamat_supplier, 'telepon_supplier' => $request->telepon_supplier, 'stok_supplier' => $request->stok_supplier];
        if($this->db->where('id_produk', $id_produk)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function destroy($id_supplier){
        if(empty($this->db->select('*')->where(array('id_supplier' => $id_supplier))->get($this->table)->row()))
            return ['msg' => 'Id tidak ditemukan', 'error' => true];

        if($this->db->delete($this->table, array('id_supplier' => $id_supplier))){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }
}
?>