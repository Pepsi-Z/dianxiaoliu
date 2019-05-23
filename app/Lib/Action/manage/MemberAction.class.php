<?php
class MemberAction extends  membersAction {
    public function _initialize(){
        parent::_initialize();
        $this->_mod = D('admin');


    }
    public function index(){

        $this->display();
    }







    public function login(){
        if($this->weixininfo !=''){
            $this->redirect('Member/personal');
        }
        if(IS_POST){
            $where['username'] = $_POST['name'];
            $where['password'] = md5($_POST['pass']);
            $user = $this->_mod->where($where)->find();

            if(!empty($user)){
                $map['openid'] = $this->get_openid();
                $this->_mod->where('id='.intval($user['id']))->save($map);
                $_SESSION['user'] = $user;
                $this->ajaxReturn(1);
            }else{
                $_SESSION['user'] = '';
                $this->ajaxReturn(0);
            }
            die;
        }
        $this->display();
    }

    public function personal(){
        $this->display();
    }
    public function choose_dc(){
        $this->display();
    }
    public function choose_kh(){
        $this->display();
    }
    public function choose_xf(){
        $this->display();
    }
    //消费信息
    public function xfxx(){
        $this->display();
    }
    //会员充值
    public function hy_cz(){
        $map = "weixin_member_money.mid !='' and weixin_member_money.type=2";

        $start_time_min = $this->_request('start_time_min', 'trim');
        $start_time_max = $this->_request('start_time_max', 'trim');
        $keyword = $this->_request('keyword', 'trim');

        if($keyword){
            $str = " like '%{$keyword}%' ";
            $map .= " and weixin_member.vipnum {$str}";
        }

        if($start_time_min){
            $map .= " and addtime >= ".strtotime($start_time_min);
        }

        if($start_time_max){
            $map .= " and addtime <= ".(strtotime($start_time_max)+86400);
        }

        $this->assign('search', array(
            'start_time_min' => $start_time_min,
            'start_time_max' => $start_time_max,
            'keyword' => $keyword,
        ));


        $join = array();
        $hycz = M('member_money')->join('weixin_member on weixin_member.id = weixin_member_money.mid')->
        where($map)->select();
        $hycz_zj = M('member_money')->field('sum(money) money')->find();
        $this->assign('hycz',$hycz);
        $this->assign('zj',$hycz_zj);
//        dump( $hycz['zongjia'] );die;
        $_SESSION['stste'] = 'huiyuan';
        $this->display();
    }
    public function hy_xf(){

        $start_time_min = $this->_request('start_time_min', 'trim');
        $start_time_max = $this->_request('start_time_max', 'trim');
        $keyword = $this->_request('keyword', 'trim');
        $map =1;
        if($keyword){
            $str = " like '%{$keyword}%' ";
            $map .= " and kahao {$str}";
        }
        if($start_time_min){
            $map .= "  and addtime >= ".strtotime($start_time_min);
        }


        if($start_time_max){
            $map .= " and  addtime <= ".(strtotime($start_time_max)+86400);
        }

        $this->assign('search', array(
            'start_time_min' => $start_time_min,
            'start_time_max' => $start_time_max,
            'keyword' => $keyword,
        ));

        $hyxf = M('goods_order')->join('weixin_goods on weixin_goods_order.goodsid = weixin_goods.id')->where
        ('weixin_goods_order.status = 1 and weixin_goods_order.kahao != 0')->select();
        $hyxf1 = M('goods_order')->group('number')->where($map)->order('addtime desc')->select();
        foreach($hyxf1 as $k=>$v){
            $hyxf2[$k] = M('goods_order')->join('weixin_goods on weixin_goods_order.goodsid = weixin_goods.id')->where
            ('weixin_goods_order.status in (1,2) and weixin_goods_order.kahao != 0 and weixin_goods_order.number ='
                .$v['number'])
                ->select();
            if(empty($hyxf2[$k])){
                unset($hyxf2[$k]);
            }
        }
        $arr = 0;
        $i=0;
        $aaa = array();
        foreach($hyxf2 as $key=>$val){
            $i+=1;
            foreach($val as $k=>$v){
                $arr +=$v['price']*$v['num'];
                $aaa[$i] += $v['price']*$v['num'];
            }
        }
        $this->assign('hyxf',$hyxf2);
        $this->assign('aaa',$aaa);
        $this->display();
    }


