<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />
    <title>商家详情</title>
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
<body class="wxh_bggreycolor">
<section>
    <div class="container">
        <div class="row">
            <div class="wxh_bgwhitecolor">
                <div class="col-xs-12 wxh_detailsimg_box">
                    <div><img style="height: 165px;" src="<?php echo attach(get_thumb($info['img'], '_b'), 'merchant');?>"/></div>
                    <?php if($info["zhe"] == '1' ): ?><div class="c_shopzk">
                            <span class="pull-right">
                              <em>
                                  <?php if($info["enjoy_num_1"] != '' ): echo ($info["enjoy_num_1"]); ?>
                                      <?php else: ?>
                                      <?php echo ($info["enjoy_num"]); endif; ?>

                              </em>人已享</span>
                            <p><em><?php echo ($info["discount"]); ?></em>折</p>
                        </div><?php endif; ?>
                </div>
                <div class="col-xs-12 wxh_details_titlebox">
                    <h5 class="wxh_fontoverflow"><?php echo ($info["title"]); ?></h5>
                    <i id="wxh-star<?php echo ($info["id"]); ?>" class="pull-left"></i>
                    <?php
 if($start){ $one = $start; }else{ $one = $info['level']; } ?>
                    <script>
                        $("#wxh-star<?php echo ($info["id"]); ?>").raty({
                            start:"<?php echo ($one); ?>",
                            readOnly:true,
                            path:"__STATIC__/js/jquery.raty-0.5/img/"
                        })
                    </script>
                    <span class="pull-left first_span">
                        <?php if($start != '' ): echo ($start); ?>.0
                            <?php else: ?>
                            <?php echo ($info["level"]); ?>.0<?php endif; ?>

                    </span>
                </div>
                <div class="wxh_address_box">
                    <div class="col-xs-2 wxh_location">
                        <a href="http://api.map.baidu.com/marker?location=<?php echo ($info["xwei"]); ?>,<?php echo ($info["xjing"]); ?>&title=<?php echo ($info["title"]); ?>&content=<?php echo ($info["address"]); ?>&output=html"></a>
                    </div>
                    <div class="col-xs-8 wxh_fontoverflow"><?php echo ($info["address"]); ?></div>
                    <div class="col-xs-2 wxh_tel">
                        <a href="tel:<?php echo ($info["tel"]); ?>"></a>
                    </div>
                </div>
            </div>
            <?php if($info["zhe"] == '1' ): ?><div class="wxh_bgwhitecolor wxh_fracture_box">
                    <div class="col-xs-12">
                        <h5><span>折</span>小六折扣优惠：</h5>
                        <p><?php echo ($info["desc"]); ?></p>
                    </div>
                </div><?php endif; ?>
            <div class="wxh_tab_box">
                <ul class="nav nav-tabs wxh_bgwhitecolor" role="tablist">
                    <li role="presentation"  class="col-xs-6 text-center first_li active">
                        <a href="#home" aria-controls="home" role="tab" data-toggle="tab">商家详情</a>
                    </li>
                    <li role="presentation" class="col-xs-6 text-center">
                        <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">评价</a>
                    </li>
                </ul>
                <div class="tab-content wxh_shopping_box">
                    <div role="tabpanel" class="tab-pane active wxh_shoppinglist c_white" id="home">
                        <?php echo ($info["merchant_desc"]); ?>
                        <p class="c_white c_nomargin"><a href="<?php echo U('Item/merchant_desc',array('id'=>$info['id'],'type'=>$_GET['type']));?>">查看详情<span class=" pull-right glyphicon glyphicon-menu-right"></span></a></p>
                        <?php if($_GET['type'] == '4'): ?><p class="c_white c_nomargin"><a href="<?php echo U('Item/item_list',array('id'=>$info['id'],'type'=>$_GET['type']));?>">商品详情  <b style="color: red">!!(请点击此处查看商品)</b><span class=" pull-right glyphicon glyphicon-menu-right"></span></a></p>
                            <?php else: ?>
                            <?php if($info["tao"] == '1' ): ?><p class="c_white c_nomargin"><a href="<?php echo U('Item/item_list',array('id'=>$info['id'],'type'=>$_GET['type']));?>">商品详情  <b style="color: red">!!(请点击此处查看商品)</b><span class=" pull-right glyphicon glyphicon-menu-right"></span></a></p><?php endif; endif; ?>

                    </div>

                    <!--评价-->
                    <div role="tabpanel" class="tab-pane wxh_evaluate_box" id="profile">
                        <?php if(is_array($item_comment)): $i = 0; $__LIST__ = $item_comment;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><div class="wxh_user_box">
                            <div class="col-xs-12 wxh_media_box">
                                <div class="media">
                                    <div class="media-left">
                                        <div>
                                            <?php if($val["cover"] != '' ): ?><img class="media-object" src="<?php echo ($val["cover"]); ?>" alt="...">
                                                <?php else: ?>
                                                <img class="media-object" src="static/css/img/logo2.png" alt="..."><?php endif; ?>

                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <h5 class="media-heading wxh_333color wxh_fontoverflow"><?php echo ($val["uname"]); ?></h5>
                                        <p>
                                            <i id="wxh-star<?php echo ($val["id"]); ?>" class="pull-left"></i>
                                            <script>
                                                $("#wxh-star<?php echo ($val["id"]); ?>").raty({
                                                    start:"<?php echo ($val["startcomment"]); ?>",
                                                    readOnly:true,
                                                    path:"static/js/jquery.raty-0.5/img/"
                                                })
                                            </script>
                                            <span class="wxh_666color pull-right"><?php echo (date('Y-m-d',$val["add_time"])); ?></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 wxh_user_evaluate">
                                <p>
                                   <?php echo ($val["info"]); ?>
                                </p>
                            </div>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                        <?php if(empty($item_comment)): ?><div class="wxh_user_box">
                                <div class="col-xs-12 wxh_user_evaluate">
                                    ！暂无评价数据
                                </div>
                            </div><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
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