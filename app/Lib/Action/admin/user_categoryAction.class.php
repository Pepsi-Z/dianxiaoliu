<?php
/**
 * 用户信息管理
 */
class user_categoryAction extends backendAction
{

    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('user_category');
    }

   protected function _search() {
        $map = array();
        if( $keyword = $this->_request('keyword', 'trim') ){
            $map['_string'] = "name like '%".$keyword."%'";
        }
        $this->assign('search', array(
            'keyword' => $keyword,
        ));
        return $map;
    }

   public function _before_index() {
        $big_menu = array(
            //'title' => L('add_user'),
            'title' => '添加会员组',
            'iframe' => U('user_category/add'),
            'id' => 'add',
            'width' => '500',
            'height' => '330'
        );
        $this->assign('big_menu', $big_menu);
    }

    /**
     * ajax检测会员组是否存在
     */
    public function ajax_check_name() {
        $name = $this->_get('name', 'trim');
        $id = $this->_get('id', 'intval');
        if (D('user_category')->name_exists($name,  $id)) {
            $this->ajaxReturn(0, '该会员组已经存在');
        } else {
            $this->ajaxReturn();
        }
    }
   /* public function index() {
         $this->display();
    }*/

}