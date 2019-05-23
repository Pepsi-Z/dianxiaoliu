<?php
class merchantAction extends backendAction {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('merchant');
        $this->_cate_mod = D('item_cate');
        $brandlist= $this->_brand=M('brandlist')->where('status=1')->order('ordid asc,id asc')->select();
        $this->assign('brandlist',$brandlist);
    }

    public function _before_index() {
        //显示模式
        $sm = $this->_get('sm', 'trim');
        $this->assign('sm', $sm);
        //分类信息
        $res = $this->_cate_mod->field('id,name')->where(array('pid'=>'0'))->select();
        $cate_list = array();
        foreach ($res as $val) {
            $cate_list[$val['id']] = $val['name'];
        }
        $this->assign('cate_list', $cate_list);
        $this->assign('cate', $res);
        $this->assign('_teg', $this->_teg());  //属性 单位

        //默认排序
        $this->sort = 'ordid ASC,';
        $this->order ='add_time DESC';
        $this->list_relation = true;
    }

    public function index(){
        $map = 'a.status = 1';
        $time_start = $this->_request('time_start', 'trim');
        $time_end = $this->_request('time_end', 'trim');
        $pid = $this->_request('pid', 'intval');
        $cid = $this->_request('cate_id', 'intval');

        if($pid){
            $map .= " and c.id = ".$pid;
        }

        if($cid){
            $map .= " and t.id = ".$cid;
            $cname = M('item_cate')->where(array('id'=>$cid))->getField('name');
        }
        if($time_start){
            $map .= " and  a.add_time >='".$time_start."'";
        }

        if($time_end){
            $map .= " and a.add_time <= '".$time_end."'";
        }

        $keyword = $this->_request('keyword', 'trim');
        if($keyword){
            $str = " like '%{$keyword}%' ";
            $map .= " and (a.title {$str} or a.info {$str} or


             c.name {$str} or t.name {$str} or tc.name {$str})";
        }

        $this->assign('search', array(
            'keyword' => $keyword,
            'pid' => $pid,
            'cate_id'=>$cid,
            'cname'=>$cname
        ));

        $sort = 'a.ordid';
        $order = 'asc';


        $sql = "select a.*,r.name as rname,rm.name as rmname,c.name as c_name,t.name as t_name,tc.name as tc_name,p.name as p_name,city.name as city_name,area.name as area_name".
            " from weixin_merchant a left join weixin_region p on a.province = p.id ".
            " left join weixin_region city on a.city = city.id ".
            " left join weixin_region area on a.town = area.id ".
            " left join weixin_item_cate c on a.pid = c.id ".
            " left join weixin_item_cate t on a.tid = t.id ".
            " left join weixin_item_cate tc on a.ttid = tc.id ".
            " left join weixin_rule r on a.rid = r.id ".
            " left join weixin_return_money rm on a.rm_id = rm.id ".
            " where {$map} order by ".$sort . ' ' . $order;
        $Dao = M();
        $pagesize = 10;
        $count = $Dao->query("select count(*) as total from (".$sql.") cc");
        $pager = new Page($count[0]['total'], $pagesize);
        $list = $Dao->query($sql." limit ".$pager->firstRow.','.$pager->listRows);
        $this->assign('page', $pager->show());
        $this->assign('list',$list);
        $this->assign('list_table', true);
        $this->display();
    }

    protected function _search() {
        $map = array();
        //'status'=>1
        ($time_start = $this->_request('time_start', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));
        ($time_end = $this->_request('time_end', 'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        ($price_min = $this->_request('price_min', 'trim')) && $map['price'][] = array('egt', $price_min);
        ($price_max = $this->_request('price_max', 'trim')) && $map['price'][] = array('elt', $price_max);
        ($stock_min = $this->_request('stock_min', 'trim')) && $map['goods_stock'][] = array('egt', $stock_min);
        ($stock_max = $this->_request('stock_max', 'trim')) && $map['goods_stock'][] = array('elt', $stock_max);
      //  ($rates_min = $this->_request('rates_min', 'trim')) && $map['rates'][] = array('egt', $rates_min);
       // ($rates_max = $this->_request('rates_max', 'trim')) && $map['rates'][] = array('elt', $rates_max);
        ($uname = $this->_request('uname', 'trim')) && $map['uname'] = array('like', '%'.$uname.'%');
        $cate_id = $this->_request('cate_id', 'intval');
        $pid = $this->_request('pid', 'intval');
        $tuijian = $this->_request('tuijian', 'trim');
        if($pid){
            $map['pid'] = array('eq', $pid);
        }
        if ($cate_id) {
            $map['cate_id'] = array('eq', $cate_id);
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
            $map['tuijian'] = $tuijian;
        }


        if( $_GET['is_xiangou']==null ){
            $is_xiangou = -1;
        }else{
            $is_xiangou = intval($_GET['is_xiangou']);
        }
        $is_xiangou>=0 && $map['is_xiangou'] = array('eq',$is_xiangou);
        
        ($keyword = $this->_request('keyword', 'trim')) && $map['title'] = array('like', '%'.$keyword.'%');
        $this->assign('search', array(
            'time_start' => $time_start,
            'time_end' => $time_end,
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
            'cname' => $cname,
            'keyword' => $keyword,
        ));
        return $map;
    }

    public function add() {
        if (IS_POST) {
            $mod = D('merchant');
            //获取数据
            if (false === $data = $this->_mod->create()) {

                $this->error($this->_mod->getError());
            }
            if( !$data['pid']){
                $this->error('请选择商品分类');
            }
            //必须上传图片
            if (empty($_FILES['img']['name'])) {
                $this->error('请上传商品图片');
            }
            //上传图片
            $date_dir = date('ym/d/'); //上传目录
            $item_imgs = array(); //相册
            $result = $this->_upload($_FILES['img'], 'merchant/'.$date_dir, array(
                'width'=>C('pin_item_bimg.width').','.C('pin_item_img.width').','.C('pin_item_simg.width'), 
                'height'=>C('pin_item_bimg.height').','.C('pin_item_img.height').','.C('pin_item_simg.height'),
                'suffix' => '_b,_m,_s',
                //'remove_origin'=>true 
            ));
           
            if ($result['error']) {
                $this->error($result['info']);
            } else {
                $data['img'] = $date_dir . $result['info'][0]['savename'];
                $img_url = 'data/upload/merchant/'.$data['img'];
                $this->get_cut_img($img_url,260,280);
            }

            //标签属性
            $list_shu = $_POST['attr'];

            $address[0] = M('region')->where(array('id'=>$data['city']))->getField('name');
            $address[1] = M('region')->where(array('id'=>$data['town']))->getField('name');
            $address[2] = $data['address'];
//            $address = explode(' ',$data['address']);
            $jwdu = $this->get_jwdu($address);
            $data['jing'] = $jwdu['jingdu'];
            $data['wei'] = $jwdu['weidu'];
            if(empty($data['zhe'])){
                $data['discount'] = '';
                $data['zhe_title'] = '';
                $data['enjoy_num'] = '';
                $data['desc'] = '';
                $data['zhe'] = 0;
            }
            if(empty($data['tao'])){
                $data['tao_title'] = '';
                $data['tao'] = 0;
            }
            if($mod->add($data)){
                if( method_exists($this, '_after_insert')){
                    $id = $mod->getLastInsID();
                    $this->_after_insert($id);
                }

                $this->success(L('operation_success'),U('merchant/index',array('menuid'=>365)));
            } else {
                IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }


        } else {
            //分类信息
            $cate=M('item_cate')->field('id,name')->where(array('pid'=>0))->select();
            $this->assign('_tag', $this->_teg());  //属性 单位
            $this->assign('cate', $cate);
            //返积分规则
            $rule = M('rule')->field('id,name')->order('id desc')->select();
            $this->assign('rule', $rule);

            //返利规则
            $return_money = M('return_money')->field('id,name')->order('id desc')->select();
            $this->assign('return_money',$return_money);

            //城市
            $province = M('region')->where(array('pid'=>1))->select();
            $this->assign('province',$province);
            $this->display();
        }



    }


    //商家获取商家产品分类
    public function ajax_get_tcate(){
        $ttid = I('post.tid');
        $brand = M('item_cate')->where(array('pid'=>$ttid))->select();
        if($brand){
            $data['status'] = 1;
            $data['data'] = $brand;
        }else{
            $data['status'] = 0;
        }
        echo json_encode($data);
    }



    public function edit() {
        if (IS_POST) {
            //获取数据
            if (false === $data = $this->_mod->create()) {
                $this->error($this->_mod->getError());
            }
            $item_id = $data['id'];
            $date_dir = date('ym/d/'); //上传目录
            $item_imgs = array(); //相册
            //修改图片
            if (!empty($_FILES['img']['name'])) {
                $result = $this->_upload($_FILES['img'], 'merchant/'.$date_dir, array(
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

            $address[0] = M('region')->where(array('id'=>$data['city']))->getField('name');
            $address[1] = M('region')->where(array('id'=>$data['town']))->getField('name');
            $address[2] = $data['address'];
//            $address = explode(' ',$data['address']);
            $jwdu = $this->get_jwdu($address);
            $data['jing'] = $jwdu['jingdu'];
            $data['wei'] = $jwdu['weidu'];
            if(empty($data['zhe'])){
                $data['discount'] = '';
                $data['zhe_title'] = '';
                $data['enjoy_num'] = '';
                $data['desc'] = '';
                $data['zhe'] = 0;
            }
            if(empty($data['tao'])){
                $data['tao_title'] = '';
                $data['tao'] = 0;
            }

            //更新商品
            if (false !== $this->_mod->where(array('id'=>$item_id))->save($data)) {
                if (method_exists($this, '_after_update')) {
                    $id = $data['id'];
                    $this->_after_update($id);
                }
                IS_AJAX && $this->ajaxReturn(1, L('operation_success'), '', 'edit');
                $this->success(L('operation_success'),U('merchant/index',array('menuid'=>365)));
            }


        } else {
            $id = $this->_get('id','intval');
            $tid = $this->_get('tid','intval');
            $item = $this->_mod->where(array('id'=>$id))->find();
            //分类
            $pid_list=M('item_cate')->field('id,name')->where(array('id'=>$item['pid']))->select();
            $tid_list=M('item_cate')->field('id,name')->where(array('id'=>$item['tid']))->select();
            //特色商品分类
            $ttid_list=M('item_cate')->field('id,name')->where(array('id'=>$item['ttid']))->select();
            $this->assign('pid_list', $pid_list);
            $this->assign('tid_list', $tid_list);
            $this->assign('ttid_list', $ttid_list);
            $this->assign('info', $item);
             if(!empty($id)){
                    $garage = M('merchant')->find($id);
                    $arr = array();
                    array_push($arr, $garage['province']);
                    array_push($arr, $garage['city']);
                    array_push($arr, $garage['town']);
                    $where['id'] = array('in',$arr);
                    $name =M('Region')->where($where)->select();
                    $this->assign('name',$name);
            }
            //返积分规则
            $rule = M('rule')->field('id,name')->select();
            $this->assign('rule', $rule);

            //返利规则
            $return_money = M('return_money')->field('id,name')->order('id desc')->select();
            $this->assign('return_money',$return_money);

            $province = M('Region')->where( array('pid'=>1) )->select ();
            $this->assign('province',$province);



            //相册
            $img_list = M('item_img')->where(array('item_id'=>$id))->select();
            $this->assign('img_list', $img_list);
            //属性
            $attr_list = M('lable')->order('id asc')->where(array('sid'=>$id))->select();
            $this->assign('attr_list', $attr_list);
            $this->assign('teg',$this->_teg());
            $this->display();
        }
    }


    /*public function _before_insert($data){
        $new_start = str_replace(':', '', $data['start_time']);
        $new_end  = str_replace(':', '', $data['end_time']);
        if($new_end <= $new_start){
            IS_AJAX && $this->ajaxReturn(0, "结束营业时间不能小于开始营业时间",'','add');
            $this->error("结束营业时间不能小于开始营业时间");

        }
        return $data;
    }


    public function _before_update($data){
        $new_start = str_replace(':', '', $data['start_time']);
        $new_end  = str_replace(':', '', $data['end_time']);
        if($new_end <= $new_start){
            IS_AJAX && $this->ajaxReturn(0, "结束营业时间不能小于开始营业时间",'','add');
            $this->error("结束营业时间不能小于开始营业时间");
        }
        return $data;
    }*/

    public function map_look()
    {
        $jing = $_GET['xjing'];
        $wei = $_GET['xwei'];
        $this->assign('jing', $jing);
        $this->assign('wei', $wei);
        $this->display();
    }




    public function _after_update($id){
        for ($i = 0; $i < count($_POST['attr']['name']); $i++) {
            $labe['sid'] = $id;
            $labe['name'] = $_POST['attr']['name'][$i];
            $labe['value'] = $_POST['attr']['value'][$i];
            $labe['tag'] = $_POST['attr']['tag'][$i];
            if($labe['value']){
                if(!empty($_POST['attr']['bs'][$i])){
                    M('lable')->where(array('id'=>$_POST['attr']['id'][$i]))->save($labe);
                }else{
                    M('lable')->add($labe);

                }
            }

        }
    }


    public function get_jwdu($address){
        $url = 'http://api.map.baidu.com/geocoder?address='.$address['1'].'&nbsp'
            .$address['2'].'&output=json&key=96980ac7cf166499cbbcc946687fb414&city='.$address['0'];
        $jwd = $this->https_request($url);
        $infolist=json_decode($jwd);
        if(isset($infolist->result->location) && !empty($infolist->result->location)) {
            $data = array(
                'jingdu' => $infolist->result->location->lng,
                'weidu' => $infolist->result->location->lat,
            );
        }
        return $data;
    }

    function https_request($url, $data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
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
        $attr_mod = M('lable');
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


    //ajax_get_brand
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

    public function getRegion()
    {
        $reg = I('get.pid','','intval');
        $Region = M("Region");
        $map['pid'] = $_REQUEST["pid"];
        $map['type'] = $_REQUEST["type"];
        $list = $Region->where($map)->select();
        echo json_encode($list);
    }



}