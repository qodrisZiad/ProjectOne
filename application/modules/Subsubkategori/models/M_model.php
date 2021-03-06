<?php defined('BASEPATH') or exit('maaf akses anda kita tutup');
class M_model extends CI_Model
{
	private $table = "tm_subsubkategori";
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
    function getData($where){
        $query = $this->db->where($where)->get($this->table);
        if($query->num_rows()>0)
        { return $query->row();}
        else{return null;}
    } 
    function getKategori(){
    	$query = $this->db->where(array('fc_status' => '1'))->get('tm_kategori');
    	return $query->result();
    }

    function getSubkategori($where){
    	$query = $this->db->where($where)->get('tm_subkategori');
    	return $query->result();
    }
// ---------------------------------------------------------------------
	//update data
	function update($data,$where){
		$query = $this->db->update($this->table,$data,$where);
		return $this->db->affected_rows();
	}
	//ini khusus untuk datatablenya
    function allposts_count($tabel)
    {   
        $query = $this->db->get($tabel);
        return $query->num_rows();  
    } 
    function allposts($tabel,$limit,$start,$col,$dir)
    {   
       $query = $this->db->select('a.*,b.fv_kat,c.fv_subkat')
       			->from('tm_subsubkategori a')
       			->join("tm_kategori b","a.fc_kdkat=b.fc_kat")
       			->join("tm_subkategori c","a.fc_kdkat=c.fc_kat and a.fc_kdsubkat=c.fc_subkat")
       			->limit($limit,$start)
       			->order_by($col,$dir)
       			->get();
        if($query->num_rows()>0)
        { return $query->result();}
        else{return null;}
    }
    function posts_search($tabel,$field1,$field2,$limit,$start,$search,$col,$dir)
    {
        $query = $this->db->select('a.*,b.fv_kat,c.fv_subkat')
		       			->from('tm_subsubkategori a')
		       			->join("tm_kategori b","a.fc_kdkat=b.fc_kat")
		       			->join("tm_subkategori c","a.fc_kdkat=c.fc_kat and a.fc_kdsubkat=c.fc_subkat")
        				->like($field1,$search)
                        ->or_like($field2,$search)
                        ->limit($limit,$start)
                        ->order_by($col,$dir)->get();
        if($query->num_rows()>0)
        { return $query->result(); }
        else { return null; }
    } 
    function posts_search_count($tabel,$field1,$field2,$search)
    {   $query = $this->db->select('a.*,b.fv_kat,c.fv_subkat')
		       			->from('tm_subsubkategori a')
		       			->join("tm_kategori b","a.fc_kdkat=b.fc_kat")
		       			->join("tm_subkategori c","a.fc_kdkat=c.fc_kat and a.fc_kdsubkat=c.fc_subkat")
		       			->like($field1,$search)
		       			->or_like($field2,$search)
		       			->get();
        return $query->num_rows();  }
}