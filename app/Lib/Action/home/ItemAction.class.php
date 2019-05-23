<?php
header("Content-type: text/html; charset=utf-8");
class ItemAction extends frontendAction {

    public function _initialize() {
        parent::_initialize();
        $this->get_user_info(0);
    }
    
    public function wx_index(){
    	$_SESSION['openid'] = $_GET['openid'];
    	$id = $this->_get('id', 'intval');
    	$this->redirect('index', array('id'=>$id));
    }
    public function index(){
    	$_SESSION['foot_id'] = $_GET['foot_id'];
        $level = $this->user['level'];
        $id = $this->_get('id', 'intval');
        $cid = $this->_get('cid', 'intval');

        if($id){
            $where['pid'] = $id;
        }
        if($cid){
           $where['cate_id'] = $cid;
        }
        $where['goods_stock'] = array('gt','0');
        $where['status'] = 1;
        $cate_title = M('item_cate')->where('id = '.intval($id))->getField('name');
        $list = M('item')->where($where)->order('ordid asc')->select();
//        echo  M('item')->_sql();
        foreach($list as $k=>$v){
            //当前等级的价格
         	$pricelevel = 'price'.$level;
            $list[$k]['price'] = $v[$pricelevel]  ;
            if($v['end_time']>time()){
                 $list[$k]['price'] = $v['xs_price'] ;
           	}
        }
        $this->assign('list',$list);
        $this->assign('cate_title',$cate_title);
        $this->display();
    }

   //小六推荐
    public function item_cate(){
        /****小六推荐*****/
        $wherenews=array('a.tuijian'=>1);
        $tuijian=$this->getItem($wherenews);
        $this->assign('tuijian',$tuijian);
        $this->display();
    }


