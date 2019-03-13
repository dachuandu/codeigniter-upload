<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Tree Class
 *
 * 转换EasyuiTree函数
 *
 */

class Loglib {

    function addlog($useract,$logdata)
    {
        $CI = &get_instance();
        $CI->load->model('Bas_model');


        $Data['USERACT']=$useract;
        $Data['LOGDATA']=$logdata;

        //$result=$CI->Bas_model->saveAdd('au_log',$Data);
        $result=$CI->Bas_model->saveAdd('log',$Data); //获取权限关联表数据
        return $result;

    }
	
	
	
}