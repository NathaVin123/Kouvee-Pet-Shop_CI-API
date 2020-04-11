<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LayananModel extends CI_Model
{
    private $table = 'layanans';

    public $id_layanan;
    public $nama_layanan;
    public $harga_layanan;
    public $id_ukuranHewan;
    public $updateLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;

    public $rule = [
        [
            'field' => 'id_layanan',
            'label' => 'id_layanan',
            'rules' => 'required'
        ],
        [
            'field' => 'nama_layanan',
            'label' => 'nama_layanan',
            'rules' => 'required'
        ],
        [
            'field' => 'harga_layanan',
            'label' => 'harga_layanan',
            'rules' => 'required'
        ],
        [
            'field' => 'id_ukuranHewan',
            'label' => 'id_ukuranHewan',
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
        return $this->db->get('layanans')->result();
    }

    public function store($request){
        $this->id_layanan = $request->id_layanan;
        $this->nama_layanan = $request->nama_layanan;
        $this->harga_layanan = $request->harga_layanan;
        $this->id_ukuranHewan = $request->id_ukuranHewan;
        $this->updateLog_by = $request->updateLog_by;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_layanan){
        $updateData = ['id_layanan' => $request->id_layanan, 'nama_layanan' => $request->nama_layanan, 'harga_layanan' => $request->harga_layanan, 'id_ukuranHewan' => $request->id_ukuranHewan, 'updateLog_by' => $request->updateLog_by];
        
        if($this->db->where('id_layanan', $id_layanan)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
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