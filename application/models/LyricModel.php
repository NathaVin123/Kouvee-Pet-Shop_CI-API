<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LyricModel extends CI_Model
{
    private $table = 'lyrics';

    public $id;
    public $title;
    public $artist;
    public $genre;
    public $lyric;

    public $rule = [
        [
            'field' => 'title',
            'label' => 'title',
            'rules' => 'required'
        ],
    ];

    public function Rules() { return $this->rule; }

    public function getAll() {
        return $this->db->get('lyrics')->result();
    }

    public function store($request){
        $this->title = $request->title;
        $this->artist = $request->artist;
        $this->genre = $request->genre;
        $this->lyric = $request->lyric;
        if($this->db->insert($this->table, $this)){
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id){
        $updateData = ['title' => $request->title, 'artist' => $request->artist, 'genre' => $request->genre, 'lyric' => $request->lyric];
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
}
?>