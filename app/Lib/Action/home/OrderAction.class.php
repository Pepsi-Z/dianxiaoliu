<?php
header("Content-type: text/html; charset=utf-8");
class OrderAction extends frontendAction {
	public function _initialize() {
		parent::_initialize();
		$this->get_user_info(1);
	}

	public function zhifu()
	{
		$this->_config_seo();
		$this->display();
	}

    //小五订单
    public function index(){
        if(empty($_SESSION['user'])){
            $this->redirect(U('Member/login'));
        }

    	$uid = $this->user['id'];
    	$where = " `delete` = 0 and `type` = 1 and userId = ".intval($uid);
    	$status = $this->_get('status','trim');
        if(empty($status)){
            $status = 1;
        }
    	if($status){
    		$where .=" and status = ".$status;
    	}
    	$order = M('item_order')->where($where)->order('status asc,id desc')->select();
//    	echo M('item_order')->getLastSql();
    	foreach ($order as $k=>$v){
    		$order[$k]['detail'] = M('order_detail')->where("orderId = '".$v['orderId']."'")->select();

        }
    	$this->assign('order',$order);
        //p($order);

        $this->display();
    }

    //全部订单
    public function index_wu(){
        if(empty($_SESSION['user'])){
            $this->redirect(U('Member/login'));
        }
        $uid = $this->user['id'];
        $where = " `delete` = 0 and `type` = 0 and userId = ".intval($uid);
        $status = $this->_get('status','trim');
        if(empty($status)){
            $status = 1;
        }
        if($status){
            $where .=" and status = ".$status;
        }
        $order = M('item_order')->where($where)->order('status asc,id desc')->select();
//    	echo M('item_order')->getLastSql();
        foreach ($order as $k=>$v){
            $order[$k]['detail'] = M('order_detail')->where("orderId = '".$v['orderId']."'")->select();
        }
        $this->assign('order',$order);
        $this->assign('status_type',6);
        $this->assign('tuan',1);
        $this->display();
    }

    //折扣订单
    public function order_zhe(){
        $integral_list = M('return_integral')->where(array('delete'=>0,'uid' =>$this->user['id']))->order('id desc')->select();
        $this->assign('integral_list',$integral_list);
        $this->display();
    }

    //团购订单
    public function order_tuan(){
        if(empty($_SESSION['user'])){
            $this->redirect(U('Member/login'));
        }
        $uid = $this->user['id'];
        $where = " `delete` = 0 and `type` = 0 and userId = ".intval($uid);
        $status = $this->_get('status','trim');
        if(empty($status)){
            $status = 1;
        }
        if($status){
            $where .=" and status = ".$status;
        }
        $order = M('item_order')->where($where)->order('status asc,id desc')->select();
//    	echo M('item_order')->getLastSql();
        foreach ($order as $k=>$v){
            $order[$k]['detail'] = M('order_detail')->where("orderId = '".$v['orderId']."'")->select();
        }
        $this->assign('order',$order);
        $this->display();
    }












	public  function cancelOrder()//取消订单
	{
	  $orderId=$_GET['orderId'];
	  !$orderId && $this->_404();

	  $this->assign('orderId',$orderId);
	  $this->_config_seo();
	  $this->display();
	}
    //小五
    public  function cancelOrder_wu()//取消订单
    {
        $orderId=$_GET['orderId'];
        !$orderId && $this->_404();

        $this->assign('orderId',$orderId);
        $this->_config_seo();
        $this->display();
    }

	public function confirmOrder1111()//确认收货
	{
		 $orderId=$_GET['orderId'];
		 $status=$_GET['status'];
	     !$orderId && $this->_404();
	     $item_order=M('item_order');
	     $item=M('item');
	     $item_orders= $item_order->where("orderId='$orderId' and userId='".$this->user['id']."' and status=3")->find();
	     if(!is_array($item_orders))
	     {
	     	$this->error('该订单不存在!');
	     }
	     $data['status']=4;//收到货
	     if($item_order->where("orderId='$orderId' and userId='".$this->user['id']."'")->save($data))
	     {

	     	$this->success('确认收货成功');
	     }else
	     {
	     	$this->error('确定收货失败');
	     }

	}


    public function confirmOrder1111_wu()//确认收货
    {
        $orderId=$_GET['orderId'];
        $status=$_GET['status'];
        !$orderId && $this->_404();
        $item_order=M('item_order');
        $item=M('item');
        $item_orders= $item_order->where("orderId='$orderId' and userId='".$this->user['id']."' and status=3")->find();
        if(!is_array($item_orders))
        {
            $this->error('该订单不存在!');
        }
        $data['status']=4;//收到货
        if($item_order->where("orderId='$orderId' and userId='".$this->user['id']."'")->save($data))
        {

            $this->success('确认收货成功');
        }else
        {
            $this->error('确定收货失败');
        }

    }

	public function closeOrder()//关闭订单
	{

	  $orderId=$_POST['orderId'];
	 $cancel_reason=$_POST['cancel_reason'];
	  !$orderId && $this->_404();
	  $item_order=M('item_order');
	  $item=M('item');
	  $order_detail=M('order_detail');
	  $userId=$this->visitor->info['id'];
	  $order=$item_order->where("orderId='$orderId' and userId='$userId'")->find();
	  if(!is_array($order))
	  {
	  	$this->error('该订单不存在');
	  }else
	  {
	  	$data['status']=5;
	  	$data['closemsg']=$cancel_reason;
	   	if($item_order->where("orderId='$orderId'")->save($data))//设置为关闭
	   	{	//如果选择货到付款
			if($order['supportmetho']==2){
				$order_details=$order_detail->where("orderId='$orderId'")->select();
				foreach ($order_details as $val)
				{
					$item->where('id='.$val['itemId'])->setInc('goods_stock',$val['quantity']);
				}
			}
	   		$this->redirect('User/index');
	   	}else{
	   		  $this->error('关闭订单失败!');
	   	}
	  }

	}


    public function closeOrder_wu()//关闭订单
    {

        $orderId=$_POST['orderId'];
        $cancel_reason=$_POST['cancel_reason'];
        !$orderId && $this->_404();
        $item_order=M('item_order');
        $item=M('item');
        $order_detail=M('order_detail');
        $userId=$this->visitor->info['id'];
        $order=$item_order->where("orderId='$orderId' and userId='$userId'")->find();
        if(!is_array($order))
        {
            $this->error('该订单不存在');
        }else
        {
            $data['status']=5;
            $data['closemsg']=$cancel_reason;
            if($item_order->where("orderId='$orderId'")->save($data))//设置为关闭
            {	//如果选择货到付款
                if($order['supportmetho']==2){
                    $order_details=$order_detail->where("orderId='$orderId'")->select();
                    foreach ($order_details as $val)
                    {
                        $item->where('id='.$val['itemId'])->setInc('goods_stock',$val['quantity']);
                    }
                }
                $this->redirect('User/index');
            }else{
                $this->error('关闭订单失败!');
            }
        }

    }

	public  function checkOrder()//查看订单
	{
	   $orderId=$_GET['orderId'];
	  !$orderId && $this->_404();
	  $status=$_GET['status'];
	  $item_order=M('item_order');
	  $userId=$this->visitor->info['id'];
	  $order=$item_order->where("orderId='$orderId' and userId='$userId'")->find();
	  if(!is_array($order))
	  {
	  	$this->error('该订单不存在');
	  }else
	  {

	  	$order_detail=M('order_detail');

	  	$order_details= $order_detail->where("orderId='".$order['orderId']."'")->select();
	  	$item_detail=array();
	  	foreach ($order_details as $key=>$val)
	  	{
	  		$items_attr=$val['item_attr'];//商品属性
			$attr_arr=array_filter(explode(";",$items_attr));
			$attr_list=array();
			foreach($attr_arr as $ke=>$va){
				$attr_arr2=array_filter(explode("|",$va));
				$attr_list[]=array('name'=>$attr_arr2[0],'value'=>$attr_arr2[1]);
			}
			$items[$key]['attr']=$attr_list;//赋值items

	  		$items[$key]= array('title'=>$val['title'],'img'=>$val['img'],'price'=>$val['price'],'quantity'=>$val['quantity'],'attr'=>$attr_list);

	  		//$order[$key]['items'][]=$items;
	  		$item_detail[$key]=$items[$key];

	  	}
	  }


	    $this->assign('item_detail',$item_detail);
		$this->assign('order',$order);

		$this->_config_seo();
		$this->display();
	}


