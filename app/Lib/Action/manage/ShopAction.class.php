<?php
class ShopAction extends frontendAction {
	
	// 微官网
    public function index() {
        $this->display();
    }
	// 积分商城
    public function score_shop() {
        $this->display();
    }
    
	// 个人中心
    public function user_shop() {
        $this->display();
    }
    public function comment(){
        $id = $_GET['id'];
        $user = M('user')->where(array('id'=>$id))->field('id,name,card_number')->find();
        $this->assign('user',$user);
        $this->display();

    }

}