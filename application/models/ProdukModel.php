<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProdukModel extends CI_Model
{
    private $table = 'produks';

    public $id_produk;
    public $id_pegawai_fk;
    public $nama_produk;
    public $harga_produk;
    public $stok_produk;
    public $min_stok_produk;
    public $satuan_produk;
    public $createLog_by;
    public $updateLog_by;
    public $deleteLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;

    public $rule = [
        [
            'field' => 'title',
            'label' => 'title',
            'rules' => 'required'
        ],
    ];

    public function Rules() { return $this->rule; }

    public function getAll() {
        return $this->db->get('produks')->result();
    }

    public function store($request){
        $this->nama_produk = $request->nama_produk;
        $this->harga_produk = $request->harga_produk;
        $this->stok_produk = $request->stok_produk;
        $this->min_stok_produk = $request->min_stok_produk;
        $this->satuan_produk = $request->satuan_produk;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_produk){
        $updateData = ['nama_produk' => $request->nama_produk, 'harga_produk' => $request->harga_produk, 'stok_produk' => $request->stok_produk, 'min_stok_produk' => $request->min_stok_produk, 'satuan_produk' => $request->satuan_produk];
        if($this->db->where('id_produk', $id_produk)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function destroy($id_produk){
        if(empty($this->db->select('*')->where(array('id_produk' => $id_produk))->get($this->table)->row()))
            return ['msg' => 'Id tidak ditemukan', 'error' => true];

        if($this->db->delete($this->table, array('id_produk' => $id_produk))){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }
}
?>