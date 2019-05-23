<?php
header("Content-type: text/html; charset=utf-8");
class ShopAction extends frontendAction {
    public function _initialize() {
        parent::_initialize();

    }
	// 微官网
    public function index() {
        $this->display();
    }
	// 积分商城
    public function score_shop() {
        $this->display();
    }
    
	// 个人中心
    public function user_shop() {
        $this->display();
    }
    public function comment(){
        $this->get_manage_info();
        $id = $_GET['id'];
        $user = M('user')->where(array('id'=>$id))->field('id,name,card_number')->find();
        $this->assign('user',$user);
        $this->display();

    }

    public function m_login(){
        $user = M('user_merchant')->where(array('openid'=>$this->openid))->find();
        if (!empty($user)) {
            $url = U('Shop/merchant_order');
            echo "<script>window.location.href='".$url."'</script>";
        }
        $this->display();
    }

    public function fan_score(){
        $this->get_manage_info();
        $uid = $_POST['uid'];  //用户id
        //用户
        $user_info = M('user')->where(array('id'=>$uid))->field('name,card_number,score')->find();
        $sid = $_SESSION['manage']['mid']; //商家id
        //商家
        $merchant =  M('merchant')->where(array('id'=>$_SESSION['manage']['mid']))->field('id,rid,title,enjoy_num_1')->find();
        //返利规则
        $rule = M('rule')->where(array('id'=>$merchant['rid']))->find();
        $money = $_POST['money'];  //用户的消费金额]


        $data['uid'] = $uid;
        $data['username'] = $user_info['name'];
        $data['card_number'] = $user_info['card_number'];
        $data['action'] = $merchant['title'].'商家返积分';
        $data['add_time'] = time();
        $data['sname'] = $merchant['title']; //商家名称
        $data['fid'] =  $merchant['rid']; //返利id
        $data['sid'] = $sid; //商家id
        $data['money'] = $money; //用户的消费金额
        $data['score'] = (($rule['price']*$money)/$rule['score']); //根据用户消费金额对应生成的积分
        M('return_integral')->add($data);  //返利表

        $score_data['uid'] = $uid;  //用户ID
        $score_data['uname'] = $user_info['name']; //用户名字
        $score_data['action'] = $merchant['title'].'商家返积分';
        $score_data['score'] = (($rule['price']*$money)/$rule['score']); //得到的积分
        $score_data['add_time'] = time();
        $score_data['total'] = $user_info['score']+(($rule['price']*$money)/$rule['score']);  //总积分
        M('score_log')->add($score_data);  //返积分记录
        //修改用户积分
        $user_data['score'] = $user_info['score']+(($rule['price']*$money)/$rule['score']);  //总积分
         //返积分记录
       if(false !== M('user')->where(array('id'=>$uid))->save($user_data)){
           $mdata['enjoy_num_1'] = $merchant['enjoy_num_1'] + 1;
           M('merchant')->where(array('id'=>$_SESSION['manage']['mid']))->save($mdata);
           $url = U('Shop/merchant_order');
           echo "<script>alert('返用户积分成功');window.location.href='".$url."'</script>";
       }

    }


    //梁浩修改
    public function login(){
        if(IS_POST) {
            $this->_mod = M('user_merchant');
            $where['tel'] = $_POST['tel'];
            $where['password'] = md5($_POST['pass']);
            $user = $this->_mod->where($where)->find();
            if (!empty($user)) {
                $map['openid'] = $this->openid;//openid
                $map['weixin_name'] = M('wechatuser')->where(array('openid'=>$this->openid))->getField('nickname');
                $map['cover']       = M('wechatuser')->where(array('openid'=>$this->openid))->getField('headimgurl');
                $this->_mod->where('id=' . intval($user['id']))->save($map);
                $_SESSION['manage'] = $user;
                $data['status'] = 1;
                $data['data'] =$user;
                echo json_encode($data);
            }else{
                $data['status'] = 0;
                $_SESSION['manage'] = '';
                echo json_encode($data);
            }
            die;
        }
        $this->display();
    }


    /**
     * 修改密码
     */
    public function password() {

        $this->display();
    }

    public function edit_password(){

        $tel= $_POST['tel'];
        $this->assign('tel',$tel);
        $this->display();
    }
    //修改密码
    public function edit_pass(){

        $data['password'] = md5($_POST['password']);
        if(D('user_merchant')->where("tel= ".$_POST['tel'])->save($data)){
            $url = U('Shop/m_login');
            echo "<script>alert('修改密码成功');window.location.href='".$url."'</script>";
        } else {
            $url = U('Shop/m_login');
            echo "<script>alert('修改密码失败');window.location.href='".$url."'</script>";
        }
    }


