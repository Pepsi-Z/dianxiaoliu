<?php
/**
 * 名管理
 */
class baomingAction extends backendAction
{

    public function _initialize() {
        parent::_initialize();
        $this->_mod = M('baoming');
    }

	public function index() {
		 $keyword = $_POST['keyword'];
		 if($keyword){
		 	$list = $this->_mod->where("baby_name like '%".$keyword."%'")->order("id desc")->select();
		 }else{	
		 	$list = $this->_mod->order("id desc")->select();
		 }
		 
		 $this->assign('list',$list);
		 $this->display();
    }
    
    public function delete() {
        $cate_tag_mod = M('baoming');
        $cate_id = $this->_get('id');
        if ($cate_id) {
            $cate_tag_mod->where("id=".$cate_id)->delete();
            $this->ajaxReturn(1, L('operation_success'));
        } else {
            $this->ajaxReturn(0, L('illegal_parameters'));
        }
    }
}