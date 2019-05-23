<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
 <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />
<title>详情</title>

<link href="../Style/static/css/reset.css" rel="stylesheet" type="text/css">
<link href="../Style/static/css/header.css" rel="stylesheet" type="text/css">
<link href="../Style/static/css/menu.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../Style/static/js/nav4.js"></script>
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
		body{ height: 100%;}
		.promptbox{ margin-top:10%;}
		h1{ font-weight:normal; color:#000; text-align:center; margin-top:5%;}
		.prompt{ width:95px; height:93px; display:block; margin:0 auto;}
         .xiangqing,.xq_main{width:100%;}
        .bg_colorxq a{color: #40C780}
        .xq_main{height: 100%;}
        .lxt-a1{padding:3px 5px; margin-top:2px; display:inline-block;}
        .btn-default{background:#40C780;color: #e4ecf7 ;}
        .btn_a{ padding: 5px 5px; font-size: 12px;background-color:#40C780!important; color: #fff !important;}

</style>
<style>
    .shopping_box{ height: 90px;}
    .wxh_title{ height: 18px; margin-bottom: 28px; overflow: hidden;text-overflow:ellipsis; white-space: nowrap;}
</style>

</head>

<body style="background:#eeeeee;">
        <div class="xiangqing bg_colorxq">
        	<p><img src="../Style/static/images/icon41.png" class="fl"/>
        	订单状态：
        		<?php if($order["status"] == 1): ?><a href="<?php echo U('Order/pay',array('orderId'=>$order['orderId'],'order_sumPrice'=>$order['order_sumPrice']));?>" style="margin-right:10px;">去付款</a>
                <?php elseif($order["status"] == 2): ?>
                <a href="javascript:;" style="margin-right:10px;">待配送</a>
                <?php elseif($order["status"] == 3): ?>
                <a href="javascript:;" style="margin-right:10px;">待确认收货</a>
                <?php elseif($order["status"] == 4): ?>
                <a href="javascript:;" style="margin-right:10px;">已完成</a>
                <?php elseif($val["status"] == 6): ?>
                <a href="javascript:;" style="margin-right:10px;">后台下单</a>
                <?php else: ?>
                <a href="javascript:;" style="margin-right:10px;">关闭</a><?php endif; ?>
        	</p>
        </div>
        <div class="xiangqing bg_colorxq1">
        	<p><img src="../Style/static/images/icon42.png" class="fl"/>收货人：<?php echo ($order["userName"]); ?><span class="fr"><?php echo ($order["mobile"]); ?></span></p>
            <p class="left_pdis"><?php echo ($order["address"]); ?></p>
        </div>
        <div class="xq_main">
            <?php if(is_array($arr)): $k = 0; $__LIST__ = $arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($k % 2 );++$k;?><p class="titile_p">商家：<?php echo ($val["0"]["ctitle"]); ?></p>

                <?php if(is_array($val)): $i = 0; $__LIST__ = $val;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$info): $mod = ($i % 2 );++$i;?><div class="shopping_box">
                        <a href="#">
                            <img src="<?php echo attach(get_thumb($info['img'], '_s'), 'item');?>"/>

                            <p class="shopping_p wxh_title"><?php echo ($info["title"]); ?></p>

                            <p class="shopping_p">
                                <i>￥<?php echo ($info["price"]); ?></i>
                                <span>x <?php echo ($info["quantity"]); ?></span></p>
                        </a>


                    </div><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
            <?php if($order['freetype'] != '' ): ?><div class="bottom_p">
                    <p class="pull-right">
                    <span>
                        配送方式：
                              <?php if($order['freetype'] == 0): ?>自提 <?php if($order['supportmetho'] == '3'){ echo '(货到付款)';}?>
                                  <?php elseif($order['freetype'] == 1): ?>
                                  配送 <?php if($order['supportmetho'] == '3'){ echo '(货到付款)';}?>
                                  <?php else: ?>
                                  配送 <?php if($order['supportmetho'] == '3'){ echo'(货到付款)';} endif; ?>
                    </span>
                    </p>
                </div><?php endif; ?>



            <div class="bottom_p">
                <p class="pull-right">
                    <span>
                        共 <?php echo ($num); ?> 件商品，合计：
                     <span>￥<?php echo ($order["order_sumPrice"]); ?></span>
                    </span>
                </p>
            </div>
            <style>
                .bottom_p{ overflow: hidden;height: 32px;margin:12px auto;}
                .bottom_p>p{ overflow:hidden ;}
                .bottom_p>p>span span{ color:#DE2C2B;}
            </style>
        </div>
        <!-- <div class="xq_bottom">
        	<span>共<i> 1 </i> 件，总金额<i> ￥258.00</i></span>
            <input type="button" value="提交订单" class="bottom_button fr"/>
        </div> -->
        <div style="height:80px;"></div>
        </body>
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