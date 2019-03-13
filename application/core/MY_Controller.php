<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Bas_model', 'Bas');
        /*
         * 定义全局变量
         */
        $this->INTDATE = time();
        $this->TIME = date('Y-m-d H:i:s', time());
        $this->IP = $this->input->ip_address();
        $this->SET_HEADER = $this->set_header();



    }


    function set_header()
    {
        header('content-type:application:json;charset=utf8');
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE,PATCH,OPTIONS');
        header('Access-Control-Allow-Headers:x-requested-with,content-type,x-auth-token,token,X-Token');

//        header('Access-Control-Allow-Origin:*');
//        header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE,PATCH,OPTIONS');
//        header('Access-Control-Allow-Headers:Content-Type, X-ELEME-USERID, X-Eleme-RequestID, X-Shard,X-Shard, X-Eleme-RequestID,X-Adminid,X-Token');
    }

}