    public function tuifei(){
        $kahao = M('goods_order')->where(array('number'=>$_GET['number']))->getField('kahao');
        $mid = M('member')->where(array('vipnum'=>$kahao))->getField('id');
        $setting = M('setting')->where('id = 1')->getField('integral');
        $join = array('a left join weixin_goods  b ON a.goodsid = b.id');
        $list = M('goods_order')->join($join)->where("a.number='".$_GET['number']."'")->order('addtime desc')->select();
        $jifens = '';
        $jiner = '';
        foreach($list as $k=>$v){
            $list[$k]['jifen'] = $v['price']*$setting*$v['num'];
            $list[$k]['setting'] = $setting;
            $jifens = $v['price']*$setting*$v['num']+$jifens;
            $jiner = $v['price']*$v['num']+$jiner;
            $tt = time()-60*60*24*3;
            if($tt < $v['addtime']){
                $tfstate = 1;//小于三天 可以退费
            }else{
                $tfstate = 0;
            }
        }

        $this->assign('list',$list);
        $this->assign('tfstate',$tfstate);
        $this->assign('mid',$mid);
        $this->assign('jifens',$jifens);
        $this->assign('jiner',$jiner);
        $this->display();
    }


    public function fhy_tuifei(){
        $kahao = M('goods_order')->where(array('number'=>$_GET['number']))->getField('kahao');
        $mid = M('member')->where(array('vipnum'=>$kahao))->getField('id');
        $setting = M('setting')->where('id = 1')->getField('integral');
        $join = array('a left join weixin_goods  b ON a.goodsid = b.id');
        $list = M('goods_order')->join($join)->where("a.number='".$_GET['number']."'")->order('addtime desc')->select();
         $jifens = '';
        $jiner = '';
        foreach($list as $k=>$v){
            $list[$k]['jifen'] = $v['price']*$setting*$v['num'];
            $list[$k]['setting'] = $setting;
//            $jifens = $v['price']*$setting*$v['num']+$jifens;
            $jiner = $v['price']*$v['num']+$jiner;
            $tt = time()-60*60*24*3;
            if($tt < $v['addtime']){
                $tfstate = 1;//小于三天 可以退费
            }else{
                $tfstate = 0;
            }
        }
        $this->assign('list',$list);
        $this->assign('tfstate',$tfstate);
        $this->assign('mid',$mid);
        $this->assign('jiner',$jiner);
        $this->assign('out_trade_no',$list[0]['out_trade_no']);
        $this->display();
    }

    public function hy_tuifei(){
        $arr_tf = M('member_money')->where('order_number = '.$_POST['order_number'])->select();
        $tf = array();
        foreach($arr_tf as $key => $val){
            $tf['mid'] = $val['mid'];
            if($val['type'] == 3){
                $tf['type'] = 22;
            }else{
                $tf['type'] = 7;
            }
            $tf['money'] = -$val['money'];
            $tf['managerid'] = $this->weixininfo['id'];
            $tf['managername'] = $this->weixininfo['username'];
            $tf['state'] = $val['state'];
            $tf['addtime'] = time();
            $tf['order_number'] = $val['order_number'];
            $tf['vipnum'] = $val['vipnum'];
            M('member_money')->add($tf);
        }
        M('goods_order')->where("number = '".$_POST['order_number']."'")->save(array('status'=>2));
        $this->redirect('Member/hy_tf','', 1, '退费成功 页面跳转中...');
        die;
    }

