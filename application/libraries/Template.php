<?php
class Template
{
    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function adminlte($view = "index", $data = false)
    {
        $file_header = __FUNCTION__ . "/header";
        $file_view = __FUNCTION__ . "/" . $view;
        $file_footer = __FUNCTION__ . "/footer";
        
        $this->CI->load->view($file_header, $data);
        $this->CI->load->view($file_view, $data);
        $this->CI->load->view($file_footer, ['script' => $view]);
    }

    public function login()
    {
        $this->CI->load->view('adminlte/login');
    }
}

?>
