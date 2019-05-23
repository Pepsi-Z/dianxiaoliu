<?php
class Manage_siteAction extends frontendAction
{
    public function _initialize() {
        parent::_initialize();
    }

   public function index(){

       $this->display();
   }

    //非会员列表
    public function nmember_site(){
        $list_openid = M('member')->where(array('id' =>I('get.id','','intval')))->field('openid')->find();
       //退积分  返还积分奖励
        $join = array(
            'a left join __MEMBER__ b on a.uid = b.id',
            'left join __SITE__ c on a.site_id = c.id',
            'left join __CLUB__ d on a.club_id = d.id'
        );
        $openid = $list_openid['openid']; //openid
        $field = 'a.*,b.vipnum,b.name as bname,b.tel as btel,c.site_name,c.tqqx_fhy,c.tqqx_hy,d.club_name';
        $list =  M('ordersite')->join($join)->field($field)->where("a.openid = '".$openid."' and a.vipnum is null and a.order_status=1")->order('start_month desc')->select();
            //非会员过期时间
        foreach($list as $key=>$val){
            $month = $val['start_month']; //开始时间
            $time = $val['start_time'];
            $times = $month.' '.$time.':00:00';
            if(strtotime($times) > strtotime("now") ){
                $list[$key]['xianshi'] = '没有过期';
            }
        }

        $this->assign('list',$list);
        $this->display();

    }
    //判断是否取消信息
    public function alert_judge(){
       $data = M('ordersite')->where(array('id'=>$_POST['id']))->field('start_month,start_time')->find();
       echo json_encode($data);die;
    }


    //确定修改状态
    public function alert_confirm(){
        $data['status'] = 2; //1表示取消
        $onum = M('ordersite')->where("id =".intval($_POST['id']))->getField('order_number');
        $m_list = M('member_money')->where("order_number = '".$onum."'")->select();

        foreach($m_list as $val){
            if($val['type'] == 14){
                $tf['type'] = 17;
                $tf['money'] = -$val['money'];
            }else if($val['type'] == 13){
                $tf['type'] = 18;
                $tf['money'] = -$val['money'];
            }else{
                $tf['type'] = 16;
                $tf['money'] = -$val['money'];
            }

            $tf['mid'] = $val['mid'];
            $tf['managerid'] = $this->userinfo['id'];
            $tf['managername'] = $this->userinfo['name'];
            $tf['state'] = $val['state'];
            $tf['addtime'] = time();
            $tf['order_number'] = $val['order_number'];
            $tf['vipnum'] = $val['vipnum'];
            M('member_money')->add($tf);
        }
        M('ordersite')->where("order_number = '".$val['order_number']."'")->save(array('status'=>2));
        echo 1;

    }


