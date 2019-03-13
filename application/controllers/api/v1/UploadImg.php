<?php
/**
 * Created by PhpStorm.
 * User: 44573
 * Date: 2019/2/28
 * Time: 17:43
 */

class UploadImg extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Api_model');
        $this->config->load('config', true);
    }
    function upload(){
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->helper('form');
        $this->load->library('upload');
        $result = $this->Api_model->uploadImg2();

    }
}