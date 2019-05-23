<?php
/**
 * 帐户管理
 */
class accountAction extends backendAction
{

    public function index()
    {
    	$user = M('user');
    	if( $keyword = $this->_request('keyword', 'trim') ){
            $map['_string'] = "username like '%".$keyword."%' OR email like '%".$keyword."%'";
        }
    	$count      = $user->where($map)->count();// 查询满足要求的总记录数 $map表示查询条件
    	$Page       = new Page($count);// 实例化分页类 传入总记录数
    	$show       = $Page->show();// 分页显示输出
    	// 进行分页数据查询
    	$list = $user->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
    	$this->assign('list',$list);// 赋值数据集
    	$this->assign('page',$show);// 赋值分页输出
    	$this->display();
    }
    
    public function chongzhi()
    {
    	$id = $this->_get('id','intval');
    	$user = M('user');
    	$userinfo = $user->where("id=$id")->find();
    	$this->assign('userinfo',$userinfo);
    	$this->display();
    }
    public function chongzhi_add()
    {
    	$user_acclog  = M('user_acclog');
    	$user = M('user');
    	
    	$userid						= $_POST['userid'];
    	$price						= trim($_POST['price']);
    	
    	$userinfo = $user->where("id=$userid")->find();
    	if(!is_array($userinfo))
		$this->_404();
    	if($_POST['price'] == '')
    	{
    		$this->error('请输入充值金额');
    	}
    	if($_POST['intro'] == '')
    	{
    		$this->error('请输入充值说明');
    	}
    	//更新用户帐户
    	$user_data['user_account'] 	= sprintf("%01.2f", $userinfo['user_account'] + $price);
    	$user->where("id=$userid")->save($user_data);
    	//添加记录
    	//添加帐户记录
		$log_data['userid']		= $userid;
		$log_data['username']	= $userinfo['username'];
		$log_data['fl']			= 3;
		$log_data['jiner']		= $price;
		$log_data['addtime']	= time();
		$log_data['info']		= $_POST['intro'].'  管理员：'.$_SESSION['admin']['username'];
		$log_data['orderid']	= $userid.date("YmdHis",time()).rand(1, 99);
		$log_data['status']		= '成功';
		$user_acclog->add($log_data);
		$this->success('人工充值成功', U('account/index'));
    }
	public function log()
    {
    	$useracc = M('user_acclog');
    	if( $keyword = $this->_request('keyword', 'trim') ){
            $map['_string'] = "username like '%".$keyword."%' ";
        }
        if($fl = $this->_request('fl', 'trim'))
        {
        	if(!empty($fl))
        	{
        		$map['fl']	= $fl;
        	}
        }
    	$count      = $useracc->where($map)->count();// 查询满足要求的总记录数 $map表示查询条件
    	$Page       = new Page($count);// 实例化分页类 传入总记录数
    	$show       = $Page->show();// 分页显示输出
    	// 进行分页数据查询
    	$list = $useracc->where($map)->order('logid desc')->limit($Page->firstRow.','.$Page->listRows)->select();
    	$this->assign('list',$list);// 赋值数据集
    	$this->assign('page',$show);// 赋值分页输出
    	$this->display();
    }

}