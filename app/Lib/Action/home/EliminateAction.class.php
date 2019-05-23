<?php
require_once './app/Common/JsApi.php';
class EliminateAction extends baseAction {
    public function _initialize() {
        parent::_initialize();
//        $this->get_user_openid();
    }
    //胜者组
    public function win(){
        $bid = $_SESSION['uadmin']['bid'];
        $arr=array('','一','二','三','四','五');
        //败者组进行到第几轮 已提交分
        $n = M('record')->where(array('gid'=>$bid,'genre'=>3))->order('round desc')->getField('round');
        //胜者组人数
        $wnum = M('apply')->where(array('gid'=>$bid,'zu'=>1))->count();
        //败者组人数
        $fnum = M('apply')->where(array('gid'=>$bid,'zu'=>2))->count();
        $winnum = M('eliminate')->where(array('gid'=>$bid,'type'=>1))->count();
        if(in_array($wnum,array(0,1,2,8))){
            //胜者组人数大2 则开始第一轮
            $e = M('eliminate')->where(array('gid'=>$bid,'type'=>1))->count();
            $rr =  M('record')->where(array('gid'=>$bid,'genre'=>2,'status'=>0))->find();
//        $rrr =  M('record')->where(array('gid'=>$bid,'genre'=>2))->find();
            if($e>=2 && !$rr ){
                $data[0]['status'] = 0;
            }
//        }elseif($wnum == 1){
//            if($fnum == 3) {
//                $w1 = M('record')->where(array('gid' => $bid, 'genre' => 2, 'round' => 1, 'status' => 1))->find();
//                if ($w1) {
//                    $data[0]['status'] = 1;
//                } else {
//                    $data[0]['status'] = 0;
//                }
//            }
        }elseif($wnum == 3){
            if($fnum ==2){
                $w1 = M('record')->where(array('gid'=>$bid,'genre'=>2,'round'=>1,'status'=>1))->find();
                if($w1){
                    $data[0]['status'] = 1;
                }else{
                    if($n>0){
                        $data[0]['status'] = 0;
                    }

                }
            }else if($fnum == 3){
                $w1 = M('record')->where(array('gid'=>$bid,'genre'=>2,'round'=>1,'status'=>1))->find();
                if($w1){
                    $data[0]['status'] = 1;
                }else{
                    $data[0]['status'] = 0;
                }

            }elseif($fnum == 4 || $fnum == 5 || $fnum == 6){
                if($n>0){
                    $w1 = M('record')->where(array('gid'=>$bid,'genre'=>2,'round'=>1,'status'=>1))->find();
                    if($w1){
                        $data[0]['status'] = 1;
                    }else{
                        $data[0]['status'] = 0;
                    }
                }
            }elseif($fnum == 7){
                if($n>1){
                    $w1 = M('record')->where(array('gid'=>$bid,'genre'=>2,'round'=>1,'status'=>1))->find();
                    if($w1){
                        $data[0]['status'] = 1;
                    }else{
                        $data[0]['status'] = 0;
                    }
                }

            }
        }elseif($wnum == 4){
            if($fnum ==2){
                $w1 = M('record')->where(array('gid'=>$bid,'genre'=>2,'round'=>1,'status'=>1))->find();
                if($w1){
                    $data[0]['status'] = 1;
                }else{
                    $data[0]['status'] = 0;
                }
            }elseif($fnum == 3 || $fnum == 4){
                if($n>0){
                    $w1 = M('record')->where(array('gid'=>$bid,'genre'=>2,'round'=>1,'status'=>1))->find();
                    if($w1){
                        $data[0]['status'] = 1;
                    }else{
                        $data[0]['status'] = 0;
                    }
                }

            }elseif($fnum == 5 || $fnum == 6){
                if($n>1){
                    $w1 = M('record')->where(array('gid'=>$bid,'genre'=>2,'round'=>1,'status'=>1))->find();
                    if($w1){
                        $data[0]['status'] = 1;
                    }else{
                        $data[0]['status'] = 0;
                    }
                }
            }
        }elseif($wnum == 5){
            if($fnum ==2){
                if($n>0){
                    $w1 = M('record')->where(array('gid'=>$bid,'genre'=>2,'round'=>1,'status'=>1))->find();
                    $w2 = M('record')->where(array('gid'=>$bid,'genre'=>2,'round'=>2,'status'=>1))->find();
                    if($w1){
                        $data[0]['status'] = 1;
//                        if($w2){
//                            $data[1]['status'] = 1;
//                        }else{
//                            $data[1]['status'] = 0;
//                        }
                    }else{
                        $data[0]['status'] = 0;
                    }
                }

            }elseif($fnum ==3){
                $w1 = M('record')->where(array('gid'=>$bid,'genre'=>2,'round'=>1,'status'=>1))->find();
                $w2 = M('record')->where(array('gid'=>$bid,'genre'=>2,'round'=>2,'status'=>1))->find();
                if($w1){
                    $data[0]['status'] = 1;
                    if($n>0){
                        if($w2){
                            $data[1]['status'] = 1;
                        }else{
                            $data[1]['status'] = 0;
                        }
                    }
                }else{
                    $data[0]['status'] = 0;
                }

            }elseif($fnum ==4){
                if($n>0){
                    $w1 = M('record')->where(array('gid'=>$bid,'genre'=>2,'round'=>1,'status'=>1))->find();
                    $w2 = M('record')->where(array('gid'=>$bid,'genre'=>2,'round'=>2,'status'=>1))->find();
                    if($w1){
                        $data[0]['status'] = 1;
                        if($n>1){
                            if($w2){
                                $data[1]['status'] = 1;
                            }else{
                                $data[1]['status'] = 0;
                            }
                        }

                    }else{
                        $data[0]['status'] = 0;
                    }
                }

            }

        }elseif($wnum == 6){
            if($fnum == 2 || $fnum == 4){
                $w1 = M('record')->where(array('gid'=>$bid,'genre'=>2,'round'=>1,'status'=>1))->find();
                $w2 = M('record')->where(array('gid'=>$bid,'genre'=>2,'round'=>2,'status'=>1))->find();
                if($w1){
                    $data[0]['status'] = 1;
                    if($n>0){
                        if($w2){
                            $data[1]['status'] = 1;
                        }else{
                            $data[1]['status'] = 0;
                        }
                    }
                }else{
                    $data[0]['status'] = 0;
                }
            }elseif($fnum ==3){
                if($n>0){
                    $w1 = M('record')->where(array('gid'=>$bid,'genre'=>2,'round'=>1,'status'=>1))->find();
                    $w2 = M('record')->where(array('gid'=>$bid,'genre'=>2,'round'=>2,'status'=>1))->find();
                    if($w1){
                        $data[0]['status'] = 1;
                        if($n>1){
                            if($w2){
                                $data[1]['status'] = 1;
                            }else{
                                $data[1]['status'] = 0;
                            }
                        }

                    }else{
                        $data[0]['status'] = 0;
                    }
                }
            }
        }elseif($wnum == 7){
            if($n>0){
                $w1 = M('record')->where(array('gid'=>$bid,'genre'=>2,'round'=>1,'status'=>1))->find();
                $w2 = M('record')->where(array('gid'=>$bid,'genre'=>2,'round'=>2,'status'=>1))->find();
                if($w1){
                    $data[0]['status'] = 1;
                    if($n>1){
                        if($w2){
                            $data[1]['status'] = 1;
                        }else{
                            $data[1]['status'] = 0;
                        }
                    }

                }else{
                    $data[0]['status'] = 0;
                }
            }

        }
        $round = M('record')->where(array('gid'=>$bid,'genre'=>2))->getField('round',true);
        if($round){
            $datas = array_unique($round);
            if($wnum == 5 || $wnum == 6 || $wnum == 7){
                if(($fnum ==3 || $fnum ==4) && $wnum == 5){
                    $ii = 3;
                }else{
                    $ii = 2;
                }

            }else{
                $ii = 1;
            }
            for($i=$ii;$i<=count($datas);$i++){
                $k = $i-1;
//                $m = M('record')->where(array('gid'=>$bid,'round'=>$k,'genre'=>2,'status'=>1))->find();
                $mm = M('record')->where(array('gid'=>$bid,'round'=>$i,'genre'=>2,'status'=>1))->find();
                if($mm){
                    $data[$k]['status'] = 1;
                } else{
                    $kkkkk = $k-1;
                    if($data[$kkkkk]){
                        $data[$k]['status'] = 0;
                    }
                }
            }
            $m = M('record')->where(array('gid'=>$bid,'genre'=>2,'status'=>0))->find();
            if($winnum>1 && !$m){
                if($wnum == 5){
                    if($fnum ==3){
                        if($n>1){
                            $data[]['status'] = 0;
                        }
                    }else{
                        $data[]['status'] = 0;
                    }
                }else{
                    $data[]['status'] = 0;
                }

            }
        }
        $r = M('eliminate')->where(array('gid'=>$bid,'rank'=>3))->find();
        if($r){
            $res = count($data);
            $resc = $res-1;
            $data[$resc]['end'] = 1;
        }
//        p($data);
        $this->assign('arr',$arr);
        $this->assign('data',$data);
        $this->display();

    }

