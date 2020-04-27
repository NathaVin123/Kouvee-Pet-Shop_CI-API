<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CustomerModel extends CI_Model
{
    private $table = 'customers';

    public $id_customer;
    public $nama_customer;
    public $alamat_customer;
    public $tglLahir_customer;
    public $noTelp_customer;
    public $updateLog_by;
    public $createLog_at = '';
    public $updateLog_at = '';
    public $deleteLog_at = '';

    public $rule = [
        [
            'field' => 'id_customer',
            'label' => 'id_customer',
            'rules' => 'required'
        ],
        [
            'field' => 'nama_customer',
            'label' => 'nama_customer',
            'rules' => 'required'
        ],
        [
            'field' => 'alamat_customer',
            'label' => 'alamat_customer',
            'rules' => 'required'
        ],
        [
            'field' => 'tglLahir_customer',
            'label' => 'tglLahir_customer',
            'rules' => 'required'
        ],
        [
            'field' => 'noTelp_customer',
            'label' => 'noTelp_customer',
            'rules' => 'required'
        ],
        [
            'field' => 'updateLog_by',
            'label' => 'updateLog_by',
            'rules' => 'required'
        ]
    ];

    public function Rules() { return $this->rule; }

    public function getAll() {
        return $this->db->get('customers')->result();
    }

    public function store($request){
        $this->id_customer = $request->id_customer;
        $this->nama_customer = $request->nama_customer;
        $this->alamat_customer = $request->alamat_customer;
        $this->tglLahir_customer = $request->tglLahir_customer;
        $this->noTelp_customer = $request->noTelp_customer;
        $this->updateLog_by = $request->updateLog_by;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_customer){
        $updateData = 
        ['id_customer' => $request->id_customer, 
        'nama_customer' => $request->nama_customer, 
        'alamat_customer' => $request->alamat_customer, 
        'tglLahir_customer' => $request->tglLahir_customer, 
        'noTelp_customer' => $request->noTelp_customer, 
        'updateLog_by' => $request->updateLog_by];
        if($this->db->where('id_customer', $id_customer)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function destroy($id_customer){
        if(empty($this->db->select('*')->where(array('id_customer' => $id_customer))->get($this->table)->row()))
            return ['msg' => 'Id tidak ditemukan', 'error' => true];

        if($this->db->delete($this->table, array('id_customer' => $id_customer))){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }
}
?>

