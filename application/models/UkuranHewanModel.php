<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class UkuranHewanModel extends CI_Model
{
    private $table = 'ukuranhewans';

    public $id_ukuranHewan;
    public $nama_ukuranHewan;
    public $updateLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;
    public $aktif;

    public $rule = [];

    public function Rules() { return $this->rule; }

    public function getAllAktif() {
        return $this->db->get_where('ukuranhewans', ["aktif" => 1])->result();
    } 

    public function store($request){
        $this->nama_ukuranHewan = $request->nama_ukuranHewan;
        $this->updateLog_by = $request->updateLog_by;
        $this->aktif=1;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_ukuranHewan){
        $updateData = 
        ['nama_ukuranHewan' => $request->nama_ukuranHewan, 
         'updateLog_by' => $request->updateLog_by,
         'createLog_at' => date('Y-m-d H:i:s')
        ];
        if($this->db->where('id_ukuranHewan', $id_ukuranHewan)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function softDelete($request, $id_ukuran_hewan){
        $updateData = [
            'aktif' => 0,
            'deleteLog_at' => date('Y-m-d H:i:s')
        ];
        $this->db->trans_start();
        $this->db->where('id_ukuranHewan',$id_ukuranHewan)->update($this->table, $updateData);
        $this->db->where('id_ukuranHewan',$id_ukuranHewan)->update('layananhargas', $updateData);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            return ['msg'=>'Gagal','error'=>true];
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    // public function destroy($id_ukuranHewan){
    //     if(empty($this->db->select('*')->where(array('id_ukuranHewan' => $id_ukuranHewan))->get($this->table)->row()))
    //         return ['msg' => 'Id tidak ditemukan', 'error' => true];

    //     if($this->db->delete($this->table, array('id_ukuranHewan' => $id_ukuranHewan))){
    //         return ['msg' => 'Berhasil', 'error' => false];
    //     }
    //     return ['msg' => 'Gagal', 'error' => true];
    // }
}
?>