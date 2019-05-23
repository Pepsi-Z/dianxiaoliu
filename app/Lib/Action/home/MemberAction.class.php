<?php
header("Content-type: text/html; charset=utf-8");
include './api/phpqrcode/phpqrcode.php';
class memberAction extends  frontendAction {
    public function _initialize(){
        parent::_initialize();
        $this->_mod = D('user');
        //$this->user = $_SESSION['user']


    }
    //我要激活
    public function activation_member() {
        $mod = D('user');
        if (IS_POST) {
            if(false === $data = $mod->create()) {
                IS_AJAX && $this->ajaxReturn(0, $mod->getError());
                $this->error($mod->getError());
            }
            $join= array('a left join __CARDTYPE__ b ON a.cid = b.id');
            $filed='a.cid,b.gname,b.money,b.begin_time,b.end_time';
            $info = M('cardnumber')->join($join)->field($filed)->where("cards= ".$_POST['card_number'])->find();
            $data['status'] = 1;
            $data['cardtype'] = $info['cid'];  //卡的id
            $data['cardname'] = $info['gname'];  //卡的name

            if(!empty($data['cardtype'])){
                $card_name = M('cardtype')->where(array('id'=>$data['cardtype']))->getField('gname');
                if($card_name == '半年卡'){
                    $data['begin_time'] = date("Y-m-d",time());
                    $data['end_time'] = date("Y-m-d", strtotime("+6 months", strtotime($data['begin_time'])));
                }elseif($card_name == '一年卡'){
                    $data['begin_time'] = date("Y-m-d",time());
                    $data['end_time'] = date("Y-m-d", strtotime("+12 months", strtotime($data['begin_time'])));
                }else{

                }
            }
            $data['money'] = $info['money']; //卡的价钱
            //$data['openid'] = $this->openid;//openid
            if($mod->add($data)){
                $id = $mod->getLastInsID();
                //生成二维码
                $qr_code['qr_code']  = $this->set_order_code($id);
                M('user')->where(array('id' =>$id))->save($qr_code);

                //会员卡号已经生成 修改会员卡里的状态
                $state['state'] = 1;
                M('cardnumber')->where(array('cards' =>$data['card_number']))->save($state);

                $url = U('Member/login');
                echo "<script>alert('激活成功');window.location.href='".$url."'</script>";
            } else {
                $url = U('Member/activation_member');
                echo "<script>alert('注册失败');window.location.href='".$url."'</script>";
            }
        } else {
            $this->display();

        }
    }

    //更加激活码获取卡号
    public function activation_info(){

        $card_number = D('cardnumber')->where("line = 1 and code= ".$_POST['cards'])->find();
        if($card_number){
            if(M('user')->where(array('card_number' =>$card_number['cards']))->find()){
                $info['status'] = 1;
            }else{
                $info['status'] = 2;
                $info['data'] = $card_number['cards'];
            }
        }else{
            $info['status'] = 0;
        }
        echo json_encode($info);


    }


    //我要办卡
    public function add_member() {
        $mod = D('user');
        if (IS_POST) {
            if(false === $data = $mod->create()) {
                IS_AJAX && $this->ajaxReturn(0, $mod->getError());
                $this->error($mod->getError());
             }
                $data['status'] = 1;
                //线上 line=0
                $card_list = M('cardnumber')->where(array('line' =>0,'state' =>0))->field('cards')->select();
                foreach($card_list as $ke=>$va){
                    $arr[] = $va['cards'];
                }
                $arr_card =  array_rand($arr);
                $data['card_number'] = $arr[$arr_card];
                //$data['openid'] = $this->openid;
                $data['status'] = 1;
                if(false !== $mod->add($data) ){
                    $id = $mod->getLastInsID();
                    $user = M('user')->where(array('id'=>$id))->find();
                    //生成二维码
                    $qr_code['qr_code']  = $this->set_order_code($id);
                    $qr_code['status']  = 1;
                    M('user')->where(array('id' =>$id))->save($qr_code);
                    //会员卡号已经生成 修改会员卡里的状态
                    $state['state'] = 1;
                    M('cardnumber')->where(array('cards' =>$user['card_number']))->save($state);
                    $url = U('Member/login');
                    echo "<script>alert('注册成功,请去登陆');window.location.href='".$url."'</script>";
                }else{
                    $url = U('Member/add_member');
                    echo "<script>alert('注册失败');window.location.href='".$url."'</script>";
                }


        } else {
            $cardtype = M('cardtype')->where(array('status' =>1))->select();
            $this->assign('cardtype',$cardtype);
            $this->display();

        }
    }


