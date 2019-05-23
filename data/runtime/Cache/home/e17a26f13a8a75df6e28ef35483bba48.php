<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />
    <title>登录</title>
    <meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />

<link href="__STATIC__/manage/css/bootstrap.min.css" rel="stylesheet">
<link href="__STATIC__/manage/css/common.css" rel="stylesheet" type="text/css">
<link href="__STATIC__/manage/css/main.css" rel="stylesheet" type="text/css">
<script src="__STATIC__/manage/js/jquery-1.9.1.min.js"></script>
<script src="__STATIC__/manage/js/bootstrap.min.js"></script>
<!--layer-->
<!--<script src="http://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script>-->
<script src="__STATIC__/js/layer_main.js"></script>
<script src="__STATIC__/js/layer.js"></script>


    <style>
    	.lxt_inputtext label{ display:none;}
    </style>
</head>
<body class="lxt-color">
<section class="section_topdis">
	<div class="container">
    	<div class="row">
        	<div class="col-xs-12 lxt_loginimg">
            	<img src="__STATIC__/manage/img/logo.png"/>
            </div>
            <form>
                <div class="col-xs-12 lxtlogin_input">
                	<div class="lxt_bgwhitecolor  lxt_inputtext">
                        <div>
                            <span class="pull-left lxt_tel"></span>
                            <span class="pull-left lxt_333color">手&nbsp机&nbsp;号：</span>
                            <input  id="tel" type="text"     placeholder="请输入手机号码" maxlength="11" />

                        </div>
                        <div class="lxt_borderbottom">
                            <span class="pull-left lxt_password"></span>
                            <span class="pull-left lxt_333color">密&nbsp;&nbsp;&nbsp;&nbsp;码：</span>
                            <input id="pass" type="password"   placeholder="请输入6-18位字符"  minlength="6" maxlength="18"/>
                        </div>
                     </div>
                    <p class="pull-right" style="margin:10px 0 30px 0;"><a href="<?php echo U('Shop/password');?>">忘记密码？</a></p>
                        <input onclick="hy_submit()" type="button" value="登&nbsp;录" class="btn btn-default btn-block lxt_bggreencolor"/>


                        </div>
                </div>
            </form>

        </div>
    </div>
</section>

<script>
    //梁浩修改
    function hy_submit(){

        var tel = $('#tel').val();
        var pass = $('#pass').val();
        if(tel == ''){
            layer.msg('请输入电话号！');
        }else if(pass == ''){
            layer.msg('密码不能为空！');
        }else{
           $.post("<?php echo U('Shop/login');?>",{tel:tel,pass:pass},function(data){
                if(data.status == 1){
                    layer.msg('登录成功！');
                        window.location.replace("<?php echo U('Shop/merchant_order');?>");
                } else {
                    layer.msg('电话号或密码输入有误！');
                }
            },'json')
        }
    }

</script>

</body>
</html>