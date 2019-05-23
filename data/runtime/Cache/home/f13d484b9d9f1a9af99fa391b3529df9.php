<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />
    <title>商品详情</title>
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
        .wxh_shopping_textimgbox img{max-width:100%;}
        .wxh_textdetails_box h5{height:auto;}
        .wxh_fontoverflow{white-space: normal;overflow:auto;}
        h1 span,h2 span,h3 span,h4 span{white-space: normal!important;}
        .w_details div,.w_details p{ word-break: break-all; width: 100%!important;}
    </style>
</head>



<body class="wxh_bggreycolor">
<?php if($_GET['type'] == 4 ): ?><header class="wxh_shopping_header">
        <div class="container">
            <div class="row">
                <div class="col-xs-2"><a href="javascript:history.go(-1)" class="text-left"><span class="glyphicon glyphicon-menu-left"></span></a></div>
                <div class="col-xs-8 text-center">商品详情</div>
            </div>
        </div>
    </header><?php endif; ?>
<!--wxh_section_30 加返回头部的时候加此class名-->
<section <?php if($_GET['type'] == 4 ): ?>class="wxh_disbottom wxh_section_30"<?php else: ?>class="wxh_disbottom"<?php endif; ?> >
    <div class="container">
        <div class="row">
            <div class="swiper-container wxhindex_banner">
                <div class="swiper-wrapper">
                    <?php if(is_array($item_img)): $i = 0; $__LIST__ = $item_img;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><div class="swiper-slide"><img style="height: 166px;" src="<?php echo attach(get_thumb($val['url'], '_b'), 'item');?>" /></div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
            </div>
            <div class="col-xs-12 wxh_textdetails_box">
                <h5 class="wxh_fontoverflow"><?php echo ($info["title"]); ?></h5>
                <p>
                    <span class="wxh_redcolor">￥<span id="add_price"><?php echo ($info["tc_price"]); ?></span></span>

                    <span class="pull-right wxh_font12">配送价：<span><del>￥<?php echo ($info["price"]); ?></del></span></span>
                </p>


                <!--商品规格-->
                <?php if($_GET['type'] == 4 ): ?><div class="clearfix wxh_a_box10 lh_a_box10">
                            <?php if(is_array($attr_guge)): $i = 0; $__LIST__ = $attr_guge;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><div class="col-xs-3 text-center ">
                                    <a href="javascript:;" data-price="<?php echo ($val["attr_value"]); ?>" class="block liang_payment"><?php echo ($val["attr_name"]); ?></a>
                                </div><?php endforeach; endif; else: echo "" ;endif; ?>
                    </div><?php endif; ?>


                <div class="add_num">
                    <ul class="nav pull-left">
                        <li><a href="javascript:;">-</a></li>
                        <li class="li_input">
                            <input type="text" readonly="readonly" value="1" name="num" id="num" onkeyup="this.value=this.value.replace(/[^1-9.]/g,'1')" onafterpaste="this.value=this.value.replace(/[^1-9.]/g,'1')" /></li>
                        <li><a href="javascript:;">+</a></li>
                    </ul>

                    <a id="tijiao" href="javascript:;" url="<?php echo U('Item/queren');?>" sid="<?php echo ($info["id"]); ?>" class="wxh_shoppingcart pull-right">
                        <input type="button" value="立即订购"/>
                    </a>
                </div>
            </div>
            <div class="wxh_shopping_textimgbox">
                <div class="col-xs-12 text-center">图文详情</div>
                <div class="col-xs-12 w_details">
                    <?php echo ($info["info"]); ?>
                </div>
            </div>

        </div>
    </div>
</section>

<?php if($_GET['type'] == 4 ): ?><div class="container wxh_shopping_box10">
            <div class="row">
                <div id="gwc_add" class="col-xs-8 text-center" style="cursor: pointer;">加入购物车</div>
                <a href="<?php echo U('Shopcart/index',array('foot_id'=>6));?>" class="col-xs-4 block text-center" >
                    <img src="__STATIC__/css/img/wxh_font12.png"/>
                </a>
            </div>
    </div><?php endif; ?>


