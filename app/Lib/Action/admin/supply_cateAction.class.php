<?php

class supply_cateAction extends backendAction
{

    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('supply_cate');
    }

    public function _before_index() {
        $big_menu = array(
            'title' => '添加分类',
            'iframe' => U('supply_cate/add'),
            'id' => 'add',
            'width' => '400',
            'height' => '130'
        );
        $this->assign('big_menu', $big_menu);

        //默认排序
        $this->sort = 'ordid';
        $this->order = 'ASC';
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
        if (D('supply_cate')->name_exists($name, $id)) {
            $this->ajaxReturn(0, L('该分类名称已存在'));
        } else {
            $this->ajaxReturn(1);
        }
    }
}