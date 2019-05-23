<?php
class ZhuanpanAction extends userbaseAction
{
	public $u_phone 	= '';
	public $u_name 		= '';
	public function index()
	{
		//读取抽奖配置
		$zhuanpan = M('zhuanpan_config');
		$zp_info = $zhuanpan->where("id=1")->find();
		if(time()<= $zp_info['hd_begin_time'] || time() >= $zp_info['hd_end_time'])
		{
			$this->assign('hd_over',1);
		}else{
			$this->assign('hd_over',0);
		}
		$this->assign('zp_info',$zp_info);
		//读取抽奖奖项
		$zplist = M('zhuanpan_list');
		$zplistinfo = $zplist->where("id<7")->order("id Asc")->select();
		$this->assign('zplistinfo',$zplistinfo);
		$this->display();
	}

    public function run(){
    	$this->u_phone 	= trim($_GET['uphone']);
    	$this->u_name 	= trim($_GET['uname']);
    	$prize_arr=array();
    	$m= M('zhuanpan_list');
    	$arr=$m->select();
    	foreach($arr as $key=>$val){
			$min=explode(",",$val['min']);
			$max=explode(",",$val['max']);
			if(count($min)>1){
				$val['min']=$min;
			}
			if(count($max)>1){
				$val['max']=$max;
			}
				$prize_arr[$key]=$val;
		}
		echo $this->getResult($prize_arr);
    }
    
    private function getResult($priearr){
		$arr=array();
		$count=array();
		foreach ($priearr as $key => $val) {
    		$arr[$val['id']] = $val['chance'];
    		$count[$val['id']] = $val['praisenumber'];
		}
		$rid = $this->getRand($arr,$count); //根据概率获取奖项id
		$res = $priearr[$rid-1]; //中奖项
		
		$listid	= $res['id'];//中奖ID
		
		$zpconfig	= M('zhuanpan_config');
		$zp_con_info = $zpconfig->where("id=1")->find();
		
		$zplist = M('zhuanpan_list');
		$zp_list_info	= $zplist->where("id=$listid")->find();
		
		$zplog	= M('zhuanpan_log');
		
		$user_id	= $this->visitor->info['id'];
		$userinfo = M('user')->where("id=$user_id")->find();
		//读取用户今天已抽取数
		$map['addtime'] = array('egt',strtotime(date("Y-m-d 00:00:00")));
		$map['userid']	= $user_id;
		
		$you_num = $zplog->where($map)->count("id");
		if(!$you_num)
		{
			$you_num = 1;
		}
		if(($zp_con_info['hd_cishu'] - $you_num) > 0)//如果有抽奖次数
		{
			//添加到抽奖记录
			$log_data['userid']		= $user_id;
			$log_data['username']	= $userinfo['username'];
			$log_data['u_phone']	= $this->u_phone;
			$log_data['u_name']		= $this->u_name;
			$log_data['snid']		= $user_id.$this->makecode(6).date("s",time());
			$log_data['listid']		= $listid;
			$log_data['addtime']	= time();
			$log_data['status']		= 0;
			$zplog->data($log_data)->add();
			
			$num=$zp_con_info['hd_cishu'] - $you_num;
			
			$min = $res['min'];
			$max = $res['max'];
			if(is_array($min)){ //多等奖的时候
    			$i = mt_rand(0,count($min)-1);
    			$result['angle'] = mt_rand($min[$i],$max[$i]);
			}else{
    			$result['angle'] = mt_rand($min,$max); //随机生成一个角度
			}
			$result['praisename'] = $zp_list_info['praisecontent'];
			if($listid != 7)//如果中奖
			{
				$msg = "恭喜你获得【".$zp_list_info['praisecontent'].'】'.$zp_con_info['hd_duijiang_info'];
			}else{
				$msg	= "请您再接再厉，本次没有中奖哦!!";
			}
			$result['msg']		= $msg;
		}else{
			$num=0;
			$result['praisename'] =null;
			$result['angle']=0;
			$result['msg']		= '';
		}
		$result['num']=$num;	
		return $this->json($result);
    }
    
    private function getRand($proArr,$proCount){
    	$result = '';
    	$proSum=0;
    	//概率数组的总概率精度  获取库存不为0的
    	foreach($proCount as $key=>$val){
    		if($val==0){
    			continue;
    		}else{
    			$proSum=$proSum+$proArr[$key];
    		}
    	}
    	//概率数组循环
    	foreach ($proArr as $key => $proCur) {
    		if($proCount[$key]==0){
    			continue;
    		}else{
    			$randNum = mt_rand(1, $proSum);//关键
        		if ($randNum <= $proCur) {
        			$result = $key;
           	 		break;
        		}else{
            		$proSum -= $proCur;
        		}
    		}

    	}
    	unset ($proArr);
    	return $result;
    }
    private function json($array){
    	$this->arrayRecursive($array, 'urlencode', true);
		$json = json_encode($array);
		return urldecode($json);
    }
    //对数组中所有元素做处理
	private function arrayRecursive(&$array, $function, $apply_to_keys_also = false){
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				arrayRecursive($array[$key], $function, $apply_to_keys_also);
			}else{
				$array[$key] = $function($value);
			}
			if ($apply_to_keys_also && is_string($key)){
				$new_key = $function($key);
				if ($new_key != $key){
					$array[$new_key] = $array[$key];
					unset($array[$key]);
				}
			}
		}
	}
	//生成随机字母
	public function makecode($lenth)
	{
		$pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
		for($i = 0; $i < $lenth; $i++) {
        	$code .= $pattern{mt_rand(0, 61)};
		}
		return $code;
	}
}
?>