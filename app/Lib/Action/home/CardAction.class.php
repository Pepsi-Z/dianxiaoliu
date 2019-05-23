<?php
header("Content-type: text/html; charset=utf-8");
class CardAction extends frontendAction {
	public function _initialize() {
		parent::_initialize();
		$this->get_user_info(1);
	}
   //
    public function index() {
        if(empty($_SESSION['user'])){
            $this->redirect(U('Member/login'));
        }
        $mod = M('article');
        $count = $mod->where(' status = 1')->count();
        $pager = new Page($count, 20);
        $list = $mod->where('status = 1')->limit($pager->firstRow.','.$pager->listRows)->select();
        $this->assign('list',$list);
        $num = M('item')->where('end_time>'.time())->count();
        $this->assign('num',$num);
        $this->assign('page',$pager->show());
        $this->display();
    }
    public function hychongzhi(){
    	$uid = $this->user['id'];
    	if(IS_POST){
    		$mod = M('yue_log');
    		$data['add_time'] = time();
    		$data['money'] = $_POST['money'];
    		$data['type']  = 1;
    		$data['uid']   = $uid;
    		$m = $mod->add($data);
    		$id = $mod->getLastInsID();
    		if($m){
				//微信支付
				$body = "会员余额支付";//支付名称
				$Total_fee = $data['money'];
				$xfurl = XF_HTTP;
				$type = 2;
				//$Total_fee = 0.01;
	
				$param1 = "body=".$body."&Total_fee=".$Total_fee."&xfurl=".$xfurl.'&type='.$type.'&yuerid='.$id;
				
				echo "<script>location.href='api/wxqrpay/unifiedorder.php?".$param1."'</script>";
    		}
    	}else{
    		$this->display();
    	}
        
    }

    public function tixian(){
        if(IS_POST){
            $data['addtime'] = time();
            $data['money'] = $_POST['money'];
            $data['bank'] = $_POST['bank'];
            $data['bank_num'] = $_POST['bank_num'];
            $data['bank_name'] = $_POST['bank_name'];
            $data['bank_address'] = $_POST['bank_address'];
            $data['uid']   =   $this->user['id'];
            $data['uname']   = $this->user['username'];
            $data['utel']   = $this->user['tel'];
            $dingdanhao = date("YmdHis");  //订单编号
            $dingdanhao .= rand(100, 999);//订单编号 日期加随机数
            $url = U('Card/index');
            $data['order_number']=$dingdanhao;//订单号
            if(false != M('member_money')->add($data)){
                $da['money'] = ($this->user['money'] - $_POST['money']);
                M('user')->where('id = '.$this->user['id'])->save($da);//修改用户余额
                echo "<script>alert('已经提交后台审核！');window.location.href='".$url."'</script>";
            }else{
                echo "<script>alert('已经提交后台审核失败');window.location.href='".$url."'</script>";

            }
        }else{
            $this->assign('money',$this->user['money']);
            $this->display();
        }

    }

	public function call_back(){


        $uid = $this->user['id'];
        $money = $_POST['Total_fee'];

        $uu = M('user')->where('id = '.$uid)->find();
        $yuerid = $_POST['yuerid'];
        $arr['money'] = $uu['money'] +$money;
        $ye['status'] = 1;
        $ye['total'] = $arr['money'];

        //给用户积分
        $array['uid'] = $uid;
        $array['uname'] = $this->user['username'];
        $array['action'] = '充值';
        $consume = C('pin_reward');  //消费送积分
        $array['score'] = intval($consume * $money);
        $array['add_time'] = time();

        $um['score'] = $array['score'] + $this->user['score'];
        $array['total'] = $um['score'];

        $arr['score'] = $um['score'];
        M('score_log')->add($array);
        M('yue_log')->where('id = '.intval($yuerid))->save($ye);
        M('user')->where('id = '.$uid)->save($arr);

        $url = U('User/index');
        echo "<script>alert('充值成功');window.location.href='".$url."'</script>";
        //$this->success("充值成功",U('User/index'));
	}


    public function cards(){
        if(empty($_SESSION['user'])){
            $this->redirect(U('Member/login'));
        }
        //未过期未使用
        $time = time();
        $where['a.start_time'] = array('elt',$time);
        $where['a.end_time'] = array('egt',$time);
        $where['a.uid'] = $this->user['id'];
        $where['a.status'] = 0;
        $join= 'a left join __MERCHANT__ b on a.sid = b.id ';
        $field = 'a.*,b.title';
        $wei_list = M('coupon')->where($where)->join($join)->field($field)->select();
        $this->assign('wei_list',$wei_list);

        //未过期已使用
        $wheres['a.start_time'] = array('elt',$time);
        $wheres['a.end_time'] = array('egt',$time);
        $wheres['a.uid'] = $this->user['id'];
        $wheres['a.status'] = 1;
        $join= 'a left join __MERCHANT__ b on a.sid = b.id ';
        $field = 'a.*,b.title';
        $yi_list = M('coupon')->where($wheres)->join($join)->field($field)->select();
        $this->assign('yi_list',$yi_list);
        //已过期
        $gu['a.end_time'] = array('lt',$time);
        $gu['a.uid'] = $this->user['id'];
        $join= 'a left join __MERCHANT__ b on a.sid = b.id ';
        $field = 'a.*,b.title';
        $gu_list = M('coupon')->where($gu)->join($join)->field($field)->select();
        $this->assign('gu_list',$gu_list);
        $this->display();
    }



    public function gw_pay_cards(){
        //未过期未使用
        $time = time();
        $where['start_time'] = array('elt',$time);
        $where['end_time'] = array('egt',$time);
        $where['uid'] = $this->user['id'];
        $where['status'] = 0;
        $wei_list = M('coupon')->where($where)->select();
        foreach($wei_list as $k=>$v){
            $wei_list[$k]['start'] =   date("Y-m-d", $v['start_time']);
            $wei_list[$k]['end']   =   date("Y-m-d", $v['end_time']);
        }
        echo json_encode($wei_list);die;
    }

    public function pay_cards(){
        //未过期未使用
        $time = time();
        $where['start_time'] = array('elt',$time);
        $where['end_time'] = array('egt',$time);
        $where['uid'] = $this->user['id'];
        $where['status'] = 0;
        $wei_list = M('coupon')->where($where)->select();
        $this->assign('wei_list',$wei_list);
        $this->assign('id',$_GET['id']); //商品id
        $this->assign('num',$_GET['num']); //数量
        $this->assign('peisong',$_GET['peisong']);
        $this->assign('min',$_GET['min']);
        $this->display();
    }
	
}