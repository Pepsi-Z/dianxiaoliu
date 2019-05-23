<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />
    <title>提交订单</title>
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

<?php if($_GET['type'] == 4 ): ?><header class="wxh_shopping_header">
        <div class="container">
            <div class="row">
                <div class="col-xs-2"><a href="javascript:history.go(-1);" class="text-left"><span class="glyphicon glyphicon-menu-left"></span></a></div>
                <div class="col-xs-8 text-center">确认订单</div>
            </div>
        </div>
    </header><?php endif; ?>

<style>
    .lxt_choose{ display:none !important;}
    .lxt_choose + label{ display:block;  width:18px; height:18px; background: url(__STATIC__/css/img/choose0.png) no-repeat center; background-size:100%;}
    .lxt_choose1:checked + label {width:18px; height:18px; background: url(__STATIC__/css/img/choose1.png) no-repeat center; background-size:100%;}
    #content .enter,#content .login_btn{ background-image: none; line-height: 30px;}
    .make_sure p{width: 80%; margin:0 auto !important;}
    .make_sure p a{ background-color: #40C780 !important;}
   /* .row {
        margin-right: 0px;
        margin-left: 0px;
    }*/

</style>

<body <?php if($_GET['type'] == 4 ): ?>class="wxh_bggreycolor wxh_section_30"<?php else: ?>class="wxh_bggreycolor"<?php endif; ?> >
<form id="form2" method="post" action="<?php echo U('Order/pay');?>">
    <div class="container">
        <div class="row">
            <div class="wxh_bgwhitecolor wxh_submitorder">
                <div class="col-xs-12">
                    <div class="col-xs-9"><?php echo ($item["title"]); ?></div>
                    <?php if($_GET['type'] == 4): ?><div class="col-xs-3"><span class="pull-right"><?php echo ($price); ?>元</span></div>
                        <?php else: ?>
                        <div class="col-xs-3"><span class="pull-right"><?php echo ($item["tc_price"]); ?>元</span></div><?php endif; ?>
                </div>

                <div class="col-xs-12">
                    <div class="col-xs-6">数量：</div>
                    <div class="col-xs-6 text-right">
                            <p>X<b><?php echo ($item["num"]); ?></b></p>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="col-xs-6">小计</div>
                    <?php if($_GET['type'] == 4): ?><div class="col-xs-6"><span class="pull-right"><?php echo ($t_price); ?>元</span></div>
                        <?php else: ?>
                        <div class="col-xs-6"><span class="pull-right"><?php echo ($item["total_price"]); ?>元</span></div><?php endif; ?>
                </div>

                <?php if($_GET['type'] == 4): if($guige != '' or $guige != 0 ): ?><div class="col-xs-12">
                            <div class="col-xs-6">规格：</div>
                            <div class="col-xs-6 text-right">
                                <p><?php echo ($guige); ?></p>
                            </div>
                        </div><?php endif; endif; ?>


            </div>


            <?php if($_GET['type'] == 4): ?><div class="col-xs-12" style="height: 35px; line-height: 35px;background: #fff;">
                    <span class="pull-left">订单备注：</span>
                    <input class="pull-left" style="border:0px;width:70%; height: 33px;" name="beizhu" placeholder="请输入备注内容" type="text" value=""  />
                </div><?php endif; ?>


            <?php if($_GET['type'] == 4 ): ?><!--    <div class="col-xs-12" style="background:#fff;padding:5px;margin-top:10px;border-bottom:1px solid #e5e5e5;">
                    <div class="row lxt-pay">
                        <div class="col-xs-2 text-center"><img style="height: 30px;" src="__STATIC__/css/img/ziti.png" class="lxt-pay-img"></span></div>
                        <div class="col-xs-8 "><p>自提</p></div>
                        <div class="col-xs-2 text-center">
                            <input type="radio" name="freetype" value="0" id="checkbox1" class="lxt_choose lxt_choose1 lxt_chooseBox" />
                            <label class="lxt-all2" for="checkbox1"></label>
                        </div>
                    </div>
                </div>-->

                <div class="col-xs-12" style="background:#fff;padding:5px;">
                    <div class="row lxt-pay">
                        <div class="col-xs-2 text-center" style="padding-left:20px;"><img style="height: 30px;" src="__STATIC__/css/img/peisong.png" class="lxt-pay-img"></span></div>
                        <div class="col-xs-8 "><p>配送</p></div>
                        <div class="col-xs-2 text-center">
                            <input type="radio" name="freetype" value="1" id="checkbox4" class="lxt_choose lxt_choose1 lxt_chooseBox" checked="checked" />
                            <label class="lxt-all2" for="checkbox4"></label>
                        </div>
                    </div>
                </div><?php endif; ?>

            <?php if($_GET['type'] != '4'): ?><div class="wxh_bgwhitecolor wxh_submitorder_tel">
                    <div class="col-xs-12">
                        输入手机号：
                    </div>
                    <div class="col-xs-12">
                        <input id="t_tel" name="tel" type="text" placeholder="请输入手机号"/>
                    </div>
                </div><?php endif; ?>


            <?php $code = mt_rand(0,1000000); ?>
            <?php if($_GET['type'] == 4): ?><input type="hidden" name="total" value="<?php echo ($t_price); ?>">
                <input type="hidden" name="price<?php echo ($_GET['id']); ?>" value="<?php echo ($price); ?>">
                <input type="hidden" name="guige" value="<?php echo ($guige); ?>">
                <?php else: ?>
                <input type="hidden" name="total" value="<?php echo ($item["total_price"]); ?>">
                <input type="hidden" name="price<?php echo ($_GET['id']); ?>" value="<?php echo ($item["tc_price"]); ?>"><?php endif; ?>
            <input type="hidden" name="code" value="<?php echo ($code); ?>"/>
            <input type="hidden" name="id[]" value="<?php echo ($_GET['id']); ?>">
            <input type="hidden" name="number<?php echo ($_GET['id']); ?>" value="<?php echo ($_GET['num']); ?>">
            <input type="hidden" name="pay" value="1">
            <input type="hidden" name="type" value="<?php echo ($_GET['type']); ?>">


            <div class="col-xs-12 wxh_submitorder_btn">
                <a id="submit1" href="javascript:;">
                    <input type="button" value="提交订单"/>
                </a>
            </div>

        </div>
    </div>
</form>
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
<script>
    $("#submit1").click(function(e){
        var t_tel  = $('#t_tel').val();
        var type = "<?php echo ($_GET['type']); ?>"
        if(type == '4'){
          $("#form2").submit();
        }else{
            if(t_tel == ''){
                layer.msg('电话不能为空');
                return false;
            }
            //电话验证
            if(!(/^0?1[3|4|5|7|8][0-9]\d{8}$/.test($('#t_tel').val()))){
                layer.msg('电话号输入不正确');
                return false;
            }
            $("#form2").submit();

        }


    })
</script>
</html>