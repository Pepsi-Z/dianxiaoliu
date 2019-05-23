<?php

class yue_logAction extends backendAction
{
    public function logs() {
        $yue_log_mod = M('yue_log');
        $map = array();
        if($_GET['uid']){
            $map['uid'] = $_GET['uid'];

        }
        $keyword = $this->_request('keyword', 'trim');
        $keyword && $map = array('u.username'=>array('like', '%'.$keyword.'%'));
        $map['y.status'] = 1;
        $count = $yue_log_mod->table('weixin_yue_log y')->join('weixin_user u on y.uid = u.id')->where($map)->count();
        $pager = new Page($count, 20);
        $list = $yue_log_mod->table('weixin_yue_log y')->field('y.*,u.username uname')->join('weixin_user u on y.uid = u.id')->where($map)->order('y.id DESC')->limit($pager->firstRow.','.$pager->listRows)->select();
      
        $this->assign('list',$list);
        $this->assign('page',$pager->show());
        $this->assign('keyword', array('keyword' => $keyword,));
        $this->display();
    }
}