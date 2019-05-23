<?php
header("Content-type: text/html; charset=utf-8");
class UserAction extends userbaseAction {
    public function _initialize() {
        parent::_initialize();
        $this->get_user_info(1);
    }

	public function wx_index(){
    	$_SESSION['openid'] = $_GET['openid'];
    	$this->redirect('index');
    }
    //关于我们
    public function about(){
        $this->display();
    }
    public function my_book(){
        $uid = $this->user['id'];
        $list = M('yuyue')->table('weixin_yuyue y')->field('y.*,a.title')->join('weixin_activity a on y.cate_id = a.id')->where(array('y.uid'=>$uid))->select();
        $this->assign('list',$list);
        $this->display();
    }
    //已服务
    public function confirmOrder(){
        $id = $_POST['id'];
        $m = M('yuyue')->where('id = '.intval($id))->setField('status',1);
        if($m){
            echo 1;
        }else{
            echo 0;
        }

    }
    //取消预约
    public function cancel(){
        $id = $_POST['id'];
        $m = M('yuyue')->where('id = '.intval($id))->setField('status',3);
        if($m){
            echo 1;
        }else{
            echo 0;
        }

    }

/*    public function index(){
    	$_SESSION['foot_id'] = $_GET['foot_id'];
//    	p($this->user);
    	$item_order_mod = M('item_order');
    	$uid = $this->user['id'];
    	$count['fk'] = $item_order_mod->where('status = 1 and userId = '.intval($uid))->count();
        $count['fh'] = $item_order_mod->where('status = 2 and userId = '.intval($uid))->count();
        $count['sh'] = $item_order_mod->where('status = 3 and userId = '.intval($uid))->count();
        $count['wc'] = $item_order_mod->where('status = 4 and userId = '.intval($uid))->count();
       	$this->assign("count",$count);
        $this->display();
    }*/