    public function index(){
        $bid = $_SESSION['uadmin']['bid'];
        $arr=array('','一','二','三','四','五');
        //比赛进行完的轮次
        $n = I('get.round');
        $status = I('get.status');
        //当前轮次淘汰赛是否有比赛记录
        $re =  M('record')->where(array('gid'=>$bid,'genre'=>2,'round'=>$n))->getField('member1');
        //胜者组队员
        $win = M('eliminate')->where(array('gid'=>$bid,'type'=>1))->select();
        $winnum = M('eliminate')->where(array('gid'=>$bid,'type'=>2))->count();
        $failnum = M('eliminate')->where(array('gid'=>$bid,'type'=>1))->count();
        $paiming = M('eliminate')->where(array('gid'=>$bid,'rank'=>3))->find();
        if($winnum == 0 && $failnum == 2 && !$paiming){
            $win = M('eliminate')->where(array('gid'=>$bid,'type'=>array('in',array(3,5))))->select();

        }
        $fn = count($win);
        if(count($win)>1 && !$re ){
            //参赛人数
            $npeople = M('game')->where(array('id'=>$bid))->getField('num');
            $yz = $this->calculate($npeople);
            if($npeople == '24' || $npeople == '20'){
                $yz = array('16,8,4,2,1');
            }
            //胜者组人数
            $c =count($win);
            $cc = $c/2;
            //胜者组第一轮结束后必须保证剩余人数为8 4 2 1 若不是则考虑第一轮轮空几个队
            if(!in_array($cc,$yz)){
                //计算有几队应该是轮空的
                foreach($yz as $v ){
                    if($c > $v){
                        $m = $v;
                        break;
                    }
                }
                //应该轮空的人数为
                $mm = $m*2-$c;
                foreach($win as $k=>$f){
                    $apply = M('apply')->where(array('id'=>$f['aid']))->find();
                    $uscore =M('score')->where(array('uid'=>$apply['uid']))->getField('score');
                    $dscore = M('score')->where(array('uid'=>$apply['did']))->getField('score');
                    if($uscore>$dscore){
                        $max = $uscore;
                        $min = $dscore;
                    }else{
                        $min = $uscore;
                        $max = $dscore;
                    }
                    $win[$k]['sc'] = $max*0.6+$min*0.4;
                    $win[$k]['id'] = $f['aid'];
                    $sss[] = $win[$k]['sc'];
                }
                //按照比分给队员排序
                array_multisort($sss, SORT_DESC, $win);
                for($i = 0;$i<$mm;$i++){

                    $eli['member1'] = $win[$i]['id'];
                    $eli['member2'] = 0;
                    $eli['round'] = $n;
                    $eli['genre'] = 2;
                    $eli['gid'] = $bid;
                    $eli['zd'] = 0;
                    M('record')->add($eli);
                    unset($win[$i]);
                }

            }

            $zd = ($fn==2)?1:0;
//            echo $zd;die;
            $end = I('get.end');
           $this->match($win,$n,2,$zd,$end);
        }
        $record = M('record')->where(array('gid'=>$bid,'round'=>$n,'genre'=>2))
            ->order('id desc')->select();
        foreach($record as $k=>$v){
            $uu[$k]['member1'] = M('apply ')->table('weixin_apply a')
                ->field('a.*,u.img,u.nickname name,u.name uname,u.sscore,d.img dimg,d.nickname dname,d.name duname,d.sscore dsscore')
                ->join('weixin_user u on a.uid = u.id ')
                ->join('weixin_user d on a.did = d.id ')
                ->where(array('a.id'=>$v['member1']))->find();
            $uu[$k]['member1']['oscore'] = $v['oscore'];
            $uu[$k]['member1']['bid'] = $v['id'];
            $uu[$k]['member2'] = M('apply ')->table('weixin_apply a')
                ->field('a.*,u.img,u.nickname name,u.name uname,u.sscore,d.img dimg,d.nickname dname,d.name duname,d.sscore dsscore')
                ->join('weixin_user u on a.uid = u.id ')
                ->join('weixin_user d on a.did = d.id ')
                ->where(array('a.id'=>$v['member2']))->find();
            $uu[$k]['member2']['tscore'] = $v['tscore'];
            $mids = M('record')->where(array('gid'=>$bid,'round'=>$n,'genre'=>2,'id'=>array('neq',$v['id'])))->select();
//            echo M('record')->_sql();die;
            foreach($mids as $kk=>$vv){
                if($vv['member1']){
                    $uu[$k]['m1'][] = M('apply ')->table('weixin_apply a')
                        ->field('a.*,u.img,u.nickname name,u.name uname,u.sscore,d.img dimg,d.nickname dname,d.name duname,d.sscore dsscore')
                        ->join('weixin_user u on a.uid = u.id ')
                        ->join('weixin_user d on a.did = d.id ')
                        ->where(array('a.id'=>$vv['member1']))->find();
                }
                if($vv['member2']){
                    $uu[$k]['m1'][] = M('apply ')->table('weixin_apply a')
                        ->field('a.*,u.img,u.nickname name,u.name uname,u.sscore,d.img dimg,d.nickname dname,d.name duname,d.sscore dsscore')
                        ->join('weixin_user u on a.uid = u.id ')
                        ->join('weixin_user d on a.did = d.id ')
                        ->where(array('a.id'=>$vv['member2']))->find();
                }

                $uu[$k]['zd'] = $v['zd'];
            }

        }
//        p($uu);

        $this->assign('uu',$uu);
        $this->assign('num',$n);
        $this->assign('status',$status);
        $this->assign('arr',$arr);
        $this->display();
    }
     //败者组
    public function fail(){
        $bid = $_SESSION['uadmin']['bid'];
        $arr=array('','一','二','三','四','五');
        //胜者组进行到第几轮 已提交分
        $n = M('record')->where(array('gid'=>$bid,'genre'=>2,'status'=>1))->order('round desc')->getField('round');
        //胜者组人数
        $wnum = M('apply')->where(array('gid'=>$bid,'zu'=>1))->count();
        //败者组人数
        $fnum = M('apply')->where(array('gid'=>$bid,'zu'=>2))->count();
        //败者组当前人数
        $failnum = M('eliminate')->where(array('gid'=>$bid,'type'=>2))->count();
        //败者组决赛人选
        $failc = M('eliminate')->where(array('gid'=>$bid,'type'=>4))->count();
        if($fnum != 0){
            if($wnum == 0 ){
                if($fnum == 8){
                    $f = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>1,'status'=>1))->find();
                    $f2 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>2,'status'=>1))->find();
                    $f3 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>3,'status'=>1))->find();
                    $f4 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>4,'status'=>1))->find();
                    if($f){
                        $data[0]['status'] = 1;
                        if($f2){
                            $data[1]['status'] = 1;
                            if($f3){
                                $data[2]['status'] = 1;
                                if($f4){
                                    $data[3]['status'] = 1;
                                }else{
                                    $data[3]['status'] = 0;
                                }
                            }else{
                                $data[2]['status'] = 0;
                            }
                        }else{
                            $data[1]['status'] = 0;
                        }
                    }else{
                        $data[0]['status'] = 0;;
                    }

                }elseif(in_array($fnum,array(9,10,11,12))){
                    if($fnum == 9){
                        $lk = 7;
                    }else if($fnum == 10){
                        $lk = 6;
                    }else if($fnum == 11){
                        $lk = 5;
                    }else if($fnum == 12){
                        $lk = 4;
                    }
                    $f = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>1,'status'=>1))->find();
                    $f2 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>2,'status'=>1))->find();
                    $f3 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>3,'status'=>1))->find();
                    $f4 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>4,'status'=>1))->find();
                    $f5 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>5,'status'=>1))->find();
                    if($f){
                        $data[0]['status'] = 1;
                        $data[0]['lk'] = $lk;
                        if($f2){
                            $data[1]['status'] = 1;
                            if($f3){
                                $data[2]['status'] = 1;
                                if($f4){
                                    $data[3]['status'] = 1;
                                    if($f5){
                                        $data[4]['status'] = 1;
                                    }else{
                                        $data[4]['status'] = 0;
                                    }
                                }else{
                                    $data[3]['status'] = 0;
                                }
                            }else{
                                $data[2]['status'] = 0;
                            }
                        }else{
                            $data[1]['status'] = 0;
                        }
                    }else{
                        $data[0]['status'] = 0;
                        $data[0]['lk'] = $lk;
                    }
                }elseif(in_array($fnum,array(5,6,7))){
                    if($fnum == 5){
                        $lk = 3;
                    }else if($fnum == 6){
                        $lk = 2;
                    }else if($fnum == 7){
                        $lk = 1;
                    }
                    $f = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>1,'status'=>1))->find();
                    $f2 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>2,'status'=>1))->find();
                    $f3 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>3,'status'=>1))->find();
                    $f4 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>4,'status'=>1))->find();
