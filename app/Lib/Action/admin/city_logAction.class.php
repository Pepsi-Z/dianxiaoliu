<?php

class city_logAction extends backendAction
{
    public function _initialize() {
        $order_status=array(1=>'待配送',2=>'已配送',3=>'已完成');
        $this->assign('order_status',$order_status);
    }

    /*public function index() {
        $item_order = M("item_order");
        $map = array();
        if(trim($_GET['start_time']) && trim($_GET['end_time'])){
            $this->assign('start_time',trim($_GET['start_time']));
            $this->assign('end_time',trim($_GET['end_time']));
            $st = strtotime(trim($_GET['start_time']));
            $et = strtotime(trim($_GET['end_time']));
            if($st < $et){
                $arr = array($st,$et);
            }elseif($st > $et){
                $arr = array($et,$st);
            }
            $map['o.add_time'] = array('between',$arr);
        }
        ($status = $this->_request('status', 'trim')) && $map['o.status'] = array('eq', $status);
        ($orderId = $this->_request('orderId', 'trim')) && $map['f.title'] = array('like', '%'.$orderId.'%');
        ($userfree = $this->_request('userfree', 'trim')) && $map['o.userfree'] = array('like', '%'.$userfree.'%');
        $this->assign('search', array(
            'status' =>$status,
            'orderId' => $orderId,
            'userfree' => $userfree
        ));
        $join = array(
                'o left join __ORDER_DETAIL__ d on o.orderId = d.orderId',
                'left join __ITEM__ e on d.itemId = e.id',
                'left join __MERCHANT__ f on e.sid = f.id',
        );
        $list = $item_order->field('o.*,d.title,d.price,d.quantity,f.title as c_title')->join($join)->where($map)->order('o.id DESC')->select();
        $order_sumPrice = $item_order->sum("order_sumPrice");
        $con = count($list);
        $all_num = '';
        foreach($list as $key=>$val){

            $all_num += $val['order_sumPrice'];
        }
        $p = new Page($con,20);
        $list = array_slice($list,$p->firstRow,$p->listRows);
        $show = $p->show();
        $this->assign('con',$con);
        $this->assign('order_sumPrice',$all_num);
        $this->assign('page',$show);
        $this->assign('list',$list);
        $this->display();
    }*/


    public function index(){

        $map = array();
        if(trim($_GET['start_time']) && trim($_GET['end_time'])){
            $this->assign('start_time',trim($_GET['start_time']));
            $this->assign('end_time',trim($_GET['end_time']));
            $st = strtotime(trim($_GET['start_time']));
            $et = strtotime(trim($_GET['end_time']));
            if($st < $et){
                $arr = array($st,$et);
            }elseif($st > $et){
                $arr = array($et,$st);
            }
            $map['add_time'] = array('between',$arr);
        }
        ($status = $this->_request('status', 'trim')) && $map['status'] = array('eq', $status);
        ($userfree = $this->_request('userfree', 'trim')) && $map['userfree'] = array('like', '%'.$userfree.'%');
        $this->assign('search', array(
            'status' =>$status,
            'userfree' => $userfree
        ));


        $list = M('cityexpress_order')->where($map)->order('id DESC')->select();
        $all_num=0;
        foreach($list as $key=>$val){

            $all_num += $val['price'];
        }
        $con = count($list);
        $p = new Page($con,20);
        $list = array_slice($list,$p->firstRow,$p->listRows);
        $show = $p->show();
        $this->assign('order_sumPrice',$all_num);
        $this->assign('con',$con);
        $this->assign('page',$show);
        $this->assign('list',$list);


        $this->display();

    }
}