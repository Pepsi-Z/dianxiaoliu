<?php
class AshortsiteAction extends membersAction
{
    public function _initialize() {
        parent::_initialize();
    }

   public function index(){

       $this->display();
   }
    //会员/非会员
    public function member(){
        $this->display();
    }

    public function site(){
        $sql = "select a.*,c.name as c_name,d.name as city_name,e.name as area_name".
            " from weixin_site a left join weixin_region c on a.province = c.id ".
            " left join weixin_region d on a.city = d.id ".
            " left join weixin_region e on a.town = e.id ";

        $Dao = M();
        $list = $Dao->query($sql);
        $this->assign('lists',$list);
        $this->assign('hid',I('get.hid','','intval'));
        $this->display();
    }

    //会员
    public function club(){
        for($i=0;$i<7;$i++) {
            $mod = M('club');
            $id = I('get.id','','intval');//场馆id
            $where['a.id'] = $id;
            $startmonth = date("md",strtotime("+$i day"));
            $where['c.startmonth'] = array('elt',$startmonth); //开始时间年月日
            $where['c.endmonth'] = array('egt',$startmonth);    //结束时间年月日

            $join = array('a left join __PACKAGE__  b ON a.package = b.id',
                'left join __PACKMONTH__ c ON b.id = c.pid'
            );

            $min_time[] = M('site')->join($join)->where($where)->min('c.start_time');//最小开业时间
            $max_time[] = M('site')->join($join)->where($where)->max('c.end_time');//最大开业时间
        }
        $min_time = min($min_time);
        $max_time = max($max_time);
        $i = intval($min_time);
        for($i = $min_time;$i<=$max_time;$i++){
            $timelist[]=$i;

        }
        foreach($timelist as $key=>$val){
            $where =array();
            $time=str_replace('-', '', date("m-d", time()));//当前时间年月日
            $where['a.startmonth'] = array('elt',$time); //开始时间年月日
            $where['a.endmonth'] = array('egt',$time);    //结束时间年月日

            $where['a.start_time'] = array('elt',$timelist[$key]); //开始时间年月日
            $where['a.end_time'] = array('egt',$timelist[$key]);   //结束时间年月日

            $where['a.status'] = 1;
            $where['c.id'] = $id;
            $join= array(
                'a left join __PACKAGE__ b ON a.pid = b.id',
                'left join __SITE__ c ON c.package = b.id',
                'left join __CLUB__ d ON d.pid = c.id',
            );
            $field = 'a.start_time,a.end_time,b.packname,d.club_name,c.id,c.site_name,a.startmonth,a.endmonth';
            $packlist[$timelist[$key]] = M('packmonth')->join($join)->distinct(true)->field($field)->where($where)->select();
        }
        //场馆名字
        $site_name = M('site')->field('site_name')->where(array('id'=>$id))->find();
        $this->assign('site_name', $site_name['site_name']);
        $this->assign('min_times', $min_time);
        $this->assign('max_times', $max_time);
        $this->assign('i', $i);
        $this->assign('open_month',7);
        $this->assign('packlist', $packlist);
        $this->display();
    }

    //非会员
    public function nclub(){
        for($i=0;$i<7;$i++) {
            $mod = M('club');
            $id = I('get.id','','intval');//场馆id
            $where['a.id'] = $id;
            $startmonth = date("md",strtotime("+$i day"));
            $where['c.startmonth'] = array('elt',$startmonth); //开始时间年月日
            $where['c.endmonth'] = array('egt',$startmonth);    //结束时间年月日

            $join = array('a left join __PACKAGE__  b ON a.package = b.id',
                'left join __PACKMONTH__ c ON b.id = c.pid'
            );

            $min_time[] = M('site')->join($join)->where($where)->min('c.start_time');//最小开业时间
            $max_time[] = M('site')->join($join)->where($where)->max('c.end_time');//最大开业时间
        }
        $min_time = min($min_time);
        $max_time = max($max_time);
        $i = intval($min_time);
        for($i = $min_time ;$i<=$max_time;$i++){
            $timelist[]=$i;

        }

        foreach($timelist as $key=>$val){
            $where =array();
            $time=str_replace('-', '', date("m-d", time()));//当前时间年月日
            $where['a.startmonth'] = array('elt',$time); //开始时间年月日
            $where['a.endmonth'] = array('egt',$time);    //结束时间年月日

            $where['a.start_time'] = array('elt',$timelist[$key]); //开始时间年月日
            $where['a.end_time'] = array('egt',$timelist[$key]);   //结束时间年月日

            $where['a.status'] = 1;
            $where['c.id'] = $id;
            $join= array(
                'a left join __PACKAGE__ b ON a.pid = b.id',
                'left join __SITE__ c ON c.package = b.id',
                'left join __CLUB__ d ON d.pid = c.id',
            );
            $field = 'a.start_time,a.end_time,b.packname,d.club_name,c.id,c.site_name,a.startmonth,a.endmonth';
            $packlist[$timelist[$key]] = M('packmonth')->join($join)->distinct(true)->field($field)->where($where)->select();
        }
        //echo M('packmonth')->_sql();
        //p($packlist);
        //p('11');
        //dump($i);
        if($_SESSION['user']){
            $open_month =   I('get.reserve','','intval');
            $this->assign('open_month', $open_month);
        }else{
            $open_month =   I('get.nm_reserve','','intval');
            $this->assign('open_month', $open_month);
        }

        //场馆名字
        $site_name = M('site')->field('site_name')->where(array('id'=>$id))->find();
        $this->assign('site_name', $site_name['site_name']);
        $this->assign('min_times', $min_time);
        $this->assign('max_times', $max_time);
        $this->assign('i', $i);
        $this->assign('packlist', $packlist);
        $this->assign('open_month',7);
        $this->display();
    }