//                    $f5 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>5,'status'=>1))->find();
                    if($f){
                        $data[0]['status'] = 1;
                        $data[0]['lk'] = $lk;
                        if($f2){
                            $data[1]['status'] = 1;
                            if($f3){
                                $data[2]['status'] = 1;
                                if($f4){
                                    $data[3]['status'] = 1;
                                }else{
                                    $data[3]['status'] = 0;
                                }
                            }else{
                                $data[2]['status'] = 0;
                            }
                        }else{
                            $data[1]['status'] = 0;
                        }
                    }else{
                        $data[0]['status'] = 0;
                        $data[0]['lk'] = $lk;
                    }
                }elseif($fnum == 3){
                    $lk = 1;
                    $f = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>1,'status'=>1))->find();
                    $f2 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>2,'status'=>1))->find();
                    if($f){
                        $data[0]['status'] = 1;
                        $data[0]['lk'] = $lk;
                        if($f2){
                            $data[1]['status'] = 1;
                        }else{
                            $data[1]['status'] = 0;
                        }
                    }else{
                        $data[0]['status'] = 0;
                        $data[0]['lk'] = $lk;
                    }
                }else{
                    $round = M('record')->where(array('gid'=>$bid,'genre'=>3))->getField('round',true);
                    if($round){
                        $datas = array_unique($round);
                        for($i=1;$i<=count($datas);$i++){
                            $mm = M('record')->where(array('gid'=>$bid,'round'=>$i,'genre'=>3,'status'=>1))->find();
                            if($mm ){
                                $data[]['status'] = 1;
                            }else{
                                $data[]['status'] = 0;
                            }

                        }
                    }
                    $m = M('record')->where(array('gid'=>$bid,'genre'=>3,'status'=>0))->find();
                    $ccccc = M('eliminate')->where(array('gid'=>$bid,'type'=>4))->count();
                    if(($failnum>1 or ($ccccc == 1 && $failnum == 1)) && !$m){
                        $data[]['status'] = 0;
                    }
                }

            } elseif( $wnum == 1){
                if(in_array($fnum,array(9,10))){
                    if($fnum == 9){
                        $lk = 7;
                    }else if($fnum == 10){
                        $lk = 6;
                    }
                    $f = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>1,'status'=>1))->find();
                    $f2 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>2,'status'=>1))->find();
                    $f3 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>3,'status'=>1))->find();
                    $f4 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>4,'status'=>1))->find();
                    if($f){
                        $data[0]['status'] = 1;
                        $data[0]['lk'] = $lk;
                        if($f2){
                            $data[1]['status'] = 1;
                            if($f3){
                                $data[2]['status'] = 1;
                                if($f4){
                                    $data[3]['status'] = 1;
                                }else{
                                    $data[3]['status'] = 0;
                                }
                            }else{
                                $data[2]['status'] = 0;
                            }
                        }else{
                            $data[1]['status'] = 0;
                        }
                    }else{
                        $data[0]['status'] = 0;
                        $data[0]['lk'] = $lk;
                    }
                }elseif(in_array($fnum,array(5,6,7,8))){
                    if($fnum == 6){
                        $lk = 2;
                    }else if($fnum == 7){
                        $lk = 1;
                    }else if($fnum == 5){
                        $lk = 3;
                    }
                    $f = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>1,'status'=>1))->find();
                    $f2 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>2,'status'=>1))->find();
                    $f3 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>3,'status'=>1))->find();
                    if($f){
                        $data[0]['status'] = 1;
                        $data[0]['lk'] = $lk;
                        if($f2){
                            $data[1]['status'] = 1;
                            if($f3){
                                $data[2]['status'] = 1;
                            }else{
                                $data[2]['status'] = 0;
                            }
                        }else{
                            $data[1]['status'] = 0;
                        }
                    }else{
                        $data[0]['status'] = 0;
                        $data[0]['lk'] = $lk;
                    }
                }elseif(in_array($fnum,array(3,4))){
                    if($fnum == 3){
                        $lk = 1;
                    }
                    $f = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>1,'status'=>1))->find();
                    $f2 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>2,'status'=>1))->find();
