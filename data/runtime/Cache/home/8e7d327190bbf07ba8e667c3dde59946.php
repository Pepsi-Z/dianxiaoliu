<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />
    <title>
        <?php if($_GET['type'] == '5'): ?>折扣
            <?php elseif($_GET['type'] == '6'): ?>
            套餐
            <?php elseif($_GET['type'] == '7'): ?>
            商家<?php endif; ?>
    </title>
    <style>
        .tao{
            display: block;
            width: 35px;
            height: 35px;
            background: transparent url("__STATIC__/css/img/wxh_bluezhe1.png") no-repeat scroll center center / 35px 35px;
            z-index: 90;
            position: absolute;
            top: 10px;
            left: 7px;
        }

        .zhe{
            display: block;
            width: 35px;
            height: 35px;
            background: transparent url("__STATIC__/css/img/wxh_bluezhe3.png") no-repeat scroll center center / 35px 35px;
            z-index: 90;
            position: absolute;
            top: 10px;
            left: 7px;
        }
    </style>
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



    <script>
        $(document).ready(function(e) {
            //主tab
            var h=$(window).height()-200;
            $('.yy_ul').css({
                'height':h,
                'overflow':'auto'
            })
            $('.yy_tabBarS li').click(function () {
                var i = $('.yy_tabBarS li').index($(this)[0]);
                var elm='<div class="c_black"></div>';

                if($('.c_black').length>0){
                    //$('.c_black').remove();
                }else{
                    $('.yy_tabS').append(elm);
                }


                $('.c_filter').find('ul').css('display','none');
                $(this).parents('.yy_tabS').find('ul').eq(i+1).slideDown();


                //black
                $('.c_black').click(function(){
                    $('.c_filter').find('ul').css('display','none');
                    $(this).remove();
                });
                //附近tab
                $('.yy_xl a').click(function () {
                    var i = $('.yy_xl a').index($(this)[0]);
                    var elm='<div class="c_black"></div>';

                    if($('.c_black').length>0){
                        //$('.c_black').remove();
                    }else{
                        $('.yy_xl').append(elm);
                    }
                    $(this).closest('.pull-left').siblings('.pull-right').find('div').css('display','none');
                    $(this).parents('.yy_xl').find('.yy_list').eq(i+0).show();

                    //black
                    $('.c_black').click(function(){
                        $('.pull-right').find('div').css('display','none');
                        $(this).remove();
                    })
                });
            });
        });
    </script>
