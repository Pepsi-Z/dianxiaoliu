<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
 <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />
<title>全部订单</title>
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
<style>
		body{ height: 100%;}
		.promptbox{ margin-top:10%;}
		h1{ font-weight:normal; color:#000; text-align:center; margin-top:5%;}
		.prompt{ width:95px; height:93px; display:block; margin:0 auto;}
        .button_div input{width:auto; margin:0;}
        .button_div .active{margin-top:5px;padding:3px 5px;}
        .button_div{width:60%;}
        .lxt-a1{padding:3px 5px; margin-top:2px; display:inline-block;}
        .quanbu_div{overflow:hidden; height:auto;}
        .c_xq{ border:1px solid #40C780; padding:3px 5px; color:#40C780; border-radius: 5px; font-size: 12px; display:inline-block;margin:4px 10px 0 0;}
        .coupon_box .nav-tabs > li.active > a{border-radius:0;}
        .coupon_box .nav li:nth-child(3) a{ padding:10px 0;}
        .coupon_box .nav li:nth-child(3) span{ border-right:1px solid #EEE; display:block;}


        .coupon_box .nav-tabs > li > a{ padding:10px 0;}
        .coupon_box .nav li:nth-child(4) span{border-right:1px solid #EEE;display:block;}
        .wxh_top_div .col-xs-3{ padding: 0;}
</style>
</head>
<body>


<div class="coupon_box wxh_top_div" style="margin-bottom: 10px;">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation"
            <?php if($_GET['status'] == 1 or $_GET['status'] == ''): ?>class="active col-xs-3 text-center"
                <?php else: ?>
                class="col-xs-3 text-center"<?php endif; ?>>


            <a href="<?php echo U('Order/index',array('status'=>1));?>">
                <span>待付款</span>
            </a>
        </li>

        <li role="presentation"
        <?php if($_GET['status'] == 2 ): ?>class="active col-xs-3 text-center"
            <?php else: ?>
            class="col-xs-3 text-center"<?php endif; ?>>
        <a href="<?php echo U('Order/index',array('status'=>2));?>" >
            <span>处理中</span>
        </a>
        </li>


        <li role="presentation"
        <?php if($_GET['status'] == 3 ): ?>class="active col-xs-3 text-center"
            <?php else: ?>
            class="col-xs-3 text-center"<?php endif; ?>>
             <a href="<?php echo U('Order/index',array('status'=>3));?>" >
                <span>分配中</span>
             </a>
        </li>
        <li role="presentation"
        <?php if($_GET['status'] == 4 ): ?>class="active col-xs-3 text-center"
            <?php else: ?>
            class="col-xs-3 text-center"<?php endif; ?>>
                <a href="<?php echo U('Order/index',array('status'=>4));?>">
                <span>已完成</span></a>
        </li>

       <!-- <li role="presentation"
        <?php if($_GET['status'] == 5 ): ?>class="active col-xs-3 text-center"
            <?php else: ?>
            class="col-xs-3 text-center"<?php endif; ?>>
        <a href="<?php echo U('Order/index',array('status'=>5));?>">
            <span>已取消</span>
        </a>
        </li>-->
    </ul>
</div>

		<?php if(is_array($order)): $i = 0; $__LIST__ = $order;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><div class="quanbu_div">
        	<p class="diandan_p"><span>订单号：<?php echo ($val["orderId"]); ?></span></p>
                <?php if(is_array($val['detail'])): $i = 0; $__LIST__ = $val['detail'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="shopping_box border_bottom">
                        <a href="<?php echo U('Order/order_xx',array('id'=>$val[id],'status'=>$_GET['status']));?>">
                            <img style="width:86px;hieght:86.15px;" src="<?php echo attach(get_thumb($v['img'], '_s'), 'item');?>" class="right_disimg1"/>
                            <p class="shopping_p line_height3"><?php echo ($v['guige']); echo (strip_tags(mb_substr($v["title"],0,8,'utf-8'))); ?><span>x <?php echo ($v["quantity"]); ?></span></p>
                            <?php if($v['guige'] != '' or $v['guige'] != 0 ): ?><p class="shopping_p line_height3">
                                    <span>规格:<?php echo ($v["guige"]); ?></span>
                                </p><?php endif; ?>
                        </a>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
            <div class="diandan_bottomdiv">
            	<span class="fl">总价：<i>￥<?php echo ($val["order_sumPrice"]); ?></i></span>
                <div class="button_div fr">
                    <a href="javascript:;" data="<?php echo ($val["id"]); ?>" class="delete lxt-a1">删除</a>
                   <?php if($val["status"] == 1): ?><!--<a class="cancel lxt-a1" data="<?php echo ($val["id"]); ?>" href="javascript:;">取消</a>&nbsp;-->
                	<a href="<?php echo U('Order/pay',array('orderId'=>$val['orderId'],'pay'=>1,'order_sumPrice'=>$val['order_sumPrice']));?>"  class="lxt-a2">付款</a>
                	<?php elseif($val["status"] == 2): ?>
                       <?php if($val['supportmetho'] == 1 ): ?><a href="javascript:;" data="<?php echo ($val["id"]); ?>" class="but2-z lxt-a1" charset="lxt-a1">退款</a><?php endif; ?>
                	    <a href="javascript:;" style="margin-right:10px;" charset="lxt-a1">待配送</a>
                	<?php elseif($val["status"] == 3): ?>
                	<a class="confirm lxt-a2" data="<?php echo ($val["id"]); ?>" href="javascript:;"  style="margin-right:10px;">确认收货</a>
                	<?php elseif($val["status"] == 4): ?>
                       <?php if($val["ping"] == 1 ): ?><a href="javascript:;"  class="lxt-a1" >已评价</a>
                           <?php else: ?>
                           <a href="<?php echo U('Order/item_comment',array('id'=>$val['id'],'type'=>'4'));?>" class="lxt-a2" >待评价</a><?php endif; ?>
                       <?php elseif($val["status"] == 6): ?>
                	<?php else: endif; ?>

                </div>
            </div>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>
<?php if(empty($order)): ?><div class="promptbox">
        <img src="../Style/static/images/cw_03.png" class="prompt">
    </div>
    <h1>
        您还没有
        <?php if($_GET['status'] == 1): ?>待付款
            <?php elseif($_GET['status'] == 5): ?>
            已取消
            <?php elseif($_GET['status'] == 3): ?>
            配送中
            <?php elseif($_GET['status'] == 4): ?>
            已完成
            <?php elseif($_GET['status'] == 2): ?>
            待配送<?php endif; ?>
        订单，去<a href="<?php echo U('Index/index');?>">首页</a>
    </h1><?php endif; ?>


 <script>
$(function(){

    //退款
    $(".but2-z").click(function(){
        var id = $(this).attr('data');
        if(window.confirm("您确定要退款吗?")){
            $.post("<?php echo U('Order/alert_confirm');?>",{ id:id },function(data){
                if(data == 1){
                    layer.msg('退款成功');
                    var url="<?php echo u('Order/index',array('status'=>$_GET['status']));?>"
                    setTimeout("location.href='"+url+"'",1000);
                }else{
                    layer.msg('退款失败')
                }
            });
        }

    });


	$('.cancel').click(function(){
		var id = $(this).attr('data');
		if(window.confirm("您确定要取消订单吗?")){
			$.post("<?php echo U('Order/cancel');?>",{ id:id },function(data){
				if(data == 1){
					layer.msg('取消成功');
                    var url="<?php echo u('Order/index',array('status'=>$_GET['status']));?>"
                    setTimeout("location.href='"+url+"'",1000);
                }else{
                    layer.msg('取消失败')
				}
			});
		}

	});
    $('.delete').click(function(){
        var id = $(this).attr('data');
        if(window.confirm("您确定要删除订单吗?")){
            $.post("<?php echo U('Order/delete');?>",{ id:id },function(data){
                if(data == 1){
                    layer.msg('删除成功');
                    var url="<?php echo u('Order/index',array('status'=>$_GET['status']));?>"
                    setTimeout("location.href='"+url+"'",1000);
                }else{
                    layer.msg('取消失败')
                }
            });
        }

    });
	$('.confirm').click(function(){
		var id = $(this).attr('data');
		if(window.confirm("您确定已收货吗?")){
			$.post("<?php echo U('Order/confirmOrder');?>",{ id:id },function(data){
				//alert(data)
				if(data == 1){
                    layer.msg('确认成功');
                    var url="<?php echo u('Order/index',array('status'=>$_GET['status']));?>"
                    setTimeout("location.href='"+url+"'",1000);
                }else{
                    layer.msg('确认失败')
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