<style>
    .wxh_shopping_box10{ width: 100%; position: fixed; height: 50px; line-height: 50px; bottom:46.5px; background: #fff;}
    .wxh_shopping_box10 .col-xs-8{background:#66ccee !important; color: #fff;}
    .wxh_shopping_box10 a{ background: #333;}
    .wxh_shopping_box10 img{ width: 19px; height: 17px;}
    .wxh_a_box10>div{ padding-left: 0;}
    .wxh_a_box10 a{ display: block; border: 1px solid #dcdcdc; line-height: 20px; font-size: 12px; margin-bottom: 10px; color: #333;}
    .wxh_a_box10 a.active{ background: #66ccee; color: #fff;}
</style>
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
<?php  $user = $_SESSION['username']; ?>

<script>
    $(document).ready(function() {
        $('.liang_payment').click(function(){
            var g_price = $(this).data('price');
            $('#add_price').html('');
            var price = g_price.toFixed(2)
            $('#add_price').html((price));
            $('.liang_payment').removeClass("active");
            $(this).addClass("active");

        })
    });
</script>

<script>
    $('#tijiao').click(function(){
        var hrefs;

        var attr_guge = "<?php echo ($attr_guge); ?>";


        var url = $(this).attr('url');
        var id  = $(this).attr('sid');
        var num =  $(".li_input input").val();
        var guige = $('.active').html();
        var price = $('#add_price').html(); //价钱


        var login = "<?php echo ($_SESSION['user']['id']); ?>";
        var login_url = "<?php echo U('Member/login');?>";

        var login_address = "<?php echo U('User/address');?>";

        var user_address = "<?php echo U('User/wanshan',array('status' =>1));?>";
        var type = "<?php echo ($_GET['type']); ?>"; //平台类型
        var address = "<?php echo ($address); ?>";
        var username = "<?php echo ($_SESSION['user']['name']); ?>";
        if(guige == undefined){
            guige = '';
        }

        if(login){
            if(username){
                if(type == '4'){


             if(attr_guge){
                 //请选择商品规格
                 if(guige){
                     if(address){
                         hrefs='&id='+id+'&num='+num+'&price='+price+'&type='+type+'&guige='+guige;
                         $(this).attr("href",url+hrefs);
                     }else{
                         layer.msg('您还没有填写收货地址！！');
                         $(function () {
                             setTimeout(function(){window.location=login_address;},1000);
                         })
                     }
                 }else{
                     layer.msg('请选择商品规格！！');
                     return false;
                 }

             }else{
                 if(address){
                     hrefs='&id='+id+'&num='+num+'&price='+price+'&type='+type+'&guige='+guige;
                     $(this).attr("href",url+hrefs);
                 }else{
                     layer.msg('您还没有填写收货地址！！');
                     $(function () {
                         setTimeout(function(){window.location=login_address;},1000);
                     })
                 }

             }



                }else{
                    hrefs='&id='+id+'&num='+num+'&price='+price;
                    $(this).attr("href",url+hrefs);
                }

            }else{
                layer.msg('你还没有设置自己的姓名！！');
                setTimeout(function(){window.location=user_address;},1000);
            }


        }else{
            layer.msg('您还没有去登录！！');
            $(function () {
                setTimeout(function(){window.location=login_url;},1000);
            })
        };




    });
</script>
<script>
    $(function(){
        //加减
        var value=1;
        $(".add_num li:last").click(function(){
            var oldValue=parseInt($(".li_input input").val());
            value = oldValue+1;
            $(".li_input input").val(value);
        });

        $(".add_num li:first").click(function(){
            var oldValue=parseInt($(".li_input input").val());
            if(oldValue>1){
                value = oldValue - 1;
            }
            $(".li_input input").val(value);
        });


        var goodId = "<?php echo ($info["id"]); ?>";
        var num = $(".li_input input").val();

        $('#gwc_add').click(function(){

             var price = $('#add_price').html(); //商品价钱
             var num = $(".li_input input").val(); //商品数量
             var guige = $('.active').html(); //商品规格
             var login = "<?php echo ($_SESSION['user']['id']); ?>";
             var login_url = "<?php echo U('Member/login');?>";





            var login_address = "<?php echo U('User/address');?>";

            var user_address = "<?php echo U('User/wanshan',array('status' =>1));?>";
            var type = "<?php echo ($_GET['type']); ?>"; //平台类型
            var address = "<?php echo ($address); ?>";
            var username = "<?php echo ($_SESSION['user']['name']); ?>";

            var attr_guge = "<?php echo ($attr_guge); ?>";

            if(login){
                if(username){

                    if(attr_guge){
                        //请选择商品规格
                        if(guige){
                            if(address){
                                $.post("<?php echo U('Shopcart/add_cart');?>",{goodId:goodId,num:num,guige:guige,price:price},function(data){
                                    layer.msg(data);
                                    setTimeout(function(){window.location.reload();},1000);
                                },"html");
                            }else{
                                layer.msg('您还没有填写收货地址！！');
                                $(function () {
                                    setTimeout(function(){window.location=login_address;},1000);
                                })
                            }

                        }else{
                            layer.msg('请选择商品规格！！');
                            return false;
                        }

                    }else{
                        if(address){
                            $.post("<?php echo U('Shopcart/add_cart');?>",{goodId:goodId,num:num,guige:guige,price:price},function(data){
                                layer.msg(data);
                                setTimeout(function(){window.location.reload();},1000);
                            },"html");
                        }else{
                            layer.msg('您还没有填写收货地址！！');
                            $(function () {
                                setTimeout(function(){window.location=login_address;},1000);
                            })
                        }

                    }




                }else{
                    layer.msg('你还没有设置自己的姓名！！');
                    setTimeout(function(){window.location=user_address;},1000);
                }


            }else{
                layer.msg('您还没有去登录！！');
                $(function () {
                    setTimeout(function(){window.location=login_url;},1000);
                })
            };

        });





    });
</script>

<script>
    $(".accordion").accordion();
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        slidesPerView: 'auto',
        paginationClickable: true,
        autoplay:5000
    });
</script>
<style>
    .wxh_shopping_textimgbox img{ width: 100%;}
</style>
</body>
</html>