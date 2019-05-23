<?php

class merchant_cateAction extends backendAction
{

    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('merchant_cate');
    }

    public function _before_index() {
        $id = I('get.id','intval'); //商家id
        $big_menu = array(
            'title' => '添加商品分类',
            'iframe' => U('merchant_cate/add',array('sid'=>$id)),
            'id' => 'add',
            'width' => '400',
            'height' => '130'
        );
        $this->assign('big_menu', $big_menu);

        //默认排序
        $this->sort = 'ordid';
        $this->order = 'ASC';
    }

    public function index(){
        $id = I('get.id','intval'); //商家id
        $list =  M('merchant_cate')->where(array('sid'=>$id))->order('ordid asc')->select();
        $p = new Page(count($list),15);
        $list = array_slice($list,$p->firstRow,$p->listRows);
        $show = $p->show();
        $this->assign('page', $show);
        $this->assign('list',$list);
        $this->assign('list_table', true);
        $this->display();
    }



    protected function _search() {
        $map = array();
        ($keyword = $this->_request('keyword', 'trim')) && $map['name'] = array('like', '%'.$keyword.'%');
        $this->assign('search', array(
            'keyword' => $keyword,
        ));
        return $map;
    }

    public function ajax_check_name() {
        $name = $this->_get('name', 'trim');
        $id = $this->_get('id', 'intval');
        if (D('merchant_cate')->name_exists($name, $id)) {
            $this->ajaxReturn(0, L('该分类名称已存在'));
        } else {
            $this->ajaxReturn(1);
        }
    }
}