    public function getItem($where = array(),$limit)
    {
        $where_init = array('a.status'=>'1');
        $where =array_merge($where_init, $where);
        $join= array('a left join __MERCHANT__ b ON a.sid = b.id');
        $field='a.*,b.chang';
        if(empty($limit))
        {

            $item=M('item')->join($join)->where($where)->order('a.tordid asc')->field($field)->select();
            foreach($item as $k=>$v){
                $merchant =  M('merchant')->where('id = '.$v['sid'])->order('ordid asc')->find();
                //125.302757,43.885791当前位置
                $item[$k]['merchant_title'] = $merchant['title'];
                $juli = $this->getdistance($_SESSION['jing'],$_SESSION['wei'],$merchant['xjing'],$merchant['xwei']);
                $item[$k]['min'] = $juli*0.001;

            }
            return $item;

        }else{
            return $item=M('item')->join($join)->where($where)->field($field)->order('a.ordid asc')->limit($limit)->select();
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

    //点击平台进入 商家
    public function item_merchant(){
        $_SESSION['foot_id'] = $_GET['foot_id'];
        $id = $_GET['id'];   //平台id
        $map['pid'] = $id;
        $map['status'] = 1;
        //商家特色搜索
        $tid = $this->_request('tid');

        //分类名称

        $keyword = $this->_request('keyword', 'trim');
        if($keyword){
            $map['title'] = array('like', '%'.$keyword.'%');
        }

     /*   if(!empty($tid)){
            if($tid == '全部'){
                $map .= '';
                $tname= '全部';
            }else{
                $map .= " and tid = ".$tid;
                $tname = M('item_cate')->where(array('id' =>$tid))->getField('name');
            }

        }
        //区搜索
        $town = $this->_request('town');
        if(!empty($town)){
                $map .= " and town = ".$town;
                $tname = M('item_cate')->where(array('id' =>$tid))->getField('name');
                $town_name = M("Region")->where(array('id'=>$town,'type'=>3))->getField('name');

        }
        //距离
        $km = $this->_request('km');
        if(!empty($km)){
            if($_SESSION['jing'] && $_SESSION['wei']){
                $squares = $this->returnSquarePoint($_SESSION['jing'], $_SESSION['wei'],$km);
                $map .= " and xwei > {$squares['right-bottom']['lat']}
					  and xwei  < {$squares['left-top']['lat']}
					  and xjing > {$squares['left-top']['lng']}
					  and xjing < {$squares['right-bottom']['lng']}";
            }
            $town_name = $km.'km';
        }

        //折扣
        $z_name = $this->_request('z_name');
        if(!empty($z_name)){
                if($z_name == '最大优惠'){
                    $order = 'discount asc';
                    $zname= '最大优惠';
                }

        }else{

        }

        if(!empty($z_name)){
            if($z_name == '好评'){
                $zname= '好评';
            }

        }

        if(!empty($z_name)){
            if($z_name == '最高人气'){
                $zname= '最高人气';
            }
        }*/

        $this->assign('search', array(
            'keyword' => $keyword,
        ));

        //地区搜索
        $city= $_SESSION['city_name'];
        //$city= '长春';
        $city_id= M("Region")->where(array('name'=>$city))->field('id,name')->find();
        $city_list = M("Region")->where(array('pid'=>$city_id['id'],'type'=>3))->field('id,name')->select();
        $this->assign('city_list',$city_list);


        /*****美食广告***/
        $ad= M('ad');
        $ads= $ad->field('url,content,desc')->where('pid='.$id.' and status=1')->order('ordid asc')->select();
        $this->assign('ad',$ads);
        /*****美食广告end******/
        // 平台 搜素
        $merchant_cate =  M('item_cate')->where(array('pid' =>$id))->field('id,name')->order('ordid asc')->select();
        $this->assign('merchant_cate',$merchant_cate);
        //商家
        $merchant_list = M('merchant')->where($map)->order('ordid asc')->select();
        foreach($merchant_list as $key=>$val){
            //商家评论
            $item_comment = M('item_comment')->where(array('merchant_id'=>$val['id']))->select();
            $total_start = M('item_comment')->where(array('merchant_id'=>$val['id']))->sum('startcomment');
            $start = round($total_start/count($item_comment));
            $merchant_list[$key]['start'] = $start;
            $juli = $this->getdistance($_SESSION['jing'],$_SESSION['wei'],$val['xjing'],$val['xwei']);
            $merchant_list[$key]['min'] = $juli*0.001;
            //购买人数最多
            $buy_num = M('item')->where(array('sid'=>$val['id']))->sum('buy_num');
            $merchant_list[$key]['buy_num'] = $buy_num;

        }

        //好评搜索
      /*  if($z_name == '好评'){
            $newArr=array();
            for($j=0;$j<count($merchant_list);$j++){
                $newArr[]=$merchant_list[$j]['start'];
            }
            array_multisort($newArr,SORT_DESC,$merchant_list);
        }*/
        //p($merchant_list);
        //好评搜索
        /*if($z_name == '最高人气'){
            $newArr=array();
            for($j=0;$j<count($merchant_list);$j++){
                $newArr[]=$merchant_list[$j]['buy_num'];
            }
            array_multisort($newArr,SORT_DESC,$merchant_list);
        }*/
        $this->assign('merchant_list',$merchant_list);
        $this->display();
    }




    //菜单进入
    public function menu_merchant(){
        $_SESSION['foot_id'] = $_GET['foot_id'];
        if($_GET['type'] == '5'){
            //全部折扣
            $map = ' and zhe = 1 ';
        }else if($_GET['type'] == '6'){
            //全部套餐
            $map = ' and tao = 1';

        }else if($_GET['type'] == '7'){
            //全部商家
        };
        $map = 'status = 1 ';
        //全部搜索
        $item_list = M('item_cate')->where(array('pid'=>0))->order('ordid asc')->select();
        $this->assign('item_list',$item_list);
        $pid = $_GET['id'];

        if(!empty($pid)){
            if($_GET['type'] == 5){
                //全部折扣
                $map .= " and pid = ".$pid.' and zhe = 1 ';
                $pname = M('item_cate')->where(array('id' =>$pid))->getField('name');
            }else if($_GET['type'] == '6'){
                //全部套餐
                $map .= " and pid = ".$pid.' and tao = 1 ';
                $pname = M('item_cate')->where(array('id' =>$pid))->getField('name');

            }else if($_GET['type'] == '7'){
                //全部商家
                $map .= " and pid = ".$pid;
                $pname = M('item_cate')->where(array('id' =>$pid))->getField('name');
            };

        }else{
            if($_GET['type'] == 5){
                //全部折扣
                $map .= ' and zhe = 1 ';
                $pname = M('item_cate')->where(array('id' =>$pid))->getField('name');
            }else if($_GET['type'] == '6'){
                //全部套餐
                $map .= ' and tao = 1 ';
                $pname = M('item_cate')->where(array('id' =>$pid))->getField('name');
            }else if($_GET['type'] == '7'){
                //全部商家
                $pname = M('item_cate')->where(array('id' =>$pid))->getField('name');
            };

        }

        //区
        $town = $this->_request('town');
        $tid = $this->_request('tid');

        if(!empty($town)){

            $map .= " and town = ".$town;
            $tname = M('item_cate')->where(array('id' =>$tid))->getField('name');
            $town_name = M("Region")->where(array('id'=>$town,'type'=>3))->getField('name');


        }
        //距离
        $km = $this->_request('km');
        if(!empty($km)){
            if($_SESSION['jing'] && $_SESSION['wei']){
                $squares = $this->returnSquarePoint($_SESSION['jing'], $_SESSION['wei'],$km);
                $map .= " and xwei > {$squares['right-bottom']['lat']}
					  and xwei  < {$squares['left-top']['lat']}
					  and xjing > {$squares['left-top']['lng']}
					  and xjing < {$squares['right-bottom']['lng']}";
            }
            $town_name = $km.'km';
        }

        //折扣
        $z_name = $this->_request('z_name');
        if(!empty($z_name)){
            if($z_name == '最大优惠'){
                $order = 'discount asc';
                $zname= '最大优惠';
            }

        }else{
            $order = 'ordid asc';
        }

        if(!empty($z_name)){
            if($z_name == '好评'){
                $zname= '好评';
            }

        }

        if(!empty($z_name)){
            if($z_name == '最高人气'){
                $zname= '最高人气';
            }
        }

        $this->assign('search', array(
            'pname' => $pname,
            'town_name'=>$town_name,
            'zname'=>$zname
        ));

        //地区搜索
        $city= $_SESSION['city_name'];
        //$city = '长春';
        $city_id= M("Region")->where(array('name'=>$city))->field('id,name')->find();
        $city_list = M("Region")->where(array('pid'=>$city_id['id'],'type'=>3))->field('id,name')->select();
        $this->assign('city_list',$city_list);

        //商家
        $merchant_list = M('merchant')->where($map)->order($order)->select();
        foreach($merchant_list as $key=>$val){
            //商家评论
            $item_comment = M('item_comment')->where(array('merchant_id'=>$val['id']))->select();
            $total_start = M('item_comment')->where(array('merchant_id'=>$val['id']))->sum('startcomment');
            $start = round($total_start/count($item_comment));
            $merchant_list[$key]['start'] = $start;
            $juli = $this->getdistance($_SESSION['jing'],$_SESSION['wei'],$val['xjing'],$val['xwei']);
            $merchant_list[$key]['min'] = $juli*0.001;
            //购买人数最多
            $buy_num = M('item')->where(array('sid'=>$val['id']))->sum('buy_num');
            $merchant_list[$key]['buy_num'] = $buy_num;

        }

        //好评搜索
        if($z_name == '好评'){
            $newArr=array();
            for($j=0;$j<count($merchant_list);$j++){
                $newArr[]=$merchant_list[$j]['start'];
            }
            array_multisort($newArr,SORT_DESC,$merchant_list);
        }
        //p($merchant_list);
        //好评搜索
        if($z_name == '最高人气'){
            $newArr=array();
            for($j=0;$j<count($merchant_list);$j++){
                $newArr[]=$merchant_list[$j]['buy_num'];
            }
            array_multisort($newArr,SORT_DESC,$merchant_list);
        }
        $this->assign('merchant_list',$merchant_list);
        $this->display();
    }



    //获取城区
    public function getRegion()
    {
        $reg = I('get.pid','','intval');
        $Region = M("Region");
        $map['pid'] = $_REQUEST["pid"];
        $map['type'] = $_REQUEST["type"];
        $list = $Region->where($map)->select();
        return $list;
    }



    //获取商家
    public function merchant_info(){
        //商家简介
        $id = I('get.id','intval');  //商家id
        $merchant_list = M('merchant')->where(array('id'=>$id,'status'=>1))->find();
        //商家评论
        $item_comment = M('item_comment')->where(array('merchant_id'=>$id))->select();
        $this->assign('info',$merchant_list);
        //汇总评价星级
        $total_start = M('item_comment')->where(array('merchant_id'=>$id))->sum('startcomment');
        //p($total_start);
       // p(count($item_comment));
        $start = round($total_start/count($item_comment));
        $this->assign('item_comment',$item_comment);
        $this->assign('start',$start);
        $this->display();
    }
    //图文详情
    public function merchant_desc(){
        $id = I('get.id','intval');  //商家id
        $merchant_desc = M('merchant')->where(array('id'=>$id,'status'=>1))->find();
        $this->assign('info',$merchant_desc);
        $this->display();
    }





    public function merchant_goods(){
        $sid  = I('post.sid','intval');
        $where['mid'] = $sid;
        $merchant_goods =  M('item')->where($where)->order('ordid asc')->select();
        echo json_encode($merchant_goods);
        die;


    }

    /**
     * 商品详细页
     */
    public function content(){
        $id = $this->_get('id', 'intval');
        $uid = $this->user['id'];
        !$id && $this->_404();
        $address = M('user_address')->where('status = 1 and uid = '.intval($uid))->find();
        $item = M('item')->where(array('id' => $id, 'status' => 1))->find();
        !$item && $this->_404();
        //幻灯片
        $item_img = M('item_img')->where(array('item_id' => $id, 'status' => 1))->order('ordid asc')->select();
        //$item_comment = M('item_comment')->where('item_id ='.$id)->order('add_time asc')->select();
        $attr_guge = M('item_attr')->where(array('item_id' => $id))->select();
        $this->assign('info',$item);
        $this->assign('attr_guge',$attr_guge);
        $this->assign('item_img',$item_img);
        $this->assign("address",$address);
        $this->display();
    }

    public function info_content(){
        $this->display();
    }

    //商品列表
    public function item_list(){
        $id = I('get.id','intval'); //商家id
        $map['sid'] = $id;
        $map['status'] = 1;
        $keyword = $this->_request('keyword', 'trim');
        if($keyword){
            $map['title'] = array('like', '%'.$keyword.'%');
        }


        $item_list = M('item')->where($map)->order('ordid asc')->select();
        $this->assign('item_list',$item_list);
        $this->assign('search', array(
            'keyword' => $keyword,
        ));
         $this->display();
    }
    //代购
/*    public function buy_shopping(){


        $id = I('get.id','intval');
        $cate_list=  M('item_cate')->where(array('pid'=>$id))->order('ordid asc')->select();
        foreach($cate_list as $key=>$val){
            $cate_list[$key]['cate'] =  M('item_cate')->where(array('pid'=>$val['id']))->order('ordid asc')->select();
        }
        $this->assign('cate_list',$cate_list);
        //p($cate_list);
           $first_list=  M('item_cate')->where(array('pid'=>$id))->order('ordid asc')->find();
            $where['tid'] = $first_list['id'];
            $merchant_list =  M('merchant')->where($where)->order('ordid asc')->select();
            foreach($merchant_list as $key=>$val){
                $lable = M('lable')->order('id asc')->where(array('sid'=>$val['id']))->field('name,value,tag')->select();
                $merchant_list[$key]['yuexiao'] = $lable[0]['value'];
                $merchant_list[$key]['qisong'] = $lable[1]['value'];
                $merchant_list[$key]['peisong'] = $lable[2]['value'];
                $merchant_list[$key]['min'] = $lable[3]['value'];
            }
            $this->assign('merchant_list',$merchant_list);

        $this->display();
    }*/

    public function buy_shopping(){
        $id = I('get.id','intval');
        $cate_list=  M('item_cate')->where(array('pid'=>$id))->order('ordid asc')->select();
        foreach($cate_list as $key=>$val){
            $cate_list[$key]['cate'] =  M('item_cate')->where(array('pid'=>$val['id']))->order('ordid asc')->select();
        }
        $this->assign('cate_list',$cate_list);
        $first_list=  M('item_cate')->where(array('pid'=>$id))->order('ordid asc')->find();

        $first_list_cate = M('item_cate')->where(array('pid'=>$first_list['id']))->order('ordid asc')->find();
        if($first_list_cate){
            $where['ttid'] = $first_list_cate['id'];
            $merchant_list =  M('merchant')->where($where)->order('ordid asc')->select();
            foreach($merchant_list as $key=>$val){
                $lable = M('lable')->order('id asc')->where(array('sid'=>$val['id']))->field('name,value,tag')->select();
                $merchant_list[$key]['yuexiao'] = $lable[0]['value'];
                $merchant_list[$key]['qisong'] = $lable[1]['value'];
                $merchant_list[$key]['peisong'] = $lable[2]['value'];
                $merchant_list[$key]['min'] = $lable[3]['value'];
                $merchant_list[$key]['over'] = $lable[4]['value'];
            }
            $this->assign('merchant_list',$merchant_list);
        }else{
            $where['tid'] = $first_list['id'];
            $merchant_list =  M('merchant')->where($where)->order('ordid asc')->select();
            foreach($merchant_list as $key=>$val){
                $lable = M('lable')->order('id asc')->where(array('sid'=>$val['id']))->field('name,value,tag')->select();
                $merchant_list[$key]['yuexiao'] = $lable[0]['value'];
                $merchant_list[$key]['qisong'] = $lable[1]['value'];
                $merchant_list[$key]['peisong'] = $lable[2]['value'];
                $merchant_list[$key]['min'] = $lable[3]['value'];
            }
            $this->assign('merchant_list',$merchant_list);
        }
        $this->display();



    }



    //代购详情
    public function buy_shopping_content(){
            $id = $_GET['id'];
            $where['id'] = $id;
            $merchant_info =  M('merchant')->where($where)->order('ordid asc')->find();
            $lable = M('lable')->order('id asc')->where(array('sid' => $merchant_info['id']))->field('name,value,tag')->select();
            $merchant_info['yuexiao'] = $lable[0]['value'];
            $merchant_info['qisong'] = $lable[1]['value'];
            $merchant_info['peisong'] = $lable[2]['value'];
            $merchant_info['min'] = $lable[3]['value'];
            $merchant_info['c_price'] = $lable[4]['value'];
            $this->assign('merchant_info',$merchant_info);
            $this->display();
    }


    public function item_station(){
        $dingdanhao = date("YmdHis");  //订单编号
        $dingdanhao .= rand(100, 999);//订单编号 日期加随机数
        $data['order_sn']= $dingdanhao;//订单号
        $data['uid']     = $this->user['id']; //用户id
        $data['uname']   = $this->user['username']; //用户名
        $data['mobile']   = $this->user['tel'];    //电话
        $data['c_station']= $this->_post('c_station', 'trim');
        $data['d_station'] = $this->_post('d_station', 'trim');
        $data['c_time'] = $this->_post('c_time');
        $data['num']= $this->_post('num', 'intval');
        $data['price'] = $this->_post('price');
        $data['p_name'] = $this->_post('p_name');
        $data['add_time'] = time();
        if(isset($_POST['code'])) {
            if ($_POST['code'] == $_SESSION['code']) {
                // 重复提交表单了
            } else {
                $_SESSION['code'] = $_POST['code']; //存储code
                $ticket_id = M('return_integral')->add($data);
            }
        }
        $this->assign('dingdanhao',$dingdanhao);
        $this->assign('ticket_id',$ticket_id);
        $this->assign('pay',0);
        $this->display();


    }

    public function item_station_pay(){
        $where['ticket_id'] = $this->_post('ticket_id', 'intval');
        $data['status'] = 1;
        if( false !== M('return_integral')->where($where)->save($data)){
            $url =  U('User/return_integral');
            echo "<script>alert('支付成功');window.location.href='".$url."'</script>";
            //$this->success("提交成功！", U('User/return_integral'));
        }


    }

    //同城速递
    public function city_express(){

        $min  = C('pin_city_money');  //同城速递收费标准
        $danci_price = C('pin_city_service'); //同城速递服务内容
        $this->display();
    }

    //代办
    public function agency(){
        $teg = $this->_teg();
        $id = I('get.id','intval');
        $cate_list=  M('item_cate')->where(array('pid'=>$id))->order('ordid asc')->select();
        foreach($cate_list as $key=>$val){
            $cate_list[$key]['cate'] =  M('item_cate')->where(array('pid'=>$val['id']))->order('ordid asc')->select();
        }
        $this->assign('cate_list',$cate_list);
        //p($cate_list);
        $first_list=  M('item_cate')->where(array('pid'=>$id))->order('ordid asc')->find();
        $where['tid'] = $first_list['id'];
        $merchant_list =  M('merchant')->where($where)->order('ordid asc')->select();
        foreach($merchant_list as $key=>$val){
            $lable = M('lable')->order('id asc')->where(array('sid'=>$val['id']))->field('name,value,tag')->select();
            foreach($lable as $k=>$v){
                $lable[$k]['teg'] = $teg[$v['tag']];
            }
            $merchant_list[$key]['yuexiao'] = $lable[0]['value'];
            $merchant_list[$key]['yuexiao_teg'] = $lable[0]['teg'];
            $merchant_list[$key]['qisong'] = $lable[1]['value'];
            $merchant_list[$key]['qisong_teg'] = $lable[1]['teg'];
            $merchant_list[$key]['peisong'] = $lable[2]['value'];
            $merchant_list[$key]['peisong_teg'] = $lable[2]['teg'];




        }
        $this->assign('merchant_list',$merchant_list);
        //p($merchant_list);die;
        $this->display();
    }


    //代办详情
    public function agency_content(){
        $teg = $this->_teg();
        $id = $_GET['id'];
        $where['id'] = $id;
        $merchant_info =  M('merchant')->where($where)->order('ordid asc')->find();
        $lable = M('lable')->order('id asc')->where(array('sid' => $merchant_info['id']))->field('name,value,tag')->select();
        foreach($lable as $k=>$v){
            $lable[$k]['teg'] = $teg[$v['tag']];
        }
        $merchant_info['yuexiao'] = $lable[0]['value'];
        $merchant_info['yuexiao_teg'] = $lable[0]['teg'];
        $merchant_info['qisong'] = $lable[1]['value'];
        $merchant_info['qisong_teg'] = $lable[1]['teg'];
        $merchant_info['peisong'] = $lable[2]['value'];
        $merchant_info['peisong_teg'] = $lable[2]['teg'];
        $merchant_info['min'] = $lable[3]['value'];
        $merchant_info['c_price'] = $lable[4]['value'];
        $this->assign('merchant_info',$merchant_info);
        $this->assign('type',$_GET['type']);
        $this->display();
    }


    //对人服务
   /* public function people_service(){
        $teg = $this->_teg();
        $id = I('get.id','intval');
        $cate_list=  M('item_cate')->where(array('pid'=>$id))->order('ordid asc')->select();
        foreach($cate_list as $key=>$val){
            $cate_list[$key]['cate'] =  M('item_cate')->where(array('pid'=>$val['id']))->order('ordid asc')->select();
        }
        $this->assign('cate_list',$cate_list);
        //p($cate_list);
        $first_list=  M('item_cate')->where(array('pid'=>$id))->order('ordid asc')->find();
        $where['tid'] = $first_list['id'];
        $merchant_list =  M('merchant')->where($where)->order('ordid asc')->select();
        foreach($merchant_list as $key=>$val){
            $lable = M('lable')->order('id asc')->where(array('sid'=>$val['id']))->field('name,value,tag')->select();
            foreach($lable as $k=>$v){
                $lable[$k]['teg'] = $teg[$v['tag']];
            }
            $merchant_list[$key]['yuexiao'] = $lable[0]['value'];
            $merchant_list[$key]['qisong'] = $lable[1]['value'];
            $merchant_list[$key]['peisong'] = $lable[2]['value'];

        }
        $this->assign('merchant_list',$merchant_list);
        //p($merchant_list);die;
        $this->display();
    }*/

    public function people_service(){
        $teg = $this->_teg(); //标签
        $id = I('get.id','intval');
        $cate_list=  M('item_cate')->where(array('pid'=>$id))->order('ordid asc')->select();
        foreach($cate_list as $key=>$val){
            $cate_list[$key]['cate'] =  M('item_cate')->where(array('pid'=>$val['id']))->order('ordid asc')->select();
        }
        $this->assign('cate_list',$cate_list);
        $first_list=  M('item_cate')->where(array('pid'=>$id))->order('ordid asc')->find();

        $first_list_cate = M('item_cate')->where(array('pid'=>$first_list['id']))->order('ordid asc')->find();
        if($first_list_cate){
            $where['ttid'] = $first_list_cate['id'];
            $merchant_list =  M('merchant')->where($where)->order('ordid asc')->select();
            foreach($merchant_list as $key=>$val){
                $lable = M('lable')->order('id asc')->where(array('sid'=>$val['id']))->field('name,value,tag')->select();

                foreach($lable as $k=>$v){
                    $lable[$k]['teg'] = $teg[$v['tag']];
                }
                $merchant_list[$key]['yuexiao'] = $lable[0]['value'];
                $merchant_list[$key]['yuexiao_teg'] = $lable[0]['teg'];
                $merchant_list[$key]['qisong'] = $lable[1]['value'];
                $merchant_list[$key]['qisong_teg'] = $lable[1]['teg'];
                $merchant_list[$key]['peisong'] = $lable[2]['value'];
                $merchant_list[$key]['peisong_teg'] = $lable[2]['teg'];
                $merchant_list[$key]['min'] = $lable[3]['value'];
                $merchant_list[$key]['over'] = $lable[4]['value'];


            }
            $this->assign('merchant_list',$merchant_list);
        }else{
            $where['tid'] = $first_list['id'];
            $merchant_list =  M('merchant')->where($where)->order('ordid asc')->select();
            foreach($merchant_list as $key=>$val){
                $lable = M('lable')->order('id asc')->where(array('sid'=>$val['id']))->field('name,value,tag')->select();
                foreach($lable as $k=>$v){
                    $lable[$k]['teg'] = $teg[$v['tag']];
                }
                $merchant_list[$key]['yuexiao'] = $lable[0]['value'];
                $merchant_list[$key]['yuexiao_teg'] = $lable[0]['teg'];
                $merchant_list[$key]['qisong'] = $lable[1]['value'];
                $merchant_list[$key]['qisong_teg'] = $lable[1]['teg'];
                $merchant_list[$key]['peisong'] = $lable[2]['value'];
                $merchant_list[$key]['peisong_teg'] = $lable[2]['teg'];
                $merchant_list[$key]['min'] = $lable[3]['value'];
                $merchant_list[$key]['over'] = $lable[4]['value'];
            }
            $this->assign('merchant_list',$merchant_list);
        }
        $this->display();



    }


    //对人服务详情
    public  function people_content(){
        $teg = $this->_teg(); //标签
        $id = $_GET['id'];
        $where['id'] = $id;
        $merchant_info =  M('merchant')->where($where)->order('ordid asc')->find();
        $lable = M('lable')->order('id asc')->where(array('sid' => $merchant_info['id']))->field('name,value,tag')->select();
        foreach($lable as $k=>$v){
            $lable[$k]['teg'] = $teg[$v['tag']];
        }
        $merchant_info['yuexiao'] = $lable[0]['value'];
        $merchant_info['yuexiao_teg'] = $lable[0]['teg'];
        $merchant_info['qisong'] = $lable[1]['value'];
        $merchant_info['qisong_teg'] = $lable[1]['teg'];
        $merchant_info['peisong'] = $lable[2]['value'];
        $merchant_info['peisong_teg'] = $lable[2]['teg'];
        $merchant_info['min'] = $lable[3]['value'];
        $merchant_info['c_price'] = $lable[4]['value'];
        if($merchant_info['title'] == '看管购买'){
            $merchant_info['tile'] = 1;
        }else if($merchant_info['title'] == '接送'){
            $merchant_info['tile'] = 2;
        }else{
            $merchant_info['tile'] = 3;
        }
        $this->assign('merchant_info',$merchant_info);
        $this->assign('type',$_GET['type']);
        $this->display();

    }
    //酒店预订
    public function hotel(){
        $this->display();

    }
    public function queren(){
    	 $id = $this->_get('id', 'intval');  //商品id
         $num = $this->_get('num', 'intval'); //商品数量

        //小六商城 单价 规格
        $price = $this->_get('price', 'trim'); //单价
        $guige = $this->_get('guige', 'trim');  //商品规格 ;
        $t_price = sprintf('%.2f',($price * $num));

    	 $item_mod = M('item');
    	 $item = $item_mod->where(array('id' => $id, 'status' => 1))->find();
         $item['num'] = $num;
         $item['total_price'] = sprintf('%.2f',($item['tc_price'] * $num));
         $this->assign('item', $item);
         //小六商城 单价 总价 规格
         $this->assign('price', $price);
         $this->assign('t_price', $t_price);
         $this->assign('guige', $guige);

    	 $this->display();
    }

    public function index1() {
    	
        $id = $this->_get('id', 'intval');
        !$id && $this->_404();
        $item_mod = M('item');
        //$item = $item_mod->field('id,title,intro,price,info,comments,add_time,goods_stock,buy_num,brand,size,color')->where(array('id' => $id, 'status' => 1))->find();
        $item = $item_mod->where(array('id' => $id, 'status' => 1))->find();
        !$item && $this->_404();
    
          /**
         * ***品牌 
         */
        $brand=M('brandlist')->field('name')->find($item['brand']);
        $item['brand']=$brand['name'];
        //会员价格
        if($this->visitor->info){
            $cate_id=$this->visitor->info['uid'];
            $cate =M('item_userprice')->field('user_price')->where(array('cate_id' =>$cate_id,'item_id'=>$item['id']))->find();
            if($cate){
            $item['price']=$cate['user_price'];
            }
        }
        //商品相册
        $img_list = M('item_img')->field('url')->where(array('item_id' => $id))->order('ordid')->select();
        //标签
        $item['tag_list'] = unserialize($item['tag_cache']);
        //可能还喜欢
     /*   $item_tag_mod = M('item_tag');
        $db_pre = C('DB_PREFIX');
        $item_tag_table = $db_pre . 'item_tag';
        $maylike_list = array_slice($item['tag_list'], 0, 3, true);
        foreach ($maylike_list as $key => $val) {
            $maylike_list[$key] = array('name' => $val);
            $maylike_list[$key]['list'] = $item_tag_mod->field('i.id,i.img,i.intro,' . $item_tag_table . '.tag_id')->where(array($item_tag_table . '.tag_id' => $key, 'i.id' => array('neq', $id)))->join($db_pre . 'item i ON i.id = ' . $item_tag_table . '.item_id')->order('i.id DESC')->limit(9)->select();
        }
*/
        //第一页评论不使用AJAX利于SEO
        $item_comment_mod = M('item_comment');
        $pagesize = 8;
        $map = array('item_id' => $id);
        $count = $item_comment_mod->where($map)->count('id');
        $pager = $this->_pager($count, $pagesize);
        $pager->path = 'comment_list';
        $pager_bar = $pager->fshow();
        $cmt_list = $item_comment_mod->where($map)->order('id DESC')->limit($pager->firstRow . ',' . $pager->listRows)->select();
    
       //$item_mod->where(array('id' => $id))->setInc('hits'); //点击量 
        $this->assign('item', $item);
       
        //$this->assign('maylike_list', $maylike_list);
        $this->assign('img_list', $img_list);
        $this->assign('cmt_list', $cmt_list);
        $this->assign('page_bar', $pager_bar);
        $this->_config_seo(C('pin_seo_config.item'), array(
            'item_title' => $item['title'],
            'item_intro' => $item['intro'],
            'item_tag' => implode(' ', $item['tag_list']),
            'user_name' => $item['uname'],
            'seo_title' => $item['seo_title'],
            'seo_keywords' => $item['seo_keys'],
            'seo_description' => $item['seo_desc'],
        ));

         //属性
	 $attr_list = M('item_attr')->where(array('item_id'=>$id))->select();
		
	foreach($attr_list as $k=>$v){
		$attr_list[$k]['name_v']=explode("|",$v['attr_value']);
	}			
        $this->assign('attr_list', $attr_list);
	$this->assign('attr_list_count', count($attr_list));					
        $this->display();
    }

    /**
     * 点击去购买
     */
    public function tgo() {
        $url = $this->_get('to', 'base64_decode');
        redirect($url);
    }



    /**
     *求两个已知经纬度之间的距离,单位为米
     *@param lng1,lng2 经度
     *@param lat1,lat2 纬度
     *@return float 距离，单位米
     *@author www.Alixixi.com
     **/
    function getdistance($lng1,$lat1,$lng2,$lat2){
        //将角度转为狐度

        $radLat1=deg2rad($lat1);//deg2rad()函数将角度转换为弧度
        $radLat2=deg2rad($lat2);
        $radLng1=deg2rad($lng1);
        $radLng2=deg2rad($lng2);
        $a=$radLat1-$radLat2;
        $b=$radLng1-$radLng2;
        $s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6378.137*1000;
        $s1 = round($s);
        if($s1 > 300){
            $s1 = $s1 - 150;
        }
        return $s1;
    }
    // 根据经纬度计算周围1000米寻找店铺  **$distance = 0.5 代表0.5千米
    public function returnSquarePoint($lng, $lat,$distance = ''){
        $EARTH_RADIUS = 6371;
        //$lat 已知点的纬度
        $dlng =  4 * asin(sin($distance / (2 * $EARTH_RADIUS)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);//转换弧度

        $dlat = $distance/$EARTH_RADIUS;//EARTH_RADIUS地球半径
        $dlat = rad2deg($dlat);//转换弧度

        return array(
            'left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
            'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
            'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
            'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
        );
    }


    /**
     * AJAX获取评论列表
     */
    public function comment_list() {
        $id = $this->_get('id', 'intval');
        !$id && $this->ajaxReturn(0, L('invalid_item'));
        $item_mod = M('item');
        $item = $item_mod->where(array('id' => $id, 'status' => '1'))->count('id');
        !$item && $this->ajaxReturn(0, L('invalid_item'));
        $item_comment_mod = M('item_comment');
        $pagesize = 8;
        $map = array('item_id' => $id);
        $count = $item_comment_mod->where($map)->count('id');
        $pager = $this->_pager($count, $pagesize);
        $pager->path = 'comment_list';
        $cmt_list = $item_comment_mod->where($map)->order('id DESC')->limit($pager->firstRow . ',' . $pager->listRows)->select();
        $this->assign('cmt_list', $cmt_list);
        $data = array();
        $data['list'] = $this->fetch('comment_list');
        $data['page'] = $pager->fshow();
        $this->ajaxReturn(1, '', $data);
    }

    /**
     * 评论一个商品
     */
    public function comment() {
        foreach ($_POST as $key=>$val) {
            $_POST[$key] = Input::deleteHtmlTags($val);
        }
        $data = array();
        $data['item_id'] = $this->_post('id', 'intval');
        !$data['item_id'] && $this->ajaxReturn(0, L('invalid_item'));
        $data['info'] = $this->_post('content', 'trim');
        !$data['info'] && $this->ajaxReturn(0, L('please_input') . L('comment_content'));
        //敏感词处理
        $check_result = D('badword')->check($data['info']);
        switch ($check_result['code']) {
            case 1: //禁用。直接返回
                $this->ajaxReturn(0, L('has_badword'));
                break;
            case 3: //需要审核
                $data['status'] = 0;
                break;
        }
        $data['info'] = $check_result['content'];
        //$data['uid'] = $this->visitor->info['id'];
        $data['uname'] = $this->visitor->info['username'];

        //验证商品
      //  $item_mod = M('item');
//        $item = $item_mod->field('id,uid,uname')->where(array('id' => $data['item_id'], 'status' => '1'))->find();
//        !$item && $this->ajaxReturn(0, L('invalid_item'));
        //写入评论
        $item_comment_mod = M('item_comment');
		 
       if (false === $item_comment_mod->create($data)) {
            $this->ajaxReturn(0, $item_comment_mod->getError());
        }
        $comment_id = $item_comment_mod->add($datacom);
        if ($comment_id) {
            $this->assign('cmt_list', array(
                array(
                    'uid' => $data['uid'],
                    'uname' => $data['uname'],
                    'info' => $data['info'],
                    'add_time' => time(),
                )
            ));
            $resp = $this->fetch('comment_list');
            $this->ajaxReturn(1, L('comment_success'), $resp);
        } else {
            $this->ajaxReturn(0, L('comment_failed'));
        }
    }

    //分享商品弹窗
    public function share_item() {
        //支持的网站
        if (false === $site_list = F('item_site_list')) {
            $site_list = D('item_site')->site_cache();
        }
        $this->assign('site_list', $site_list);
        $resp = $this->fetch('dialog:share_item');
        $this->ajaxReturn(1, '', $resp);
    }

    //获取商品信息
    public function fetch_item() {
        $url = $this->_get('url', 'trim');
        $url == '' && $this->ajaxReturn(0, L('please_input') . L('correct_itemurl'));
        $aid = $this->_get('aid', 'intval');
        //获取商品信息
        $itemcollect = new itemcollect();
        !$itemcollect->url_parse($url) && $this->ajaxReturn(0, L('please_input') . L('correct_itemurl'));
        if (!$info = $itemcollect->fetch()) {
            $this->ajaxReturn(0, L('fetch_item_failed'));
        }
        $this->assign('item', $info['item']);
        $data = array();
        if ($aid) {
            $this->assign('aid', $aid);
        } else {
            //用户的专辑
            $album_list = M('album')->field('id,title')->where(array('uid' => $this->visitor->info['id']))->select();
            //专辑分类
            if (false === $album_cate_list = F('album_cate_list')) {
                $album_cate_list = D('album_cate')->cate_cache();
            }
            $this->assign('album_cate_list', $album_cate_list);
            $this->assign('album_list', $album_list);
        }
        //专辑分类
        $data['html'] = $this->fetch('dialog:fetch_item');
        $data['item'] = serialize($info['item']);
        $this->ajaxReturn(1, L('fetch_item_success'), $data);
    }

    //发布商品
    public function publish_item() {
        $item = unserialize($this->_post('item', 'trim'));
        !$item['key_id'] && $this->ajaxReturn(0, L('publish_item_failed'));
        $album_id = $this->_post('album_id', 'intval', 0);
        $ac_id = $this->_post('ac_id', 'intval', 0);
        $item['intro'] = $this->_post('intro', 'trim');
        $item['info'] = Input::deleteHtmlTags($item['info']);
        $item['uid'] = $this->visitor->info['id'];
        $item['uname'] = $this->visitor->info['username'];
        $item['status'] = C('pin_item_check') ? 0 : 1;
        //添加商品
        $item_mod = D('item');
        $result = $item_mod->publish($item, $album_id, $ac_id);
        if ($result) {
            //发布商品钩子
            $tag_arg = array('uid' => $item['uid'], 'uname' => $item['uname'], 'action' => 'pubitem');
            tag('pubitem_end', $tag_arg);
            $this->ajaxReturn(1, L('publish_item_success'));
        } else {
            $this->ajaxReturn(0, $item_mod->getError());
        }
    }

    /**
     * 喜欢一个商品
     * 返回status(todo)
     */
    public function like() {
        $id = $this->_get('id', 'intval');
        $aid = $this->_get('aid', 'intval');
        !$id && $this->ajaxReturn(0, L('invalid_item'));
        $item_mod = M('item');
        $item = $item_mod->field('id,uid,uname')->where(array('id' => $id, 'status' => '1'))->find();
        !$item && $this->ajaxReturn(0, L('invalid_item'));
        $uid = $this->visitor->info['id'];
        $uname = $this->visitor->info['username'];
        $item['uid'] == $uid && $this->ajaxReturn(0, L('like_own')); //自己的商品
        $like_mod = M('item_like');
        //是否已经喜欢过
        $is_liked = $like_mod->where(array('item_id' => $item['id'], 'uid' => $uid))->count();
        $is_liked && $this->ajaxReturn(0, L('you_was_liked'));
        if ($like_mod->add(array('item_id' => $item['id'], 'uid' => $uid, 'add_time' => time()))) {
            //增加商品喜欢数
            $item_mod->where(array('id' => $id))->setInc('likes');
            //增加用户被喜欢数
            M('user')->where(array('id' => $item['uid']))->setInc('likes');
            //增加专辑喜欢
            $aid && M('album')->where(array('id' => $aid))->setInc('likes');
            //添加喜欢钩子
            $tag_arg = array('uid' => $uid, 'uname' => $uname, 'action' => 'likeitem');
            tag('likeitem_end', $tag_arg);
            $this->ajaxReturn(1, L('like_success'));
        } else {
            $this->ajaxReturn(0, L('like_failed'));
        }
    }

    /**
     * 删除喜欢
     */
    public function unlike() {
        $id = $this->_get('id', 'intval');
        !$id && $this->ajaxReturn(0, L('invalid_item'));
        $uid = $this->visitor->info['id'];
        $like_mod = M('item_like');
        if ($like_mod->where(array('uid' => $uid, 'item_id' => $id))->delete()) {
            //喜欢数不减少~
            $this->ajaxReturn(1, L('unlike_success'));
        } else {
            $this->ajaxReturn(1, L('unlike_failed'));
        }
    }

    /**
     * 删除商品
     */
    public function delete() {
        $id = $this->_get('id', 'intval');
        $album_id = $this->_get('album_id', 'intval');
        !$id && $this->ajaxReturn(0, L('invalid_item'));
        $uid = $this->visitor->info['id'];
        $uname = $this->visitor->info['username'];
        if ($album_id) {
            //删除专辑里面的商品
            $result = M('album')->where(array('id' => $album_id, 'uid' => $uid))->count();
            if ($result) {
                M('album_item')->where(array('album_id' => $album_id, 'item_id' => $id))->delete();
                //减少专辑商品数量
                M('album')->where(array('id' => $album_id))->setDec('items');
                //刷新专辑封面
                D('album')->update_cover($album_id);
                $this->ajaxReturn(1, L('del_item_success'));
            } else {
                $this->ajaxReturn(0, L('del_item_failed'));
            }
        } else {
            $result = D('item')->where(array('id' => $id, 'uid' => $uid))->delete();
            //减少用户分享数量
            M('user')->where(array('id' => $uid))->setDec('shares');
            if ($result) {
                //添加删除钩子
                $tag_arg = array('uid' => $uid, 'uname' => $uname, 'action' => 'delitem');
                tag('delitem_end', $tag_arg);
                $this->ajaxReturn(1, L('del_item_success'));
            } else {
                $this->ajaxReturn(0, L('del_item_failed'));
            }
        }
    }

}