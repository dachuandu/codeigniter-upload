<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @property Api_model $api_model Optional description
 */

class Api_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function saveUserWxUserId($id, $wxUserId, $wxUserName)
    {
        $sql = "UPDATE userinfo SET wxUserName='" . $wxUserName . "',wxUserId='" . $wxUserId . "' WHERE id=" . $id;
        $this->db->query($sql);
    }

    function getUserByWxUserId($wxUserId)
    {
        $sql = "select Id id,
        USERNAME name,
        USERALIAS,
        USERPASS,
        USERDES,
        LASTLOGIN,
        LASTIP,
        CPASS,
        USERPROP,
        CTIME,
        UTIME,wxUserId,wxUserName from userinfo where status = 1 and wxUserId='" . $wxUserId . "'";
        $query = $this->db->query($sql);
        if (($query->row_array()) == null) {
            $result = array(
                'success' => false,
                'userinfo' => null
            );
        } else {
            $result = array(
                'success' => true,
                'userinfo' => $query->row_array()
            );
        }
        return $result;
    }
    //剪切文件方法：
    function cutFile($filename,$dest){
    //检测文件是否存在
    if(!file_exists($filename)){
        return false;
    }
    //检测目标目录是否存在 不存在则创建
    if(!is_dir($dest)){
        mkdir($dest,0777,true);
    }
    //剪切后的文件路径
    $newFilePath=$dest.DIRECTORY_SEPARATOR.basename($filename);
    //检测目标路径是否已存在同名文件
    if(file_exists($newFilePath)){
        return false;
    }
    //剪切文件
    if(rename($filename,$newFilePath)){
        return true;
    };
    return false;

}
    function uploadImg2(){
        $this->load->helper('file');
        $config['upload_path'] = './uploads';
        $config['allowed_types'] = '*';
        $config['max_size'] = '20000';//允许上传文件大小的最大值:20M
        $config['max_width'] = '3000';
        $config['max_height'] = '3000';
        $config['file_name'] = uniqid();
        $this->load->library('upload');
        $this->upload->initialize($config);
        $result = $this->upload->do_upload('userfile',$config);

        if(!$result){
            $error = array('error' => $this->upload->display_errors());
            echo json_encode($error);
        }else{
            //时间
            $BaseUrl = "http://192.168.31.170/testupload-php/uploads/";
            $date=date('Ymdhis');
            //发送来的文件名、分解得到文件类型
            $fileName=$this->upload->data()['file_name'];
            $name=explode('.',$fileName);
            //寻找、创建文件夹
            $ID_folder='./uploads/'.$_POST['folderName'].'/';
            if (!is_dir($ID_folder)) {mkdir($ID_folder);};
            //去掉第一个点
            $ID_folder = substr($ID_folder,2);
            //组合新文件名
            $new_file_name =$_POST['imgId'].'_'.$date.'.'.$name[1];
            //完整路径
            $newPath=$this->upload->data()['file_path'].$_POST['folderName'].'/'.$new_file_name;
            $newUrl = $BaseUrl.$_POST['folderName'].'/'.$new_file_name;
            // $newPath=$this->upload->data()['file_path'].$new_file_name;
            $oldPath=$this->upload->data()['full_path'];
            // var_dump("老文件名：".$oldPath."新文件名：".$newPath);
            //删除之前所有的imgId文件
            $dirname =$this->upload->data()['file_path'].$_POST['folderName'].'/';
            $dirInfo = glob($dirname.'/'.$_POST['imgId'].'*');
            foreach($dirInfo as $f){
                unlink($f);
            };
            
            rename($oldPath,$newPath);
            //存到服务器里
            $newdata = array(
                'PIDNO' => $_POST['folderName'] ,
                'imgId'=>$_POST['imgId'],
                'srcurl' => $newUrl ,
                'time' => $date
                );
                //删除老数据
                $deletequery= $this->db->query("delete from urls where `PIDNO`='".$_POST['folderName']."' and `imgId`='".$_POST['imgId']."';");

            $query = $this->db->insert('urls',$newdata);
            
            $data = array('upload_data' => $this->upload->data(),
            'success'=>true,
            'folderName'=>$_POST['folderName'],
                'new_file_name'=>$new_file_name,'new_path'=>urlencode($newPath));
            echo json_encode($data);
        }
    }
    function check_PID($EMPNAME, $PIDNO){
        $query = $this->db->query("SELECT * FROM  temp_emp where `EMPNAME` = '".$EMPNAME."' and `PIDNO` = '".$PIDNO."'");
        if (($query->row_array()) == null) {
            $result = array(
                'success' => false,
                'MOBILEPHONE' => null
            );
        } else {
            
            $result = array(
                'success' => true,
               'EMPNAME' => $query->row_array()['EMPNAME'],
               'PIDNO' => $query->row_array()['PIDNO'],
                'MOBILEPHONE' => $query->row_array()['MOBILEPHONE']
            );
        return json_encode($result);
    }
    }
    function check_img($PIDNO){
    $query = $this->db->query("SELECT * FROM urls where `PIDNO` ='".$PIDNO."'");
    if (($query->row_array()) == null) {
        // if nothing found:
            $result = array(
                'success' => false,
                'imgs' => null
            );
            echo json_encode($result);
        } else {
            $rows = $query->result();

                        $result = array(
                'success' => true,
                'imgs' => $rows
            );
            echo json_encode($result);
            // foreach($rows as $row){
            //     echo json_encode($row);
            // }
            // $result = array(
            //     'success' => true,
            //    'EMPNAME' => $query->row_array()['EMPNAME'],
            //    'PIDNO' => $query->row_array()['PIDNO'],
            //     'MOBILEPHONE' => $query->row_array()['MOBILEPHONE']
            // );
        }
    }
    function change_phone($userIdNo,$newnumber){
        $query = "update `temp_emp` set `MOBILEPHONE`='".$newnumber."'where `PIDNO`='".$userIdNo."'";
        $success = $this->db->query($query);//返回true false
        if($success){
            $query= "select * from `temp_emp` where `PIDNO`='".$userIdNo."'";
            $result=$this->db->query($query)->row_array()['MOBILEPHONE'];
            
            return json_encode($result);
        }else{
            
            return json_encode($success);
        }
        
    }

   




}