    public function tui(){
        $info = M('ordersite')->where("id=".intval($_POST['id']))->find();
        //dump($info);
        $data['appid'] = appid_X;
        $data['mch_id'] = 1254760101;//商户号
        //$data['device_info'] = "1000";
        $data['nonce_str'] = $this->get_Rand(16);
        //$data['transaction_id'] = "1010040396201508310753258655";
        $data['out_trade_no'] = $info['out_trade_no'];///商户订单号  微信支付成功返回的out_trade_no
        $data['out_refund_no'] = $this->get_Rand(10); // 退款单号
        //$data['total_fee'] = $info['site_price']*100;//100 代表一元钱
        $data['total_fee'] = 1;//100 代表一元钱
        /*     $data['refund_fee'] = $info['site_price']*100;//100  代表一元钱 */
		$data['refund_fee'] = 1;//100  代表一元钱

        $data['refund_fee_type'] = "CNY";
        $data['op_user_id'] = 1254760101;
        $data['sign'] = $this->sign($data);

        $arr = $this->curl_tixian($this->gen_xml($data));
         if($arr['msg'] == 'OK' && $arr['code'] =='SUCCESS' && $arr['transaction_id'] != ''){

            $map['transaction_id'] = $arr['transaction_id'];
            $map['status'] = 2;
            $res= M('ordersite')->where(array('out_trade_no'=>$info['out_trade_no']))->save($map);
            if(!empty($res)){

                if (IS_AJAX) {
                    echo "1";
                } else {
                    $url =U('Manage_site/nmember_site');
                    echo "<script>alert('退款成功');window.location.href='".$url."'</script>";
                }

               // $this->redirect('Manage_site/nmember_site');
            }else{
                if (IS_AJAX) {
                    echo "2";
                } else {
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



    //订场详情
    public function site_list(){
        $join = array('a left join __SITE__ b ON a.site_id = b.id',
                      'left join __CLUB__ c ON a.club_id = c.id'
            );
        $field = 'a.*,b.site_name,c.club_name';
        $site_info = M('ordersite')->join($join)->field($field)->where(array('a.id'=>$_GET['id']))->find();
        $this->assign('week_arr', array('星期日','星期一','星期二','星期三','星期四','星期五','星期六'));
        $this->assign('site_info',$site_info);
        $this->display();

    }

    //会员列表
    public function member_site(){
        //退积分  返还积分奖励
       $join = array(
            'a left join __MEMBER__ b on a.uid = b.id',
            'left join __SITE__ c on a.site_id = c.id',
            'left join __CLUB__ d on a.club_id = d.id'
        );
        $uid = I('get.id','','intval');
        $field = 'a.*,b.vipnum,b.name as bname,b.tel as btel,c.site_name,c.tqqx_fhy,c.tqqx_hy,d.club_name,d.offtime';
        $list =  M('ordersite')->join($join)->field($field)->where(array('uid' =>$uid,'order_status'=>1))->group('order_number,club_id')->order('start_month desc')->select();
        foreach($list as $key=>$val){
            $month = $val['start_month']; //开始时间
            $time = $val['start_time'];
            $times = $month.' '.$time.':00:00';
            if(strtotime($times) > strtotime("now") ){
                $list[$key]['xianshi'] = '没有过期';
            }
        }

        $this->assign('list',$list);
        $this->display();

    }
    //长期订场列表
    public function longsite_list(){
        $join = array(
            'a left join __MEMBER__ b on a.uid = b.id',
            'left join __SITE__ c on a.site_id = c.id',
            'left join __CLUB__ d on a.club_id = d.id'
        );

        $order_number = I('get.order_number','','trim');
        $field = 'a.*,b.vipnum,b.name as bname,c.site_name,d.club_name,d.offtime';
        $list =  M('ordersite')->join($join)->field($field)->where(array('a.order_number' =>$order_number))->select();

        $use_list =  M('ordersite')->join($join)->field($field)->where("a.order_number = {$order_number} ")->select();
        $dele_list =  M('ordersite')->join($join)->field($field)->where("a.order_number = {$order_number} and a.status in (1,2)")->select();


        $all_price = '';
        foreach($list as $key=>$val){

            $month[] = $val['start_month'];
            $all_price += $val['site_price'];

        }
        $now_month = time();
        foreach($use_list as $k=>$v){
            $old_time = strtotime($v['start_month'].' '.$v['start_time'].':00:00');
            if($old_time <= $now_month){
                $use_lists[] = $v;
            }

        }
        $this->assign('list',$list);
        $this->assign('num',count($list));  //周数
        $this->assign('start_mon',min($month));  //开始周
        $this->assign('end_mon',max($month));  //结束周
        $this->assign('all_price',$all_price);  //总价钱
        $this->assign('week_arr', array('星期日','星期一','星期二','星期三','星期四','星期五','星期六'));
        $this->assign('user_lists',$use_lists);  //去除数组重复数据
        $this->assign('dele_list',$dele_list);  //总价钱
        $this->assign('zhou',(count($list)-count($use_lists)));  //剩余周数  应该还得减去 后台取消的周数
        $this->display();
    }


}


