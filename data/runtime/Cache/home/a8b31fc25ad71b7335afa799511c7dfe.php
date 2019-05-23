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



    <style>
        .lxt-a1, .lxt-a2, .lxt-a3{ padding: 4px 8px;}
    </style>
</head>
<body class="lxt-color">
<div class="container">

    <div class="row coupon_box lxt-row">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active col-xs-6 text-center">
                <a href="<?php echo U('Order/order_zhe');?>"><span>折扣订单</span></a>
            </li>
            <li role="presentation" class="col-xs-6 text-center">
                <a href="<?php echo U('Order/order_tuan');?>">
                    <span>团购订单</span></a>
            </li>
        </ul>
    </div>

    <div class="tab-content">

        <!--折扣内容-->
        <div role="tabpanel" class="row tab-pane active unused lxt-tab" id="aa" >
            <div class="tab-content">
                <!--未返利-->
                <div role="tabpanel" class="row tab-pane active unused" id="home">
                    <?php if(is_array($integral_list)): $i = 0; $__LIST__ = $integral_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><div class="col-xs-12">
                            <div class="row lxt-order">
                                <div class="col-xs-8"><?php echo ($val["sname"]); ?></div>
                                <div class="col-xs-4">积分:+<?php echo ($val["score"]); ?></div>
                            </div>
                            <div class="row lxt-order-b">
                                <div class="col-xs-6 text-left text-muted"><?php echo (date("Y-m-d H:i",$val["add_time"])); ?></div>
                                <div class="col-xs-12 text-right">
                                    <a href="javascript:;" data="<?php echo ($val["id"]); ?>" class="delete lxt-a1">删除</a>
                                    <?php if($val["status"] == 0): ?><a href="#" data="<?php echo ($val["id"]); ?>" class="lxt-a2 return_zhe">返利</a>
                                        <?php else: ?>
                                        <a href="javascript:;" class="lxt-a1">已返利</a>
                                        <?php if($val["ping"] == 1 ): ?><a href="javascript:;"  class="lxt-a1" >已评价</a>
                                            <?php else: ?>
                                            <a href="<?php echo U('Order/item_comment_zhe',array('id'=>$val['id']));?>" class="lxt-a2" >待评价</a><?php endif; endif; ?>
                                </div>
                            </div>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
        </div>
     </div>
    </div>
</div>
<script>
    $('.return_zhe').click(function(){
        var id = $(this).attr('data');
        if(window.confirm("您确定要返利吗?")){
            $.post("<?php echo U('Order/SendRedEnvelope');?>",{ id:id },function(data){
                if(data == 1){
                    layer.msg('红包已经发送！请关闭当前页面去领取');
                    var url="<?php echo u('Order/order_zhe');?>"
                    setTimeout("location.href='"+url+"'",1000);
                }else{
                    layer.msg('返利失败!金额必须大于1元小于200元')
                }
            });
        }



    });

    $('.delete').click(function(){
        var id = $(this).attr('data');
        if(window.confirm("您确定要删除订单吗?")){
            $.post("<?php echo U('Order/delete_zhe');?>",{ id:id },function(data){
                if(data == 1){
                    layer.msg('删除成功');
                    var url="<?php echo u('Order/order_zhe');?>"
                    setTimeout("location.href='"+url+"'",1000);
                }else{
                    layer.msg('删除失败')
                }
            });
        }

    });
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