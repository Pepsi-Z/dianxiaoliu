<?php
class JifenAction extends frontendAction {
	public function _initialize() {
		parent::_initialize();
		$this->get_user_info(1);
	}
    public function index() {
        if (empty($_SESSION['user'])) {

            $this->redirect('Member/login');
        }
        $mod = M('score_item');
        $count = $mod->where('stock >0')->count();
        $pager = new Page($count, 20);
        //库存必须大于0的
        $list = $mod->where('stock >0')->limit($pager->firstRow.','.$pager->listRows)->select();
        $this->assign('list',$list);
        $this->assign('page',$pager->show());
        $this->display();
    }
	public function jifen(){
		$uid = $this->user['id'];
		$id = $this->_get('id','intval');
		$mod = M('score_item');
		$address = M('user_address')->where('uid = '.intval($uid))->select();
		$addr = '';
		if($address){
			foreach ($address as $k=>$v){
				$address[$k]['saddress'] = $this->get_address($v['pid'], $v['cid'], $v['aid']);
				$address[$k]['sa'] = $address[$k]['saddress'].$v['mobile'];
				if($v['status'] == 1){
					$checked = 'checked';
				}else{
					$checked = '';
				}
				$addr .="<label><input type=\"radio\" name=\"addressid\" value=\"".$v['id']."\" ".$checked."/>".$address[$k]['sa']."</label><br>";
				
			}
		}else{
			$url = U('User/addresschange');
			$addr = "<label>您还没有收货地址快去<b><a href='".$url."'>添加</a></b>吧</label>";
            $status = 1;
		}
		
		$province = M('area')->where("pid = 0")->select();
	    $this->assign("province",json_encode($province));
		$this->assign('addr',json_encode($addr));   //平凑的收货地址
        $this->assign('status',json_encode($status));
        $this->assign('address',$address);
		$info = $mod->where('id = '.intval($id))->find();
		$this->assign("info",$info);
    	$this->display();

    }
    //AJAX获取城市
    public function ajax_get_city(){
    	$pid = $_POST['id'];
		$citys = M('area')->where("pid = {$pid}")->select();
		if($citys){
			$res['status'] = 1;
			
		}else{
			$res['status'] = 0;
			
		}
		$res['data'] = $citys;
		echo json_encode($res);
    
    }
    public function duihuan(){

    	$uid = $this->user['id'];
    	$score_order=M('score_order');
    	$data['order_sn'] = 'JF-'.date('YmdHis').time();   //订单编号
    	$data['uid'] = $uid;
    	$data['uname'] = $this->user['name'];
    	$data['item_id'] = $this->_post('item_id','trim'); //积分商品id
    	$data['item_name'] = $this->_post('item_name','trim');//积分商品名字
    	$data['item_num'] = $this->_post('item_num','trim');//购买积分商品数量
    	$data['addressid'] = $this->_post('addressid','trim') ;  //地址id
    	$address = M('user_address')->where('id = '.$data['addressid'])->find();
    	$data['consignee'] = $address['consignee'];  //收件人
    	$data['mobile'] = $address['mobile'];   //收件人电话
    	$data['score'] = $this->_post('order_score','trim'); //积分
    	$data['order_score'] = $data['score']*$data['item_num']; //总积分

    	$data['add_time'] = time();
    	$kc = M('score_item')->where(" id =".$data['item_id'])->getField('stock');
    	if($kc < $data['item_num']){
    		$this->error("库存不足");
    	}
    	if(!$data['addressid']){
    		$this->error('请先选择收货地址');
    	}
    	if($data['order_score']>$this->user['score']){
    		$this->error("您的积分不足");
    	}
        //积分订单
    	$a = M('score_order')->add($data);
    	if($a){
    		$arr['uid'] = $uid;
    		$arr['uname'] = $this->user['name'];
    		$arr['score'] = -$data['order_score'];
    		$arr['add_time'] = time();
            $arr['total'] = ($this->user['score']-$data['order_score']);
            $arr['action'] = '积分兑换';
            //用户积分日志
    		if((M('score_log')->add($arr))!== false){
    			//改变用户积分
    			$uscore = $this->user['score'];
    			$sc['score'] = $uscore - $data['order_score'];
    			M('user')->where('id = '.intval($uid))->save($sc);
    			//改变商品库存、
    			$kk['stock'] = $kc - $data['item_num'];
    			$kk['buy_num'] = $data['item_num'];//卖出去的数量
    			M('score_item')->where(" id =".$data['item_id'])->save($kk);
    		}
    		$this->success('兑换成功',U('User/index'));
    	}
    }
    public function order(){
        if (empty($_SESSION['user'])) {

            $this->redirect('Member/login');
        }
    	$score_list = M('score_order')->where('uid = '.intval($this->user['id']))->order('status asc,id desc')->select();
    	foreach ($score_list as $k=>$v){
    		$score_list[$k]['item'] = M('score_item')->where("id = ".$v['item_id'])->find();
    	}
    	$this->assign('score_list',$score_list);
    	$this->display();
    }
    
    public function confirmOrder(){
    	$id = $_POST['id'];
    	$m = M('score_order')->where('id = '.intval($id))->setField('status',3);
    	if($m){
    		echo 1;
    	}else{
    		echo 0;
    	}
    	
    }

	
	public function order_xx(){
		$id = $this->_get('id','intval');
    	$score_info = M('score_order ')->table('weixin_score_order a ')->join('weixin_score_item b on a.item_id = b.id')->where('a.id = '.intval($id))->find();
	
    	$address = M('user_address')->where('id = '.intval($score_info['addressid']))->find();
		$user_address = $this->get_address($address['pid'],$address['cid'],$address['aid']);
		$score_info['xx_address'] = $user_address.' '.$address['address'];
    	$this->assign('score_info',$score_info);
    	$this->display();
    }

}