    public  function checkOrder_wu()//查看订单
    {
        $orderId=$_GET['orderId'];
        !$orderId && $this->_404();
        $status=$_GET['status'];
        $item_order=M('item_order');
        $userId=$this->visitor->info['id'];
        $order=$item_order->where("orderId='$orderId' and userId='$userId'")->find();
        if(!is_array($order))
        {
            $this->error('该订单不存在');
        }else
        {

            $order_detail=M('order_detail');

            $order_details= $order_detail->where("orderId='".$order['orderId']."'")->select();
            $item_detail=array();
            foreach ($order_details as $key=>$val)
            {
                $items_attr=$val['item_attr'];//商品属性
                $attr_arr=array_filter(explode(";",$items_attr));
                $attr_list=array();
                foreach($attr_arr as $ke=>$va){
                    $attr_arr2=array_filter(explode("|",$va));
                    $attr_list[]=array('name'=>$attr_arr2[0],'value'=>$attr_arr2[1]);
                }
                $items[$key]['attr']=$attr_list;//赋值items

                $items[$key]= array('title'=>$val['title'],'img'=>$val['img'],'price'=>$val['price'],'quantity'=>$val['quantity'],'attr'=>$attr_list);

                //$order[$key]['items'][]=$items;
                $item_detail[$key]=$items[$key];

            }
        }


        $this->assign('item_detail',$item_detail);
        $this->assign('order',$order);

        $this->_config_seo();
        $this->display();
    }

	public function jiesuan(){//结算


		if(count($_SESSION['cart'])>0)
		{
		$user_address_mod = M('user_address');
		$address_list = $user_address_mod->where(array('uid'=>$this->visitor->info['id']))->select();
		$this->assign('address_list', $address_list);
		$items=M('item');
		$pingyou=0;
		$kuaidi=0;
		$ems=0;
		$freesum=0;
		foreach ($_SESSION['cart'] as $item)
		{
			$free= $items->field('free,pingyou,kuaidi,ems')->where('free=2')->find($item['id']);
			if(is_array($free))
			{
				$pingyou+=$free['pingyou'];
				$kuaidi+=$free['kuaidi'];
				$ems+=$free['ems'];
				$freesum+=$free['pingyou']+$free['kuaidi']+$free['ems'];
			}
/*=======================by lyye 2014-04-08=======================*/
			//判断是否限购
			//首先判断购物车数量是否超过限购数量
			$isxiangou = $items->field('is_xiangou,xiangou_num')->where('is_xiangou=1')->find($item['id']);
			if(is_array($isxiangou))
			{
				if($isxiangou['is_xiangou'] == 1)
				{
					if($item['num'] > $isxiangou['xiangou_num'])
					{
						$this->error('对不起，购物车含有限购物品，请核对限购数量');
					}
					//再次判断用户是否购买过此商品
					$order_detail = M('order_detail');
					$order = M('item_order');
					$item_orderlist = $order_detail->field('orderId')->where("itemId=$item[id]")->select();
					if($item_orderlist)
					{
						foreach ($item_orderlist as $orderid)
						{
							$map['userId'] 	= $this->visitor->info['id'];
							$map['orderId']	= $orderid['orderId'];
							$map['status']	= array('neq',5);//取消订单判断
							$you_num = $order->where($map)->count("id");
							if($you_num > 0)
							{
								$this->error('对不起，购物车含有限购物品，并且您已经购买过该商品!!');
							}
						}
					}
				}
			}

/*=======================by lyye 2014-04-08=======================*/
		}

		//   $dingdanhao = date("Y-m-dH-i-s");
		// $dingdanhao = str_replace("-","",$dingdanhao);
		// $dingdanhao .= rand(1000,2000);
		  import('Think.ORG.Cart');// 导入分页类
    	 $cart=new Cart();
    	 $sumPrice= $cart->getPrice();

    	 $freearr=array();
    	 if($pingyou>0)
    	 {
    	 	$freearr[]=array('value'=>1,'price'=>$pingyou);
    	 }
    	  if($kuaidi>0)
    	 {
    	 	$freearr[]=array('value'=>2,'price'=>$kuaidi);
    	 }
    	  if($ems>0)
    	 {
    	 	$freearr[]=array('value'=>3,'price'=>$ems);
    	 }


    	// var_dump($freearr);
    	 $this->assign('freearr',$freearr);
    	 $this->assign('freesum',$freesum);
    	 $this->assign('sumPrice',$sumPrice);
	    //$this->assign('pingyou',$pingyou);
		//$this->assign('kuaidi',$kuaidi);
	    //$this->assign('ems',$ems);

		$this->_config_seo();
		$this->display();
		}else
		{
			$this->redirect('Shopcart/index');
		}
	}

	public function pay(){
            //生成订单号
		$uid = $this->user['id'];
		$user_address=M('user_address');
		$item_order=M('item_order');
		$order_detail=M('order_detail');
		$item_goods=M('item');

        $type = $_REQUEST['type'];
		$dd = array();
		if(IS_POST){
			$time=time();//订单添加时间
            $address_options= $this->_post('address_id','intval');//地址  0：刚填的地址 大于0历史的地址
            $total=$this->_post('total','trim');//商品总额
            $guige=$this->_post('guige','trim');//商品规格
            $freetype = $this->_post('freetype','trim');//商品配货方式
			$data['add_time']=$time;//添加时间
			$data['userId']=$uid;//用户ID
			$data['userName']=$this->user['name'];//用户ID
            $data['beizhu']=$this->_post('beizhu');//备注
            if($type == '4'){
                $address= $user_address->where("uid='$uid'")->find($address_options);//取到地址            $data['mobile']=$address['mobile'];//电话号码
                if(!$address){
                    $this->error('请先完善自己收货地址');
                }
                $data['address_name']=$address['consignee'];//收货人姓名
                $data['mobile']= $address['mobile']; //电话号码
                $data['address']=$this->get_address($address['pid'],$address['cid'],$address['aid']);//地址
                $data['address'] = $data['address'].$address['address'];
                $data['type'] = 1;//小五送酒
            }else{
                $data['order_code'] = $this->NoRand();
            }

			$order_sumPrice = $total; //商品总额
			$dingdanhao = date("YmdHis");  //订单编号
			$dingdanhao .= rand(100, 999);//订单编号 日期加随机数
			$data['orderId']=$dingdanhao;//订单号
			$data['order_sumPrice'] = $total;//订单价格
			$data['goods_sumPrice']= $total;//商品价格
            $data['guige']= $guige;//商品规格
            $data['freetype']= $freetype;//商品配货方式
            $data['tel']= $this->_post('tel');//发验证码时候用的手机号
			$spid = implode(',',$_POST['id']);
			$sp = $item_goods->where(array('id'=>array('in',$spid)))->select();


			foreach($sp as $k=>$v){
				$p = $_POST;
				$numstr = 'number'.$v['id'];
			}
              //防止表单重复提交
            $ids = rtrim(implode(',', $_POST['id']), ',');
            $sid = M('item')->where(array('id'=>$ids))->getField('sid');
            $data['merchant_id'] = $sid;

            if(isset($_POST['code'])) {
                if ($_POST['code'] == $_SESSION['code']) {
                    // 重复提交表单了
                } else {
                    $_SESSION['code'] = $_POST['code']; //存储code
                    if($item_order->data($data)->add()){//添加订单成功
                        $ids = rtrim(implode(',', $_POST['id']), ',');
                        //将结算过后的商品从购物车删除
                        M('card')->where(' item_id in('.$ids.') and uid = '.intval($uid))->delete();
                        $arry = array_filter($_POST['id']); //商品id
                        $card_number = array_filter($_POST['number']); //商品数量
                        $card_price = array_filter($_POST['price']); //商品价钱
                        $card_guige = array_filter($_POST['guige']); //商品规格
                        //组成新数据
                        $car_arry = array();
                        foreach($arry as $ks =>$vs){
                            $car_arry[$ks]['id'] = $vs;
                            $car_arry[$ks]['number'] = $card_number[$ks];
                            $car_arry[$ks]['price'] = $card_price[$ks];
                            $car_arry[$ks]['guige'] = $card_guige[$ks];
                        }
                        if($_REQUEST['card']){
                            //购物车
                            foreach ($car_arry as $v){
                                //订单表插入一条数据
                                $arr['itemId'] = $v['id'];
                                $goods = M('item')->where("id = ".intval($v['id']))->find();
                                $arr['title'] = $goods['title'];
                                $arr['img'] = $goods['img'];
                                $arr['price'] =  $v['price'];
                                $arr['orderId'] = $dingdanhao;
                                $arr['guige'] = $v['guige'];
                                $arr['quantity'] = $v['number'];
                                $order_detail->data($arr)->add();
                            }

                        }else{
                            foreach ($arry as $v){
                                $p = $_POST;
                                $numstr = 'number'.$v;
                                $pricestr = 'price'.$v;
                                $arr['quantity'] = $p[$numstr];//数量
                                //订单表插入一条数据
                                $dd[] = $dingdanhao;
                                $arr['itemId'] = $v;
                                $goods = M('item')->where("id = ".intval($v))->find();
                                $arr['title'] = $goods['title'];
                                $arr['img'] = $goods['img'];
                                $arr['price'] =  $p[$pricestr];
                                $arr['orderId'] = $dingdanhao;
                                $arr['guige'] = $guige;
                                $order_detail->data($arr)->add();
                            }

                        }

                    }

                }
            }



        }
		if($_GET['orderId']){
			$dingdanhao = $_GET['orderId'];
		}
		if($_GET['order_sumPrice']){
			$order_sumPrice = $_GET['order_sumPrice'];
		}
		$this->assign('order_sumPrice',$order_sumPrice);
        if($this->user['money']){
           $s_m = $this->user['money'];
        }else{
           $s_m = 0;
        }
        $this->assign('u_mony',$s_m);
		$this->assign('dingdanhao',$dingdanhao);
        $this->assign('tel',$this->_post('tel'));
        $this->assign('type',$type);
		$this->display();

	}

