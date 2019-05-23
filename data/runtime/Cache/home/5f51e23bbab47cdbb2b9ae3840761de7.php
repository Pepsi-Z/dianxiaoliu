<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />
    <title>首页</title>
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
        .yy_index .yy_iconBox li .wxh_font11{ font-size: 11px !important;}
    </style>
</head>
<script>
    $(function(){
        var myswiper = new Swiper('.swiper-container', {
            pagination: '.swiper-pagination',
            autoplay: 5000
        });

    });

    var name = "<?php echo ($_SESSION['city_name']); ?>";
    if(name == '' ){
        getLocation();
    }
    function getLocation()
    {
        if (navigator.geolocation)
        {
            navigator.geolocation.getCurrentPosition(showPosition,showError);
        }
        else{x.innerHTML="Geolocation is not supported by this browser.";}
    }
    function showPosition(position)
    {
        user_jw(position.coords.longitude,position.coords.latitude)
        /*x.innerHTML="Latitude: " + position.coords.latitude +
         "<br />Longitude: " + position.coords.longitude;*/
    }
    function showError(error)
    {
        switch(error.code)
        {
            case error.PERMISSION_DENIED:
                x.innerHTML="User denied the request for Geolocation."
                break;
            case error.POSITION_UNAVAILABLE:
                x.innerHTML="Location information is unavailable."
                break;
            case error.TIMEOUT:
                x.innerHTML="The request to get user location timed out."
                break;
            case error.UNKNOWN_ERROR:
                x.innerHTML="An unknown error occurred."
                break;
        }
    }

    function user_jw(j,w){
        var url = "<?php echo u('Index/get_city_name');?>";
        $.post(url,{jing:j,wei:w},function(data){
            $('#city_name').html(data.city);
            window.location.reload();//刷新当前页面.

        },'json');
    }

</script>
<body class="yy_bg">
<header>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-2"><a href="<?php echo U('Index/province');?>" class="pull-right" ><span id="city_name"><?php echo ($_SESSION['city_name']); ?></span><span class="glyphicon glyphicon-menu-down"></span></a></div>
            <form action="<?php echo U('Index/search');?>" method="post">
                <div class="col-xs-10">
                    <div>
                        <input type="text" name="keyword" value="<?php echo ($search["keyword"]); ?>" placeholder="商品搜索关键字" class="pull-left wfont12"/>
                        <button type="submit" class="btn btn-default pull-right"><span class="glyphicon glyphicon-search"></span></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</header>

