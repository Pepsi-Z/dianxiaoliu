<?php
class ActivityAction extends frontendAction {
	
	// 活动
    public function index() {
        $mod = M('item');
        $time = time();
        $level = $this->user['level'];
        $count = $mod->where('end_time >'.$time.' and status = 1')->count();
        $pager = new Page($count, 20);
        $list = $mod->where('end_time >'.$time.' and status = 1')->limit($pager->firstRow.','.$pager->listRows)->select();
		foreach($list as $k=>$v){
		  //当前等级的折扣
//            $discount = ($level == '0')?'discount':"discount".$level;
//            $discountp = M('item_cate')->where('id = '.$v['cate_id'])->getField($discount);
            //当前等级的价格
            $list[$k]['price'] = $v['xs_price']  ;
           
			$list[$k]['time'] = time();
            $list[$k]['end_time'] = date('Y-m-d-H-i-s', $v['end_time']);
            $list[$k]['y'] = date('Y', $v['end_time']);
            $list[$k]['m'] = date('m', $v['end_time']);
            $list[$k]['d'] = date('d', $v['end_time']);
            $list[$k]['h'] = date('H', $v['end_time']);
            $list[$k]['min'] = date('i', $v['end_time']);
            $list[$k]['s'] = date('s', $v['end_time']);
		}
        $this->assign('list',$list);
        $this->assign('page',$pager->show());
        $this->display();
    }
	// 活动回顾
    public function hdhg_activity() {
        $this->display();
    }
	public function set(){
		if($_POST['id']){
			$mod = M('item');
			$mod->where(array('id'=>$_POST['id']))->setField('end_time',0);
			echo $mod->_sql();
		}
	}
}