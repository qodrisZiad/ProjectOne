<?php defined('BASEPATH') or exit('maaf akses anda kita tutup');
class M_model extends CI_Model
{
	private $table = "v_POMST";
	function tambah($data){
		$this->db->insert($this->table,$data);
		return $this->db->affected_rows();
	}
	//delete data
	function hapus($where){
		$this->db->where($where);
		$this->db->delete($this->table);
		return $this->db->affected_rows();
	}
// ---------------------------------------------------------------------
    //ambil data
    function getData($tabel,$where){
        $query = $this->db->where($where)->get($tabel);
        if($query->num_rows()>0)
        { return $query->row();}
        else{return null;}
    } 
    function getDetail($table,$where){
        $data = $this->db->where($where)->get($table);
        return $data->result();
    }
// ---------------------------------------------------------------------
	//update data
	function update($table,$data,$where){
		$query = $this->db->update($table,$data,$where);
		return $this->db->affected_rows();
	}
	//ini khusus untuk datatablenya
    function allposts_count($tabel)
    {   
        $query = $this->db->where(array("fc_approve" => "1","fc_print" => "0"))->get($tabel);
        return $query->num_rows();  
    } 
    function allposts($tabel,$limit,$start,$col,$dir)
    {   
       $query = $this->db->where(array("fc_approve" => "1","fc_print" => "0"))->limit($limit,$start)->order_by($col,$dir)->get($tabel);
        if($query->num_rows()>0)
        { return $query->result();}
        else{return null;}
    }
    function posts_search($tabel,$field1,$field2,$limit,$start,$search,$col,$dir)
    {
        $query = $this->db->where(array("fc_approve" => "1","fc_print" => "0"))
        				 ->like($field1,$search)
                         ->or_like($field2,$search)
                         ->limit($limit,$start)
                         ->order_by($col,$dir)->get($tabel);
        if($query->num_rows()>0)
        { return $query->result(); }
        else { return null; }
    } 
    function posts_search_count($tabel,$field1,$field2,$search)
    {   $query = $this->db->where(array("fc_approve" => "1","fc_print" => "0"))->like($field1,$search)->or_like($field2,$search)->get($tabel);
        return $query->num_rows();  }
}