<style>
    .gonggao{position:absolute;left:0 top:0;z-index:99998; width:100%; background:rgba(255,255,255,.5);padding:3px 0;color:#424542;}
    .gonggao span{position:absolute;left:0; top:2px; z-index:99999;}
    .gonggao marquee{margin-left:65px; line-height: 25px;}
    header .col-xs-10 > div input {
        color: #000000;
    }
</style>
<!------头部结束--------->
<!------主体开始--------->
<div class="gonggao"><span class="pull-left"><img src="static/images/laba.png">公告：</span><marquee scrollamount="3"  class="pull-left"><?php echo C('pin_fenxiang_about');?></marquee></div>

<section>
    <div class="container yy_index">
        <div class="row">
            <!--banner-->
            <div class="swiper-container yy_banner">
                <div class="swiper-wrapper">
                    <?php if(is_array($ad)): $i = 0; $__LIST__ = $ad;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><div class="swiper-slide"><img style="height: 133px;" src="data/upload/advert/<?php echo ($val["content"]); ?>" /></div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
            </div>
            <!--banner结束 -->
            <!--10个图标开始-->
            <ul class="col-xs-12 yy_iconBox">
                <?php if(is_array($item_list)): $i = 0; $__LIST__ = $item_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><li class="text-center pull-left">

                        <?php if($val['type'] == 0): if($val['status'] == 1){ ?>
                                    <a href="<?php echo U('Item/item_merchant',array('id'=>$val['id'],'type'=>$val['type']));?>" >
                                        <?php }else{?>
                                        <a href="#" >
                                            <?php }?>
                        <?php elseif($val['type'] == 6): ?>
                                <?php  if($val['status'] == 1){ ?>
                                     <a href="<?php echo U('Item/item_merchant',array('id'=>$val['id'],'type'=>$val['type']));?>" >
                                         <?php }else{?>
                                         <a href="#" >
                                             <?php }?>
                        <?php elseif($val['type'] == 2): ?>
                                 <?php  if($val['status'] == 1){ ?>
                                    <a href="<?php echo U('Item/item_merchant',array('id'=>$val['id'],'type'=>$val['type']));?>" >
                                        <?php }else{?>
                                        <a href="#" >
                                            <?php }?>
                        <?php elseif($val['type'] == 1): ?>
                                 <?php  if($val['status'] == 1){ ?>
                                    <a href="<?php echo U('Item/item_merchant',array('id'=>$val['id'],'type'=>$val['type']));?>">
                                        <?php }else{?>
                                        <a href="#" >
                                            <?php }?>
                        <?php elseif($val['type'] == 3): ?>
                                <?php  if($val['status'] == 1){ ?>
                                    <a href="<?php echo U('Item/city_express');?>" >
                                        <?php }else{?>
                                        <a href="#" >
                                            <?php }?>
                        <?php elseif($val['type'] == 4): ?>
                                 <?php  if($val['status'] == 1){ ?>
                                     <a href="<?php echo U('Item/item_merchant',array('id'=>$val['id'],'p_type'=>2,'type'=>$val['type']));?>" >
                                         <?php }else{?>
                                         <a href="#" >
                                             <?php }?>
                        <?php elseif($val['type'] == 5): ?>
                                 <?php  if($val['status'] == 1){ ?>
                                    <a href="<?php echo U('Item/menu_merchant',array('type'=>5,'openid'=>$_GET['openid']));?>">
                                        <?php }else{?>
                                        <a href="#" >
                                            <?php }?>
                                <!--<a href="<?php echo U('Item/item_merchant',array('id'=>$val['id'],'type'=>$val['type']));?>" >-->
                        <?php elseif($val['type'] == 7): ?>
                                  <?php  if($val['status'] == 1){ ?>
                                         <a href="<?php echo U('Item/hotel');?>">
                                             <?php }else{?>
                                             <a href="#" >
                                                 <?php }?>
                        <?php elseif($val['type'] == 8): ?>
                                   <?php  if($val['status'] == 1){ ?>
                                        <a href="<?php echo U('Item/item_merchant',array('id'=>$val['id'],'type'=>$val['type']));?>" >
                                            <?php }else{?>
                                            <a href="#" >
                                                <?php }?>
                        <?php elseif($val['type'] == 9): ?>
                                  <?php  if($val['status'] == 1){ ?>
                                     <a href="<?php echo U('Item/item_merchant',array('id'=>$val['id'],'type'=>$val['type']));?>" >
                                         <?php }else{?>
                                    <a href="#" >
                                          <?php } endif; ?>
                            <i><img src="<?php echo attach(get_thumb($val['img'], '_m'),'item_cate');?>" /></i>
                            <span class="wxh_font11"><?php echo ($val["name"]); ?></span>
                        </a>


                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <!--10个图标结束-->
            <!--小六推荐开始-->
            <div class="col-xs-12 yy_recommend">
                <div class="yy_bt">
                    <div class="col-xs-6 pull-left"><img src="__STATIC__/css/img/yy_recommend_bt.png"/></div>
                    <div class="col-xs-6 text-right"><a href="<?php echo U('Item/item_cate');?>">查看更多<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a></div>
                </div>
                <div class="yy_content">

              <?php if(is_array($tuijian)): $i = 0; $__LIST__ = $tuijian;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><div class="col-xs-4">
                        <?php if($val["chang"] == '0'): ?><a href="<?php echo U('Item/content',array('id'=>$val['id']));?>">
                                <?php else: ?>
                                <a href="<?php echo U('Item/content',array('id'=>$val['id'],'type'=>4));?>"><?php endif; ?>

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <img style="height: 66px;" src="<?php echo attach(get_thumb($val['img'], '_s'), 'item');?>"/>
                                </div>
                                <div class="panel-body">
                                    <h1 class="text-center"><?php echo ($val["title"]); ?></h1>
                                    <p>
                                        <span class="pull-left">￥<i><?php echo ($val["tc_price"]); ?></i></span>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
            <!--小六推荐结束-->
            <!--猜你喜欢开始-->
            <div class=" col-xs-12 yy_like">
                <div class="col-xs-12 yy_bt">
                    <div class="col-xs-6">猜你喜欢</div>
                    <div class="col-xs-6 text-right"><!--<a href="#">查看更多<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a>--></div>
                </div>
                <?php if(is_array($love)): $i = 0; $__LIST__ = $love;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><div class="col-xs-12">
                        <?php if($val["chang"] == '0'): ?><a href="<?php echo U('Item/content',array('id'=>$val['id']));?>">
                            <?php else: ?>
                            <a href="<?php echo U('Item/content',array('id'=>$val['id'],'type'=>4));?>"><?php endif; ?>

                            <div class="media">
                                <div class="media-left media-middle pull-left">
                                    <img class="media-object" style="height: 80px;" src="<?php echo attach(get_thumb($val['img'], '_s'), 'item');?>" alt="...">
                                </div>
                                <div class="media-body pull-right">
                                    <div><h4 class="media-heading pull-left"><?php echo ($val["title"]); ?></h4><span class="pull-right"><em><?php echo ($val["min"]); ?></em>km</span></div>
                                    <p><em><?php echo ($val["item_desc"]); ?></em></p>
                                    <div class="yy_bottom">
                                        <span>￥<b><?php echo ($val["tc_price"]); ?></b></span>
                                        <del>原价：<i><?php echo ($val["price"]); ?></i>元</del>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
          </div>
            <!--猜你喜欢结束-->
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