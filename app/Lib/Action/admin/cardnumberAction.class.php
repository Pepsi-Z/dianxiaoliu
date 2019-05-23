<?php
class cardnumberAction extends backendAction
{
    public function _initialize() {
        parent::_initialize();
        $this->assign('list_table', true);
        //$this->list_relation = true;
    }
   public function index(){
       $mod = D('cardnumber');
       $count=$mod->where(array('line'=>1))->count();
       $Page       = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
       $show       = $Page->show();// 分页显示输出
       $join = array('a left join __CARDTYPE__ b ON a.cid = b.id ');
       $field = 'a.*,b.gname,b.money';
       $list = $mod->order('a.id desc')->join($join)->field($field)->where(array('a.line'=>1))->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $key=>$val)
        {
            $cards = $val['cards'];
          if(D('user')->where(array('card_number'=>$cards))->find()){
                $list[$key]['zhuangtai'] = '已经使用';
            }

        }

        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }

    public function cardnumber() {
        if(IS_POST){
            $mod = D('cardnumber');
            $cards = I('post.cards','','trim'); //循环次数
                for($i = 1; $i <= $cards; $i++){
                    $cardnum = $this->NoRand(); //随机数
                    $code = $this->CoRand();
                    if($mod->where(array('cards'=>$cardnum))->find()){
                        unset($cardnum);
                        unset($code);
                        continue; //跳出本次循环
                    }
                    $data['cards'] = $cardnum;
                    $data['code']  = $code;
                    $data['line']  = $_POST['line']; //判断是线上0 还是线下1
                    if(!empty($_POST['line'])){
                    $data['cid'] = $_POST['cardtype'];
                    $card_name = M('cardtype')->where(array('id'=>$_POST['cardtype']))->getField('gname');
                        if($card_name == '半年卡'){
                            $data['begin_time'] = date("Y-m-d",time());
                            $data['end_time'] = date("Y-m-d", strtotime("+6 months", strtotime($data['begin_time'])));

                        }elseif($card_name == '一年卡'){
                            $data['begin_time'] = date("Y-m-d",time());
                            $data['end_time'] = date("Y-m-d", strtotime("+12 months", strtotime($data['begin_time'])));
                        }else{

                        }
                    }
                     $mod->add($data);
                }
                IS_AJAX && $this->ajaxReturn(1, L('operation_success'), '', 'edit');
                $this->success(L('operation_success')/*U('cardnumber/index')*/);
        }else{
            $cardtype = M('cardtype')->where(array('status' =>1))->select();
            $this->assign('cardtype',$cardtype);
            $this->display();
        }

    }

    public function import() {
        $mod = D('cardnumber');
        if (IS_POST) {
            if (false === $data = $mod->create()) {
                IS_AJAX && $this->ajaxReturn(0, $mod->getError());
                $this->error($mod->getError());
            }

            $import = explode(PHP_EOL,trim($data['cards']));

            foreach($import as $key=>$val){
                $code = $this->CoRand();
                if($mod->where(array('cards'=>$val))->find()){
                    unset($val);
                    unset($code);
                    $this->error('输入的幸运号已经存在');
                    continue; //跳出本次循环
                }
                if( true != is_numeric($val)){
                    $this->error('只允许输入数字');
                }
                if( mb_strlen($val) != 8){
                    $this->error('只允许输入8位数字');
                }
                $data['cards'] = $val;
                $data['code'] = $code;
                $data['status'] = 2; //2表示幸运号
                $data['line']  = $_POST['line']; //判断是线上0 还是线下1
                if(!empty($_POST['line'])){
                    $data['cid'] = $_POST['cardtype'];
                    $card_name = M('cardtype')->where(array('id'=>$_POST['cardtype']))->getField('gname');
                    if($card_name == '半年卡'){
                        $data['begin_time'] = date("Y-m-d",time());
                        $data['end_time'] = date("Y-m-d", strtotime("+6 months", strtotime($data['begin_time'])));

                    }elseif($card_name == '一年卡'){
                        $data['begin_time'] = date("Y-m-d",time());
                        $data['end_time'] = date("Y-m-d", strtotime("+12 months", strtotime($data['begin_time'])));
                    }else{

                    }
                }
                $mod->add($data);
            }
            $this->success(L('operation_success'));

        } else {
            $cardtype = M('cardtype')->where(array('status' =>1))->select();
            $this->assign('cardtype',$cardtype);
            $this->display();

        }
    }
    public function lucknum(){
        $mod = D('member');
        $join = array('a left join __CARDNUMBER__ b on a.vipnum=b.cards',
                        'left join __CARDTYPE__ c on a.cardtype=c.id');
        $count = $mod->field('a.*,b.cards,c.gname')->join($join)->where(array('b.status'=>2))->count();
        $Page       = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list = $mod->field('a.*,b.cards,c.gname')->join($join)->where(array('b.status'=>2))->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();

    }
    //导出数据方法
    public function export_excel(){
        $mod = D('cardnumber');
        $id = implode(',', $_POST['id']);
        $where = 'a.line = 1 and a.state = 0';
        if($id){
            $where .= " and a.id in({$id})";
        }
        $join = array('a left join __CARDTYPE__ b ON a.cid = b.id ');
        $field = 'a.*,b.gname,b.money';
        $list = $mod->join($join)->field($field)->where("{$where}")->select();
       // echo $mod->_sql();die;

        foreach($list as $key=>$val)
        {
            $cards = $val['cards'];
          if(D('user')->where((array('card_number'=>$cards)))->find()){
                $list[$key]['zhuangtai'] = '已经使用';
            }

        }
        $arr=array('会员卡号','激活码','卡号类型','价钱','开始时间','结束时间','使用状态');
        $title=date("Y-m-d").'卡号列表';
        $this->exportFile($list,$arr,$title);
    }

    public function exportFile($data=array(),$title=array(),$filename='report'){
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=".$filename.".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        //导出xls 开始
        if (!empty($title)){
            foreach ($title as $k => $v) {
                $title[$k]=iconv("UTF-8", "GB2312",$v);
            }
            $title= implode("\t", $title);
            echo "$title\n";
        }
        if (!empty($data)){
            foreach($data as $key=>$val){
                $va['cards']=$val['cards'];
                $va['code']=$val['code'];

                $va['gname']=$val['gname'];
                $va['money']=$val['money'];
                $va['begin_time']=$val['begin_time'];
                $va['end_time']=$val['end_time'];

                if(!empty($val['zhuangtai'])){
                    $va['zhuangtai']='已启用';
                }else{
                    $va['zhuangtai']='未启用';
                }
                foreach ($va as $ck => $cv) {
                    $va[$ck]=iconv("UTF-8", "GB2312", $cv);
                }
                $vas[]=implode("\t", $va);

            }
            echo implode("\n",$vas);
        }
    }

    //未使用的幸运卡号
    public function nouse_cardnumber(){
        $mod = D('cardnumber');
        $count=$mod->where(array('status' =>2))->count();
        $Page       = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list = $mod->order('id desc')->where(array('status' =>2))->limit($Page->firstRow.','.$Page->listRows)->select();
        //p($list);

        foreach($list as $key=>$val)
        {
            $cards = $val['cards'];
            if(D('member')->where(array('vipnum'=>$cards))->find()){
                unset($list[$key]);
                //array_shift($list[$key]);
            }

        }
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }




    //生产8位随机数
    public function NoRand($begin=10,$end=99,$limit=4){
        $rand_array=range($begin,$end);
        shuffle($rand_array);
        $list = array_slice($rand_array,0,$limit);
        foreach ($list as $value) {
            $string .= $value;
        }
        return $string;
    }

    //生产6位激活码
    public function CoRand($begin=10,$end=99,$limit=3){
        $rand_array=range($begin,$end);
        shuffle($rand_array);
        $list = array_slice($rand_array,0,$limit);
        foreach ($list as $value) {
            $string .= $value;
        }
        return $string;
    }

}