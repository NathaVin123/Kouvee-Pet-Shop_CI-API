<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class DetailTransaksiLayananModel extends CI_Model
{
    private $table = 'detailtransaksilayanans';

    public $id_detaillayanan;
    public $kode_penjualan_layanan;
    public $id_layananHarga;
    public $jml_transaksi_layanan;
    public $total_harga;
    public $createLog_at;
    public $updateLog_at;

    public $rule = [];

    public function Rules() { return $this->rule; }

    public function getAllAktif() {
        return $this->db->get('detailtransaksilayanans')->result();
    } 

    public function store($request){
        $this->kode_penjualan_layanan = $request->kode_penjualan_layanan;
        $this->id_layananHarga = $request->id_layananHarga;
        $this->jml_transaksi_layanan = $request->jml_transaksi_layanan;
        $this->total_harga = $request->total_harga;
        if($this->db->insert($this->table, $this)){
            if($this->groomingCheck($request->id_layananHarga)){
                $debug = 'grooming';
                $this->setProgress($request->kode_penjualan_layanan, 'Sedang Diproses');
            }
            $this->updateTotal($request->kode_penjualan_layanan);
            return ['msg'=>'Berhasil ','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function storeMultiple($request) {
        $groomStat = false;
        $jsondata = json_decode($request);
        $dataset = array();
        foreach($jsondata as $data){
            $dataset[] = 
                array(
                    'kode_penjualan_layanan' => $data->kode_penjualan_layanan,
                    'id_layananHarga' => $data->id_layananHarga,
                    'jml_transaksi_layanan' => $data->jml_transaksi_layanan,
                    'total_harga' => $data->total_harga,
                );
            if($this->groomingCheck($data->id_layananHarga)){
                $groomStat = true;
            }
        }
        //echo count($dataset);
        if($this->db->insert_batch($this->table, $dataset)){
            if($groomStat){
                $this->setProgress($dataset[0]["kode_penjualan_layanan"], 'Sedang Diproses');
            }
            $this->updateTotal($dataset[0]["kode_penjualan_layanan"]);
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $id_detaillayanan){
        $updateData = [
        'id_layananHarga' => $request->id_layananHarga,
        'jml_transaksi_layanan' => $request->jml_transaksi_layanan,
        'total_harga' => $request->total_harga,
        'updateLog_at' => date('Y-m-d H:i:s'),
        ];

        $data = $this->db->get_where($this->table, array('id_detaillayanan' => $id_detaillayanan))->row();
        $kode_penjualan_layanan = $data->kode_penjualan_layanan;
        $groomState = $this->groomingCheck($request->id_layananHarga);

        if($this->db->where('id_detaillayanan',$id_detaillayanan)->update($this->table, $updateData)){

            if($groomState){
                $this->setProgress($data->kode_penjualan_layanan, 'Sedang Diproses');
            }else{
                $transdata = $this->db->get_where('detailtransaksilayanans', ['kode_penjualan_layanan'=>$kode_penjualan_layanan])->result();
                $setProgress = true;
                foreach ($transdata as $temp) {
                    if($this->groomingCheck($temp->id_layananHarga)){
                        $setProgress = false;
                    }
                }
                if($setProgress){
                    $this->setProgress($kode_penjualan_layanan, 'Layanan Selesai');
                }
            }
            
            $this->updateTotal($data->kode_penjualan_layanan);
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function updateMultiple($request) {
        $groomStat = false;
        $jsondata = json_decode($request);
        //$id_transaksi_produk = 0;
        $this->db->trans_start();
        foreach($jsondata as $data){
            $id_detaillayanan = $data->id_detaillayanan;
            $kode_penjualan_layanan = $data->kode_penjualan_layanan;
            $updateData = [
                'id_layananHarga' => $data->id_layananHarga,
                'jml_transaksi_layanan' => $data->jml_transaksi_layanan,
                'total_harga' => $data->total_harga,
                'updateLog_at' => date('Y-m-d H:i:s'),
            ];
            if($this->groomingCheck($data->id_layananHarga)){
                $groomStat = true;
            }
            $this->db->where('id_detaillayanan',$id_detaillayanan)->update($this->table, $updateData);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            $this->updateTotal($kode_penjualan_layanan);
            return ['msg'=>'Gagal','error'=>true];
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();

            if($groomStat){
                $this->setProgress($jsondata[0]->kode_penjualan_layanan, 'Sedang Diproses');
            }
            $this->updateTotal($kode_penjualan_layanan);
            return ['msg'=>'Berhasil','error'=>false];
        }
    }

    public function destroy($id_detaillayanan){
        if (empty($this->db->select('*')->where(array('id_detaillayanan' => $id_detaillayanan))->get($this->table)->row())) 
            return ['msg'=>'Id tidak ditemukan','error'=>true];
        
        $data = $this->db->get_where($this->table, array('id_detaillayanan' => $id_detaillayanan))->row();
        $kode_penjualan_layanan = $data->kode_penjualan_layanan;
        $groomState = $this->groomingCheck($data->id_layananHarga);

        if($data!=null && $data->id_detaillayanan==$id_detaillayanan){
            if($this->db->delete($this->table, array('id_detaillayanan' => $id_detaillayanan))){
                
                if($groomState){
                    $transdata = $this->db->get_where('detailtransaksilayanans', ['kode_penjualan_layanan'=>$kode_penjualan_layanan])->result();
                    $setProgress = true;
                    foreach ($transdata as $temp) {
                        if($this->groomingCheck($temp->id_layananHarga)){
                            $setProgress = false;
                        }
                    }
                    if($setProgress){
                        $this->setProgress($kode_penjualan_layanan, 'Layanan Selesai');
                    }
                }
                
                $this->updateTotal($data->kode_penjualan_layanan);
                return ['msg'=>'Berhasil','error'=>false];
            }
            return ['msg'=>'Gagal','error'=>true];
        }
        return ['msg'=>'Id tidak ditemukan','error'=>true];
    }

    public function deleteMultiple($request){
        $jsondata = json_decode($request);

        $setProgress = true;
        
        $kode_penjualan_layanan = 0;

        $this->db->trans_start();

        foreach($jsondata as $id){
            $data = $this->db->get_where($this->table, array('id_detaillayanan' => $id))->row();
            $kode_penjualan_layanan = $data->kode_penjualan_layanan;
            //$groomState = $this->groomingCheck($data->id_layananHarga);

            if($data!=null && $data->id_detaillayanan==$id){
                if($this->db->delete($this->table, array('id_detaillayanan' => $id))){
                }
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            $this->updateTotal($kode_penjualan_layanan);
            return ['msg'=>'Gagal','error'=>true];
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();

            //if($groomState){
            $transdata = $this->db->get_where('detailtransaksilayanans', ['kode_penjualan_layanan'=>$kode_penjualan_layanan])->result();
            
            foreach ($transdata as $temp) {
                if($this->groomingCheck($temp->id_layananHarga)){
                    $setProgress = false;
                }
            }
            //}
            if($setProgress){
                $this->setProgress($kode_penjualan_layanan, 'Layanan Selesai');
            }
            $this->updateTotal($kode_penjualan_layanan);
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function updateTotal($kode_penjualan_layanan) {
        $transdata = $this->db->get_where('transaksipenjualanlayanans', ['kode_penjualan_layanan'=>$kode_penjualan_layanan])->row();
        $this->db->select_sum('total_harga');
        $this->db->where('kode_penjualan_layanan', $kode_penjualan_layanan);
        $pricedata = $this->db->get('detailtransaksilayanans')->row();
        if($pricedata->total_harga==null || $pricedata->total_harga<=$transdata->diskon)
        {
            $updateData = [
                'subtotal' => $pricedata->total_harga, 
                'total' => 0
            ];
            if($pricedata->total_harga==null){
                $updateData['subtotal'] = 0;
            }
        }else{
            if($transdata->diskon==null){
                $updateData = [
                    'subtotal' => $pricedata->total_harga, 
                    'total' => $pricedata->total_harga
                ];
            }else{
                $updateData = [
                    'subtotal' => $pricedata->total_harga, 
                    'total' => $pricedata->total_harga-$transdata->diskon
                ];
            }
        }
        $this->db->where('kode_penjualan_layanan',$kode_penjualan_layanan)->update('transaksipenjualanlayanans', $updateData);
    }

    public function groomingCheck($id_layananHarga){
        $this->db->select('id_layananHarga, layananhargas.id_layanan, layanans.nama "nama_layanan"');
        $this->db->from('layananhargas');
        $this->db->join('layanans', 'layananhargas.id_layanan = layanans.id_layanan');
        $this->db->where('layananhargas.id_layananHarga',$id_layananHarga);
        $data = $this->db->get()->row();
        if($data!=null){
            if(strpos(strtolower($data->nama_layanan),'grooming') !== false){
                return true;
            }else{
                return false;
            }   
        }
    }

    public function setProgress($kode_penjualan_layanan, $progress) {
        $updateData = [
            'progress' => $progress
        ];
        $data = $this->db->get_where('transaksipenjualanlayanans',['kode_penjualan_layanan'=>$kode_penjualan_layanan, 'status'=> 'Menunggu Pembayaran'])->row();
        if($data!=null){
            $this->db->where(['kode_penjualan_layanan'=>$kode_penjualan_layanan, 'status'=> 'Menunggu Pembayaran'])->update('transaksipenjualanlayanans', $updateData);
        }
    }
}
?>