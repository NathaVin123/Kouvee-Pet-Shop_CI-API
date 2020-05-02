<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SupplierModel extends CI_Model
{
    private $table = 'suppliers';

    public $id_supplier;
    public $nama_supplier;
    public $alamat_supplier;
    public $telepon_supplier;
    public $stok_supplier;
    public $updateLog_by;
    public $createLog_at  = '';
    public $updateLog_at  = '';
    public $deleteLog_at  = '';

    public $rule = [
        // [
        //     'field' => 'id_supplier',
        //     'label' => 'id_supplier',
        //     'rules' => 'required'
        // ],
        [
            'field' => 'nama_supplier',
            'label' => 'nama_supplier',
            'rules' => 'required'
        ],
        [
            'field' => 'alamat_supplier',
            'label' => 'alamat_supplier',
            'rules' => 'required'
        ],
        [
            'field' => 'telepon_supplier',
            'label' => 'telepon_supplier',
            'rules' => 'required'
        ],
        [
            'field' => 'stok_supplier',
            'label' => 'stok_supplier',
            'rules' => 'required'
        ],
        [
            'field' => 'updateLog_by',
            'label' => 'updateLog_by',
            'rules' => 'required'
        ],
    ];

    public function Rules() { return $this->rule; }

    public function getAll() {
        return $this->db->get('suppliers')->result();
    }

    public function store($request){
        // $this->id_supplier = $request->id_supplier;
        $this->nama_supplier = $request->nama_supplier;
        $this->alamat_supplier = $request->alamat_supplier;
        $this->telepon_supplier = $request->telepon_supplier;
        $this->stok_supplier = $request->stok_supplier;
        $this->updateLog_by = $request->updateLog_by;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_supplier){
        $updateData = 
        ['id_supplier' => $request->id_supplier, 
        'nama_supplier' => $request->nama_supplier, 
        'alamat_supplier' => $request->alamat_supplier, 
        'telepon_supplier' => $request->telepon_supplier, 
        'stok_supplier' => $request->stok_supplier, 
        'updateLog_by' => $request->updateLog_by];
        if($this->db->where('id_supplier', $id_supplier)->update($this->table, $updateData)){
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