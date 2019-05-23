<?php
//---------------------------------------------------------
//---------------------------------------------------------

class MessageUtil{

	public static function sendTextInfo($openid,$content){
		$access_token = MessageUtil::initToken();
		if($access_token)
    	{
    		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=";
    		$txt = '{
					    "touser":"'.$openid.'",
					    "msgtype":"text",
					    "text":
					    {
					         "content":"'.$content.'"
					    }
					}';
		 	MessageUtil::dosend($txt, $access_token);
    	}
	}
	
	public static function sendNewsInfo($openid,$titles,$descriptions,$urls,$pictures){
		$access_token = MessageUtil::initToken();	
		if($access_token)
    	{
    		if(is_string($titles) && is_string($descriptions) && is_string($urls) && is_string($pictures)){
    			$content = '{
					             "title":"'.$titles.'",
					             "description":"'.$descriptions.'",
					             "url":"'.$urls.'",
					             "picurl":"'.$pictures.'"
					         }'	;
    		}
    		
    		if(is_array($titles) && is_array($descriptions) && is_array($urls) && is_array($pictures)){
    			$content = "";
    			for ($i=0;$i<count($titles);$i++){
    				$content .= '{
					             "title":"'.$titles[$i].'",
					             "description":"'.$descriptions[$i].'",
					             "url":"'.$urls[$i].'",
					             "picurl":"'.$pictures[$i].'"
					         },';
    			}
    		}
    		
    		if($content){
	    		$news='{
						    "touser":"'.$openid.'",
						    "msgtype":"news",
						    "news":{
						        "articles": ['.
						         $content.
						         ']
						    }
						}';
	    		
	    		MessageUtil::dosend($news, $access_token);
    		}
    	}	
	}
	
	private static function dosend($content,$access_token){
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=";
		$result = MessageUtil::https_post($url,$content,$access_token);
		if($result['errcode'] == '40001' || $result['errcode'] == '42001'){
			$access_token = MessageUtil::getNewToken();
			$result = MessageUtil::https_post($url,$content,$access_token);
		}
		//echo $result['errcode'];
	}
	
	public static function checkToken($content,$access_token){
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=";
		$result = MessageUtil::https_post($url,$content,$access_token);
		if($result['errcode'] == '40001' || $result['errcode'] == '42001'){
			$access_token = MessageUtil::getNewToken();
		}
		return $access_token;
	}
	
	public static function initToken(){
		$access_token = F("access_token");
    	if($access_token==null){
    		$access_token = MessageUtil::getNewToken();
    	}
		return $access_token;
	}
	
	private static function getNewToken(){
		$appid= APPID_INDEX;
    	$appsecret=SECRET_INDEX;
    	$ACCESS_LIST= MessageUtil::curl($appid,$appsecret);
    	if($ACCESS_LIST['access_token']!=''){
    		F("access_token",$ACCESS_LIST['access_token']);
    	}
    	return F("access_token");
	}
	
	private static function curl($appid,$secret)
    {
		 $ch = curl_init(); 
		 curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret); 
		 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		 curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		 curl_setopt($ch, CURLOPT_AUTOREFERER, 1); 
		// curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		 $tmpInfo = curl_exec($ch); 
		 if (curl_errno($ch)) {  
			echo 'Errno'.curl_error($ch);
		 }
		 curl_close($ch); 
		 $arr= json_decode($tmpInfo,true);
		 return $arr;
    }
    
	private static function https_post($url,$data,$access_token)
	{
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url.$access_token); 
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	    curl_setopt($curl, CURLOPT_POST, 1);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    $result = curl_exec($curl);
	    if (curl_errno($curl)) {
	       return 'Errno'.curl_error($curl);
	    }
	    curl_close($curl);
	    return json_decode($result,true);
	}
}

?>