    public function addmember_call_back(){
        $mod = D('user');
        $id = $_GET['id'];  //用户id
        if(false !== $user = M('user')->where(array('id'=>$id))->find()){
            //生成二维码
            $qr_code['qr_code']  = $this->set_order_code($id);
            $qr_code['status']  = 1;
            M('user')->where(array('id' =>$id))->save($qr_code);
            //会员卡号已经生成 修改会员卡里的状态
            $state['state'] = 1;
            M('cardnumber')->where(array('cards' =>$user['card_number']))->save($state);
            $url = U('Member/card_number',array('card'=>$user['card_number']));
            echo "<script>alert('会员卡号已经生成');window.location.href='".$url."'</script>";
        } else {
            $url = U('Member/add_member');
            echo "<script>alert('注册失败');window.location.href='".$url."'</script>";
        }

    }



    public function card_number(){

        $_get = $_GET['card'];
        $this->assign('card_number',$_get);
        $this->display();
    }

    public function set_code ($content){
        $filename = time().rand(0, 128).'.png';
        $dir = "./data/code/";
        $url = $dir.$filename;
        QRcode::png($content, $url);
        return $url;
    }

    //生成二维码
    public function set_order_code($id){
        $content = XF_HTTP."index.php?m=Shop&a=comment&id=".$id;
        $url = $this->set_code($content);
        return $url;
    }



    //获取会员卡信息
    public function take_card(){
        $cardtype = D('cardtype')->where("id= ".$_POST['id'])->find();
        echo json_encode($cardtype);
    }

    //梁浩修改
    public function login(){
        $user = M('user')->where(array('openid'=>$this->openid))->find();
        if (!empty($user)) {
            $url = U('Index/index');
            echo "<script>window.location.href='".$url."'</script>";
        }
        if(IS_POST) {
            $this->_mod = M('user');
            $where['tel'] = $_POST['tel'];
            $where['password'] = md5($_POST['pass']);
            $user = $this->_mod->where($where)->find();
            if (!empty($user)) {
                $map['openid'] = $this->openid;//openid
                $this->_mod->where('id=' . intval($user['id']))->save($map);
                $_SESSION['user'] = $user;
                $data['status'] = 1;
                $data['data'] =$user;
                echo json_encode($data);
            }else{
                $data['status'] = 0;
                $_SESSION['user'] = '';
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
        if(D('user')->where("tel= ".$_POST['tel'])->save($data)){
            $url = U('Member/login');
            echo "<script>alert('修改密码成功');window.location.href='".$url."'</script>";
        } else {
            $url = U('Member/login');
            echo "<script>alert('修改密码失败');window.location.href='".$url."'</script>";
        }
    }


    public function check_tel(){

        if (D('user')->where("tel= ".$_POST['tel'])->find()) {
            echo 0;
        } else {
            echo 1;
        }

    }

    public function pass_check_tel(){
        $tel = D('user')->where("status = 1 and tel= ".$_POST['tel'])->find();
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
        $memeber = M('user')->where("status = 1 and tel=".$mobile)->select();
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







    //退出账号
//    public function logout() {
//        $this->visitor->logout();
//        //同步退出
//        $passport = $this->_user_server();
//        $synlogout = $passport->synlogout();
//        //跳转到退出前页面（执行同步操作）
//        $this->redirect(U('User/login'));
//    }


    public function get_Rand(){
        $c= "abcdefghijklmnopqrstuvwxyz0123456789";
        srand((double)microtime()*1000000);
        for($i=0; $i<6; $i++) {
            $rand.= $c[rand()%strlen($c)];
        }
        return $rand;
    }

    //店小六协议
    public function role(){
        $this->display();
    }





}