<?php
class cardsAction extends backendAction {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('cards');
    }
    public function _before_index() {
        $mer_list = M('merchant')->field('id,title')->select();
        $arr = array();
        foreach($mer_list as $val){
            $arr[$val['id']] = $val['title'];

        }
        $this->assign('arr', $arr);
//        $this->sort = 'ordid';
//        $this->order = 'ASC';
    }

    public function _before_add(){
        $mer_list = M('merchant')->field('id,title')->select();
        $this->assign('mer_list',$mer_list);
    }
    protected function _before_insert($data) {
        $data['start_time'] = strtotime($data['start_time']);
         $data['end_time']  = strtotime($data['end_time']);
        $data['addtime'] = time();
        return $data;
    }

    public function _before_edit(){
        $mer_list = M('merchant')->field('id,title')->select();
        $this->assign('mer_list',$mer_list);
    }

    protected function _before_update($data) {
        $data['start_time'] = strtotime($data['start_time']);
        $data['end_time']  = strtotime($data['end_time']);
        $data['addtime'] = time();
        return $data;
    }

    public function _after_update(){
        $this->success(L('operation_success'),U('cards/index',array('menuid'=>362)));
    }
    public function _after_insert(){
        $this->success(L('operation_success'),U('cards/index',array('menuid'=>362)));
    }

    public function ajax_check_name() {
        $name = $this->_get('name', 'trim');
        $id = $this->_get('id', 'intval');
        if ($this->_mod->name_exists($name, $id)) {
            $this->ajaxReturn(0, L('adboard_already_exists'));
        } else {
            $this->ajaxReturn();
        }
    }
}