<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />
    <title>登录</title>
    <meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />

<link href="__STATIC__/css/home/bootstrap.min.css" rel="stylesheet">
<link href="__STATIC__/css/home/main.css" rel="stylesheet" type="text/css">
<link href="__STATIC__/css/home/common.css" rel="stylesheet" type="text/css">
<link href="__STATIC__/css/home/swiper.min.css" rel="stylesheet" type="text/css">
<script src="__STATIC__/js/jquery-1.9.1.min.js"></script>
<script src="__STATIC__/js/bootstrap.min.js"></script>
<script src="__STATIC__/js/swiper.min.js"></script>
<script src="__STATIC__/js/indexTab/woco.accordion.min.js"></script>
<script src="__STATIC__/js/Untitled-2.js"></script>






<!--<script src="__STATIC__/js/layer_main.js"></script>-->
<script src="__STATIC__/js/layer.js"></script>
<script src="__STATIC__/js/jquery/jquery.cookie.js"></script>
<script src="static/js/jquery.raty-0.5/js/jquery.raty.js"></script>



</head>
<body class="wxh_bggreycolor">

<!---头结束--->
<!----主题开始---->
<div class="container">
    <div class="row">
        <div class="col-xs-12 zxy-Logon">
            <form>
                <div class="form-group">
                    <label>手机号：</label>
                    <div class="input-group col-xs-12">
                        <span class="input-group-addon"></span>
                        <span></span>

                        <input  id="tel" type="text"  class="form-control"    placeholder="请输入手机号码" maxlength="11" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="text">密码</label>
                    <div class="input-group col-xs-12">
                        <span class="input-group-addon"></span>
                        <span></span>

                        <input id="pass" type="password" class="form-control"  placeholder="请输入6-18位字符"  minlength="8" maxlength="18"/>
                    </div>
                </div>
            </form>
            <div class="col-xs-12 text-right" style="margin-bottom:30px">
                <a href="<?php echo U('Member/password');?>">忘记密码</a>
            </div>
            <div class="row">
                <div class=" col-xs-6 text-left">
                    <a  onclick="hy_submit()" href="javascript:;" class="btn btn-bule btn-block">登陆</a>
                </div>
                <div class=" col-xs-6  text-right">
                    <a href="<?php echo U('Member/add_member');?>"class="btn btn-bule btn-block">我要注册 </a>
                   <!-- <a href="<?php echo U('Member/activation_member');?>"class="btn btn-default">我要激活 </a>-->
                </div>
            </div>

        </div>
    </div>
</div>
<!----主题结束---->
</body>
<script>
    //梁浩修改
    function hy_submit(){
        var tel = $('#tel').val();
        var pass = $('#pass').val();
        var wanshan = "<?php echo ($_SESSION['user']['email']); ?>"
        if(tel == ''){
            layer.msg('请输入电话号！');
        }else if(pass == ''){
            layer.msg('密码不能为空！');
        }else{
            $.post("<?php echo U('Member/login');?>",{tel:tel,pass:pass},function(data){
                if(data.status == 1){
                    layer.msg('登录成功！');
                    //if(data.data.email){
                         window.location.replace("<?php echo U('Index/index');?>");
                    // }else{
                         //window.location.replace("<?php echo U('User/wanshan',array('status'=>1));?>");
                    //}
                } else {
                    layer.msg('电话号或密码输入有误！');
                }
            },'json')
        }
    }

</script>
</html>