<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class LayananHargaModel extends CI_Model
{
    private $table = 'layananhargas';

    public $id_layananHarga;
    public $id_layanan;
    public $id_ukuranHewan;
    public $harga;
    public $updateLog_by;
    public $createLog_at;
    public $updateLog_at;
    public $deleteLog_at;
    public $aktif;

    public $rule = [];

    public function Rules() { return $this->rule; }

    public function getAllAktif() {
        $this->db->select('id_layananHarga, layananhargas.id_layanan, layanans.nama_layanan "nama_layanan", layananhargas.id_ukuranHewan, 
                        ukuranhewans.nama_ukuranHewan "nama_ukuranHewan", layananhargas.harga,
                        layananhargas.createLog_at, layananhargas.updateLog_at, harga_layanan.updateLog_by,
                        layananhargas.deleteLog_at, layananhargas.aktif');
        $this->db->from('layananhargas');
        $this->db->join('layanans', 'layananhargas.id_layanan = layanans.id_layanan');
        $this->db->join('ukuranhewans', 'layananhargas.id_ukuranHewan = ukuranhewans.id_ukuranHewan');
        $this->db->where('layananhargas.aktif',1);
        $this->db->order_by('layananhargas.id_layananHarga ASC');
        return $this->db->get()->result();
    }

    public function store($request){
        $this->id_layanan = $request->id_layanan;
        $this->id_ukuranHewan = $request->id_ukuranHewan;
        $this->harga = $request->harga;
        $this->updateLog_by = $request->updateLog_by;
        $this->aktif=1;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>$this->db->insert_id(),'error'=>false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function storeMultiple($request) {
        $jsondata = json_decode($request);
        $dataset = array();
        $id_layanan = 0;
        foreach($jsondata as $data){
            $id_layanan = $data->id_layanan;
            $dataset[] =  
                array(
                    'id_layanan' => $data->id_layanan,
                    'id_ukuranHewan' => $data->id_ukuranHewan,
                    'harga' => $data->harga,
                    'updateLog_by' => $data->updateLog_by,
                    'aktif' => 1,
                );
        }
        //echo count($dataset);
        $query = $this->db->insert_batch($this->table, $dataset);
        if($query){
            return ['msg'=>'Berhasil','error'=>false];
        }
        $this->db->delete('layanans', array('id_layanan' => $id_layanan));
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $id_layananHarga){
        $updateData = 
        ['id_layanan' => $request->id_layanan,
        'id_ukuranHewan' => $request->id_ukuranHewan,
        'harga' => $request->harga,
        'updateLog_by' => $request->updateLog_by,
        'updateLog_at' => date('Y-m-d H:i:s')];
        
        if($this->db->where('id_layananHarga', $id_layananHarga)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function softDelete($request, $id_layananHarga){
        $updateData = [
            'aktif' => 0,
            'deleteLog_at' => date('Y-m-d H:i:s')
        ];
        if($this->db->where('id_layananHarga',$id_layananHarga)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function destroy($id_layananHarga){
        if(empty($this->db->select('*')->where(array('id_layananHarga' => $id_layananHarga))->get($this->table)->row()))
            return ['msg' => 'Id tidak ditemukan', 'error' => true];

        if($this->db->delete($this->table, array('id_layananHarga' => $id_layananHarga))){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }
}
?>