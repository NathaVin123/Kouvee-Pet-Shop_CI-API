<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class PemberitahuanModel extends CI_Model
{
    private $table = 'pemberitahuans';

    public $id_notifikasi;
    public $id_produk;
    public $status;
    public $createLog_at;

    public function updateStatusToOpened($id_notifikasi) {
        $data = $this->db->get_where('pemberitahuans',['id_notifikasi'=>$id_notifikasi, 'status'=> '0'])->row();
        if($data!=null){
            $this->db->where(['id_notifikasi'=>$id_notifikasi, 'status'=> '0'])->update($this->table, ['status' => 1]);
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
}
?>