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
<link href="../Style/static/css/menu.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../Style/static/js/nav4.js"></script>
<script type="text/javascript" src="../Style/js/jquery.js"></script>
<link rel="stylesheet" type="text/css" href="__STATIC__/css/home/sweet-alert.css">
    <script src="__STATIC__/js/sweet-alert.js"></script>
<style type="text/css">
.shopping_box img{width:60px; display:inline-block; height:70px;margin-left:10px;}
</style>
<style>
		body{ height: 100%;}
		.promptbox{ margin-top:10%;}
		h1{ font-weight:normal; text-align:center; margin-top:5%;}
		.prompt{ width:95px; height:93px; display:block; margin:0 auto;}
	</style>
</head>
<body>
<?php if(empty($score_list)): ?><div class="promptbox">
	<img src="../Style/static/images/cw_03.png" class="prompt">
</div>
<h1>您还没有积分订单！</h1><?php endif; ?>
<?php if(is_array($score_list)): $i = 0; $__LIST__ = $score_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><div class="quanbu_div">
        	<p class="diandan_p"><span>订单号：<?php echo ($val["order_sn"]); ?></span></p>
            <div class="shopping_box border_bottom">
            	<a href="#">
                    <img src="<?php echo get_img($val[item][img],'score_item');?>" class="right_disimg1"/>
                    <p class="shopping_p line_height3"><?php echo ($val["item_name"]); ?><span>x <?php echo ($val["item_num"]); ?></span></p>
                </a>
            </div>
            <div class="diandan_bottomdiv">
            	<span class="fl">总积分：<i>￥<?php echo ($val["order_score"]); ?></i></span>
                <div class="button_div fr">
                      <?php if($val["status"] == 1): ?><a href="javascript:;" style="margin-right:10px;">待发货</a>
                	<?php elseif($val["status"] == 2): ?>
                	<a class="confirm " data="<?php echo ($val["id"]); ?>" href="javascript:;" style="margin-right:10px;">确认收货</a>
                	<?php elseif($val["status"] == 3): ?>
                	
                	<a href="javascript:;" style="margin-right:10px;">完成</a><?php endif; ?>
                	<!-- <input type="button" value="编辑"/> -->
                    <a href="<?php echo U('Jifen/order_xx',array('id'=>$val[id]));?>"><input type="button" value="详情" class="active"/></a>
                </div>
            </div>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>     
<script>
$(function(){
	$('.confirm').click(function(){
		var id = $(this).attr('data');
		if(window.confirm("您确定已收货吗?")){
			$.post("<?php echo U('Jifen/confirmOrder');?>",{ id:id },function(data){
				//alert(data)
				if(data == 1){
					swal('确认成功');
					location.href="<?php echo u('Jifen/order');?>"
				}else{
					swal('确认失败')
				}
			});
		}
		
	});
})
</script>      
<div style="height:55px;"></div>       
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