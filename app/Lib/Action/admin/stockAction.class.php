<?php

class stockAction extends backendAction
{
    public function index(){
        $stock = M("item");
        $map = array();
        $status = $this->_request('status');
        $cate_id = $this->_request('cate_id');
        $title = $this->_request('title', 'trim');
        ($stock_min = $this->_request('stock_min', 'trim')) && $map['goods_stock'][] = array('egt',$stock_min);
        ($stock_max = $this->_request('stock_max', 'trim')) && $map['goods_stock'][] = array('elt', $stock_max);
        if($status){
            $map['status'] = $status;
            if($status == 2){
                $map['status'] = 0;
            }
            $this->assign('status',$status);
        }
        if($title){
            $map['title'] = array('like', '%'.$title.'%');
            $this->assign('title',$title);
        }
        if ($cate_id) {
            $map['cate_id'] = array('eq', $cate_id);
            $this->assign('cate_id',$cate_id);
        }
        $this->assign('stock_max',$stock_max);
        $this->assign('stock_min',$stock_min);
        $map['type'] = '1';
        $order = "add_time desc";
        $list = $stock->where($map)->order($order)->select();
        /*
         * array_slice实现分页
         */
        $item_cate = M('item_cate')->getField('id,name',true);
        $cate = M('item_cate')->select();
        $this->assign('item_cate',$item_cate);
        $this->assign('cate',$cate);
        $p = new Page(count($list),20);
        $list = array_slice($list , $p->firstRow , $p->listRows);
        $show = $p->show();
        $this->assign('page',$show);
        $this->assign('list',$list);
        $this->display();
    }

}