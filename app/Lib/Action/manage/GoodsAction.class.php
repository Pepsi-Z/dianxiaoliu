<?php
class GoodsAction extends  membersAction {
	public function _initialize(){
        parent::_initialize();
        $this->_mod = D('goods');

//        if(empty($this->weixininfo['id'])){
//            $this->redirect('Member/login');
//
//        }
    }  
	 public function index(){
         $fenlei = M('goodstype')->field('id,typename')->select();
         $this->assign('fenlei',$fenlei);
         $this->display();
	 }
    public function search_child(){
        $cate_id = $_GET['cate_id'];
        $list = M('goods')->where("typeid = ".$cate_id)->select();
        echo json_encode($list);
    }
    public function goods_add(){
        $num = sizeof($_POST['typename']);
        $order_number = $this->NoRand();
        $kahao = I('post.hykahao');
        $xingming = I('post.xingming');
        $dianhua = I('post.dianhua');


        $kahao == '会员卡号' ? $kahao='':$kahao;
        $xingming == '姓名' ? $xingming='':$xingming;
        $dianhua == '手机号码' ? $dianhua='':$dianhua;
        if(M('goods_order')->where(array('number'=>$order_number))->find()){
            $order_number = $this->NoRand();
        }
        for($i=0;$i<$num;$i++){
            $aa[$i]['typeid'] = $_POST['typename'][$i];
            $aa[$i]['num'] = $_POST['shuliang'][$i];
            $aa[$i]['goodsid'] = $_POST['chanpin'][$i];
            $aa[$i]['price'] = $_POST['danjia'][$i];
            $aa[$i]['number'] = $order_number;
            $aa[$i]['kahao'] = $kahao;
            $aa[$i]['xingming'] = $xingming;
            $aa[$i]['dianhua'] = $dianhua;
            $aa[$i]['addtime'] = time();
            $aa[$i]['all'] = $_POST['danjia'][$i]*$_POST['shuliang'][$i];

        }
        $all_price = 0;
        foreach($aa as $v){
            $all_price += $v['all'];
            if($v['price'] != ''){
                M('goods_order')->add($v);
            }
        }
        //dump($all_price);die;

        //这里不需要往金额表里写  没有付款类型
//        $map['money'] = $all_price;
//        $map['mid'] = M('member')->where('vipnum='.intval($kahao))->getField('id');
//        $map['money'] = $all_price;
//        $map['money'] = $all_price;
//
//        dump($all_price);
//        die;
        $info = M('goods_order')->where('number ='.$order_number)->field('sum(price) price')->find();

        $bili =  M('setting')->where('id=1')->getField('integral');
        $this->assign('bili',$bili);


        if(!empty($kahao)){
            $id = M('member')->where("vipnum='".$kahao."'")->getField('id');
        }else{
            $user['name'] = $xingming;
            $user['tel'] = $dianhua;
            $id = M('member')->where($user)->getField('id');
        }
        $mom =  M('member_money')->field('sum(money) mom')->where('state = 1 and type in (1,2,3,12,14,17,22) and mid='.intval
            ($id))->find();//余额
        $jf =  M('member_money')->field('sum(money) jf')->where('state = 1 and type in (4,6,7,8,10,11,13,16,18) and mid='
            .intval($id))
            ->find();//积分


        $_SESSION['goods_order_number'] = $order_number;
        $this->assign('hy_id',$id);
        $this->assign('vipnum',$kahao);
        $this->assign('xingming',$xingming);
        $this->assign('dianhua',$dianhua);
        $this->assign('info',$info);
        $this->assign('hy_mom',$mom);
        $this->assign('hy_jf',$jf);

//
//        dump($kahao);
//        if(empty($kahao)){
//            $member_info = M('member')->where("vipnum ='".$kahao."''")->find();
//        }else{
//            $member_info = M('member')->where("vipnum ='".$kahao."'")->find();
//        }

        $this->assign('price',$all_price);
        $this->display();

    }

    public function search_goods_price(){

        $goods_id = $_GET['goods_id'];
        $info = M('goods')->where('id='.$goods_id)->find();
        echo json_encode($info);
    }

    public function check_kh(){
        $kh = I('post.kh');
        $info = M('member')->where('vipnum ='.intval($kh))->find();
        echo json_encode($info);
    }




    public function pay_sh(){
        $data = array();
        $member_money = M('member_money');
        $tijiao_jf = I('post.tijiao_jf');
        $tijiao_je = I('post.tijiao_je');
        $tijiao_wx = I('post.tijiao_wx');
        $tijiao_xj = I('post.tijiao_xj');
        $vipnum = I('post.vipnum');

        $name = I('post.name');
        $tel = I('post.tel');
        $hyid = I('post.hyid');
        $vipnum = I('post.vipnum');

        //$data['hy_num'] = I('post.hy_order_number');
        $data['mid'] = $hyid;
        $data['managerid'] = $this->weixininfo['id'];
        $data['managername'] = $this->weixininfo['username'];
        $data['vipnum'] = $vipnum;
        $data['order_number'] = $_POST['goods_order_number'];
        $data['addtime'] = time();
        $data['name'] = $name;
        $data['tel'] = $tel;

        if(!empty($tijiao_jf)){
            $data['money'] = -$tijiao_jf;
            $data['state'] = '1';
            $data['type'] = '4';//积分消费 购买商品
            $member_money->add($data);
        }

        if(!empty($tijiao_je)){
            $data['money'] = -$tijiao_je;
            $data['state'] = '1';
            $data['type'] = '3';//余额消费 购买商品
            $member_money->add($data);
        }

        if(!empty($tijiao_xj)){
            $data['money'] = -$tijiao_xj;
            $data['state'] = '1';
            $data['type'] = '9';//余额消费 购买商品
            $member_money->add($data);
        }

        if(!empty($tijiao_wx)){
            $data['money'] = -$tijiao_wx;
            $data['state'] = '0';
            $data['type'] = '5';//积分消费 购买商品
            $member_money->add($data);
        }

        if(!empty($tijiao_wx)){
            $body = "二维码支付";

            $trade_no =  $data['order_number'];
//            $Total_fee = $data['order_number'];
            $xfurl = XF_HTTP;
            $Total_fee = $tijiao_wx;
            $Total_fee = 0.01;

            $param1 = "body=".$body."&trade_no=".$trade_no."&Total_fee=".$Total_fee."&xfurl=".$xfurl."&id=".$order_id;
            echo "<script>location.href='api/wxqrpay/native.php?".$param1."'</script>";
//            $this->redirect('Member/erweima',array(''=>$_POST['tijiao_wx']));
        }else{

			$www['number'] = $_POST['goods_order_number'];
			$ww['order_number'] = $_POST['goods_order_number'];
			M('goods_order')->where($www)->save(array('status'=>'1'));
			M('member_money')->where($ww)->save(array('state'=>'1'));
            if($vipnum != ''){
                $this->redirect('Member/hy_xf','',1,'支付成功');
            }else{
                $this->redirect('Member/fhy_xf','',1,'支付成功');
            }
			
//			$this->redirect('home/Index/succ');
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
        }else if($jf == null && $ye == null){


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

    //生产8位随机数
    public function NoRand($begin=10,$end=99,$limit=3){
        $rand_array=range($begin,$end);
        shuffle($rand_array);
        $list = array_slice($rand_array,0,$limit);
        foreach ($list as $value) {
            $string .= $value;
        }
        return date('ymd',time()).$string;
    }


}