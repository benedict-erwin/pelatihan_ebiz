<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

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
    }

	public function index()
	{
		$this->template->login();
    }
    
    public function verifyMe()
    {
        if ($this->input->post('username')=='admin' && $this->input->post('password')=='123') {
            $this->session->set_userdata(
                [
                    'username' => 'Administrator',
                    'isLogin' => true,
                    'akses' => 1
                ]
            );

            $output['success'] = true;
            $output['message'] = 'Login sukses';
        } else {
            $output['success'] = false;
            $output['message'] = 'Login gagal!';
        }
        
        //output to json format
        header('Content-Type: application/json');
        echo json_encode($output);
    }

    public function never()
    {
        $this->session->sess_destroy();
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    }

}
