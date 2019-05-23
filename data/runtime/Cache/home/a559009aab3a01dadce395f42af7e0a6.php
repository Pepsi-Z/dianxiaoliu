<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
 <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />
<title>领取奖励</title>
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



<link href="../Style/static/css/reset.css" rel="stylesheet" type="text/css">
<link href="../Style/static/css/header.css" rel="stylesheet" type="text/css">
<style>
    .xiangqing,.xq_main{width:100%;}
    .xq_main{min-height:150px;}
</style>
</head>
<body style="background:#eeeeee;">
        <div class="xiangqing bg_colorxq">
        	<p><img src="../Style/static/images/icon41.png" class="fl"/>
        	订单状态：
        		<?php if($score_info["status"] == 1): ?><a href="javascript:;" style="color:#40C780;margin-right:10px;">待发货</a>
                <?php elseif($score_info["status"] == 2): ?>
                <a href="javascript:;" style="color:#40C780;margin-right:10px;">待收货</a>
                <?php elseif($score_info["status"] == 3): ?>
                <a href="javascript:;" style="color:#40C780;margin-right:10px;">完成</a><?php endif; ?>
        	</p>
        </div>
        <div class="xiangqing bg_colorxq1">
        	<p><img src="../Style/static/images/icon42.png" class="fl"/>收货人：<?php echo ($score_info["uname"]); ?><span class="fr"><?php echo ($score_info["mobile"]); ?></span></p>
            <p class="left_pdis"><?php echo ($score_info["xx_address"]); ?></p>
        </div>
        <div class="xq_main">
        	<!--<p class="titile_p">店铺：<?php echo C('pin_site_name');?></p>-->
            <div class="shopping_box border_bottom">
            	<a href="#">
                    <img src="<?php echo get_img($score_info[img],'score_item');?>"/>
                    <p class="shopping_p line_height"><?php echo ($score_info["title"]); ?></p>
                    <p class="shopping_p"><i>￥<?php echo ($score_info["score"]); ?></i><span>x <?php echo ($score_info["item_num"]); ?></span></p>
                </a>
            </div>
            <!--<input type="text" placeholder="给卖家留言"  class="liuyan_button"/>-->
            <div class="bottom_p" style="border-top:none"><p class="titile_p1"><span>共 <?php echo ($score_info["item_num"]); ?> 件商品，合计：<span>￥<?php echo ($score_info["order_score"]); ?></span></span></p></div>
        </div>
        <!--<div class="xq_bottom">
        	<span>共<i> <?php echo ($score_info["item_num"]); ?> </i> 件，总金额<i> ￥<?php echo ($score_info["order_score"]); ?></i></span>
            <input type="button" value="提交订单" class="bottom_button fr"/>
        </div>-->
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
</html>