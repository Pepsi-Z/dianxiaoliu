<?php
class GoodsAction extends  membersAction {
	public function _initialize(){
        parent::_initialize();
       $this->_mod = D('goods');
    }  
	 public function index(){
         $fenlei = M('goodstype')->field('id,typename')->select();
         $this->assign('fenlei',$fenlei);
         $this->display();
	 }

    public function search_child(){
        $cate_id = $_GET['cate_id'];
        $list = M('goods')->where("typeid = ".$cate_id)->select();
        echo json_encode($list);
    }

}