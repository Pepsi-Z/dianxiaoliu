<?php
class LongsiteAction extends membersAction
{
    public function _initialize() {
        parent::_initialize();
    }

   public function index(){
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
        $this->display();
    }

    //会员
    public function club(){
        for($i=0;$i<7;$i++) {
            $mod = M('club');
            $id = I('get.id','','intval');//场馆id
            $where['a.id'] = $id;
            $date=date('Y-m-d');  //当前日期
            $first=1; //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
            $w=date('w',strtotime($date));  //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
            $now_start=date(strtotime("$date -".($w ? $w - $first : 6).' days')); //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
            $now_end=date('Y-m-d',strtotime("$now_start +6 days"));  //本周结束日期
            $startmonth = date('md',$now_start+$i*86400);

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
        $this->assign('week_arr', array('周日','周一','周二','周三','周四','周五','周六'));
        $this->assign('i', $i);
        $this->assign('open_month',7);
        $this->assign('packlist', $packlist);
        $this->display();
    }



    public function ordersite(){
        $opentime = I('get.time','','intval');  //定场时间
        $openmonth=str_replace('-', '/', I('get.month','','trim'));//定场月份
        $pid   = I('get.pid','','intval');  //场馆id
        $where['a.id'] = $pid; //场馆id

        $where['d.start_time'] = array('elt',$opentime); //开始营业时间
        $where['d.end_time'] = array('egt',$opentime);   //结束营业时间

        $time = str_replace('-', '', I('get.month','','trim')); //当前时间年月日
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
            $month=date('Y',time()).'-'.(I('get.month','','trim'));
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
        //p($packlist);die;
        $assign['opentime'] = $opentime;
        $assign['openmonth'] = $openmonth;
        $assign['zhou'] = date("w",strtotime(date('Y',time()).'-'.(I('get.month','','trim'))));
        $assign['week_arr'] = array('周日','周一','周二','周三','周四','周五','周六');
        $assign['packlist'] = $packlist;
        echo json_encode($assign);die;



    }


    public function xunhuan(){
        $opentime = I('get.opentime','','intval');  //定场时间
        $openmonth = I('get.openmonth','','trim');  //定场月份
        $pid   = I('get.pid','','intval');  //场馆id
        $club   = I('get.club','','intval');  //场地id  取一个
        $this->assign('opentime',$opentime);
        $this->assign('openmonth',$openmonth);
        $this->assign('pid',$pid);
        $this->assign('club',$club);
        $this->display();



    }


    public function orderlist(){
            $opentime = I('get.opentime','','intval');  //定场时间
            $openmonths=str_replace('/', '-',date('Y',time()).'/'.I('get.openmonth','','trim'));//定场月份

            if(strtotime($openmonths.' '.$opentime.':00:00') < time()){
              //选择的时间月份 小于 当前时间  重新赋值
              $openmonths = date('Y-m-d',strtotime($openmonths)+7*86400);
            }
            $pid   = I('get.pid','','intval');  //场馆id
            $clubs   = I('get.club','','intval');  //场地id
            $xunhuan   = I('get.xunhuan','','intval');  //场地id
            $now_date = $openmonths;
            for($i=0;$i<$xunhuan;$i++){
                //echo $openmonths.'-----<br />';
                if($i == 0){
                    $openmonth= $openmonths;
                }else{
                    $openmonth= date('Y-m-d',strtotime($openmonths)+(7*86400));
                }

                  //p($openmonth);
                //$price = 0; //总价钱
                $club =  explode(',',$clubs);
                $map['id'] = array('in',$club);
                $siteinfo = M('site')->where(array('id'=>$pid))->field('site_name,id')->find();
                $clubinfo = M('club')->where($map)->field('club_name,id')->select();

                //已经被临时订场 如果这个月份 时间的 已经被预定
                $openmonth =  $this->chongfu($clubs,$openmonth,$opentime,$pid);
                //echo $openmonth.'=====<br />';

                if($i == 0){
                    $start_month= $openmonth;
                }

                $openmonths = $openmonth;

                /*$where['d.opentime'] =$opentime;*/
                $where['c.start_time'] = array('elt',$opentime);   //开始营业时间
                $where['c.end_time'] = array('egt',$opentime);    //结束营业时间
                //如果这个时间段 数据库已经存在
                $tim=str_replace('/', '', date('m/d',strtotime($openmonths)));//当前时间年月日

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

                $holiday= strtotime(date('Y-m-d',strtotime($openmonths)));//当前时间年月日

                $qujian['begin_time'] = array('elt',$holiday); //开始时间年月日
                $qujian['end_time'] = array('gt',$holiday);    //结束时间年月日
                //判断当前时间是节假日 还是 双休 还是工作日
                if(M('holiday')->where($qujian)->select()){//判断是否是节假日

                    $wheres['c.start_time'] = array('elt',$opentime);   //开始营业时间
                    $wheres['c.end_time'] = array('egt',$opentime);    //结束营业时间


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
                    $price_list[] = M('club')->field($field)->join($join)->where($where)->select();
                    foreach($price_list as $k=>$v){
                        foreach($v as $key=>$val){
                            $val[$k][$key]['all_price'] =  $val['holiday']+$val['lightprice'];
                            $price_list[$i][$key]['club_price'] =  $price_list[$i][$key]['holiday'];
                           /* $price_list[$i][$key]['club_price'] =  $price_list[$i][$key]['holiday'];
                            $price = $price+$val[$k][$key]['all_price'];*/
                        }
                    }
                }else{//判断是周六日
                    $week = date("w",strtotime($openmonth));
                    if($week == '6' || $week == '0'){
                        $wheres['c.start_time'] = array('elt',$opentime);   //开始营业时间
                        $wheres['c.end_time'] = array('egt',$opentime);    //结束营业时间


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
                        $price_list[] = M('club')->field($field)->join($join)->where($where)->select();
                        //p($price_list);die;
                        foreach($price_list as $k=>$v){
                            foreach($v as $key=>$val){
                                $val[$k][$key]['all_price'] =  $val['doubleday']+$val['lightprice'];
                                $price_list[$i][$key]['club_price'] =  $price_list[$i][$key]['doubleday'];
                                /*$price_list[$i][$key]['club_price'] =  $price_list[$i][$key]['doubleday'];
                                $price = $price+$val[$k][$key]['all_price'];*/
                            }

                        }
                    }else{//工作日
                        $wheres['c.start_time'] = array('elt',$opentime);   //开始营业时间
                        $wheres['c.end_time'] = array('egt',$opentime);    //结束营业时间


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
                        $price_list[] = M('club')->field($field)->join($join)->where($where)->select();
                        //p($price_list);
                        foreach($price_list as $k=>$v){
                            foreach($v as $key=>$val){
                                $price_list[$i][$key]['club_price'] =  $price_list[$i][$key]['workday'];
                                $val[$k][$key]['all_price'] =  $val['workday']+$val['lightprice'];
                                /*$price_list[$i][$key]['club_price'] =  $price_list[$i][$key]['workday'];
                                $price = $price+$val[$k][$key]['all_price'];*/
                            }
                        }


                    }
                }

            }

        $price = 0; //总价钱
        foreach($price_list as $kk =>$vv){
            foreach($vv as $kkk => $vvv){
                $all_price  = $vvv['club_price']+$vvv['lightprice'];
                $price = $price + $all_price;    //总价钱

            }


        }

            $this->assign('xunhuan', $xunhuan);
            $this->assign('tians', $openmonths);
            $this->assign('club', $clubs); //场馆
            $this->assign('siteinfo', $siteinfo);
            $this->assign('clubinfo', $clubinfo);
            $this->assign('opentime', $opentime);
            $this->assign('openmonth', $start_month);
            $this->assign('week_arr', array('星期日','星期一','星期二','星期三','星期四','星期五','星期六'));
            $this->assign('weeks',date("w",strtotime($openmonth)));
            $this->assign('allprice',$price);
            $this->assign('price_list',$price_list);
            $this->display();
    }


    //确定修改状态
    public function alert_confirm(){
        $data['status'] = 2; //2表示可选
        $alert_confirm = M('ordersite')->where(array('id'=>$_POST['id']))->save($data);
        echo $alert_confirm;die;
    }

     public function price_list(){
         $clubs   = I('get.club'); //场地
         $opentime = I('get.opentime','','intval');  //定场时间
         $openmonths=str_replace('/', '-',I('get.openmonth','','trim'));//定场月份
         $pid   = I('get.pid','','intval');  //场馆id

         if(strtotime($openmonths.' '.$opentime.':00:00') < time()){
             //选择的时间月份 小于 当前时间  重新赋值
             $openmonths = date('Y-m-d',strtotime($openmonths)+7*86400);
         }


         $xunhuan   = I('get.xunhuan','','trim');  //场地id
         for($i=0;$i<$xunhuan;$i++){

             //echo $openmonths.'-----<br />';
             if($i == 0){
                 $openmonth= $openmonths;
             }else{
                 $openmonth= date('Y-m-d',strtotime($openmonths)+(7*86400));
             }




             $club =  explode(',',$clubs);
             //已经被临时订场 如果这个月份 时间的 已经被预定
             $openmonth =  $this->chongfu($clubs,$openmonth,$opentime,$pid);
             //echo $openmonth.'=====<br />';

             if($i == 0){
                 $start_month= $openmonth;
             }

             $openmonths = $openmonth;

             $where['c.start_time'] = array('elt',$opentime);   //开始营业时间
             $where['c.end_time'] = array('egt',$opentime);    //结束营业时间


             $tim=str_replace('/', '', date('m/d',strtotime($openmonths)));//当前时间年月日
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
             $holiday= strtotime(date('Y-m-d',strtotime($openmonths)));//当前时间年月日
             $qujian['begin_time'] = array('elt',$holiday); //开始时间年月日
             $qujian['end_time'] = array('gt',$holiday);    //结束时间年月日

             //判断当前时间是节假日 还是 双休 还是工作日
             //dump(M('holiday')->where($qujian)->find());
             if(M('holiday')->where($qujian)->select()){//判断是否是节假日

                 $wheres['c.start_time'] = array('elt',$opentime);   //开始营业时间
                 $wheres['c.end_time'] = array('egt',$opentime);    //结束营业时间

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
                 $price_list[] = M('club')->field($field)->join($join)->where($where)->select();
                 foreach($price_list as $k=>$v){
                     foreach($v as $key=>$val){
                         $price_list[$i][$key]['openmonth'] = $openmonth;
                         $price_list[$i][$key]['opentime'] = $opentime;
                         $price_list[$i][$key]['club_price'] =  $price_list[$i][$key]['holiday'];

                         /*$val[$k][$key]['all_price'] =  $price_list[$i][$key]['holiday']+$price_list[$i][$key]['lightprice'];

                         $price = $price+$val[$k][$key]['all_price'];

                         $clubprices  = $clubprices+$price_list[$i][$key]['club_price'];
                         $lightprices = $lightprices+$val['lightprice'];*/

                     }
                 }
             }else{//判断是周六日

                 $week = date("w",strtotime($openmonth));
                 if($week == '6' || $week == '0'){
                     $wheres['c.start_time'] = array('elt',$opentime);   //开始营业时间
                     $wheres['c.end_time'] = array('egt',$opentime);    //结束营业时间
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
                     $price_list[] = M('club')->field($field)->join($join)->where($where)->select();
                     foreach($price_list as $k=>$v){
                         foreach($v as $key=>$val){
                             $price_list[$i][$key]['openmonth'] = $openmonth;
                             $price_list[$i][$key]['opentime'] = $opentime;
                             $price_list[$i][$key]['club_price'] =  $price_list[$i][$key]['doubleday'];


                             //$val[$k][$key]['all_price'] =  $val['doubleday']+$val['lightprice'];
                             //$price = $price+$val[$k][$key]['all_price'];
                            /* $val[$k][$key]['all_price'] =  $price_list[$i][$key]['doubleday']+$price_list[$i][$key]['lightprice'];
                             $price = $price+$val[$k][$key]['all_price'];*/
                             /*$clubprices  = $clubprices+$price_list[$i][$key]['doubleday'];
                             $lightprices = $lightprices+$val['lightprice'];*/
                         }
                     }
                 }else{//工作日
                     $wheres['c.start_time'] = array('elt',$opentime);   //开始营业时间
                     $wheres['c.end_time'] = array('egt',$opentime);    //结束营业时间
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
                     $price_list[] = M('club')->field($field)->join($join)->where($where)->select();
                     foreach($price_list as $k=>$v){
                         foreach($v as $key=>$val){
                             $price_list[$i][$key]['openmonth'] = $openmonth;
                             $price_list[$i][$key]['opentime'] = $opentime;
                             $price_list[$i][$key]['club_price'] =  $price_list[$i][$key]['workday'];
                             //$val[$k][$key]['all_price'] =  $val['workday']+$val['lightprice'];
                             //$price = $price+$val[$k][$key]['all_price'];
                             /*$clubprices  = $clubprices+$price_list[$i][$key]['club_price'];
                             $lightprices = $lightprices+$val['lightprice'];*/
                             /*$val[$k][$key]['all_price'] =  $price_list[$i][$key]['workday']+$price_list[$i][$key]['lightprice'];
                             $price = $price+$val[$k][$key]['all_price'];*/


                         }
                     }
                 }
             }

         }


         $price = 0; //总价钱
         $clubprices = 0; //场地总价钱
         $lightprices = 0; //灯光总价钱
         //p($price_list);die;
         foreach($price_list as $kk =>$vv){
              foreach($vv as $kkk => $vvv){
                  $all_price  = $vvv['club_price']+$vvv['lightprice'];
                  $clubprice  = $vvv['club_price'];
                  $lightprice = $vvv['lightprice'];
                  $price = $price + $all_price;    //总价钱
                  $clubprices = $clubprices + $clubprice;    //场地
                  $lightprices = $lightprices + $lightprice;  //开灯
              }


         }
            //p($price_list);
         $this->assign('price_list', $price_list);
         $this->assign('clubprices',$clubprices); //总价钱
         $this->assign('lightprices',$lightprices); //场地总价钱
         $this->assign('allprice',$price); //灯光总价钱
         $this->display();

    }
    //查询用户个人信息
    public function member_info(){
        $vipnum = $_POST['vipnum'];
        if(M('member')->where(array('vipnum'=>$vipnum))->find()){
            $data = M('member')->where(array('vipnum'=>$vipnum))->find();
            echo  json_encode($data);die;

        }else{
            echo 0;
        }


    }


    public function pay(){
            $mod = D('ordersite');
            if(isset($_POST['code'])) {
            if ($_POST['code'] !== $_SESSION['code']) {

            $_SESSION['code'] = $_POST['code']; //存储code
            $site_id   = I('post.site_id'); //场馆

            $clubs   = I('post.club_id'); //场地
            $opentime = I('post.start_time','','intval');  //定场时间
            $openmonths=I('post.start_month','','trim');//定场月份

            if(strtotime($openmonths.' '.$opentime.':00:00') < time()){
                //选择的时间月份 小于 当前时间  重新赋值
                $openmonths = date('Y-m-d',strtotime($openmonths)+7*86400);
            }

            $xunhuan   = I('post.xunhuan','','trim');  //循环周数
            $vipnum =  I('post.vipnum'); //会员卡号
            $member = M('member')->where(array('vipnum' =>$vipnum))->field('id')->find();
             $order_number = $this->NoRand();
            if(M('ordersite')->where(array('order_number'=>$order_number))->find()){
                $order_number = $this->NoRand();
             }
             $data['vipnum'] = $vipnum;
             $data['site_id'] = $site_id;
             $data['club_id'] = $clubs;
             $data['start_time'] = $opentime;
             $data['long_status'] =1 ;//表示长期定场
             $data['order_number'] = $order_number; //订单号
             $data['uid'] = $member['id'];  //uid
             $data['add_time'] = time();
        for($i=0;$i<$xunhuan;$i++){

            if($i == 0){
                $openmonth= $openmonths;
            }else{
                $openmonth= date('Y-m-d',strtotime($openmonths)+(7*86400));
            }

            $club =  explode(',',$clubs);
            //已经被临时订场 如果这个月份 时间的 已经被预定
            $openmonth =  $this->chongfu($clubs,$openmonth,$opentime,$site_id);
            //echo $openmonth.'=====<br />';

            if($i == 0){
                $start_month= $openmonth;
            }

            $openmonths = $openmonth;

            $where['c.start_time'] = array('elt',$opentime);   //开始营业时间
            $where['c.end_time'] = array('egt',$opentime);    //结束营业时间

            $tim=str_replace('/', '', date('m/d',strtotime($openmonths)));//当前时间年月日

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
            $holiday= strtotime(date('Y-m-d',strtotime($openmonths)));//当前时间年月日
            $qujian['begin_time'] = array('elt',$holiday); //开始时间年月日
            $qujian['end_time'] = array('gt',$holiday);    //结束时间年月日
            //判断当前时间是节假日 还是 双休 还是工作日
            //dump(M('holiday')->where($qujian)->find());
            if(M('holiday')->where($qujian)->select()){//判断是否是节假日
                $wheres['c.start_time'] = array('elt',$opentime);   //开始营业时间
                $wheres['c.end_time'] = array('egt',$opentime);    //结束营业时间

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
                $price_list[] = M('club')->field($field)->join($join)->where($where)->select();
                foreach($price_list as $k=>$v){
                    foreach($v as $key=>$val){
                        $price_list[$i][$key]['openmonth'] = $openmonth;
                        $price_list[$i][$key]['opentime'] = $opentime;
                        $price_list[$i][$key]['club_price'] =  $price_list[$i][$key]['holiday'];
                        $val[$i][$key]['all_price'] =  $val['holiday']+$val['lightprice'];

                    }
                }
            }else{//判断是周六日
                $week = date("w",strtotime($openmonth));
                if($week == '6' || $week == '0'){
                    $wheres['c.start_time'] = array('elt',$opentime);   //开始营业时间
                    $wheres['c.end_time'] = array('egt',$opentime);    //结束营业时间


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
                    $price_list[] = M('club')->field($field)->join($join)->where($where)->select();
                    foreach($price_list as $k=>$v){
                        foreach($v as $key=>$val){
                            $price_list[$i][$key]['openmonth'] = $openmonth;
                            $price_list[$i][$key]['opentime'] = $opentime;
                            $price_list[$i][$key]['club_price'] =  $price_list[$i][$key]['doubleday'];
                            $val[$i][$key]['all_price'] =  $val['doubleday']+$val['lightprice'];

                        }
                    }
                }else{//工作日
                    $wheres['c.start_time'] = array('elt',$opentime);   //开始营业时间
                    $wheres['c.end_time'] = array('egt',$opentime);    //结束营业时间

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
                    $price_list[] = M('club')->field($field)->join($join)->where($where)->select();
                    foreach($price_list as $k=>$v){
                        foreach($v as $key=>$val){
                            $price_list[$i][$key]['openmonth'] = $openmonth;
                            $price_list[$i][$key]['opentime'] = $opentime;
                            $price_list[$i][$key]['club_price'] =  $price_list[$i][$key]['workday'];
                            $val[$i][$key]['all_price'] =  $val['workday']+$val['lightprice'];
                        }

                    }

                }
            }

        }
     }
   }

         //遍历插入循环数量
         foreach($price_list as $k=>$vals){
             foreach($vals as $key=>$val){
                 $data['club_price'] =  $val['club_price'];
                 $data['lightprice'] =  $val['lightprice'];
                 $data['site_price'] =  $val['club_price']+$val['lightprice'];
                 $data['start_month'] = $val['openmonth'];
                 M('ordersite')->add($data);

             }

         }


        $bili =  M('setting')->where('id=1')->getField('integral');
        $this->assign('bili',$bili);


        if(!empty($_POST['vipnum'])){
            $id = M('member')->where("vipnum='".$_POST['vipnum']."'")->getField('id');
        }
        $mom =  M('member_money')->field('sum(money) mom')->where('state = 1 and type in (1,2,3,12,14,17,22) and mid='.intval
            ($id))->find();//余额
        $jf =  M('member_money')->field('sum(money) jf')->where('state = 1 and type in (4,6,7,8,10,11,13,16,18) and mid='
            .intval($id))
            ->find();//积分



        $_SESSION['changqi_order_number'] = $order_number;
        $this->assign('hy_id',$id);
        $this->assign('vipnum',$_POST['vipnum']);

        $this->assign('hy_mom',$mom);
        $this->assign('hy_jf',$jf);

        $this->assign('allprice',I('post.site_price')); //接收过来的总价格
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

        //$data['hy_num'] = I('post.hy_order_number');
        $data['mid'] = $hyid;
        $data['managerid'] = $this->weixininfo['id'];
        $data['managername'] = $this->weixininfo['username'];
        $data['vipnum'] = $vipnum;
        $data['order_number'] = $_POST['changqi_order_number'];
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
           /* $Total_fee = $data['order_number'];*/
            $xfurl = XF_HTTP;
            $Total_fee = $tijiao_wx;
            $Total_fee = 0.01;

            $param1 = "body=".$body."&trade_no=".$trade_no."&Total_fee=".$Total_fee."&xfurl=".$xfurl."&id=".$order_id;
            echo "<script>location.href='api/wxqrpay/native.php?".$param1."'</script>";
//            $this->redirect('Member/erweima',array('jiner'=>$tijiao_wx));
        }else{
			$ww['order_number'] = $_POST['changqi_order_number'];
			M('ordersite')->where($ww)->save(array('order_status'=>'1'));
			M('member_money')->where($ww)->save(array('state'=>'1'));
            $site_id = M('ordersite')->where($ww)->find();

            $url = U('home/Index/succ',array('name'=>'open_admin','changqi'=>'1','site_id'=>$site_id['site_id']));
            echo "<script>alert('订场成功');window.location.href='".$url."'</script>";//echo ('跳到成功页面');die;
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





//    public function payajax(){
//        $jf = $_POST['jf'];
//        $ye = $_POST['ye'];
//        $allprice = $_POST['allprice'];
//        $integral =  M('setting')->field('integral')->find();
//        $integrals = round($integral['integral'],2);  //1元等于这么多积分
//        $wpay = $allprice - ($ye+($jf/$integrals)); //微信应该支付的钱了
//        echo $wpay;die;
//
//    }

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


    public function chongfu($clubs,$openmonth,$opentime,$pid){
        //已经被临时订场 如果这个月份 时间的 已经被预定
        $last_info = M('ordersite')->where("club_id in (".$clubs.") and start_month ='".$openmonth."' and start_time = '".$opentime."' and site_id = '".$pid."' and order_status = 1 and status = 0")->find();
        $club_lock = M('club_lock')->where("club_id in (".$clubs.") and start_month ='".$openmonth."' and start_time = '".$opentime."' and site_id = '".$pid."' and status = 1")->find();
        if($last_info){
            $lasts_month = $last_info['start_month'];
            $openmonth= date('Y-m-d',strtotime($lasts_month)+(7*86400));
            $openmonths = date('Y-m-d',strtotime($lasts_month)+(7*86400));  //下一周时间
            $openmonth = $this->chongfu($clubs,$openmonth,$opentime,$pid);
        }elseif($club_lock){
            $club_lock_month = $club_lock['start_month'];
            $openmonth= date('Y-m-d',strtotime($club_lock_month)+(7*86400));
            $openmonths = date('Y-m-d',strtotime($club_lock_month)+(7*86400));  //下一周时间
            $openmonth = $this->chongfu($clubs,$openmonth,$opentime,$pid);
        }

        return $openmonth;

    }



}


