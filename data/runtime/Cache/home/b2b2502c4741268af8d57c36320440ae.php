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
                <a href="<?php echo U('Shop/merchant_order');?>"><span>折扣订单</span></a>
            </li>
            <li role="presentation" class="active col-xs-6 text-center">
                <a href="<?php echo U('Shop/order_tuan',array('status'=>1));?>">
                    <span>团购订单</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content">
        <!--团购内容-->
        <div role="tabpanel" class="row tab-pane  active unused lxt-tab" id="bb">
            <div class="row coupon_box wxh_ul_box">
                <ul class="nav nav-tabs" role="tablist">

                    <li role="presentation"
                        <?php if($_GET['status'] == 1 ): ?>class="col-xs-4 active text-center"
                            <?php elseif($status == 1 or $status == ''): ?>
                            class="col-xs-4 active text-center"
                            <?php else: ?>
                            class="col-xs-4 text-center"<?php endif; ?>>
                        <a href="<?php echo U('Shop/order_tuan',array('status'=>1));?>">
                            <span>待付款</span>
                        </a>
                    </li>

                    <li role="presentation"
                        <?php if($_GET['status'] == 2 ): ?>class="col-xs-4 active text-center"
                            <?php elseif($status == 2): ?>
                            class="col-xs-4 active text-center"
                            <?php else: ?>
                            class="col-xs-4 text-center"<?php endif; ?>>
                        <a href="<?php echo U('Shop/order_tuan',array('status'=>2));?>" >
                            <span>未消费</span>
                        </a>
                    </li>

                    <li role="presentation"
                        <?php if($_GET['status'] == 4 ): ?>class="col-xs-4 active text-center"
                            <?php elseif($status == 4): ?>
                             class="col-xs-4 active text-center"
                            <?php else: ?>
                             class="col-xs-4  text-center"<?php endif; ?>>
                        <a href="<?php echo U('Shop/order_tuan',array('status'=>4));?>">
                            <span>已消费</span></a>
                    </li>

                </ul>
                <form action="<?php echo U('Shop/order_tuan');?>" method="post">
                     <div class="col-xs-12" style="margin:10px 0;">
                        <div class="input-group">
                              <input type="text" name="keyword" value="<?php echo ($search["keyword"]); ?>" class="form-control" placeholder="请输入订单编号">
                             <?php if($_GET['status'] == '' ): ?><input type="hidden" name="status" value="<?php echo ($status); ?>" />
                                 <?php else: ?>
                                 <input type="hidden" name="status" value="<?php echo ($_GET['status']); ?>" /><?php endif; ?>

                             <span class="input-group-btn">
                                <button class="btn btn-default" type="submit" style="background:#65CCED; color:#fff;">搜索</button>
                              </span>
                        </div>
                    </div>
                </form>


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
                                <a href="<?php echo U('Shop/order_xx_wu',array('id'=>$val[id],'status'=>$_GET['status']));?>">
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
                                    </if>
                                </div>

                            </div>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
            <?php if(empty($order)): ?><div class="tab-pane" style="margin-top: 15px;">
                    <img style=" display: block; width:95px; height:93px; margin:0 auto" src="../Style/static/images/cw_03.png" class="prompt">
                </div>

                <h5 class="text-center">
                    <?php if($_GET['status'] == 1): ?>暂无待付款订单
                        <?php elseif($status == 1): ?>
                        暂无待付款订单
                        <?php elseif($_GET['status'] == 4): ?>
                        暂无已消费订单
                        <?php elseif($status == 4): ?>
                        暂无已消费订单
                        <?php elseif($_GET['status'] == 2): ?>
                        暂无未消费订单
                        <?php elseif($status == 2): ?>
                        暂无未消费订单<?php endif; ?>
                </h5><?php endif; ?>
            </div>
        </div>
    </div>
</div>



<script>
    $(function(){

        $('.delete').click(function(){
            var id = $(this).attr('data');
            if(window.confirm("您确定要删除订单吗?")){
                $.post("<?php echo U('Shop/delete');?>",{ id:id },function(data){
                    if(data == 1){
                        layer.msg('删除成功');
                        var url="<?php echo u('Shop/order_tuan',array('status'=>$_GET['status']));?>"
                        setTimeout("location.href='"+url+"'",1000);
                    }else{
                        layer.msg('取消失败')
                    }
                });
            }

        });
    })
</script>
</body>
</html>