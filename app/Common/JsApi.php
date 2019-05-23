<?php 
class JsApi{
		public function GetOpenid()
		{
			//通过code获得openid
			if (!isset($_GET['code'])){
				//触发微信返回code码
				$url1 = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
				if($_SERVER['QUERY_STRING']){
					$url1 .= '?'.$_SERVER['QUERY_STRING'];
				}
				$baseUrl = urlencode($url1);
				$url = $this->__CreateOauthUrlForCode($baseUrl);
				Header("Location: $url");
				exit();
			} else {
				//获取code码，以获取openidexit;
			    $code = $_GET['code'];
				$openid = $this->getOpenidFromMp($code);
				return $openid;
			}
		}
		
		private function __CreateOauthUrlForCode($redirectUrl)
		{
			$urlObj["appid"] = APPID_INDEX;
			$urlObj["redirect_uri"] = "$redirectUrl";
			$urlObj["response_type"] = "code";
			$urlObj["scope"] = "snsapi_base";
			$urlObj["state"] = "STATE"."#wechat_redirect";
			$bizString = $this->ToUrlParams($urlObj);
			return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
		}
		
	private function ToUrlParams($urlObj)
	{
		$buff = "";
		foreach ($urlObj as $k => $v)
		{
			if($k != "sign"){
				$buff .= $k . "=" . $v . "&";
			}
		}
		
		$buff = trim($buff, "&");
		return $buff;
	}
	
	public function GetOpenidFromMp($code)
	{
		$url = $this->__CreateOauthUrlForOpenid($code);
		//初始化curl
		$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);//$this->curl_timeout
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//		if(WxPayConfig::CURL_PROXY_HOST != "0.0.0.0" 
//			&& WxPayConfig::CURL_PROXY_PORT != 0){
//			curl_setopt($ch,CURLOPT_PROXY, WxPayConfig::CURL_PROXY_HOST);
//			curl_setopt($ch,CURLOPT_PROXYPORT, WxPayConfig::CURL_PROXY_PORT);
//		}
		//运行curl，结果以jason形式返回
		$res = curl_exec($ch);
		curl_close($ch);
		//取出openid
		$data = json_decode($res,true);
		$openid = $data['openid'];
		return $openid;
	}
	
	private function __CreateOauthUrlForOpenid($code)
	{
		$urlObj["appid"] = APPID_INDEX;
		$urlObj["secret"] = SECRET_INDEX;
		$urlObj["code"] = $code;
		$urlObj["grant_type"] = "authorization_code";
		$bizString = $this->ToUrlParams($urlObj);
		return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
	}
}
?>