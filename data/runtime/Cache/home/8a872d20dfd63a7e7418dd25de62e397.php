<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title><?php echo L('message_title');?></title>
<link rel="stylesheet" type="text/css" href="__STATIC__/css/default/base.css" />
<link rel="stylesheet" type="text/css" href="__STATIC__/css/default/style.css" />
<script src="__STATIC__/js/jquery/jquery.js"></script>
<link href="../style/static/css/reset.css" rel="stylesheet" type="text/css">
<style>
		body{ height: 100%;}
		.promptbox{ margin-top:10%;}
		h1{ font-weight:normal; color:#000; text-align:center; margin-top:5%;}
		.prompt{ width:95px; height:93px; display:block; margin:0 auto;}
	</style>
<meta charset="utf-8" />
<title><?php echo ($page_seo["title"]); ?></title>
<meta name="keywords" content="<?php echo ($page_seo["keywords"]); ?>" />
<meta name="description" content="<?php echo ($page_seo["description"]); ?>" />
<meta name="viewport" content="width=device-width;minimum-scale=1.0; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="../Style/shop.css">
<script charset="utf-8" src="../Style/js/jquery.js" type="text/javascript"></script>
<script charset="utf-8" src="../Style/js/ecmall.js" type="text/javascript"></script>
<script type="text/javascript" src="../Style/js/index.js"></script>
<script src="../Style/js/touchslider.dev.js"></script>
</head>
<script type="text/javascript">
//<!CDATA[
var SITE_URL = __ROOT__;
var PRICE_FORMAT = '¥%s';

$(function(){
    var span = $("#child_nav");
    span.hover(function(){
        $("#float_layer:not(:animated)").show();
    }, function(){
        $("#float_layer").hide();
    });
});
//]]>
</script>
</head>
<body>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"  />
    <title><?php echo C('pin_site_name');?></title>
    <script type="text/javascript" src="../Style/static/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="../Style/static/js/jquery.bxslider.js"></script>
    <script type="text/javascript" src="../Style/static/js/nav4.js"></script>
    <link href="../Style/static/css/reset.css" rel="stylesheet" type="text/css">
    <link href="../Style/static/css/header.css" rel="stylesheet" type="text/css">
    <link href="../Style/static/css/menu.css" rel="stylesheet" type="text/css">
    <link href="../Style/static/css/jquery.bxslider.css" rel="stylesheet" type="text/css">

    <script>
        $(function(){
            var mySwiper = new Swiper ('.swiper-container', {
                autoplay: 5000,
                pagination: '.swiper-pagination'
            })
        });
    </script>
</head>

<div class="promptbox" style="margin-bottom:20px;">
		<img src="../Style/static/images/cg_03.png" class="prompt">
</div>
<h1><?php echo ($message); ?></h1>
<p class="msg_btnleft" style="padding-top:20px;padding-left:10px;"><a href="<?php echo ($jumpUrl); ?>"><?php echo L('msg_jump_desc');?></a></p>
<script language="javascript">
   setTimeout("location.href='<?php echo ($jumpUrl); ?>';",<?php echo ($waitSecond); ?>*2000);
</script>

</body>
</html>