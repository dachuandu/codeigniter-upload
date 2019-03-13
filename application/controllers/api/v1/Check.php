<?php

require_once './vendor/autoload.php';
require_once BASEPATH.'core/CodeIgniter.php';
use \Firebase\JWT\JWT;

/**
 * Created by PhpStorm.
 * User: 44573
 * Date: 2019/2/27
 * Time: 15:48
 */

/*
 * @property Check $check Optional description
 */
class Check extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Api_model');
        $this->config->load('config', true);

    }
    public function check_pid()
    {
        $key = 'dachuandu';
        $token='';
        if ($_SERVER["REQUEST_METHOD"] == 'POST') {   // 只处理post请求，否则options请求 500错误
            $json_params = file_get_contents('php://input');
            $data = json_decode($json_params, true);
            if (!empty($data)) {
                if (!empty($data['e_EMPNAME']) && !empty($data['e_PIDNO'])) {
                    $EMPNAME = $data['e_EMPNAME'];
                    $PIDNO = $data['e_PIDNO'];


                    $result = $this->Api_model->check_PID($EMPNAME, $PIDNO);

                    //判断是否存在token记录
                    $check_result = $this->check_token($PIDNO);
                    if($check_result['exists']){
                        //存在，查看是否过期
                        $date = $check_result['time'];//服務器上的註冊日期
                        $now = date('Y-m-d h:i:s');//當前日期
                        $interval = ceil((strtotime($now)-strtotime($date))/60);//分钟为单位
                        if($interval>60){
                            //超过一个小时则更新为新的token
                            $token= $this->generate_token($EMPNAME,$PIDNO,$key);
                            $this->update_token($PIDNO,$token);

                        }else{
                            //未超过一个小时
                            $token=$this->check_token($PIDNO)['token'];


                        }
//                        var_dump($date);
                        //判断是否过期
                    }else{
                        //不存在，把新的token存入数据库,并返回给客户端
                        $interval =0;
                        $token= $this->generate_token($EMPNAME,$PIDNO,$key);
                        $this->save_token($EMPNAME, $PIDNO,$token);

                    };
                    $data = array(
                        "code" => 20000,
                        "data" => array(
                            "token" => $token,
                            'interval' =>$interval
                        ),
                        "result" => $result
                    );
                   echo json_encode($data);
                }
            }
        }
    }

    public function generate_token($EMPNAME, $PIDNO, $key)
	{
        // $this->load->helper('cookie');
		    $token_array = array(
                "iss" => "http://dachuandu.org",
                "aud" => "http://dachuandu.com",
                'iat' => $_SERVER['REQUEST_TIME'],
                'exp' =>$_SERVER['REQUEST_TIME']+7200,
                'EMPNAME' =>$EMPNAME,
                'PIDNO' =>$PIDNO
            );

        $jwt = JWT::encode($token_array, $key);
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        return($jwt);
//        return($decoded);
    }
    public function check_token($PIDNO){
        $query = "select * from token_register where `PIDNO`='".$PIDNO."'";//该员工的token是否存在
        $exists = $this->db->query($query);

        if (($exists->row_array()) == null) {
            $result = array(
                'exists' => false,
                'time'=>'not exist',
                'token'=>''
            );

        } else {
            $result = array(
                'exists' => true,
                'time'=>$exists->row_array()['time'],
                'token'=>$exists->row_array()['token']
            );

        }
        return $result;
    }
    public function save_token($EMPNAME, $PIDNO, $token){
//        $deletequery= $this->db->query("delete from token_register where `PIDNO`='".$PIDNO."' and `imgId`='".$_POST['imgId']."';");
//
        $token_data = array(
            'token' =>  substr($token,0,28) ,
            'EMPNAME' => $EMPNAME ,
            'PIDNO' => $PIDNO,
            'time' => date('Ymdhis')
        );
        $this->db->insert('token_register', $token_data);
//        $query = $this->db->insert('urls',$newdata);
    }
     public function verify_token($PIDNO,$jwt,$key){
         $decoded = JWT::decode($jwt, $key, array('HS256'));
         $decoded_array = (array) $decoded;
//         if($decoded_array==$server_token)
         return true;
     }
     public function update_token($PIDNO,$token){
         $query = "update `token_register` set `token`='".$token."'where `PIDNO`='".$PIDNO."'";
         $success = $this->db->query($query);
         return $success;
     }


    public function check_img(){

        if ($_SERVER["REQUEST_METHOD"] == 'POST') {   // 只处理post请求，否则options请求 500错误
            $json_params = file_get_contents('php://input');
            $data = json_decode($json_params, true);

            if (!empty($data)) {
                if (!empty($data['PIDNO'])) {
                     $PIDNO = $data['PIDNO'];
                    $result = $this->Api_model->check_img($PIDNO);
                    $data = array(
                        "code" => 20000,
                        "data" => $data,
                        "token" => $_SERVER['HTTP_AUTHORIZATION'],
                    );
//                    echo json_encode($data);
                }
            }
        }
    }
    
}

