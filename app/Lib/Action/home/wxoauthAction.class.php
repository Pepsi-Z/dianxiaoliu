<?php
class wxoauthAction extends frontendAction {
    /**
     * 第三方帐号登陆和绑定
     */
    public function index() {
    	$mod = $this->_get('mod', 'trim');
    	!$mod && $this->_404();
        $wxoauth = new wxoauth($mod);
        return $wxoauth->authorize();
    }

    /**
     * 登陆回调页面
     */
    function callback() {
        $mod = $this->_get('mod', 'trim');
		!$mod && $this->_404();
        $callback_type = cookie('callback_type');
        $wxoauth = new wxoauth($mod);
        $rk = $wxoauth->NeedRequest();
        $request_args = array();
        foreach ($rk as $v) {
            $request_args[$v] = $this->_get($v);
        }
        /*switch ($callback_type) {
            case 'login':
                $url = $wxoauth->callbackLogin($request_args);
                break;
            case 'bind':
                $url = $wxoauth->callbackbind($request_args);
                break;
            default:
                $url = U('index/index');
                break;
        }
        cookie('callback_type', null);*/
		if(!empty($request_args['code'])){
			 $url = $wxoauth->callbackbinduser($request_args);
		}else{
			$url = U('User/login');
			$_SESSION['oauth2cache']='ok';
		}
        redirect($url);
    }
}