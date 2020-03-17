<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DetailProdukModel extends CI_Model
{
    private $table = 'detailproduks';

    public $id_detail_produk;
    public $id_produk_fk;
    public $id_transaksi_penjualan_fk;
    public $kode_produk;
    public $tgl_transaksi_produk;
    public $jml_transaksi_produk;
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
        return $this->db->get('detailproduks')->result();
    }

    public function store($request){
        $this->kode_produk = $request->kode_produk;
        $this->tgl_transaksi_produk = $request->tgl_transaksi_produk;
        $this->jml_transaksi_produk = $request->jml_transaksi_produk;
        $this->subtotal = $request->subtotal;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_detail_produk){
        $updateData = ['title' => $request->title, 'artist' => $request->artist, 'genre' => $request->genre, 'lyric' => $request->lyric];
        if($this->db->where('id_detail_produk', $id_detail_produk)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function destroy($id_detail_produk){
        if(empty($this->db->select('*')->where(array('id_detail_produk' => $id_detail_produk))->get($this->table)->row()))
            return ['msg' => 'Id tidak ditemukan', 'error' => true];

        if($this->db->delete($this->table, array('id_detail_produk' => $id_detail_produk))){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }
}
?>