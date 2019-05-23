<?php

class IndexAction extends frontendAction {
    public function _initialize() {
        parent::_initialize();
        $this->get_user_info(0);
        /*$_SESSION['user'] = '';*/
    }



    public function index() {
        //p($_SESSION['jing']);
        //p($_SESSION['wei']);
        $_SESSION['foot_id'] = $_GET['foot_id'];
        //获取城市
        $id = I('get.id');
        if($id){
            $name = M('region')->where(array('id'=>$id))->getField('name');
            $_SESSION['city_name'] = $name;
            $this->assign('name',$name);

        }

        /*****首页广告***/
        $ad= M('ad');
        $ads= $ad->field('url,content,desc')->where('pid=0 and status=1')->order('ordid asc')->select();
        $this->assign('ad',$ads);
        /*****首页广告end******/

        //平台分类
        $item_list = M('item_cate')->where(array('pid'=>0))->order('ordid asc')->select();
        $this->assign('item_list',$item_list);
        /* p($item_list);*/

        /****小六推荐*****/
        $wherenews=array('a.tuijian'=>1);
        $tuijian=$this->getItem($wherenews,3,'tuijian');
        $this->assign('tuijian',$tuijian);
        /****小六推荐 END*****/

        /****猜你喜欢*****/
        $wherenews=array('a.love'=>1);
        $love_list=$this->getItem($wherenews,10,'love');
        //p($love_list);
        $this->assign('love',$love_list);


        if(!empty($_GET['from'])){
            $url = "http://".$_SERVER['SERVER_NAME'].__APP__."?m=Index&a=index&foot_id=1&openid=".$_GET['openid']."&from=".$_GET['from']."&isappinstalled=".$_GET['isappinstalled'];
        }else{
            $url = "http://".$_SERVER['SERVER_NAME'].__APP__."?m=Index&a=index&foot_id=1&openid=".$_GET['openid'];
        }

        $this->wx_data = $this->get_wx_config($url);
        $this->assign('zf_urls',$url);
        $this->assign('zf_title','凡事帮平台');
        //$this->assign('zf_img',"http://".$_SERVER['SERVER_NAME'].__ROOT__.'/'.attach(get_thumb(C('pin_weixin_img'), ''), 'weixin'));
        $this->assign('zf_ms','如需帮助,请找凡事帮');
        $this->display();

    }

    //微信分享朋友圈 朋友
    public function ajaxshare(){
        if($this->user['id']){
            $fenx_score = C('pin_fenxiang');
            $data['uid'] = $this->user['id'];
            $data['username'] =  $this->user['username'];
            $data['add_time'] =  time();
            $data['total'] =  ($this->user['score']+$fenx_score);
            //分享送积分
            $log['uname'] =  $this->user['username'];
            $log['score'] = (+$fenx_score);
            $log['uid'] = $this->user['id'];
            $log['add_time'] =  time();
            $log['total'] =  ($this->user['score']+$fenx_score);
            $log['action'] =  '分享';
            //增加积分日志
            if(M('score_log')->add($log)){
                $kk['score'] = ($this->user['score']+$fenx_score);
                M('user')->where(array('id'=>$this->user['id']))->save($kk); //更新积分
                echo 1;  //分享送积分成功
                die;
            }else{
                echo 0;  //分享送积分失败
                die;
            }
        }



    }

    //自动获取地理位置
    public function get_city_name(){
        $jing = I('post.jing');
        $wei = I('post.wei');
        $_SESSION['jing'] = number_format($jing,6)+0.012744;
        $_SESSION['wei']  = number_format($wei,6) + 0.008446;
        $adr = $this->getCityByJWd( $_SESSION['jing'],$_SESSION['wei']);
        $_SESSION['address']  = $adr['address'];
        $_SESSION['city']  = $adr['city'];
        $_SESSION['city_name'] = $adr['city'];
        echo json_encode($adr);
    }

    //省province
    public function province(){
        $list="SELECT * FROM weixin_region where pid=1  GROUP BY piyin order by piyin";
        $lists=M()->query($list);
        foreach($lists as $k=>$v){
            $lis[$k]['na']=M('region')->where("piyin='".$lists[$k]['piyin']."' and pid=1")->order('name desc')->select();
            $lis[$k]['sou']=$lists[$k]['piyin'];
        }
        $this->assign('lists',$lis);
        $this->display();
    }
    //市
    public function city(){
        $pid = I('get.pid');
        $list="SELECT * FROM weixin_region where pid = ".$pid."  GROUP BY piyin order by piyin";
        $lists=M()->query($list);
        foreach($lists as $k=>$v){
            $lis[$k]['na']=M('region')->where("piyin='".$lists[$k]['piyin']."' and pid = ".$pid)->order('name desc')->select();
            $lis[$k]['sou']=$lists[$k]['piyin'];
        }
//        p($lis);die;
        $this->assign('lists',$lis);
        $this->display();
    }


