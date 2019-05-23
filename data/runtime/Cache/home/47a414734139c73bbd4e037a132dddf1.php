<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0"/>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<title>支付订单</title>
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



<link href="../Style/shop.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../Style/js/jquery.js" charset="utf-8"></script>
<script type="text/javascript" src="../Style/js/ecmall.js" charset="utf-8"></script>
<script type="text/javascript" src="../Style/js/touchslider.dev.js" charset="utf-8"></script>
<script type="text/javascript">
//<!CDATA[
var SITE_URL = __ROOT__;
var PRICE_FORMAT = '¥%s';

$(function(){
    var span = $("#child_nav");
    span.hover(function(){
        $("#float_layer:not(:animated)").show();
    }, function(){
        $("#float_layer").hide();
    });
});
//]]>
</script>
    <style>
        .lxt_choose{ display:none !important;}
        .lxt_choose + label{ display:block;  width:18px; height:18px; background: url(__STATIC__/css/img/choose0.png) no-repeat center; background-size:100%;}
        .lxt_choose1:checked + label {width:18px; height:18px; background: url(__STATIC__/css/img/choose1.png) no-repeat center; background-size:100%;}
        #content .enter,#content .login_btn{ background-image: none; line-height: 30px;}
        .make_sure p{width: 80%; margin:0 auto !important;}
        .make_sure p a{ background-color: #40C780 !important;}
        .lxt-pay{ margin-top: 10px;}
    </style>
</head>

<body>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />
    <title><?php echo C('pin_site_name');?></title>
    <script type="text/javascript" src="../Style/static/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="../Style/static/js/jquery.bxslider.js"></script>
    <script type="text/javascript" src="../Style/static/js/nav4.js"></script>
    <link href="../Style/static/css/reset.css" rel="stylesheet" type="text/css">
    <link href="../Style/static/css/header.css" rel="stylesheet" type="text/css">
    <link href="../Style/static/css/menu.css" rel="stylesheet" type="text/css">
    <link href="../Style/static/css/jquery.bxslider.css" rel="stylesheet" type="text/css">

    <script>
        $(function(){
            var mySwiper = new Swiper ('.swiper-container', {
                autoplay: 5000,
                pagination: '.swiper-pagination'
            })
        });
    </script>
</head>
<div id="content">
    <form action="<?php echo U('Order/end');?>" method="POST" id="goto_pay">
        <input type="hidden" name="orderid" value="<?php echo ($orderid); ?>" />
        <input type="hidden" name="dingdanhao" value="<?php echo ($dingdanhao); ?>" />

        <div class="step_main">
            <div class="clue_on"><p>您的订单已成功生成，选择您想用的支付方式进行支付</p></div>
            <div class="order_information">
                    <p>订单号：<span><?php echo ($dingdanhao); ?></span></p>订单总价：<span>¥<?php echo ($order_sumPrice); ?></span>
            </div>
		    <input type="hidden" name="orderId" value="<?php echo ($dingdanhao); ?>">
		    <input type="hidden" name="total" value="<?php echo ($order_sumPrice); ?>">
            <input type="hidden" name="tel" value="<?php echo ($tel); ?>">
            <input type="hidden" name="type" value="<?php echo ($type); ?>">
            <div class="buy">

                <div class="container">
                        <div class="row lxt-pay">
                            <div class="col-xs-2 text-center"><img style="height: 30px;" src="__STATIC__/css/img/m2.png" class="lxt-pay-img"></span></div>
                            <div class="col-xs-8 "><p>微信支付</p></div>
                            <div class="col-xs-2 text-center">
                                <input type="radio" name="payment_id" value="2" id="checkbox1" class="lxt_choose lxt_choose1 lxt_chooseBox" checked="checked" />
                                <label class="lxt-all2" for="checkbox1"></label>
                            </div>
                        </div>



                        <div class="row lxt-pay">
                            <div class="col-xs-2 text-center"><img style="height: 30px;" src="__STATIC__/css/img/m1.png" class="lxt-pay-img"></span></div>
                            <div class="col-xs-8 "><p>余额支付</p></div>
                            <div class="col-xs-2 text-center">
                                <input type="radio" name="payment_id" value="1" id="checkbox4" class="lxt_choose lxt_choose1 lxt_chooseBox" />
                                <label class="lxt-all2" for="checkbox4"></label>
                            </div>
                        </div>
<!--
                        <?php if($type == 4 ): ?><div class="row lxt-pay">
                                <div class="col-xs-2 text-center"><img style="height: 30px;" src="__STATIC__/css/img/m3.png" class="lxt-pay-img"></span></div>
                                <div class="col-xs-8 "><p>货到付款</p></div>
                                <div class="col-xs-2 text-center">
                                    <input type="radio" name="payment_id" value="3" id="checkbox2" class="lxt_choose lxt_choose1 lxt_chooseBox" />
                                    <label class="lxt-all2" for="checkbox2"></label>
                                </div>
                            </div><?php endif; ?>-->
                </div>
        </div>
        <div class="make_sure">
                <p>
                    <a href="javascript:$('#goto_pay').submit();" id="submut" class="btn enter" style="background-color:#C4374A;color:#FFF;">确认支付</a>
                </p>
            </div>
            <!--<div class="remark">
                商品将于5工作日内送达。<a href="#">配送范围请查看</a>。<br />
                您可以在 <a href="#">我的订单</a>  中查看或取消您的订单，由于系统需进行订单预处理，您可能不会立刻查询到刚提交的订单。<br />
                如果您现在不方便支付，可以随后到 <a href="#">我的订单</a>  完成支付，我们会在48小时内为您保留未支付的订单。
            </div>-->
            <div class="clear"></div>
        </div>
    </form>
</div>
<div style="height:45px;"></div>

<script>
    $('#checkbox4').click(function(){
        var monery = "<?php echo ($u_mony); ?>";
        var total =  "<?php echo ($order_sumPrice); ?>";
        if(parseInt(monery) < parseInt(total)){
            layer.msg('余额不足');
            return false;
        }
    })
</script>


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
</html>