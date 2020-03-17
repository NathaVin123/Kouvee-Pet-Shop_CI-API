<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LayananModel extends CI_Model
{
    private $table = 'layanans';

    public $id_layanan;
    public $id_pegawai_fk;
    public $nama_layanan;
    public $harga_layanan;
    public $jenis_layanan;
    public $createLog_by;
    public $updateLog_by;
    public $deleteLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;
    public $id_ukuranHewan_fk;

    public $rule = [
        [
            'field' => 'title',
            'label' => 'title',
            'rules' => 'required'
        ],
    ];

    public function Rules() { return $this->rule; }

    public function getAll() {
        return $this->db->get('layanans')->result();
    }

    public function store($request){
        $this->nama_jenisHewan = $request->nama_jenisHewan;
        $this->harga_layanan = $request->harga_layanan;
        $this->jenis_layanan = $request->jenis_layanan;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_hewan){
        $updateData = ['nama_layanan' => $request->nama_layanan];
        if($this->db->where('id_layanan', $id_layanan)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function destroy($id_hewan){
        if(empty($this->db->select('*')->where(array('id_layanan' => $id_layanan))->get($this->table)->row()))
            return ['msg' => 'Id tidak ditemukan', 'error' => true];

        if($this->db->delete($this->table, array('id_layanan' => $id_layanan))){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }
}
?>