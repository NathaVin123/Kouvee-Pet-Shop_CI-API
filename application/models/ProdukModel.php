<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class ProdukModel extends CI_Model
{
    private $table = 'produks';

    public $id_produk;
    public $nama_produk;
    public $harga_produk;
    public $stok_produk;
    public $min_stok_produk;
    public $satuan_produk;
    public $gambar;
    public $updateLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;
    public $aktif;

    public $rule = [];

    public function Rules() { return $this->rule; }

    public function getAllAktif() {
        return $this->db->get_where('produks', ["aktif" => 1])->result();
    }

    public function store($request){
        $this->nama_produk = $request->nama_produk;
        $this->harga_produk = $request->harga_produk;
        $this->stok_produk = $request->stok_produk;
        $this->min_stok_produk = $request->min_stok_produk;
        $this->satuan_produk = $request->satuan_produk;
        $this->gambar = $this->uploadImage();
        $this->updateLog_by = $request->updateLog_by;
        $this->aktif=1;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_produk){
        $this->nama_produk = $request->nama_produk;
        if (!empty($_FILES["gambar"])) {
            $image = $this->uploadImage();
        } else {
            $old_data = $this->db->get_where('produks', ["id_produk" => $id_produk])->row();
            $image = $old_data->gambar;
        }
        $updateData = 
        ['nama_produk' => $request->nama_produk, 
        'harga_produk' => $request->harga_produk, 
        'stok_produk' => $request->stok_produk, 
        'min_stok_produk' => $request->min_stok_produk, 
        'satuan_produk' => $request->satuan_produk,
        'gambar' => $image,
        'updateLog_by' => $request->updateLog_by,
        'updateLog_at' => date('Y-m-d H:i:s')
        ];
        if($this->db->where('id_produk', $id_produk)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function softDelete($request, $id_produk){
        $updateData = [
            'aktif' => 0,
            'deleteLog_at' => date('Y-m-d H:i:s')
        ];
        if($this->db->where('id_produk',$id_produk)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    // public function destroy($id_produk){
    //     if(empty($this->db->select('*')->where(array('id_produk' => $id_produk))->get($this->table)->row()))
    //         return ['msg' => 'Id tidak ditemukan', 'error' => true];

    //     if($this->db->delete($this->table, array('id_produk' => $id_produk))){
    //         return ['msg' => 'Berhasil', 'error' => false];
    //     }
    //     return ['msg' => 'Gagal', 'error' => true];
    // }

    private function uploadImage()
    {
        $config['upload_path']          = './uploads/produk/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['file_name']            = $this->nama_produk;
        $config['overwrite']			= true;
        $config['max_size']             = 4096; // 4MB
        // $config['max_width']            = 1024;
        // $config['max_height']           = 768;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('gambar')) {
            return $this->upload->data("file_name");
        }
        
        return "default.jpg";
    }
}
?>