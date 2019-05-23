<?php

class member_consumeAction extends backendAction
{
    public function index(){
        $order_detail = M("order_detail");
        $map = array();
        $map['u.status'] = array('egt',2);
        $keyword = $this->_request('keyword11', 'trim');
        $goods = $this->_request('goods', 'trim');
        if($keyword){
            $map['u.userName'] = array('like', '%'.$keyword.'%');
            $this->assign('keyword11',$keyword);
        }
        if($goods){
            $map['d.title'] = array('like', '%'.$goods.'%');
            $this->assign('goods',$goods);
        }
        $list = $order_detail->table('weixin_order_detail d')->field('d.*,u.userName,u.add_time,u.goods_sumPrice')->join('weixin_item_order u on d.orderId = u.orderId')->where($map)->order('d.id DESC')->select();
        /*
         * array_slice实现分页
         */
        $p = new Page(count($list),20);
        $list = array_slice($list , $p->firstRow , $p->listRows);
        foreach($list as $k=>$v){
            $list[$k]['total'] = $v['price']*$v['quantity'];
        }
        $show = $p->show();
        $this->assign('page',$show);
        $this->assign('list',$list);
		$this->assign('list_table', true);
        $this->display();
    }
    /**
     * 删除
     */
    public function delete()
    {
        $mod = D('order_detail');
        $pk = $mod->getPk();
        $ids = trim($this->_request($pk), ',');
        if ($ids) {
            if (false !== $mod->delete($ids)) {
                IS_AJAX && $this->ajaxReturn(1, L('operation_success'));
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            IS_AJAX && $this->ajaxReturn(0, L('illegal_parameters'));
            $this->error(L('illegal_parameters'));
        }
    }

}