<?php

class payAction extends backendAction
{

    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('pay');
    }
    
    public function index(){
       $aliinfo= $this->_mod->where(array('pay_type'=>'alipay'))->find();
	   $wxinfo= $this->_mod->where(array('pay_type'=>'wxpay'))->find();
	   $aliinfo['content']=unserialize($aliinfo['content']);
	   $wxinfo['content']=unserialize($wxinfo['content']);
	   $this->assign('aliinfo',$aliinfo);
       $this->assign('wxinfo',$wxinfo);
        $this->display();	
	
	}
	 /**
     * ajax修改单个字段值
     */
      public function edit()
    {
        $mod = D($this->_name);
        $pk = $mod->getPk();
        if (IS_POST) {
            if (false === $data = $mod->create()) {
                IS_AJAX && $this->ajaxReturn(0, $mod->getError());
                $this->error($mod->getError());
            }
            if (method_exists($this, '_before_update')) {
                $data = $this->_before_update($data);
            }
            if (false !== $mod->save($data)) {
                if( method_exists($this, '_after_update')){
                    $id = $data['id'];
                    $this->_after_update($id);
                }
                
                IS_AJAX && $this->ajaxReturn(1,L('operation_success'), '', 'edit');
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            $id = $this->_get($pk, 'intval');
            $info = $mod->find($id);
            $this->assign('info', $info);
            $this->assign('open_validator', true);
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajaxReturn(1, '', $response);
            } else {
                $this->display();
            }
        }
    }
    
      public function deletebrand()
    {
    	 
        $mod = D($this->_name);
      
        $pk = $mod->getPk();
        $ids = trim($this->_request($pk), ',');
        
        if ($ids) {
        
        	
            if (false !== $mod->delete($ids)) {
                IS_AJAX && $this->ajaxReturn(1, L('operation_success'));
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            IS_AJAX && $this->ajaxReturn(0, L('illegal_parameters'));
            $this->error(L('illegal_parameters'));
        }
    }
    
	

   /* public function index() {
     
    	if(IS_POST)
    	{
    		$alipayname=trim($_POST['alipayname']);
    		$partner=trim($_POST['partner']);
    		$key=trim($_POST['key']);
			$view=intval($_POST['view']);
			//echo $view;exit;
    		$data['alipayname']=$alipayname;
    		$data['partner']=$partner;
    		$data['key']=$key;
			$data['view']=$view;
			
			$wxdata['appid']     = trim($_POST['appid']);
    		$wxdata['secret']    = trim($_POST['secret']);
    		$wxdata['signkey']   = trim($_POST['signkey']);
			$wxdata['partner']   = trim($_POST['partner']);
    		$wxdata['partnerkey']= trim($_POST['partnerkey']);
			$wxdata['wxview']    = intval($_POST['wxview']);
			
    		if(empty($_POST['id']))
    		{

    			if($this->_alimod->data($data)->add()!==false)
    			{
    				$this->success('修改成功!');exit;
    			}else
    			{
    				$this->error('修改失败');exit;
    			}
    		}else
    		{
              
    		if($this->_alimod->data($data)->where('id='.$_POST['id'])->save()!==false)
    		{
    				$this->success('修改成功!');exit;
    		}else 
    		{
    			$this->error('修改失败');exit;
    		}

    		}
    		
    	}else 
    	{
    		$info= $this->_alimod->find();
			$wxinfo= $this->_wxmod->find();
    		$this->assign('wxinfo',$wxinfo);
    	}
    	
    	$this->display();
    }*/
    
}