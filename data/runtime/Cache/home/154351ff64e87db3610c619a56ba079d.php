<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />
    <title>我要办卡</title>
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



    <style>
        /*.zxy-Logon form .input-group span.yam{background:url("__STATIC__/css/img/zxy-suo.png") no-repeat scroll center center / 18px 22px}*/
        .zxy-Logon form .input-group span{background:url("__STATIC__/css/img/zxy-suo.png") no-repeat scroll center center / 18px 22px}
        /*.zxy-Logon form .form-group:nth-child(4) .input-group span:nth-child(1){background:url("__STATIC__/css/img/zxy-shouji.png") no-repeat scroll center center / 18px 22px}
*/
        .zxy-Logon form .form-group:nth-child(2) .input-group span:nth-child(1){background:url("__STATIC__/css/img/zxy-shouji.png") no-repeat scroll center center / 18px 22px;}
        .c_mobile{background:url("__STATIC__/css/img/zxy-shouji.png") no-repeat scroll center center / 18px 22px!important;}
        .c_name{background:url("__STATIC__/css/img/zxy-name.png") no-repeat scroll center center / 18px 22px!important;}

        .c_suo{background:url("__STATIC__/css/img/zxy-suo.png") no-repeat scroll center center / 18px 22px!important;}
        .J_loading{background:url("__STATIC__/css/img/jz.jpg") no-repeat 0 center;background-color: white;}
    </style>

</head>
<body >
<div class="J_loading"  style="width: 100%;height: 100%;display: inline-block;position: fixed;top: 0;z-index: 9999;"></div>


<section class="margin-z">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 zxy-Logon">
             <form id="form2" action="<?php echo U('Member/add_member');?>" method="post">
                 <div class="form-group">

                     <div class="input-group col-xs-12">
                         <span class="input-group-addon c_name"></span>
                         <span></span>
                         <input id="username" name="name" type="text"  class="form-control"  placeholder="请输入用户名"  />
                     </div>
                 </div>
                    <div class="form-group">
                        <div class="input-group col-xs-12">
                            <span class="input-group-addon yam  c_mobile"></span>
                            <span></span>
                            <input id="t_tel" name="tel" type="text"  maxlength="11" class="form-control"  placeholder="请输入手机号码" maxlength="11" />
                        </div>
                    </div>

                 <div class="form-group Verification-z">
                     <div class="input-group col-xs-12">
                         <span class="input-group-addon"></span>
                         <span></span>
                         <input type="text" class="form-control"  id="vcode" onkeyup="check_yzm()" autocomplete="on" maxlength="6"  placeholder="请输入6位短信验证码" style="width:60%;font-size: 12px;"/>
                         <input type="button" style="float: right; height:35px; background:#eee; border-left:1px solid #ccc; border-radius:0 5px 5px 0;text-align:center;width:38%;" class="yy-SecurityCode lxtMeX_hq yy-SecurityCode btn btn-default" id="getcodebtn" onclick="getRegCode()" class="lxtMeX_hq yy-SecurityCode" value="获取验证码" />
                         <input id="phone_info" type="hidden" value="<?php echo ($msg); ?>">
                     </div>
                 </div>



                    <div class="form-group">
                        <div class="input-group col-xs-12">
                            <span class="input-group-addon "></span>
                            <span></span>
                            <input type="password" id="t_password" name="password" class="form-control" placeholder="请输入6-18位字符"  maxlength="18">
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="checkbox">
                                <label>
                                    <input id="role" type="checkbox" checked="checked" >查看并同意
                                </label>
                                <a href="<?php echo U('Member/role');?>">《店小六条款》</a>
                            </div>
                        </div>
                    </div>


                </form>
                   <div class=" col-xs-12 zxy-payment margin-z"><a id="submit1" href="javascript:;" class="btn btn-default">注册</a></div>
            </div>
        </div>
    </div>
</section>
</body>
<script>
    $(function(){

        $("#field").change(function(){
            var id = $(this).val();
            $('#money').val('');
            $('#price').html('')
            $('#gname').html('')
            $.ajax({
                type: "post",
                url: "<?php echo U('Member/take_card');?>",
                data: {id: id},
                dataType: "json",
                success: function (data) {
                    $('#money').val(data.money);
                    $('#price').html(data.money)
                    $('#gname').val(data.gname);
                }

            });
        });

    })
</script>
<script>
    $(function(){

        $("#submit1").click(function(e){
            var t_tel  = $('#t_tel').val();
            var username  = $('#username').val();
            var role = $('#role').is(':checked');

            if(username == ''){
                layer.msg('用户名不能为空');
                return false;
            }

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

            if($('#t_password').val() == ''){
                layer.msg('密码不能为空');
                return false;
            };

            if($('#t_repassword').val() == ''){
                layer.msg('确认密码不能为空');
                return false;
            };
            if(role == false){
                layer.msg('请勾选店小六条款');
                return false;
            }
            if(t_tel){
                $.ajax({
                    type: "post",
                    url: "<?php echo U('Member/check_tel');?>",
                    data: {tel: t_tel},
                    dataType: "html",
                    success: function (data) {
                        if(data == 0){
                            layer.msg('电话号已经被注册');
                            return false;
                        }else{
                            $("#form2").submit();
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
                url:"<?php echo U('Member/getRegCode');?>",
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
        //alert(strcode)
        var tel = $('#t_tel').val();
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

    //定时 淡入淡出 加载数据
    $(document).ready(function(){
        setTimeout(function(){ $('.J_loading').fadeOut("slow");},500)
    })
</script>

</html>