//                    $f3 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>3,'status'=>1))->find();
                    if($f){
                        $data[0]['status'] = 1;
                        $data[0]['lk'] = $lk;
                        if($f2){
                            $data[1]['status'] = 1;
//                            if($f3){
//                                $data[2]['status'] = 1;
//                            }else{
//                                $data[2]['status'] = 0;
//                            }
                        }else{
                            $data[1]['status'] = 0;
                        }
                    }else{
                        $data[0]['status'] = 0;
                        $data[0]['lk'] = $lk;
                    }
                }else{
                    $round = M('record')->where(array('gid'=>$bid,'genre'=>3))->getField('round',true);
                    if($round){
                        $datas = array_unique($round);
                        for($i=1;$i<=count($datas);$i++){
                            $mm = M('record')->where(array('gid'=>$bid,'round'=>$i,'genre'=>3,'status'=>1))->find();
                            if($mm ){
                                $data[]['status'] = 1;
                            }else{
                                $data[]['status'] = 0;
                            }

                        }
                    }
                    $m = M('record')->where(array('gid'=>$bid,'genre'=>3,'status'=>0))->find();
                    $ccccc = M('eliminate')->where(array('gid'=>$bid,'type'=>4))->count();
                    if(($failnum>1 or ($ccccc == 1 && $failnum == 1)) && !$m){
                        $data[]['status'] = 0;
                    }
                }
            } elseif($wnum == 2){
                if(in_array($fnum,array(5,6,7,8))){
                    if($fnum == 5){
                        $lk = 3;
                    } elseif($fnum == 6){
                        $lk = 2;
                    }else if($fnum == 7){
                        $lk = 1;
                    }
                    $f = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>1,'status'=>1))->find();
                    $f2 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>2,'status'=>1))->find();
                    $f3 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>3,'status'=>1))->find();
                    $f4 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>4,'status'=>1))->find();
                    if($f){
                        $data[0]['status'] = 1;
                        $data[0]['lk'] = $lk;
                        if($f2){
                            $data[1]['status'] = 1;
                            if($f3){
                                $data[2]['status'] = 1;
                                if($f4){
                                    $data[3]['status'] = 1;
                                }else{
                                    if($failc == 1){
                                        $data[3]['status'] = 0;
                                    }

                                }
                            }else{
                                $data[2]['status'] = 0;
                            }
                        }else{
                            $data[1]['status'] = 0;
                        }
                    }else{
                        $data[0]['status'] = 0;
                        $data[0]['lk'] = $lk;
                    }

                }elseif($fnum == 3){
                    $f = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>1,'status'=>1))->find();
                    $f2 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>2,'status'=>1))->find();
                    $f3 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>3,'status'=>1))->find();
                    if($f){
                        $data[0]['status'] = 1;
                        $data[0]['lk'] = 1;
                        if($f2){
                            $data[1]['status'] = 1;
                            if($f3){
                                $data[2]['status'] = 1;
                            }else{
                                if($failc ==1){
                                    $data[2]['status'] = 0;
                                }
                            }
                        }else{
                            $data[1]['status'] = 0;
                        }
                    }else{
                        $data[0]['status'] = 0;
                        $data[0]['lk'] = 1;
                    }
                }elseif($fnum == 4){
                    $f = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>1,'status'=>1))->find();
                    $f2 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>2,'status'=>1))->find();
                    $f3 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>3,'status'=>1))->find();
                    if($f){
                        $data[0]['status'] = 1;
                        if($f2){
                            $data[1]['status'] = 1;
                            if($f3){
                                $data[2]['status'] = 1;
                            }else{
                                if($failc ==1){
                                    $data[2]['status'] = 0;
                                }
                            }
                        }else{
                            $data[1]['status'] = 0;
                        }
                    }else{
                        $data[0]['status'] = 0;
                    }
                }elseif($fnum == 9){
                    $lk = 7;
                    $f = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>1,'status'=>1))->find();
                    $f2 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>2,'status'=>1))->find();
                    $f3 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>3,'status'=>1))->find();
                    $f4 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>4,'status'=>1))->find();
                    $f5 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>5,'status'=>1))->find();
                    if($f){
                        $data[0]['status'] = 1;
                        $data[0]['lk'] = $lk;
                        if($f2){
                            $data[1]['status'] = 1;
                            if($f3){
                                $data[2]['status'] = 1;
                                if($f4){
                                    $data[3]['status'] = 1;
                                    if($f5){
                                        $data[4]['status'] = 1;
                                    }else{
                                        if($failc ==1){
                                            $data[4]['status'] = 0;
                                        }

                                    }
                                }else{
                                    $data[3]['status'] = 0;
                                }
                            }else{
                                $data[2]['status'] = 0;
                            }
                        }else{
                            $data[1]['status'] = 0;
                        }
                    }else{
                        $data[0]['status'] = 0;
                        $data[0]['lk'] = $lk;
                    }

                }else{
                    $round = M('record')->where(array('gid'=>$bid,'genre'=>3))->getField('round',true);
                    if($round){
                        $datas = array_unique($round);
                        for($i=1;$i<=count($datas);$i++){
                            $mm = M('record')->where(array('gid'=>$bid,'round'=>$i,'genre'=>3,'status'=>1))->find();
                            if($mm ){
                                $data[]['status'] = 1;
                            }else{
                                $data[]['status'] = 0;
                            }

                        }
                    }
                    $m = M('record')->where(array('gid'=>$bid,'genre'=>3,'status'=>0))->find();
                    $ccccc = M('eliminate')->where(array('gid'=>$bid,'type'=>4))->count();
                    if(($failnum>1 or ($ccccc == 1 && $failnum == 1)) && !$m){
                        $data[]['status'] = 0;
                    }
                }

            }else if($wnum == 3){
                if($fnum == 2){
                    //败者人数2人时，2 1(1) 1;
                    $f = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>1,'status'=>1))->find();
                    $f2 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>2,'status'=>1))->find();
                    $f3 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>3,'status'=>1))->find();
                    if($f){
                        $data[0]['status'] = 1;
                        if($n>0){
                            if($f2){
                                $data[1]['status'] = 1;
                                if($f3){
                                    $data[2]['status'] = 1;
                                }else{
                                    if($failc == 1){
                                        $data[2]['status'] = 0;
                                    }
                                }
                            }else{
                                $data[1]['status'] = 0;
                            }
                        }
                    }else{
                        $data[0]['status'] = 0;
                    }
                }else if($fnum == 3){
                    if($n>0){
                        $f = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>1,'status'=>1))->find();
                        $f2 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>2,'status'=>1))->find();
                        $f3 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>3,'status'=>1))->find();
                        if($f){
                            $data[0]['status'] = 1;
                            if($f2){
                                $data[1]['status'] = 1;
                                if($f3){
                                    $data[2]['status'] = 1;
                                }else{
                                    if($failc == 1){
                                        $data[2]['status'] = 0;
                                    }
                                }
                            }else{
                                $data[1]['status'] = 0;
                            }
                        }else{
                            $data[0]['status'] = 0;
                        }
                    }

                }else if($fnum == 4 || $fnum == 5 || $fnum == 6){
                    //轮空人数 2人
                    if($fnum == 4){
                        $lk = 2;
                    }else if($fnum == 5){
                        $lk = 1;
                    }
                    $f = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>1,'status'=>1))->find();
                    $f2 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>2,'status'=>1))->find();
                    $f3 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>3,'status'=>1))->find();
                    $f4 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>4,'status'=>1))->find();
                    if($f){
                        $data[0]['status'] = 1;
                        $data[0]['lk'] = $lk;
                        if($n>0){
                            if($f2){//第二轮开始的条件 败者组人数大于0
                                $data[1]['status'] = 1;
                                if($f3){//$failc
                                    $data[2]['status'] = 1;
                                    if($f4){//$failc
                                        $data[3]['status'] = 1;
                                    }else{
                                        if($failc == 1){
                                            $data[3]['status'] = 0;
                                        }
                                    }
                                }else{
                                    $data[2]['status'] = 0;
                                }
                            }else{
                                $data[1]['status'] = 0;
                            }
                        }
                    }else{
                        $data[0]['status'] = 0;
                        $data[0]['lk'] = $lk;
                    }
                }elseif($fnum == 7){
                    $f = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>1,'status'=>1))->find();
                    $f2 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>2,'status'=>1))->find();
                    $f3 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>3,'status'=>1))->find();
                    $f4 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>4,'status'=>1))->find();
                    $f5 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>5,'status'=>1))->find();
                    if($f){
                        $data[0]['status'] = 1;
                        $data[0]['lk'] = 5;
                        if($f2){
                            $data[1]['status'] = 1;
                            if($n>0){
                                if($f3){
                                    $data[2]['status'] = 1;
                                    if($f4){
                                        $data[3]['status'] = 1;
                                        if($failc==1){
                                            if($f5){
                                                $data[4]['status'] = 1;
                                            }else{
                                                $data[4]['status'] = 0;
                                            }
                                        }
                                    }else{
                                        $data[3]['status'] = 0;
                                    }
                                }else{
                                    $data[2]['status'] = 0;
                                }
                            }
                        }else{
                            $data[1]['status'] = 0;
                        }
                    }else{
                        $data[0]['status'] = 0;
                        $data[0]['lk'] = 5;
                    }

                }else{
                    $round = M('record')->where(array('gid'=>$bid,'genre'=>3))->getField('round',true);
                    if($round){
                        $datas = array_unique($round);
                        for($i=1;$i<=count($datas);$i++){
                            $mm = M('record')->where(array('gid'=>$bid,'round'=>$i,'genre'=>3,'status'=>1))->find();
                            if($mm ){
                                $data[]['status'] = 1;
                            }else{
                                $data[]['status'] = 0;
                            }

                        }
                    }
                    $m = M('record')->where(array('gid'=>$bid,'genre'=>3,'status'=>0))->find();
                    $ccccc = M('eliminate')->where(array('gid'=>$bid,'type'=>4))->count();
                    if(($failnum>1 or ($ccccc == 1 && $failnum == 1)) && !$m){
                        $data[]['status'] = 0;
                    }
                }


            }else if($wnum == 4){
                //败者组4 2(2) 2 1
                if($fnum == 2){
                    //胜者组第一轮已完结
                    $f = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>1,'status'=>1))->find();
                    $f2 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>2,'status'=>1))->find();
                    $f3 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>3,'status'=>1))->find();
                    if($n>0){
                        if($f){
                            $data[0]['status'] = 1;
                            if($f2){
                                $data[1]['status'] = 1;
                                if($failc == 1){
                                    if($f3){
                                        $data[2]['status'] = 1;
                                    }else{
                                        $data[2]['status'] = 0;
                                    }
                                }
                            }else{
                                $data[1]['status'] = 0;
                            }
                        }else{
                            $data[0]['status'] = 0;
                        }
                    }
                }elseif($fnum == 3 || $fnum == 4){
                    if($fnum == 3){
                        $lk = 1;
                    }
                    $f = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>1,'status'=>1))->find();
                    $f2 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>2,'status'=>1))->find();
                    $f3 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>3,'status'=>1))->find();
                    $f4 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>4,'status'=>1))->find();
                    if($f){
                        $data[0]['status'] = 1;
                        $data[0]['lk'] = $lk;
                        if($n>0){
                            if($f2){//第二轮开始的条件 败者组人数大于0
                                $data[1]['status'] = 1;
                                if($f3){//$failc
                                    $data[2]['status'] = 1;
                                    if($f4){//$failc
                                        $data[3]['status'] = 1;
                                    }else{
                                        if($failc == 1){
                                            $data[3]['status'] = 0;
                                        }

                                    }
                                }else{
                                    $data[2]['status'] = 0;
                                }
                            }else{
                                $data[1]['status'] = 0;
                            }
                        }
                    }else{
                        $data[0]['status'] = 0;
                        $data[0]['lk'] = $lk;
                    }


                }elseif($fnum == 5 || $fnum == 6){
                    if($fnum == 5){
                        $lk = 3;
                    }elseif($fnum == 6){
                        $lk = 2;
                    }
                    $f = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>1,'status'=>1))->find();
                    $f2 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>2,'status'=>1))->find();
                    $f3 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>3,'status'=>1))->find();
                    $f4 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>4,'status'=>1))->find();
                    $f5 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>5,'status'=>1))->find();
                    if($f){
                        $data[0]['status'] = 1;
                        $data[0]['lk'] = $lk;
                        if($f2){
                            $data[1]['status'] = 1;
                            if($n>0){//胜者组进行完第一轮再开始第三轮
                                if($f3){
                                    $data[2]['status'] = 1;
                                    if($f4){//$failc
                                        $data[3]['status'] = 1;
                                        if($f5){//$failc
                                            $data[4]['status'] = 1;
                                        }else{
                                            if($failc == 1){
                                                //胜者组进入淘汰赛决赛的人存在再开始第五轮
                                                $data[4]['status'] = 0;
                                            }
                                        }
                                    }else{
                                        $data[3]['status'] = 0;
                                    }
                                }else{
                                    $data[2]['status'] = 0;
                                }
                            }

                        }else{
                            $data[1]['status'] = 0;
                        }
                    }else{
                        $data[0]['status'] = 0;
                        $data[0]['lk'] = $lk;
                    }
                }else{
                    $round = M('record')->where(array('gid'=>$bid,'genre'=>3))->getField('round',true);
                    if($round){
                        $datas = array_unique($round);
                        for($i=1;$i<=count($datas);$i++){
                            $mm = M('record')->where(array('gid'=>$bid,'round'=>$i,'genre'=>3,'status'=>1))->find();
                            if($mm ){
                                $data[]['status'] = 1;
                            }else{
                                $data[]['status'] = 0;
                            }

                        }
                    }
                    $m = M('record')->where(array('gid'=>$bid,'genre'=>3,'status'=>0))->find();
                    $ccccc = M('eliminate')->where(array('gid'=>$bid,'type'=>4))->count();
                    if(($failnum>1 or ($ccccc == 1 && $failnum == 1)) && !$m){
                        $data[]['status'] = 0;
                    }
                }

            }else if($wnum == 5){
                if($fnum == 2){
                    $f = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>1,'status'=>1))->find();
                    $f2 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>2,'status'=>1))->find();
                    $f3 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>3,'status'=>1))->find();
                    $f4 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>4,'status'=>1))->find();
                    if($f){
                        $data[0]['status'] = 1;
                        if($n>1){
                           if($f2){
                               $data[1]['status'] = 1;
                               if($f3){
                                   $data[2]['status'] = 1;
                                   if($f4){
                                       $data[3]['status'] = 1;
                                   } else{
                                       if($failc == 1){
                                           $data[3]['status'] = 0;
                                       }
                                   }
                               } else{
                                   $data[2]['status'] = 0;
                               }
                           } else{
                               $data[1]['status'] = 0;
                           }
                        }
                    }else{
                        $data[0]['status'] = 0;
                    }
                }elseif($fnum == 3){
                    $f = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>1,'status'=>1))->find();
                    $f2 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>2,'status'=>1))->find();
                    $f3 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>3,'status'=>1))->find();
                    $f4 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>4,'status'=>1))->find();
                    if($n>0){
                      if($f){
                          $data[0]['status'] = 1;
                          if($n>1){
                              if($f2){
                                  $data[1]['status'] = 1;
                                  if($f3){
                                      $data[2]['status'] = 1;
                                      if($f4){
                                          $data[3]['status'] = 1;
                                      }else{
                                          if($failc == 1){
                                              $data[3]['status'] = 0;
                                          }

                                      }
                                  }else{
                                      $data[2]['status'] = 0;
                                  }
                              }else{
                                  $data[1]['status'] = 0;
                              }
                          }
                      }  else{
                          $data[0]['status'] = 0;
                      }
                    }

                }elseif($fnum == 4){
                    $f = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>1,'status'=>1))->find();
                    $f2 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>2,'status'=>1))->find();
                    $f3 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>3,'status'=>1))->find();
                    $f4 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>4,'status'=>1))->find();
                    $f5 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>5,'status'=>1))->find();
                    if($f){
                        $data[0]['status'] = 1;
                        $data[0]['lk'] = 2;
                        if($n>0){
                            if($f2){
                                $data[1]['status'] = 1;
                                if($n>1){
                                   if($f3){
                                       $data[2]['status'] = 1;
                                       if($f4){
                                           $data[3]['status'] = 1;
                                           if($f5){
                                               $data[4]['status'] = 1;
                                           } else{
                                               if($failc == 1){
                                                   $data[4]['status'] = 0;
                                               }

                                           }
                                       } else{
                                           $data[3]['status'] = 0;
                                       }
                                   } else{
                                       $data[2]['status'] = 0;
                                   }
                                }
                            }else{
                                $data[1]['status'] = 0;
                            }
                        }
                    }else{
                        $data[0]['status'] = 0;
                        $data[0]['lk'] = 2;
                    }

                }else{
                    $round = M('record')->where(array('gid'=>$bid,'genre'=>3))->getField('round',true);
                    if($round){
                        $datas = array_unique($round);
                        for($i=1;$i<=count($datas);$i++){
                            $mm = M('record')->where(array('gid'=>$bid,'round'=>$i,'genre'=>3,'status'=>1))->find();
                            if($mm ){
                                $data[]['status'] = 1;
                            }else{
                                $data[]['status'] = 0;
                            }

                        }
                    }
                    $m = M('record')->where(array('gid'=>$bid,'genre'=>3,'status'=>0))->find();
                    $ccccc = M('eliminate')->where(array('gid'=>$bid,'type'=>4))->count();
                    if(($failnum>1 or ($ccccc == 1 && $failnum == 1)) && !$m){
                        $data[]['status'] = 0;
                    }
                }

            }else if($wnum == 6){
                if($fnum ==2){
                    $f = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>1,'status'=>1))->find();
                    $f2 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>2,'status'=>1))->find();
                    $f3 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>3,'status'=>1))->find();
                    $f4 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>4,'status'=>1))->find();
                    if($n>0){
                       if($f){
                           $data[0]['status'] = 1;
                           if($n>1){
                               if($f2){
                                   $data[1]['status'] = 1;
                                   if($f3){
                                       $data[2]['status'] = 1;
                                       if($f4){
                                           $data[3]['status'] = 1;
                                       } else{
                                           if($failc == 1){
                                               $data[3]['status'] = 0;
                                           }

                                       }
                                   } else{
                                       $data[2]['status'] = 0;
                                   }
                               } else{
                                   $data[1]['status'] = 0;
                               }
                           }
                       } else{
                           $data[0]['status'] = 0;
                       }
                    }

                }elseif($fnum == 3){
                    $f = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>1,'status'=>1))->find();
                    $f2 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>2,'status'=>1))->find();
                    $f3 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>3,'status'=>1))->find();
                    $f4 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>4,'status'=>1))->find();
                    $f5 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>5,'status'=>1))->find();
                    if($f){
                        $data[0]['status'] = 1;
                        $data[0]['lk'] = 1;
                        if($n>0){
                            if($f2){
                                $data[1]['status'] = 1;
                                if($n>1) {
                                    if ($f3) {
                                        $data[2]['status'] = 1;
                                        if ($f4) {
                                            $data[3]['status'] = 1;
                                            if ($f5) {
                                                $data[4]['status'] = 1;
                                            } else {
                                                if($failc == 1){
                                                    $data[4]['status'] = 0;
                                                }

                                            }
                                        } else {
                                            $data[3]['status'] = 0;
                                        }
                                    } else {
                                        $data[2]['status'] = 0;
                                    }
                                }

                            } else{
                                $data[1]['status'] = 0;
                            }
                        }
                    }else{
                        $data[0]['status'] = 0;
                        $data[0]['lk'] = 1;
                    }

                }else{
                    $round = M('record')->where(array('gid'=>$bid,'genre'=>3))->getField('round',true);
                    if($round){
                        $datas = array_unique($round);
                        for($i=1;$i<=count($datas);$i++){
                            $mm = M('record')->where(array('gid'=>$bid,'round'=>$i,'genre'=>3,'status'=>1))->find();
                            if($mm ){
                                $data[]['status'] = 1;
                            }else{
                                $data[]['status'] = 0;
                            }

                        }
                    }
                    $m = M('record')->where(array('gid'=>$bid,'genre'=>3,'status'=>0))->find();
                    $ccccc = M('eliminate')->where(array('gid'=>$bid,'type'=>4))->count();
                    if(($failnum>1 or ($ccccc == 1 && $failnum == 1)) && !$m){
                        $data[]['status'] = 0;
                    }
                }

            }else if($wnum == 7){
                //败者组 1(3) 2(2) 2 1
                $f = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>1,'status'=>1))->find();
                $f2 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>2,'status'=>1))->find();
                $f3 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>3,'status'=>1))->find();
                $f4 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>4,'status'=>1))->find();
                $f5 = M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>5,'status'=>1))->find();
                if($f){
                    $data[0]['status'] = 1;
                    if($n>0){
                        if($f2){
                            $data[1]['status'] = 1;
                            if($n>1) {
                                if ($f3) {
                                    $data[2]['status'] = 1;
                                    if ($f4) {
                                        $data[3]['status'] = 1;
                                        if ($f5) {
                                            $data[4]['status'] = 1;
                                        } else {
                                            if($failc == 1){
                                                $data[4]['status'] = 0;
                                            }

                                        }
                                    } else {
                                        $data[3]['status'] = 0;
                                    }
                                } else {
                                    $data[2]['status'] = 0;
                                }
                            }

                        } else{
                            $data[1]['status'] = 0;
                        }
                    }
                }else{
                    $data[0]['status'] = 0;
                }

            }else{
                $round = M('record')->where(array('gid'=>$bid,'genre'=>3))->getField('round',true);
                if($round){
                    $datas = array_unique($round);
                    for($i=1;$i<=count($datas);$i++){
                        $mm = M('record')->where(array('gid'=>$bid,'round'=>$i,'genre'=>3,'status'=>1))->find();
                        if($mm ){
                            $data[]['status'] = 1;
                        }else{
                            $data[]['status'] = 0;
                        }

                    }
                }
                $m = M('record')->where(array('gid'=>$bid,'genre'=>3,'status'=>0))->find();
                $ccccc = M('eliminate')->where(array('gid'=>$bid,'type'=>4))->count();
                if(($failnum>1 or ($ccccc == 1 && $failnum == 1)) && !$m){
                    $data[]['status'] = 0;
                }
            }
        }
        $r = M('eliminate')->where(array('gid'=>$bid,'rank'=>3))->find();
        $res = count($data);
        $resc = $res-1;
        if($r){
            $data[$resc]['end'] = 1;
        }
        if($wnum == '0'){
            if($r){
                $data[$resc]['end'] = 1;
            }
        }elseif($wnum == '1'){
            if($failnum == 2){
                $data[$resc]['end'] = 1;
            }
        }else{
            if($failc == 1){
                $data[$resc]['end'] = 1;
            }


        }
