<?php
// 本类由系统自动生成，仅供测试用途
header("Content-type: text/html; charset=utf-8");
class ShopcartAction extends frontendAction {

	public function _initialize() {
		parent::_initialize();
		$this->get_user_info(1);
	}
    public function index(){
        $_SESSION['foot_id'] = $_GET['foot_id'];

        if(empty($this->openid)){
            $this->redirect(U('Member/login'));
        }
	    $uid = $this->user['id'];
        //p($uid);
	    $list = M('card')->where("uid = ".intval($uid))->select();
        //p($list);
	    $num = M('card')->where("uid = ".intval($uid))->count();
	    $total = 0;
	    foreach ($list as $k=>$v){
            //p($v['merchant_id']);
//	    	$list[$k]['item'] = M('item')->where('id = '.intval($v['item_id']))->find();
            //$list[$k]['merchant_id'] = M('merchant')->where('id = '.intval($v['merchant_id']))->field('id,title')->find();
	    	$total += $v['price'];
	    	
	    }

        $lists = array();
        foreach($list as $key=>$val){
            //p($val['item_id']);
            $lists[$val['merchant_id']] = M('merchant')->where('id = '.intval($val['merchant_id']))->field('id,title')->find();
        }
        foreach($list as $key=>$val){
            if($val['guige']){
                $join = 'a left join __CARD__ b on   b.item_id = a.id';
                $lists[$val['merchant_id']]['goods'][] = M('item')->where(array('a.id'=>intval($val['item_id']),'b.uid' => $uid,'b.guige'=>$val['guige']))->join($join)->find();

            }else{
                $join = 'a left join __CARD__ b on   b.item_id = a.id';
                $lists[$val['merchant_id']]['goods'][] = M('item')->where(array('a.id'=>intval($val['item_id']),'b.uid' => $uid))->join($join)->find();

            }

        }
	    $this->assign('total',$total);
	    $this->assign("num",$num);
	    $this->assign('item',$lists);
	    $this->display();
    }
    
    //添加进购物车
    public function add_cart(){
    	$uid = $this->user['id'] ;
    	$data['uid'] = $uid;
    	$data['add_time'] = time();
    	$data['item_id'] = $this->_post('goodId', 'intval');//商品ID
 		$item = M('item')->where('id = '.intval($data['item_id']))->find();
        $data['merchant_id'] = $item['sid'] ;
        $data['num'] = $this->_post('num', 'intval');//商品数量 ;
        $data['price'] = $this->_post('price', 'trim');  //商品价钱 ;
        $data['guige'] = $this->_post('guige', 'trim');  //商品规格 ;


        if(!is_array($item)){
    		echo '不存在该商品';
    	}elseif($item['goods_stock']<$data['num']){
    		echo "库存不足";
    	}else {
            if($data['guige']){
                $res = M('card')->where('uid = '.intval($uid).' and item_id = '.intval($data['item_id'])." and guige = '".$data['guige']."'")->find();
                if($res){
                    $data_num['num'] = $res['num'] + $data['num'];
                    M('card')->where('uid = '.intval($uid).' and item_id = '.intval($data['item_id'])." and guige = '".$data['guige']."'")->save($data_num);
                    echo '已放入购物车';
                }else {
                    $c = M('card')->add($data);
                    if ($c) {
                        echo '已放入购物车';
                    }

                }
            }else{
                $res = M('card')->where('uid = '.intval($uid).' and item_id = '.intval($data['item_id']))->find();
                if($res){
                    $data_num['num'] = $res['num'] + $data['num'];
                    M('card')->where('uid = '.intval($uid).' and item_id = '.intval($data['item_id']))->save($data_num);
                    echo '已放入购物车';
                }else {
                    $c = M('card')->add($data);
                    if ($c) {
                        echo '已放入购物车';
                    }

                }
            }

    	}
    }

    public function add_num(){
        $id = I('post.id');
        $num['num'] = I('post.num');
        if(M('card')->where(array('id'=>$id))->save($num)){
            echo 1;
        }else{
            echo 0;
        }

    }
    
    public function jiesuan(){
        if($_GET['mk']){
            $ids = $_GET['mk'];
        }else{
            $data = $_POST;
            if(!$data['subBox']){
                $this->error('请选择要结算的商品');
            }
            $ids = implode(',', $data['subBox']);
        }
    	$uid = $this->user['id'];
    	$list = M('card')->where("id in({$ids})")->select();
//    	$num = M('card')->where("id in({$ids})")->count();
        $address = M('user_address')->where('status = 1 and uid = '.intval($uid))->find();
        $address['saddress'] = $this->get_address($address['pid'],$address['cid'],$address['aid']);
	    $total = 0;
	    $num = 0;

	    foreach ($list as $k=>$v){
	    	$number = 'number'.$v['id'];
	    	$list[$k]['item'] = M('item')->where(array('id' => $v['item_id'],'guige' =>$v['guige']))->find();
	    	$list[$k]['num']  = $v['num'];
	    	$list[$k]['price'] = $v['price'];
            $list[$k]['item']['price']  = $v['price'];
            $list[$k]['guige'] = $v['guige'];
	    	$price = $v['price']* $list[$k]['num'];
	    	$total += $price;
	    	$num = $num + $v['num'];

	    }
        $lists = array();
        foreach($list as $key=>$val){
            $lists[$val['merchant_id']] = M('merchant')->where('id = '.intval($val['merchant_id']))->field('id,title,jing,wei,start_time,end_time,xstart_time,xend_time')->find();
            }

        foreach($list as $key=>$val){
            $lists[$val['merchant_id']]['goods'][$key] = M('item')->where('id = '.intval($val['item_id']))->find();
            $number = 'number'.$val['id'];
            $lists[$val['merchant_id']]['goods'][$key]['num'] = $val['num'];
            $lists[$val['merchant_id']]['goods'][$key]['price'] = $val['price'];
            $lists[$val['merchant_id']]['goods'][$key]['guige'] = $val['guige'];

        }

        $this->assign('mk',$ids);
    	$this->assign("address",$address);
	    $this->assign('total',$total);
	    $this->assign("num",$num);
	    $this->assign('item',$lists);
    	$this->display();
    }
    public function remove_cart_item()//删除购物车商品
    {
    	import('Think.ORG.Cart');// 导入购物车类
    	  $cart=new Cart();
    	
    	$goodId= $this->_post('itemId', 'intval');//商品ID
    	 $cart->delItem($goodId);
    	$data=array('status'=>1);
    	echo json_encode($data);
    }
    
    public function change_quantity()
    {
    	import('Think.ORG.Cart');// 导入购物车类
        $cart=new Cart();
    	  
    	$itemId= $this->_post('itemId', 'intval');//商品ID
    	$quantity= $this->_post('quantity', 'intval');//购买数量
    	$seid= $this->_post('seid','intval');//sessionID
        //dump($seid);exit;
    	$item=M('item')->field('goods_stock')->find($itemId);
    	if($item['goods_stock']<$quantity)
    	{
    	$data=array('status'=>0,'msg'=>'该商品的库存不足');
    	}else {
    	$cart->modNum($seid,$quantity);
    	$data=array('status'=>1,'item'=>$cart->getItem($seid),'sumPrice'=>$cart->getPrice());
    	}
    	
    
    	echo json_encode($data);
    }


	public function delete(){
		$id = I('post.id');
		if(M('card')->where(array('id'=>$id))->delete()){
			echo 1;
		}else{
			echo 0;
		}
	}
}