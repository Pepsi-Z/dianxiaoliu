<?php

/**
 * Wechat
 * 
 * @author www.5cando.com
 * @copyright 2014
 * @version 1.2
 * @access public
 */
class Wechat{

	
   
    /**
     * Wechat::getXmlArray()
     * 从xml中获取数组
     * @return array
     */
    public function getXmlArray() {
		$postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
		if ($postStr) {
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            if (! is_object($postObj)) {
                return false;
            }
            $array = json_decode(json_encode($postObj), true); // xml对象转数组
            return array_change_key_case($array, CASE_LOWER); // 所有键小写
        } else {
            return false;
        }        
    }
    
	

	/**
	 * Wechat::buildSign()
	 * 生成sign值
	 * @param array $array
	 * @param array $config
	 * @return string
	 */
	public function buildSign($array, $config) {
		$signPars = "";
        $array['appKey'] = $config['paySignKey'];
		ksort($array);
		foreach($array as $k => $v) {
			if("" != $v && "sign" != $k) {
				$signPars .= strtolower($k) . "=" . $v . "&";
			}
		}
        $signPars = substr($signPars, 0, -1); // 去除最后一个&符号
		$sign = sha1($signPars);
		return $sign;

	}
    
    /**
     * wechat::getAccessToken()
     * 获取access_token
     * @return string
     */
    public function getAccessToken($config)
    {
     $ch = curl_init(); 
     curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$config['appId']."&secret=".$config['appSecret']); 
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


    /**
     * Wechat::delivernotify()
     * 发货通知
     * @param array $config
     * @param array $parameter
     * @return array
     */
    public function delivernotify($config, $parameter) {
        $url = 'https://api.weixin.qq.com/pay/delivernotify?access_token=' . $this->getAccessToken($config);
        $parameter += array(
            'app_signature' => $this->buildSign($parameter, $config),
            'sign_method' => 'sha1'
        );
        $result = $this->doPost($url, json_encode($parameter));
        return json_decode($result, true);
    }
    
    /**
     * Wechat::orderquery()
     * 订单查询
     * @param array $config
     * @param string $out_trade_no
     * @return array
     */
    public function orderquery($config, $out_trade_no) {
        if (! $config || ! $out_trade_no) {
            return false;
        }
        $url = 'https://api.weixin.qq.com/pay/orderquery?access_token=' . $this->getAccessToken($config);
        $array = array(
            'appid' => $config['appId'],
            'package' => 'out_trade_no=' . $out_trade_no . '&partner=' . $config['partnerId'] . '&sign=' . strtoupper(md5('out_trade_no=' . $out_trade_no . '&partner=' . $config['partnerId'] . '&key=' . $config['partnerkey'])),
            'timestamp' => mktime()
        );
        $array .= array(
            'app_signature' => $this->buildSign($array, $config),
            'sign_method' => 'sha1'
        );
        $result = $this->doPost($url, json_encode($array));
        return json_decode($result, true);
    }
    
    /**
     * Wechat::doPost()
     * post请求
     * @param string $url
     * @param array $data
     * @return
     */
    public function doPost($url, $data) {
        $context = array('http' => array('method' => "POST", 'header' => "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) \r\n Accept: */*", 'content' => $data));
        $stream_context = stream_context_create($context);
        $request = @file_get_contents($url, FALSE, $stream_context);
        return $request;
    }
    /**
     * Wechat::log()
     * log日志
     * @param $word 日志信息
     * 
     */
    public function log_result($word) {
        $fp = fopen("log.txt","a");
        //flock($fp, LOCK_EX) ;
        fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n\n");
        //flock($fp, LOCK_UN);
        fclose($fp);
    }
}