    public function hy_tf(){

        $map =1;
        $start_time_min = $this->_request('start_time_min', 'trim');
        $start_time_max = $this->_request('start_time_max', 'trim');

        $keyword = $this->_request('keyword', 'trim');

        if($keyword){
            $str = " like '%{$keyword}%' ";
            $map .= " and ( dianhua {$str} or xingming {$str} )";
        }


        if($start_time_min){
            $map .= " and addtime >= ".strtotime($start_time_min);
        }

        if($start_time_max){
            $map .= " and addtime <= ".(strtotime($start_time_max)+86400);
        }

        $this->assign('search', array(
            'start_time_min' => $start_time_min,
            'start_time_max' => $start_time_max,
            'keyword' => $keyword,
        ));

        $hyxf = M('goods_order')->join('weixin_goods on weixin_goods_order.goodsid = weixin_goods.id')->where
        ('weixin_goods_order.status = 1 and weixin_goods_order.kahao != 0')->select();
        $hyxf1 = M('goods_order')->group('number')->where($map)->order('addtime desc')->select();
        foreach($hyxf1 as $k=>$v){
            $hyxf2[$k] = M('goods_order')->join('weixin_goods on weixin_goods_order.goodsid = weixin_goods.id')->where
            ('weixin_goods_order.status = 2 and weixin_goods_order.kahao != 0 and weixin_goods_order.number ='
                .$v['number'])
                ->select();
            if(empty($hyxf2[$k])){
                unset($hyxf2[$k]);
            }
        }
        $arr = 0;
        $i=0;
        $aaa = array();
        foreach($hyxf2 as $key=>$val){
            $i+=1;
            foreach($val as $k=>$v){
                $arr +=$v['price']*$v['num'];
                $aaa[$i] += $v['price']*$v['num'];
            }
        }
        $this->assign('hyxf',$hyxf2);
        $this->assign('aaa',$aaa);
        $this->display();
    }



    //非会员消费
    public function fhy_xf(){
        $map =1;
        $start_time_min = $this->_request('start_time_min', 'trim');
        $start_time_max = $this->_request('start_time_max', 'trim');

        $keyword = $this->_request('keyword', 'trim');

        if($keyword){
            $str = " like '%{$keyword}%' ";
            $map .= " and ( dianhua {$str} or xingming {$str} )";
        }

        if($start_time_min){
            $map .= " and addtime >= ".strtotime($start_time_min);
        }

        if($start_time_max){
            $map .= " and addtime <= ".(strtotime($start_time_max)+86400);
        }

        $this->assign('search', array(
            'start_time_min' => $start_time_min,
            'start_time_max' => $start_time_max,
            'keyword' => $keyword,
        ));



        $hyxf = M('goods_order')->join('weixin_goods on weixin_goods_order.goodsid = weixin_goods.id')->where
        ('weixin_goods_order.status in (1,2) ')->select();
        $hyxf1 = M('goods_order')->group('number')->where($map)->order('addtime desc')->select();
        foreach($hyxf1 as $k=>$v){
            $hyxf2[$k] = M('goods_order')->join('weixin_goods on weixin_goods_order.goodsid = weixin_goods.id')->where
            ('weixin_goods_order.status in (1,2) and weixin_goods_order.number ='.$v['number'])->select();
            if(empty($hyxf2[$k])){
                unset($hyxf2[$k]);
            }
        }
        $arr = 0;
        $i=0;
        $aaa = array();
        foreach($hyxf2 as $key=>$val){
            $i+=1;
            foreach($val as $k=>$v){
                $arr +=$v['price']*$v['num'];
                $aaa[$i] += $v['price']*$v['num'];
            }
        }
        $this->assign('hyxf',$hyxf2);
        $this->assign('aaa',$aaa);
        $this->display();
    }

    public function fhy_tf(){
        $map =1;
        $start_time_min = $this->_request('start_time_min', 'trim');
        $start_time_max = $this->_request('start_time_max', 'trim');

        $keyword = $this->_request('keyword', 'trim');

        if($keyword){
            $str = " like '%{$keyword}%' ";
            $map .= " and ( dianhua {$str} or xingming {$str} )";
        }

        if($start_time_min){
            $map .= " and addtime >= ".strtotime($start_time_min);
        }

        if($start_time_max){
            $map .= " and addtime <= ".(strtotime($start_time_max)+86400);
        }

        $this->assign('search', array(
            'start_time_min' => $start_time_min,
            'start_time_max' => $start_time_max,
            'keyword' => $keyword,
        ));


        $hyxf = M('goods_order')->join('weixin_goods on weixin_goods_order.goodsid = weixin_goods.id')->where
        ('weixin_goods_order.status = 2 ')->select();
        $hyxf1 = M('goods_order')->group('number')->where($map)->order('addtime desc')->select();
        foreach($hyxf1 as $k=>$v){
            $hyxf2[$k] = M('goods_order')->join('weixin_goods on weixin_goods_order.goodsid = weixin_goods.id')->where
            ('weixin_goods_order.status = 2  and  weixin_goods_order.number ='.$v['number'])
                ->select();
            if(empty($hyxf2[$k])){
                unset($hyxf2[$k]);
            }
        }
        $arr = 0;
        $i=0;
        $aaa = array();
        foreach($hyxf2 as $key=>$val){
            $i+=1;
            foreach($val as $k=>$v){
                $arr +=$v['price']*$v['num'];
                $aaa[$i] += $v['price']*$v['num'];
            }
        }
        $this->assign('hyxf',$hyxf2);
        $this->assign('aaa',$aaa);
        $this->display();
    }

