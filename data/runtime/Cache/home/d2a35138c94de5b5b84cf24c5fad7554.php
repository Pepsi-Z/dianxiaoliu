<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />
<title>积分明细</title>
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



<body class="lxt-color">
<!------主体--------->
<section>
  <div class="container lxt_account">
    <div class="row">
		<div class="col-xs-12 text-center">
            <div class="lxt_wealth">我的积分：</div>
            <p><span><?php echo ($score); ?></span>分</p>
        </div>
    </div>
  </div>
  <div class="container lxt_integral">
    <div class="row">
        <table class="table">
        	<caption class="text-center colorred">积分明细</caption>
        	<tr>
            	<th style="width: 120px;">时间</td>
                <th style="width: 120px;">明细</td>
                <th style="width: 100px;">财富</td>
            </tr>
            <?php if(is_array($score_log)): $i = 0; $__LIST__ = $score_log;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
                    <td><?php echo (date("Y-m-d H:i",$val["add_time"])); ?></td>
                    <td><?php echo ($val["action"]); ?></td>
                    <td class="lxt_cut"><?php echo ($val["score"]); ?></td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</table>
    </div>
  </div>
</section>
<!-----主体结束-------->
</body>
</html>