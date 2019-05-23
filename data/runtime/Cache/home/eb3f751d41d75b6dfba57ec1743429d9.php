<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
 <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />
<title>编辑地址</title>
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
<link href="../Style/static/css/menu.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../Style/static/js/nav4.js"></script>
<script type="text/javascript" language="javascript" src='../Style/js/jquery.js'></script>
</head>
<body>
   <form action="<?php echo U('User/addresschange');?>" id="form2" method="post">
        <ul class="newadress_ul">
        	<li class="newaddress_li">
            	<span>收 货 人: </span>
                 <input type="text" name="consignee" placeholder="请填写您的真实姓名" value="<?php echo ($info["consignee"]); ?>"/>
            </li>
            <li class="newaddress_li span_dis">
            	<span>手机号码: </span>
                 <input id="t_tel" type="text" name="mobile" placeholder="请填写您的手机号码" value="<?php echo ($info["mobile"]); ?>"/>
            </li>
           <!-- <li class="newaddress_li span_dis">
            	<span><label style="color:#F00;">*</label>邮政编码</span>
                 <input name="postal" type="text" placeholder="请填写邮政编码" value="<?php echo ($info["postal"]); ?>"/>
            </li>-->
            <li class="newaddress_li span_dis">
            	<span>选择省份: </span>
                 <select name="pid" id="sid">
                 	<option value="">请选择省份</option>
                 	<?php if(is_array($province)): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" <?php if($val['id'] == $info['pid']): ?>selected<?php endif; ?>><?php echo ($val["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                 </select>&nbsp;
            </li>
            <li class="newaddress_li span_dis">
                <span>选择城市: </span>
                <select name="cid" id="cid">
                    <option value="">请选择城市</option>
                    <?php if(!empty($city)): if(is_array($city)): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" <?php if($val['id'] == $info['cid']): ?>selected<?php endif; ?>><?php echo ($val["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; endif; ?>
                </select>&nbsp;
            </li>
            <li class="newaddress_li span_dis">
                <span>选择县区: </span>
                  <select name="aid" id="aid">
                 	<option value="">请选择县区</option>
                 	<?php if(!empty($area)): if(is_array($area)): $i = 0; $__LIST__ = $area;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" <?php if($val['id'] == $info['aid']): ?>selected<?php endif; ?>><?php echo ($val["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; endif; ?>
                 </select>
            </li>

            <li class="newaddress_li height_li span_dis">
            	<span>详细地址</span>
                 <textarea placeholder="请填写详细地址" name="address"><?php echo ($info["address"]); ?></textarea>
            </li>
        </ul>
        <input type="hidden" name="mk" value="<?php echo ($_GET['mk']); ?>">
        <input type="hidden" name="id" value="<?php echo ($info["id"]); ?>">
        <input id="submit1" type="submit" value="保存" class="address_button"/>
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
   <script>
       $(function(){

           $("#submit1").click(function(e){
               var t_tel  = $('#t_tel').val();
               var street = $('#street').val();
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


           });

       })
</script>
<script>
$(function(){
	$('#sid').change(function(){
		 $('#cid').get(0).options.length=1;
		 $('#aid').get(0).options.length=1;
		var sid = $(this).val();
		$.post("<?php echo u('User/ajax_get_address');?>",{ id:sid },function(data){
			if(data.status == 1){
				 $('#cid').get(0).options.length=1;
					$.each(data.data,function(i,v){
					   var $option=$('<option value="'+v.id+'">'+v.name+'</option>');
						   $('#cid').append($option);					
					})	
			}
		},"json");	
	});
	
	$('#cid').change(function(){
		 
		 $('#aid').get(0).options.length=1;
		var cid = $(this).val();
		$.post("<?php echo u('User/ajax_get_address');?>",{ id:cid },function(data){
			if(data.status == 1){
				 $('#aid').get(0).options.length=1;
					$.each(data.data,function(i,v){
					   var $option=$('<option value="'+v.id+'">'+v.name+'</option>');
						   $('#aid').append($option);					
					})	
			}
		},"json");	
	});
});
</script>