    public function member_list(){
        $map = 'vipnum != 0';
        $keyword = $this->_request('keyword', 'trim');
        if($keyword){
            $str = " like '%{$keyword}%' ";
            $map .= " and (name {$str} or tel {$str} or vipnum {$str})";
        }

        $list = M('member')->where($map)->order('id desc')->select();
        $this->assign('search', array(
            'keyword' => $keyword,
        ));
        $this->assign('list',$list);
        $this->display();
    }
    public function unmember_list(){
        $map = 'vipnum = 0';
        $keyword = $this->_request('keyword', 'trim');
        if($keyword){
            $str = " like '%{$keyword}%' ";
            $map .= " and (name {$str} or tel {$str})";
        }

        $list = M('member')->where($map)->order('id desc')->select();
        $this->assign('list',$list);
        $this->assign('search', array(
            'keyword' => $keyword,
        ));
        $this->display();
    }
    public function member_xx(){
        $id = I('get.id','','intval');
        $join = array('a left join weixin_cardtype  b ON a.cardtype = b.id');
        $info = M('member')->join($join)->where('a.id='.$id)->find();
        $this->assign('info',$info);
        $mom =  M('member_money')->field('sum(money) mom')->where("state = 1  and type in (1,2,3,12,14,17,22) and mid=".intval($id))->find();//余额
        $jf =  M('member_money')->field('sum(money) jf')->where('state = 1 and type in (4,6,7,8,10,11,13,16,18) and mid='
            .intval($id))->find();//积分
        $this->assign('mom',$mom);
        $this->assign('jf',$jf);
        $this->display();
    }

    public function chongzhi(){
        if($_GET['vipnum'] != ''){
            $ye = M('member_money')->field('sum(money) money')->where("type in (1,2,3,12,14,17,22) and state = 1 and vipnum =".intval($_GET['vipnum']))->find();
        }else{
            $ye = M('member_money')->field('sum(money) money')->where("type in (1,2,3,12,14,17,22) and state = 1 and mid =".intval($_GET['id']))->find();
        }
        $setting = M('setting')->where("id = 1")->getField('recharge');
        $this->assign('ye',$ye);
        $this->assign('vipnum',$_GET['vipnum']);
        $this->assign('setting',$setting);
        $this->display();
    }


    public function zhifu(){
        if($_POST['vipnum'] != ''){
            $vipnum = $_POST['vipnum'];
        }else{
            $vipnum = M('member')->where("id=".intval($_POST['mid']))->getField('vipnum');
        }

        $this->assign('allprice',$_POST['money']);
        $this->assign('hy_id',$_POST['mid']);
        $this->assign('vipnum',$vipnum);
        $this->display();
    }


    public function pay_sh(){
        $data = array();
        $member_money = M('member_money');
        $tijiao_jf = I('post.tijiao_jf');
        $tijiao_je = I('post.tijiao_je');
        $tijiao_wx = I('post.tijiao_wx');
        $tijiao_xj = I('post.tijiao_xj');

        $name = I('post.name');
        $tel = I('post.tel');
        $hyid = I('post.hyid');
        $vipnum = I('post.vipnum');

        //$data['hy_num'] = I('post.hy_order_number');
        $data['mid'] = $hyid;
        $data['managerid'] = $this->weixininfo['id'];
        $data['managername'] = $this->weixininfo['username'];
        $data['vipnum'] = $vipnum;

        $data['order_number'] = $this->NoRand();
        if(M('member_money')->where(array('order_number'=>$data['order_number']))->find()){
            $data['order_number'] = $this->NoRand();
        }


        $data['addtime'] = time();
        $data['name'] = $name;
        $data['tel'] = $tel;



        if(!empty($tijiao_xj)){
            $data['money'] = $tijiao_xj;
            $data['state'] = '1';
            $data['type'] = '12';//余额消费 订场
            $member_money->add($data);
        }

        if(!empty($tijiao_wx)){
            $data['money'] = $tijiao_wx;
            $data['state'] = '0';
            $data['type'] = '2';//积分消费 订场
            $member_money->add($data);
        }

        if(!empty($tijiao_wx)){

            $body = "二维码支付";

            $trade_no =  $data['order_number'];
           // $Total_fee = $data['order_number'];
            $xfurl = XF_HTTP;
            $Total_fee = $tijiao_wx;
            $Total_fee = 0.01;

            $param1 = "body=".$body."&trade_no=".$trade_no."&Total_fee=".$Total_fee."&xfurl=".$xfurl."&id=".$order_id;
            echo "<script>location.href='api/wxqrpay/native.php?".$param1."'</script>";
//
//            $this->redirect('Member/erweima',array('jiner'=>$tijiao_wx,''));
        }else{
            $ww['order_number'] = $data['order_number'];
            M('ordersite')->where($ww)->save(array('order_status'=>'1'));
            M('member_money')->where($ww)->save(array('state'=>'1'));

            $mid = M('member_money')->where($ww)->find();


            $this->redirect('home/Index/succ',array('chongzhi'=>'1','mid' =>$mid['mid']));
            //echo ('跳到成功页面');die;
        }

    }


