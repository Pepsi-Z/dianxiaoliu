<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />
    <meta name="format-detection" content="telephone=no" />
    <title>订单管理</title>
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
<body class="lxt-color">
<div class="container">

    <div class="row coupon_box lxt-row">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class=" col-xs-6 text-center">
                <a href="<?php echo U('Order/order_zhe');?>"><span>折扣订单</span></a>
            </li>
            <li role="presentation" class="active col-xs-6 text-center">
                <a href="<?php echo U('Order/order_tuan');?>">
                    <span>团购订单</span></a>
            </li>
        </ul>
    </div>

    <div class="tab-content">
        <!--团购内容-->
        <div role="tabpanel" class="row tab-pane  active unused lxt-tab" id="bb">
            <div class="row coupon_box wxh_ul_box">
                <ul class="nav nav-tabs" role="tablist">

                    <li role="presentation"
                        <?php if($_GET['status'] == 1 or $_GET['status'] == ''): ?>class="col-xs-4 active text-center"
                            <?php else: ?>
                            class="col-xs-4 text-center"<?php endif; ?>>

                        <a href="<?php echo U('Order/order_tuan',array('status'=>1));?>">
                            <span>待付款</span>
                        </a>
                    </li>

                    <li role="presentation"
                        <?php if($_GET['status'] == 2 ): ?>class="col-xs-4 active text-center"
                            <?php else: ?>
                            class="col-xs-4 text-center"<?php endif; ?>>
                        <a href="<?php echo U('Order/order_tuan',array('status'=>2));?>" >
                            <span>未消费</span>
                        </a>
                    </li>

                    <li role="presentation"
                        <?php if($_GET['status'] == 4 ): ?>class="col-xs-4 active text-center"
                            <?php else: ?>
                            class="col-xs-4  text-center"<?php endif; ?>>
                        <a href="<?php echo U('Order/order_tuan',array('status'=>4));?>">
                            <span>已消费</span></a>
                    </li>

                </ul>
                <style>
                    .wxh_ul_box .col-xs-4,.wxh_ul_box .col-xs-4 a{ padding: 0; height: 40px; line-height: 40px;}
                    .coupon_box.wxh_ul_box .nav li:nth-child(2) a{ padding: 0;}
                    .lxt-a2 { padding: 4px 8px;}
                    .lxt-a1 { padding: 4px 8px;}
                </style>
            </div>
            <div class="tab-content">

                <div role="tabpanel" class="row tab-pane active unused">
                    <?php if(is_array($order)): $i = 0; $__LIST__ = $order;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><div class="col-xs-12">
                            <div class="row lxt-order">
                                <div class="col-xs-12">订单号：<?php echo ($val["orderId"]); ?></div>
                            </div>
                            <div class="row lxt-confirm-b lxt-order-a">
                                <a href="<?php echo U('Order/order_xx_wu',array('id'=>$val[id],'status'=>$_GET['status']));?>">
                                    <?php if(is_array($val['detail'])): $i = 0; $__LIST__ = $val['detail'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="col-xs-4"> <img style="height:100px;" src="<?php echo attach(get_thumb($v['img'], '_s'), 'item');?>" alt="..."> </div>
                                        <div class="col-xs-8">
                                            <h4><?php echo (strip_tags(mb_substr($v["title"],0,8,'utf-8'))); ?></h4>
                                            <span class="pull-left">X<span><?php echo ($v["quantity"]); ?></span></span>
                                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                                </a>
                            </div>
                            <div class="row lxt-order-b">
                                <div class="col-xs-6 text-left">总价：<i>￥<?php echo ($val["order_sumPrice"]); ?></i></div>
                                <div class="col-xs-6 text-right">
                                         <a href="javascript:;" data="<?php echo ($val["id"]); ?>" class="delete lxt-a1">删除</a>
                                    <?php if($val["status"] == 1): ?><!--<a class=" lxt-a1" data="<?php echo ($val["id"]); ?>" href="javascript:;">取消</a>&nbsp;-->
                                            <a href="<?php echo U('Order/pay',array('orderId'=>$val['orderId'],'pay'=>1,'order_sumPrice'=>$val['order_sumPrice']));?>"  class="lxt-a2">付款</a>
                                        <?php elseif($val["status"] == 2): ?>
                                             <a href="javascript:;" data="<?php echo ($val["id"]); ?>" class="but2-z lxt-a2" charset="lxt-a1">退款</a>
                                        <?php elseif($val["status"] == 4): ?>
                                           <?php if($val["ping"] == 1 ): ?><a href="javascript:;"  class="lxt-a1" >已评价</a>
                                               <?php else: ?>
                                               <a href="<?php echo U('Order/item_comment',array('id'=>$val['id']));?>" class="lxt-a2" >待评价</a><?php endif; ?>


                                        <?php elseif($val["status"] == 6): ?>
                                        <?php else: endif; ?>
                                </div>

                            </div>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
            <?php if(empty($order)): ?><div class="tab-pane" style="margin-top: 15px;">
                    <img style=" display: block; width:95px; height:93px; margin:0 auto" src="../Style/static/images/cw_03.png" class="prompt">
                </div>

                <h5 class="text-center">
                    您还没有
                    <?php if($_GET['status'] == 1): ?>待付款
                        <?php elseif($_GET['status'] == 4): ?>
                        已消费
                        <?php elseif($_GET['status'] == 2): ?>
                        未消费<?php endif; ?>
                    订单，去<a href="<?php echo U('Index/index');?>">首页</a>
                </h5><?php endif; ?>
            </div>
        </div>
    </div>
</div>



<script>
    $(function(){

        //退款
        $(".but2-z").click(function(){
            var id = $(this).attr('data');
            if(window.confirm("您确定要退款吗?")){
             $.post("<?php echo U('Order/alert_confirm');?>",{ id:id },function(data){
                    if(data == 1){
                        layer.msg('退款成功');
                        var url="<?php echo u('Order/order_tuan',array('status'=>$_GET['status']));?>"
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
                $.post("<?php echo U('Order/delete_wu');?>",{ id:id },function(data){
                    if(data == 1){
                        layer.msg('删除成功');
                        var url="<?php echo u('Order/order_tuan',array('status'=>$_GET['status']));?>"
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