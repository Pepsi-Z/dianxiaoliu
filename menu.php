<?php
$goods_type = $_GET['type'];	
    	
    	$weurl ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxdd4810aa0790b0dd".
    			"&redirect_uri=http://www.xiaoliuge.com/oauth2.php&response_type=code&scope=snsapi_base&state=".$goods_type."#wechat_redirect";
    	header("location:".$weurl);
?>