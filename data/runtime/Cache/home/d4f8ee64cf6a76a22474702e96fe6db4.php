<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />
<title>积分商品详情</title>
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



    <script type="text/javascript" src="../Style/static/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="../Style/static/js/layer.m/layer.m.js"></script>
    <link href="../Style/static/css/reset.css" rel="stylesheet" type="text/css">

    <link href="../Style/static/css/header.css" rel="stylesheet" type="text/css">
    <link href="../Style/static/css/menu.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../Style/static/js/nav4.js"></script>

    <style>
        .add_jf{}
        .add_num ul{border: 1px solid #ddd; overflow: auto; border-radius: 5px;}
        .add_num li{float: left; width: 40px; height: 24px; line-height: 20px; text-align: center; overflow: hidden;}
        .add_num li input{ width: 40px; height: 24px; box-sizing: border-box; text-align: center;}
        .add_num li a{display: block; font-size: 18px; font-weight: bold;}
        .add_num li.li_input{ border-left: 1px solid #ddd;border-right: 1px solid #ddd;}

        .lay_class{width:80%; padding:10px; background-color:#fff; color:#666; border:none; position: relative; min-height: 250px;}
        .lay_class p{margin: 10px 0;}
        .lay_class p button{ width: 100%; text-align: center;background: #6CE; color: #fff; padding: 5px 0; margin: 20px 0;border-radius: 10px;}
        .lay_class p button[type='button']{background: #7f7f7f;}
        .lay_class .close_btn{ position: absolute; right:10px; top: 20px;}


        .xz_div{ text-align: center;}
        .xz_div input{ border: 1px solid #ccc;width: 90%; margin: 0 auto; height: 30px;box-sizing: border-box; background: #fff;}
        .xz_div p{ margin: 10px auto; width: 90%; text-align: center;}
        .xz_div p input{ text-align: left;}
        .xz_btn{ width: 100%; margin: 20px auto; background: #40C780; color: #fff; padding: 5px 0;  border-radius: 10px; text-align: center;}
    </style>

    <script>
            //加减
            $(function(){
                var value=1;
                var stock_num = $('#stock_num').val() ;
                var jifen = $('#add_jf').html();
                $(".li_input input").val('1');
                //加
                $(".add_num li:last").click(function(){
                    var oldValue=parseInt($(".li_input input").val());
                    if(oldValue == stock_num){
                        alert('亲,已经没有库存')
                    }else{
                        value = oldValue+1;
                        $(".li_input input").val(value);
                        $("#item_num").val(value);
                        $('#add_jf').html(((oldValue+1)*jifen));
                    }

                });
                //减
                $(".add_num li:first").click(function(){
                    var oldValue=parseInt($(".li_input input").val());
                    if(oldValue>1){
                        value = oldValue - 1;
                    }
                    $(".li_input input").val(value);
                    $("#item_num").val(value);
                    if(oldValue > 1){
                        $('#add_jf').html(((oldValue-1)*jifen));
                    }else{
                        $('#add_jf').html(((oldValue)*jifen));
                    }

                });
               
                //新增收货地址
                $('#xuanze').click(function(){
                	var data = <?php echo ($addr); ?>;
                    var status = <?php echo ($status); ?>;
                    if(!status){
                        var btn = "<button type=\"submit\" onclick=\"tijiao()\">立即兑换</button>";
                    }else{
                        var btn = "";
                    }
                	 var url = "<?php echo U('Jifen/duihuan');?>";
                     var content = "<form id=\'infoForm\' action=\""+url+"\" method=\"post\">"+
                    	 "<div id=\"xz_el\"><p>"+data+
                     "<input type=\"hidden\" name=\"item_id\" value=\"<?php echo ($info["id"]); ?>\">"+
                     "<input type=\"hidden\" name=\"item_name\" value=\"<?php echo ($info["title"]); ?>\">"+
                     "<input type=\"hidden\" name=\"item_num\" id=\"item_num\">"+
                     "<input type=\"hidden\" name=\"order_score\" value=\"<?php echo ($info["score"]); ?>\">"+
                 	"</label></p></div><p>"+btn+"</p></form>";
                    layer.open({
                        type: 1,
                        title:'请选择收货地址',
                        shadeClose:false,
                        content: content,
                        className:'lay_class'
                    });
                })
            });
            var _one;
            var name;
            var tel;
            var addr;

        function xzSure(){
            name=$('#name').val();
            tel=$('#tel').val();
            addr=$('#addr').val();
            layer.close(_one);
            $('#xz_el').append('<p><label><input type=\"radio\" name=\"add\" />'+addr + name+tel+'</label></p>')
        }
        function ajax_get_city(obj){
        	$('#cid').get(0).options.length=1;
        	var pid = obj;
        	if(pid != '0'){
        		$.post("<?php echo u('Jifen/ajax_get_city');?>",{ id:pid },function(data){
        			if(data.status == 1){
        				 $('#cid').get(0).options.length=1;
        					$.each(data.data,function(i,v){
        					   var $option=$('<option value="'+v.id+'">'+v.name+'</option>');
        						   $('#cid').append($option);
        					})
        			}
        			
        		},"json");
        		
        	}
        }
        function ajax_get_area(obj){
        	$('#aid').get(0).options.length=1;
        	var pid = obj;
        	if(pid != '0'){
        		$.post("<?php echo u('Jifen/ajax_get_city');?>",{ id:pid },function(data){
        			if(data.status == 1){
        				 $('#aid').get(0).options.length=1;
        					$.each(data.data,function(i,v){
        					   var $option=$('<option value="'+v.id+'">'+v.name+'</option>');
        						   $('#aid').append($option);
        					})
        			}
        			
        		},"json");
        		
        	}
        }
        
        function tijiao(){
        	var value= $('#num').val();
        	$("#item_num").val(value);
        	$('#infoForm').submit();
        }
    </script>
</head>

<body>
<form action="<?php echo U('Jifen/duihuan');?>" method="post" id="from2">
        <div class="baner">
        	<img src="<?php echo get_img($info[img],'score_item');?>"/>
         </div>
        <div class="baner-bottom"><?php echo ($info["title"]); ?></div>
        <div class="baner-bottom1">
            <div class="add_jf fl" id="add_jf" style="margin-top:6px;"><?php echo ($info["score"]); ?></div><span style="color:#333;margin:6px 0 0 10px;display:inline-block;">积分</span>
            <div class="add_num fr">
                <ul>
                    <li><a href="javascript:;">-</a></li>
                    <li class="li_input">
                        <input readonly="readonly" type="text" id="num" name="num" value="1" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" /></li>
                    <li><a href="javascript:;">+</a></li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="content">
            <div class="biaoti">
                <h1>产品参数</h1>
                <input type="hidden" id="stock_num" value="<?php echo ($info["stock"]); ?>">
            </div>
                <div class="artical">
                    <p><?php echo ($info["desc"]); ?></p>
                </div>
            	<div class="jf_btn"><input id="xuanze" type="button" value="选择地址" /></div>
                <div class="jf_btn" style="display:none"><input id="duihuan" type="text" value="立即兑换" /></div>

        </div>
        
        </form>
<div style="height:55px;"></div>
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