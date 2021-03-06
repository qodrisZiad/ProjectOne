<?php defined('BASEPATH') or exit('maaf akses anda ditutup.'); 
error_reporting(0);
class Subsubkategori extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('M_model');
	}
	private $table = "tm_subsubkategori";
	private $primary_key = "fc_kdsubsubkat";
	private $secondary_key = "fv_subsubkat";
	private $kolom = array("fc_kdkat","fc_kdsubkat","fc_kdsubsubkat","fv_kat","fv_subkat","fv_subsubkat","fv_pict","fc_status");
	public function index(){
		if(empty($this->session->userdata('userid'))){
			redirect('Login');
		}
        $hakakses_user = getAkses($this->uri->segment(1));
		$data = array(
			'subtitle'     =>'Master Sub sub Kategori',			
			'greeting'  => $this->session->userdata('greeting'),
			'nik'       => $this->session->userdata('userid'),
			'bread'     => 'Sub Sub Kategori',
			'sub_bread' => '',
			'input'		=> $hakakses_user[0],
			'update'	=> $hakakses_user[1],
			'delete'	=> $hakakses_user[2],
			'view'		=> $hakakses_user[3],
			'kategori'  => $this->getKategori() 
		);
		$this->load->view('Template/v_header',$data);
		$this->load->view('Template/v_datatable');
		$this->load->view('Template/v_sidemenu',$data);
		$this->load->view('v_view',$data);
		$this->load->view('Template/v_footer',$data);
	}
	public function Simpan(){
		$aksi = $this->input->post('aksi');
		$message = "";  
			if (!empty($_FILES['a4']['name'])) {
				upload('a4');
				$data = array(
					'fc_kdkat'    => $this->input->post('a1'), 
					'fc_kdsubkat'    => $this->input->post('a2'), 
					'fv_subsubkat'    => $this->input->post('a3'), 
					'fv_pict' => $_FILES['a4']['name'],
					'fc_status' => $this->input->post('a5')
				);
			}else{
				$data = array(
					'fc_kdkat'    => $this->input->post('a1'), 
					'fc_kdsubkat'    => $this->input->post('a2'), 
					'fv_subsubkat'    => $this->input->post('a3'), 
					'fc_status' => $this->input->post('a5')
				);
			}
			if ($aksi == 'tambah') {
				$proses = $this->M_model->tambah($data);
			}else if($aksi =='update'){
				$where = array($this->primary_key => $this->input->post('kode'));
				$proses = $this->M_model->update($data,$where);
			}
			if ($proses > 0) {
				$message = 'Berhasil menyimpan data';
			}else{
				$message = 'Gagal menyimpan data'; 
			} 
		echo json_encode($message);
	}
	public function Edit(){
		$kode = $this->uri->segment(3);
		$data = array($this->primary_key => $kode);
		$edit = $this->M_model->getData($data);
		echo json_encode($edit);
	} 
	public function Hapus(){
		$kode = $this->uri->segment(3);
		$foto = $this->uri->segment(4);
		$data = array($this->primary_key => $kode);
		$hapus = $this->M_model->hapus($data);
		if ($hapus > 0) {
			$dir = "./assets/foto/".$foto;
			unlink($dir);
			echo "Berhasil menghapus data";
		}else{
			echo "Gagal menghapus data";
		}
	}
	public function data(){ 
		$tabel = $this->table;  
		$limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $kolom[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir']; 
        $totalData = $this->M_model->allposts_count($tabel); 
        $totalFiltered = $totalData;  
        if(empty($this->input->post('search')['value']))
        {            
            $posts = $this->M_model->allposts($tabel,$limit,$start,$order,$dir);
        }
        else {
            $search = $this->input->post('search')['value'];  
            $posts =  $this->M_model->posts_search($tabel,$this->primary_key,$this->secondary_key,$limit,$start,$search,$order,$dir); 
            $totalFiltered = $this->M_model->posts_search_count($tabel,$this->primary_key,$this->secondary_key,$search);
        } 
        $data = array();
        if(!empty($posts))
        {	$no = 1;
            foreach ($posts as $post)
            { 	
                $nestedData['no'] = $no++;
                for ($i=0; $i < count($this->kolom) ; $i++) {
                	$hasil = $this->kolom[$i]; 
                	$nestedData[$this->kolom[$i]] = $post->$hasil;
                }  
                $data[] = $nestedData; 
            }
        } 
        $json_data = array(
                    "draw"            => intval($this->input->post('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    ); 
        echo json_encode($json_data); 
	} 
	public function getKategori(){
		$jabatan = $this->M_model->getKategori();
		$arr_data = array();
			$arr_data[""] = "Pilih Kategori";
	 	 foreach ($jabatan as $hasil) {
	 	 	$arr_data[$hasil->fc_kat] = $hasil->fv_kat; 
	 	 }
		return $arr_data;
	}
	public function getSubkategories(){
		if (!empty($this->uri->segment(3))) {
			$where = array('fc_kat' => $this->uri->segment(3),'fc_status' => '1');
		}else{
			$where = array('fc_status' => '1');
		}
		$subkategori = $this->M_model->getSubkategori($where);
		$data = "";
	 	 foreach ($subkategori as $hasil) { 
	 	 	$data .= "<option value='".$hasil->fc_subkat."'>".$hasil->fv_subkat."</option>";  
	 	 }
		echo $data;
	}
}