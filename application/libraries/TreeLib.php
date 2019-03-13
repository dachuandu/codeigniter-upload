<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Tree Class
 *
 * 转换EasyuiTree函数
 *
 */

class Treelib {
	
	private $idKey = 'id'; 			//主键的键名
	private $fidKey = 'fid'; 		//父ID的键名
	private $root = 0; 				//最顶层fid
    private $pId = 0; 				//父fid
	private $data = array(); 		//源数据
	private $treeArray = array(); 	//属性数组
    private $state='closed';        //默认关闭
	
	
	/**
	 * 获得一个带children的树形数组
	 * @return multitype:
	 */
	public function getTreeArray($data,$idKey,$fidKey,$root,$closed='0')
	{
		if($idKey) $this->idKey = $idKey;
		if($fidKey) $this->fidKey = $fidKey;
		if($root) $this->root = $root;

		if($data) {
            //var_dump($data);
			$this->data = $data;
			$this->getChildren($this->root,$closed);

		}
		
		//去掉键名
        //var_dump($this->treeArray);
		return array_values($this->treeArray);
	}
	
	/**
	 * @param int $root 父id值
	 * @return null or array
	 */
	private function getChildren($root,$closed)
	{
		$children='';

		foreach ($this->data as &$node){
			if($root == $node[$this->fidKey]){
				$node['children'] = $this->getChildren($node[$this->idKey],$closed);
				$children[] = $node;
			}
			//只要一级节点
			if($this->root == $node[$this->fidKey]){
                //$s=array('state'=>'close');
                //array_push($node,'close');
                if ($closed)
                {
                    $node['state']=$this->state;
                }

				$this->treeArray[$node[$this->idKey]] = $node;

			}
		}
		return $children;
	}

    /*
     * 生成树结构
     */
    function genTree($data, $idKey, $fidKey, $pId)
    {
//        $tree = '';
        $tree = array();
        foreach($data as $k => $v)
        {
            // 找到父节点为$pId的节点，然后进行递归查找其子节点，
            // 同时将子节点赋值至该节点的'children'元素，同时判断是否叶子节点
            if($v[$fidKey] == $pId)
            {
                $v['children'] = $this->genTree($data, $idKey, $fidKey, $v[$idKey]);
                //   print_r($pId);
                $v['isLeaf']=$v['children']?0:1;
                $v['state']=$v['children']?'closed':'open';
                $tree[] = $v;     // 循环数组添加元素 属于同一层级
                //   print_r($tree);
            }
        }
        return $tree;
    }

    /**
     * 将数据格式化成树形结构 ___非递归方式，使用了数组指针，与前面json方式一样，array 必须以1开头___
     * @author Xuefen.Tong
     * @param array $items
     * @return array
     */
    function genTree9($items) {
        $tree = array(); //格式化好的树
        foreach ($items as $item)
            if (isset($items[$item['pid']]))
                $items[$item['pid']]['son'][] = &$items[$item['id']];
            else
                $tree[] = &$items[$item['id']];
        return $tree;
    }


    public function getMenuArray($data,$idKey,$fidKey,$root)
	{
		if($idKey) $this->idKey = $idKey;
		if($fidKey) $this->fidKey = $fidKey;
		if($root) $this->root = $root;
		if($data) {
			$this->data = $data;
			$this->getSubMenu($this->root);
		}
		
		//去掉键名
		$b=array_values($this->treeArray);
		
		return "{basic:".json_encode($b)."}";
		
	}
	
	private function getSubMenu($root)
	{
		$menus='';
		foreach ($this->data as &$node){
			if($root == $node[$this->fidKey]){
				$node['menus'] = $this->getSubMenu($node[$this->idKey]);
				$menus[] = $node;
			}
			//只要一级节点
			if($this->root == $node[$this->fidKey]){
				$this->treeArray[$node[$this->idKey]] = $node;
			}
		}
		return $menus;
		
	}
	
	
	
}