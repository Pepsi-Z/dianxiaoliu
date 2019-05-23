<?php
/**
 * 用户信息管理
 */
class userAction extends backendAction
{

    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('user');
    }

    protected function _search() {
        $map = array();
        $map['status'] = 1;
        if( $keyword = $this->_request('keyword', 'trim') ){
            $map['_string'] = "username like '%".$keyword."%' OR email like '%".$keyword."%'";
        }
        $this->assign('search', array(
            'keyword' => $keyword,
        ));
        return $map;
    }

    public function _before_index() {
        $big_menu = array(
            'title' => "添加会员消费明细",
            'iframe' => U('user/addconsume'),
            'id' => 'add',
            'width' => '500',
            'height' => '220'
        );
//        $this->assign('big_menu', $big_menu);
    }

    public function _before_insert($data) {
        if( ($data['password']!='')&&(trim($data['password'])!='') ){
            $data['password'] = $data['password'];
        }else{
            unset($data['password']);
        }
        $birthday = $this->_post('birthday', 'trim');
        if ($birthday) {
            $birthday = explode('-', $birthday);
            $data['byear'] = $birthday[0];
            $data['bmonth'] = $birthday[1];
            $data['bday'] = $birthday[2];
        }
        return $data;
    }

    public function _after_insert($id) {
        $img = $this->_post('img','trim');
        $this->user_thumb($id,$img);
    }

    public function _before_update($data) {
        if( ($data['password']!='')&&(trim($data['password'])!='') ){
            $data['password'] = md5($data['password']);
        }else{
            unset($data['password']);
        }
        $birthday = $this->_post('birthday', 'trim');
        if ($birthday) {
            $birthday = explode('-', $birthday);
            $data['byear'] = $birthday[0];
            $data['bmonth'] = $birthday[1];
            $data['bday'] = $birthday[2];
        }
        return $data;
    }

    public function _after_update($id){
        $img = $this->_post('img','trim');
        if($img){
            $this->user_thumb($id,$img);
        }
    }

    public function user_thumb($id,$img){
        $img_path= avatar_dir($id);
        //会员头像规格
        $avatar_size = explode(',', C('pin_avatar_size'));
        $paths =C('pin_attach_path');

        foreach ($avatar_size as $size) {
            if($paths.'avatar/'.$img_path.'/' . md5($id).'_'.$size.'.jpg'){
                @unlink($paths.'avatar/'.$img_path.'/' . md5($id).'_'.$size.'.jpg');
            }
            !is_dir($paths.'avatar/'.$img_path) && mkdir($paths.'avatar/'.$img_path, 0777, true);
            Image::thumb($paths.'avatar/temp/'.$img, $paths.'avatar/'.$img_path.'/' . md5($id).'_'.$size.'.jpg', '', $size, $size, true);
        }

        @unlink($paths.'avatar/temp/'.$img);
    }

    /**
     * 添加
     */
    public function add() {
        $mod = D($this->_name);
        if (IS_POST) {
            if (false === $data = $mod->create()) {
                IS_AJAX && $this->ajaxReturn(0, $mod->getError());
                $this->error($mod->getError());
            }
            if (method_exists($this, '_before_insert')) {
                $data = $this->_before_insert($data);
            }
            if( $mod->add($data) ){
                if( method_exists($this, '_after_insert')){
                    $id = $mod->getLastInsID();
                    $this->_after_insert($id);
                }
                IS_AJAX && $this->ajaxReturn(1, L('operation_success'), '', 'add');
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            $this->assign('open_validator', true);
             $cate=M('user_category')->field('id,name')->where(array('status' =>1))->select();
                 $this->assign('cate', $cate);
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajaxReturn(1, '', $response);
            } else {
               
                $this->display();
            }
        }
    }


    /**
     * 修改
     */
    public function edit()
    {
        $mod = D($this->_name);
        $pk = $mod->getPk();
        if (IS_POST) {
            if (false === $data = $mod->create()) {
                IS_AJAX && $this->ajaxReturn(0, $mod->getError());
                $this->error($mod->getError());
            }
            if (method_exists($this, '_before_update')) {
                $data = $this->_before_update($data);
            }
            if (false !== $mod->save($data)) {
                if( method_exists($this, '_after_update')){
                    $id = $data['id'];
                    $this->_after_update($id);
                }
                IS_AJAX && $this->ajaxReturn(1, L('operation_success'), '', 'edit');
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            $id = $this->_get($pk, 'intval');
            $info = $mod->find($id);
            $cate=M('user_category')->field('id,name')->where(array('status' =>1))->select();
            $info['cate']=$cate;

            $this->assign('info', $info);
            $this->assign('open_validator', true);
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajaxReturn(1, '', $response);
            } else {
                $this->display();
            }
        }
    }


    public function add_users(){
        if (IS_POST) {
            $users = $this->_post('username', 'trim');
            $users = explode(',', $users);
            $password = $this->_post('password', 'trim');
            $gender = $this->_post('gender', 'intavl');
            $reg_time= time();
            $data=array();
            foreach($users as $val){
                $data['password']=$password;
                $data['gender']=$gender;
                $data['reg_time']=$reg_time;
                if($gender==3){
                    $data['gender']=rand(0,1);
                }
                $data['username']=$val;
                $this->_mod->create($data);
                $this->_mod->add();
            }
            $this->success(L('operation_success'));
        } else {
            $this->display();
        }
    }

    public function ajax_upload_imgs() {
        //上传图片
        if (!empty($_FILES['img']['name'])) {
            $result = $this->_upload($_FILES['img'], 'avatar/temp/' );
            if ($result['error']) {
                $this->error($result['info']);
            }else {
                $data['img'] =  $result['info'][0]['savename'];
                $this->ajaxReturn(1, L('operation_success'), $data['img']);
            }


        } else {
            $this->ajaxReturn(0, L('illegal_parameters'));
        }
    }
    /*
     *会员组管理会员
     */
     public function cateindex() {
        $uid=$this->_get('uid', 'trim');
       $map=array('uid' =>$uid);
        $mod =$this->_mod;
        !empty($mod) && $this->_list($mod, $map);
        $this->display();
    }

    /**
     * 列表处理
     *
     * @param obj $model  实例化后的模型
     * @param array $map  条件数据
     * @param string $sort_by  排序字段
     * @param string $order_by  排序方法
     * @param string $field_list 显示字段
     * @param intval $pagesize 每页数据行数
     */
    protected function _list($model, $map = array(), $sort_by='', $order_by='', $field_list='*', $pagesize=20)
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
            $order = 'DESC';
        }

        //如果需要分页
        if ($pagesize) {
            $count = $model->where($map)->count($mod_pk);
            $pager = new Page($count, $pagesize);
        }
        $select = $model->field($field_list)->where($map)->order($sort . ' ' . $order);
       // echo M()->getLastSql();
        $this->list_relation && $select->relation(true);
        if ($pagesize) {
            $select->limit($pager->firstRow.','.$pager->listRows);
            $page = $pager->show();
            $this->assign("page", $page);
        }
        $list = $select->select();
        foreach ($list as $key => $value) {
            $catename=M('user_category')->field('name')->where(array('id' =>$value['uid']))->find();
            $value['cname']=$catename['name'];
            $list[$key]=$value;//重复值list
        }
        //dump($list);
        $this->assign('list', $list);
        $this->assign('list_table', true);
    }
    /**
     * ajax检测会员是否存在
     */
    public function ajax_check_name() {
        $name = $this->_get('username', 'trim');
        $id = $this->_get('id', 'intval');
        if ($this->_mod->name_exists($name,  $id)) {
            $this->ajaxReturn(0, '该会员已经存在');
        } else {
            $this->ajaxReturn();
        }
    }



    //充值

    public function chongzhi() {
        $mod = D('user');
        $pk = $mod->getPk();
        if (IS_POST) {
            $money = $_POST['money'];
            $id = $this->_post($pk, 'intval');
            $uu = M('user')->find($id);
//            if(!$uu['email'] || $uu['email']==''){
//                //会员卡号
//                $arr['email'] = '00000'.$uu['id'];
//            }
            //充值
            $chongzhi = C('pin_level_rules');
            $udata['money'] = $_POST['money'] + $uu['money'];
            if (false !== $mod->where('id = '.intval($id))->save($udata)) {
                $mod = M('yue_log');
                $data['add_time'] = time();
                $data['money'] = $_POST['money'];
                $data['type']  = 1;
                $data['status']  = 1;
                $data['total']  = $udata['money'];
                $data['uid']   = $uu['id'];
                $m = M('yue_log')->add($data);

//                $cz = M('yue_log')->where('type = 1 and uid = '.intval($uu['id']))->getField('SUM(money)');

                if($money<$chongzhi['level1']){
                    $arr['level'] = 0;
                }elseif ($money<$chongzhi['level3']){
                    $arr['level'] =1;
//                }elseif ($cz<$level['level3']){
//                    $arr['level'] =2;
                }elseif ($money<$chongzhi['level5']  && $money>=$chongzhi['level3']){ //3000-5000
                    $arr['level'] =3;
//                }elseif ($cz<$level['level5']){
//                    $arr['level'] = 4;
                }elseif ($money<$chongzhi['level6']  && $money>=$chongzhi['level5']){
                    $arr['level'] = 5;
                }elseif ($money>=$chongzhi['level6']){
                    if($uu['level']<6){
                        $arr['level'] = 6;
                        $arr['time_6'] = time();

                    }elseif($uu['level'] == 6){

                        if($money >= C('pin_gupgrade.amoney')){
                            $num = D('yue_log')->where('add_time >'.$uu['time_6'].' and type = 1 and uid = '.$uu['id'])->count();
                            //		    				echo $mod->getLastSql();
                            if($num >= C('pin_gupgrade.anum')){
                                $arr['level'] = 7;
                                $arr['time_7'] = time();
                            }
                        }

                    }elseif($uu['level'] == 7){
                        if($money >= C('pin_gupgrade.bmoney')){
                            $num = D('yue_log')->where('add_time >'.$uu['time_7'].' and type = 1 and uid = '.$uu['id'])->count();
                            if($num >= C('pin_gupgrade.bnum')){
                                $arr['level'] = 8;
                            }
                        }
                    }
                }

                if($uu['level'] > $arr['level']){
                    $arr['level'] = $uu['level'];
                }
                M('user')->where('id = '.$uu['id'])->save($arr);
                //给推荐人积分
                if($uu['umobile'] != $uu['mobile']){
                    $user = M('user')->where(array('mobile'=>$uu['umobile']))->find();
                    if($user){
                        $array['uid'] = $user['id'];
                        $array['uname'] = $user['username'];
                        $array['action']='tuijianchongzhi';
                        $array['score'] =  $money*C('pin_score_rule.tuijianc')/100;
                        $array['add_time'] = time();
                        $um['score'] = $array['score'] + $user['score'];

                        $array['total']=$um['score'];
                        M('score_log')->add($array);
                        M('user')->where(array('id'=>$user['id']))->save($um);
                    }
                }


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
            $response = $this->fetch();
            $this->ajaxReturn(1, '', $response);

        }
    }
    //添加消费明细
    public function consume(){
        if(IS_POST){

        }else{
           $item = M('item_cate')->select();
            $user = M('user')->select();
            $this->assign('item_cate',$item);
            $this->assign('user',$user);
            $this->display();

        }

    }
    public function addconsume()
    {
        $item_order=M('item_order');
        $order_detail=M('order_detail');

        if (IS_POST) {
            $data = $_POST;
            $uid = $_POST['uid'];
            $data['status'] = 6;
            $user = M('user')->where(array('id'=>$uid))->find();
//            $data['name'] = $user['username'];
            $item = M('item')->where(array('id'=>$data['item_id']))->find();
            if($item['price'.$user['level']] > $user['money']){
                $this->error("用户余额不足");
            }
			if($data['num'] >$item['goods_stock']){
				$this->error("商品库存不足");
			}
            $total = $item['price'.$user['level']] * $data['num'];
            $data['add_time']=time();//添加时间
            $data['support_time']=time();//添加时间
            $data['userId']=$uid;//用户ID
            $data['userName']=$user['username'];//用户ID
            $dingdanhao = date("YmdHis");
            $dingdanhao .= rand(100, 999);
            $data['orderId']=$dingdanhao;//订单号
            $data['order_sumPrice'] = $total;//订单价格
            $data['goods_sumPrice']= $total;//商品价格

            if($item_order->data($data)->add()){//添加订单成功
                    $arr['quantity'] = $data['num'];//数量
                    //订单表插入一条数据

                    $arr['itemId'] = $data['item_id'];

                    $arr['title'] = $item['title'];
                    $arr['img'] = $item['img'];
                    $arr['price'] =  $item['price'.$user['level']];
                    $arr['orderId'] = $dingdanhao;
                    if($order_detail->data($arr)->add() !==false){
                        //更改用户积分
                        $uscore = $user['score'];

                        //给用户积分
                        $array['uid'] = $uid;
                        $array['uname'] = $user['username'];
                        $array['action']='consume';
                        $consume = C('pin_score_rule.consume');
                        $array['score'] =  intval($consume * $total);
                        $array['add_time'] = time();
                        $um['score'] = $array['score'] + $uscore;

                        $array['total']=$um['score'];
                        M('score_log')->add($array);
                        M('user')->where('id ='.intval($uid))->save($um);
                        //若是余额支付 扣除用户余额
                        $udata['money'] = $user['money'] - $total ;
                        M('user')->where('id = '.intval($uid))->save($udata);
                        $data['add_time'] = time();
                        $data['money'] = -$total;
                        $data['type']  = 2;
                        $data['status']  = 1;
                        $data['uid']   = $uid;
                        $data['total']   = $udata['money'];
                        if(M('yue_log')->add($data) !== false){
//                            if($user['level'] < 7){
//                                $level =C('pin_upgrade');
//                                $xf = M('yue_log')->where('type = 2 and uid = '.intval($uid))->getField('SUM(money)');
//                                $xf = abs($xf);
//                                if( $xf < $level['level1']){//<1000
//                                    $arr['level'] = 0;
//                                }elseif ($xf < $level['level3']){ //1001<x<2000
//                                    $arr['level'] =1;
//                                }elseif ($xf < $level['level5'] && $xf >= $level['level3']){
//                                    $arr['level'] =3;
//                                }elseif ($xf < $level['level6']  && $xf >= $level['level5']){
//                                    $arr['level'] = 5;
//                                }elseif ($xf <= $level['level7']){
//                                    $arr['level'] = 6;
//                                }
//                                if( $user['level'] > $arr['level']){
//                                    $arr['level'] = $user['level'];
//                                }
//                                M('user')->where('id = '.$uid)->save($arr);
//
//                            }
                        }
                       //更改商品库存

                       $ku['goods_stock'] = $item['goods_stock'] - $data['num'];
                        $ku['buy_num'] = $item['buy_num'] + $data['num'];
                        if(M('item')->where('id = '.intval($data['item_id']))->save($ku) !==false) {
                            IS_AJAX && $this->ajaxReturn(1, L('operation_success'), '', 'edit');
                            $this->success(L('operation_success'));
                        }

                    }

            }


        } else {
            $item = M('item_cate')->where(array('pid'=>0))->select();

            $this->assign('item_cate',$item);
            $id = $this->_get('id', 'intval');
            $this->assign('id',$id);
            $this->assign('open_validator', true);
            $response = $this->fetch();
            $this->ajaxReturn(1, '', $response);

        }
    }
    public function ajax_get_item(){
        $pid = I('post.id');
        $pids = I('post.pid');
        if($pids){
            $data['data'] = M('item')->where('pid = '.$pids)->select();
        }else{
            $data['data'] = M('item')->where('cate_id = '.$pid)->select();
        }
        if($data){
            $data['status']=1;
        }else{
            $data['status']=0;
        }
        echo json_encode($data);
    }
}