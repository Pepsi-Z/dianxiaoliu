<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />
    <title>注册</title>
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
<body class="zxy-bac">
<style>
    .lxtMeX_hq {
        font-size: 1.2rem;
    }
    .c_btn{ width:100%; background:#6ce;color:#fff; height:35px; border:none; margin-top:30px; border-radius:5px;}
</style>

<!---头结束--->
<!----主题开始---->

<div class="container">
    <div class="row">
        <div class="col-xs-12 zxy-Logon">
            <form id="form2" action="<?php echo U('Member/edit_password');?>" method="post">
                <div class="form-group">
                    <label >手机号：</label>
                    <div class="input-group col-xs-12">
                        <span class="input-group-addon"></span>
                        <span></span>
                        <input id="t_tel" name="tel" type="text"  maxlength="11" class="form-control"  placeholder="请输入手机号码" maxlength="11" />

                    </div>
                </div>

                <div class="form-group Verification-z">
                    <label>验证码：</label>
                    <div class="input-group col-xs-12">
                        <span class="input-group-addon"></span>
                        <span></span>
                        <input type="text" class="form-control" name="vcode" id="vcode" onkeyup="check_yzm()" autocomplete="on" maxlength="6"  placeholder="请输入6位短信验证码" style="width:60%;font-size: 12px;"/>
                        <input style="float: right; height:35px; background:#eee; border-left:1px solid #ccc; border-radius:0 5px 5px 0;text-align:center;width:38%; font-size:12px;" class="yy-SecurityCode lxtMeX_hq yy-SecurityCode btn btn-default" id="getcodebtn" onclick="getRegCode()" class="lxtMeX_hq yy-SecurityCode" value="获取验证码"/>
                        <input id="phone_info" type="hidden" value="<?php echo ($msg); ?>">
                    </div>
                </div>
            </form>
        <div class=" col-xs-12 text-left"><input class="c_btn" id="submit1" type="button" value="下一步"/></div>

        </div>
    </div>
</div>
<!----主题结束---->
</body>
<script>
    $(function(){

        $("#submit1").click(function(e){
            var t_tel  = $('#t_tel').val();
            if(t_tel == ''){
                layer.msg('电话不能为空');
                return false;
            }
            //电话验证
            if(!(/^0?1[3|4|5|7|8][0-9]\d{8}$/.test($('#t_tel').val()))){
                layer.msg('电话号输入不正确');
                return false;
            }
            if($('#phone_info').val() !== '填写正确'){
                layer.msg('验证码输入不正确');
                return false;
            }
            if(t_tel){
                $.ajax({
                    type: "post",
                    url: "<?php echo U('Member/pass_check_tel');?>",
                    data: {tel: t_tel},
                    dataType: "html",
                    success: function (data) {
                        if(data  == 1){
                           $("#form2").submit();
                        }else{
                            layer.msg('电话号没有被注册');
                            return false;

                        }

                    }

                });
            }












        });

    })
</script>
<script>
    function getRegCode(){
        var phonenum = $('#t_tel').val();
        if(phonenum == null || phonenum == ""){
            layer.msg('请输入注册手机号码');
            $('#phone_info').val('请输入注册手机号码');
        }else if(phonenum.length != 11){
            layer.msg('请输入正确的手机号码');
            $('#phone_info').val('请输入注册手机号码');
        }else{
            for(i=0;i<phonenum.length;i++){
                if(phonenum.charAt(i)<'0' || phonenum.charAt(i)>'9')
                {
                    layer.msg('只能包含数字');
                    return false;
                }
            }
            $('#phone_info').html("");
            $.ajax({
                type: "post",
                url:"<?php echo U('Member/getRegCodes');?>",
                data:{"mobile":phonenum},
                dataType: "text",
                success: function (data) {
                    if($.trim(data) == "100"){
                        test.init();
                        layer.msg('短信已发出，请注意查收短信');
                    }else{
                        layer.msg(data);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown+textStatus+errorThrown);
                }
            });

        }
    }

    var test = {
        count:60,
        start:function(){
            //console.log(this.count);

            if(this.count > 0){
                $('#getcodebtn').val(--this.count);
                $('#getcodebtn').attr('disabled','disabled');
                $('#getcodebtn').css('background-color','#ccc');
                var _this = this;
                setTimeout(function(){
                    _this.start();
                },1000);
            }else{
                $('#phone_info').html("");
                $('#getcodebtn').val('获取验证码');
                $('#getcodebtn').removeAttr('disabled');
                $('#getcodebtn').css('background-color','#f30');
                this.count = 60;
            }
        },
        init:function(node){
            this.start();
        }
    };

    function next(){
        //var name = $('#name').val();

        var phone = $('#J_tel').val();
        var vcode = $('#vcode').val();
        $('#phone_info').html("");
        if(phone == null || phone == ""){
            layer.msg('请输入注册手机号码');
            $('#phone_info').val('请输入注册手机号码');
            return;
        }else if(vcode == null || vcode == ""){
            layer.msg('请输入验证码');
            $('#phone_info').val('请输入验证码');
            return;
        }else{
            $.ajax({
                type: "post",
                url:"<?php echo U('Member/checkRegCode');?>",
                data:{"mobile":phone,"vcode":vcode},
                dataType: "text",
                success: function (data) {
                    if($.trim(data) == "100"){
                        $('#yy-bntBox').submit();
                    }else{
                        $('#phone_info').html(data);
                        $('#phone_info').val(data);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown+textStatus+errorThrown);
                }
            });
        }
    }

    function check_yzm(){
        var strcode = $('#vcode').val();
        var tel = $('#t_tel').val();
        //alert(strcode);
        //alert(tel);
        if(strcode.length == 6){
            $.post("<?php echo U('Member/ajax_getcode');?>",{tel:tel,code:strcode},function(data){
                layer.msg(data);

                $('#phone_info').val(data);
            })

        }else{
            //layer.msg('请正确填写');
            $('#phone_info').val('请正确填写');
        }
    }

    function checkuid(){
        var strtel = $('#J_tel').val();
        var struname = $('#J_name').val();
        if(strtel.length == 11){
            $.post("<?php echo U('Member/ajax_hquid');?>",{name:struname,tel:strtel},function(data){
                $('#memid').val(data);
            })
        }
    }


</script>
</html>