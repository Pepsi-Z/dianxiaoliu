<?php
class return_moneyAction extends backendAction {

    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('return_money');
    }
    public function _before_index() {
        $big_menu = array(
            'title' => '添加返利规则',
            'iframe' => U('return_money/add'),
            'id' => 'add',
            'width' => '500',
            'height' => '210'
        );
        $this->assign('list_table', true);
        $this->assign('big_menu', $big_menu);
    }

}