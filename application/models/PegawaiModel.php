<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PegawaiModel extends CI_Model
{
    private $table = 'pegawais';

    public $NIP;
    public $nama_pegawai;
    public $alamat_pegawai;
    public $tglLahir_pegawai;
    public $noTelp_pegawai;
    public $stat;
    public $password;
    // public $gambar;
    public $updateLog_by;
    public $createLog_at  = '';
    public $updateLog_at  = '';
    public $deleteLog_at  = '';

    public $rule = [
        [
            'field' => 'NIP',
            'label' => 'NIP',
            'rules' => 'required'
        ],
        [
            'field' => 'nama_pegawai',
            'label' => 'nama_pegawai',
            'rules' => 'required'
        ],
        [
            'field' => 'alamat_pegawai',
            'label' => 'alamat_pegawai',
            'rules' => 'required'
        ],
        [
            'field' => 'tglLahir_pegawai',
            'label' => 'tglLahir_pegawai',
            'rules' => 'required'
        ],
        [
            'field' => 'noTelp_pegawai',
            'label' => 'noTelp_pegawai',
            'rules' => 'required'
        ],
        [
            'field' => 'stat',
            'label' => 'stat',
            'rules' => 'required'
        ],
        [
            'field' => 'password',
            'label' => 'password',
            'rules' => 'required'
        ],
        // [
        //     'field' => 'gambar',
        //     'label' => 'gambar',
        //     'rules' => 'required'
        // ],
        [
            'field' => 'updateLog_by',
            'label' => 'updateLog_by',
            'rules' => 'required'
        ]
    ];

    public function Rules() { return $this->rule; }

    public function getAll() {
        return $this->db->query('select * ')->result();
    }

    public function store($request){
        $this->NIP = $request->NIP;
        $this->nama_pegawai = $request->nama_pegawai;
        $this->alamat_pegawai = $request->alamat_pegawai;
        $this->tglLahir_pegawai = $request->tglLahir_pegawai;
        $this->noTelp_pegawai = $request->noTelp_pegawai;
        $this->stat = $request->stat;
        $this->password = $request->password;
        // $this->gambar = $request->gambar;
        $this->updateLog_by = $request->updateLog_by;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $NIP){
        $updateData = 
        ['NIP' => $request->NIP, 
        'nama_pegawai' => $request->nama_pegawai, 
        'alamat_pegawai' => $request->alamat_pegawai, 
        'tglLahir_pegawai' => $request->tglLahir_pegawai, 
        'noTelp_pegawai' => $request->noTelp_pegawai, 
        'stat' => $request->stat,
        'password' => $request->password,
        // 'gambar' => $request->gambar,
        'updateLog_by' => $request->updateLog_by];

        if($this->db->where('NIP', $NIP)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function destroy($NIP){
        if(empty($this->db->select('*')->where(array('NIP' => $NIP))->get($this->table)->row()))
            return ['msg' => 'Id tidak ditemukan', 'error' => true];

        if($this->db->delete($this->table, array('NIP' => $NIP))){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }
}
?>