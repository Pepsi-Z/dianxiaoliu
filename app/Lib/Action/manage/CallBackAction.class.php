<?php
class CallBackAction extends frontendAction
{

    public function call_back_url(){
    	$apply_id = $_POST['trade_no'];
    	$info = M('ordersite')->where(" order_number= ".$apply_id)->find();
        if($apply_id){
            $v['order_status'] = 1;
            if(M('ordersite')->data($v)->where("order_number='".$apply_id."'")->save() !== false){
	            
            	$arr['state'] = 1;
            	M('member_money')->data($arr)->where("type = 15 and order_number='".$apply_id."'")->save();
            	
            	if($info['vipnum']){
                    echo 111;
	 				$url = U('Member/hy_xf');
		 		}else{
                    echo 222;
		 			$url = U('Member/fhy_xf');
		 		}
		 		$this->redirect();
            	
                $this->success("支付成功！",$url);
            }else{
                $this->redirect(U('Shortsite/call_back_url',array('trade_no'=>$apply_id)));
            }
        }
    }
 	public function delete_apply(){
 		$order_number = $_GET['id'];
 		$info = M('ordersite')->where(" order_number= ".$order_number)->find();
 		M('ordersite')->where(" order_number= ".$order_number)->delete();
 		if($info['vipnum']){
 			$url = U('Mysite/member_site');
 		}else{
 			$url = U('Mysite/nmember_site');
 		}
 		$this->redirect($url);
    }
}


