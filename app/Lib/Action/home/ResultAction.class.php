<?php
require_once './app/Common/JsApi.php';
class ResultAction extends baseAction {
    public function _initialize() {
        parent::_initialize();
//        $this->get_user_openid();
    }
    //赛事进程
    public function index(){
        $bid = $_SESSION['uadmin']['bid'];
        $arr=array('','一','二','三','四','五');
        $round = M('game')->where(array('id'=>$bid))->getField('round');
        for($i=1;$i<=$round;$i++){
            $m = M('record')->where(array('gid'=>$bid,'round'=>$i,'genre'=>1,'status'=>1))->find();
            if(!$m){
                $data[]['status'] = 0;
            }else{
                $data[]['status'] = 1;
            }
        }
        $this->assign('round',$round);
        $this->assign('arr',$arr);
        $this->assign('data',$data);
        $this->display();
    }
    public function result(){
        $bid = $_SESSION['uadmin']['bid'];
        //当前轮次
        $n =  I('get.round');
        $data = M('apply')->where(array('gid'=>$bid,'did'=>'0','is_detele'=>0))->find();
        if($data){
            $this->error("还没有匹配完队友，无法进行循环赛");
        }
        $m = $n-1;
        $nnn = M('record')->where(array('gid'=>$bid,'round'=>$m,'genre'=>1))->find();
        $arr=array('','一','二','三','四','五');
//        if(!$nnn && $n!=1){
//            $this->error("请先开始第".$arr[$m]."轮比赛",U('Result/index'));
//        }

        $status =  M('record')->where(array('gid'=>$bid,'round'=>$n,'genre'=>1))->find();
        //若当前比赛当前轮次没有匹配战队记录则匹配战队
        if(!$status){
            $list = M('apply ')->table('weixin_apply a')
                ->field('a.*,u.img,u.nickname name,u.sscore,d.img dimg,d.nickname dname,d.sscore dsscore')
                ->join('weixin_user u on a.uid = u.id ')
                ->join('weixin_user d on a.did = d.id ')
                ->where(array('a.gid'=>$bid,'a.is_detele'=>0,'a.pay'=>1))->select();
            foreach($list as $k=>$v){
                $list[$k]['score'] = M('user')->where(array('id'=>array('in',array($v['uid'],$v['did']))))->getField('sum(sscore)');
                if(!$list[$k]['score']){
                    $list[$k]['score'] = 0;
                }
                $score[] = $list[$k]['score'];
            }
            //按照比分给队员排序
            array_multisort($score, SORT_DESC, $list);
//            p($list);die;
            $this->xh($list,$n);
        }

        $record = M('record')->where(array('gid'=>$bid,'round'=>$n,'genre'=>1))->select();

        foreach($record as $k=>$v){
            $uu[$k]['member1'] = M('apply ')->table('weixin_apply a')
                ->field('a.*,u.img,u.nickname name,u.name uname,u.sscore,d.img dimg,d.nickname dname,d.name duname,d.sscore dsscore')
                ->join('weixin_user u on a.uid = u.id ')
                ->join('weixin_user d on a.did = d.id ')
                ->where(array('a.id'=>$v['member1']))->find();
            $uu[$k]['member1']['oscore'] = $v['oscore'];
            $uu[$k]['member1']['bid'] = $v['id'];
//            $uu[$k]['member1']['win'] = $v['win'];
            $uu[$k]['member2'] = M('apply ')->table('weixin_apply a')
                ->field('a.*,u.img,u.nickname name,u.name uname,u.sscore,d.img dimg,d.nickname dname,d.name duname,d.sscore dsscore')
                ->join('weixin_user u on a.uid = u.id ')
                ->join('weixin_user d on a.did = d.id ')
                ->where(array('a.id'=>$v['member2']))->find();
            $uu[$k]['member2']['tscore'] = $v['tscore'];
        }
//        p($uu);
        $this->assign('uu',$uu);
        $this->assign('arr',$arr);
        $this->assign('n',$n);
        $this->assign('status',I('get.status'));
        $this->display();
    }
    //循环赛匹配
    public function xh($list,$n){
        //echo $n;
//        p($list);die;
        $bid = $_SESSION['uadmin']['bid'];
        $a = rand(1,999);
        foreach($list as $k=>$v){
            $count = count($list);
            //取出第一个战队
            if($k == 0){
                $sscore = $v['score'];
                $id = $v['id'];
                $m = $k;
                continue;
            }
            //比分差距
            foreach($list as $kk=>$vv){
                if($id == $vv['id']){
                    continue;
                }
                $score =  $sscore - $vv['score'];
                if($score<120 && $score >0){
                    $d1[] = $vv['id'];
                }
            }
            if(empty($d1)){
                foreach($list as $kk=>$vv){
                    if($id == $vv['id']){
                        continue;
                    }
                    $score =  $sscore - $vv['score'];
                    if($score<180 && $score >0){
                        $d2[] = $vv['id'];
                    }
                }
            }
            if(empty($d2)){
                foreach($list as $kk=>$vv){
                    if($id == $vv['id']){
                        continue;
                    }
                    $score =  $sscore - $vv['score'];
                    if($score<240 && $score >0){
                        $d3[] = $vv['id'];
                    }
                }
            }

            if(empty($d3)){
                foreach($list as $kk=>$vv){
                    if($id == $vv['id']){
                        continue;
                    }
                    $score =  $sscore - $vv['score'];
                    if($score<300 && $score >0){
                        $d4[] = $vv['id'];
                    }
                }
            }

            if(!empty($d1) || !empty($d2) || !empty($d3)|| !empty($d4)){
                if(!empty($d1)){

                    $nn = count($d1) -1;
//                    echo $nn.'<hr>';
                    $i = rand(0,$nn);
//                    echo $i;die;
                    $m2 = $d1[$i];
                }elseif(!empty($d2)){

                    $nn = count($d2) -1;
                    $i = rand(0,$nn);
                    $m2 = $d2[$i];
                }elseif(!empty($d3)){

                    $nn = count($d3) -1;
                    $i = rand(0,$nn);
                    $m2 = $d3[$i];
                }elseif(!empty($d4)){

                    $nn = count($d4) -1;
                    $i = rand(0,$nn);
                    $m2 = $d4[$i];
                }

                //匹配比分差距120或180或240或300的战队
                $arr[$a]['member1'] = $id;
                $arr[$a]['member2'] = $m2;
                $arr[$a]['gid'] = $bid;
                $arr[$a]['round'] = $n;
                //p($arr);
                //之前不能和该战队有过比赛记录  本比赛内
                $where =  M('record')->where($arr[$a])->find();
                if($arr[$a]['member2'] && $arr[$a]['member1'] && !$where){
                    $ss = "`gid` =".$bid." and (( `member1` ='".$arr[$a]['member2'];
                    $ss .= "' AND `member2` = '".$arr[$a]['member1']."') OR (`member1` ='".$arr[$a]['member1'];
                    $ss .= "' AND `member2` = '".$arr[$a]['member2']."'))";
                    $sss  =  M('record')->where($ss)->find();
                    if($sss){
                        $nn = count($list) -1;
                        if($nn == 1){
                            M('record')->where(array('gid'=>$bid,'round'=>$n))->delete();
                        }
                    }else{
                        M('record')->add($arr[$a]);
                        foreach($list as $kkkk=>$vvvv){
                            if($vvvv['id'] == $m2){
                                $kkk = $kkkk;
                            }
                        }
                        unset($list[$kkk]);
                        unset($list[$m]);
                        break;
                    }
                }

            }else{
                //随机匹配战队
                $nn = count($list) -1;
                $i = rand(1,$nn);
                $arr[$a]['member1'] = $id;
                $arr[$a]['member2'] = $list[$i]['id'];
                $arr[$a]['gid'] = $bid;
                $arr[$a]['round'] = $n;
                //之前不能和该战队有过比赛记录  本比赛内
                $where =  M('record')->where($arr[$a])->find();
                if($arr[$a]['member2'] && $arr[$a]['member1'] && !$where){
                    $ss = "`gid` =".$bid." and (( `member1` ='".$arr[$a]['member2'];
                    $ss .= "' AND `member2` = '".$arr[$a]['member1']."') OR (`member1` ='".$arr[$a]['member1'];
                    $ss .= "' AND `member2` = '".$arr[$a]['member2']."'))";
                    $sss  =  M('record')->where($ss)->find();
                    if($sss){
                        if($nn == 1){
                            M('record')->where(array('gid'=>$bid,'round'=>$n))->delete();
                        }
                    }else{
                        M('record')->add($arr[$a]);
                        unset($list[$i]);
                        unset($list[$m]);
                        break;
                    }
                }
            }
            $c = count($list);
            if($c == $count){
                $k2 = $k+1;
                $sscore = $list[$k2]['score'];
                $id = $list[$k2]['id'];
                $m = $k2;
                continue;
            }

        }

        //战队数大于1继续匹配
        if(count($list)>1){
            $list = array_reverse(array_reverse($list));
            $this->xh($list,$n);
        }
        return $arr;

    }
    public function score(){
        $bid = $_SESSION['uadmin']['bid'];
        $round = M('game')->where(array('id'=>$bid))->getField('round');
        $n = I('get.round');
//         p($_POST);die;
        foreach ($_POST as $k => $v) {
            if (intval($v['member1']['sc']) == intval($v['member2']['sc'])) {
                $this->error('两队分数不可以一样');
            }
            $cha = abs($v['member1']['sc']-$v['member2']['sc']);
            if($cha>6){
                $this->error('两队分数相差不可以大于6');
            }
        }
        foreach($_POST as $k=>$v){
            if($v['member1']['sc'] > $v['member2']['sc']){
                $data['win'] = $v['member1']['id'];
                $data['fail'] = $v['member2']['id'];
            }else{
                $data['win'] = $v['member2']['id'];
                $data['fail'] = $v['member1']['id'];
            }
            $wids =  M('apply')->field('uid,did')->where(array('id'=>$v['member1']['id']))->find();

            $fids =  M('apply')->field('uid,did')->where(array('id'=>$v['member2']['id']))->find();
//            $s1 = M('score_log')->where(array('uid'=>$wids['uid'],'type'=>2))->getField('sum(score) score');
//            $s2 = M('score_log')->where(array('uid'=>$wids['did'],'type'=>2))->getField('sum(score) score');
//            $s3 = M('score_log')->where(array('uid'=>$fids['uid'],'type'=>2))->getField('sum(score) score');
//            $s4 = M('score_log')->where(array('uid'=>$fids['did'],'type'=>2))->getField('sum(score) score');
            $s1 = M('user')->where(array('id'=>$wids['uid']))->getField('sscore');
            $s2 = M('user')->where(array('id'=>$wids['did']))->getField('sscore');
            $s3 = M('user')->where(array('id'=>$fids['uid']))->getField('sscore');
            $s4 = M('user')->where(array('id'=>$fids['did']))->getField('sscore');

            $data['fen1'] = $wids['uid'].','.$s1;
            $data['fen2'] = $wids['did'].','.$s2;
            $data['fen3'] = $fids['uid'].','.$s3;
            $data['fen4'] = $fids['did'].','.$s4;
//            if(!$s1 || !$s2 || !$s3 || !$s4){
//                unset($data['fen1']);
//                unset($data['fen2']);
//                unset($data['fen3']);
//                unset($data['fen4']);
//            }
            $data['oscore'] = $v['member1']['sc'];
            $data['tscore'] = $v['member2']['sc'];
            $data['status'] = 1;
            M('record')->where(array('id'=>$k))->save($data);

        }
        //若是最后一轮比赛分出胜负组
        if($round == $n){
            $fid = M('record')->where(array('gid'=>$bid))->getField('fail',true);
            $aid = M('apply')->where(array('gid'=>$bid,'is_detele'=>0))->getField('id',true);
            //失败零次的放在胜者组
            foreach($aid as $v){
                $ww['xwin'] = M('record')->where(array('gid'=>$bid,'win'=>$v))->count();
                $ww['xfail'] = M('record')->where(array('gid'=>$bid,'fail'=>$v))->count();
                if(!in_array($v,$fid)){
                    $win[] = $v;
                }
                M('apply')->where(array('gid'=>$bid,'id'=>$v))->save($ww);
            }
            //失败一次的放在败者组
            foreach($aid as $v) {
                $c = M('record')->where(array('gid' => $bid, 'fail' => $v))->count();

                if ($c == 1) {
                    $fail[] = $v;
                }

            }
            //若败者组就只有一组则将失败两次中分最高的放入败者组
            if(count($fail) == 1){
                foreach($aid as $v) {
                    $cc = M('record')->where(array('gid' => $bid, 'fail' => $v))->count();
                    if ($cc == 2) {
                        $ff[] = $v;
                    }
                }
                foreach($ff as $k=>$f){
                    $apply = M('apply')->where(array('id'=>$f))->find();
                    $uscore =M('score')->where(array('uid'=>$apply['uid']))->getField('score');
                    $dscore = M('score')->where(array('uid'=>$apply['did']))->getField('score');
                    if($uscore>$dscore){
                        $max = $uscore;
                        $min = $dscore;
                    }else{
                        $min = $uscore;
                        $max = $dscore;
                    }
                    $applys[$k]['sc'] = $max*0.6+$min*0.4;
                    $applys[$k]['id'] = $f;
                    $sss[] = $applys[$k]['sc'];
                }
                //按照比分给队员排序
                array_multisort($sss, SORT_DESC, $applys);
                $fail[] = $applys[0]['id'];
            }

            foreach($win as $v){
                $data['aid'] = $v;
                $data['type'] = 1;
                $data['gid'] = $bid;
                //胜者组放在淘汰赛记录表(eliminate)里type=1
                $wins = implode(',',$win);
                M('apply')->where(array('id'=>array('in',$wins)))->setField('zu',1);
                M('eliminate')->add($data);
            }
            foreach($fail as $v) {
                $fails = implode(',',$fail);
                $arr['aid'] = $v;
                $arr['type'] = 2;
                $arr['gid'] = $bid;
                M('apply')->where(array('id'=>array('in',$fails)))->setField('zu',2);
                //败者组放在淘汰赛记录表(eliminate)里type=2
                M('eliminate')->add($arr);
            }

        }
        $this->redirect(U('Result/index'));
    }
    //
    public function rank(){
        $bid = $_SESSION['uadmin']['bid'];
        $list = M('apply ')->table('weixin_apply a')
            ->field('a.*,u.img,u.nickname name,u.name uname,u.sscore,d.img dimg,d.nickname dname,d.name duname,d.sscore dsscore')
            ->join('weixin_user u on a.uid = u.id ')
            ->join('weixin_user d on a.did = d.id ')
            ->where(array('a.gid'=>$bid,'is_detele'=>0))->order('a.xwin desc')->select();
        $lists = M('apply ')->table('weixin_apply a')
            ->field('a.*,u.img,u.nickname name,u.name uname,u.sscore,d.img dimg,d.nickname dname ,d.name duname,d.sscore dsscore')
            ->join('weixin_user u on a.uid = u.id ')
            ->join('weixin_user d on a.did = d.id ')
//            ->join('weixin_eliminate e on  a.id = e.aid')
            ->where(array('a.gid'=>$bid,'is_detele'=>0))->order('a.win desc,a.xwin desc')->select();
//        p($lists);
        $r= M('eliminate')->where(array('gid'=>$bid,'rank'=>1))->find();
        $rank = M('eliminate')->where(array('gid'=>$bid))->select();
        if($r){
            $rid = M('eliminate')->where(array('gid'=>$bid))->getField('aid',true);
            $rids = implode(',',$rid);
            $datas1 =M('apply ')->table('weixin_apply a')
                ->field('a.*,u.img,e.rank,u.nickname name,u.name uname,u.sscore,d.img dimg,d.nickname dname ,d.name duname,d.sscore dsscore')
                ->join('weixin_user u on a.uid = u.id ')
                ->join('weixin_user d on a.did = d.id ')
            ->join('weixin_eliminate e on  a.id = e.aid')
                ->where(array('a.id'=>array('in',$rids),'is_detele'=>0))->order(' e.rank asc')->select();
            $datas2 = M('apply')->table('weixin_apply a')
                ->field('a.*,u.img,u.nickname name,u.name uname,u.sscore,d.img dimg,d.nickname dname ,d.name duname,d.sscore dsscore')
                ->join('weixin_user u on a.uid = u.id ')
                ->join('weixin_user d on a.did = d.id ')
                ->join('weixin_eliminate e on  a.id = e.aid')
                ->where(array('a.id'=>array('not in',$rids),'a.is_detele'=>0,'a.gid'=>$bid))->order(' a.win desc')->select();

        }
        foreach($datas2 as $k=>$v){
            $datas2[$k]['rank'] = $k+4;
        }
//        p($datas2);
        $listss = array_merge($datas1,$datas2);

//        foreach($lists as $k=>$v) {
//            $rank[] =$v['rank'];
//        }
//        foreach($lists as $k=>$v){
//            if($v['rank'] >0 && in_array(1,$rank)){
//                $data['win'][$k] = $v;
//            }else{
//                $data['fail'][$k] = $v;
//            }
//        }
//        foreach($data['fail'] as $k=>$v){
//            $w[] = $v['xwin'];
//        }
//        array_multisort($w, SORT_DESC, $data['fail']);

        foreach($lists as $k=>$v){
            $lists[$k]['win'] = ($r)?$v['win']:$v['xwin'];
            $lists[$k]['fail'] = ($r)?$v['fail']:$v['xfail'];
            $lists[$k]['rank'] = $k+1;
        }
        if($r){
            $this->assign('lists',$listss);
        }else{
            $this->assign('lists',$lists);
        }

        $this->assign('list',$list);
        $this->display();
    }

    public function is_round(){
        $bid = $_SESSION['uadmin']['bid'];
        $round = I('post.round');
        $res =M('record')->where(array('gid'=>$bid,'genre'=>1,'status'=>0))->order('round asc')->getField('round');
        if($res < $round){
            echo $res;
        }
    }

    //ajax提交比分
    public function ss(){
        $rid = I('post.rid');
        $arr['oscore'] = I('post.oscore');
        $arr['tscore'] = I('post.tscore');
        M('record')->where(array('id'=>$rid))->save($arr);
    }
}