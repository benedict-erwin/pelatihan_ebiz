<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

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
        if (!$this->session->userdata('isLogin')) {
            redirect('login');
        }
    }

    public function index()
    {
        $this->template->adminlte('home');
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
            $row[] = $karyawan->id_karyawan;
            $row[] = $no;
            $row[] = $karyawan->nama_lengkap;
            $row[] = $karyawan->email;
            $row[] = $karyawan->no_hp;
            $data[] = $row;
        }
 
        $output = [
            "draw" => $post['draw'],
            "recordsTotal" => $this->KaryawanModel->count_all(),
            "recordsFiltered" => $this->KaryawanModel->count_filtered($post),
            "data" => $data,
        ];

        //output to json format
        header('Content-Type: application/json');
        echo json_encode($output);
    }

    public function create()
    {
        $msg = $this->KaryawanModel->insertTable($this->input->post());
        $output['success'] = $msg['success'];
        $output['message'] = $msg['message'];
        
        //output to json format
        header('Content-Type: application/json');
        echo json_encode($output);
    }

    public function update()
    {
        $msg = $this->KaryawanModel->updateTable($this->input->post('id_karyawan'), $this->input->post());
        $output['success'] = $msg['success'];
        $output['message'] = $msg['message'];
        //output to json format
        header('Content-Type: application/json');
        echo json_encode($output);
    }

    public function delete()
    {
        $msg = $this->KaryawanModel->deleteTable($this->input->post('id_karyawan'));
        $output['success'] = $msg['success'];
        $output['message'] = $msg['message'];
        //output to json format
        header('Content-Type: application/json');
        echo json_encode($output);
    }

    public function generate_dummy()
    {
        // jumlah data yang akan di insert
        $jumlah_data = 1000;
        for ($i = 1; $i <= $jumlah_data; $i++) {
            $data = [
                "nama_lengkap" => "Nama Karyawan Ke-" . $i,
                "email" => "karyawan-$i@gmil.com",
                "no_hp" => "08" . rand(1000000000, 9999999999),
                "foto" => "foto-karyawan-$i.jpg"
            ];
            $this->KaryawanModel->insertTable($data);
        }
        echo $i . ' Data Berhasil Di Insert';
    }


}