    //用户个人中心
    public function index(){
        $_SESSION['foot_id'] = $_GET['foot_id'];
        if(empty($this->openid)){
            $this->redirect(U('Member/login'));
        }
       /* $op = $this->get_openid();
        if(empty($op)){
            $this->redirect(U('Member/login'));
        }*/
        /*if(empty($this->user['username'])){
            $this->redirect(U('User/wanshan'));
        }*/
        $_SESSION['foot_id'] = $_GET['foot_id'];
     	//p($this->user);
        $item_order_mod = M('item_order');
        $uid = $this->user['id'];
        $count['fk'] = $item_order_mod->where('status = 1 and userId = '.intval($uid))->count();
        $count['fh'] = $item_order_mod->where('status = 2 and userId = '.intval($uid))->count();
        $count['sh'] = $item_order_mod->where('status = 3 and userId = '.intval($uid))->count();
        $count['wc'] = $item_order_mod->where('status = 4 and userId = '.intval($uid))->count();
        $this->assign("count",$count);
        //等级
        $jifen_lev = M('user')->where('id = '.$this->user['id'])->getField('score');
        $map['cash'] = array('elt',$jifen_lev);
        $map['end'] = array('egt',$jifen_lev);
        $leve = M('group')->where($map)->getField('name');
        $this->assign('leve',$leve);
        //查看今天是否签到
        $t = time();
        $start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t)); //一天开始时间
        $end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));//一天结束时间
        $sign['add_time'] =array(between,array($start,$end));
        $sign['uid'] = $this->user['id'];
        $sign = M('user_sign')->where($sign)->find();
        $this->assign('sign',$sign);
        $this->display();
    }




    public function wanshan(){
        //上传头像
    	$url = XF_HTTP . "index.php?m=User&a=wanshan&status=1";
        $this->wx_data = $this->get_wx_config($url);
    	if(IS_POST){
    		 $data = $_POST;
//    		 $data['openid'] = $_SESSION['openid'];
             if(!$data['username']){
                 $this->error('请填写用户姓名');
             }
            if($data['tel']){
                if (!is_mobile($data['tel'])) {
                    $this->error( '手机号码不正确！');
                }
            }
            if(!$data['tel']){
                $this->error('请填写联系方式');
            }

            if($data['email']){
                if (!is_email($data['email'])) {
                    $this->error( '邮箱输入不正确！');
                }
            }
             $data['reg_time'] = time();
             $data['birthday'] = $data['birthday'];
             $u = M('user')->where('id ='.intval($data['id']))->save($data);
    		 	if($u !== false){
                    $url = U('User/index',array('openid'=>$data['openid']));
                    echo "<script>alert('资料完善成功');window.location.href='".$url."'</script>";
    		 		//$this->success("资料完善成功",U('User/index',array('openid'=>$data['openid'])));
    		 	}

    	}else{
//    		$openid = $_SESSION['openid'];
//    		$user_info = M('user')->where("openid = '".$openid."'")->find();
            $user_info = $this->user;
    		$this->assign("user_info",$user_info);
    		$this->display();
    	}
        
    }

     //检验是否重复
    public function check_tel(){
        if (D('user')->where("id <>".$_POST['id']." and tel= ".$_POST['tel'])->find()) {
            echo 0;
        } else {
            echo 1;
        }

    }



    public function benefits(){
    	$uid = $this->user['id'];
    	$level = array('普通会员','一星会员','二星会员','三星会员','四星会员','五星会员','高级会员A','高级会员B','高级会员C');
    	$uu = M('user')->where('id = '.$uid)->find();
    	$list['data'] = M('item_cate')->select();
//    	echo '<pre>';
//    	print_r($list);

        foreach($list['data'] as $k=>$v){
        	if($uu['level'] == '0'){
        		$l = 'discount';
        	}else{
        		$l = 'discount'.$uu['level'];
        	}
        	
        	if($v[$l] != '1'){
        		 $qx[$k]['dd'] = $v[$l] * 10;
                 $qx[$k]['name'] = $v['name'];
        	}

        }
//        print_r($list);
        $list['level'] = $uu['level'];
        $list['lel'] = $level[$uu['level']];
        $this->assign('qx',$qx);
        $this->assign("list",$list);
    	$this->display();
    }
    //充值记录
    public function czjl(){
    	$uid = $this->user['id'];
    	$list = M('yue_log')->where('type = 1 and uid = '.intval($uid))->select();
    	$cz = M('yue_log')->where('type = 1 and uid = '.intval($uid))->getField('SUM(money)');
    	$this->assign("list",$list);
    	$this->assign('cz',$cz);
    	$this->display();
    }
  /**
     * 收货地址
     */
    public function address() {
        $user_address_mod = M('user_address');
        $address_list = $user_address_mod->where(array('uid'=>$this->user['id']))->select();
        foreach($address_list as $k=>$v){
        	$address_list[$k]['pname'] = M('area')->where(array('id'=>$v['pid']))->getField('name');
        	$address_list[$k]['cname'] = M('area')->where(array('id'=>$v['cid']))->getField('name');
        	$address_list[$k]['aname'] = M('area')->where(array('id'=>$v['aid']))->getField('name');
        }

        $this->assign('address_list', $address_list);
        //街道
        $mk = $this->_get('mk');
        $this->assign('mk',$mk);
        $this->display();
    }
    //添加/编辑地址
    public function addresschange(){
   		$user_address_mod = M('user_address');
        if (IS_POST) {
            $mk = $this->_post('mk','trim');
            $consignee = $this->_post('consignee', 'trim');
            $address = $this->_post('address', 'trim');
            $address_info = $this->_post('address', 'trim');
	        $mobile = $this->_post('mobile', 'trim');
	        $sheng = $this->_post('pid', 'trim');
	        $shi = $this->_post('cid', 'trim');
	        $qu = $this->_post('aid', 'trim');
            $street = $this->_post('street', 'intval');
	       /* $postal = $this->_post('postal', 'trim');*/ //邮编
            $id = $this->_post('id', 'intval');
            $sjiedao = $this->street();
            $address =$sjiedao[$street].$address;
            $region_address[0] = M('area')->where(array('id'=>$shi))->getField('name');
            $region_address[1] = M('area')->where(array('id'=>$qu))->getField('name');
            $region_address[2] = $address;
            $jwdu = $this->get_jwdu($region_address);
            $jing = $jwdu['jingdu'];
            $wei = $jwdu['weidu'];
            //检验手机号
        	if (!is_mobile($mobile)) {
                $this->error( '手机号码不正确！');
             }
            if ($id) {
                $result = $user_address_mod->where(array('id'=>$id, 'uid'=>$this->user['id']))->save(array(
                    'consignee' => $consignee,
                    'address' =>  $address_info,
                    'mobile' => $mobile,
                     'pid' => $sheng,
                      'cid' => $shi,
                       'aid' => $qu,
                       'jing'=>$jing,
                       'wei'=>$wei,
                    'street'=>$street

                ));
                if ($result) {
                    if(!empty($mk)){
                        $this->redirect('Shopcart/jiesuan', array('mk'=>$mk));
                        //$this->success("编辑地址成功",U('Shopcart/jiesuan',array('mk'=>$mk)),1);

                    }else{
                        $url = U('User/address');
                        echo "<script>alert('编辑地址成功');window.location.href='".$url."'</script>";

                        //$this->success('编辑地址成功',U('User/address'));
                    }
                }else{
                    if(!empty($mk)){
                        $this->redirect('Shopcart/jiesuan', array('mk'=>$mk));
                        //$this->success("编辑地址失败",U('Shopcart/jiesuan',array('mk'=>$mk)),1);
                    }else{
                        $url = U('User/address');
                        echo "<script>alert('编辑地址失败');window.location.href='".$url."'</script>";
                        //$this->success('编辑地址失败',U('User/address'));
                    }

                }
            } else {
            	$user_address_mod->where(array('uid'=>$this->user['id']))->setField('status',0);
                $result = $user_address_mod->add(array(
                    'uid' => $this->user['id'],
                    'consignee' => $consignee,
                    'address' =>  $address_info,
                    'pid' => $sheng,
                    'cid' => $shi,
                    'aid' => $qu,
                    'status'=>1,
                    'mobile' => $mobile,
                    'jing'=>$jing,
                    'wei'=>$wei,
                    'street'=>$street
                ));
                if ($result) {
                    if(!empty($mk)){
                        $this->redirect('Shopcart/jiesuan', array('mk'=>$mk));
                        //$this->success("添加地址成功",U('Shopcart/jiesuan',array('mk'=>$mk)));
                    }else{
                        $url = U('User/address');
                        echo "<script>alert('添加地址成功');window.location.href='".$url."'</script>";
                       // $this->success('添加地址成功',U('User/address'));
                    }
                } 
            }
            
        }else{
        	$id = $this->_get('id','intval');
        	if($id){
        		$info = $user_address_mod->where('id = '.$id)->find();
        	}
	        
	        if($info['pid']){
	        	$city = M('area')->where("pid = ".$info['pid'])->select();
	        	$area = M('area')->where("pid = ".$info['cid'])->select();
	        	$this->assign("city",$city);
	        	$this->assign("area",$area);
	        }
	        $this->assign("info",$info);
	        $province = M('area')->where("pid = 0")->select();
            $jiedao = $this->street();
            $this->assign('jd',$jiedao);
	        $this->assign("province",$province);
	    	$this->display();
        }
        
    }

   //显示收货地址

    public function collect_address() {
//        $info = $this->user;
        $user_address_mod = M('collect_address');
        $collect_id = $_GET['collect_id'];
        $address_list = $user_address_mod->where(array('uid'=>$this->user['id']))->select();
        foreach($address_list as $k=>$v){
            $address_list[$k]['pname'] = M('area')->where(array('id'=>$v['pid']))->getField('name');
            $address_list[$k]['cname'] = M('area')->where(array('id'=>$v['cid']))->getField('name');
            $address_list[$k]['aname'] = M('area')->where(array('id'=>$v['aid']))->getField('name');
        }
        $this->assign('address_list', $address_list);
        $this->assign('collect_id', $collect_id);
        //街道
        $jiedao = $this->street();
        $this->assign('jd',$jiedao);
        $this->display();
    }


    //添加/收货地址
    public function collect_schange(){
        $user_address_mod = M('collect_address');
        $collect_id = $_GET['collect_id'];
        if (IS_POST) {
            $mk = $this->_get('mk','trim');
            $consignee = $this->_post('consignee', 'trim');
            $address = $this->_post('address', 'trim');
            $info_address = $this->_post('address', 'trim');
            $mobile = $this->_post('mobile', 'trim');
            $sheng = $this->_post('pid', 'trim');
            $shi = $this->_post('cid', 'trim');
            $qu = $this->_post('aid', 'trim');
            $street = $this->_post('street', 'intval');
            /* $postal = $this->_post('postal', 'trim');*/ //邮编
            $id = $this->_post('id', 'intval');
            $sjiedao = $this->street();
            $address =$sjiedao[$street].$address;
            $region_address[0] = M('area')->where(array('id'=>$shi))->getField('name');
            $region_address[1] = M('area')->where(array('id'=>$qu))->getField('name');
            $region_address[2] = $address;
            $jwdu = $this->get_jwdu($region_address);
            $jing = $jwdu['jingdu'];
            $wei = $jwdu['weidu'];
            //检验手机号
            if (!is_mobile($mobile)) {
                $this->error( '手机号码不正确！');
            }

            if (empty($street)) {
                $this->error( '请选择街道！');
            }
            if ($id) {
               /* $address = .$address*/
                $result = $user_address_mod->where(array('id'=>$id, 'uid'=>$this->user['id']))->save(array(
                    'consignee' => $consignee,
                    'address' => $info_address,
                    'mobile' => $mobile,
                    'pid' => $sheng,
                    'cid' => $shi,
                    'aid' => $qu,
                    'jing'=>$jing,
                    'wei'=>$wei,
                    'street'=>$street

                ));
                if ($result) {
                    $this->success("编辑地址成功",U('Shopcart/jiesuan',array('id'=>$collect_id,'mk'=>$mk)));
                }else{
                    $this->success("编辑地址成功",U('Shopcart/jiesuan',array('id'=>$collect_id,'mk'=>$mk)));
                }
            } else {
                $user_address_mod->where(array('uid'=>$this->user['id']))->setField('status',0);
                $result = $user_address_mod->add(array(
                    'uid' => $this->user['id'],
                    'consignee' => $consignee,
                    'address' => $info_address,
                    'pid' => $sheng,
                    'cid' => $shi,
                    'aid' => $qu,
                    'status'=>1,
                    'mobile' => $mobile,
                    'jing'=>$jing,
                    'wei'=>$wei,
                    'street'=>$street
                ));
                if ($result) {
                    $this->success("添加地址成功",U('Shopcart/jiesuan',array('id'=>$collect_id,'mk'=>$mk)));
                }
            }

        }else{
            $id = $this->_get('id','intval');
            if($id){
                $info = $user_address_mod->where('id = '.$id)->find();
            }

            if($info['pid']){
                $city = M('area')->where("pid = ".$info['pid'])->select();
                $area = M('area')->where("pid = ".$info['cid'])->select();
                $this->assign("city",$city);
                $this->assign("area",$area);
            }
            $this->assign("info",$info);
            $province = M('area')->where("pid = 0")->select();
            //街道
            $jiedao = $this->street();
            $this->assign('jd',$jiedao);
            //p($jiedao);
            //p($info);
            $this->assign("province",$province);
            $this->display();
        }

    }



    //获取经纬度
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
   //获取经纬度
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

