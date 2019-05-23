<?php
class ConsumeAction extends  membersAction {
	public function _initialize(){
        parent::_initialize();
        $this->_mod = D('admin');


    }
    public function hy_xf(){

         $nmber_info =  M('member')->where(array('id'=>I('get.id','','intval')))->field('vipnum')->find();

      $hyxf = M('goods_order')->join('weixin_goods on weixin_goods_order.goodsid = weixin_goods.id')->where
        ('weixin_goods_order.status = 1 and weixin_goods_order.kahao ='.$nmber_info['vipnum'])->select();
        $hyxf1 = M('goods_order')->group('number')->order('addtime desc')->select();
        foreach($hyxf1 as $k=>$v){
            $hyxf2[$k] = M('goods_order')->join('weixin_goods on weixin_goods_order.goodsid = weixin_goods.id')->where
            ('weixin_goods_order.status = 1 and weixin_goods_order.kahao ='.$nmber_info['vipnum'].' and weixin_goods_order.number ='
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


    public function fhy_xf(){

        $nmber_info =  M('member')->where(array('id'=>I('get.id','','intval')))->find();


        $hyxf = M('goods_order')->join('weixin_goods on weixin_goods_order.goodsid = weixin_goods.id')->where
        ("weixin_goods_order.status = 1 and weixin_goods_order.openid ='".$nmber_info['openid']."'")->select();
/*        $hyxf1 = M('goods_order')->group('number')->select();
        foreach($hyxf1 as $k=>$v){
            $hyxf2[$k] = M('goods_order')->join('weixin_goods on weixin_goods_order.goodsid = weixin_goods.id')->where
            ("weixin_goods_order.status = 1 and weixin_goods_order.kahao ='' and weixin_goods_order.xingming = '".$nmber_info['name']."' and weixin_goods_order.dianhua = '".$nmber_info['tel']."' and weixin_goods_order.number ="
                .$v['number'])
                ->select();
            if(empty($hyxf2[$k])){
                unset($hyxf2[$k]);
            }
        }*/
//
//    $hyxf1 = M('goods_order')->group('number')->select();
//        foreach($hyxf1 as $k=>$v){
//            $hyxf2[$k] = M('goods_order')->join('weixin_goods on weixin_goods_order.goodsid = weixin_goods.id')->where
//            ("weixin_goods_order.status = 1 and  weixin_goods_order.openid = '".$nmber_info['openid']."'")
//            ->select();
//            if(empty($hyxf2[$k])){
//                unset($hyxf2[$k]);
//            }
//        }
//
//        $arr = 0;
//        $i=0;
//        $aaa = array();
//        foreach($hyxf2 as $key=>$val){
//            $i+=1;
//            foreach($val as $k=>$v){
//                $arr +=$v['price']*$v['num'];
//                $aaa[$i] += $v['price']*$v['num'];
//            }
//        }


        $hyxf = M('goods_order')->join('weixin_goods on weixin_goods_order.goodsid = weixin_goods.id')->where
        ("weixin_goods_order.status in (1,2) and weixin_goods_order.openid = '".$this->get_openid()."'")->select();
        $hyxf1 = M('goods_order')->group('number')->order('addtime desc')->select();
        foreach($hyxf1 as $k=>$v){
            $hyxf2[$k] = M('goods_order')->join('weixin_goods on weixin_goods_order.goodsid = weixin_goods.id')->where
            ("weixin_goods_order.status in (1,2) and weixin_goods_order.openid = '".$this->get_openid()."' and
            weixin_goods_order.number ='".$v['number']."'")->select();
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




}