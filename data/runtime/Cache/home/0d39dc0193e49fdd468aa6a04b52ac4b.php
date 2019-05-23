<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
 <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />
<title>购物车</title>
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



<link href="../Style/static/css/reset.css" rel="stylesheet" type="text/css">
<link href="../Style/static/css/header.css" rel="stylesheet" type="text/css">
<style>
    .wxh_shop_box b{ border-bottom:1px solid #E9E9E9;}
</style>


<script type="text/javascript" src="../Style/static/js/jquery-1.9.1.min.js"></script>
    <script src="__STATIC__/js/jquery.raty-0.5/js/jquery.raty.js"></script>
    <link rel="stylesheet" type="text/css" href="__STATIC__/css/home/sweet-alert.css">
    <script src="__STATIC__/js/sweet-alert.js"></script>
    <script>
//数量加减
function add(id,goods_stock){
    var val =$("#number"+id).val()
    var stock = goods_stock;
    var i= parseInt(val);
    if(val < stock){
        i=i+1;
        $("#number"+id).val(i);
    }else{
        layer.msg('亲,已经没有库存');
    }
    }
function subtract(id,goods_stock){
    alert(111);
    var val =$("#number"+id).val()
    var stock = goods_stock;
    var i= parseInt(val);
     if(i >1){
        i=i-1;
        $("#number"+id).val(i);
        }
    }

function num(id){
	$('#span_num'+id).html('x '+$('#number'+id).val());
}
$(function(){
	//编辑
	$('.shopping_div input.bnmb ').click(function(){
		var txt=$(this).val();
		if(txt=='完成'){
            var car_id = $(this).attr('mid');
            var c_num = $('#span_num'+car_id).text().substr(1);
            $.post("<?php echo U('Shopcart/add_num');?>",{ id:car_id,num:c_num},function(data){
                if(data == 1){
                    location.href="<?php echo U('Shopcart/index');?>";
                }
            });

			$(this).val('编辑')
			$(this).parents('.shopping_box').find('ul').css('display','none');
			$(this).parents('.shopping_box').find('.span_num').css('display','block');
		}else{
			$(this).val('完成')
			$(this).parents('.shopping_box').find('ul').css('display','block');
			$(this).parents('.shopping_box').find('.span_num').css('display','none');

		}

	})
	   //全选
	    $("#checkboxall").click(function() {
            $("input[name='subBox[]']").prop("checked", this.checked);

            var $subs = $("input[name='subBox[]']");
            var str = '结算（'+$subs.filter(":checked").length+')';
            $('#jiesuan').val(str);
            sum();
        });
        $("input[name='subBox[]']").click(function() {
        	var $subs = $("input[name='subBox[]']");
            var str = '结算（'+$subs.filter(":checked").length+')';
            $('#jiesuan').val(str);
            $("#checkboxall").prop("checked" , $subs.length == $subs.filter(":checked").length ? true :false);
        });
        //价格累加
        $("input[name='subBox[]']").bind('click',sum);
        $('.bnmb').bind('click',sum);
        function sum(){
            var total = 0;
            $("input[name='subBox[]']:checked").each(function(){
                var  price1 = parseFloat( $(this).parent().siblings('div').find('p span').text().substr(1));
                //alert(price1);

                var  quantity = parseFloat($(this).siblings('div.wxh_right_box').find('p').find('span.span_num').text().substr(1));
                //alert(quantity);
                total = total + ( price1 * quantity);
            });
            $('#heji').text(total.toFixed(2));
        }
        sum();

})

</script>
<style>
		body{ height: 100%;}
		.promptbox{ margin-top:10%;}
		h1{ font-weight:normal; color:#000; text-align:center; margin-top:5%;}
		.prompt{ width:95px; height:93px; display:block; margin:0 auto;}
	</style>
</head>
<body>
<?php if(empty($item)): ?><div class="promptbox">
	<img src="../Style/static/images/cw_03.png" class="prompt">
</div>
<h1>您的购物车还没有商品！</h1><?php endif; ?>
        <form id="form2" action="<?php echo U('Shopcart/jiesuan');?>" method="post">
        <div style="margin-bottom:50px; height:auto; padding-bottom:10px;" class="wxh_shop_box">

        <?php if(is_array($item)): $i = 0; $__LIST__ = $item;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><b><?php echo ($v["title"]); ?></b>
            <?php if(is_array($v["goods"])): $i = 0; $__LIST__ = $v["goods"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><div class="shopping_box wxh_box" style="position:relative;">
                    <a href="javascript:;">
                    <input type="checkbox" name="subBox[]" value="<?php echo ($val["id"]); ?>" id="checkbox<?php echo ($val["id"]); ?>" class="lxt_choose lxt_choose1 " />
                    <label for="checkbox<?php echo ($val["id"]); ?>" class=""></label>
                    <img src="<?php echo get_img($val[img],'item');?>" class="right_disimg"/>
                        <div class="wxh_right_box fl">
                            <p class="shopping_p wxh_shopping_ps">
                                <span class="wxh_firstspan"><?php echo (strip_tags(mb_substr($val["title"],0,16,'utf-8'))); ?></span>
                                <span class="span_num" id="span_num<?php echo ($val["id"]); ?>">x<?php echo ($val["num"]); ?></span>
                            </p>



                            <ul class="shopping_ul" style="display:none;">
                                <li class="fitst_bg cursor" onClick="subtract(<?php echo ($val["id"]); ?>,<?php echo ($val["goods_stock"]); ?>);"></li>
                                <li><input type="text" value="<?php echo ($val["num"]); ?>" onkeyup="this.value=this.value.replace(/[^1-9.]/g,'1')" onafterpaste="this.value=this.value.replace(/[^1-9.]/g,'1')"  id="number<?php echo ($val["id"]); ?>" name='number<?php echo ($val["id"]); ?>'/></li>
                                <li class="three_bg cursor"  id="subtract" onClick="add(<?php echo ($val["id"]); ?>,<?php echo ($val["goods_stock"]); ?>);" ></li>
                            </ul>
                        </div>
                    </a>
                    <div class="shopping_div wxh_shopping_div">
                        <p class="">价格：<span class="wxh_color">￥<?php echo ($val["price"]); ?></span></p>
                        <input type="button" value="编辑	" class="bnmb" mid = "<?php echo ($val["id"]); ?>" onClick="num(<?php echo ($val["id"]); ?>);"/>
                        <input type="button" value="删除" data-id="<?php echo ($val["id"]); ?>" class="delete"/>
                    </div>
                </div><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
        </div>

        <?php if(!empty($item)): ?><div class="shopping_box1 wxh_shopping_box1">
            <div class="fl">
        	<input type="checkbox" id="checkboxall"  class="lxt_choose lxt_choose1" />
        	<label for="checkboxall"><p style=" position: absolute; left:30px; top:0px; width:30px;">全选</p></label>
            </div>
            <div class="sellement_box wxh_sellement_box  fr">
            <div class="Settlement_div fl">
            <p class="left_juli" style="padding-top:5px;">合计：<span id="heji" class="wxh_span1">&yen; 0</span></p></div>
            <div class="settlement_div1 wxh_settlement_div1 fr"><input id="jiesuan" type="button" value=" 结算(0)" class="jiesuan_button wxh_jiesuan_button"/></div>
           	</div>
        </div><?php endif; ?>
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
</html>
<script>
    $('.delete').click(function(){
        var id = $(this).attr('data-id');
//        alert(id)
        if(window.confirm("您确定要删除该商品吗?")){
            $.post("<?php echo U('Shopcart/delete');?>",{ id:id },function(data){
//                alert(data)
                if(data == 1){
//                    swal('删除成功');
                    location.href="<?php echo U('Shopcart/index');?>";
                }else{
//                    swal('删除失败')
                }
            });
        }

    });
</script>
<script>
    $('#jiesuan').click(function(){
        var attr = $(this).attr('checked');
        var c = $("input[type=checkbox]:checked").length;
        if(c > 0){
            $("#form2").submit();
        }else{
            layer.msg('请选择要结算的商品');
            return;

        }

    })
</script>