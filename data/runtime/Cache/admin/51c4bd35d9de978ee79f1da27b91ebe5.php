<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <link href="__STATIC__/css/admin/style.css" rel="stylesheet"/>
    <link href="__STATIC__/css/admin/WdatePicker.css" rel="stylesheet"/>
    <title><?php echo L('website_manage');?></title>
    <script>
        var URL = '__URL__';
        var SELF = '__SELF__';
        var ROOT_PATH = '__ROOT__';
        var APP	 =	 '__APP__';
        //语言项目
        var lang = new Object();
        <?php $_result=L('js_lang');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>lang.<?php echo ($key); ?> = "<?php echo ($val); ?>";<?php endforeach; endif; else: echo "" ;endif; ?>
    </script>
    <script charset="utf-8" src="__STATIC__/js/jquery-1.9.1.min.js" type="text/javascript"></script>
    <script charset="utf-8" src="__STATIC__/js/layer/layer.js"></script>
    <script charset="utf-8" src="__STATIC__/js/My97DatePicker/WDatePicker.js" type="text/javascript"></script>
</head>

<body>
<div id="J_ajax_loading" class="ajax_loading"><?php echo L('ajax_loading');?></div>
<?php if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav">
        <div class="content_menu ib_a blue line_x">
            <?php if(!empty($big_menu)): ?><a class="add fb J_showdialog" href="javascript:void(0);" data-uri="<?php echo ($big_menu["iframe"]); ?>" data-title="<?php echo ($big_menu["title"]); ?>" data-id="<?php echo ($big_menu["id"]); ?>" data-width="<?php echo ($big_menu["width"]); ?>" data-height="<?php echo ($big_menu["height"]); ?>"><em><?php echo ($big_menu["title"]); ?></em></a>　<?php endif; ?>
            <?php if(!empty($sub_menu)): if(is_array($sub_menu)): $key = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($key % 2 );++$key; if($key != 1): ?><span>|</span><?php endif; ?>
                    <a href="<?php echo U($val['module_name'].'/'.$val['action_name'],array('menuid'=>$menuid)); echo ($val["data"]); ?>" class="<?php echo ($val["class"]); ?>"><em><?php echo ($val['name']); ?></em></a><?php endforeach; endif; else: echo "" ;endif; endif; ?>
        </div>
    </div><?php endif; ?>
<link rel="stylesheet" type="text/css" href="__STATIC__/css/admin/weixin.css"/>
<div class="content-right" style="padding-top:20px;">
</div>
</div>
<div class="showimg" style="display:none">
	<div class="stit">
    	<span>上传图片</span>
        <a href="javascript:zsc_close();"><img src="__STATIC__/images/admin/addpageup_06.jpg" /></a>
    </div>
    <div class="sup">
	<input type="button" value="上传图片" class="uploadbtn"/>
    <form action="<?php echo U('keyword/ajaxupload');?>" method="post" id="zsc_myform" enctype="multipart/form-data" target="yframe"><!-- target="yframe"--><!--target="yframe"-->
    	<input type="file" value="上传图片" class="uploadbtn" style="position:absolute; top:75px; left:20px; filter:alpha(pacity=0);opacity:0; z-index:999;" onchange="zsc_upload()" name="image[]" />大小不超过1M ，仅限png,jpeg,jpg
        <input type="hidden" name="sub" value="submit" /> 
    </form>
	<iframe name="yframe" src="<?php echo U('keyword/ajaxupload');?>" style="border:none; display:none;"></iframe>
    </div>
    <div class="imgbox">
    </div>
    <div class="sbottom"><input type="button" class="submit" id="zsc_surebtn" /></div>
     <!-----正在提交------>
    <span class="loadsubmit">正在上传...</span>
</div>
<div class="zhe" style="display:none"></div>
<script type="text/javascript" src="__STATIC__/js/dingcan/addmess.js"></script>
<script>
	$(function(){
		$('#menu2_autoreply').show();
		$('.arrow_img_autoreply').attr('src', 'static/images/index_20.png');
		$('#zsc_keyNew').css("backgroundColor","#D9DDDE");
	});
</script>
</body>
</html>