    public function payajax(){
        $allprice = intval($_POST['allprice']);
        $jf = $_POST['val1'];
        $ye = $_POST['val2'];
        $val3 = $_POST['val3'];
        $integral =  M('setting')->field('integral')->find();
        $mom = round($jf/$integral['integral'],2);//剩余积分相当于多少钱
        $postall = intval($ye+$mom);

        if($jf != null && $ye == null){
            if($allprice > $mom){
                echo "积分不足请继续选择支付方式支付!";
            }else{
                echo 'ok';
            }
        }elseif($jf != null && $ye != null){
            if($allprice > $postall) {
                echo "您的余额和积分不足请继续选择其他方式支付!";
            }else{
                echo "ok";
            }
        }else{

            if($allprice > $val3) {
                echo "您的余额不足请继续选择其他方式支付!";
            }else{
                echo "ok";
            }
        }
        die;

        $jf = $_POST['jf'];
        $ye = $_POST['ye'];
        $allprice = $_POST['allprice'];
        $integral =  M('setting')->field('integral')->find();
        $integrals = round($integral['integral'],2);  //1元等于这么多积分
        $wpay = $allprice - ($ye+($jf/$integrals)); //微信应该支付的钱了
        echo $wpay;die;

    }


    public function chongzhi_succ(){
        $ye = M('member_money')->field('sum(money) money')->where("type in (1,2,3,12,14,17,22) and state = 1 and mid =".intval
            ($_GET['id']))->find();
        $jf = M('member_money')->field('sum(money) jf')->where("state = 1 and mid = {$_GET['id']} and
        type in (4,6,7,8,10,11,13,16,18)")->find();
        $this->assign('ye',$ye);
        $this->assign('jf',$jf);
        $this->assign('je',I('get.je'));
        $this->display();
    }

    public function add_member() {
        $mod = D('member');
        if (IS_POST) {
            if (false === $data = $mod->create()) {
                IS_AJAX && $this->ajaxReturn(0, $mod->getError());
                $this->error($mod->getError());
            }
            $data['status'] = 1;
            $data['password'] = md5($data['password']);

            $info_id =  M('member')->where(array('name'=>$data['name'],'tel' => $data['tel'],'vipnum' => 0))->find();
            if($info_id){
                if(false !==  $mod->where(array('id' =>$info_id['id']))->save($data)){
                    if( method_exists($this, '_after_insert')){
                        $id = $info_id['id'];
                        $this->_after_insert($id);
                    }
                    $url = U('Member/member_list');
                    echo "<script>alert('添加会员成功');window.location.href='".$url."'</script>";
                    //$this->redirect(U('Member/member_list'));
                }else{
                    $this->error('添加失败');
                }

            }else{
                if( $mod->add($data) ){
                    if( method_exists($this, '_after_insert')){
                        $id = $mod->getLastInsID();
                        $this->_after_insert($id);
                    }
                    $url = U('Member/member_list');
                    echo "<script>alert('添加会员成功');window.location.href='".$url."'</script>";
                  /*  $this->redirect(U('Member/member_list'));*/
                } else {
                    $this->error('添加失败');
                }

            }

        } else {
            $cardtype  = D('cardtype')->where('status=1')->select();
            $this->assign('cardtype',$cardtype);
            $this->display();

        }
    }

    public function _after_insert($id){
        $user_je = M('member')->field('money,vipnum')->where('id = '.intval($id))->find();
        $data['money'] = trim($user_je['money']);
        $data['vipnum'] = trim($user_je['vipnum']);
        $data['mid'] = intval($id);
        $data['type'] = 1;
        $data['state'] = 1;  //支付成功
        $data['addtime'] = time(); //充值时间
        $data['managername'] = $this->weixininfo['username'];//操作者名字
        $data['managerid'] = $this->weixininfo['id'];////操作者ID
        M('member_money')->add($data);


        $user_jf = M('member')->field('integral,vipnum')->where('id = '.intval($id))->find();
        $map['money'] = trim($user_jf['integral']);
        $map['vipnum'] = trim($user_jf['vipnum']);
        $map['mid'] = intval($id);
        $map['type'] = 11;
        $map['state'] = 1;  //支付成功
        $map['addtime'] = time(); //充值时间
        $map['managername'] = $this->weixininfo['username']; //操作者名字
        $map['managerid'] = $this->weixininfo['id']; ////操作者ID
        M('member_money')->add($map);

    }


    public function check_vipnum(){
        $vipnum = I('post.vipnum','','intval');
        if(D('cardnumber')->where(array('cards'=>$vipnum))->select()){
            if (D('member')->where(array('vipnum'=>$vipnum))->find()) {
                echo 0;
            } else {
                echo 1;
            }
        }else{
            echo 0;
        }

    }

    public function check_tel(){
        if (D('member')->where("tel= ".$_POST['tel'].",vipnum != 0")->find()) {
            echo 0;
        } else {
            echo 1;
        }

    }

    //获取短信验证码
    public function getRegCode(){
        $mobile = $_POST['mobile'];
        $memeber = M('member')->where("tel=".$mobile." and vipnum != 0")->select();
        if($memeber){
            echo "该手机号已经注册过！";
        }else{
            $flag =  $this->sendSmsCode($mobile);
            if($flag == 100){
                echo $flag;
            }else{
                echo '短信发送失败，请稍后再试。'.$flag;
            }
        }
    }

    public function getForgetCode(){
        $mobile = $_POST['mobile'];
        $memeber = M('xf_consumer')->where("phone=".$mobile)->select();
        if($memeber){
            $flag =  $this->sendSmsCode($mobile);
            if($flag == 100){
                echo $flag;
            }else{
                echo '短信发送失败，请稍后再试。'.$flag;
            }
        }else{
            echo "该手机号码未注册！";
        }
    }

    public function checkRegCode(){
        $mobile = $_POST['mobile'];
        $vcode = $_POST['vcode'];

        $yz = M('regcode')->where("phone=".$mobile)->find();
        if(empty($yz)){
            echo '验证码不正确';
        }else{
            $m = $this->_dateDiff("n", $yz['datetime'],date('Y-m-d H:i:s'));
            if($m > 30){
                echo '验证码已过期，请重新获取';
            }elseif($vcode != $yz['code']){
                echo '验证码不正确';
            }else{
                echo '100';
            }
        }
    }

    public function sendSmsCode($phone){
        $code = rand(100000, 999999);
        $content = "您的验证码是：".$code."，30分钟内有效。如需帮助请联系客服。";
        $target = "http://sms.106jiekou.com/utf8/sms.aspx";
        $list = M('sms')->where("id=1")->find();
        $post_data = "account=".$list['username']."&password=".$list['password']."&mobile=".$phone."&content=".rawurlencode($content);

        $flag = $this->Post($post_data, $target);
        if($flag==100){
            M('regcode')->where('phone='.$phone)->delete();
            $regcode['phone']=$phone;
            $regcode['code'] = $code;
            $regcode['datetime'] = date('Y-m-d H:i:s');
            M('regcode')->data($regcode)->add();
        }
        return $flag;
    }

    function Post($curlPost,$url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
    }

    public static function _dateDiff($part, $begin, $end){
        $diff = strtotime($end) - strtotime($begin);
        switch($part){
            case "y": $retval = bcdiv($diff, (60 * 60 * 24 * 365)); break;
            case "m": $retval = bcdiv($diff, (60 * 60 * 24 * 30)); break;
            case "w": $retval = bcdiv($diff, (60 * 60 * 24 * 7)); break;
            case "d": $retval = bcdiv($diff, (60 * 60 * 24)); break;
            case "h": $retval = bcdiv($diff, (60 * 60)); break;
            case "n": $retval = bcdiv($diff, 60); break;
            case "s": $retval = $diff; break;
        }
        return $retval;
    }


    public function ajax_getcode(){
        $where['phone'] = $_POST['tel'];
        $where['code'] = $_POST['code'];
        if(M('regcode')->where($where)->find()){
            echo "填写正确";
        }else{
            echo "验证码有误";
        }

    }
    //生产8位随机数
    public function NoRand($begin=10,$end=99,$limit=8){
        $rand_array=range($begin,$end);
        shuffle($rand_array);
        $list = array_slice($rand_array,0,$limit);
        foreach ($list as $value) {
            $string .= $value;
        }
        return '88'.$string;
    }

    public function ajax_tuifei(){
        //dump($_POST);die;
        $shuliang = $_POST['gname'];
        $arr = $_POST;
        $vipnum = M('member')->where(array('id'=>$arr['mid']))->getField('vipnum');

        for($i=0;$i<count($shuliang);$i++){
            $aaa[$i]['gname'] = $arr['gname'][$i];
            $aaa[$i]['gnum'] = $arr['gnum'][$i];
            $aaa[$i]['danjia'] = $arr['price'][$i];
            $aaa[$i]['jifen'] = $arr['gnum'][$i]*$arr['price'][$i]*$arr['setting'];

        }
        $data = array();
        foreach($aaa as $k=>$v){
            $data['mid'] = $arr['mid'];
            $data['order_number'] = $arr['order_number'];
            $data['vipnum'] = $vipnum;
            $data['goodsname'] = $v['gname'];
            $data['goodsnum'] = $v['gnum'];
            $data['danjia'] = $v['danjia'];
            $data['jifen'] = $v['jifen'];
            $data['addtime'] = time();
            $iid = M('goodstuifei')->add($data);
        }
        $mjifen = M('goodstuifei')->field("sum(jifen) jf")->where(array('order_number'=>$arr['order_number']))->find();

        $map['mid'] = $arr['mid'];
        $map['type'] = 7;//购买商品退积分
        $map['money'] = $v['jifen'];
        $map['managerid'] = $this->weixininfo['id'];
        $map['managername'] = $this->weixininfo['username'];
        $map['state'] = 1;
        $map['addtime'] = time();
        $map['order_number'] = $arr['order_number'];
        $map['vipnum'] = $vipnum;
        M('member_money')->add($map);
        M('goods_order')->where(array('number'=>$arr['order_number']))->save(array('status'=>2));



        if(!empty($iid)){
            echo "退费成功";
        }else{
            echo "退费失败";
        }


        die;
    }


    public function tui(){
        $fhy_tf = M('member_money')->where("order_number = '".$_POST['order_number']."'")->find();

        if($fhy_tf['type'] == 9){
            $tf['mid'] = $fhy_tf['mid'];
            $tf['type'] = 19;
            $tf['money'] = -$fhy_tf['money'];
            $tf['managerid'] = $this->weixininfo['id'];
            $tf['managername'] = $this->weixininfo['username'];
            $tf['state'] = $fhy_tf['state'];
            $tf['addtime'] = time();
            $tf['order_number'] = $fhy_tf['order_number'];
            $tf['name'] = $fhy_tf['name'];
            $tf['tel'] = $fhy_tf['tel'];

            M('member_money')->add($tf);
            M('goods_order')->where("number = '".$_POST['order_number']."'")->save(array('status'=>2));
            $this->redirect('Member/fhy_tf','', 1, '退费成功 页面跳转中...');
        }else{
            $data['appid'] = appid_X;
            $data['mch_id'] = 1254760101;//商户号
            //$data['device_info'] = "1000";
            $data['nonce_str'] = $this->get_Rand(16);
            //$data['transaction_id'] = "1010040396201508310753258655";
            $data['out_trade_no'] = $_POST['out_trade_no'];///商户订单号  微信支付成功返回的out_trade_no
            $data['out_refund_no'] = $this->get_Rand(10); // 退款单号
            $data['total_fee'] = 1;//100 代表一元钱
//        $data['total_fee'] = $_POST['price'][0]*100;//100 代表一元钱
            $data['refund_fee'] = 1;//100  代表一元钱
//        $data['refund_fee'] = $_POST['jiner']*100;//100  代表一元钱
            $data['refund_fee_type'] = "CNY";
            $data['op_user_id'] = 1254760101;
            $data['sign'] = $this->sign($data);

            $arr = $this->curl_tixian($this->gen_xml($data));

            if($arr['msg'] == 'OK' && $arr['code'] =='SUCCESS' && $arr['transaction_id'] != ''){

                $map['transaction_id'] = $arr['transaction_id'];
                $map['status'] = 2;
                $info= M('goods_order')->where(array('out_trade_no'=>$_POST['out_trade_no']))->save($map);
                if(!empty($info)){
                    $url =U('Member/fhy_tf');
                    echo "<script>alert('退款成功');window.location.href='".$url."'</script>";

                }else{
                    echo "<script>alert('退费失败');</script>";
                }
            }
        }


    }
    public function gen_xml($params) {
        $xml = '<xml>';
        $fmt = '<%s><![CDATA[%s]]></%s>';
        foreach($params as $key=>$val){
            $xml.=sprintf($fmt, $key, $val, $key);
        }
        $xml.='</xml>';
        return $xml;
    }

    public function sign($params){
        ksort($params);

        $beSign = array_filter($params,'strlen');
        $pairs= array();

        foreach ($beSign as $k => $v) {
            $pairs[] = $k ."=".$v;
        }

        $sign_data = implode("&", $pairs);
        $sign_data.='&key=gd123456789gd123456789gd12345678';
        return strtoupper(md5($sign_data));
    }

    public function get_Rand($size){
        $c= "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        srand((double)microtime()*1000000);
        for($i=0; $i<$size; $i++) {
            $rand.= $c[rand()%strlen($c)];
        }
        return $rand;
    }

    function curl_tixian( $vars, $second=30,$aHeader=array())
    {
        $url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
        $ch = curl_init();
        //超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        //这里设置代理，如果有的话
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        //cert 与 key 分别属于两个.pem文件
        //请确保您的libcurl版本是否支持双向认证，版本高于7.20.1
        curl_setopt($ch,CURLOPT_SSLCERT,realpath("./").DIRECTORY_SEPARATOR.'api'.DIRECTORY_SEPARATOR.'wxqrpay'.DIRECTORY_SEPARATOR.'cert'.DIRECTORY_SEPARATOR.'apiclient_cert.pem');
        curl_setopt($ch,CURLOPT_SSLKEY,realpath("./").DIRECTORY_SEPARATOR.'api'.DIRECTORY_SEPARATOR.'wxqrpay'.DIRECTORY_SEPARATOR.'cert'.DIRECTORY_SEPARATOR.'apiclient_key.pem');
        curl_setopt($ch,CURLOPT_CAINFO,realpath("./").DIRECTORY_SEPARATOR.'api'.DIRECTORY_SEPARATOR.'wxqrpay'.DIRECTORY_SEPARATOR.'cert'.DIRECTORY_SEPARATOR.'rootca.pem');
        if( count($aHeader) >= 1 ){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }
        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);

        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Errno:'.curl_error($ch);
        }
        curl_close($ch);
        $j = simplexml_load_string($tmpInfo);
//        p((string)$j->return_msg); // 退款成功 正确返回 string(2) "OK"
//        p((string)$j->return_code); // 退款成功 正确返回 string(7) "SUCCESS"
//        p((string)$j->err_code); // 退款成功 正确返回 string(0) ""
//        p((string)$j->err_code_des); // 退款成功 正确返回 string(0) ""
//        p((string)$j->transaction_id); // 退款成功 正确返回  string(28) "1010040396201508310755684157"

        $arr['msg'] = (string)$j->return_msg;
        $arr['code'] = (string)$j->return_code;
        $arr['transaction_id'] = (string)$j->transaction_id;

        return $arr;

        exit;
    }


    public function logout_hy(){
        $where['id'] = $this->weixininfo['id'];
        M('admin')->where($where)->save(array('openid'=>''));

        $this->redirect(U('home/Index/index'));
    }





    //退出账号
//    public function logout() {
//        $this->visitor->logout();
//        //同步退出
//        $passport = $this->_user_server();
//        $synlogout = $passport->synlogout();
//        //跳转到退出前页面（执行同步操作）
//        $this->redirect(U('User/login'));
//    }




}