<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head runat="server"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />
    <link href="__STATIC__/css/home/reset.css" rel="stylesheet" type="text/css">
    <link href="__STATIC__/css/home/head.css" rel="stylesheet" type="text/css">
    <link href="__STATIC__/css/home/lxt_main.css" rel="stylesheet" type="text/css">
    <link href="__STATIC__/js/swiper3.1.0.min.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="__STATIC__/js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="__STATIC__/js/swiper3.1.0.jquery.min.js"></script>
    <script src="__STATIC__/js/layer_main.js"></script>
    <script src="__STATIC__/js/layer.js"></script>
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
        .wxh_icon{ margin-top: 22px;margin-right:5px; color: #666 !important;}
        .tjsldzleft a{ overflow: hidden;}

        .tjsldz_top {
            padding: 20px 15px;
            background: #337AB7 none repeat scroll 0% 0%;
            overflow: hidden;
            !important;
        }
    </style>
<title>个人资料</title>
    <link href="../Style/static/css/reset.css" rel="stylesheet" type="text/css">
    <link href="../Style/static/css/header.css" rel="stylesheet" type="text/css">
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" ></script>
<script type="text/javascript">

        wx.config({
            debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: '<?php echo APPID_INDEX; ?>', // 必填，公众号的唯一标识
            timestamp: '<?php echo ($wx_data["timestamp"]); ?>', // 必填，生成签名的时间戳
            nonceStr: '<?php echo ($wx_data["nonceStr"]); ?>', // 必填，生成签名的随机串
            signature: '<?php echo ($wx_data["signature"]); ?>', // 必填，签名，见附录1
            jsApiList: [
                'checkJsApi',
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
                'onMenuShareQQ',
                'onMenuShareWeibo',
                'hideMenuItems',
                'showMenuItems',
                'hideAllNonBaseMenuItem',
                'showAllNonBaseMenuItem',
                'translateVoice',
                'startRecord',
                'stopRecord',
                'onRecordEnd',
                'playVoice',
                'pauseVoice',
                'stopVoice',
                'uploadVoice',
                'downloadVoice',
                'chooseImage',
                'previewImage',
                'uploadImage',
                'downloadImage',
                'getNetworkType',
                'openLocation',
                'getLocation',
                'hideOptionMenu',
                'showOptionMenu',
                'closeWindow',
                'scanQRCode',
                'chooseWXPay',
                'openProductSpecificView',
                'addCard',
                'chooseCard',
                'openCard'
            ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        });

        var upload = function(img, num, id) {
            if (img.length < num + 1) {
                $('#' + id + ' img').attr("src", "../Style/static/images/icon17.png");
                return;
            }
            var file = img[num];

            setTimeout(function() {
                wx.uploadImage({
                    localId: file,
                    isShowProgressTips: 1, // 默认为1，显示进度提示
                    success: function(res) {
            		
                       upload_imgurl(res.serverId); 
                    },
                    fail: function(res) {
                        alert(JSON.stringify(res));
                    }
                });
            }, 200)
        }

        wx.ready(function() {
            var images = {
                localId: [],
                serverId: []
            };
            document.querySelector('#tx').onclick = function() {
                wx.chooseImage({
                    success: function(res) {
                        var imgs = res.localIds;
                        
                        upload(imgs, 0, 'tx');
                    }
                });
            };

        });
        
        function upload_imgurl(serverId){
            var url = "<?php echo U('User/download_img');?>&serverId="+serverId;
            $.post(url,null,function(data){
                    if(data.state == 0){
                         alert("上传失败");
                    }else{
                        $("#person_img").attr("src",data.imgurl);
                    }
            },'json');	
        }
</script>
</head>
<body style=" background:#eeeeee;">
<form id="form2" action="<?php echo U('User/wanshan',array('status'=>1));?>" method="post">
<div class="tjsldz_top">
    <div class="tjsldzleft">
        <span class="pull-left wxh_icon">头像：</span>
        <a href="javascript:void();" id="tx" class="pull-left">
            <?php if($user_info[cover] == ''): ?><img class="" src="__STATIC__/css/img/yy_mine_head.jpg" alt="..." id="person_img">
                <?php else: ?>
                <img src="<?php echo ($user_info["cover"]); ?>" id="person_img" /><?php endif; ?>
        </a>
        <input name="tx_input" type="hidden" id="tx_input"/>
    </div>
</div>
        
            <div class="ws_main">
                <ul>
                    <li><span>姓 名：</span><input id="username" name="username" value="<?php echo ($user_info["name"]); ?>" type="text" /><a href="javascript:;"></a></li>
                    <li><span>性 别：</span><label><input type="radio" name="gender" value="1" <?php if($user_info["gender"] != '0'): ?>checked<?php endif; ?>> 男</label><label><input type="radio" name="gender" value="0" <?php if($user_info["gender"] == '0'): ?>checked<?php endif; ?>> 女</label></li>
                    <li><span>手机号：</span><input id="t_tel" name="tel" value="<?php echo ($user_info["tel"]); ?>"  type="text" /><a href="javascript:;"></a></li>
<!--
                    <li><span>邮 箱：</span><input id="t_email" name="email" value="<?php echo ($user_info["email"]); ?>" type="text" /><a href="javascript:;"></a></li>
-->
<!--
                    <li><span>职 业：</span><input id="occupation" name="occupation" value="<?php echo ($user_info["occupation"]); ?>" type="text" /><a href="javascript:;"></a></li>
-->

                </ul>
                <input type="hidden" id="t_id" name="id" value="<?php echo ($user_info["id"]); ?>">
                <div class="ws_btn">
                    <!--<a href="<?php echo U('User/index');?>"><button type="button">取消</button></a>-->
                    <button id="submit1" class="yy_address_btn" style="text-align:center;line-height:15px;width:80%;padding-top:15px;margin-top:45px;" type="button">确定</button>
                </div>
            </div>
            <?php if($user_info['openid'] != ''): ?><input type="hidden" name="openid" value="<?php echo ($user_info["openid"]); ?>">
                <?php else: ?>
                <input type="hidden" name="openid" value="<?php echo ($_GET['openid']); ?>"><?php endif; ?>

</form>

<div style="height:80px;"></div>
<?php $uid = $_SESSION['user']['id']; $list = M('card')->where("uid = ".intval($uid))->select(); $num = ''; foreach($list as $key => $val){ $num += $val['num']; } ?>
<footer>
    <ul class="nav nav-tabs nav-tabs-justified">
        <li>
            <a href="<?php echo U('Index/index',array('foot_id'=>1,'openid'=>$_GET['openid']));?>">
                <?php if($_SESSION['foot_id'] == 1 or $_SESSION['foot_id'] == ''): ?><img src="__STATIC__/css/img/footer0_active0.png"/>
                    <?php else: ?>
                    <img src="__STATIC__/css/img/footer0.png"/><?php endif; ?>
                <p  <?php if($_SESSION['foot_id'] == 1 or $_SESSION['foot_id'] == ''): ?>class="active_p"<?php endif; ?>>首页</p>
            </a>
        </li>
        <li>
            <a href="<?php echo U('Item/menu_merchant',array('foot_id'=>2,'type'=>5,'openid'=>$_GET['openid']));?>">
                <?php if($_SESSION['foot_id'] == 2): ?><img src="__STATIC__/css/img/footer1_active1.png"/>
                    <?php else: ?>
                    <img src="__STATIC__/css/img/footer1.png"/><?php endif; ?>
                <p  <?php if($_SESSION['foot_id'] == 2): ?>class="active_p"<?php endif; ?> >折扣</p>
            </a>
        </li>




        <li>
            <a href="<?php echo U('Shopcart/index',array('foot_id'=>6,'openid'=>$_GET['openid']));?>" >
                <?php if($_SESSION['foot_id'] == 6): ?><img src="__STATIC__/css/img/inder_footer1active.png" class="wxh_position_img" >

                    <div class="wxh_shopping_number">
                        <?php if($num == 0 ): ?>0
                            <?php else: ?>
                            <?php echo ($num); endif; ?>
                    </div>

                    <?php else: ?>

                    <img src="__STATIC__/css/img/inder_footer1.png" class="wxh_positionimg">
                    <div class="wxh_shopping_number">
                        <?php if($num == 0 ): ?>0
                            <?php else: ?>
                            <?php echo ($num); endif; ?>
                    </div><?php endif; ?>
                <p <?php if($_SESSION['foot_id'] == 6): ?>class="active_p"<?php endif; ?> >购物车</p>
            </a>
        </li>
        <style>
            .footer ul li a .wxh_position_img{ position: relative;}
            .wxh_shopping_number{ position: absolute; top:-1px; left: 52%; width: 18px; height: 18px; text-align: center; line-height: 18px; background: #66ccee;color: #fff; border-radius: 100px; font-size: 12px;}
        </style>


        <li>
            <a href="<?php echo U('Item/menu_merchant',array('foot_id'=>3,'type'=>6,'openid'=>$_GET['openid']));?>">
                <?php if($_SESSION['foot_id'] == 3): ?><img src="__STATIC__/css/img/footer2_active2.png"/>
                    <?php else: ?>
                    <img src="__STATIC__/css/img/footer2.png"/><?php endif; ?>
                <p  <?php if($_SESSION['foot_id'] == 3): ?>class="active_p"<?php endif; ?> >套餐</p>
            </a>
        </li>

        <!--  <li>
            <a href="<?php echo U('Shopcart/index',array('foot_id'=>6,'openid'=>$_GET['openid']));?>">
                <?php if($_SESSION['foot_id'] == 6): ?><img src="__STATIC__/css/img/footer3_active3.png"/>

                    <div class="wxh_shopping_number">
                        <?php if($num == 0 ): ?>0
                            <?php else: ?>
                            <?php echo ($num); endif; ?>
                    </div>
                    <?php else: ?>
                    <img src="__STATIC__/css/img/footer3.png"/>

                    <div class="wxh_shopping_number">
                        <?php if($num == 0 ): ?>0
                            <?php else: ?>
                            <?php echo ($num); endif; ?>
                    </div><?php endif; ?>
                <p  <?php if($_SESSION['foot_id'] == 6): ?>class="active_p"<?php endif; ?> >购物车</p>
            </a>
         </li>-->

      <!--  <li>
            <a href="<?php echo U('Item/menu_merchant',array('foot_id'=>4,'type'=>7,'openid'=>$_GET['openid']));?>">
                <?php if($_SESSION['foot_id'] == 4): ?><img src="__STATIC__/css/img/footer3_active3.png"/>
                    <?php else: ?>
                    <img src="__STATIC__/css/img/footer3.png"/><?php endif; ?>
                <p  <?php if($_SESSION['foot_id'] == 4): ?>class="active_p"<?php endif; ?> >商家</p>
            </a>
        </li>-->
        <li>
            <a href="<?php echo U('User/index',array('foot_id'=>5,'openid'=>$_GET['openid']));?>">
                <?php if($_SESSION['foot_id'] == 5): ?><img src="__STATIC__/css/img/footer4_active4.png"/>
                    <?php else: ?>
                    <img src="__STATIC__/css/img/footer4.png"/><?php endif; ?>
                <p  <?php if($_SESSION['foot_id'] == 5): ?>class="active_p"<?php endif; ?> >我的</p>
            </a>
        </li>
    </ul>
</footer>
</body>
<script>
    $(function(){

        $("#submit1").click(function(e){
            var username  = $('#username').val();
            if(username == ''){
                layer.msg('请输入姓名');
                return false;
            }
            var age  = $('#age').val();
          /*  if(age == ''){
                layer.msg('请输入年龄');
                return false;
            }*/
            var birthday  = $('#birthday').val();
           /* if(birthday == ''){
                layer.msg('请输入生日');
                return false;
            }*/

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

            var t_email  = $('#t_email').val();
        /*    if(t_email == ''){
                layer.msg('请输入邮箱');
                return false;
            }
            //邮箱验证
            if(!(/^([A-Za-z0-9\-_.+]+)@([A-Za-z0-9\-]+[.][A-Za-z0-9\-.]+)$/.test($('#t_email').val()))){
                layer.msg('邮箱输入不正确');
                return false;
            }
*/
            var t_id = $('#t_id').val();
            if(t_tel) {
                $.ajax({
                    type: "post",
                    url: "<?php echo U('User/check_tel');?>",
                    data: {id: t_id, tel: t_tel},
                    dataType: "html",
                    success: function (data) {
                        if (data == 0) {
                            layer.msg('电话号已经被注册');
                            return false;
                        } else {
                            $("#form2").submit();
                        }

                    }

                });
            }

        });

    })
</script>
</html>