    public function hot(){
        $wherenews=array('tuijian'=>1);
        $hot=$this->getItem($wherenews,8);
        $level = $this->user['level'];
        foreach($hot as $k=>$v){
            //当前等级的折扣
//            $discount = ($level == '0')?'discount':"discount".$level;
//            $discountp = M('item_cate')->where('id = '.$v['cate_id'])->getField($discount);
            //当前等级的价格
            $pricelevel = 'price'.$level;
            $hot[$k]['price'] = $v[$pricelevel]  ;
            if($v['end_time']>time()){

                 $hot[$k]['price'] = $v['xs_price'] ;
           }
        }
        $this->assign('hot',$hot);
        $this->display();
    }
    public function getItem($where = array(),$limit,$name)
    {
        $where_init = array('a.status'=>'1');
        $where =array_merge($where_init, $where);
        $join= array('a left join __MERCHANT__ b ON a.sid = b.id');
        $field='a.*,b.chang';
        if(empty($limit))
        {
            $item=M('item')->join($join)->where($where)->field($field)->select();
            foreach($item as $k=>$v){
                $merchant =  M('merchant')->where('id = '.$v['sid'])->order('ordid asc')->find();
                //125.302757,43.885791当前位置
                $item[$k]['merchant_title'] = $merchant['title'];
                $juli = $this->getdistance($_SESSION['jing'],$_SESSION['wei'],$merchant['xjing'],$merchant['xwei']);
                $item[$k]['min'] = $juli*0.001;

            }
            return $item;
        }else{
            if($name == 'tuijian'){
                $item=M('item')->join($join)->where($where)->order('tordid asc')->field($field)->limit($limit)->select();
            }else{
                $item=M('item')->join($join)->where($where)->order('lordid asc')->field($field)->limit($limit)->select();
            }

            foreach($item as $k=>$v){
                $merchant =  M('merchant')->where('id = '.$v['sid'])->order('ordid asc')->find();
                //125.302757,43.885791当前位置
                $item[$k]['merchant_title'] = $merchant['title'];
                $juli = $this->getdistance($_SESSION['jing'],$_SESSION['wei'],$merchant['xjing'],$merchant['xwei']);
                $item[$k]['min'] = $juli*0.001;

            }
            return $item;


        }
    }


    public function ajaxLogin()
    {

       $user_name=$_POST['user_name'];
       $password=$_POST['password'];

       $user=M('user');
       $users= $user->where("username='".$user_name."' and password='".md5($password)."'")->find();
       if(is_array($users))
       {
    	$data = array('status'=>1);
    	$_SESSION['user_info']=$users;
       }else {
       	$data = array('status'=>0);
       }

    	echo json_encode($data);
    	exit;
    }
    public function ajaxRegister()
    {
    	$username=$_POST['user_name'];
    	$user=M('user');
    	$count=$user->where("username='".$username."'")->find();
    	if(is_array($count))
    	{
        echo 'false';
       // echo json_encode(array('user_nameData'=>true));
    	}else
    	{
    		echo 'true';
        //echo json_encode(array('user_nameData'=>true));
    	}


    }

    //微信菜单
    public function menu(){
        $menu_id = $_GET['type'];
        if($menu_id == 'thr_7'){ //关于小六
            $this->assign('content',C('pin_about'));
            $this->display();

        }else if($menu_id == 'thr_8'){ //联系我们
            $this->assign('content',C('pin_liao'));
            $this->display();

        }else if($menu_id == 'thr_9'){ //使用帮助
            $this->assign('content',C('pin_help'));
            $this->display();

        }



    }


    public function search(){
        $map = array();
        $keyword = $this->_request('keyword', 'trim');
        $map['a.title'] = array('like', '%'.$keyword.'%');
        $join= array('a left join __MERCHANT__ b ON a.sid = b.id');
        $field='a.*,b.chang';
        $goods_list =  M('item')->where($map)->join($join)->field($field)->order('a.ordid asc')->select();
        foreach($goods_list as $k=>$v){
            $merchant =  M('merchant')->where('id = '.$v['sid'])->order('ordid asc')->find();
            //125.302757,43.885791当前位置
            $goods_list[$k]['merchant_title'] = $merchant['title'];
            $juli = $this->getdistance($_SESSION['jing'],$_SESSION['wei'],$merchant['xjing'],$merchant['xwei']);
            $goods_list[$k]['min'] = $juli*0.001;

        }
        $this->assign('search', array(
            'keyword' => $keyword,
        ));
        $this->assign('goods_list',$goods_list);
        $this->display();
    }

    /*//客服
    public function custom(){
        $k_moblie = C('pin_site_tel');
        $openid = $_GET['openid'];
        require_once 'app/Common/MessageUtil.php';
            $str = "亲爱的,如果有任何问题欢迎致电【凡事帮】 客服电话 ".$k_moblie."祝您愉快";
            MessageUtil::sendTextInfo($openid,$str);

    }*/






}