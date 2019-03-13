<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 后台管理菜单操作模型
 *
 * @package     
 * @subpackage  Models
 * @category    Models
 * @author      Team
 */
class Bas_model extends CI_Model
{
	
    function __construct()
     {
         parent::__construct();
         $this->load->database();
     }
     
     function saveAdd($table,$data)
     {
     	$this->db->insert($table,$data);
		return $this->db->insert_id();
     }
     
	function saveEdit($table,$data,$where)
	{
		$this->db->where($where);
    	$this->db->update($table,$data);
    	return $this->db->affected_rows();
	}
     
     function isExist($table,$where)
     {
     	$this->db->where($where);
     	$this->db->from($table);
		return $this->db->count_all_results();
     }
     
	function saveDel($table,$where)
	{
		$this->db->where($where);
    	$this->db->delete($table);
    	return $this->db->affected_rows();
	}
	
	function select($table,$select='',$where='',$order='')
	{
        if ($select) { $this->db->select($select); }
		if ($where) { $this->db->where($where); }
        if ($order) { $this->db->order_by($order); }

		$query = $this->db->get($table);
		return $query->result();
		
		
	}

    function total($table,$field,$keyword)
    {
        $sql="select count(*) numrows from $table where $field like '%$keyword%' ";
        $query=$this->db->query($sql);
        if( ($query->row_array())==null ){
            return 0;
        }else
        {
            $result=$query->result_array();
            return $result;
        }
    }

}