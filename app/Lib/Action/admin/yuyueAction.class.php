<?php

class yuyueAction extends backendAction
{
    public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('yuyue');
        $this->_cate_mod = D('activity');
    }

    protected function _search() {
        $map = array();
        ($time_start = $this->_request('time_start', 'trim')) && $map['yuyue_time'][] = array('egt', strtotime($time_start));
        ($time_end = $this->_request('time_end', 'trim')) && $map['yuyue_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        ($order_score_min = $this->_request('order_score_min', 'trim')) && $map['order_score'][] = array('egt', $order_score_min);
        ($order_score_max = $this->_request('order_score_max', 'trim')) && $map['order_score'][] = array('elt', $order_score_max);
        ($rates_min = $this->_request('rates_min', 'trim')) && $map['rates'][] = array('egt', $rates_min);
        ($rates_max = $this->_request('rates_max', 'trim')) && $map['rates'][] = array('elt', $rates_max);
        ($uname = $this->_request('uname', 'trim')) && $map['name'] = array('like', '%'.$uname.'%');
        $cate_id = $this->_request('cate_id', 'intval');
        if ($cate_id) {
            $map['cate_id'] = array('eq', $cate_id);

        }
        if( $_GET['status']==null ){
            $status = -1;
        }else{
            $status = intval($_GET['status']);
        }
        $status>=0 && $map['status'] = array('eq',$status);
        ($keyword = $this->_request('keyword', 'trim')) && $map['num'] = array('like', '%'.$keyword.'%');
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

        $cate_lists = $this->_cate_mod->field('id,title')->select();
        $this->assign('cate_lists',$cate_lists);
        //分类信息
        $res = $this->_cate_mod->field('id,title')->select();
        $cate_list = array();
        foreach ($res as $val) {
            $cate_list[$val['id']] = $val['title'];
        }
        $this->assign('cate_list', $cate_list);
        $this->assign('cate', $res);
    }

   /* public function _before_update($data){
        $data['status']=1;
        return $data;
    }*/
    public function sendmsg(){
        if(IS_POST){
            require_once 'app/Common/MessageUtil.php';
            $uid = $_POST['uid'] ;
            $openid = M('user')->where(array('id'=>$uid))->getField('openid');
            $content = $_POST['content'];
            if(!$content){
                IS_AJAX && $this->ajaxReturn(0,  '请填写消息内容');
                $this->error( '请填写消息内容');
            }

            if( $content && $openid ){
                MessageUtil::sendTextInfo($openid,$content);
                IS_AJAX && $this->ajaxReturn(1, '发送成功', '', 'edit');
                $this->success( '发送成功');
            }else{
                IS_AJAX && $this->ajaxReturn(0,  '发送失败');
                $this->error( '发送失败');
            }

        }else{
            $id = $_GET['id'];
            $info =M('yuyue')->where(array('id'=>$id))->find();
            $this->assign('info',$info);
            $this->assign('open_validator', true);
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajaxReturn(1, '', $response);
            }
        }
    }
    public function _before_update($data){
    	$data['yuyue_time'] = strtotime($data['yuyue_time']);
    	return $data;
    }
}