    public  function end()
    {
        if(IS_POST)
        {
            $uid = $this->user['id'];
            $payment_id=$_POST['payment_id'];
            $dingdanhao=$_POST['orderId'];
            $total = $_POST['total'];
            $userId=$this->user['id'];
            $tel = $_POST['tel'];  //发送验证吗
            $type = $_POST['type'];
            $item_order=M('item_order')->where("userId='$userId' and orderId='$dingdanhao'")->find();
            if($type == '4'){
                if ($payment_id ==2 ){ //微信支付
                    //微信支付
                    $body = "订单支付";
                    $trade_no = $dingdanhao;
                    $Total_fee = $total;//支付金额
                    $xfurl = XF_HTTP;//返回地址
                    $type = 6;
                    //$Total_fee = 0.01;
                    $param1 = "body=".$body."&trade_no=".$trade_no."&Total_fee=".$Total_fee."&xfurl=".$xfurl.'&type='.$type;
                    echo "<script>location.href='api/wxqrpay/unifiedorder.php?".$param1."'</script>";
                }else if($payment_id==1) {//余额支付
                    if ($total > $this->user['money']) {
                        $this->error("您的余额已不足");
                    }
                    //余额表插入一条数据
                    $yue['uid'] = $uid;
                    $yue['type'] = 2;
                    $yue['status'] = 2;
                    $yue['money'] = -$total;
                    $yue['add_time'] = time();
                    //更改用户余额、积分
                    $umoney = $this->user['money'];
                    $uscore = $this->user['score'];
                    $um['money'] = $umoney - $total;

                    $yue['total'] = $um['money'];
                    //给用户积分
                    $array['uid'] = $uid;
                    $array['uname'] = $this->user['name'];
                    $array['action'] = '消费';
                    $consume = C('pin_reward');  //消费送积分
                    $array['score'] = intval($consume * $total);
                    $array['add_time'] = time();

                    //$um['score'] = $array['score'] + $uscore;
                    $array['total'] = $um['score'];
                    M('yue_log')->add($yue);
                    //M('score_log')->add($array);

                    M('user')->where('id =' . intval($uid))->save($um);
                    $order_num = $dingdanhao;
                    if ($order_num) {
                        //改变订单状态
                        $data['status'] = 2;
                        $data['support_time'] = time();
                        $data['supportmetho'] = 2;
                        M('item_order')->where("orderId='" . $order_num . "'")->save($data);
                        $order = M('order_detail')->where("orderId = " . $order_num)->select();
                        foreach ($order as $k => $v) {
                            //更改商品库存
                            $ku = M('item')->field('goods_stock,buy_num')->where('id = ' . intval($v['itemId']))->find();
                            $ku['goods_stock'] = $ku['goods_stock'] - $v['quantity'];
                            $ku['buy_num'] = $ku['buy_num'] + $v['quantity'];
                            M('item')->where('id = ' . intval($v['itemId']))->save($ku);
                        }

                    }
                    $item = M('item_order')->where(array('orderId'=>$order_num))->find();
                    $order_detail=M('order_detail')->where(array('orderId'=>$order_num))->select();
                    $a = $item['address'];
                    $str=str_replace('&nbsp;','',$a);

                    if($item['freetype'] == 0){

                        $peisong = '自提 ';
                    }else if($item['freetype'] == 1){

                        $peisong = '配送';
                    }



                    foreach($order_detail as $ok => $ov){
                        if($ov['guige']){
                            //$admin_openid = 'oM0qSwzGjZVxl2nmjRXKH3GHrQJw';

                            $admin_openid = 'oM0qSw3D7XuMEPwKuJevvOw-h93s';
                            $admin_str =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,商品数量：【".$ov['quantity']."】,商品规格：【".$ov['guige']."】,配送方式：【".$peisong."】,收货人：【".$item['address_name']."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                            MessageUtil::sendTextInfo($admin_openid,$admin_str);

                            $admin_openid2 = 'oM0qSwxvkIZUKTpiHBOiZ0t2zFCk';
                            $admin_str2 =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,商品数量：【".$ov['quantity']."】,商品规格：【".$ov['guige']."】,配送方式：【".$peisong."】,收货人：【".$item['address_name']."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                            MessageUtil::sendTextInfo($admin_openid2,$admin_str2);

                            $admin_openid3 = 'oM0qSw6b0ubzynlvkN_24ovGHCgI';
                            $admin_str3 =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,商品数量：【".$ov['quantity']."】,商品规格：【".$ov['guige']."】,配送方式：【".$peisong."】,收货人：【".$item['address_name']."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                            MessageUtil::sendTextInfo($admin_openid3,$admin_str3);

                            $admin_openid4 = 'oM0qSw8cJvWaEXaz_zIOMcDPZ6-A';
                            $admin_str4 =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,商品数量：【".$ov['quantity']."】,商品规格：【".$ov['guige']."】,配送方式：【".$peisong."】,收货人：【".$item['address_name']."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                            MessageUtil::sendTextInfo($admin_openid4,$admin_str4);




                        }else{
                            //$admin_openid = 'oM0qSwzGjZVxl2nmjRXKH3GHrQJw';
                            $admin_openid = 'oM0qSw3D7XuMEPwKuJevvOw-h93s';
                            $admin_str =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,商品数量：【".$ov['quantity']."】,配送方式：【".$peisong."】,收货人：【".$item['address_name']."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                            MessageUtil::sendTextInfo($admin_openid,$admin_str);



                            $admin_openid2 = 'oM0qSwxvkIZUKTpiHBOiZ0t2zFCk';
                            $admin_str2 =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,商品数量：【".$ov['quantity']."】,配送方式：【".$peisong."】,收货人：【".$item['address_name']."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                            MessageUtil::sendTextInfo($admin_openid2,$admin_str2);


                            $admin_openid3 = 'oM0qSw6b0ubzynlvkN_24ovGHCgI';
                            $admin_str3 =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,商品数量：【".$ov['quantity']."】,配送方式：【".$peisong."】,收货人：【".$item['address_name']."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                            MessageUtil::sendTextInfo($admin_openid3,$admin_str3);



                            $admin_openid4 = 'oM0qSw8cJvWaEXaz_zIOMcDPZ6-A';
                            $admin_str4 =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,商品数量：【".$ov['quantity']."】,配送方式：【".$peisong."】,收货人：【".$item['address_name']."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                            MessageUtil::sendTextInfo($admin_openid4,$admin_str4);
                        }


                    }



                    $url = U('Order/index',array('status'=>2));
                    echo "<script>alert('支付成功');window.location.href='".$url."'</script>";

                }else if($payment_id==3) {
                    $order_num = $dingdanhao;
                    if ($order_num) {
                        //改变订单状态
                        $uid = $this->user['id'];
                        $data['status'] = 2;
                        $data['support_time'] = time();
                        $data['supportmetho'] = 3;
                        M('item_order')->where("orderId='" . $order_num . "'")->save($data);
                        $order = M('order_detail')->where("orderId = " . $order_num)->select();
                        foreach ($order as $k => $v) {
                            //更改商品库存
                            $ku = M('item')->field('goods_stock,buy_num')->where('id = ' . intval($v['itemId']))->find();
                            $ku['goods_stock'] = $ku['goods_stock'] - $v['quantity'];
                            $ku['buy_num'] = $ku['buy_num'] + $v['quantity'];
                            M('item')->where('id = ' . intval($v['itemId']))->save($ku);
                        }

                    }
                    $item = M('item_order')->where(array('orderId'=>$order_num))->find();
                    $order_detail=M('order_detail')->where(array('orderId'=>$order_num))->select();
                    $a = $item['address'];
                    $str=str_replace('&nbsp;','',$a);

                    if($item['freetype'] == 0){
                        $peisong = '自提 ';
                    }else if($item['freetype'] == 1){
                        $peisong = '配送';
                    }

                    foreach($order_detail as $ok => $ov){
                        if($ov['guige']){
                            //$admin_openid = 'oM0qSwzGjZVxl2nmjRXKH3GHrQJw';
                            $admin_openid = 'oM0qSw3D7XuMEPwKuJevvOw-h93s';
                            $admin_str =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,商品数量：【".$ov['quantity']."】,商品规格：【".$ov['guige']."】,该商品为：【货到付款】,配送方式：【".$peisong."】,收货人：【".$item['address_name']."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                            MessageUtil::sendTextInfo($admin_openid,$admin_str);



                            $admin_openid2 = 'oM0qSwxvkIZUKTpiHBOiZ0t2zFCk';
                            $admin_str2 =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,商品数量：【".$ov['quantity']."】,商品规格：【".$ov['guige']."】,该商品为：【货到付款】,配送方式：【".$peisong."】,收货人：【".$item['address_name']."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                            MessageUtil::sendTextInfo($admin_openid2,$admin_str2);

                            $admin_openid3 = 'oM0qSw6b0ubzynlvkN_24ovGHCgI';
                            $admin_str3 =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,商品数量：【".$ov['quantity']."】,商品规格：【".$ov['guige']."】,该商品为：【货到付款】,配送方式：【".$peisong."】,收货人：【".$item['address_name']."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                            MessageUtil::sendTextInfo($admin_openid3,$admin_str3);

                            $admin_openid4 = 'oM0qSw8cJvWaEXaz_zIOMcDPZ6-A';
                            $admin_str4 =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,商品数量：【".$ov['quantity']."】,商品规格：【".$ov['guige']."】,该商品为：【货到付款】,配送方式：【".$peisong."】,收货人：【".$item['address_name']."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                            MessageUtil::sendTextInfo($admin_openid4,$admin_str4);

                        }else{
                            //$admin_openid = 'oM0qSwzGjZVxl2nmjRXKH3GHrQJw';
                            $admin_openid = 'oM0qSw3D7XuMEPwKuJevvOw-h93s';
                            $admin_str =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,该商品为：【货到付款】,商品数量：【".$ov['quantity']."】,配送方式：【".$peisong."】,收货人：【".$item['address_name']."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                            MessageUtil::sendTextInfo($admin_openid,$admin_str);



                            $admin_openid2 = 'oM0qSwxvkIZUKTpiHBOiZ0t2zFCk';
                            $admin_str2 =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,该商品为：【货到付款】,商品数量：【".$ov['quantity']."】,配送方式：【".$peisong."】,收货人：【".$item['address_name']."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                            MessageUtil::sendTextInfo($admin_openid2,$admin_str2);

                            $admin_openid3 = 'oM0qSw6b0ubzynlvkN_24ovGHCgI';
                            $admin_str3 =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,该商品为：【货到付款】,商品数量：【".$ov['quantity']."】,配送方式：【".$peisong."】,收货人：【".$item['address_name']."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                            MessageUtil::sendTextInfo($admin_openid3,$admin_str3);


                            $admin_openid4 = 'oM0qSw8cJvWaEXaz_zIOMcDPZ6-A';
                            $admin_str4 =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,该商品为：【货到付款】,商品数量：【".$ov['quantity']."】,配送方式：【".$peisong."】,收货人：【".$item['address_name']."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                            MessageUtil::sendTextInfo($admin_openid4,$admin_str4);
                        }


                    }
                    $url = U('Order/index',array('status'=>2));
                    echo "<script>alert('支付成功');window.location.href='".$url."'</script>";

                }else{
                    $url = U('order/index',array('status'=>1));
                    echo "<script>alert('操作失败');window.location.href='".$url."'</script>";

                }
            }else {
                if ($payment_id == 2) { //微信支付
                    //微信支付
                    $body = "订单支付";
                    $trade_no = $dingdanhao;
                    $Total_fee = $total;//支付金额
                    $xfurl = XF_HTTP;//返回地址
                    $type = 1;
                    //$Total_fee = 0.01;
                    $param1 = "body=" . $body . "&trade_no=" . $trade_no . "&Total_fee=" . $Total_fee . "&xfurl=" . $xfurl . '&type=' . $type . '&tel=' . $tel;
                    echo "<script>location.href='api/wxqrpay/unifiedorder.php?" . $param1 . "'</script>";
                } else if ($payment_id == 1) {//余额支付

                    if ($total > $this->user['money']) {
                        $this->error("您的余额已不足");
                    }
                    //余额表插入一条数据
                    $yue['uid'] = $uid;
                    $yue['type'] = 2;
                    $yue['status'] = 2;
                    $yue['money'] = -$total;
                    $yue['add_time'] = time();
                    //更改用户余额、积分
                    $umoney = $this->user['money'];
                    $uscore = $this->user['score'];
                    $um['money'] = $umoney - $total;

                    $yue['total'] = $um['money'];
                    //给用户积分
                    $array['uid'] = $uid;
                    $array['uname'] = $this->user['name'];
                    $array['action'] = '消费';
                    $consume = C('pin_reward');  //消费送积分
                    $array['score'] = intval($consume * $total);
                    $array['add_time'] = time();

                    //$um['score'] = $array['score'] + $uscore;
                    $array['total'] = $um['score'];
                    M('yue_log')->add($yue);
                    //M('score_log')->add($array);

                    M('user')->where('id =' . intval($uid))->save($um);
                    $order_num = $dingdanhao;
                    $order_coder = M('item_order')->where("orderId='" . $order_num . "'")->getField('order_code');

                    if ($order_num) {
                        //改变订单状态
                        $data['status'] = 2;
                        $data['support_time'] = time();
                        //余额支付
                        $data['supportmetho'] = 2;


                        M('item_order')->where("orderId='" . $order_num . "'")->save($data);
                        $order = M('order_detail')->where("orderId = " . $order_num)->select();
                        foreach ($order as $k => $v) {
                            //更改商品库存
                            $ku = M('item')->field('goods_stock,buy_num')->where('id = ' . intval($v['itemId']))->find();
                            $ku['goods_stock'] = $ku['goods_stock'] - $v['quantity'];
                            $ku['buy_num'] = $ku['buy_num'] + $v['quantity'];
                            M('item')->where('id = ' . intval($v['itemId']))->save($ku);
                        }

                    }
                    //发生微信验证码
                    require_once 'app/Common/MessageUtil.php';
                    $info_openid = M('user')->where(array('id' => $uid))->find();
                    $openids = $info_openid['openid'];
                    //$openids= 'oM0qSwzGjZVxl2nmjRXKH3GHrQJw';
                    $str = "";
                    if ($info_openid['name']) {
                        $str .= "尊敬的店小六用户【" . $info_openid['name'] . "】您好！您的订单编号是【" . $order_num . "】,你的验证码是【" . $order_coder . "】";
                    } else {
                        $str .= "尊敬的店小六用户您好！您的订单编号是【" . $order_num . "】,你的验证码是【" . $order_coder . "】";
                    }

                    MessageUtil::sendTextInfo($openids, $str);

                    $url = U('Order/order_tuan', array('status' => 2));
                    echo "<script>alert('支付成功');window.location.href='" . $url . "'</script>";

               /* }else if($payment_id==3) {
                    $order_num = $dingdanhao;
                    if ($order_num) {
                        //改变订单状态
                        $uid = $this->user['id'];
                        $data['status'] = 2;
                        $data['support_time'] = time();
                        $data['supportmetho'] = 3;
                        M('item_order')->where("orderId='" . $order_num . "'")->save($data);
                        $order = M('order_detail')->where("orderId = " . $order_num)->select();
                        foreach ($order as $k => $v) {
                            //更改商品库存
                            $ku = M('item')->field('goods_stock,buy_num')->where('id = ' . intval($v['itemId']))->find();
                            $ku['goods_stock'] = $ku['goods_stock'] - $v['quantity'];
                            $ku['buy_num'] = $ku['buy_num'] + $v['quantity'];
                            M('item')->where('id = ' . intval($v['itemId']))->save($ku);
                        }

                    }
                    $item = M('item_order')->where(array('orderId'=>$order_num))->find();
                    $order_detail=M('order_detail')->where(array('orderId'=>$order_num))->select();
                    $a = $item['address'];
                    $str=str_replace('&nbsp;','',$a);

                    foreach($order_detail as $ok => $ov){
                        if($ov['guige']){
                            $admin_openid = 'oM0qSwzGjZVxl2nmjRXKH3GHrQJw';
                            //$admin_openid = 'oM0qSw3D7XuMEPwKuJevvOw-h93s';
                            $admin_str =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,商品数量：【".$ov['quantity']."】,商品规格：【".$ov['guige']."】,该商品为：【货到付款】,收货人：【".$item['address_name']."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                            MessageUtil::sendTextInfo($admin_openid,$admin_str);



                            $admin_openid2 = 'oM0qSwxvkIZUKTpiHBOiZ0t2zFCk';
                            $admin_str2 =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,商品数量：【".$ov['quantity']."】,商品规格：【".$ov['guige']."】,该商品为：【货到付款】,收货人：【".$item['address_name']."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                            //MessageUtil::sendTextInfo($admin_openid2,$admin_str2);

                        }else{
                            $admin_openid = 'oM0qSwzGjZVxl2nmjRXKH3GHrQJw';
                            //$admin_openid = 'oM0qSw3D7XuMEPwKuJevvOw-h93s';
                            $admin_str =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,该商品为：【货到付款】,商品数量：【".$ov['quantity']."】,收货人：【".$item['address_name']."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                            MessageUtil::sendTextInfo($admin_openid,$admin_str);



                            $admin_openid2 = 'oM0qSwxvkIZUKTpiHBOiZ0t2zFCk';
                            $admin_str2 =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,该商品为：【货到付款】,商品数量：【".$ov['quantity']."】,收货人：【".$item['address_name']."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                            //MessageUtil::sendTextInfo($admin_openid2,$admin_str2);
                        }


                    }
                    $url = U('Order/index',array('status'=>2));
                    echo "<script>alert('支付成功');window.location.href='".$url."'</script>";*/

                }else{
                    $url = U('order/order_tuan',array('status'=>1));
                    echo "<script>alert('操作失败');window.location.href='".$url."'</script>";

                }
            }

        }

    }



