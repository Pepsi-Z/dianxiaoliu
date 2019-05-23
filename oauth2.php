<?php


if (isset($_GET['code'])){
    $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxdd4810aa0790b0dd".
    	   "&secret=20533e302cef32c7ea1a0669edebc69f&code=".$_GET['code']."&grant_type=authorization_code";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);//数据发送地址
	//curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
	$result = curl_exec($ch);
	
	$j = json_decode($result);
	
	$fcate_id = $_GET['state'];


	// 判断是否为数字 数字为商品分类
    if($fcate_id == 'thr_1'){ //首页
    	$urls = "index.php?m=Index&a=index&foot_id=1&openid=".$j->openid;
    	header("location:".$urls);
	}else if($fcate_id == 'thr_3'){ //我要办卡
    	$urls = "index.php?m=Member&a=add_member&foot_id=1&openid=".$j->openid;
    	header("location:".$urls);
	}else if($fcate_id == 'thr_4'){ //我要激活
        $urls = "index.php?m=Member&a=activation_member&foot_id=1&openid=".$j->openid;
        header("location:".$urls);
	}else if($fcate_id == 'thr_5'){ //商家入口
        $urls = "index.php?m=Shop&a=m_login&foot_id=1&openid=".$j->openid;
        header("location:".$urls);
    }else if($fcate_id == 'thr_10') { //商家入口
        $urls = "index.php?m=Member&a=login&foot_id=1&openid=" . $j->openid;
        header("location:" . $urls);
    }else if($fcate_id == 'thr_6'){ //更多

    }else if($fcate_id == 'thr_7'){ //关于小六
        $urls = "index.php?m=Index&a=menu&type=".$fcate_id."&openid=".$j->openid;
        header("location:".$urls);
    }else if($fcate_id == 'thr_8'){ //联系我们
        $urls = "index.php?m=Index&a=menu&type=".$fcate_id."&openid=".$j->openid;
        header("location:".$urls);
    }else if($fcate_id == 'thr_9'){ //使用帮助
        $urls = "index.php?m=Index&a=menu&type=".$fcate_id."&openid=".$j->openid;
        header("location:".$urls);
    }
	
	exit;
}else{
    echo "NO CODE";
}
?>