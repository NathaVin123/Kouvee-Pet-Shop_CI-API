<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SupplierModel extends CI_Model
{
    private $table = 'suppliers';

    public $id_supplier;
    public $nama_supplier;
    public $alamat_supplier;
    public $telepon_supplier;
    public $updateLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;
    public $aktif;

    public $rule = [];

    public function Rules() { return $this->rule; }

    public function getAllAktif() {
        return $this->db->get_where('suppliers', ["aktif" => 1])->result();
    }

    public function store($request){
        $this->nama_supplier = $request->nama_supplier;
        $this->alamat_supplier = $request->alamat_supplier;
        $this->telepon_supplier = $request->telepon_supplier;
        $this->updateLog_by = $request->updateLog_by;
        $this->aktif=1;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_supplier){
        $updateData = 
        ['nama_supplier' => $request->nama_supplier, 
        'alamat_supplier' => $request->alamat_supplier, 
        'telepon_supplier' => $request->telepon_supplier,  
        'updateLog_by' => $request->updateLog_by,
        'updateLog_at' => $request->updateLog_at];
        if($this->db->where('id_supplier', $id_supplier)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function softDelete($request, $id_supplier){
        $updateData = [
            'aktif' => 0,
            'deleteLog_at' => date('Y-m-d H:i:s')
        ];
        if($this->db->where('id_supplier',$id_supplier)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
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