	public function call_back(){
		$order_num = $_POST['trade_no'];
        $tel = $_GET['tel'];
        $uid = $this->user['id'];
		if($order_num){
            //改变订单状态
            $data['status'] = 2;
            $data['support_time'] = time();
            $data['supportmetho'] = 1;
            $order_coder = M('item_order')->where("orderId='".$order_num."'")->getField('order_code');
            M('item_order')->where("orderId='".$order_num."'")->save($data);
            $order = M('order_detail')->where("orderId = ".$order_num)->select();
            $total = $_POST['Total_fee'];
			foreach ($order as $k=>$v){
				//更改商品库存
				$ku = M('item')->field('goods_stock,buy_num')->where('id = '.intval($v['itemId']))->find();
				//$ku['goods_stock'] = $ku['goods_stock'] - $v['quantity'];
				$ku['buy_num'] = $ku['buy_num'] + $v['quantity'];
				M('item')->where('id = '.intval($v['itemId']))->save($ku);
			}
            //发生微信验证码
            require_once 'app/Common/MessageUtil.php';
            $info_openid=M('user')->where(array('id'=>$uid))->find();
            $openids=$info_openid['openid'];
            $str = "";
            if($info_openid['name']){
                $str.= "尊敬的店小六用户【".$info_openid['name']."】您好！您的订单编号是【".$order_num."】,你的验证码是【".$order_coder."】";
            }else{
                $str.= "尊敬的店小六用户您好！您的订单编号是【".$order_num."】,你的验证码是【".$order_coder."】";
            }

            MessageUtil::sendTextInfo($openids,$str);
            //管理员opneid
            //$admin_openid = 'oM0qSwzGjZVxl2nmjRXKH3GHrQJw';
            //$admin_str = "您有新订单需要处理,订单编号：【".$order_num."】";
            //MessageUtil::sendTextInfo($admin_openid,$admin_str);
            $url = U('Order/order_tuan',array('status'=>2));
            echo "<script>alert('支付成功');window.location.href='".$url."'</script>";
		}
	}