/**
     * 删除
     */
    public function addressdel()
    {
        $mod =  M('user_address');
        $pk = $mod->getPk();
        $ids = trim($this->_request($pk), ',');
        $status = $this->_get('status','intval');
        $mk = $this->_get('mk','trim');
        if ($ids) {
            if (false !== $mod->delete($ids)) {
            	if($status == 1){
            		$id = $mod->where('uid = '.$this->user['id'])->order('id desc')->getField('id');
            		$mod->where(array('id'=>$id))->setField('status',1);
            	}
                if(!empty($mk)){
               
                    $this->redirect('Shopcart/jiesuan', array('mk' => $mk));
                    //$this->success("删除成功",U('Shopcart/jiesuan',array('mk'=>$mk)),1);
                }else{
                    $url = U('User/address');
                    echo "<script>alert('删除成功');window.location.href='".$url."'</script>";

                }
            } else {
                if(!empty($mk)){
                    
                    $this->redirect('Shopcart/jiesuan', array('mk' => $mk));
                    //$this->success("删除失败",U('Shopcart/jiesuan',array('mk'=>$mk)),1);
                }else{
                    $url = U('User/address');
                    echo "<script>alert('删除失败');window.location.href='".$url."'</script>";
                }
            }
        } else {
            IS_AJAX && $this->ajaxReturn(0, L('illegal_parameters'));
            $this->error(L('illegal_parameters'));
        }
    }

   /**
    * 设为默认地址
    * **/
     public function addressmr(){
     	$mod =  M('user_address');
     	 $id = $this->_get('id','intval');
         $mk = $this->_get('mk','trim');
     	$r = $mod->where(array('id'=>$id))->setField('status',1);
     	if($r !== false){
     		$mod->where("id <> ".intval($id)." and uid = ".intval($this->user['id']))->setField('status',0);
     		if(!empty($mk)){
                //$this->success("设置成功",U('Shopcart/jiesuan',array('mk'=>$mk)),1);
               
                $this->redirect('Shopcart/jiesuan', array('mk' => $mk));
            }else{
                     $url = U('User/address');
                     echo "<script>alert('设置成功');window.location.href='".$url."'</script>";
                     //$this->success('设置成功');
            }

     	}else{
            if($mk){
                $this->redirect('Shopcart/jiesuan', array('mk' => $mk));
                //$this->error("设置失败",U('Shopcart/jiesuan',array('mk'=>$mk)),1);
            }else{
                $url = U('User/address');
                echo "<script>alert('设置失败');window.location.href='".$url."'</script>";
                //$this->error('设置失败');
            }

     	}
     }


    /**
     * 删除收货地址
     */
    public function collect_addressdel()
    {
        $mod =  M('collect_address');
        $pk = $mod->getPk();
        $collect_id = $_GET['collect_id'];
        $ids = trim($this->_request($pk), ',');
        $status = $this->_get('status','intval');
        $mk = $this->_get('mk','trim');
        if ($ids) {
            if (false !== $mod->delete($ids)) {
                if($status == 1){
                    $id = $mod->where('uid = '.$this->user['id'])->order('id desc')->getField('id');
                    $mod->where(array('id'=>$id))->setField('status',1);
                }

                $this->success("删除成功",U('Shopcart/jiesuan',array('id'=>$collect_id,'mk'=>$mk)));
            } else {
                $this->error("删除失败",U('Shopcart/jiesuan',array('id'=>$collect_id,'mk'=>$mk)));
            }
        } else {
            IS_AJAX && $this->ajaxReturn(0, L('illegal_parameters'));
            $this->error(L('illegal_parameters'));
        }
    }

    /**
     * 收货设为默认地址
     * **/
    public function collect_addressmr(){
        $mod =  M('collect_address');
        $collect_id = $_GET['collect_id'];
        $id = $this->_get('id','intval');
        $mk = $this->_get('mk','trim');
        $r = $mod->where(array('id'=>$id))->setField('status',1);
        if($r !== false){
            $mod->where("id <> ".intval($id)." and uid = ".intval($this->user['id']))->setField('status',0);
            $this->success("设置成功",U('Shopcart/jiesuan',array('id'=>$collect_id,'mk'=>$mk)));
        }else{
            $this->success("设置失败",U('Shopcart/jiesuan',array('id'=>$collect_id,'mk'=>$mk)));
        }
    }

    /**
     * 第三方头像保存
     */
    private function _save_avatar($uid, $img) {
        //获取后台头像规格设置
        $avatar_size = explode(',', C('pin_avatar_size'));
        //会员头像保存文件夹
        $avatar_dir = C('pin_attach_path') . 'avatar/' . avatar_dir($uid);
        !is_dir($avatar_dir) && mkdir($avatar_dir,0777,true);
        //生成缩略图
        $img = C('pin_attach_path') . 'avatar/temp/' . $img;
        foreach ($avatar_size as $size) {
            Image::thumb($img, $avatar_dir.md5($uid).'_'.$size.'.jpg', '', $size, $size, true);
        }
        @unlink($img);
    }
    
    /**
     * 用户消息提示 
     */
    public function msgtip() {
        $result = D('user_msgtip')->get_list($this->visitor->info['id']);
        $this->ajaxReturn(1, '', $result);
    }

   

    /**
     * 修改头像
     */
    public function upload_avatar() {

        if (!empty($_FILES['avatar']['name'])) {
            //会员头像规格
            $avatar_size = explode(',', C('pin_avatar_size'));
            //回去会员头像保存文件夹
            $uid = abs(intval($this->visitor->info['id']));
            $suid = sprintf("%09d", $uid);
            $dir1 = substr($suid, 0, 3);
            $dir2 = substr($suid, 3, 2);
            $dir3 = substr($suid, 5, 2);
            $avatar_dir = $dir1.'/'.$dir2.'/'.$dir3.'/';
            //上传头像
            $suffix = '';
            foreach ($avatar_size as $size) {
                $suffix .= '_'.$size.',';
            }
            $result = $this->_upload($_FILES['avatar'], 'avatar/'.$avatar_dir, array(
                'width'=>C('pin_avatar_size'), 
                'height'=>C('pin_avatar_size'),
                'remove_origin'=>true, 
                'suffix'=>trim($suffix, ','),
                'ext' => 'jpg',
            ), md5($uid));
            if ($result['error']) {
                $this->ajaxReturn(0, $result['info']);
            } else {
                $data = __ROOT__.'/data/upload/avatar/'.$avatar_dir.md5($uid).'_'.$size.'.jpg?'.time();
                $this->ajaxReturn(1, L('upload_success'), $data);
            }
        } else {
            $this->ajaxReturn(0, L('illegal_parameters'));
        }
    }

    /**
     * 修改密码
     */
    public function password() {
        if( IS_POST ){
            $oldpassword = $this->_post('oldpassword','trim');
            $password   = $this->_post('password','trim');
            $repassword = $this->_post('repassword','trim');
            !$password && $this->error(L('no_new_password'));
            $password != $repassword && $this->error(L('inconsistent_password'));
            $passlen = strlen($password);
            if ($passlen < 6 || $passlen > 20) {
                $this->error('password_length_error');
            }
            //连接用户中心
            $passport = $this->_user_server();
            $result = $passport->edit($this->visitor->info['id'], $oldpassword, array('password'=>$password));
            if ($result) {
                $msg = array('status'=>1, 'info'=>L('edit_password_success'));
            } else {
                $msg = array('status'=>0, 'info'=>$passport->get_error());
            }
            $this->assign('msg', $msg);
        }
        $this->_config_seo();
        $this->display();
    }

    /**
     * 帐号绑定
     */
    public function bind() {
        //获取已经绑定列表
        $bind_list = M('user_bind')->field('type')->where(array('uid'=>$this->visitor->info['id']))->select();
        $binds = array();
        if ($bind_list) {
            foreach ($bind_list as $val) {
                $binds[] = $val['type'];
            }
        }
        
        //获取网站支持列表
        $oauth_list = $this->oauth_list;
        foreach ($oauth_list as $type => $_oauth) {
            $oauth_list[$type]['isbind'] = '0';
            if (in_array($type, $binds)) {
                $oauth_list[$type]['isbind'] = '1';
            }
        }
        $this->assign('oauth_list', $oauth_list);
        $this->_config_seo();
        $this->display();
    }


    /**
     * 取消封面
     */
    public function cancle_cover() {
        $result = M('user')->where(array('id'=>$this->visitor->info['id']))->setField('cover', '');
        !$result && $this->ajaxReturn(0, L('illegal_parameters'));
        $this->ajaxReturn(1, L('edit_success'));
    }

    /**
     * 上传封面图片
     */
    public function upload_cover() {
        if (!empty($_FILES['cover']['name'])) {
            $data_dir = date('ym/d');
            $file_name = md5($this->visitor->info['id']);
            $result = $this->_upload($_FILES['cover'], 'cover/'.$data_dir, array('width'=>'900', 'height'=>'330', 'remove_origin'=>true), $file_name);
            if ($result['error']) {
                $this->ajaxReturn(0, $result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $cover = $data_dir.'/'.$file_name.'_thumb.'.$ext;
                $data = '<img src="./data/upload/cover/'.$data_dir.'/'.$file_name.'_thumb.'.$ext.'?'.time().'">';
                //更新数据
                M('user')->where(array('id'=>$this->visitor->info['id']))->setField('cover', $cover);
                $this->ajaxReturn(1, L('upload_success'), $data);
            }
        } else {
            $this->ajaxReturn(0, L('illegal_parameters'));
        }
    }

    
    public function edit_address()
    {
       $user_address_mod = M('user_address');
        $id = $this->_get('id', 'intval');
        $info = $user_address_mod->find($id);
       
       $this->assign('info', $info);
    	$this->display();
    }
    
  
   
    /**
     * 修改用户
     */
    public function username() {
        $id = $this->visitor->info['id'];
        $user_mod= D('user');
        
        if( IS_POST ){
            $info = $user_mod->find($id);
            $editid = $this->_post('editid','trim');
            $date['nickname'] = $this->_post('nickname','trim');
            $date['tel']   = $this->_post('tel','trim');

            /*if($editid==1){
                //echo $editid;
                $username = $this->_post('username','trim');

                if($this->_mod->name_exists($username, intval($id))){
                    //echo "1";exit;
                    $this->error('用户名已存在');exit;
                }else{
                    $date['username']=$username;
                    //$date['password'] = $this->_post('password','trim');
                    $date['isedit']=$editid;
                }
            }
            if ($editid==2) {
                //echo $editid;
                $wechatid = $this->_post('wechatid','trim');
                $where = "wechatid='" . $wechatid . "' AND id<>'" . $id . "'";
                $result = $this->where($where)->count('id');
                if ($result) {
                    $this->error('微信id已被绑定，请确认是否填写正确！');exit;
                } else {
                    $date['wechatid']=$wechatid;
                    $date['isedit']=$editid;
                }
            }*/
            switch ($editid) {
                case 1:
                    $username = $this->_post('username','trim');

                    if($this->_mod->name_exists($username, intval($id))){
                        //echo "1";exit;
                        $this->error('用户名已存在');exit;
                    }else{
                        $date['username']=$username;
                        //$date['password'] = $this->_post('password','trim');
                        $date['isedit']=$editid;
                    }
                    break;
                case 2:
                    $wechatid = $this->_post('wechatid','trim');
                    $where = "wechatid='" . $wechatid . "' AND id<>'" . $id . "'";
                    $result = $this->_mod->where($where)->count('id');
                    if ($result) {
                        $this->error('微信id已被绑定，请确认是否填写正确！');exit;
                    } else {
                        $date['wechatid']=$wechatid;
                        $date['isedit']=$editid;
                    }
                    break;
                default:
                    $date['password'] = $this->_post('password','trim');
                    break;
            }
           
           echo $date;
            $result = $user_mod->where(array('id' =>$id))->save($date);
            
            if ($result) {
                $this->success('修改成功', U('User/index'));
            } else {
                $this->error('失败');
            }   
        }else{
        $info = $user_mod->find($id);
        $this->assign('info', $info);
        $this->_config_seo();
        $this->display();}
    }

     /**
     * 检测会员是否存在
     */
    public function check_name($name) {
        $id = $this->_get('id', 'intval');
        if ($this->_mod->name_exists($name,  $id)) {
            $this->ajaxReturn(0, '该会员已经存在');
        } else {
            $this->ajaxReturn();
        }
    }
    /**
     * 检测用户
     */
    public function ajax_check() {
        $type = $this->_get('type', 'trim', 'email');
        $user_mod = D('user');
        switch ($type) {
            case 'email':
                $email = $this->_get('J_email', 'trim');
                $user_mod->email_exists($email) ? $this->ajaxReturn(0) : $this->ajaxReturn(1);
                break;
            
            case 'username':
                $username = $this->_get('J_username', 'trim');
                $user_mod->name_exists($username) ? $this->ajaxReturn(0) : $this->ajaxReturn(1);
                break;
        }
    }

    /**
     * 关注
     */
    public function follow() {
        $uid = $this->_get('uid', 'intval');
        !$uid && $this->ajaxReturn(0, L('follow_invalid_user'));
        $uid == $this->visitor->info['id'] && $this->ajaxReturn(0, L('follow_self_not_allow'));
        $user_mod = M('user');
        if (!$user_mod->where(array('id'=>$uid))->count('id')) {
            $this->ajaxReturn(0, L('follow_invalid_user'));
        }
        $user_follow_mod = M('user_follow');
        //已经关注？
        $is_follow = $user_follow_mod->where(array('uid'=>$this->visitor->info['id'], 'follow_uid'=>$uid))->count();
        $is_follow && $this->ajaxReturn(0, L('user_is_followed'));
        //关注动作
        $return = 1;
        //他是否已经关注我
        $map = array('uid'=>$uid, 'follow_uid'=>$this->visitor->info['id']);
        $isfollow_me = $user_follow_mod->where($map)->count();
        $data = array('uid'=>$this->visitor->info['id'], 'follow_uid'=>$uid, 'add_time'=>time());
        if ($isfollow_me) {
            $data['mutually'] = 1; //互相关注
            $user_follow_mod->where($map)->setField('mutually', 1); //更新他关注我的记录为互相关注
            $return = 2;
        }
        //关注的人数
        $id = 1;
        $resulrs = M('weixin')->where(array('id'=>$id))->add();

        $result = $user_follow_mod->add($data);
        !$result && $this->ajaxReturn(0, L('follow_user_failed'));
        //增加我的关注人数
        $user_mod->where(array('id'=>$this->visitor->info['id']))->setInc('follows');
        //增加Ta的粉丝人数
        $user_mod->where(array('id'=>$uid))->setInc('fans');
        //提醒被关注的人
        D('user_msgtip')->add_tip($uid, 1);
        //把他的微薄推送给我
        //TODO...是否有必要？
        $this->ajaxReturn(1, L('follow_user_success'), $return);
    }

    /**
     * 取消关注
     */
    public function unfollow() {
        $uid = $this->_get('uid', 'intval');
        !$uid && $this->ajaxReturn(0, L('unfollow_invalid_user'));
        $user_follow_mod = M('user_follow');
        if ($user_follow_mod->where(array('uid'=>$this->visitor->info['id'], 'follow_uid'=>$uid))->delete()) {
            $user_mod = M('user');
            //他是否已经关注我
            $map = array('uid'=>$uid, 'follow_uid'=>$this->visitor->info['id']);
            $isfollow_me = $user_follow_mod->where($map)->count();
            if ($isfollow_me) {
                $user_follow_mod->where($map)->setField('mutually', 0); //更新他关注我的记录为互相关注
            }
            //减少我的关注人数
            $user_mod->where(array('id'=>$this->visitor->info['id']))->setDec('follows');
            //减少Ta的粉丝人数
            $user_mod->where(array('id'=>$uid))->setDec('fans');
            //删除我微薄中Ta的内容
            M('topic_index')->where(array('author_id'=>$uid, 'uid'=>$this->visitor->info['id']))->delete();
            $this->ajaxReturn(1, L('unfollow_user_success'));
        } else {
            $this->ajaxReturn(0, L('unfollow_user_failed'));
        }
    }

    /**
     * 移除粉丝
     */
    public function delfans() {
        $uid = $this->_get('uid', 'intval');
        !$uid && $this->ajaxReturn(0, L('delete_invalid_fans'));
        $user_follow_mod = M('user_follow');
        if ($user_follow_mod->where(array('follow_uid'=>$this->visitor->info['id'], 'uid'=>$uid))->delete()) {
            $user_mod = M('user');
            //减少我的粉丝人数
            $user_mod->where(array('id'=>$this->visitor->info['id']))->setDec('fans');
            //减少Ta的关注人数
            M('user')->where(array('id'=>$uid))->setDec('follows');
            //删除Ta微薄中我的内容
            M('topic_index')->where(array('author_id'=>$this->visitor->info['id'], 'uid'=>$uid))->delete();
            $this->ajaxReturn(1, L('delete_fans_success'));
        } else {
            $this->ajaxReturn(0, L('delete_fans_failed'));
        }
    }
    
/*=======================by lyye 2014-03-30=======================*/
    /*
     * 帐户中心
     */
    public function account()
    {
    	$userid = $this->visitor->info['id'];
    	$user = M('user');
    	$user_info = $user->where("id='$userid'")->find();
    	!$user_info && $this->_404();
    	$this->assign('userinfo',$user_info);
    	$this->display();
    }
    public function chongzhi()
    {
    	$this->display();
    }
	public function chongzhi_do()
	{
		$jiner = $this->_post('jiner', 'trim');
		if(empty($jiner) || !is_numeric($jiner))
		{
			$this->error('请输入充值金额');
		}
		$pay=M('pay')->where(array('pay_type'=>'alipay'))->find();
		$alipay=unserialize($pay['config']);
		//添加充值记录
		$user_acclog  = M('user_acclog');
		$userid = $this->visitor->info['id'];
		$user = M('user');
    	$userinfo = $user->where("id='$userid'")->find();
    	$orderId = $userid.date("YmdHis",time()).rand(1, 99);
		$log_data['userid']		= $userid;
		$log_data['username']	= $userinfo['username'];
		$log_data['fl']			= 2;
		$log_data['jiner']		= sprintf("%01.2f",$jiner);
		$log_data['addtime']	= time();
		$log_data['info']		= '支付宝充值';
		$log_data['orderid']	= $orderId;
		$log_data['status']		= '处理中';
		$user_acclog->add($log_data);
		echo "<script>location.href='api/chongzhipay/alipayapi.php?WIDseller_email=".$alipay['alipayname']."&WIDout_trade_no=".$orderId."&WIDsubject=".$orderId."&WIDtotal_fee=".$jiner."'</script>";
	}
	
	//微信头像上传
   public function download_img(){
       $serverId = $_GET['serverId'];
        $imgurl = $this->downImg($serverId);
         if($imgurl){
             $data['cover'] = $imgurl;
             M('user')->where('id ='.intval($this->user['id']))->save($data);
             $arr['state'] = 1;
             $arr['imgurl'] = $imgurl;
         }else{
             $arr['state'] = 0;
         } 
         echo json_encode($arr);
   }
   
   public function wanshans(){
       $url = XF_HTTP . "index.php?m=User&a=wanshans&status=1";
       $this->wx_data = $this->get_wx_config($url);
       $this->display();
   }


    //退出账号
    public function logout() {

        session('user', null);
        $data['openid'] = '';
        M('user')->where('openid = '."'$this->openid'")->save($data);
        $this->redirect(U('Member/login'));
        exit;

        //$_SESSION['user'] = null;
        //跳转到退出前页面（执行同步操作）
        //$this->redirect(U('Member/login'));
    }
    //同城速递订单
    public function cityexpress(){

        $city_list = M('cityexpress_order')->where('uid = '.$this->user['id'])->select();
        $this->assign('city_list',$city_list);
        $this->display();
    }

    public function city_order(){
        $info = M('cityexpress_order')->where('id = '.$_GET['id'])->find();
        $this->assign('info',$info);
        $this->display();
    }

    public function city_confirmOrder(){
        $id = $_POST['id'];
        $m = M('cityexpress_order')->where('id = '.intval($id))->setField('status',3);
        if($m){
            echo 1;
        }else{
            echo 0;
        }

    }
    //订票订场
    public function ticket_order(){
        $ticket_list = M('return_integral')->where('uid = '.$this->user['id'])->select();
        $this->assign('ticket_list',$ticket_list);
        $this->display();
    }

    public function ticket_order_info(){
        $info = M('return_integral')->where('id = '.$_GET['id'])->find();
        $this->assign('info',$info);
        $this->display();
    }

    //确认收货
    public function ticket_confirmOrder(){
        $id = $_POST['id'];
        $m = M('return_integral')->where('id = '.intval($id))->setField('status',3);
        if($m){
            echo 1;
        }else{
            echo 0;
        }

    }

    //积分明细
    public function jifen_info(){
        $score_log = M('score_log')->where(array('uid'=>$this->user['id']))->order('add_time desc')->select();
        $this->assign('score',$this->user['score']);
        $this->assign('score_log',$score_log);
        $this->display();
    }


    //我的小六卡
    public function user_card(){
        $user_info = $this->user;
        $this->assign("user",$user_info);
        $this->display();
    }

    public function xu_price(){
        $info = M('cardtype')->where(array('status'=>1))->select();
        $this->assign('info',$info);
        $aa = date("Y-m-d",time());
        //p($aa);
        //echo   date("Y-m-d", strtotime("+6 months", strtotime($aa)));
        $this->display();
    }


    //续费支付
    public function xu_pay(){
        $user_id = $this->user['id'];
        $money = $_POST['money'];
        //$money = M('cardtype')->where(array('id'=>$_POST['cardtype']))->getField('money');
        if(IS_POST) {
            /*if(!empty($_POST['cardtype'])){
                $card_name = M('cardtype')->where(array('id'=>$_POST['cardtype']))->getField('gname');
                if($card_name == '半年卡'){

                    $user_begin =  M('user')->where(array('id'=>$user_id))->getField('end_time');
                    $data['end_time'] = date("Y-m-d", strtotime("+6 months", strtotime($user_begin)));
                }elseif($card_name == '一年卡'){
                    $user_begin =  M('user')->where(array('id'=>$user_id))->getField('end_time');
                    $data['end_time'] = date("Y-m-d", strtotime("+12 months", strtotime($user_begin)));
                }else{
                    $data['begin_time'] = '';
                    $data['end_time'] = '';
                }
            }*/
            //微信支付
            $body = "充值金额";//支付名称
            $Total_fee = $money; //支付金额
            $xfurl = XF_HTTP;
            $type = 2;
            //$Total_fee = 0.01;
            $dingdanhao = date("YmdHis");  //订单编号
            $dingdanhao .= rand(100, 999);//订单编号 日期加随机数
            $trade_no = $dingdanhao;
            $param1 = "body=" . $body ."&trade_no=".$trade_no. "&Total_fee=" . $Total_fee . "&xfurl=" . $xfurl . '&type=' . $type . '&end_time=' . $data['end_time']. '&money=' . $money;
            echo "<script>location.href='api/wxqrpay/unifiedorder.php?" . $param1 . "'</script>";
        }else{

            $this->display();
        }

    }

    public function xu_call_back(){
        $user_id = $this->user['id'];
        $money = $_GET['money'];  //价钱

        $umoney = $this->user['money'];
        $data['money'] = $money + $umoney;  //价钱

        $data['end_time'] = $_GET['end_time'];  //结束时间
        if(false !==M('user')->where(array('id'=>$user_id))->save($data)){
            //余额表插入一条数据
            $yue['uid'] = $user_id;
            $yue['type'] = 1;
            $yue['status'] = 1;
            $yue['money'] = $money;
            $yue['add_time'] = time();
            M('yue_log')->add($yue);
            $url = U('User/user_card');
            echo "<script>alert('充值成功');window.location.href='".$url."'</script>";
        }else{
            $url = U('User/user_card');
            echo "<script>alert('充值失败');window.location.href='".$url."'</script>";
        }

    }
}