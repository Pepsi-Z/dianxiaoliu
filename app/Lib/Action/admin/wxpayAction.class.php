<?php

class wxpayAction extends backendAction
{

    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('pay');
    }

    public function index() {
     
    	 if(IS_POST)
        {
            $appid=trim($_POST['appid']);
            $appsecret=trim($_POST['appsecret']);
            $signkey=trim($_POST['signkey']);
            $partnerid=trim($_POST['partnerid']);
            $partnerkey=trim($_POST['partnerkey']);
            $status=intval($_POST['status']);
            //echo $view;exit;
            $data['appid']=$appid;
            $data['appsecret']=$appsecret;
            $data['signkey']=$signkey;
            $data['partnerid']=$partnerid;
            $data['partnerkey']=$partnerkey;
            $pay['status']=$status;
            $pay['config']=serialize($data);
            $pay['pay_type']='wxpay';
            $pay['name']='微信支付';

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
    	
            $wxinfo= $this->_mod->where(array('pay_type'=>'wxpay'))->find();
            $payconfig=unserialize($wxinfo['config']);
            $this->assign('wxinfo',$wxinfo);
            $this->assign('payconfig',$payconfig);
    	}
    	
    	$this->display();
    }     
}