</head>
<body class="bac-zxy">
<section class="margin-z">
    <div class="container zxy-Car">
        <div class="row yy_tabS">
            <ul class="nav nav-pills col-xs-12 margin-z yy_tabBarS" id="poifilter-bar" class="poifilter-bar">
                <li class="dropdown col-xs-4 margin-z yy_tebLiS">
                    <a class="dropdown-toggle" class="j-nav-item nav-item nav-right-sep" href="javascript:;" role="button">
                        <?php if($search['pname'] != ''): echo ($search['pname']); ?>
                            <?php else: ?>
                            全部<?php endif; ?>
                        <span class="caret"></span>
                    </a>
                </li>
                <li role="presentation yy_tebLiS" class="dropdown col-xs-4 margin-z">
                    <a class="dropdown-toggle" class="j-nav-item nav-item nav-right-sep" href="javascript:;" role="button" >
                        <?php if($search['town_name'] != ''): echo ($search['town_name']); ?>
                            <?php else: ?>
                            附近<?php endif; ?>
                        <span class="caret"></span>
                    </a>
                </li>
                <li role="presentation yy_tebLiS" class="dropdown col-xs-4 margin-z">
                    <a class="dropdown-toggle" class="j-nav-item nav-item"  href="javascript:;" role="button">
                        <?php if($search['zname'] != ''): echo ($search['zname']); ?>
                            <?php else: ?>
                            智能排序<?php endif; ?>
                        <span class="caret"></span>
                    </a>
                </li>
            </ul>
            <!--筛选-->
            <div class="c_filter">
            <ul style="display:none;" class="yy_ul">
                <?php if(is_array($item_list)): $i = 0; $__LIST__ = $item_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><li>
                        <?php if($val['type'] == 0): ?><a href="<?php echo U('Item/menu_merchant',array('id'=>$val['id'],'foot_id'=>$_GET['foot_id'],'type'=>$_GET['type'],'km'=>$_GET['km'],'town'=>$vo['id'],'z_name'=>$_GET['z_name']));?>" >
                        <?php elseif($val['type'] == 6): ?>
                            <a href="<?php echo U('Item/menu_merchant',array('id'=>$val['id'],'foot_id'=>$_GET['foot_id'],'type'=>$_GET['type'],'km'=>$_GET['km'],'town'=>$vo['id'],'z_name'=>$_GET['z_name']));?>" >
                        <?php elseif($val['type'] == 2): ?>
                            <a href="<?php echo U('Item/menu_merchant',array('id'=>$val['id'],'foot_id'=>$_GET['foot_id'],'type'=>$_GET['type'],'km'=>$_GET['km'],'town'=>$vo['id'],'z_name'=>$_GET['z_name']));?>" >
                        <?php elseif($val['type'] == 1): ?>
                            <a href="<?php echo U('Item/menu_merchant',array('id'=>$val['id'],'foot_id'=>$_GET['foot_id'],'type'=>$_GET['type'],'km'=>$_GET['km'],'town'=>$vo['id'],'z_name'=>$_GET['z_name']));?>">
                        <?php elseif($val['type'] == 3): ?>
                             <a href="<?php echo U('Item/city_express');?>" >
                        <?php elseif($val['type'] == 4): ?>
                             <a href="<?php echo U('Item/menu_merchant',array('id'=>$val['id'],'foot_id'=>$_GET['foot_id'],'p_type'=>2,'type'=>$_GET['type'],'km'=>$_GET['km'],'town'=>$vo['id'],'z_name'=>$_GET['z_name']));?>" >
                        <?php elseif($val['type'] == 5): ?>
                             <a href="<?php echo U('Item/menu_merchant',array('id'=>$val['id'],'foot_id'=>$_GET['foot_id'],'type'=>$_GET['type'],'km'=>$_GET['km'],'town'=>$vo['id'],'z_name'=>$_GET['z_name']));?>" >
                        <?php elseif($val['type'] == 7): ?>
                              <a href="<?php echo U('Item/hotel');?>">
                        <?php elseif($val['type'] == 8): ?>
                              <a href="<?php echo U('Item/menu_merchant',array('id'=>$val['id'],'foot_id'=>$_GET['foot_id'],'type'=>$_GET['type'],'km'=>$_GET['km'],'town'=>$vo['id'],'z_name'=>$_GET['z_name']));?>" >
                        <?php elseif($val['type'] == 9): ?>
                              <a href="<?php echo U('Item/menu_merchant',array('id'=>$val['id'],'foot_id'=>$_GET['foot_id'],'type'=>$_GET['type'],'km'=>$_GET['km'],'town'=>$vo['id'],'z_name'=>$_GET['z_name']));?>" ><?php endif; ?>
                        <?php echo ($val["name"]); ?>
                        </a>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
                <ul style="display:none;" class="yy_ul">
                    <li style="width:100%; height:200px; background-color:#ebebeb;" class="yy_xl">
                        <div class="pull-left" style="width:50%; background-color:#fff;">
                                <p><a href="#">附近</a></p>
                            <?php if(is_array($city_list)): $i = 0; $__LIST__ = $city_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><p><a href="<?php echo U('Item/menu_merchant',array('id'=>$_GET['id'],'foot_id'=>$_GET['foot_id'],'type'=>$_GET['type'],'town'=>$vo['id'],'z_name'=>$_GET['z_name']));?>"><?php echo ($vo["name"]); ?></a></p><?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                        <!--附近筛选-->
                        <div class="pull-right" style="width:50%; background-color:#ebebeb;">
                            <div class="yy_list">
                                <p><a href="<?php echo U('Item/menu_merchant',array('id'=>$_GET['id'],'foot_id'=>$_GET['foot_id'],'type'=>$_GET['type'],'tid'=>$_GET['tid'],'z_name'=>$_GET['z_name'],'km'=>1));?>">1km</a></p>
                                <p><a href="<?php echo U('Item/menu_merchant',array('id'=>$_GET['id'],'foot_id'=>$_GET['foot_id'],'type'=>$_GET['type'],'tid'=>$_GET['tid'],'z_name'=>$_GET['z_name'],'km'=>3));?>">3km</a></p>
                                <p><a href="<?php echo U('Item/menu_merchant',array('id'=>$_GET['id'],'foot_id'=>$_GET['foot_id'],'type'=>$_GET['type'],'tid'=>$_GET['tid'],'z_name'=>$_GET['z_name'],'km'=>5));?>">5km</a></p>
                                <p><a href="<?php echo U('Item/menu_merchant',array('id'=>$_GET['id'],'foot_id'=>$_GET['foot_id'],'type'=>$_GET['type'],'tid'=>$_GET['tid'],'z_name'=>$_GET['z_name'],'km'=>10));?>">10km</a></p>
                            </div>
                        </div>
                    </li>
                </ul>
                <ul style="display:none;" class="yy_ul">
                    <li><a href="<?php echo U('Item/menu_merchant',array('id'=>$_GET['id'],'foot_id'=>$_GET['foot_id'],'type'=>$_GET['type'],'z_name'=>'好评','tid'=>$_GET['tid'],'town'=>$_GET['town'],'km'=>$_GET['km']));?>">好评</a></li>
                    <li><a href="<?php echo U('Item/menu_merchant',array('id'=>$_GET['id'],'foot_id'=>$_GET['foot_id'],'type'=>$_GET['type'],'z_name'=>'最大优惠','tid'=>$_GET['tid'],'town'=>$_GET['town'],'km'=>$_GET['km']));?>">最大优惠</a></li>
                    <li><a href="<?php echo U('Item/menu_merchant',array('id'=>$_GET['id'],'foot_id'=>$_GET['foot_id'],'type'=>$_GET['type'],'z_name'=>'最高人气','tid'=>$_GET['tid'],'town'=>$_GET['town'],'km'=>$_GET['km']));?>">最高人气</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <!--位置--->
            <div class=" col-xs-12 Pull-z">
                <div class="col-xs-10  margin-z pull-left">
                    <?php echo ($_SESSION['address']); ?>附近
                </div>
                <div class="col-xs-2 margin-z">
                    <!--<a href="#" class="glyphicon glyphicon-repeat pull-right"></a>-->
                </div>
            </div>
            <!-----banner----->
            <div class=" col-xs-12 margin-z zxy-Commodities">
                <div class="bannerbox-z">
                    <div class="swiper-container banner-z">
                        <div class="swiper-wrapper">
                            <?php if(is_array($ad)): $i = 0; $__LIST__ = $ad;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><div class="swiper-slide"><img style="height: 133px;" src="data/upload/advert/<?php echo ($val["content"]); ?>" /></div><?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
            <!---产品--->
            <div class="col-xs-12 margin-z zxy_agencyhouse_box">
               <?php if($_GET['type'] == '5'): if(is_array($merchant_list)): $i = 0; $__LIST__ = $merchant_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; if($v["chang"] == '0'): ?><a class="pull-left" href="<?php echo U('Item/merchant_info',array('id'=>$v['id']));?>" class="yy_discount_A">
                               <?php else: ?>
                               <a class="pull-left" href="<?php echo U('Item/merchant_info',array('id'=>$v['id'],'type'=>4));?>" class="yy_discount_A"><?php endif; ?>
                       <div class="media  bgcolor_whitezxy">
                            <span class="pull-left" style="margin-right:10px;">
                               <img style="width:80px;height:65px;"  class="media-object" src="<?php echo attach(get_thumb($v['img'], '_s'), 'merchant');?>" alt="...">
                               <i class="zhe"></i>
                            </span>
                           <div class="media-body">
                               <h4 class="media-heading wfont14 color333"><?php echo ($v["title"]); ?><!--<span class="yy_piao"></span><span class="yy_zhe"></span>--></h4>
                               <div>
                                   <i id="zxy-star<?php echo ($v["id"]); ?>"></i>
                                   <script>
                                       $("#zxy-star<?php echo ($v["id"]); ?>").raty({
                                           start:"<?php echo ($v['start']); ?>",
                                           readOnly:true,
                                           path:"__STATIC__/js/jquery.raty-0.5/img/"
                                       })
                                   </script>
                                   <!--<span>305评价</span>
                                   <span class="consumption-z pull-right">人均￥118</span>-->
                               </div>
                               <div class="zxy_media_bodylast">
                                   <span><?php echo ($v["min"]); ?>km</span>
                               </div>
                               <div class="c_zkou"><em><?php echo ($v["discount"]); ?></em>折</div>
                           </div>
                       </div>
                       </a><?php endforeach; endif; else: echo "" ;endif; ?>
                <?php elseif($_GET['type'] == '6'): ?>
                   <?php if(is_array($merchant_list)): $i = 0; $__LIST__ = $merchant_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><a class="pull-left" href="<?php echo U('Item/merchant_info',array('id'=>$v['id']));?>">
                       <div class="media  bgcolor_whitezxy">
                            <span class="pull-left" style="margin-right:10px;">
                               <img style="width:80px;height:65px;" class="media-object" src="<?php echo attach(get_thumb($v['img'], '_s'), 'merchant');?>" alt="...">
                               <i class="tao"></i>
                            </span>
                           <div class="media-body">
                               <h4 class="media-heading wfont14 color333"><?php echo ($v["title"]); ?><!--<span class="yy_piao"></span>--></h4>
                               <div>
                                   <i id="zxy-star<?php echo ($v["id"]); ?>"></i>
                                   <script>
                                       $("#zxy-star<?php echo ($v["id"]); ?>").raty({
                                           start:"<?php echo ($v['start']); ?>",
                                           readOnly:true,
                                           path:"__STATIC__/js/jquery.raty-0.5/img/"
                                       })
                                   </script>
                                   <!--<span>305评价</span>
                                   <span class="consumption-z pull-right">人均￥118</span>-->
                               </div>
                               <div class="zxy_media_bodylast">
                                   <span><?php echo ($v["address"]); ?></span>
                                   <span><?php echo ($v["min"]); ?>km</span>
                               </div>
                           </div>
                       </div>
                       </a><?php endforeach; endif; else: echo "" ;endif; ?>
                <?php else: ?>
                   <!--遍历开始-->
                   <?php if(is_array($merchant_list)): $i = 0; $__LIST__ = $merchant_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; if($v["chang"] == '0'): ?><a href="<?php echo U('Item/merchant_info',array('id'=>$v['id']));?>" class="yy_discount_A">
                               <?php else: ?>
                               <a href="<?php echo U('Item/merchant_info',array('id'=>$v['id'],'type'=>4));?>" class="yy_discount_A"><?php endif; ?>
                       <div class="media  bgcolor_whitezxy">
                           <span class="pull-left" style="margin-right:10px;">
                               <img class="media-object" src="<?php echo attach(get_thumb($v['img'], '_s'), 'merchant');?>" style="width:80px;height:65px;">
                            </span>
                           <div class="media-body">
                               <h4 class="media-heading wfont14 color333"><?php echo ($v["title"]); ?></h4>
                               <div>
                                   <i id="zxy-star<?php echo ($v["id"]); ?>"></i>
                                   <script>
                                       $("#zxy-star<?php echo ($v["id"]); ?>").raty({
                                           start:"<?php echo ($v['start']); ?>",
                                           readOnly:true,
                                           path:"__STATIC__/js/jquery.raty-0.5/img/"
                                       })
                                   </script>
                                   <!--<span>305评价</span>-->
                                   <span class="consumption-z pull-right"><?php echo ($v["min"]); ?>km</span>
                               </div>
                               <div class="zxy_media_bodylast">
                                   <span><?php echo ($v["address"]); ?></span>
                               </div>
                           </div>
                       </div>
                       </a><?php endforeach; endif; else: echo "" ;endif; endif; ?>
                <?php if(empty($merchant_list)): ?><div class="tab-pane" style="margin-top: 15px;">
                        <img style=" display: block; width:95px; height:93px; margin:0 auto" src="../Style/static/images/cw_03.png" class="prompt">
                    </div>

                    <h5 class="text-center">
                        !抱歉亲,暂无数据
                    </h5>
                    <!-- <div class="media  bgcolor_whitezxy">
                         !抱歉亲,暂无数据
                     </div>--><?php endif; ?>
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
<!-- Initialize Swiper -->
<script>
    $(".accordion").accordion();
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        autoplay:5000
    });
</script>

</body>
</html>