    //小五支付返回方法
    public function xiaowu_call_back(){
        $order_num = $_POST['trade_no'];
        $uid = $this->user['id'];
        if($order_num){
            //改变订单状态
            $data['status'] = 2;
            $data['support_time'] = time();
            $data['supportmetho'] = 1;
            M('item_order')->where("orderId='".$order_num."'")->save($data);
            $order = M('order_detail')->where("orderId = ".$order_num)->select();
            $total = $_POST['Total_fee'];
            foreach ($order as $k=>$v){
                //更改商品库存
                $ku = M('item')->field('goods_stock,buy_num')->where('id = '.intval($v['itemId']))->find();
                //$ku['goods_stock'] = $ku['goods_stock'] - $v['quantity'];
                $ku['buy_num'] = $ku['buy_num'] + $v['quantity'];
                M('item')->where('id = '.intval($v['itemId']))->save($ku);
            }


            $item = M('item_order')->where(array('orderId'=>$order_num))->find();
            $order_detail=M('order_detail')->where(array('orderId'=>$order_num))->select();

            $a = $item['address'];
            $str=str_replace('&nbsp;','',$a);

            if($item['freetype'] == 0){
                $peisong = '自提 ';
            }else if($item['freetype'] == 1){
                $peisong = '配送';
            }


            foreach($order_detail as $ok => $ov){
                if($ov['guige']){
                    //$admin_openid = 'oM0qSwzGjZVxl2nmjRXKH3GHrQJw';
                    $admin_openid = 'oM0qSw3D7XuMEPwKuJevvOw-h93s';
                    $admin_str =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,商品数量：【".$ov['quantity']."】,商品规格：【".$ov['guige']."】,收货人：【".$item['address_name']."】,配送方式：【".$peisong."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                    MessageUtil::sendTextInfo($admin_openid,$admin_str);



                    $admin_openid2 = 'oM0qSwxvkIZUKTpiHBOiZ0t2zFCk';
                    $admin_str2 =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,商品数量：【".$ov['quantity']."】,商品规格：【".$ov['guige']."】,收货人：【".$item['address_name']."】,配送方式：【".$peisong."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                    MessageUtil::sendTextInfo($admin_openid2,$admin_str2);


                    $admin_openid3 = 'oM0qSw6b0ubzynlvkN_24ovGHCgI';
                    $admin_str3 =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,商品数量：【".$ov['quantity']."】,商品规格：【".$ov['guige']."】,收货人：【".$item['address_name']."】,配送方式：【".$peisong."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                    MessageUtil::sendTextInfo($admin_openid3,$admin_str3);


                    $admin_openid4 = 'oM0qSw8cJvWaEXaz_zIOMcDPZ6-A';
                    $admin_str4 =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,商品数量：【".$ov['quantity']."】,商品规格：【".$ov['guige']."】,收货人：【".$item['address_name']."】,配送方式：【".$peisong."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                    MessageUtil::sendTextInfo($admin_openid4,$admin_str4);

                }else{
                    //$admin_openid = 'oM0qSwzGjZVxl2nmjRXKH3GHrQJw';
                    $admin_openid = 'oM0qSw3D7XuMEPwKuJevvOw-h93s';
                    $admin_str =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,商品数量：【".$ov['quantity']."】,收货人：【".$item['address_name']."】,配送方式：【".$peisong."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                    MessageUtil::sendTextInfo($admin_openid,$admin_str);



                    $admin_openid2 = 'oM0qSwxvkIZUKTpiHBOiZ0t2zFCk';
                    $admin_str2 =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,商品数量：【".$ov['quantity']."】,收货人：【".$item['address_name']."】配送方式：【".$peisong."】,,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                    MessageUtil::sendTextInfo($admin_openid2,$admin_str2);



                    $admin_openid3 = 'oM0qSw6b0ubzynlvkN_24ovGHCgI';
                    $admin_str3 =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,商品数量：【".$ov['quantity']."】,收货人：【".$item['address_name']."】,配送方式：【".$peisong."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                    MessageUtil::sendTextInfo($admin_openid3,$admin_str3);


                    $admin_openid4 = 'oM0qSw8cJvWaEXaz_zIOMcDPZ6-A';
                    $admin_str4 =  "您有新订单需要处理,订单编号：【".$order_num."】,商品名称：【".$ov['title']."】,商品数量：【".$ov['quantity']."】,收货人：【".$item['address_name']."】,配送方式：【".$peisong."】,地址：【".$str."】,联系电话：【".$item['mobile']."】";
                    MessageUtil::sendTextInfo($admin_openid4,$admin_str4);

                }


            }
            $url = U('Order/index',array('status'=>2));
            echo "<script>alert('支付成功');window.location.href='".$url."'</script>";
        }
    }

