<?php
class ArticleAction extends frontendAction {
	
	public function wx_index(){
    	$_SESSION['openid'] = $_GET['openid'];
    	$this->redirect('index');
    }
   //讯息
    public function index() {
    	$_SESSION['foot_id'] = $_GET['foot_id'];
        $mod = M('article');
        $count = $mod->where(' status = 1')->count();
        $pager = new Page($count, 20);
        $list = $mod->where('status = 1')->limit($pager->firstRow.','.$pager->listRows)->select();
        $this->assign('list',$list);
        $this->assign('page',$pager->show());
        $this->display();
    }
    //讯息详情
    public function content(){
    	$id = $this->_get('id', 'intval');
    	$info = M('article')->where('id = '.intval($id))->find();
    	$this->assign('info',$info);
    	$this->display();
    
    }
    public function cate() {
       $cid = $this->_get('cid', 'intval');
       !$cid && $this->_404();
	   
	   //分类数据
       $cate_info = M('article_cate')->where(array("id"=>$cid))->find();
              
		$item=M('article');
		$where['cate_id']=array('eq',$cid);
		$where['status']=array('eq',1);
		$items=  $item->field('id,title,img,intro')->order('ordid asc,id desc')->where($where)->select();

        $this->assign('cate_name',$cate_info['name']);
		$this->assign('item_list',$items);
       //SEO
        $this->_config_seo(C('pin_seo_config.cate'), array(
            'cate_name' => $cate_info['name'],
            'seo_title' => $cate_info['seo_title'],
            'seo_keywords' => $cate_info['seo_keys'],
            'seo_description' => $cate_info['seo_desc'],
        ));
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