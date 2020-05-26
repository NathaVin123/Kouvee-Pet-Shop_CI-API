<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class TransaksiPenjualanLayananModel extends CI_Model
{
    private $table = 'transaksipenjualanlayanans';

    public $kode_penjualan_layanan;
    public $id_hewan;
    public $id_cs;
    public $id_kasir;
    public $subtotal;
    public $diskon;
    public $total;
    public $proses;
    public $status_transaksi;
    public $tanggal_lunas;
    public $createLog_at;
    public $updateLog_at;

    public $rule = [];

    public function Rules() { return $this->rule; }

    public function getAllAktif() {
        return $this->db->get('transaksipenjualanlayanans')->result();
    } 

    public function store($request){
        $date_now = date('dmy');
        $this->db->select_max('kode_penjualan_layanan');
        $this->db->like('kode_penjualan_layanan', 'LY-'.$date_now, 'after');
        $query = $this->db->get('transaksipenjualanlayanans');
        $lastdata = $query->row();
        $last_id = $lastdata->kode_penjualan_layanan;
        $last_count = substr($last_id, 10, 2);
        $next_count = $last_count+1;
        $next_id = 'LY-'.$date_now.'-'.sprintf('%02s', $next_count);

        $this->kode_penjualan_layanan = $next_id;
        $this->id_hewan = $request->id_hewan;
        $this->id_cs = $request->id_cs;
        $this->subtotal = $request->subtotal;
        $this->diskon = $request->diskon;
        $this->total = $request->total;
        $this->proses = 'Layanan Selesai';
        $this->status_transaksi = 'Menunggu Pembayaran';
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function storeReturnObject($request) { 
        $date_now = date('dmy');
        $this->db->select_max('kode_penjualan_layanan');
        $this->db->like('kode_penjualan_layanan', 'LY-'.$date_now, 'after');
        $query = $this->db->get('transaksipenjualanlayanans');
        $lastdata = $query->row();
        $last_id = $lastdata->kode_penjualan_layanan;
        $last_count = substr($last_id, 10, 2);
        $next_count = $last_count+1;
        $next_id = 'LY-'.$date_now.'-'.sprintf('%02s', $next_count);

        $this->kode_penjualan_layanan = $next_id;
        $this->id_hewan = $request->id_hewan;
        $this->id_cs = $request->id_cs;
        $this->subtotal = $request->subtotal;
        $this->diskon = $request->diskon;
        $this->total = $request->total;
        $this->proses = 'Layanan Selesai';
        $this->status_transaksi = 'Menunggu Pembayaran';
        if($this->db->insert($this->table, $this)){
            //$temp = $this->updateTotal($next_id, $request->diskon);
            $obj = $this->db->get_where('transaksipenjualanlayanans', ["kode_penjualan_layanan" => $next_id])->row();
            return ['msg'=>$obj,'error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $kode_penjualan_layanan){
        $updateData = 
        ['id_hewan' => $request->id_hewan,
        'subtotal' => $request->subtotal, 
        'diskon' => $request->diskon, 
        'total' => $request->total, 
        'updateLog_at' => date('Y-m-d H:i:s'),
        ];
        $data = $this->db->get_where('transaksipenjualanlayanans',['kode_penjualan_layanan'=>$kode_penjualan_layanan, 'status_transaksi'=> 'Menunggu Pembayaran'])->row();
        if($data){
            $this->db->where(['kode_penjualan_layanan'=>$kode_penjualan_layanan, 'status_transaksi'=> 'Menunggu Pembayaran'])->update($this->table, $updateData);
            //$temp = $this->updateTotal($kode_penjualan_layanan, $request->diskon);
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function updateProgress($request, $kode_penjualan_layanan) {
        $messageStat = false;
        
        $updateData = [
            'proses' => 'Layanan Selesai',
            'createLog_at' => date('Y-m-d H:i:s')
        ];
        $data = $this->db->get_where('transaksipenjualanlayanans',['kode_penjualan_layanan'=>$kode_penjualan_layanan, 'status_transaksi'=> 'Menunggu Pembayaran'])->row();
        if($data){
            $this->db->trans_start();
            $this->db->where(['kode_penjualan_layanan'=>$kode_penjualan_layanan, 'status_transaksi'=> 'Menunggu Pembayaran'])->update($this->table, $updateData);            
            $this->db->select('id_hewan, hewans.id_customer, customers.nama "nama_customer", customers.alamat_customer "alamat_customer", 
                            customers.tglLahir_customer "tglLahir_customer", customers.noTelp_customer "noTelp_customer",
                            hewans.id_jenisHewan, jenishewans.nama_jenisHewan "nama_jenisHewan", hewans.nama_hewan "nama_hewan", hewans.tglLahir_hewan "tglLahir_hewan", 
                            hewans.createLog_at, hewans.updateLog_by, hewans.updateLog_at, hewans.deleteLog_at, hewans.aktif');
            $this->db->from('hewans');
            $this->db->join('customers', 'hewans.id_customer = customers.id_customer');
            $this->db->join('jenishewans', 'hewans.id_jenisHewan = jenishewans.id_jenisHewan');
            $this->db->where('id_hewan',$data->id_hewan);
            $query = $this->db->get();
            $hewans = null;
            if($query->num_rows()!=0){
                $hewans = $query->row();
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                # Something went wrong.
                $this->db->trans_rollback();
                return ['msg'=>'Gagal','error'=>true];
            } 
            else {
                # Everything is Perfect. 
                # Committing data to the database.
                // if($hewan!=null){
                //     $telp = '';
                //     if(substr($hewan->telp_pelanggan,0,3)=='+62'){
                //         $telp = $hewan->telp_pelanggan;
                //     }else{
                //         $number = substr($hewan->telp_pelanggan,1,strlen($hewan->telp_pelanggan));
                //         $telp = '+62'.$number;
                //     }

                //     $fields_string  =   "";
                //     $fields = array(
                //                 'api_key'       =>  'a0c91022',
                //                 'api_secret'    =>  'qCSO83HdmC87Pv3P',
                //                 'to'            =>  $telp,
                //                 'from'          =>  "Kouvee Pet Shop",
                //                 'text'          =>  'Halo '.$hewan->nama_pelanggan.', layanan untuk peliharaan anda sudah selesai dikerjakan, mohon selesaikan pembayaran di Kouvee Pet Shop. Thanks.'
                //                 );
                //     $url    =   "https://rest.nexmo.com/sms/json";

                //     //url-ify the data for the POST
                //     foreach($fields as $key=>$value) { 
                //             $fields_string .= $key.'='.$value.'&'; 
                //             }
                //     rtrim($fields_string, '&');

                //     //open connection
                //     $ch = curl_init();

                //     //set the url, number of POST vars, POST data
                //     curl_setopt($ch,CURLOPT_URL, $url);
                //     curl_setopt($ch,CURLOPT_POST, count($fields));
                //     curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
                //     curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

                //     //execute post
                //     $curl_res = curl_exec($ch);
                //     //close connection
                //     curl_close($ch);

                //     $result = json_decode($curl_res);

                //     if($result->messages[0]->status == 0) {
                //         $messageStat = true;
                //     } else {
                //         $messageStat = false;
                //     }
                //     if($messageStat){
                //         $this->db->trans_commit();
                //         return ['msg'=>'Berhasil','error'=>false];
                //     }
                //     $this->db->trans_rollback();
                //     return ['msg'=>'Gagal','error'=>true];
                // }
                $this->db->trans_commit();
                return ['msg'=>'Berhasil','error'=>false];
            }
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function updateStatus($request, $kode_penjualan_layanan) {
        $updateData = [
            'id_kasir' => $request->id_kasir,
            'status_transaksi' => 'Lunas',
            'tanggal_lunas' => date('Y-m-d H:i:s'),
            'createLog_at' => date('Y-m-d H:i:s'),
        ];

        $data = $this->db->get_where('transaksipenjualanlayanans',['kode_penjualan_layanan'=>$kode_penjualan_layanan, 'proses'=>'Layanan Selesai', 'status_transaksi'=> 'Menunggu Pembayaran'])->row();
        if($data!=null){
            $this->db->where(['kode_penjualan_layanan'=>$kode_penjualan_layanan, 'proses'=>'Layanan Selesai', 'status_transaksi'=> 'Menunggu Pembayaran'])->update('transaksipenjualanlayanans', $updateData);
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function updateTotal($kode_penjualan_layanan, $diskon) {
        //$transdata =$this->db->get_where('transaksi_produk', ['id_transaksi_produk'=>$id_transaksi_produk])->row();
        $this->db->select_sum('total');
        $this->db->where('kode_penjualan_layanan', $kode_penjualan_layanan);
        $pricedata = $this->db->get('detailtransaksilayanans')->row();
        if($pricedata->total==null || $pricedata->total<=$diskon)
        {
            $updateData = [
                'subtotal' => $pricedata->total, 
                'total' => 0
            ];
            if($pricedata->total==null){
                $updateData['subtotal'] = 0;
            }
        }else{
            if($diskon==null){
                $updateData = [
                    'subtotal' => $pricedata->total, 
                    'total' => $pricedata->total
                ];
            }else{
                $updateData = [
                    'subtotal' => $pricedata->total, 
                    'total' => $pricedata->total-$diskon
                ];
            }
        }
        
        if($this->db->where('kode_penjualan_layanan',$kode_penjualan_layanan)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function destroy($kode_penjualan_layanan){
        if (empty($this->db->select('*')->where(array('kode_penjualan_layanan' => $kode_penjualan_layanan))->get($this->table)->row())) 
            return ['msg'=>'Id tidak ditemukan','error'=>true];
        
        $data = $this->db->get_where($this->table, array('kode_penjualan_layanan' => $kode_penjualan_layanan))->row();
        if($data!=null && $data->kode_penjualan_layanan==$kode_penjualan_layanan){
            $this->db->trans_start();
            $this->db->delete('detailtransaksilayanans', ['kode_penjualan_layanan' => $kode_penjualan_layanan]);
            $this->db->delete($this->table, ['kode_penjualan_layanan' => $kode_penjualan_layanan]);
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                # Something went wrong.
                $this->db->trans_rollback();
                return ['msg'=>'Gagal','error'=>true];
            } 
            else {
                # Everything is Perfect. 
                # Committing data to the database.
                $this->db->trans_commit();
                return ['msg'=>'Berhasil','error'=>false];
            }
            
        }
        return ['msg'=>'Id tidak ditemukan','error'=>true];
    }
}
?>