    public function pay111()//出订单
	{


		if(IS_POST&&count($_SESSION['cart'])>0)
		{
			 import('Think.ORG.Cart');// 导入分页类
             $cart=new Cart();
			$user_address=M('user_address');
			$item_order=M('item_order');
			$order_detail=M('order_detail');
			$item_goods=M('item');
			$this->visitor->info['id'];//用户ID
		   $this->visitor->info['username'];//用户账号
		   $this->visitor->info['wechatid'];//用户账号

		   //生成订单号
		   $dingdanhao = date("Y-m-dH-i-s");
		   $dingdanhao = str_replace("-","",$dingdanhao);
		   $dingdanhao .= rand(1000,2000);

		    $time=time();//订单添加时间
			$address_options= $this->_post('address_options','intval');//地址  0：刚填的地址 大于0历史的地址
			$shipping_id=$this->_post('shipping_id','intval');//配送方式
			$postscript=$this->_post('postscript','trim');//卖家留言

			if(!empty($postscript))//卖家留言
			{
				$data['note']=$postscript;
			}

		    if(empty($shipping_id))//卖家包邮
		    {
		    	$data['freetype']=0;
		    	$data['order_sumPrice']=$cart->getPrice();
		    }else
		    {
		    	$data['freetype']=$shipping_id;
		    	$data['freeprice']= $this->getFree($shipping_id);//取到运费
		    	$data['order_sumPrice']=$cart->getPrice()+$this->getFree($shipping_id);

		    	//echo $cart->getPrice()+$this->getFree($shipping_id);exit;
		    }

		   $data['orderId']=$dingdanhao;//订单号
		   $data['add_time']=$time;//添加时间
		   $data['goods_sumPrice']=$cart->getPrice();//商品总额
		   $data['userId']=$this->visitor->info['id'];//用户ID
		   if($this->visitor->info['username']){
		   		$data['userName']=$this->visitor->info['username'];//用户名
			}else{
				$data['userName']=$this->visitor->info['wechatid'];//用微信id做用户名
			}
			if($address_options==0)
			{
			$consignee=$this->_post('consignee','trim');//真实姓名
			$sheng=$this->_post('sheng','trim');//省
			$shi=$this->_post('shi','trim');//市
			$qu=$this->_post('qu','trim');//区
			$address=$this->_post('address','trim');//详细地址
			$phone_mob=$this->_post('phone_mob','trim');//电话号码
			$save_address=$this->_post('save_address','trim');//是否保存地址

			$data['address_name']=$consignee;//收货人姓名
			$data['mobile']=$phone_mob;//电话号码
			$data['address']=$sheng.$shi.$qu.$address;//地址

			   if($save_address)//保存地址
			   {
			   	$add_address['uid']=$this->visitor->info['id'];
			   	$add_address['consignee']=$consignee;
			   	$add_address['address']=$address;
			   	$add_address['mobile']=$phone_mob;
			   	$add_address['sheng']=$sheng;
			   	$add_address['shi']=$shi;
			   	$add_address['qu']=$qu;

                 $user_address->data($add_address)->add();
		       }

			}else{
				$userId=$this->visitor->info['id'];
				$address= $user_address->where("uid='$userId'")->find($address_options);//取到地址

				$data['address_name']=$address['consignee'];//收货人姓名
				$data['mobile']=$address['mobile'];//电话号码
				$data['address']=$address['sheng'].$address['shi'].$address['qu'].$address['address'];//地址
			}
			if($orderid=$item_order->data($data)->add())//添加订单成功
			{
				$orders['orderId']=$dingdanhao;
				foreach ($_SESSION['cart'] as $item )
				{

					//$item_goods->where('id='.$item['id'])->setDec('goods_stock',$item['num']);
					//echo $item['attr'];exit;
					$orders['itemId']=$item['id'];//商品ID
					$orders['title']=$item['name'];//商品名称
					$orders['img']=$item['img'];//商品图片
					$orders['price']=$item['price'];//商品价格
					$orders['quantity']=$item['num'];//购买数量
					$orders['item_attr']=$item['attr'];//商品属性
					$order_detail->data($orders)->add();
				}


				$cart->clear();//清空购物车

				$this->assign('orderid',$orderid);//订单ID
				$this->assign('dingdanhao',$dingdanhao);//订单号
				$this->assign('order_sumPrice',$data['order_sumPrice']);

			}else
			{
				$this->error('生成订单失败!');
			}
		}else if(isset($_GET['orderId']))
		{
			$item_order=M('item_order');
			$orderId=$_GET['orderId'];//订单号
			$userId=$this->visitor->info['id'];
			$orders=$item_order->where("userId='$userId' and orderId='$orderId'")->find();
			if(!is_array($orders))
			$this->_404();

			if(empty($orders['supportmetho']) || $orders['supportmetho']==4)//是否已有支付方式
			{
				$this->assign('orderid',$orders['id']);//订单ID
				$this->assign('dingdanhao',$orders['orderId']);//订单号
				$this->assign('order_sumPrice',$orders['order_sumPrice']);
			}elseif($orders['supportmetho']==1)//选择支付宝
			{
			$pay=M('pay')->where(array('pay_type'=>'alipay'))->find();
			$alipay=unserialize($pay['config']);
			//$this->assign('alipayview',$pay['status']);
			echo "<script>location.href='api/wapalipay/alipayapi.php?WIDseller_email=".$alipay['alipayname']."&WIDout_trade_no=".$orderId."&WIDsubject=".$orderId."&WIDtotal_fee=".$orders['order_sumPrice']."'</script>";
			}elseif($orders['supportmetho']==3)//选择微信支付
			{
			//$pay=M('pay')->where(array('pay_type'=>'wxpay'))->find();
			//dump($orders);exit;
			//$wxpay=unserialize($pay['config']);
			$wxconfig=$this->wxconfig();
			$ip = get_client_ip();//获取ip
                $body = "微信支付";
                $trade_no = $ic;
                $Total_fee = $_POST['price'];
                $xfurl = XF_HTTP;
                //$Total_fee = 0.01;

                $param1 = "body=".$body."&trade_no=".$trade_no."&Total_fee=".$Total_fee."&xfurl=".$xfurl;
                echo "<script>location.href='api/wxqrpay/unifiedorder.php?".$param1."'</script>";


                //echo $ip;exit;
			echo "<script>location.href='api/wxpay/jsapicall.php?ip=".$ip."&partner=".$wxconfig['partnerid']."&out_trade_no=".$orderId."&body=".$orderId."&total_fee=".$orders['order_sumPrice']."&notify_url=".$wxconfig['notify_url']."&showwxpaytitle=1'</script>";
			}elseif($orders['supportmetho']==4)
			{ //支付宝个人收款主页收款
				$modpayset = M('setting');
				$alipayhome = $modpayset->where("name='alipayhome'")->getField('data');
				echo "<script>location.href='$alipayhome'</script>";exit;
			}
		}
		else
		{
			$this->redirect('User/index');
		}
		$alipay=M('pay')->where(array('pay_type'=>'alipay'))->find();
		$this->assign('alipaystatus',$alipay['status']);
		$wxpay=M('pay')->where(array('pay_type'=>'wxpay'))->find();
		$this->assign('wxpaystatus',$wxpay['status']);
		$this->display();
	}
   public function confirmOrder(){
    	$id = $_POST['id'];
    	$m = M('item_order')->where('id = '.intval($id))->setField('status',4);
    	if($m){
    		echo 1;
    	}else{
    		echo 0;
    	}

    }
    //取消订单
   public function cancel(){
    	$id = $_POST['id'];
    	$m = M('item_order')->where('id = '.intval($id))->setField('status',5);
    	if($m){
    		echo 1;
    	}else{
    		echo 0;
    	}

    }


