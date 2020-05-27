<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class LayananModel extends CI_Model
{
    private $table = 'layanans';

    public $id_layanan;
    public $nama_layanan;
    public $updateLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;
    public $aktif;

    public $rule = [];

    public function Rules() { return $this->rule; }

    public function getAllAktif() {
        return $this->db->get_where('layanans', ["aktif" => 1])->result();
    }

    public function store($request){
        $this->nama_layanan = $request->nama_layanan;
        $this->updateLog_by = $request->updateLog_by;
        $this->aktif=1;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>$this->db->insert_id(),'error'=>false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_layanan){
        $updateData = 
        ['nama_layanan' => $request->nama_layanan, 
        'updateLog_by' => $request->updateLog_by,
        'updateLog_at' => date('Y-m-d H:i:s')];
        
        if($this->db->where('id_layanan', $id_layanan)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function softDelete($request, $id_layanan){
        $updateData = [
            'aktif' => 0,
            'deleteLog_at' => date('Y-m-d H:i:s')
        ];
        $this->db->trans_start();
        $this->db->where('id_layanan',$id_layanan)->update($this->table, $updateData);
        $this->db->where('id_layanan',$id_layanan)->update('layananhargas', $updateData);
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

    public function destroy($id_layanan){
        if(empty($this->db->select('*')->where(array('id_layanan' => $id_layanan))->get($this->table)->row()))
            return ['msg' => 'Id tidak ditemukan', 'error' => true];

        if($this->db->delete($this->table, array('id_layanan' => $id_layanan))){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }
}
?>