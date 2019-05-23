<?php
class item_cateAction extends backendAction {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('item_cate');
    }

    public function _before_index(){
        //bigmenu (标题，地址，弹窗ID，宽，高)
        $big_menu = array(
            'title' => '添加平台',
            'iframe' => U('item_cate/add'),
            'id' => 'add',
            'width' => '520',
            'height' => '100'
        );
        $this->sort = 'ordid';
        $this->order = 'ASC';
        $lable = $this->_lable(); //标签
        $this->assign('lable', $lable);
        $this->assign('big_menu', $big_menu);
        $this->assign('list_table', true);
    }
    public function index(){
        $count = $this->_mod->where(array('pid'=>'0'))->count();
        $Page       = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $Page->show();// 分页显示输出
        $list = $this->_mod->order('ordid asc')->where(array('pid'=>'0'))->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign("list",$list);
        $this->assign('page',$show);
        $this->assign('list_table', true);
        $this->display();
    }

    public function _before_add(){
        $lable = $this->_lable(); //标签
        $this->assign('lable', $lable);
    }

    public function _before_insert($data = '') {
        //检测分类是否存在
        if($this->_mod->name_exists($data['name'])){
            $this->ajaxReturn(0, L('item_cate_already_exists'));
        }
        //上传图片
        $date_dir = date('ym/d/'); //上传目录
        $item_imgs = array(); //相册
        $result = $this->_upload($_FILES['img'], 'item_cate/'.$date_dir, array(
            'width'=>C('pin_item_bimg.width').','.C('pin_item_img.width').','.C('pin_item_simg.width'),
            'height'=>C('pin_item_bimg.height').','.C('pin_item_img.height').','.C('pin_item_simg.height'),
            'suffix' => '_b,_m,_s',
            //'remove_origin'=>true
        ));



        if ($result['error']) {
            $this->error($result['info']);
        } else {
            $data['img'] = $date_dir . $result['info'][0]['savename'];
            $img_url = 'data/upload/item_cate/'.$data['img'];
            $this->get_cut_img($img_url,260,280);

        }
        return $data;
    }

    public function _before_edit(){
        $lable = $this->_lable(); //标签
        $this->assign('lable', $lable);
    }
    /**
     * 修改提交数据
     */
    protected function _before_update($data = '') {
        if ($this->_mod->name_exists($data['name'], $data['id'])) {
            $this->ajaxReturn(0, L('item_cate_already_exists'));
        }
        if (!empty($_FILES['img']['name'])) {
            //上传图片
            $date_dir = date('ym/d/'); //上传目录
            $item_imgs = array(); //相册
            $result = $this->_upload($_FILES['img'], 'item_cate/'.$date_dir, array(
                'width'=>C('pin_item_bimg.width').','.C('pin_item_img.width').','.C('pin_item_simg.width'),
                'height'=>C('pin_item_bimg.height').','.C('pin_item_img.height').','.C('pin_item_simg.height'),
                'suffix' => '_b,_m,_s',
                //'remove_origin'=>true
            ));
            if ($result['error']) {
                $this->error($result['info']);
            } else {
                $data['img'] = $date_dir . $result['info'][0]['savename'];
                $img_url = 'data/upload/item_cate/'.$data['img'];
                $this->get_cut_img($img_url,260,280);

            }
        }else{
            unset($data['img']);
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
            $cids = $mod->where(array('pid'=>array('in',$ids)))->getField('id',true);
            $cidss = implode(',',$cids);
			$item_mod->where('cate_id in('.$cidss.')')->delete();
            $mod->where(array('pid'=>array('in',$ids)))->delete();
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
    //品牌列表
    public function brand(){
        $pid = I('get.pid');
        $type = I('get.type','intval');
        $big_menu = array(
            'title' => '添加特色类型',
            'iframe' => U('item_cate/brand_add',array('pid'=>$pid,'type'=>$type)),
            'id' => 'add',
            'width' => '520',
            'height' => '100'
        );
        $this->sort = 'ordid';
        $this->order = 'ASC';
        $this->assign('big_menu', $big_menu);
        $count = D('item_cate')->where(array('pid'=>$pid))->count();
        $Page       = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $Page->show();// 分页显示输出
        $list= D('item_cate')->order('ordid asc')->where(array('pid'=>$pid))
            ->limit($Page->firstRow.','.$Page->listRows)->select();
        $pname = D('item_cate')->order('ordid asc')->where(array('id'=>$pid))->getField('name');
        $this->assign('type',$type);
        $this->assign("list",$list);
        $this->assign("type",$type);
        $this->assign("pname",$pname);
        $this->assign('page',$show);
        $this->assign('list_table', true);
        $this->display();
    }
    //添加品牌

    public function brand_add() {
        $mod = M('item_cate');
        if (IS_POST) {
            if (false === $data = $mod->create()) {
                IS_AJAX && $this->ajaxReturn(0, $mod->getError());
                $this->error($mod->getError());
            }
            if( $mod->add($data) ){
                IS_AJAX && $this->ajaxReturn(1, L('operation_success'), '', 'add');
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            $this->assign('open_validator', true);
            $response = $this->fetch();
            $this->ajaxReturn(1, '', $response);
        }
    }

    /**
     * 修改品牌
     */
    public function brand_edit()
    {
        $mod = M('item_cate');
        $pk = $mod->getPk();
        if (IS_POST) {
            if (false === $data = $mod->create()) {
                IS_AJAX && $this->ajaxReturn(0, $mod->getError());
                $this->error($mod->getError());
            }
            if (false !== $mod->save($data)) {
                IS_AJAX && $this->ajaxReturn(1, L('operation_success'), '', 'edit');
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            $id = $this->_get($pk, 'intval');
            $info = $mod->find($id);
            $this->assign('info', $info);
            $this->assign('open_validator', true);
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajaxReturn(1, '', $response);
            }
        }
    }

    //s删除品牌
    public function brand_delete()
    {
        $mod = D($this->_name);
        $item_mod = M('item_cate');
        $pk = $mod->getPk();
        $ids = trim($this->_request($pk), ',');
        if ($ids) {
            $item_mod->where('id in('.$ids.')')->delete();
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


    //显示特色类型
    public function brand_cate(){
        $pid = I('get.id');
        $type = I('get.type','intval');
        $big_menu = array(
            'title' => '添加特色类型',
            'iframe' => U('item_cate/brand_cate_add',array('pid'=>$pid,'type'=>$type)),
            'id' => 'add',
            'width' => '520',
            'height' => '100'
        );
        $this->sort = 'ordid';
        $this->order = 'ASC';
        $this->assign('big_menu', $big_menu);
        $count = D('item_cate')->where(array('pid'=>$pid))->count();
        $Page       = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $Page->show();// 分页显示输出
        $list= D('item_cate')->order('ordid asc')->where(array('pid'=>$pid))
            ->limit($Page->firstRow.','.$Page->listRows)->select();
        $pname = D('item_cate')->order('ordid asc')->where(array('id'=>$pid))->getField('name');
        $this->assign('type',$type);
        $this->assign("list",$list);
        $this->assign("pname",$pname);
        $this->assign('page',$show);
        $this->assign('list_table', true);
        $this->display();
    }


    //添加特色类型

    public function brand_cate_add() {
        $mod = M('item_cate');
        if (IS_POST) {
            if (false === $data = $mod->create()) {
                IS_AJAX && $this->ajaxReturn(0, $mod->getError());
                $this->error($mod->getError());
            }
            if( $mod->add($data) ){
                IS_AJAX && $this->ajaxReturn(1, L('operation_success'), '', 'add');
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            $this->assign('open_validator', true);
            $response = $this->fetch();
            $this->ajaxReturn(1, '', $response);
        }
    }

    /**
     * 修改特色类型
     */
    public function brand_cate_edit()
    {
        $mod = M('item_cate');
        $pk = $mod->getPk();
        if (IS_POST) {
            if (false === $data = $mod->create()) {
                IS_AJAX && $this->ajaxReturn(0, $mod->getError());
                $this->error($mod->getError());
            }
            if (false !== $mod->save($data)) {
                IS_AJAX && $this->ajaxReturn(1, L('operation_success'), '', 'edit');
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            $id = $this->_get($pk, 'intval');
            $info = $mod->find($id);
            $this->assign('info', $info);
            $this->assign('open_validator', true);
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajaxReturn(1, '', $response);
            }
        }
    }


    //删除特色类型
    public function brand_cate_delete()
    {
        $mod = D($this->_name);
        $pk = $mod->getPk();
        $ids = trim($this->_request($pk), ',');
        if ($ids) {
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