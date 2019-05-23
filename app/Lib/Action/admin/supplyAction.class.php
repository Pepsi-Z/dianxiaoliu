<?php

class supplyAction extends backendAction
{

    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('supply');
        $this->_cate_mod =D('item_cate');
    }

    public function _before_index() {
        //默认排序
        $this->sort = 'ordid';
        $this->order = 'ASC';
        $res = $this->_cate_mod->field('id,name')->where(array('pid'=>0))->select();
        $cate_list = array();
        foreach ($res as $val) {
            $cate_list[$val['id']] = $val['name'];
        }
        $this->assign('cate_list', $cate_list);
        $this->list_relation = true;
    }

    protected function _search() {
        $map = array();
        ($cate_id = $this->_request('cate_id', 'trim')) && $map['cid'] = array('eq', $cate_id);
        ($type = $this->_request('type', 'trim')) && $map['type'] = array('eq', $type);
        ($keyword = $this->_request('keyword', 'trim')) && $map['name'] = array('like', '%'.$keyword.'%');
        if ($cate_id) {
            $map['cate_id'] = array('eq', $cate_id);
            $cname = M('item_cate')->where(array('id'=>$cate_id))->getField('name');
            $pid = M('item_cate')->where(array('id'=>$cate_id))->getField('pid');

        }
        $this->assign('search', array(
            'keyword' => $keyword,
            'cate_id' => $cate_id,
            'pid' => $pid,
            'type' => $type,
            'cname' => $cname,
        ));
        return $map;
    }

    public function _before_add() {
        $cate_list = $this->_cate_mod->field('id,name')->select();
        $this->assign('cate_list',$cate_list);
    }

    public function edit() {
        if (IS_POST) {
            //获取数据
            if (false === $data = $this->_mod->create()) {
                $this->error($this->_mod->getError());
            }
            if( !$data['pid']){
                $this->error('请选择产品分类');
            }
            //库存
            if($_POST['num']<=0){

                $this->error('产品数量不能低于1');
            }
            $item_id = $data['id'];
            $date_dir = date('ym/d/'); //上传目录
            $item_imgs = array(); //相册
            //修改图片
            if (!empty($_FILES['img']['name'])) {
                $result = $this->_upload($_FILES['img'], 'supply/'.$date_dir, array(
                    'width'=>C('pin_item_bimg.width').','.C('pin_item_img.width').','.C('pin_item_simg.width'),
                    'height'=>C('pin_item_bimg.height').','.C('pin_item_img.height').','.C('pin_item_simg.height'),
                    'suffix' => '_b,_m,_s',
                ));
                if ($result['error']) {
                    $this->error($result['info']);
                } else {
                    $data['url'] = $date_dir . $result['info'][0]['savename'];
                }
            }


            /*=======================by lyye 2014-04-08=======================*/
            //上传相册
            $file_imgs = array();
            foreach( $_FILES['imgs']['name'] as $key=>$val ){
                if( $val ){
                    $file_imgs['name'][] = $val;
                    $file_imgs['type'][] = $_FILES['imgs']['type'][$key];
                    $file_imgs['tmp_name'][] = $_FILES['imgs']['tmp_name'][$key];
                    $file_imgs['error'][] = $_FILES['imgs']['error'][$key];
                    $file_imgs['size'][] = $_FILES['imgs']['size'][$key];
                }
            }
            if( $file_imgs ){
                $result = $this->_upload($file_imgs, 'supply/'.$date_dir, array(
                    'width'=>C('pin_item_bimg.width').','.C('pin_item_simg.width'),
                    'height'=>C('pin_item_bimg.height').','.C('pin_item_simg.height'),
                    'suffix' => '_b,_s',
                ));
                if ($result['error']) {
                    $this->error($result['info']);
                } else {
                    foreach( $result['info'] as $key=>$val ){
                        $item_imgs[] = array(
                            'supply_id' => $item_id,
                            'url'    => $date_dir . $val['savename'],
                            'order'   => $key + 1,
                        );
                    }
                }
            }
//            p($item_imgs);die;
            //更新商品
//            $data['url'] = $item_imgs[0]['url'];
            $this->_mod->where(array('id'=>$item_id))->save($data);
            //更新图片和相册
            $item_imgs && M('supply_img')->addAll($item_imgs);
            $this->success(L('operation_success'),u('supply/index'));
        } else {
            $id = I('get.id');
            $item = D('supply')->where(array('id'=>$id))->find();
//            $pid = M('item_cate')->where(array('id'=>$item['cid']))->getField('pid');
           $pid = $item['pid'];
            $cate = M('item_cate')->where(array('pid'=>$pid))->select();
//            $item['pid'] = $pid;
            //分类
            $cate_list=M('item_cate')->field('id,name')->where(array('pid'=>0))->select();
            $this->assign('cate_list', $cate_list);
            $this->assign('info', $item);
            $this->assign('cates', $cate);

            //相册
            $img_list = M('supply_img')->where(array('supply_id'=>$id))->select();
            $this->assign('img_list', $img_list);
            $this->assign('cate', $cate);
            $this->display();
        }
    }

    function delete_album() {
        $album_mod = M('supply_img');
        $album_id = $this->_get('album_id','intval');
        $album_img = $album_mod->where('id='.$album_id)->getField('url');
        if( $album_img ){
            $ext = array_pop(explode('.', $album_img));
            $album_min_img = C('pin_attach_path') . 'supply/' . str_replace('.' . $ext, '_s.' . $ext, $album_img);
            is_file($album_min_img) && @unlink($album_min_img);
            $album_img = C('pin_attach_path') . 'supply/' . $album_img;
            is_file($album_img) && @unlink($album_img);
            $album_mod->delete($album_id);
        }
        echo '1';
        exit;
    }

    function delete_attr() {
        $attr_mod = M('item_attr');
        $attr_id = $this->_get('attr_id','intval');
        $attr_mod->delete($attr_id);
        echo '1';
        exit;
    }

    protected function _before_insert($data) {
        //上传图片
        if (!empty($_FILES['img']['name'])) {
            $time_dir = date('ym/d');
            $result = $this->_upload($_FILES['img'], 'score_item/' . $time_dir, array(
                'width' => C('pin_score_item_img.swidth').','.C('pin_score_item_img.bwidth'),
                'height' => C('pin_score_item_img.sheight').','.C('pin_score_item_img.bheight'),
                'suffix' => '_s,_b',
                'remove_origin' => true
            ));
            if ($result['error']) {
                $this->error($result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['img'] = $time_dir .'/'. str_replace('.' . $ext, '_s.' . $ext, $result['info'][0]['savename']);
            }
        }
        return $data;
    }


}