    public function check_tel(){

        if (D('user_merchant')->where("tel= ".$_POST['tel'])->find()) {
            echo 0;
        } else {
            echo 1;
        }

    }

    public function pass_check_tel(){
        $tel = D('user_merchant')->where("tel= ".$_POST['tel'])->find();
        if ($tel) {
            //如果查到用户名 可以修改
            echo 1;
        } else {
            //如果没有查到用户名 不可以修改
            echo 2;
        }

    }

    //获取短信验证码
    public function getRegCode(){
        $mobile = $_POST['mobile'];
        $memeber = M('user_merchant')->where("tel=".$mobile)->select();
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

    //获取短信验证码
    public function getRegCodes(){
        $mobile = $_POST['mobile'];
        $flag =  $this->sendSmsCode($mobile);
        if($flag == 100){
            echo $flag;
        }else {
            echo '短信发送失败，请稍后再试。' . $flag;
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
        $code = rand(100000, 999999); //随机数
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

    //商家折扣订单
    public function merchant_order(){
        $this->get_manage_info();
        $merchant_id =  $this->merchant['mid']; //商家id
        $integral_list = M('return_integral')->where(array('sid' =>$merchant_id))->order('id desc')->select();
        $this->assign('integral_list',$integral_list);
        $this->display();
    }


    //商家团购订单
    public function order_tuan(){
        $this->get_manage_info();
        $keyword = $this->_request('keyword');
        $merchant_id  = $this->merchant['mid'];
        $where = " `delete` = 0 and `type` = 0 and merchant_id = ".intval($merchant_id);
        $status = $this->_request('status','trim');
        if(empty($status)){
            $status = 1;
        }
        if($status){
            $where .=" and status = ".$status;
        }
        if($keyword){
            $where .=" and orderId = ".$keyword;
        }
        $order = M('item_order')->where($where)->order('status asc,id desc')->select();
//    	echo M('item_order')->getLastSql();
        foreach ($order as $k=>$v){
            $order[$k]['detail'] = M('order_detail')->where("orderId = '".$v['orderId']."'")->select();
        }
        $this->assign('order',$order);
        $this->assign('status',$status);
        $this->assign('search', array(
            'keyword' => $keyword,
        ));

        $this->display();
    }


    //删除订单
    public function delete(){
        $id = $_POST['id'];
        $orderId = M('item_order')->where('id = '.intval($id))->getField('orderId');
        $m = M('item_order')->where('id = '.intval($id))->setField('delete',1);
        $m2 = M('order_detail')->where(array('orderId'=>$orderId))->setField('delete',1);
        if($m){
            echo 1;
        }else{
            echo 0;
        }

    }

    //订单详情
    public function order_xx_wu(){
        $oid =  $this->_get('id','intval');
        $order = M('item_order')->where('id = '.$oid)->find();
        $join = array(
            'a left join __ITEM__ b on a.itemId = b.id',
            'left join __MERCHANT__ c on b.sid = c.id'
        );
        $field= 'a.*,b.sid,c.title as ctitle,c.id as cid';
        $order_info = M('order_detail')->field($field)->where("a.orderId = '".$order['orderId']."'")->join($join)->select();

        $num = 0 ;
        $p_price = 0;
        $arr = array();
        foreach ($order_info as $k=>$v){
            $order_info[$k]['all'] = $v['price']*$v['quantity'];
            $num = $num+$v['quantity'];
            $p_price += $v['price']*$v['quantity'];
            $arr[$v['cid']][] = $v;
        }

        $this->assign('order',$order);
        $this->assign('list',$order_info);
        $this->assign('arr',$arr);
        $this->assign('p_price',$p_price);
        $this->assign('num',$num);
        $this->display();
    }

    //检测验证码
    public function check_code(){
            $this->_mod = M('item_order');
            $code = $this->_post('code','intval'); //验证码
            $orderId = $this->_post('orderId'); //订单编号
            $status  = $this->_post('status','intval'); //类型
            $id = $this->_post('id','intval'); //订单id
            $where['order_code'] = $code;
            $where['orderId'] = $orderId;
            $order_item = $this->_mod->where($where)->find();
            if (!empty($order_item)) {
                $this->_mod->where($where)->setField('status',4);
                $url = U('Shop/order_tuan',array('status'=>$status));
                echo "<script>alert('验证成功');window.location.href='".$url."'</script>";
            }else{
                $url = U('Shop/order_xx_wu',array('id'=>$id,'status'=>$status));
                echo "<script>alert('验证失败');window.location.href='".$url."'</script>";
            }
    }





}