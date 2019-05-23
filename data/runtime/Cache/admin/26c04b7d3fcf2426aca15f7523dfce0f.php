<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />

<link href="__STATIC__/css/admin/header.css" rel="stylesheet" type="text/css">
<title>店小六平台管理中心</title>
<style>
body {
	background-color:#f6f8f9;
	background-image:none;
}
.login h1 img{ vertical-align:bottom;}
</style>
</head>

<body>
<div class="bg"><img src="__STATIC__/css/admin/images/beijing.jpg" /></div>
<div class="bg2"><img src="__STATIC__/css/admin/images/iconbg.png" /></div>
<form id="login_form" action="<?php echo U('index/login');?>" method="post" id="myform">
<div class="box">
		<div class="content">
        	<div class="top"><h2>店小六平台管理中心</h2></div>
            <div class="main">
            	<h1>登&nbsp;录</h1>
                <input name="username" type="text" placeholder="请输入用户名" >
                <input name="password" type="password"  placeholder="请输入密码" >
                <div class="yzm">
                	<input name="verify_code" type="text" placeholder="请输入验证码" >
                    <a href="#"><img src="<?php echo U('index/verify_code', array('t'=>time()));?>"  title="<?php echo (L("refresh_verify_code")); ?>" class="verify_img" id="verify"  style="cursor:pointer; height:35px; margin-left:5px;"></a>
                </div>
                
                <div class="inpcheck">
                	<div class="mainLeft">
                        <input name="remember" type="checkbox" value="1" class="remember-me"/>
                        <label for="save">记住我的登录信息</label>
                    </div>
                    <!--<div class="mainRight"><a href="#">忘记密码</a></div>-->
                    <div class="clear"></div>
                </div>
                <div class="denglu">
                    <input type="submit" style="display:none">
                    <a href="#" id="submit">登&nbsp;录</a>
                </div>
            </div>
            <!--<div class="last"><a href="Registration.html">商家注册</a></div>-->
		</div>
	</div>
</form>

<script language="javascript" type="text/javascript" src="__STATIC__/js/jquery/jquery.js"></script>
<script>
$(function(){
    if(self != top){
        top.location = self.location;
    }
    $('#submit').click(function(){
    	$('#login_form').submit();
    })
    $(".verify_img").click(function(){
        var timenow = new Date().getTime();
        $(this).attr("src","<?php echo U('index/verify_code');?>&"+timenow)
    });
});
</script>
<iframe name="upload" style="display:none"></iframe>
</body>
</html>