    //订单取消 退款
    public function alert_confirm(){
        $id = $_POST['id'];
        $item_order = M('item_order')->where('id = '.intval($id))->find();
        if($item_order){
            $info = M('item_order')->where('id = '.intval($id))->find();
            $data['appid'] = APPID_INDEX;
            $data['mch_id'] = 1310282001;//商户号
            $data['nonce_str'] = $this->get_Rand(16);
            //$data['transaction_id'] = "1010040396201508310753258655";
            $data['out_trade_no'] = $info['orderId'];///商户订单号  微信支付成功返回的out_trade_no
            $data['out_refund_no'] = $this->get_Rand(10); // 退款单号
            $data['total_fee'] = $info['order_sumPrice']*100;//100 代表一元钱
            //$data['total_fee'] = 1;//100 代表一元钱
            $data['refund_fee'] = $info['order_sumPrice']*100;//100  代表一元钱 */
            //$data['refund_fee'] = 1;//100  代表一元钱
            $data['op_user_id'] = 1310282001;
            $data['sign'] = $this->sign($data);
            $arr = $this->curl_tixian($this->gen_xml($data));
            if($arr['msg'] == 'OK' && $arr['code'] =='SUCCESS' && $arr['transaction_id'] != ''){
                //$map['transaction_id'] = $arr['transaction_id'];
                $res= M('item_order')->where('id='.intval($_POST['id']))->setField('delete',1);
                      M('order_detail')->where('orderId='.$info['orderId'])->setField('delete',1);
                if($res){
                    echo 1;
                }else{
                    echo 0;
                }

            }else{
                echo 0;
            }
            die;
        }else{
            echo 0;
            die;
        }

    }

    //发红包返利
    public function SendRedEnvelope(){
        $id = $_POST['id'];  //折扣订单id
        $join = array(
            'a left join __MERCHANT__ b on a.sid = b.id',
            'left join __RETURN_MONEY__ c on b.rm_id = c.id'
            );
        $fiel= 'a.*,b.rm_id,c.price,c.score as cscore';
        $return_integral = M('return_integral')->join($join)->field($fiel)->where(array('a.id'=>$id,'a.status'=>'0'))->select();
        $price = ($return_integral[0]['score']*$return_integral[0]['price'])/$return_integral[0]['cscore'];
        $price = number_format($price, 2, '.', '');
        $data['nonce_str'] = $this->get_Rand(32);
        $data['mch_id'] = '1310282001';
        $data['mch_billno'] = '1310282001'.date(Ymd).$this->NoRand();
        $data['wxappid'] = APPID_INDEX;
        $data['nick_name'] = "消费折扣奖励";
        $data['send_name'] = "消费折扣奖励";
        $data['re_openid'] = $this->get_openids();
        $data['total_amount'] =  $price * 100;
        $data['min_value'] = "1";//1代表 一块钱
        $data['max_value'] =  "200";//1代表 一块钱
        $data['total_num'] = 1;
        $data['wishing'] = "感谢您对我们的支持";
        $data['client_ip'] = "139.129.194.124";
        $data['act_name'] = "红包提现";
        $data['remark'] = "还有更多红包速来兑换";
        $data['logo_imgurl'] = "http://".$_SERVER['SERVER_NAME'].__ROOT__."/data/upload/level/1512/02/5666a971b7711.jpg";
        $data['logo_imgurl'] = "http://".$_SERVER['SERVER_NAME'].__ROOT__."/data/upload/level/1512/02/5666a971b7711.jpg";
        $data['share_content'] = "";
        $data['share_url'] = "http://".$_SERVER['SERVER_NAME'].__ROOT__."/data/upload/level/1512/02/5666a971b7711.jpg";
        $data['share_imgurl'] = "http://".$_SERVER['SERVER_NAME'].__ROOT__."/data/upload/level/1512/02/5666a971b7711.jpg";
        $data['sign'] = $this->sign($data);
        $j = $this->curl_hongbao($this->gen_xml($data));

        if((string)$j->return_code == 'SUCCESS' && (string)$j->result_code == 'SUCCESS'){
            M('return_integral')->where('id='.intval($_POST['id']))->setField('status',1);
            echo 1;
        }else{
            echo 0;
        }


       // return $result;
    }

