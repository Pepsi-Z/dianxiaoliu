<?php
class cardtypeAction extends backendAction
{
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('cardtype');
    }

    public function _before_index() {
        $big_menu = array(
            'title' => '添加会员卡',
            'iframe' => U('cardtype/add'),
            'id' => 'add',
            'width' => '500',
            'height' => '210'
        );
        $this->assign('big_menu', $big_menu);
        //$this->list_relation = true;
    }

    public function ajax_check_name() {
        $clientid = I('get.clientid') ? I('get.clientid'): 'gname';
        $name = I('get.'.$clientid,'', 'trim');
        $id = I('get.id','', 'intval');
        if (D('cardtype')->name_exists($name, $id)) {
            echo 0;
        } else {
            echo 1;
        }
    }

}