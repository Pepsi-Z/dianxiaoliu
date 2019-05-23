<?php
class ruleAction extends backendAction {

    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('rule');
    }
    public function _before_index() {
        $big_menu = array(
            'title' => '添加返积分规则',
            'iframe' => U('rule/add'),
            'id' => 'add',
            'width' => '500',
            'height' => '210'
        );
        $this->assign('list_table', true);
        $this->assign('big_menu', $big_menu);
    }

}