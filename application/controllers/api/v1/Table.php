<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Table extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Api_model');
//        $this->load->model('Record_model');
//        $this->load->model('Dept_model', 'Dept');
        $this->config->load('config', true);
    }

    public function index()
    {
        $this->load->view('login_view');
    }

    public function testapi()
    {
        echo "test api ok...";
    }

    public function phpinfo()
    {
        phpinfo();
    }

    public function testdb()
    {
        $this->load->database();
        $query = $this->db->query("show tables");
        var_dump($query);
        var_dump($query->result());
        var_dump($query->row_array());
//         有结果表明数据库连接正常 reslut() 与 row_array 结果有时不太一样
//        一般加载到时model里面使用。
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */