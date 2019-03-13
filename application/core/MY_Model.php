<?php defined('BASEPATH') OR exit('No direct script access allowed');


class MY_Model extends CI_Model
{
    /**
     * [insert 插入数据函数]
     * @param  [type] $table [插入表名]
     * @param  [type] $data  [插入数据]
     * @return [type]        [返回插入ID]
     */
    public function insert($table,$data)
    {
        $this->db->insert($table,$data);
        return $this->db->insert_id();
    }

    /**
     * [update 更新数据操作]
     * @param  [type] $table [需要更新的表名]
     * @param  [type] $data  [更新的数据数组]
     * @param  [type] $where [更新的条件]
     * @return [type]        [返回影响结果int]
     */
   public function update($table,$data,$where)
    {
        $this->db->where($where);
        $this->db->update($table,$data);
        return $this->db->affected_rows();
    }


    public function select($table,$select='',$where='',$order='',$by='')
    {
        if ($select) { $this->db->select($select); }
        if ($where) { $this->db->where($where); }
        if ($order) {
            if ($by=='')
            {
                $this->db->order_by($order);
            }else
            {
                $this->db->order_by($order,$by);
            }

        }
        $query = $this->db->get($table);
        return $query->result();      
    }

    public  function delete($table,$where)
    {
        $this->db->where($where);
        $this->db->delete($table);
        return $this->db->affected_rows();
    }

    function isExist($table,$where)
    {
        $this->db->where($where);
        $this->db->from($table);
        return $this->db->count_all_results();
    }

    function execute($sql)
    {
        $query=$this->db->query($sql);
        if( ($query->row_array())==null)
        {
            return null;
        }
        else
        {
            $result=$query->result();
            return $result;
        }

    }
}