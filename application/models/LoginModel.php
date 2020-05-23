<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoginModel extends CI_Model
{
    private $table = 'pegawais';

    public $NIP;
    public $nama_pegawai;
    public $password;
    public $rule = [
        [
            'field' => 'name',
            'label' => 'name',
            'rules' => 'required'
        ],
    ];

    public function Rules() { return $this->rule; }

    public function getAll() {
        return $this->db->get('pegawais')->result();
    }

    public function store($request){
        $this->NIP = $request->NIP;
        $this->password = password_hash($request->password, PASSWORD_BCRYPT);
        $set = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = substr(str_shuffle($set), 0, 20);
        $this->verif_code = $code;//md5(rand(1000,9999));

        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
            $result = $this->db->get_where('users', ["nama_pegawai" => $this->nama_pegawai])->row();
            $id = $result->id;
            //$encrypted_id = md5($id);
            $mailMan = new EmailVerification();
            $result = $mailMan->send_mail($this, $id);
            return $result;
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id){
        $updateData = ['NIP' => $request->NIP, 'nama_pegawai' => $request->nama_pegawai];
        if($this->db->where('id', $id)->update($this->table, $updateData)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function destroy($id){
        if(empty($this->db->select('*')->where(array('id' => $id))->get($this->table)->row()))
            return ['msg' => 'Id tidak ditemukan', 'error' => true];

        if($this->db->delete($this->table, array('id' => $id))){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function verify($request){
        $user = $this->db->select('*')->where(array('NIP' => $request->NIP))->get($this->table)->row_array();
        if(!empty($user) && password_verify($request->password, $user['password'])){
           /* if($user['activation_status'] == 1)
            {
                return $user;
            }*/
            return $user;
        }else{
            return false;
        }
    }
}
?>