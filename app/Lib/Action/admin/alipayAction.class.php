<?php

class alipayAction extends backendAction
{

    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('pay');
    }

    public function index() {
     
    	if(IS_POST)
    	{
    		$alipayname=trim($_POST['alipayname']);
    		$partner=trim($_POST['partner']);
    		$key=trim($_POST['key']);
			$status=intval($_POST['status']);
			//echo $view;exit;
    		$data['alipayname']=$alipayname;
    		$data['partner']=$partner;
    		$data['key']=$key;
			$pay['status']=$status;
			$pay['config']=serialize($data);
            $pay['pay_type']='alipay';
            $pay['name']='手机支付宝';
    		if(empty($_POST['id']))
    		{

    			if($this->_mod->data($pay)->add()!==false)
    			{
    				$this->success('修改成功!');exit;
    			}else
    			{
    				$this->error('修改失败');exit;
    			}
    		}else
    		{
              
    		if($this->_mod->data($pay)->where('id='.$_POST['id'])->save()!==false)
    		{
    				$this->success('修改成功!');exit;
    		}else 
    		{
    			$this->error('修改失败');exit;
    		}

    		}
    		
    	}else 
    	{
    		$aliinfo= $this->_mod->where(array('pay_type'=>'alipay'))->find();
    		$payconfig=unserialize($aliinfo['config']);
            $this->assign('aliinfo',$aliinfo);
             $this->assign('payconfig',$payconfig);
    	}
    	
    	$this->display();
    }  
}