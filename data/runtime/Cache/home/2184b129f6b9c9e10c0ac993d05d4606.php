<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />
    <title>确认订单</title>
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



    <link href="__STATIC__/css/home/bootstrap.min.css" rel="stylesheet">
    <link href="__STATIC__/css/home/main.css" rel="stylesheet">
    <script src="__STATIC__/js/layer/layer.m.js"></script>
    <style>
        .lxt-confirm-b{margin-top:0;}
        .lxt-confirm-d{border-bottom:1px solid #e8e8e8; background:#fff;}
        .lxt-confirm-c{border:none;}
        .coupon_box .tab-pane ul{width:94%; margin:10px auto; overflow:hidden; }
        .layermchild h3{height:30px;line-height: 30px;}


         .lxt_choose{ display:none !important;}
        .lxt_choose + label{ display:block;  width:18px; height:18px; background: url(__STATIC__/css/img/choose0.png) no-repeat center; background-size:100%;}
        .lxt_choose1:checked + label {width:18px; height:18px; background: url(__STATIC__/css/img/choose1.png) no-repeat center; background-size:100%;}
        #content .enter,#content .login_btn{ background-image: none; line-height: 30px;}
        .make_sure p{width: 80%; margin:0 auto !important;}
        .make_sure p a{ background-color: #40C780 !important;}


    </style>
</head>
<body class="lxt-color">
<section class="wxh_bottom_100">
<form id="form2" method="post" action="<?php echo U('Order/pay');?>">
    <div class="container lxt-container">

        <div id="address" address="<?php echo ($address["consignee"]); ?>" class="row lxt-confirm-a ">

            <?php if(empty($address["consignee"])): ?><div class="col-xs-10 "> <em>亲！还没有收货地址! 请先去<a href="<?php echo U('User/addresschange');?>"><b>添加</b></a>吧</em></div>
                <?php else: ?>
                <a href="<?php echo U('User/address',array('mk' =>$mk));?>">
                    <div class="col-xs-10 "><p>收货人:<i><?php echo ($address["consignee"]); ?></i><em><?php echo ($address["mobile"]); ?></em></p><h5>地&nbsp;&nbsp;址：<i><?php echo ($address["saddress"]); echo ($address["address"]); ?></i></h5></div>
                    <div class="col-xs-2 text-right"><span class="glyphicon glyphicon-menu-right"></span></div>
                </a>
                <!--用户收货地址-->
                <input type="hidden" name="address_id" value="<?php echo ($address["id"]); ?>"><?php endif; ?>
        </div>
        <?php if(is_array($item)): $i = 0; $__LIST__ = $item;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="row lxt-confirm-c" style="border-bottom:1px;padding-left:15px; margin-top:10px;">商家：<?php echo ($v["title"]); ?></div>
            <?php if(is_array($v["goods"])): $i = 0; $__LIST__ = $v["goods"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><div class="row lxt-confirm-b">

                <a href="#">
                    <div class="col-xs-4"> <img style="width: 88px;height: 80px;" src="<?php echo attach(get_thumb($val[img], '_m'),'item');?>" alt="..."> </div>
                    <div class="col-xs-8">
                        <h4><?php echo (strip_tags(mb_substr($val["title"],0,16,'utf-8'))); ?></h4>
                        <?php if($val['guige'] != '' or $val['guige'] != 0 ): ?><span class=" block">规格：<span id="guige"><?php echo ($val["guige"]); ?></span></span><?php endif; ?>
                        <span class="block font_red">&yen;<span id="s_price" class="font_red"><?php echo ($val["price"]); ?></span></span>
                    </div>
                </a>
            </div>
            <div class="row lxt-confirm-d">
                <div class="col-xs-6">
                    <p>数量：</p>
                </div>
                <div class="col-xs-6 text-right">
                    <p>X<b><?php echo ($val["num"]); ?></b></p>
                </div>
            </div>






                <input type="hidden" name="id[]" value="<?php echo ($val["id"]); ?>">
                <input type="hidden" name="number[]" value="<?php echo ($val["num"]); ?>">
                <input type="hidden" name="price[]" value="<?php echo ($val["price"]); ?>">
                <input type="hidden" name="guige[]" value="<?php echo ($val["guige"]); ?>">
                <input type="hidden" name="type" value="4">
                <input type="hidden" name="card" value="1"><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>

    <!--    <div  class="row lxt-confirm-c" style="margin-top:10px;">
            &lt;!&ndash;//href="<?php echo U('Card/pay_cards',array('type'=>'gouwuche'));?>"&ndash;&gt;
            <a id="yhj" href="javascript:;">
                <div class="col-xs-6">
                    <p id="yh" yh="<?php echo ($cards_info["money"]); ?>">优惠券：</p>
                    <input type="hidden" id="gid" name="gid" value=""/>
                </div>
                <div class="col-xs-6 text-right">
                    <span class="glyphicon glyphicon-menu-right"></span>
                </div>
            </a>
        </div>-->



    </div>

   <!-- <section class="lxt-settle">
        <div class="row">
            <div  class="col-xs-8"><p class="lxt-settle-pa lxt-cart-pa">合计:<i>￥40.00</i></p></div>
            <div  class="col-xs-4 text-center lxt-cart-pb"><a href="#">确认</a></div>
        </div>
        </div>
    </section>-->

 <!--   <div  class="row lxt-confirm-c" style="margin-top:5px;" >
        <div class="col-xs-4">
            <p>订单备注：</p>
        </div>
        <input style="border:0px;" name="beizhu" placeholder="请输入备注内容" type="text" value=""  />
    </div>-->

    <div class="col-xs-12" style="height: 35px; line-height: 35px;background: #fff; margin-top: 10px;">
        <span class="pull-left">订单备注：</span>
        <input class="pull-left" style="border:0px;width:70%; height: 33px;" name="beizhu" placeholder="请输入备注内容" type="text" value=""  />
    </div>

    <!--防止表单重复提交-->
    <?php $code = mt_rand(0,1000000); ?>
    <input type="hidden" name="code" value="<?php echo ($code); ?>"/>

    <!-----footer开始-------->
    <div class="container zxy-Tail">
        <div class="row ">
            <div class="col-xs-12">
                <span class="pull-left text-left" >合计：<b>￥</b><b id="all_add"><?php echo sprintf('%.2f',$total)?></b></span>
                <input id="submit1" STYLE="border:none; border-radius:0; height:50px; line-height:50px; background:#40C780; color:#fff;" type="button" value="提交订单" class="btn btn-default pull-right" />
                <input type="hidden" id="h_total" name="total" value="<?php echo sprintf('%.2f',$total) ?>">
                <input type="hidden" name="pay" value="1">
                <input id="now_price" type="hidden" value="<?php echo sprintf('%.2f',$total)?>">
            </div>
        </div>
    </div>

           <!-- <div class="col-xs-12" style="background:#fff;padding:5px;margin-top:10px;border-bottom:1px solid #e5e5e5;">
                <div class="row">
                    <div class="col-xs-2 text-center"><img style="height: 30px;" src="__STATIC__/css/img/ziti.png" class="lxt-pay-img"></span></div>
                    <div class="col-xs-8 "><p>自提</p></div>
                    <div class="col-xs-2 text-center">
                        <input type="radio" name="freetype" value="0" id="checkbox1" class="lxt_choose lxt_choose1 lxt_chooseBox" checked="checked" />
                        <label class="lxt-all2" for="checkbox1"></label>
                    </div>
                </div>
            </div>-->

            <div class="col-xs-12"style="background:#fff;padding:5px;">
                <div class="row lxt-pay">
                    <div class="col-xs-2 text-center" style="padding-left:20px;"><img style="height: 30px;" src="__STATIC__/css/img/peisong.png" class="lxt-pay-img"></span></div>
                    <div class="col-xs-8 "><p>配送</p></div>
                    <div class="col-xs-2 text-center">
                        <input type="radio" name="freetype" value="1" id="checkbox4" checked="checked" class="lxt_choose lxt_choose1 lxt_chooseBox" />
                        <label class="lxt-all2" for="checkbox4"></label>
                    </div>
                </div>
            </div>


    <style>
        .zxy-Tail .col-xs-12{ padding: 0;}
        .zxy-Tail .col-xs-12 span{ padding: 0 15px;}
        .zxy-Tail b{ color: #f00;}
        .zxy-Tail{width:100%; left:0; position: fixed;bottom:46.5px;z-index: 9000; height: 50px; line-height: 50px; background: #fff; clear: both; overflow: hidden;}
        .zxy-Tail input[type="button"]{ width: 30%; height: 50px !important; padding: 0; font-size: 14px;}
        .lxt-confirm-d p{ margin-bottom: 5px;}
        .lxt-confirm-c{ padding-top: 5px;}
        .lxt-confirm-b, .lxt-confirm-a{ padding: 5px 0;}
        .lxt-confirm-b, .lxt-confirm-a{ margin-top: 0;}
        .block{ display:block;}
        .font_red{ color: #f00 !important;}
        .wxh_bottom_100{ margin-bottom: 100px; overflow-x:hidden; }
        .lxt-pay{}
    </style>
</form>
</section>
<!-----footer结束-------->
<script type="text/html" id="J_cards">
    <section class="coupon_box">
    <div role="tabpanel" class="tab-pane active unused" id="home">

    </div>
        </section>
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
<script>
    $("#submit1").click(function(e){
        var address = $('#address').attr('address'); //收货地址
        var s_price = Number($('#s_price').html());
        var p_price = Number($('#peisong').html());
        var total_price = (s_price+p_price).toFixed(2);
        var yh_price = Number($('#yh').attr('yh'));
        if(address == ''){
            alert('请填写收货地址');
            return false;
        }
        if(yh_price > total_price){
            alert('请选择小于总价的优惠劵');
            return false;
        }else{
            $("#form2").submit();
        }

    })
    $('#home').html('');
    var h=$(window).height();
    $(window).resize(function(){
       h=$(window).height();
    });
    $('#yhj').click(function(){
        var html=$('#J_cards').html();
        var page = layer.open({
            title:'优惠券',
            type: 1,
            content: html,
            style: 'width:100%; height:'+h+'px; border:none; overflow-y:auto; background:#fff;'
        });

        $.ajax({
            type: "get",
            url: "<?php echo U('Card/gw_pay_cards');?>",
            dataType: "json",
            success: function (data) {
                var news_html = "";
                if(data){
                    $.each(data,function(i,v){
                        news_html += "<ul><li class=\"col-xs-4 text-center\"><p>￥<span>"+ v.money+"</span></p><p>(单次现用一张)</p></li>"+
                                   "<li class=\"col-xs-6 text-center\"><div><p>"+ v.name+"优惠券</p><p>"+v.start+"-"+ v.end+"</p></div></li>"+
                                   "<li class=\"col-xs-2 text-center\"><a class=\"lj\" y_price="+ v.money+" y_id="+ v.id+" href=\"javascript:;\">立即<br/>使用</a></li></ul>";


                    });
                }else{
                    news_html = "<ul><li class=\"col-xs-4 text-center\"><p><span>暂无优惠劵</span></p><p></p></li>";
                }
                $('#home').append(news_html);
                var all_add = $('#now_price').val();
                var p_price = 0;
                $('.lj').click(function(){
                    $(".ps").each(function(){
                        p_price += Number($(this).html());
                    });
                    var j_price = $(this).attr('y_price'); //购物卷的价钱
                    if(Number(j_price) > p_price){
                        alert('你选择优惠劵的面值大于配送费');
                        return false;
                    }else{
                         $('#gid').val($(this).attr('y_id'));
                         var P =Number(all_add)-Number(j_price);
                         $('#all_add').html(P);
                         $('#h_total').val(P);
                    }
                    layer.closeAll()
                })
            }
        });




    })

</script>

</html>