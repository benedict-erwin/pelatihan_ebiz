<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

    function __construct()
    {
       parent::__construct();
       $this->load->model('KaryawanModel');
    }
    
	public function index()
	{
        $this->load->helper(['url', 'html']);
		$this->load->view('karyawan');
    }
    
    public function generate_dummy_data()
    {
        // jumlah data yang akan di insert
        $jumlah_data = 1000;
        for ($i=1;$i<=$jumlah_data;$i++){
            $data   =   [
                "nama_lengkap"  =>  "Nama Karyawan Ke-".$i,
                "email"         =>  "karyawan-$i@gmil.com",
                "no_hp"         =>  "08" . rand(1000000000, 9999999999),
                "foto"          =>  "foto-karyawan-$i.jpg"
            ];
            $this->KaryawanModel->insertTable($data); 
        }
        echo $i.' Data Berhasil Di Insert';
    }

    public function ajax_list()
    {
        $post = $this->input->post();
        $list = $this->KaryawanModel->get_datatables($post);
        $data = array();
        $no = $post['start'];
        foreach ($list as $karyawan) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $karyawan->nama_lengkap;
            $row[] = $karyawan->email;
            $row[] = $karyawan->no_hp;
            $data[] = $row;
        }
 
        $output = [
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->KaryawanModel->count_all(),
            "recordsFiltered" => $this->KaryawanModel->count_filtered($post),
            "data" => $data,
        ];
        //output to json format
        header('Content-Type: application/json');
        echo json_encode($output);
    }

}
