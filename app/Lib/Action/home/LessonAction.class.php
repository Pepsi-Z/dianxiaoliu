<?php
class LessonAction extends frontendAction {

    public function index() {
        $this->display();
    }
    // 综合课程
	public function zh_lesson() {
        $this->display();
    }
 	// 运动课程
	public function yd_lesson() {
        $this->display();
    }
	// 音乐课程
	public function yy_lesson() {
        $this->display();
    }
	// 创意课程
	public function cy_lesson() {
        $this->display();
    }
	// 语言课程
	public function yuyan_lesson() {
        $this->display();
    }
	// 预约试听
	public function yyst_lesson() {
        $this->display();
    }
	// 预约试听（提交）
	public function yyst_post() {
		$ss = $this->_post();
        $weixin_baoming = M('baoming');
        
        $ss['datetime'] = date("Y-m-d H:i:s");
        $c = $weixin_baoming->add($ss);
        if($c){
        	echo true;
        }else{
        	echo false;
        }
    }
    
    // 查看课程信息页面
    public function lesson_show (){
    	$kid = $_GET['kid'];
    	$type = $_GET['type'];
    	
    	$keyinfo=M('keyword')->where('kid = '.$kid)->select();
		
    	if($type == 1){
    		$this->assign('keyinfo',$keyinfo[0]['content1']);
    	}elseif($type == 2){
    		$this->assign('keyinfo',$keyinfo[0]['content2']);
    	}elseif($type == 3){
    		$this->assign('keyinfo',$keyinfo[0]['content3']);
    	}elseif($type == 4){
    		$this->assign('keyinfo',$keyinfo[0]['content4']);
    	}elseif($type == 5){
    		$this->assign('keyinfo',$keyinfo[0]['content5']);
    	}elseif($type == 6){
    		$this->assign('keyinfo',$keyinfo[0]['content6']);
    	}
    	$this->display();
    }
	public function show() {
       $id = $this->_get('id', 'intval');
       !$id && $this->_404();
	   $info = M('article')->find($id);
		$this->assign('info', $info);
		$this->assign('id', $id);
		$this->_config_seo();
		$this->display();
	}
	public function listcate() {
		//获取分类
		$where['status']=array('eq',1);
	    $cate_info = M('article_cate')->order('ordid asc,id desc')->where($where)->select();
		
		$this->assign('art_list', $cate_info);
		$this->_config_seo();
		$this->display();
	}

}