    public function ordersite(){
        $opentime = I('get.time','','intval');  //定场时间
        $openmonth=str_replace('-', '/', substr(I('get.month','','trim'),5));//定场月份
        $pid   = I('get.pid','','intval');  //场馆id
        $where['a.id'] = $pid;

        $where['d.start_time'] = array('elt',$opentime); //开始营业时间
        $where['d.end_time'] = array('egt',$opentime);   //结束营业时间

        $time = str_replace('-', '', substr(I('get.month','','trim'),5)); //当前时间年月日
        $where['d.startmonth'] = array('elt',$time); //开始时间年月日
        $where['d.endmonth'] = array('egt',$time);    //结束时间年月日
        $join = array(
            'a left join __CLUB__ b on a.id = b.pid',
            'left join  __PACKAGE__ c on b.package = c.id',
            'left join  __PACKMONTH__ d on c.id = d.pid',
        );
        $field = ('a.site_name,a.id as pid,b.id as club_id,b.club_name,b.status as suoding');
        $packlist = M('site')->field($field)->join($join)->where($where)->select();
        foreach($packlist as $k=>$v){
            $month=I('get.month','','trim');
            //锁定
            $packlist[$k]['lock'] = M('club_lock')->where("start_month = '{$month}' and start_time = '{$opentime}'
             and club_id = {$v['club_id']} and status = 1 and site_id = '{$pid}'") ->count();
            //取消
            $quxiao = M('ordersite')->where("start_month = '{$month}' and start_time = '{$opentime}'
             and club_id = {$v['club_id']} and status = 1 and status_desc != '' and order_status = 1 and site_id = '{$pid}' ")->field('id')->find();
            $packlist[$k]['quxiao'] =$quxiao['id'];
            //会员临时
            $now_time = time();
            $order_time =  M('setting')->getField('order_time');
            $order_time = $order_time*60;
            $packlist[$k]['member_short'] = M('ordersite')->where("start_month = '{$month}' and start_time = '{$opentime}'
             and club_id = {$v['club_id']} and long_status = 0 and status =0 and vipnum != ''  and site_id = '{$pid}' and (order_status = 1 or ( add_time + '".$order_time."' > '".$now_time."' and order_status = 0))")->find();


            //会员长期
            $packlist[$k]['member_long'] = M('ordersite')->where("start_month = '{$month}' and start_time = '{$opentime}'
             and club_id = {$v['club_id']} and long_status = 1 and status in (0) and vipnum != '' and order_status = 1 and site_id = '{$pid}' ")->find();

            //非会员临时
            $packlist[$k]['nmember_short'] = M('ordersite')->where("start_month = '{$month}' and start_time = '{$opentime}'
             and club_id = {$v['club_id']} and status in (0)  and long_status = 0 and vipnum is null   and site_id = '{$pid}' and (order_status = 1 or ( add_time + '".$order_time."' > '".$now_time."' and order_status = 0))")->find();
        }
        $assign['opentime'] = $opentime;
        $assign['openmonth'] = $openmonth;
        $assign['packlist'] = $packlist;
        echo json_encode($assign);die;



    }
    //确定修改状态
    public function alert_confirm(){
        $data['status'] = 2; //2表示可选
        $alert_confirm = M('ordersite')->where(array('id'=>$_POST['id']))->save($data);
        echo $alert_confirm;die;
    }

    public function orderlist(){
            $opentime = I('get.opentime','','intval');  //定场时间
            //$openmonth = I('post.openmonth','','trim');  //定场月份
            $openmonth=str_replace('/', '-',date('Y',time()).'/'.I('get.openmonth','','trim'));//定场月份
            $pid   = I('get.pid','','intval');  //场馆id
            $club   = explode(',',I('get.club','','trim'));  //场地id
            $map['id'] = array('in',$club);
            $siteinfo = M('site')->where(array('id'=>$pid))->field('site_name,id')->find();
            $clubinfo = M('club')->where($map)->field('club_name,id')->select();

            //$where['d.opentime'] =$opentime;
            $where['c.start_time'] = array('elt',$opentime);   //开始营业时间
            $where['c.end_time'] = array('egt',$opentime);    //结束营业时间

            $tim=str_replace('/', '', I('get.openmonth','','trim'));//当前时间年月日

            $where['c.startmonth'] = array('elt',$tim); //开始时间年月日
            $where['c.endmonth'] = array('egt',$tim);    //结束时间年月日

            $pack_time = $opentime.':00';
            $where['d.opentime'] = array('elt',$pack_time); //pack开始时间
            $where['d.endtime'] = array('egt',$pack_time);    //pack结束时间

            $where['a.id'] = array('in',$club);
            $join = array(
                'a left join  __PACKAGE__ b on a.package = b.id',
                'left join  __PACKMONTH__ c on b.id = c.pid',
                'left join __PACK__ d on c.id= d.mid'
            );
            //节假日
            $holiday=strtotime(str_replace('/', '-',date('Y',time()).'/'.I('get.openmonth','','trim')));//当前时间年月日
            $qujian['begin_time'] = array('elt',$holiday); //开始时间年月日
            $qujian['end_time'] = array('egt',$holiday);    //结束时间年月日
            //判断当前时间是节假日 还是 双休 还是工作日
            $price = 0; //总价钱
            if(M('holiday')->where($qujian)->select()){
                $wheres['c.start_time'] = array('elt',$opentime);   //开始营业时间
                $wheres['c.end_time'] = array('egt',$opentime);    //结束营业时间

                $tim=str_replace('/', '', I('get.openmonth','','trim'));//当前时间年月日

                $wheres['c.startmonth'] = array('elt',$tim); //开始时间年月日
                $wheres['c.endmonth'] = array('egt',$tim);    //结束时间年月日

                $wheres['a.id'] = array('in',$club);
                $wheres['c.light_time'] = array('elt',$opentime);

                $light_join = array(
                    'a left join  __PACKAGE__ b on a.package = b.id',
                    'left join  __PACKMONTH__ c on b.id = c.pid',
                );
                //开灯时间
                $light_list = M('club')->join($light_join)->field('c.light_time')->where($wheres)->select();
                if($light_list){
                    $field = ('holiday,lightprice');
                }else{
                    $field = ('holiday');
                }
                $price_list = M('club')->field($field)->join($join)->where($where)->select();
                foreach($price_list as $key=>$val){
                    $price_list[$key]['all_price'] =  $val['holiday']+$val['lightprice'];
                    $price_list[$key]['club_price'] =  $val['holiday'];
                    $price = $price + $price_list[$key]['all_price'];
                }
            }else{
                $week = date("w",strtotime($openmonth));
                if($week == '6' || $week == '0'){
                    $wheres['c.start_time'] = array('elt',$opentime);   //开始营业时间
                    $wheres['c.end_time'] = array('egt',$opentime);    //结束营业时间

                    $tim=str_replace('/', '', I('get.openmonth','','trim'));//当前时间年月日

                    $wheres['c.startmonth'] = array('elt',$tim); //开始时间年月日
                    $wheres['c.endmonth'] = array('egt',$tim);    //结束时间年月日

                    $wheres['a.id'] = array('in',$club);
                    $wheres['c.light_time'] = array('elt',$opentime);

                    $light_join = array(
                        'a left join  __PACKAGE__ b on a.package = b.id',
                        'left join  __PACKMONTH__ c on b.id = c.pid',
                    );
                    //开灯时间
                    $light_list = M('club')->join($light_join)->field('c.light_time')->where($wheres)->select();

                    if($light_list){
                        $field = ('doubleday,lightprice');
                    }else{
                        $field = ('doubleday');
                    }
                    $price_list = M('club')->field($field)->join($join)->where($where)->select();
                    foreach($price_list as $key=>$val){
                        $price_list[$key]['all_price'] =  $val['doubleday']+$val['lightprice'];
                        $price_list[$key]['club_price'] =  $val['doubleday'];
                        $price = $price + $price_list[$key]['all_price'];
                    }

                }else{
                    $wheres['c.start_time'] = array('elt',$opentime);   //开始营业时间
                    $wheres['c.end_time'] = array('egt',$opentime);    //结束营业时间

                    $tim=str_replace('/', '', I('get.openmonth','','trim'));//当前时间年月日

                    $wheres['c.startmonth'] = array('elt',$tim); //开始时间年月日
                    $wheres['c.endmonth'] = array('egt',$tim);    //结束时间年月日

                    $wheres['a.id'] = array('in',$club);
                    $wheres['c.light_time'] = array('elt',$opentime);

                    $light_join = array(
                        'a left join  __PACKAGE__ b on a.package = b.id',
                        'left join  __PACKMONTH__ c on b.id = c.pid',
                    );
                    //开灯时间
                    $light_list = M('club')->join($light_join)->field('c.light_time')->where($wheres)->select();

                    if($light_list){
                        $field = ('workday,lightprice');
                    }else{
                        $field = ('workday');
                    }

                    $price_list = M('club')->field($field)->join($join)->where($where)->select();
                    foreach($price_list as $key=>$val){
                        $price_list[$key]['all_price'] =  $val['workday']+$val['lightprice'];
                        $price_list[$key]['club_price'] =  $val['workday'];
                        $price = $price + $price_list[$key]['all_price'];

                    }

                }
            }
            //获取操作时间 为当时的时间
            $this->assign('siteinfo', $siteinfo);
            $this->assign('clubinfo', $clubinfo);
            $this->assign('opentime', $opentime);
            $this->assign('openmonth', $openmonth);
            $this->assign('week_arr', array('星期日','星期一','星期二','星期三','星期四','星期五','星期六'));
            $this->assign('weeks',date("w",strtotime($openmonth)));
            $this->assign('price_list',$price_list);
            $this->assign('allprice',$price);
             //判断会员 还是非会员
            /*p(I('get.hid','','intval'));*/
            if(I('get.hid','','intval')){
                $this->assign('user','1');//用户已经登陆
            }else{
                $this->assign('user',''); //用户没有登陆
            }
            $this->display();
    }

    //查询用户个人信息
    public function member_info(){
        $vipnum = $_POST['vipnum'];
        if(M('member')->where(array('vipnum'=>$vipnum))->find()){
            $data = M('member')->where(array('vipnum'=>$vipnum))->find();
            $id = $data['id'];
            $openid = $data['openid'];

            $t = time();
            $start_month = $_POST['openmonth'];//定场月份
            $num_where['start_month']  =  $start_month;
            $start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t)); //一天开始时间
            $end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));//一天结束时间
            $num_where['add_time'] =array(between,array($start,$end));
            $num_where['_string'] =  "uid = '".$id."' or openid = '".$openid."'"; //或者等openid or uid
            $num_where['long_status'] = 0;
            $num_where['order_status'] = 1;
            $num_where['status'] = 0;
            $num_where['site_id'] = $_POST['site_id'];  //场馆ID
            $member =  M('ordersite')->where($num_where)->count();
            $assign['member'] = $member;
            $assign['dat'] = $data;
            echo json_encode($assign);die;


        }else{
            echo 0;
        }


    }
    //查询用户电话-对应用户今天订了几天场次
    public function t_tel(){
        /*$t_tel = $_POST['t_tel'];
        $t_name = $_POST['t_name'];
        $t = time();
        $start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t)); //一天开始时间
        $end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));//一天结束时间
        $num_where['add_time'] =array(between,array($start,$end));
        $start_month = $_POST['openmonth'];//定场月份
        $num_where['start_month']  =  $start_month;
        $num_where['long_status'] = 0;
        $num_where['order_status'] = 1;
        $num_where['site_id'] = $_POST['site_id'];
        $num_where['t_tel'] =  $t_tel;
        $num_where['t_name'] = $t_name;
        $member =  M('ordersite')->where($num_where)->count();
        echo  $member;die;*/
    }


    public function pay(){
        if(isset($_POST['code'])) {
            if ($_POST['code'] == $_SESSION['code']) {
                // 重复提交表单了
            } else {
                $_SESSION['code'] = $_POST['code']; //存储code

            $mod = D('ordersite');
            if (false === $data = $mod->create()) {
                IS_AJAX && $this->ajaxReturn(0, $mod->getError());
                $this->error($mod->getError());
            }
             $club_ids = $data['club_id'];
             $club_prices = $_POST['club_price'];
             $lightprices = $_POST['lightprice'];
             $order_number = $this->NoRand();
            if(M('ordersite')->where(array('order_number'=>$order_number))->find()){
                $order_number = $this->NoRand();
             }
             $data['order_number'] = $order_number;
             $uid = M('member')->where(array('vipnum'=>$_POST['vipnum']))->field('id')->find();
            if($uid){
                $data['uid'] =$uid['id'];
                $data['vipnum'] =$_POST['vipnum'];
            }

            foreach($club_ids as $key=>$val){
                $data['club_id'] = $val;
                $club_price = $club_prices[$key];
                $lightprice = $lightprices[$key];

                $data['club_price'] = $club_prices[$key]; //场地价钱
                $data['lightprice'] = $lightprices[$key]; //灯光价钱
                $data['site_price']=($club_price+$lightprice);//总价钱

                $data['add_time'] = time();
                $user= I('post.user','','intval');
                if(empty($user)){
                    $data['t_name'] = $_POST['name'];
                    $data['t_tel'] = $_POST['tel'];

                }
                //判断有没这条数据/或者过没过期
                $now_time = time();
                $order_time =  M('setting')->getField('order_time');
                $order_time = $order_time*60;
                $list = M('ordersite')->where("start_month = '{$data['start_month']}' and start_time = '{$data['start_time']}'
                and club_id = {$data['club_id']} and long_status = 0 and status =0 and (vipnum != '' or vipnum is null ) and site_id = '{$data['site_id']}' and (order_status = 1 or ( add_time + '".$order_time."' > '".$now_time."' and order_status = 0))")->find();
                //锁定
                $list_lock= M('club_lock')->where("start_month = '{$data['start_month']}' and start_time = '{$data['start_time']}'
                 and club_id = {$data['club_id']} and status = 1 and site_id = '{$data['site_id']}'") ->find();
                if(!empty($list)){
                    $info = M('site')->where(array('id'=>$list['site_id']))->find();
                    $url = U('Shortsite/club',array('id'=>$list['site_id'],'reserve'=>$info['reserve'],'nm_reserve'=>$info['nm_reserve'],'hydc_num'=>$info['hydc_num'],'fhydc_num'=>$info['fhydc_num']));
                    echo "<script>alert('这个场地已经被预定');window.location.href='".$url."'</script>";
                }elseif(!empty($list_lock)){
                    $info = M('site')->where(array('id'=>$list_lock['site_id']))->find();
                    $url = U('Shortsite/club',array('id'=>$list_lock['site_id'],'reserve'=>$info['reserve'],'nm_reserve'=>$info['nm_reserve'],'hydc_num'=>$info['hydc_num'],'fhydc_num'=>$info['fhydc_num']));
                    echo "<script>alert('这个场地已经被锁定');window.location.href='".$url."'</script>";
                }else{
                    $order_id  = $mod->add($data);
                    $order_number =   M('ordersite')->where(array('id'=>$order_id))->getField('order_number');
                    $_SESSION['czy_order_number'] = $order_number;

                }


             }
            }

        }

            $allprice =  I('post.site_price','','trim');

            $member = M('member')->where(array('vipnum'=>$_POST['vipnum']))->find();
            $mon =  M('member_money')->field('sum(money) mon')->where('type in (1,2,3,12,14,17,22) and state = 1 and mid='.intval($member['id']))->select();//余额
            $jf =  M('member_money')->field('sum(money) jf')->where('type in (4,6,7,8,10,11,13,16,18) and state = 1 and mid='.intval($member['id']))->select();
            $member['mom'] = $mon[0]['mon']?$mon[0]['mon']:$member['money'];
            $member['jf'] = $jf[0]['jf']?$jf[0]['jf']+0:0;
            $this->assign('allprice',$allprice);
            $this->assign('member',$member);
            $bili =  M('setting')->where('id=1')->getField('integral');
            $this->assign('bili',$bili);

            $vipnum = I('post.vipnum');
            if(!empty($vipnum)){
                $id = M('member')->where("vipnum='".$vipnum."'")->getField('id');
            }else{
                $user['name'] = I('post.name');
                $user['tel'] = I('post.tel');
                $id = M('member')->where($user)->getField('id');
            }
            $username = I('post.name');
            $usertel = I('post.tel');
            $this->assign('vipnum',$vipnum);
            $this->assign('username',$username);
            $this->assign('usertel',$usertel);
            $this->assign('hyxx_id',$id);

            $this->assign('is_member',$_POST['is_member']); //判断是不是 会员提交
            $this->display();


    }



    public function pay_sh(){
        $data = array();
        $member_money = M('member_money');
        $tijiao_jf = I('post.tijiao_jf');
        $tijiao_je = I('post.tijiao_je');
        $tijiao_wx = I('post.tijiao_wx');
        $tijiao_xj = I('post.tijiao_xj');

        $name = I('post.name');
        $tel = I('post.tel');
        $hyid = I('post.hyid');
        $vipnum = I('post.vipnum');

        $data['hy_num'] = I('post.hy_order_number');
        $data['mid'] = $hyid;
        $data['managerid'] = $this->weixininfo['id'];
        $data['managername'] = $this->weixininfo['username'];
        $data['vipnum'] = $vipnum;
        $data['order_number'] = $_POST['czy_order_number'];
        $data['addtime'] = time();
        $data['name'] = $name;
        $data['tel'] = $tel;
 
        if(!empty($tijiao_jf)){
            $data['money'] = -$tijiao_jf;
            $data['state'] = '1';
            $data['type'] = '13';//积分消费 订场
            $member_money->add($data);
        }

        if(!empty($tijiao_je)){
            $data['money'] = -$tijiao_je;
            $data['state'] = '1';
            $data['type'] = '14';//余额消费 订场
            $member_money->add($data);
        }

        if(!empty($tijiao_je)){
            $bili =  M('setting')->where('id=1')->getField('reward');//奖励比例
            $data['money'] = $tijiao_je*$bili;
            $data['state'] = '1';
            $data['type'] = '8';//余额消费送积分

            $member_money->add($data);
        }

        if(!empty($tijiao_xj)){
            $data['money'] = -$tijiao_xj;
            $data['state'] = '1';
            $data['type'] = '9';//余额消费 订场
            $member_money->add($data);
        }

        if(!empty($tijiao_wx) && $tijiao_wx!= 0){
            $data['money'] = -$tijiao_wx;
            $data['state'] = '0';
            $data['type'] = '15';//积分消费 订场
            $member_money->add($data);
        }

        if(!empty($tijiao_wx) && $tijiao_wx!= 0){
            $body = "二维码支付";

            $trade_no = $data['order_number'];
           // $Total_fee = $data['order_number'];
            $xfurl = XF_HTTP;
            $Total_fee = $tijiao_wx;
            $Total_fee = 0.01;

            $param1 = "body=".$body."&trade_no=".$trade_no."&Total_fee=".$Total_fee."&xfurl=".$xfurl."&id=".$order_id;
            echo "<script>location.href='api/wxqrpay/native.php?".$param1."'</script>";
//            $this->redirect('Member/erweima',array('jiner'=>$tijiao_wx));
        }else{
			$ww['order_number'] = $_POST['czy_order_number'];
			M('ordersite')->where($ww)->save(array('order_status'=>'1'));
			M('member_money')->where($ww)->save(array('state'=>'1'));
            $site_id = M('ordersite')->where($ww)->find();
            if($vipnum){
                $url =  U('home/Index/succ',array('name'=>'open_admin','hy'=>'1','site_id'=>$site_id['site_id']));
                echo "<script>alert('订场成功');window.location.href='".$url."'</script>";
            }else{
                $url = U('home/Index/succ',array('name'=>'open_admin','fhy'=>'1','site_id'=>$site_id['site_id']));
                echo "<script>alert('订场成功');window.location.href='".$url."'</script>";

            }
            //echo ('跳到成功页面');die;
        }

    }


    public function payajax(){
        $allprice = intval($_POST['allprice']);
        $jf = $_POST['val1'];
        $ye = $_POST['val2'];
        $integral =  M('setting')->field('integral')->find();
        $mom = round($jf/$integral['integral'],2);//剩余积分相当于多少钱
        $postall = intval($ye+$mom);
//        dump($allprice);
//        dump($postall);die;

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
            if($allprice > $postall) {
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
    public function NoRand($begin=10,$end=99,$limit=2){
        $rand_array=range($begin,$end);
        shuffle($rand_array);
        $list = array_slice($rand_array,0,$limit);
        foreach ($list as $value) {
            $string .= $value;
        }
        return date('ymd',time()).$string;
    }



}