//        p($data);
        $this->assign('arr',$arr);
        $this->assign('data',$data);
        $this->display();

    }
    public function eliminate(){
        $bid = $_SESSION['uadmin']['bid'];
        $arr=array('','一','二','三','四','五');
        //比赛进行完的轮次
        //当前轮次
        $n = I('get.round');
        $status = I('get.status');
        $lk = I('get.lk');
        //当前轮次败者组淘汰赛是否有比赛记录
        $re =  M('record')->where(array('gid'=>$bid,'genre'=>3,'round'=>$n))->getField('member1');
        //胜者组人数
        $winnum = M('eliminate')->where(array('gid'=>$bid,'type'=>1))->count();
        $failnum = M('eliminate')->where(array('gid'=>$bid,'type'=>2))->count();
        //败者组队员
        $fail = M('eliminate')->where(array('gid'=>$bid,'type'=>2))->select();
        if($winnum == 1 && $failnum == 1){
            $fail = M('eliminate')->where(array('gid'=>$bid,'type'=>array('in',array(2,4))))->select();
        }
        $paiming = M('eliminate')->where(array('gid'=>$bid,'rank'=>3))->find();
        if($winnum == 0 && $failnum == 2 && !$paiming){
            $fail = M('eliminate')->where(array('gid'=>$bid,'type'=>array('in',array(3,5))))->select();

        }
        $fn = count($fail);
        if(count($fail)>1 && !$re) {
            $c = count($fail);
//            if($c%2 != 0){
                foreach($fail as $k=>$f){
                    $apply = M('apply')->where(array('id'=>$f['aid']))->find();
                    $uscore =M('score')->where(array('uid'=>$apply['uid']))->getField('score');
                    $dscore = M('score')->where(array('uid'=>$apply['did']))->getField('score');
                    if($uscore>$dscore){
                        $max = $uscore;
                        $min = $dscore;
                    }else{
                        $min = $uscore;
                        $max = $dscore;
                    }
                    $fail[$k]['sc'] = $max*0.6+$min*0.4;
                    $fail[$k]['id'] = $f['aid'];
                    $sss[] = $fail[$k]['sc'];
                }
                //按照比分给队员排序
                array_multisort($sss, SORT_DESC, $fail);
                if($c%2 != 0 && $lk == 0){
                    $lk = 1;
                }
                for($i=0;$i<$lk;$i++){
                    $eli['member1'] = $fail[$i]['id'];
                    $eli['member2'] = 0;
                    $eli['round'] = $n;
                    $eli['genre'] = 3;
                    $eli['gid'] = $bid;
                    $eli['zd'] = 0;
                    M('record')->add($eli);
                    unset($fail[$i]);
                }

//            }

            $zd = ($fn==2)?1:0;
            $end = I('get.end');
            $this->match($fail,$n,3,$zd,$end);

        }
        $record = M('record')->where(array('gid'=>$bid,'round'=>$n,'genre'=>3))
            ->order('id desc')->select();
        foreach($record as $k=>$v){
            $uu[$k]['member1'] = M('apply ')->table('weixin_apply a')
                ->field('a.*,u.img,u.nickname name,u.name uname,u.sscore,d.img dimg,d.nickname dname,d.name duname,d.sscore dsscore')
                ->join('weixin_user u on a.uid = u.id ')
                ->join('weixin_user d on a.did = d.id ')
                ->where(array('a.id'=>$v['member1']))->find();
            $uu[$k]['member1']['oscore'] = $v['oscore'];
            $uu[$k]['member1']['bid'] = $v['id'];
            $uu[$k]['member2'] = M('apply ')->table('weixin_apply a')
                ->field('a.*,u.img,u.nickname name,u.name uname,u.sscore,d.img dimg,d.nickname dname,d.name duname,d.sscore dsscore')
                ->join('weixin_user u on a.uid = u.id ')
                ->join('weixin_user d on a.did = d.id ')
                ->where(array('a.id'=>$v['member2']))->find();
            $uu[$k]['member2']['tscore'] = $v['tscore'];
            $mids = M('record')->where(array('gid'=>$bid,'round'=>$n,'genre'=>3,'id'=>array('neq',$v['id'])))->select();
//            echo M('record')->_sql();die;
            foreach($mids as $kk=>$vv){
                if($vv['member1']){
                    $uu[$k]['m1'][] = M('apply ')->table('weixin_apply a')
                        ->field('a.*,u.img,u.nickname name,u.name uname,u.sscore,d.img dimg,d.nickname dname,d.name duname,d.sscore dsscore')
                        ->join('weixin_user u on a.uid = u.id ')
                        ->join('weixin_user d on a.did = d.id ')
                        ->where(array('a.id'=>$vv['member1']))->find();
                }
                if($vv['member2']){
                    $uu[$k]['m1'][] = M('apply ')->table('weixin_apply a')
                        ->field('a.*,u.img,u.nickname name,u.name uname,u.sscore,d.img dimg,d.nickname dname,d.name duname,d.sscore dsscore')
                        ->join('weixin_user u on a.uid = u.id ')
                        ->join('weixin_user d on a.did = d.id ')
                        ->where(array('a.id'=>$vv['member2']))->find();
                }

                $uu[$k]['zd'] = $v['zd'];
            }
        }
        $this->assign('uu',$uu);
//       p($uu);
        $this->assign('num',$n);
        $this->assign('status',$status);
        $this->assign('arr',$arr);
        $this->display();
    }
    //计算胜场第一轮结束应该剩余的人数
    public function calculate($n){
        for($i = 0;$i<100;$i++){
            $m = $n/2;
            $arr[] = $m;
            if($m == 1){
                break;
            }
            $n = $m;
        }
        return $arr;
    }


    public function match($list,$n,$type,$zd=0,$end=0){

        $bid = $_SESSION['uadmin']['bid'];
        $a = rand(1,999);
        $list = array_reverse(array_reverse($list));
        foreach($list as $k=>$v) {
            //取出第一个战队
            if ($k == 0) {
                $id = $v['aid'];
                $m = $k;
                continue;
            }
            //随机匹配战队
            $nn = count($list) -1;
            $i = rand(1,$nn);
            $arr[$a]['member1'] = $id;
            $arr[$a]['member2'] = $list[$i]['aid'];
            $arr[$a]['gid'] = $bid;
            $arr[$a]['round'] = $n;
            $arr[$a]['zd'] = $zd;
            $arr[$a]['end'] = $end;
            $arr[$a]['genre'] = $type;//1循环赛2胜者组淘汰赛3败者组淘汰赛
            if( M('record')->where($arr[$a])->find() !==false){
                M('record')->add($arr[$a]);
                unset($list[$i]);
                unset($list[$m]);
                break;
            }
        }
        //战队数大于1继续匹配
        if(count($list)>1){
            $this->match($list,$n,$type,$zd);
        }
        return $arr;
    }

    public function score(){
        $bid = $_SESSION['uadmin']['bid'];
        $n = I('get.round');
        //当前轮次的败者组淘汰赛是否匹配战队
        $el =  M('record')->where(array('gid'=>$bid,'round'=>$n,'genre'=>3))->find();
        //败者组人数
        $fc = M('eliminate')->where(array('type'=>2,'gid'=>$bid))->count();
        if($n>1){
            //上一轮的淘汰赛是否结束
            $sl = $n-1;
            $ee = M('record')->where(array('gid'=>$bid,'round'=>$sl,'genre'=>3,'status'=>1))->find();
        }
        foreach ($_POST as $k => $v) {
            if (intval($v['member1']['sc']) == intval($v['member2']['sc']) && ($v['member2']['id'] !='' && $v['member1']['id'] )) {
                $this->error('两队分数不可以一样');
            }
            $cha = abs($v['member1']['sc']-$v['member2']['sc']);
            if($cha>6){
                $this->error('两队分数相差不可以大于6');
            }
        }
        $ordid = M('record')->where(array('gid' => $bid,'genre'=>array('in',array(2,3))))->order('ordid desc')->getField('ordid');
        $data['ordid'] = $ordid+1;
        foreach($_POST as $k=>$v){
            if($v['member1']['sc'] > $v['member2']['sc']){
                $data['win'] = $v['member1']['id'];
                $data['fail'] = $v['member2']['id'];
            }else{
                $data['win'] = $v['member2']['id'];
                $data['fail'] = $v['member1']['id'];
            }
            $data['oscore'] = $v['member1']['sc'];
            $data['tscore'] = $v['member2']['sc'];
            if($v['member2']['id'] == '' || $v['member1']['id'] == ''){
                $data['win'] = 0;
                $data['fail'] = 0;
                $data['oscore'] = 0;
                $data['tscore'] = 0;

            }
//            if($v['member1']['id'] == ''){
//                $data['win'] = 0;
//                $data['fail'] = 0;
//                $data['oscore'] = 0;
//                $data['tscore'] = 0;
//            }
            //记录得失分情况
            $wids =  M('apply')->field('uid,did')->where(array('id'=>$data['win']))->find();

            $fids =  M('apply')->field('uid,did')->where(array('id'=>$data['fail']))->find();
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

            $data['status'] = 1;

            M('record')->where(array('id'=>$k))->save($data);
            if($fc == 0){//单淘汰赛
                //胜者组人数
                $t5 = M('eliminate')->where(array('gid'=>$bid,'type'=>5))->find();
                $wc = M('eliminate')->where(array('type'=>1,'gid'=>$bid))->count();
                if($wc>3){
                    if($wc == 4) {
                        $arrr['type'] = 5;
                        M('eliminate')->where(array('gid' => $bid, 'aid' => $data['fail']))->save($arrr);
                    }else {
                        M('eliminate')->where(array('gid' => $bid, 'aid' => $data['fail']))->delete();
                    }
                }elseif($wc == 3){
                    if($t5){
                        $arr['type'] = 3;
                    }else{
                        $arr['rank'] = 3;
                        $arr['type'] = 2;
                    }

                    M('eliminate')->where(array('gid'=>$bid,'aid'=>$data['fail']))->save($arr);
                }else{
                    $rank = M('eliminate')->where(array('gid' => $bid, 'aid' => $data['fail']))->getField('type');
                    if ($rank != 1) {
                        $arr['rank'] = 3;
                        M('eliminate')->where(array('gid'=>$bid,'aid'=>$data['win']))->save($arr);
                        M('eliminate')->where(array('gid' => $bid, 'aid' => $data['fail']))->delete();
                    }else{
                        $arr['rank'] = 2;
                        $arr['type'] = 3;
                        M('eliminate')->where(array('gid'=>$bid,'aid'=>$data['fail']))->save($arr);
                        M('eliminate')->where(array('gid'=>$bid,'aid'=>$data['win']))->setField('rank',1);
                        //所有参加本比赛队员的ID
                        $aid = M('apply')->where(array('gid'=>$bid))->select();
                        //统计胜负场次
                        foreach($aid as $v){
                            $ww['win'] = M('record')->where(array('gid'=>$bid,'win'=>$v['id']))->count();

                            $ww['fail'] = M('record')->where(array('gid'=>$bid,'fail'=>$v['id']))->count();
                            M('apply')->where(array('gid'=>$bid,'id'=>$v['id']))->save($ww);
                            //更新用户的胜负场次
                            $user = M('user')->where(array('id'=>$v['uid']))->find();
                            $sc['win'] = $user['win'] + $ww['win'];
                            $sc['fail'] = $user['fail'] + $ww['fail'];
                            M('user')->where(array('id'=>$v['uid']))->save($sc);
                            $user = M('user')->where(array('id'=>$v['did']))->find();
                            $sc['win'] = $user['win'] + $ww['win'];
                            $sc['fail'] = $user['fail'] + $ww['fail'];
                            M('user')->where(array('id'=>$v['did']))->save($sc);

                        }
                    }

                }
            }else{
                $eliminate['type'] = 2;
                $wnum = M('eliminate')->where(array('gid'=>$bid,'type'=>1))->count();
                if($wnum==2){
                    $eliminate['type'] = 4;
                }
                $rank = M('eliminate')->where(array('gid'=>$bid,'rank'=>3))->find();
                if($rank){
                    //淘汰赛的最后一轮
                    $eliminate['rank'] = 2;
                    $eliminate['type'] = 3;
                    M('eliminate')->where(array('gid'=>$bid,'aid'=>$data['win']))->setField('rank',1);
                    //统计每个人的胜负场次
                    $fid = M('record')->where(array('gid'=>$bid))->getField('fail',true);
                    //所有参加本比赛队员的ID
                    $aid = M('apply')->where(array('gid'=>$bid,'is_detele'=>0))->select();
                    //统计胜负场次
                    foreach($aid as $v){
                        $ww['win'] = M('record')->where(array('gid'=>$bid,'win'=>$v['id']))->count();

                        $ww['fail'] = M('record')->where(array('gid'=>$bid,'fail'=>$v['id']))->count();
                        M('apply')->where(array('gid'=>$bid,'id'=>$v['id']))->save($ww);
                    }
                }
                M('eliminate')->where(array('gid'=>$bid,'aid'=>$data['fail']))->save($eliminate);
            }

        }
        $this->redirect(U('Eliminate/win'));
    }

    public function eliminate_score(){
        $bid = $_SESSION['uadmin']['bid'];
        $n = I('get.round');
        //当前轮次的胜者组淘汰赛是否匹配战队
        $el =  M('record')->where(array('gid'=>$bid,'round'=>$n,'genre'=>2))->find();
        //胜者组人数
        $fc = M('eliminate')->where(array('type'=>1,'gid'=>$bid))->count();
        $fcc = M('apply')->where(array('zu'=>1,'gid'=>$bid))->count();
        foreach ($_POST as $k => $v) {
            if (intval($v['member1']['sc']) == intval($v['member2']['sc']) && ($v['member2']['id'] !='' && $v['member1']['id'] )) {
                $this->error('两队分数不可以一样');
            }
            $cha = abs($v['member1']['sc']-$v['member2']['sc']);
            if($cha>6){
                $this->error('两队分数相差不可以大于6');
            }
        }
        $ordid = M('record')->where(array('gid' => $bid,'genre'=>array('in',array(2,3))))->order('ordid desc')->getField('ordid');
        $data['ordid'] = $ordid+1;
        foreach($_POST as $k=>$v){
            if($v['member1']['sc'] > $v['member2']['sc']){
                $data['win'] = $v['member1']['id'];
                $data['fail'] = $v['member2']['id'];
            }else{
                $data['win'] = $v['member2']['id'];
                $data['fail'] = $v['member1']['id'];
            }
            $data['oscore'] = $v['member1']['sc'];
            $data['tscore'] = $v['member2']['sc'];
            if($v['member2']['id'] == '' || $v['member1']['id'] == ''){
                $data['win'] = 0;
                $data['fail'] = 0;
                $data['oscore'] = 0;
                $data['tscore'] = 0;
            }
//            if($v['member1']['id'] == ''){
//                $data['win'] = $v['member2']['id'];
//                $data['fail'] = $v['member1']['id'];
//                $data['oscore'] = 0;
//                $data['tscore'] = 0;
//            }
            //记录得失分情况
            $wids =  M('apply')->field('uid,did')->where(array('id'=>$data['win']))->find();

            $fids =  M('apply')->field('uid,did')->where(array('id'=>$data['fail']))->find();
            $s1 = M('user')->where(array('id'=>$wids['uid']))->getField('sscore');
            $s2 = M('user')->where(array('id'=>$wids['did']))->getField('sscore');
            $s3 = M('user')->where(array('id'=>$fids['uid']))->getField('sscore');
            $s4 = M('user')->where(array('id'=>$fids['did']))->getField('sscore');
            $data['fen1'] = $wids['uid'].','.$s1;
            $data['fen2'] = $wids['did'].','.$s2;
            $data['fen3'] = $fids['uid'].','.$s3;
            $data['fen4'] = $fids['did'].','.$s4;

//            if(!$s1 || !$s3 || !$s2 || !$s4){
//                unset($data['fen1']);
//                unset($data['fen2']);
//                unset($data['fen3']);
//                unset($data['fen4']);
//            }


            $data['status'] = 1;

            M('record')->where(array('id'=>$k))->save($data);

            if($fc == 0){//胜者组人数为零败者组单淘汰
                //败者组人数
              $t5 = M('eliminate')->where(array('gid'=>$bid,'type'=>5))->find();
              $wc = M('eliminate')->where(array('type'=>2,'gid'=>$bid))->count();
                if($wc>3){
                    if($wc == 4) {
                        $arrr['type'] = 5;
                        M('eliminate')->where(array('gid' => $bid, 'aid' => $data['fail']))->save($arrr);
                    }else{
                        M('eliminate')->where(array('gid'=>$bid,'aid'=>$data['fail']))->delete();
                    }
                }elseif($wc == 3){
                    if($t5){
                        $arr['type'] = 3;
                    }else{
                        $arr['rank'] = 3;
                        $arr['type'] = 3;
                    }

                    M('eliminate')->where(array('gid'=>$bid,'aid'=>$data['fail']))->save($arr);

                }else{
                    $rank = M('eliminate')->where(array('gid' => $bid, 'aid' => $data['fail']))->getField('type');
                    if ($rank != 2) {
                        $arr['rank'] = 3;
                        M('eliminate')->where(array('gid'=>$bid,'aid'=>$data['win']))->save($arr);
                        M('eliminate')->where(array('gid' => $bid, 'aid' => $data['fail']))->delete();
                    }else{
                        $arr['rank'] = 2;
                        $arr['type'] = 1;
                        M('eliminate')->where(array('gid'=>$bid,'aid'=>$data['fail']))->save($arr);
                        M('eliminate')->where(array('gid'=>$bid,'aid'=>$data['win']))->setField('rank',1);
                        //所有参加本比赛队员的ID
                        $aid = M('apply')->where(array('gid'=>$bid,'is_detele'=>0))->select();
                        //统计胜负场次
                        foreach($aid as $v){
                            $ww['win'] = M('record')->where(array('gid'=>$bid,'win'=>$v['id']))->count();
                            $ww['fail'] = M('record')->where(array('gid'=>$bid,'fail'=>$v['id']))->count();
                            M('apply')->where(array('gid'=>$bid,'id'=>$v['id']))->save($ww);
                        }
                    }

                }
            }else{
                $cc = M('eliminate')->where(array('gid'=>$bid,'type'=>4,'aid'=>array('in',array($data['win'],$data['fail']))))->find();
                $c = M('eliminate')->where(array('gid'=>$bid,'type'=>2))->count();

                if($cc){
                    M('eliminate')->where(array('gid'=>$bid,'aid'=>$data['fail']))->setField('rank',3);
                    M('eliminate')->where(array('gid'=>$bid,'aid'=>$data['win']))->setField('type',1);
                }else{
                    if(!$data['fail']){
                        continue;
                    }
                    if($c==2 && $fcc ==1){
                        M('eliminate')->where(array('gid'=>$bid,'aid'=>$data['fail']))->setField('rank',3);
                        M('eliminate')->where(array('gid'=>$bid,'aid'=>$data['win']))->setField('type',1);
                    }elseif(in_array($c,array(3,4,5,6,7,8,9,10,11,12)) && $fcc ==1){
                        M('eliminate')->where(array('gid'=>$bid,'aid'=>$data['win']))->setField('type',2);
//                        echo M('eliminate')->_sql();die;
                        M('eliminate')->where(array('gid'=>$bid,'aid'=>$data['fail']))->delete();
                    }else{
                        M('eliminate')->where(array('gid'=>$bid,'aid'=>$data['fail']))->delete();
                    }

                }
            }


        }

        $this->redirect(U('Eliminate/fail'));
    }


    public function ss(){
        $rid = I('post.rid');
        $arr['oscore'] = I('post.oscore');
        $arr['tscore'] = I('post.tscore');
        M('record')->where(array('id'=>$rid))->save($arr);
    }


    public function sss(){
        $bid = $_SESSION['uadmin']['bid'];
        $genre = I('post.genre');
        $n = I('post.round');
        $aid = I('post.aid');
        $m2 = I('post.m2');
        $arr['member2'] = $aid;
        $arr['zd'] = 1;
        if($aid == '-1'){
            $arr['member2'] = $m2;
            M('record')->where(array('gid'=>$bid,'genre'=>$genre,'round'=>$n,'member2'=>$m2))->save($arr);
            echo 1;
            exit;
        }
        $mm2 = M('record')->where(array('gid'=>$bid,'genre'=>$genre,'round'=>$n,'member2'=>$aid))->find();
        $mm1 = M('record')->where(array('gid'=>$bid,'genre'=>$genre,'round'=>$n,'member1'=>$aid))->find();

        M('record')->where(array('gid'=>$bid,'genre'=>$genre,'round'=>$n,'member2'=>$m2))->save($arr);
//        echo M('record')->_sql();
        if($mm1){
            $data['member1'] = $m2;
//            $data['zd'] = 1;
            $id = $mm1['id'];
        }
        if($mm2){
            $data['member2'] = $m2;
//            $data['zd'] = 1;
            $id = $mm2['id'];
        }

        if(M('record')->where(array('id'=>$id))->save($data) !==false){
//            echo M('record')->_sql();
            echo 1;

        }else{
            echo 0;
        }


    }
    
}