    function curl_hongbao( $vars, $second=30,$aHeader=array())
    {
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
        $ch = curl_init();
        //超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        //这里设置代理，如果有的话
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
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
        curl_close($ch);
        $j = simplexml_load_string($tmpInfo);
        //(string)$j->return_msg;
        return $j;

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
        $sign_data.='&key=ABCDEX4654646465F4DS654F6SD4F6SD';
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


    //删除订单
    public function delete_wu(){
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

    //删除折扣订单
    public function delete_zhe(){
        $id = $_POST['id'];
        $m = M('return_integral')->where('id = '.intval($id))->setField('delete',1);
        if($m){
            echo 1;
        }else{
            echo 0;
        }

    }

	public function payYuer()
	{
		$item_order=M('item_order');
		$orderId=$_GET['orderId'];//订单号
		$userId=$this->visitor->info['id'];
		$orders=$item_order->where("userId='$userId' and orderId='$orderId'")->find();
		if(!is_array($orders))
		$this->_404();
		$this->assign('orderid',$orders['id']);//订单ID
		$this->assign('dingdanhao',$orders['orderId']);//订单号
		$this->assign('order_sumPrice',$orders['order_sumPrice']);//订单金额
		//读取会员帐户余额
		$user = M('user');
		$userinfo = $user->where("id='$userId'")->find();
		$this->assign('user_yuer',$userinfo['user_account']);
		if($orders['order_sumPrice'] > $userinfo['user_account'])//如果订单金额大于帐户余额
		{
			$tsmsg = "提示：您的帐户余额已不足。<a href='javascript:void(0)'>请点击充值</a>";
			$this->assign('tsmsg',$tsmsg);
		}
		$this->display();
	}
	
	public function payYuerSubmit()
	{
		$user_info = M('user');
		$user_acclog  = M('user_acclog');
		$item_order=M('item_order');
		
		$orderid=$_POST['orderid'];
		$dingdanhao=$_POST['dingdanhao'];
		$userId=$this->visitor->info['id'];
		$order_info = $item_order->where("userId='$userId' and orderId='$dingdanhao'")->find();
		!$order_info && $this->_404();
		//读取会员帐户余额
		$user = M('user');
		$userinfo = $user->where("id='$userId'")->find();
		if($order_info['order_sumPrice'] > $userinfo['user_account'])
		{
			$this->error('对不起，您的余额不足，请充值');
		}else{
			//更新用户帐户
			$user_data['user_account'] = $userinfo['user_account']-$order_info['order_sumPrice'];
			$user_info->where("id=$userId")->save($user_data);
			//添加帐户记录
			$log_data['userid']		= $userId;
			$log_data['username']	= $userinfo['username'];
			$log_data['fl']			= 1;
			$log_data['jiner']		= $order_info['order_sumPrice'];
			$log_data['addtime']	= time();
			$log_data['info']		= '支付成功！订单号：'.$dingdanhao;
			$log_data['orderid']	= $userId.date("YmdHis",time()).rand(1, 99);
			$log_data['status']		= '成功';
			$user_acclog->add($log_data);
			//更新订单状态 支付时间
			$order_data['status']		= 2;
			$order_data['support_time']	= time();
			$item_order->where("orderId='$dingdanhao'")->save($order_data);
			$this->success('订单支付成功，请等待发货!!!', U('User/index'));
		}
		
	}
/*=======================by lyye 2014-03-29=======================*/
	
	public function  getFree($type)
	{
		import('Think.ORG.Cart');
       $cart=new Cart();	
		$money=0;
		$items=M('item');
		
		$method=array(1=>'pingyou',2=>'kuaidi',3=>'ems');
		//echo $method[$type];exit;
		foreach ($_SESSION['cart'] as $item)
		{
			$free= $items->field('free,pingyou,kuaidi,ems')->where('free=2')->find($item['id']);
			if(is_array($free))
			{
				$money+=$free[$method[$type]];
				
			}
		}
		return $money;
	}
	
	/**
	*@wxpay config
	* 微信基本配置
	*/
	public function wxconfig(){
	$wxpay=M('pay')->where(array('pay_type'=>'wxpay'))->find();
	$wxpayconfig=unserialize($wxpay['config']);
	$wxpobj['appId'] = $wxpayconfig['appid'];
	$wxpobj['appsecret'] = $wxpayconfig['appsecret'];
	$wxpobj['signkey'] = $wxpayconfig['signkey'];
	$wxpobj['partnerid'] = $wxpayconfig['partnerid'];
	$wxpobj['partnerkey'] = $wxpayconfig['partnerkey'];
	$wxpobj['notify_url']="http://".$_SERVER['SERVER_NAME'].__ROOT__."/api/wxpay/notifyurl.php";
	$wxpobj['signType'] = 'SHA1';
	$wxpobj['bank_type'] = 'WX';
	$wxpobj['fee_type'] = '1';
	$wxpobj['spbill_create_ip']=get_client_ip();
	$wxpobj['input_charset']='UTF-8';
	return $wxpobj;
	}
	
	public function order_xx(){
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
//            $arr[$v['cid']]['m_title'] = $v['ctitle'];
        }

		$this->assign('order',$order);
		$this->assign('list',$order_info);
        $this->assign('arr',$arr);
        $this->assign('p_price',$p_price);
		$this->assign('num',$num);
		$this->display();
	}



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
//            $arr[$v['cid']]['m_title'] = $v['ctitle'];
        }

        $this->assign('order',$order);
        $this->assign('list',$order_info);
        $this->assign('arr',$arr);
        $this->assign('p_price',$p_price);
        $this->assign('num',$num);
        $this->display();
    }


    public function item_comment(){
        if(IS_POST){
            $data['uid'] =   $this->user['id'];
            $data['uname'] = $this->user['name'];
            $data['cover'] = $this->user['cover'];
            $data['startcomment'] = $_POST['startcomment']; //评价星级
            $data['info'] =  $_POST['info'];  //评价内容
            $data['add_time'] = time();  //评价时间
            $oid = $_POST['order_id'];
            $order = M('item_order')->where('id = '.$oid)->find();
            $join = array(
                'a left join __ITEM__ b on a.itemId = b.id',
                'left join __MERCHANT__ c on b.sid = c.id'
            );
            $field= 'a.*,b.sid,c.title as ctitle,c.id as cid';
            $order_info = M('order_detail')->field($field)->where("a.orderId = '".$order['orderId']."'")->join($join)->select();

            $data['merchant_id'] = $order_info[0]['cid']; //商家id
            $data['merchant_title'] = $order_info[0]['ctitle']; //商家名称
            if($_GET['type']){
                $url = U('Order/index',array('status'=>4));
            }else{
                $url = U('Order/order_tuan',array('status'=>4));

            }

            if(isset($_POST['code'])) {
                if ($_POST['code'] == $_SESSION['code']) {
                    //重复提交订单
                }else{
                    $_SESSION['code'] = $_POST['code']; //存储code
                    if(false != M('item_comment')->add($data)){
                        //修改订单 评价状态
                        $item_order['ping'] = 1;//已评价
                        M('item_order')->where(array('id'=>$oid))->save($item_order);
                        echo "<script>alert('评价成功');window.location.href='".$url."'</script>";
                    }else{
                        echo "<script>alert('评价失败');window.location.href='".$url."'</script>";

                    }

                }
            }

        }
        $this->display();

    }


    public function item_comment_zhe(){
        if(IS_POST){
            $data['uid'] =   $this->user['id'];
            $data['uname'] = $this->user['name'];
            $data['cover'] = $this->user['cover'];
            $data['startcomment'] = $_POST['startcomment']; //评价星级
            $data['info'] =  $_POST['info'];  //评价内容
            $data['add_time'] = time();  //评价时间
            $oid = $_POST['id'];
            $return_integral = M('return_integral')->where(array('id'=>$oid))->find();
            $data['merchant_id'] = $return_integral['sid']; //商家id
            $data['merchant_title'] = $return_integral['sname']; //商家名称
            $url = U('Order/order_zhe');
            if(isset($_POST['code'])) {
                if ($_POST['code'] == $_SESSION['code']) {
                    //重复提交订单
                }else{
                    $_SESSION['code'] = $_POST['code']; //存储code
                    if(false != M('item_comment')->add($data)){
                        //修改订单 评价状态
                        $item_order['ping'] = 1;//已评价
                        M('return_integral')->where(array('id'=>$oid))->save($item_order);
                        echo "<script>alert('评价成功');window.location.href='".$url."'</script>";
                    }else{
                        echo "<script>alert('评价失败');window.location.href='".$url."'</script>";

                    }

                }
            }

        }
        $this->display();

    }


    //生产8位随机数
    public function NoRand($begin=10,$end=99,$limit=2){
        $rand_array=range($begin,$end);
        shuffle($rand_array);
        $list = array_slice($rand_array,0,$limit);
        foreach ($list as $value) {
            $string .= $value;
        }
        return $string;
    }
}