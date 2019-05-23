<?php

class cityexpress_orderAction extends backendAction
{
    public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('cityexpress_order');
        $this->_cate_mod = D('score_item_cate');
    }

    protected function _search() {
        $map = array();
        ($time_start = $this->_request('time_start', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));
        ($time_end = $this->_request('time_end', 'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        ($order_score_min = $this->_request('order_score_min', 'trim')) && $map['order_score'][] = array('egt', $order_score_min);
        ($order_score_max = $this->_request('order_score_max', 'trim')) && $map['order_score'][] = array('elt', $order_score_max);
        ($rates_min = $this->_request('rates_min', 'trim')) && $map['rates'][] = array('egt', $rates_min);
        ($rates_max = $this->_request('rates_max', 'trim')) && $map['rates'][] = array('elt', $rates_max);
        ($uname = $this->_request('uname', 'trim')) && $map['uname'] = array('like', '%'.$uname.'%');
        $cate_id = $this->_request('cate_id', 'intval') && $map['cate_id'] = array('IN', $cate_id);
        if( $_GET['status']==null ){
            $status = -1;
        }else{
            $status = intval($_GET['status']);
        }
        $status>=0 && $map['status'] = array('eq',$status);
        ($keyword = $this->_request('keyword', 'trim')) && $map['order_sn'] = array('like', '%'.$keyword.'%');
        $this->assign('search', array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'order_score_min' => $order_score_min,
            'order_score_max' => $order_score_max,
            'rates_min' => $rates_min,
            'rates_max' => $rates_max,
            'uname' => $uname,
            'status' =>$status,
            'cate_id' => $cate_id,
            'keyword' => $keyword,
        ));
        return $map;
    }


   
    public function delivery(){
    	$id = $this->_get('id','intval');
    	$data['status']=2;
        $data['warning']=0;
    	$a = M('cityexpress_order')->where('id = '.$id)->save($data);
    	if( $a ){
            IS_AJAX && $this->ajaxReturn(1, L('operation_success'), '', 'add');
            $this->success(L('operation_success'));
        } else {
            IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
            $this->error(L('operation_failure'));
        }
    	
    }

    public function order_warning() {
        if(true){
            $count = M('cityexpress_order')->where("warning = 1 and status = 1")->count();
            if($count > 0){
                $arr['state'] = 1;
            }
            $arr['count'] = $count;

            echo json_encode($arr);
        }

    }


    public function get_address($pid,$cid,$aid){
    	$pname = M('area')->where("id = ".intval($pid))->getField('name');
        $cname = M('area')->where("id = ".intval($cid))->getField('name');
        $aname= M('area')->where("id = ".intval($aid))->getField('name');
        $saddress = $pname.(($cname)?'&nbsp;'.$cname:'&nbsp;').$aname;
        return $saddress;
    }
    /*public function _before_update($data){
        $data['status']=3;
        return $data;
    }*/

    public function updateRemark(){
        $txtSellerRemark= $_POST['remark'];//用客服备注
        $id=$_POST['id'];//订单ID
        $data['remark']=$txtSellerRemark;
        if(M('cityexpress_order')->where('id='.$id)->save($data)!==false)
        {
            $this->success('修改成功！');
        }else
        {
            $this->error('修改失败！');
        }


    }

    public function fahuo()
    {
        $mod = D($this->_name);
        if (IS_POST&&$this->_post('orderId','trim')) {
            $orderId= $this->_post('orderId','trim');//订单号ID
            if (false === $data = $mod->create()) {
                IS_AJAX && $this->ajaxReturn(0, $mod->getError());
                $this->error($mod->getError());
            }
            if (method_exists($this, '_before_insert')) {
                $data = $this->_before_insert($data);
            }
            if($_POST['delivery']=='0')
            {
                $date['userfree']=0;
            }else
            {
                $date['userfree']=$_POST['delivery'];
                $date['freecode']=$_POST['deliverycode'];
                $date['fahuoaddress']=$data['address'];
            }
            $date['fahuo_time']=time();
            $date['status']=2;
            $date['warning'] = 0; //已经处理
            if($mod->where(array('order_sn'=>$orderId))->data($date)->save()){

                IS_AJAX && $this->ajaxReturn(1, L('operation_success'), '', 'add');
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }

        } else {
            $this->assign('open_validator', true);
            if (IS_AJAX) {
                if(count(M('address')->where('status=1')->find())==0)
                {
                    $this->ajaxReturn(1, '', '请先添加默认收货地址！');
                }
                $id= $this->_get('id','trim');//订单号ID
                $info= $this->_mod->find($id);
                $this->assign('info',$info);
                $deliveryList= M('delivery')->where('status=1')->order('ordid asc,id asc')->select();//快递方式
                $this->assign('deliveryList',$deliveryList);
                $addressList=M('address')->where('status=1')->order('ordid asc,id asc')->select();//快递方式
                $this->assign('addressList',$addressList);
                $response = $this->fetch();
                $this->ajaxReturn(1, '', $response);
            } else {
                $deliveryList= M('delivery')->where('status=1')->order('ordid asc,id asc')->select();//快递方式
                $this->assign('deliveryList',$deliveryList);
                $id= $this->_get('id','trim');//订单号ID
                $info= $this->_mod->find($id);
                $this->assign('info',$info);
                $this->display();
            }
        }
    }


    public function status()
    {
        $orderId= $this->_get('orderId', 'trim');
        !$orderId && $this->_404();
        $status= $this->_get('status', 'intval');
        !$status && $this->_404();
        if($status == 4)
        {
            $data['status']=$status;
            if($this->_mod->where(array('orderId'=>$orderId))->save($data))
            {
                $order_detail=M('order_detail');
                $item=M('item');
                $order_details = $order_detail->where(array('orderId'=>$orderId))->select();
                foreach ($order_details as $val)
                {
                    //$item->where('id='.$val['itemId'])->setInc('buy_num',$val['quantity']);
                }
                $this->success('修改成功!');
            }else{
                $this->error('修改失败!');
            }

        }else{
            $data['status']=$status;
            if($this->_mod->where(array('order_sn'=>$orderId))->save($data))
            {
                $this->success('设置成功!');
            }else
            {
                $this->error('设置成功!');
            }
        }


    }



    
    
}