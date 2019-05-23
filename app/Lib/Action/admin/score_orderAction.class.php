<?php

class score_orderAction extends backendAction
{
    public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('score_order');
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

    public function _before_index(){

        $cate_lists = $this->_cate_mod->field('id,name')->select();
        $this->assign('cate_lists',$cate_lists);
    }
    public function _before_edit(){

    	$id = $_REQUEST['id'];
        //p($id);die;
    	$addressid = M('score_order')->where('id = '.$id)->getField('addressid');
    	$address = M('user_address')->where('id ='.$addressid)->find();
    	$address['saddress'] = $this->get_address($address['pid'],$address['cid'],$address['aid']);
    	$this->assign('address',$address);
    }


    public function _after_update(){

            $this->success(L('operation_success'),U('score_order/index',array('menuid'=>262)));
    }


    public function delivery(){
    	$id = $this->_get('id','intval');
    	$data['status']=2;
        $data['warning']=0;
    	$a = M('score_order')->where('id = '.$id)->save($data);
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
            $count = M('score_order')->where("warning = 1 and status = 1")->count();
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
    public function _before_update($data){
        $data['status']=2;
        $data['warning']=0;
        return $data;
    }
    
    
}