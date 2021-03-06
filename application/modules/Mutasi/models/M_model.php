<?php defined('BASEPATH') or exit('maaf akses anda kita tutup');
class M_model extends CI_Model
{
	private $table = "tm_mutasi";
	function tambah($tabel,$data){
		$this->db->insert($tabel,$data);
		return $this->db->affected_rows();
	}
	//delete data
	function hapus($tabel,$where){
		$this->db->where($where);
		$this->db->delete($tabel);
		return $this->db->affected_rows();
	}
// ---------------------------------------------------------------------
    //ambil data
    function getData($tabel,$where, $out=0){
		$query = $this->db->where($where)->get($tabel);
		if ($out == 0) {
			if($query->num_rows()>0){ return $query->row();}else{return null;}
		} else 
		if ($out==1){
			if($query->num_rows()>0){ return $query->result();}else{return null;}
		}
    }  
// ---------------------------------------------------------------------
// ---------------------------------------------------------------------
// check master sudah ada belum
    function checkMst($where){
    	$query = $this->db->where($where)->get("tm_mutasi");
    	return $query->num_rows();
    }
// ---------------------------------------------------------------------
	//update data
	function update($tabel,$data,$where){
		$query = $this->db->update($tabel,$data,$where);
		return $this->db->affected_rows();
	}
	//ini khusus untuk datatablenya
    function allposts_count($tabel,$where = "")
    {   
    	if (!empty($where)) {
        	$query = $this->db->where($where)->get($tabel);
    	}else{
        	$query = $this->db->get($tabel);
    	}
        return $query->num_rows();  
    } 
    function allposts($tabel,$limit,$start,$col,$dir,$where = "")
    {   
    	if(!empty($where)){
       		$query = $this->db->where($where)->limit($limit,$start)->order_by($col,$dir)->get($tabel);
    	}else{
       		$query = $this->db->limit($limit,$start)->order_by($col,$dir)->get($tabel);
    	}
        if($query->num_rows()>0)
        { return $query->result();}
        else{return null;}
    }
    function posts_search($tabel,$field1,$field2,$limit,$start,$search,$col,$dir,$where = "")
    {
    	if (!empty($where)) {
    		$query = $this->db->where($where)
    					 ->group_start()
	    					 ->like($field1,$search)
	                         ->or_like($field2,$search)
	                     ->group_end()
                         ->limit($limit,$start)
                         ->order_by($col,$dir)->get($tabel);
    	}else{
        	$query = $this->db->like($field1,$search)
                         ->or_like($field2,$search)
                         ->limit($limit,$start)
                         ->order_by($col,$dir)->get($tabel);
    	}
        if($query->num_rows()>0)
        { return $query->result(); }
        else { return null; }
    } 
    function posts_search_count($tabel,$field1,$field2,$search,$where = "")
    {   if (!empty($where)) {
    		$query = $this->db->where($where)
    					 ->group_start()
	    					 ->like($field1,$search)
	                         ->or_like($field2,$search)
	                     ->group_end()
                         ->limit($limit,$start)
                         ->order_by($col,$dir)->get($tabel);
    	}else{
        	$query = $this->db->like($field1,$search)
                         ->or_like($field2,$search)
                         ->limit($limit,$start)
                         ->order_by($col,$dir)->get($tabel);
    	}
        return $query->num_rows();  }
}