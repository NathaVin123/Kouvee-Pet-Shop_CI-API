<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class HewanModel extends CI_Model
{
    private $table = 'hewans';

    public $id_hewan;
    public $nama_hewan;
    public $tglLahir_hewan;
    public $id_customer;
    public $id_jenisHewan;
    public $updateLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;
    public $aktif;

    public $rule = [];

    public function Rules() { return $this->rule; }

    public function getAllAktif() {
        $this->db->select('id_hewan, hewans.id_customer, customers.nama_customer "nama_customer", customers.alamat_customer "alamat_customer", 
                        customers.tglLahir_customer "tglLahir_customer", customers.noTelp_customer "noTelp_customer",
                        hewans.id_jenisHewan, jenishewans.nama_jenisHewan "nama_jenisHewan", hewans.nama_hewan "nama_hewan", hewans.tglLahir_hewan "tglLahir_hewan", 
                        hewans.createLog_at, hewans.createLog_by, hewans.updateLog_at, hewans.deleteLog_at, hewans.aktif');
        $this->db->from('hewans');
        $this->db->join('customers', 'hewans.id_customer = customers.id_customers');
        $this->db->join('jenis_hewan', 'hewans.id_jenisHewan = jenis_hewan.id_jenis_hewan');
        $this->db->where('hewans.aktif',1);
        $this->db->order_by('hewans.id_hewan ASC');
        return $this->db->get()->result();
    }

    public function store($request){
        $this->nama_hewan = $request->nama_hewan;
        $this->tglLahir_hewan = $request->tglLahir_hewan;
        $this->id_customer = $request->id_customer;
        $this->id_jenisHewan = $request->id_jenisHewan;
        $this->aktif=1;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_hewan){
        $updateData = 
        ['nama_hewan' => $request->nama_hewan, 
         'tglLahir_hewan' => $request->tglLahir_hewan, 
         'id_customer' => $request->id_customer, 
         'id_jenisHewan' => $request->id_jenisHewan,
         'updateLog_by' => $request->updateLog_by,
         'updateLog_at' => date('Y-m-d H:i:s')
        ];
        if($this->db->where('id_hewan', $id_hewan)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function softDelete($request, $id_hewan){
        $updateData = [
            'aktif' => 0,
            'delete_at' => date('Y-m-d H:i:s')
        ];
        if($this->db->where('id_hewan',$id_hewan)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    // public function destroy($id_hewan){
    //     if(empty($this->db->select('*')->where(array('id_hewan' => $id_hewan))->get($this->table)->row()))
    //         return ['msg' => 'Id tidak ditemukan', 'error' => true];

    //     if($this->db->delete($this->table, array('id_hewan' => $id_hewan))){
    //         return ['msg' => 'Berhasil', 'error' => false];
    //     }
    //     return ['msg' => 'Gagal', 'error' => true];
    // }
}
?>