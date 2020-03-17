<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DetailPengadaanModel extends CI_Model
{
    private $table = 'detailpengadaans';

    public $id_detail_pengadaan;
    public $id_produk_fk;
    public $id_stock_fk;
    public $kode_stok;
    public $jml_stok_pengadaan;
    public $status_pengadaan_produk;

    // public $rule = [
    //     [
    //         'field' => 'title',
    //         'label' => 'title',
    //         'rules' => 'required'
    //     ],
    // ];

    // public function Rules() { return $this->rule; }

    public function getAll() {
        return $this->db->get('detailpengadaans')->result();
    }

    public function store($request){
        $this->kode_stok = $request->kode_stok;
        $this->jml_stok_pengadaan = $request->jml_stok_pengadaan;
        $this->status_pengadaan_produk = $request->status_pengadaan_produk;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_detail_pengadaan){
        $updateData = ['title' => $request->title, 'artist' => $request->artist, 'genre' => $request->genre, 'lyric' => $request->lyric];
        if($this->db->where('id_detail_pengadaan', $id_detail_pengadaan)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function destroy($id_detail_pengadaan){
        if(empty($this->db->select('*')->where(array('id_detail_pengadaan' => $id_detail_pengadaan))->get($this->table)->row()))
            return ['msg' => 'Id tidak ditemukan', 'error' => true];

        if($this->db->delete($this->table, array('id_detail_pengadaan' => $id_detail_pengadaan))){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }
}
?>