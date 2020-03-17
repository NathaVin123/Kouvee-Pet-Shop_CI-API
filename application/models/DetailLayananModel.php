<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DetailLayananModel extends CI_Model
{
    private $table = 'detaillayanans';

    public $id_detail_layanan;
    public $id_layanan_fk;
    public $id_transaksi_penjualan_fk;
    public $tgl_transaksi_layanan;
    public $jml_transaksi_layanan;
    public $subtotal;

    // public $rule = [
    //     [
    //         'field' => 'title',
    //         'label' => 'title',
    //         'rules' => 'required'
    //     ],
    // ];

    // public function Rules() { return $this->rule; }

    public function getAll() {
        return $this->db->get('detaillayanans')->result();
    }

    public function store($request){
        $this->kode_layanan = $request->kode_layanan;
        $this->tgl_transaksi_layanan = $request->tgl_transaksi_layanan;
        $this->jml_transaksi_layanan = $request->jml_transaksi_layanan;
        $this->subtotal = $request->subtotal;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_detail_layanan){
        $updateData = ['title' => $request->title, 'artist' => $request->artist, 'genre' => $request->genre, 'lyric' => $request->lyric];
        if($this->db->where('id_detail_layanan', $id_detail_layanan)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function destroy($id_detail_layanan){
        if(empty($this->db->select('*')->where(array('id_detail_layanan' => $id_detail_layanan))->get($this->table)->row()))
            return ['msg' => 'Id tidak ditemukan', 'error' => true];

        if($this->db->delete($this->table, array('id_detail_layanan' => $id_detail_layanan))){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }
}
?>