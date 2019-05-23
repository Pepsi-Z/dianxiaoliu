<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />
<meta name="format-detection" content="telephone=no" />
<title>订单管理</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />

<link href="__STATIC__/manage/css/bootstrap.min.css" rel="stylesheet">
<link href="__STATIC__/manage/css/common.css" rel="stylesheet" type="text/css">
<link href="__STATIC__/manage/css/main.css" rel="stylesheet" type="text/css">
<script src="__STATIC__/manage/js/jquery-1.9.1.min.js"></script>
<script src="__STATIC__/manage/js/bootstrap.min.js"></script>
<!--layer-->
<!--<script src="http://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script>-->
<script src="__STATIC__/js/layer_main.js"></script>
<script src="__STATIC__/js/layer.js"></script>


</head>
<body class="lxt-color">
<div class="container">
    <div class="row coupon_box lxt-row">
  <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class=" active col-xs-6 text-center">
          <a href="<?php echo U('Shop/merchant_order');?>"><span>折扣订单</span></a>
      </li>
      <li role="presentation" class=" col-xs-6 text-center">
          <a href="<?php echo U('Shop/order_tuan',array('status'=>1));?>">
              <span>团购订单</span>
          </a>
      </li>
  </ul>
  </div>
    
<div class="tab-content">

<!--折扣内容-->
 <div role="tabpanel" class="row lxt_integral tab-pane active unused lxt-tab" id="aa" >     
    <table class="table">
        	<tr>
            	<th>会员卡号</td>
                <th>时间</td>
                <th>积分</td>
                <th>状态</td>
            </tr>
        <?php if(is_array($integral_list)): $i = 0; $__LIST__ = $integral_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
            	<td><?php echo ($val["card_number"]); ?></td>
            	<td><?php echo (date("Y-m-d",$val["add_time"])); ?></td>
            	<td class="lxt_increase">+<?php echo ($val["score"]); ?></td>
                <?php if($val["status"] == 0 ): ?><td class="lxt-blue">未返利</td>
                    <?php else: ?>
                    <td>已返利</td><?php endif; ?>


            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</table>
</div>
<!--团购内容-->
</div>
</div>



</body>
</html>