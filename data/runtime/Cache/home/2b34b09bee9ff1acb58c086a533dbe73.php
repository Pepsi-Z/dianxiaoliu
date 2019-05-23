<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />
    <title>我的</title>
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
    <div class="container lxt_mine">
        <div class="row">
            <div class="media lxt_media col-xs-12 lxt_top">
                <div class="media-left media-middle col-xs-3 text-right lxt_touxiang">
                    <a href="javascript:void();" id="tx" class="col-xs-12 text-right">
                        <?php if($user['cover'] == ''): ?><img class="" src="__STATIC__/css/img/yy_mine_head.jpg" alt="..." id="person_img">
                            <?php else: ?>
                            <img src="<?php echo ($user["cover"]); ?>" id="person_img"/><?php endif; ?>
                    </a>
                </div>
                <div class="media-body col-xs-4">
                    <h6 class="media-heading"><name><?php echo ($user['name']); ?></name></h6>
                    <h6 class="media-heading"><em class="pull-left"></em> 积分：
                       <span>
                           <?php if($user['score'] != '' ): echo ($user['score']); ?>
                               <?php else: ?>
                               0<?php endif; ?>

                       </span>分</h6>
                </div>

            </div>
            <h1></h1>
            <div class="media lxt_media_list">
                <a href="<?php echo U('Order/order_zhe');?>">
                    <div class="media-left media-middle">
                        <img class="media-object img-rounded" src="__STATIC__/css/img/icon0.png" alt="...">
                    </div>
                    <div class="media-body">
                        <h5 class="media-heading pull-left">全部订单</h5><span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span>
                    </div>
                </a>
            </div>


            <div class="media lxt_media_list">
                <a href="<?php echo U('Order/index');?>">
                    <div class="media-left media-middle">
                        <img class="media-object img-rounded" src="__STATIC__/css/img/xw.png" alt="...">
                    </div>
                    <div class="media-body">
                        <h5 class="media-heading pull-left">小六订单</h5><span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span>
                    </div>
                </a>
            </div>

            <div class="media lxt_media_list">
                <a href="<?php echo U('User/jifen_info');?>">
                    <div class="media-left media-middle">
                        <img class="media-object img-rounded" src="__STATIC__/css/img/icon1.png" alt="...">
                    </div>
                    <div class="media-body">
                        <h5 class="media-heading pull-left">积分明细</h5><span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span>
                    </div>
                </a>
            </div>

            <div class="media lxt_media_list">
                <a href="<?php echo U('User/wanshan',array('status'=>1));?>">
                    <div class="media-left media-middle">
                        <img class="media-object img-rounded" src="__STATIC__/css/img/icon2.png" alt="...">
                    </div>
                    <div class="media-body">
                        <h5 class="media-heading pull-left">个人资料</h5><span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span>
                    </div>
                </a>
            </div>

            <div class="media lxt_media_list">
                <a href="<?php echo U('User/address');?>">
                    <div class="media-left media-middle">
                        <img class="media-object img-rounded" src="__STATIC__/css/img/icon3.png" alt="...">
                    </div>
                    <div class="media-body">
                        <h5 class="media-heading pull-left">收货地址</h5><span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span>
                    </div>
                </a>
            </div>

            <div class="media lxt_media_list">
                <a href="<?php echo U('Jifen/index');?>">
                    <div class="media-left media-middle">
                        <img class="media-object img-rounded" src="__STATIC__/css/img/icon4.png" alt="...">
                    </div>
                    <div class="media-body">
                        <h5 class="media-heading pull-left">积分商城</h5><span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span>
                    </div>
                </a>
            </div>


            <div class="media lxt_media_list">
                <a href="<?php echo U('Jifen/order');?>">
                    <div class="media-left media-middle">
                        <img class="media-object img-rounded" src="__STATIC__/css/img/jf.png" alt="...">
                    </div>
                    <div class="media-body">
                        <h5 class="media-heading pull-left">积分订单</h5><span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span>
                    </div>
                </a>
            </div>


            <div class="media lxt_media_list">
                <a href="<?php echo U('User/user_card');?>">
                    <div class="media-left media-middle">
                        <img class="media-object img-rounded" src="__STATIC__/css/img/icon5.png" alt="...">
                    </div>
                    <div class="media-body">
                        <h5 class="media-heading pull-left">我的小六卡</h5><span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span>
                    </div>
                </a>
            </div>


        </div>
    </div>
    <a href="<?php echo U('User/logout');?>" class="btn lxt_out" id="out" type="button">退出登录</a>
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
<!-----主体结束-------->

</body>
</html>