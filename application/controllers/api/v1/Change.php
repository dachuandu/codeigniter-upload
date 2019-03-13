<?php
/**
 * Created by PhpStorm.
 * User: 44573
 * Date: 2019/2/27
 * Time: 15:48
 */

class Change extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Api_model');

        $this->config->load('config', true);
    }
    public function change_phone(){
                $json_params = file_get_contents('php://input');
                if ($_SERVER["REQUEST_METHOD"] == 'POST') {   // 只处理post请求，否则options请求 500错误
                    
            $json_params = file_get_contents('php://input');
            $data = json_decode($json_params,true);

                // if (!empty($data['oldnumber']) && !empty($data['newnumber'])) {
                    if (true) {
                    $userIdNo = $data['userIdNO'];
                    $newnumber = $data['newnumber'];
                    $result = $this->Api_model->change_phone($userIdNo,$newnumber);

                    $data = array(
                        "code" => 20000,
                        "data" => array(
                            "token" => $_SERVER['HTTP_AUTHORIZATION']
                        ),
                        "result" => $result
                    );
                   echo json_encode($data);
                }else{
                    echo "wrong input!";
                };
            }
        }


}