<?php
class item_cateAction extends backendAction {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('item_cate');
    }

    public function _before_index(){
        //bigmenu (标题，地址，弹窗ID，宽，高)
        $big_menu = array(
            'title' => L('add_item_cate'),
            'iframe' => U('item_cate/add'),
            'id' => 'add',
            'width' => '520',
            'height' => '150'
        );
        $this->assign('big_menu', $big_menu);
        $this->assign('list_table', true);
    }
    public function index(){
        $count = $this->_mod->count();
        $Page       = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $Page->show();// 分页显示输出
        $list = $this->_mod->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $k=>$v){
            $list[$k]['discount'] =  $list[$k]['discount'] * 10;
            $list[$k]['discount1'] =  $list[$k]['discount1'] * 10;
            $list[$k]['discount2'] =  $list[$k]['discount2'] * 10;
            $list[$k]['discount3'] =  $list[$k]['discount3'] * 10;
            $list[$k]['discount4'] =  $list[$k]['discount4'] * 10;
            $list[$k]['discount5'] =  $list[$k]['discount5'] * 10;
            $list[$k]['discount6'] =  $list[$k]['discount6'] * 10;
            $list[$k]['discount7'] =  $list[$k]['discount7'] * 10;
            $list[$k]['discount8'] =  $list[$k]['discount8'] * 10;

        }
        $this->assign("list",$list);
        $this->assign('page',$show);
        $this->assign('list_table', true);
        $this->display();
    }

    protected function _before_insert($data = '') {
        //检测分类是否存在
        if($this->_mod->name_exists($data['name'])){
            $this->ajaxReturn(0, L('item_cate_already_exists'));
        }
        return $data;
    }

    /**
     * 修改提交数据
     */
    protected function _before_update($data = '') {
        if ($this->_mod->name_exists($data['name'], $data['id'])) {
            $this->ajaxReturn(0, L('item_cate_already_exists'));
        }
        return $data;
    }



    public function ajax_getchilds() {
        $id = $this->_get('id', 'intval');
        $type = $this->_get('type', 'intval', null);
        $map = array('pid'=>$id);
        if (!is_null($type)) {
            $map['type'] = $type;
        }
        $return = $this->_mod->field('id,name')->where($map)->select();
        if ($return) {
            $this->ajaxReturn(1, L('operation_success'), $return);
        } else {
            $this->ajaxReturn(0, L('operation_failure'));
        }
    }
    public function ajax_upload_img() {
        //上传图片
        if (!empty($_FILES['img']['name'])) {
            $result = $this->_upload($_FILES['img'], 'item_cate', array(
                    'width' => C('pin_itemcate_img.width'),
                    'height' => C('pin_itemcate_img.height'),
                    'remove_origin' => true,
                )
            );
            if ($result['error']) {
                $this->ajaxReturn(0, $result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['img'] = str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
                $this->ajaxReturn(1, L('operation_success'), $data['img']);
            }
        } else {
            $this->ajaxReturn(0, L('illegal_parameters'));
        }
    }
	
	public function delete()
    {
        $mod = D($this->_name);
		$item_mod = M('item');
        $pk = $mod->getPk();
        $ids = trim($this->_request($pk), ',');
        if ($ids) {
        	
			$item_mod->where('cate_id in('.$ids.')')->delete();
			
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