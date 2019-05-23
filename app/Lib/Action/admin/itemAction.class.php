<?php
class itemAction extends backendAction {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('item');
        $this->_cate_mod = D('item_cate');
        $brandlist= $this->_brand=M('brandlist')->where('status=1')->order('ordid asc,id asc')->select();
        $this->assign('brandlist',$brandlist);
    }

    public function _before_index() {
        //显示模式
        //分类信息
        $res = $this->_cate_mod->field('id,name')->where(array('pid'=>'0'))->select();
        $cate_list = array();
        foreach ($res as $val) {
            $cate_list[$val['id']] = $val['name'];
        }
        $this->assign('cate_list', $cate_list);
        $this->assign('cate', $res);

        $merchant = M('merchant')->field('id,title')->select();
        $merchant_list = array();
        foreach ($merchant as $vals) {
            $merchant_list[$vals['id']] = $vals['title'];
        }
        $this->assign('merchant_list', $merchant_list);
        $tui = array('1'=>'热卖');
        $this->assign('tuijian', $tui);
        //默认排序
        $this->sort = 'ordid ASC,';
        $this->order ='add_time DESC';
        $this->list_relation = true;



    }



    protected function _search() {
        $map = array();
        //'status'=>1
        //($time_start = $this->_request('time_start', 'trim')) && $map['a.add_time'][] = array('egt', strtotime($time_start));
        //($time_end = $this->_request('time_end', 'trim')) && $map['a.add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        ($price_min = $this->_request('price_min', 'trim')) && $map['a.price'][] = array('egt', $price_min);
        ($price_max = $this->_request('price_max', 'trim')) && $map['a.price'][] = array('elt', $price_max);
        ($stock_min = $this->_request('stock_min', 'trim')) && $map['a.goods_stock'][] = array('egt', $stock_min);
        ($stock_max = $this->_request('stock_max', 'trim')) && $map['a.goods_stock'][] = array('elt', $stock_max);
        //  ($rates_min = $this->_request('rates_min', 'trim')) && $map['rates'][] = array('egt', $rates_min);
        // ($rates_max = $this->_request('rates_max', 'trim')) && $map['rates'][] = array('elt', $rates_max);
        ($uname = $this->_request('uname', 'trim')) && $map['a.uname'] = array('like', '%'.$uname.'%');
        $cate_id = $this->_request('cate_id', 'intval');
        $pid = $this->_request('pid', 'intval');

        $sid = $this->_request('sid', 'intval');

        $tuijian = $this->_request('tuijian', 'intval');
        if($pid){
            $map['a.pid'] = array('eq', $pid);
        }

        if($sid){
            $map['a.sid'] = array('eq', $sid);
        }

        if ($cate_id) {
            $map['a.cate_id'] = array('eq', $cate_id);
            $cname = M('item_cate')->where(array('id'=>$cate_id))->getField('name');
//            $pid = M('item_cate')->where(array('id'=>$cate_id))->getField('pid');

        }
        if( $_GET['status']==null ){
            $status = -1;
        }else{
            $status = intval($_GET['status']);
        }
        $status>=0 && $map['status'] = array('eq',$status);
        if($tuijian){
            $map['a.tuijian'] = $tuijian;
        }


        if( $_GET['is_xiangou']==null ){
            $is_xiangou = -1;
        }else{
            $is_xiangou = intval($_GET['is_xiangou']);
        }
        $is_xiangou>=0 && $map['a.is_xiangou'] = array('eq',$is_xiangou);

        ($keyword = $this->_request('keyword', 'trim')) && $map['a.title'] = array('like', '%'.$keyword.'%');

        ($m_keyword = $this->_request('m_keyword', 'trim')) && $map['b.title'] = array('like', '%'.$m_keyword.'%');
        $this->assign('search', array(
            /*'time_start' => $time_start,
            'time_end' => $time_end,*/
            'price_min' => $price_min,
            'price_max' => $price_max,
            'stock_min' => $stock_min,
            'stock_max' => $stock_max,
            // 'rates_min' => $rates_min,
            //  'rates_max' => $rates_max,
            'uname' => $uname,
            'status' =>$status,
            'tuijian' =>$tuijian,
            'is_xiangou'=>$is_xiangou,
//            'selected_ids' => $spid,
            'cate_id' => $cate_id,
            'pid' => $pid,
            'sid' => $sid,
            'cname' => $cname,
            'keyword' => $keyword,
            'm_keyword' => $m_keyword,
        ));
        return $map;
    }


    public function all_cate(){
        $id = I('get.id','intval');
        $mod= D('item');
        $sql = "select a.*,b.name as b_name,c.name as c_name,d.name as d_name,e.title as e_name,f.name as f_name".
            " from weixin_item a left join weixin_item_cate b on a.pid = b.id ".
            " left join weixin_item_cate c on a.tid = c.id ".
            " left join weixin_item_cate d on a.ttid = d.id ".
            " left join weixin_merchant  e on a.sid = e.id ".
            " left join weixin_merchant_cate f on a.mid = f.id ".
            " where a.id = ".$id.
            " order by ordid asc";

        $Dao = M();
        $info = $Dao->query($sql);
        $this->assign('info', $info);
        $this->assign('open_validator', true);
        if (IS_AJAX) {
            $response = $this->fetch();
            $this->ajaxReturn(1, '', $response);
        } else {
            $this->display();
        }

    }

    public function add() {
        if (IS_POST) {
            //获取数据
            if (false === $data = $this->_mod->create()) {
                $this->error($this->_mod->getError());
            }
            /*if( !$data['sid']){
                $this->error('请选择商品分类');
            }*/

          /*   //库存
            if($_POST['goods_stock']<=0){
                
                $this->error('库存不能低于1');
            }*/
            //套餐
            if($_POST['tc_price'] <= 0){

                $this->error('套餐价钱不能为空');
            }

            //门市
            if($_POST['price'] <= 0){

                $this->error('门市价钱不能为空');
            }
            //必须上传图片
            if (empty($_FILES['img']['name'])) {
                $this->error('请上传商品图片');
            }
            //上传图片
            $date_dir = date('ym/d/'); //上传目录
            $item_imgs = array(); //相册
            $result = $this->_upload($_FILES['img'], 'item/'.$date_dir, array(
                'width'=>C('pin_item_bimg.width').','.C('pin_item_img.width').','.C('pin_item_simg.width'), 
                'height'=>C('pin_item_bimg.height').','.C('pin_item_img.height').','.C('pin_item_simg.height'),
                'suffix' => '_b,_m,_s',
                //'remove_origin'=>true 
            ));
           
            if ($result['error']) {
                $this->error($result['info']);
            } else {
                $data['img'] = $date_dir . $result['info'][0]['savename'];
                $img_url = 'data/upload/item/'.$data['img'];
                $this->get_cut_img($img_url,260,280);
            }

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
                $result = $this->_upload($file_imgs, 'item/'.$date_dir, array(
                    'width'=>C('pin_item_bimg.width').','.C('pin_item_simg.width'),
                    'height'=>C('pin_item_bimg.height').','.C('pin_item_simg.height'),
                    'suffix' => '_b,_s',
                ));
                if ($result['error']) {
                    $this->error($result['info']);
                } else {
                    foreach( $result['info'] as $key=>$val ){
                        $item_imgs[] = array(
                            'url'    => $date_dir . $val['savename'],
                            'order'  => $key + 1,
                        );
                    }
                }
            }
            foreach ($item_imgs as $k=>$v){
                $url = 'data/upload/item/'.$v['url'];
                $this->get_cut_img($url,400,400);

            }
            $data['imgs'] = $item_imgs;



            if( $item_id =$this->_mod->add($data) ){
                if( method_exists($this, '_after_insert')){
                    $this->_after_insert($item_id);
                }

                foreach ($item_imgs as $k=>$v){
                    $arr['add_time'] = time();
                    $arr['url'] = $v['url'];
                    $arr['item_id'] = $item_id;
                    M('item_img')->add($arr);
                }

                $this->success(L('operation_success'),U('item/index',array('menuid'=>52)));
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            //分类信息
            $merchant_list=M('merchant')->field('id,title')->where(array('status'=>1))->select();
            //分类信息
            $cate=M('item_cate')->field('id,name')->where(array('pid'=>0))->select();
            $this->assign('cate', $cate);
            $this->assign('merchant_list',$merchant_list);
            $this->display();
        }
    }


    public function _after_insert($id){
        if(!empty($_POST['attr']['name'])){

            for ($i = 1; $i < count($_POST['attr']['name']); $i++) {
                $labe['item_id'] = $id;
                $labe['attr_name'] = $_POST['attr']['name'][$i];
                $labe['attr_value'] = $_POST['attr']['value'][$i];
                M('item_attr')->add($labe);
            }

        }


    }


    public function edit() {
        if (IS_POST) {
            //获取数据
            if (false === $data = $this->_mod->create()) {
                $this->error($this->_mod->getError());
            }
          /*  if( !$data['pid']){
                $this->error('请选择商品分类');
            }*/

            //库存
            /*if($_POST['goods_stock']<=0){
                
                $this->error('库存不能低于1');
            }*/
            $item_id = $data['id'];
            $date_dir = date('ym/d/'); //上传目录
            $item_imgs = array(); //相册
            //修改图片
            if (!empty($_FILES['img']['name'])) {
                $result = $this->_upload($_FILES['img'], 'item/'.$date_dir, array(
                    'width'=>C('pin_item_bimg.width').','.C('pin_item_img.width').','.C('pin_item_simg.width'), 
                    'height'=>C('pin_item_bimg.height').','.C('pin_item_img.height').','.C('pin_item_simg.height'),
                    'suffix' => '_b,_m,_s',
                ));
                if ($result['error']) {
                    $this->error($result['info']);
                } else {
                    $data['img'] = $date_dir . $result['info'][0]['savename'];
                    //保存一份到相册
                    $item_imgs[] = array(
                        'item_id' => $item_id,
                        'url'     => $data['img'],
                    );
                }
            }else{
                unset($data['img']);
            }
            $data['add_time'] = time();

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
                $result = $this->_upload($file_imgs, 'item/'.$date_dir, array(
                    'width'=>C('pin_item_bimg.width').','.C('pin_item_simg.width'),
                    'height'=>C('pin_item_bimg.height').','.C('pin_item_simg.height'),
                    'suffix' => '_b,_s',
                ));
                if ($result['error']) {
                    $this->error($result['info']);
                } else {
                    foreach( $result['info'] as $key=>$val ){
                        $item_imgs[] = array(
                            'item_id' => $item_id,
                            'url'    => $date_dir . $val['savename'],
                            'order'   => $key + 1,
                        );
                    }
                }
            }

            //更新商品
            if(empty($data['tuijian'])){
                $data['tuijian'] = 0;
            }

            if(empty($data['love'])){
                $data['love'] = 0;
            }
            if (false !== $this->_mod->where(array('id'=>$item_id))->save($data)) {

                 M('item_img')->addAll($item_imgs);

                if (method_exists($this, '_after_update')) {
                    $id = $data['id'];
                    $this->_after_update($id);
                }

                IS_AJAX && $this->ajaxReturn(1, L('operation_success'), '', 'edit');
                $this->success(L('operation_success'),U('item/index',array('menuid'=>52)));
            }


        } else {
            $id = $this->_get('id','intval');
            $item = $this->_mod->where(array('id'=>$id))->find();
            //平台分类
            $pid_info = M('item_cate')->where(array('id'=>$item['pid']))->find();
            //特色产品
            $tid_info = M('item_cate')->where(array('id'=>$item['tid']))->find();
            //特色产品分类
            $ttid_info = M('item_cate')->where(array('id'=>$item['ttid']))->find();
            //商家
            $sid_info = M('merchant')->where(array('id'=>$item['sid']))->find();
            //商家下产品分类
            $mid_info = M('merchant_cate')->where(array('id'=>$item['mid']))->find();
            //分类

            //相册
            $img_list = M('item_img')->where(array('item_id'=>$id))->select();
            $this->assign('img_list', $img_list);

            //属性
            $attr_list = M('item_attr')->order('id asc')->where(array('item_id'=>$id))->select();
            $this->assign('attr_list', $attr_list);

            $this->assign('pid_info', $pid_info);
            $this->assign('tid_info', $tid_info);
            $this->assign('ttid_info', $ttid_info);
            $this->assign('sid_info', $sid_info);
            $this->assign('mid_info', $mid_info);
            $this->assign('info', $item);
            $this->display();
        }
    }



    public function _after_update($id)
    {
        if (!empty($_POST['attr']['name'])) {
            for ($i = 0; $i < count($_POST['attr']['name']); $i++) {
                $labe['item_id'] = $id;
                $labe['attr_name'] = $_POST['attr']['name'][$i];
                $labe['attr_value'] = $_POST['attr']['value'][$i];
                if ($_POST['attr']['value'][$i]) {
                    if ($_POST['attr']['bs'][$i] == 1) {
                        M('item_attr')->where(array('id' => $_POST['attr']['id'][$i]))->save($labe);
                    } else {

                        M('item_attr')->add($labe);
                    }
                }
            }
        }
    }


    function delete_album() {
        $album_mod = M('item_img');
        $album_id = $this->_get('album_id','intval');
        $album_img = $album_mod->where('id='.$album_id)->getField('url');
        if( $album_img ){
            $ext = array_pop(explode('.', $album_img));
            $album_min_img = C('pin_attach_path') . 'item/' . str_replace('.' . $ext, '_s.' . $ext, $album_img);
            is_file($album_min_img) && @unlink($album_min_img);
            $album_img = C('pin_attach_path') . 'item/' . $album_img;
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

    /**
     * 商品审核
     */
    public function check() {
        //分类信息
        $res = $this->_cate_mod->field('id,name')->select();
        $cate_list = array();
        foreach ($res as $val) {
            $cate_list[$val['id']] = $val['name'];
        }
        $this->assign('cate_list', $cate_list);
        //商品信息
        //$map = $this->_search();
        $map=array();
        $map['status']=0;
        ($time_start = $this->_request('time_start', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));
        ($time_end = $this->_request('time_end', 'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        $cate_id = $this->_request('cate_id', 'intval');
        if ($cate_id) {
            $id_arr = $this->_cate_mod->get_child_ids($cate_id, true);
            $map['cate_id'] = array('IN', $id_arr);
            $spid = $this->_cate_mod->where(array('id'=>$cate_id))->getField('spid');
            if( $spid==0 ){
                $spid = $cate_id;
            }else{
                $spid .= $cate_id;
            }
        }
        ($keyword = $this->_request('keyword', 'trim')) && $map['title'] = array('like', '%'.$keyword.'%');
        $this->assign('search', array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'selected_ids' => $spid,
            'cate_id' => $cate_id,
            'keyword' => $keyword,
        ));
        //分页
        $count = $this->_mod->where($map)->count('id');
        $pager = new Page($count, 20);
        $select = $this->_mod->field('id,title,img,cate_id,uid,uname')->where($map)->order('id DESC');
        $select->limit($pager->firstRow.','.$pager->listRows);
        $page = $pager->show();
        $this->assign("page", $page);
        $list = $select->select();
//        foreach ($list as $key=>$val) {
//            $tag_list = unserialize($val['tag_cache']);
//            $val['tags'] = implode(' ', $tag_list);
//            $list[$key] = $val;
//        }
        $this->assign('list', $list);
        $this->assign('list_table', true);
        $this->display();
    }

    /**
     * 审核操作
     */
    public function do_check() {
        $mod = D($this->_name);
        $pk = $mod->getPk();
        $ids = trim($this->_request($pk), ',');
        $datas['id']=array('in',$ids);
        $datas['status']=1;
        if ($datas) {
            if (false !== $mod->save($datas)) {
                IS_AJAX && $this->ajaxReturn(1, L('operation_success'));
            } else {
                IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
            }
        } else {
            IS_AJAX && $this->ajaxReturn(0, L('illegal_parameters'));
        }

    }

    /**
     * ajax获取标签
     */
    public function ajax_gettags() {
        $title = $this->_get('title', 'trim');
        $tag_list = D('tag')->get_tags_by_title($title);
        $tags = implode(' ', $tag_list);
        $this->ajaxReturn(1, L('operation_success'), $tags);
    }

    public function delete_search() {
        $items_mod = D('item');
        $items_cate_mod = D('item_cate');
        $items_likes_mod = D('item_like');
        $items_pics_mod = D('item_img');
        $items_tags_mod = D('item_tag');
        $items_comments_mod = D('item_comment');

        if (isset($_REQUEST['dosubmit'])) {
            if ($_REQUEST['isok'] == "1") {
                //搜索
                $where = '1=1';
                $keyword = trim($_POST['keyword']);
                $cate_id = trim($_POST['cate_id']);
                $cate_id = trim($_POST['cate_id']);
                $time_start = trim($_POST['time_start']);
                $time_end = trim($_POST['time_end']);
                $status = trim($_POST['status']);
                $min_price = trim($_POST['min_price']);
                $max_price = trim($_POST['max_price']);
                $min_rates = trim($_POST['min_rates']);
                $max_rates = trim($_POST['max_rates']);

                if ($keyword != '') {
                    $where .= " AND title LIKE '%" . $keyword . "%'";
                }
                if ($cate_id != ''&&$cate_id!=0) {
                    $where .= " AND cate_id=" . $cate_id;
                }
                if ($time_start != '') {
                    $time_start_int = strtotime($time_start);
                    $where .= " AND add_time>='" . $time_start_int . "'";
                }
                if ($time_end != '') {
                    $time_end_int = strtotime($time_end);
                    $where .= " AND add_time<='" . $time_end_int . "'";
                }
                if ($status != '') {
                    $where .= " AND status=" . $status;
                }
                if ($min_price != '') {
                    $where .= " AND price>=" . $min_price;
                }
                if ($max_price != '') {
                    $where .= " AND price<=" . $max_price;
                }
                if ($min_rates != '') {
                    $where .= " AND rates>=" . $min_rates;
                }
                if ($max_rates != '') {
                    $where .= " AND rates<=" . $max_rates;
                }
                $ids_list = $items_mod->where($where)->select();
                $ids = "";
                foreach ($ids_list as $val) {
                    $ids .= $val['id'] . ",";
                }
                if ($ids != "") {
                    $ids = substr($ids, 0, -1);
                    $items_likes_mod->where("item_id in(" . $ids . ")")->delete();
                    $items_pics_mod->where("item_id in(" . $ids . ")")->delete();
                    $items_tags_mod->where("item_id in(" . $ids . ")")->delete();
                    $items_comments_mod->where("item_id in(" . $ids . ")")->delete();
                    M('album_item')->where("item_id in(" . $ids . ")")->delete();
                    M('item_attr')->where("item_id in(" . $ids . ")")->delete();

                }
                $items_mod->where($where)->delete();

                //更新商品分类的数量
                $items_nums = $items_mod->field('cate_id,count(id) as items')->group('cate_id')->select();
                foreach ($items_nums as $val) {
                    $items_cate_mod->save(array('id' => $val['cate_id'], 'items' => $val['items']));
//                    M('album')->save(array('cate_id' => $val['cate_id'], 'items' => $val['items']));
                }

                $this->success('删除成功', U('item/delete_search'));
            } else {
                $this->success('确认是否要删除？', U('item/delete_search'));
            }
        } else {
            $res = $this->_cate_mod->field('id,name')->select();

            $cate_list = array();
            foreach ($res as $val) {
                $cate_list[$val['id']] = $val['name'];
            }
            //$this->assign('cate_list', $cate_list);
            $this->display();
        }
    }


    //平台获取特色分类
    public function ajax_get_brand(){
        $pid = I('post.pid');
        $brand = M('merchant')->where(array('pid'=>$pid,'status'=>1))->select();
        if($brand){
            $data['status'] = 1;
            $data['data'] = $brand;
        }else{
            $data['status'] = 0;
        }
        echo json_encode($data);
    }


    //平台获取特色分类
    public function ajax_get_brands(){
        $pid = I('post.pid');
        $brand = M('item_cate')->where(array('pid'=>$pid))->select();
        if($brand){
            $data['status'] = 1;
            $data['data'] = $brand;
        }else{
            $data['status'] = 0;
        }
        echo json_encode($data);
    }

    //特色分类获取商家
    public function ajax_get_merchant(){
        $cid = I('post.cid');
        $brand = M('merchant')->where(array('tid'=>$cid))->select();
        $t_cate = M('item_cate')->where(array('pid'=>$cid))->select();
        if($brand){
            $data['status'] = 1;
            $data['data'] = $brand;
            $data['t_cate'] = $t_cate;
        }else{
            $data['status'] = 0;
        }
        echo json_encode($data);
    }

    //商家获取商家产品分类
    public function ajax_get_merchant_cate(){
        $tid = I('post.tid');
        $brand = M('merchant_cate')->where(array('sid'=>$tid))->select();
        if($brand){
            $data['status'] = 1;
            $data['data'] = $brand;
        }else{
            $data['status'] = 0;
        }
        echo json_encode($data);
    }

    public function _list($model, $map = array(), $sort_by='', $order_by='', $field_list='*', $pagesize=20)
    {

        //排序
        $mod_pk = $model->getPk();

        if ($this->_request("sort", 'trim')) {
            $sort = $this->_request("sort", 'trim');
        } else if (!empty($sort_by)) {
            $sort = $sort_by;
        } else if ($this->sort) {
            $sort = $this->sort;
        } else {
            $sort = $mod_pk;
        }
        if ($this->_request("order", 'trim')) {
            $order = $this->_request("order", 'trim');
        } else if (!empty($order_by)) {
            $order = $order_by;
        } else if ($this->order) {
            $order = $this->order;
        } else {
            $order = 'a.DESC';
        }

        //如果需要分页
        $join = array('a left join __MERCHANT__ b ON  a.sid = b.id ');
        $field_list="a.*,b.title as b_name";
        if ($pagesize) {
            $count = $model->where($map)->join($join)->count('a.'.$mod_pk);
            $pager = new Page($count, $pagesize);
        }

        $select = $model->field($field_list)->join($join)->where($map)->order('a.'.$sort . ' ' . $order);
        //echo $model->_sql();
        $this->list_relation && $select->relation(true);
        if ($pagesize) {
            $select->limit($pager->firstRow.','.$pager->listRows);
            $page = $pager->show();
            $this->assign("page", $page);
        }
        $list = $select->select();
        $this->assign('list', $list);
        //p($list);
        $this->assign('list_table', true);
    }

}