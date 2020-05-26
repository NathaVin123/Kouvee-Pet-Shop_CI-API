<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class JenisHewanModel extends CI_Model
{
    private $table = 'jenishewans';

    public $id_jenisHewan;
    public $nama_jenisHewan;
    public $updateLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;
    public $aktif;

    public $rule = [];

    public function Rules() { return $this->rule; }

    public function getAllAktif() {
        return $this->db->get_where('jenishewans', ["aktif" => 1])->result();
    }

    public function store($request){
        $this->nama_jenisHewan = $request->nama_jenisHewan;
        $this->updateLog_by = $request->updateLog_by;
        $this->aktif=1;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_jenisHewan){
        $updateData = 
        ['nama_jenisHewan' => $request->nama_jenisHewan, 
         'updateLog_by' => $request->updateLog_by
        ];
        if($this->db->where('id_jenisHewan', $id_jenisHewan)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function softDelete($request, $id_jenis_hewan){
        $updateData = [
            'aktif' => 0,
            'deleteLog_at' => date('Y-m-d H:i:s')
        ];
        if($this->db->where('id_jenisHewan',$id_jenisHewan)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    // public function destroy($id_jenisHewan){
    //     if(empty($this->db->select('*')->where(array('id_jenisHewan' => $id_jenisHewan))->get($this->table)->row()))
    //         return ['msg' => 'Id tidak ditemukan', 'error' => true];

    //     if($this->db->delete($this->table, array('id_jenisHewan' => $id_jenisHewan))){
    //         return ['msg' => 'Berhasil', 'error' => false];
    //     }
    //     return ['msg' => 'Gagal', 'error' => true];
    // }
}
?>