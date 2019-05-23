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
<!--网站设置-->
<div class="pad_lr_10">
	<form id="info_form" action="<?php echo u('setting/edit');?>" method="post" enctype="multipart/form-data">
	<table width="100%" class="table_form">
        <tr>
            <th width="150"><?php echo L('site_name');?> :</th>
            <td><input type="text" name="setting[site_name]" class="input-text" size="50" value="<?php echo C('pin_site_name');?>"></td>
        </tr>
        
    <!--     <tr>
            <th>主页的logo :</th>
            <td>
            <?php if(C('pin_weixinshop_img')!=''){ ?>
            <img src="<?php echo attach(get_thumb(C('pin_weixinshop_img'), ''), 'weixin'); ?>" width="100" height="100"/><br />
            <?php }else{ ?>
            <?php } ?>
  	<input type="file" name="weixinshop_img" /></td>
        </tr>-->
        <tr>
            <th>微信公众号二维码:</th>
            <td>
            <?php if(C('pin_weixin_img')!=''){ ?>
            <img src="<?php echo attach(get_thumb(C('pin_weixin_img'), ''), 'weixin'); ?>" width="100" height="100"/><br />
            <?php }else{ ?>
            <?php } ?>
  	<input type="file" name="weixin_img" /></td>
        </tr>

        <tr>
            <th> 店小六条款:</th>
            <td><textarea name="setting[role]" id="role" style="width:68%;height:400px;visibility:hidden;resize:none;"><?php echo C('pin_role');?></textarea></td>
        </tr>

        <tr>
            <th>关于小六 :</th>
            <td><textarea name="setting[about]" id="about" style="width:68%;height:400px;visibility:hidden;resize:none;"><?php echo C('pin_about');?></textarea></td>
        </tr>

        <tr>
            <th>联系我们 :</th>
            <td><textarea name="setting[liao]" id="liao" style="width:68%;height:400px;visibility:hidden;resize:none;"><?php echo C('pin_liao');?></textarea></td>
        </tr>

        <tr>
            <th>使用帮助 :</th>
            <td><textarea name="setting[help]" id="help" style="width:68%;height:400px;visibility:hidden;resize:none;"><?php echo C('pin_help');?></textarea></td>
        </tr>
      
       <!-- <tr id="J_closed_reason" <?php if(C('pin_site_status') == 1): ?>class="hidden"<?php endif; ?>>
        	<th><?php echo L('closed_reason');?> :</th>
        	<td><textarea rows="4" cols="50" name="setting[closed_reason]" id="closed_reason"><?php echo C('pin_closed_reason');?></textarea></td>
    	</tr>-->
        <tr>
        	<th></th>
        	<td><input type="hidden" name="menuid"  value="<?php echo ($menuid); ?>"/><input type="submit" class="btn btn_submit" value="<?php echo L('submit');?>"/></td>
    	</tr>
	</table>
	</form>
</div>
<script src="__STATIC__/js/jquery/jquery.js"></script>
<script src="__STATIC__/js/jquery/plugins/jquery.tools.min.js"></script>
<script src="__STATIC__/js/jquery/plugins/formvalidator.js"></script>
<script src="__STATIC__/js/pinphp.js"></script>
<script src="__STATIC__/js/admin.js"></script>
<script>
//初始化弹窗
(function (d) {
    d['okValue'] = lang.dialog_ok;
    d['cancelValue'] = lang.dialog_cancel;
    d['title'] = lang.dialog_title;
})($.dialog.defaults);
</script>

<?php if(isset($list_table)): ?><script src="__STATIC__/js/jquery/plugins/listTable.js"></script>
<script>
$(function(){
	$('.J_tablelist').listTable();
});
</script><?php endif; ?>
<script src="__STATIC__/js/jquery/plugins/colorpicker.js"></script>
<script src="__STATIC__/js/kindeditor/kindeditor.js"></script>
<script>

    $(function() {
        KindEditor.create('#about', {
            uploadJson: '<?php echo U("attachment/editer_upload");?>',
            fileManagerJson: '<?php echo U("attachment/editer_manager");?>',
            allowFileManager: true
        });
        KindEditor.create('#role', {
            uploadJson: '<?php echo U("attachment/editer_upload");?>',
            fileManagerJson: '<?php echo U("attachment/editer_manager");?>',
            allowFileManager: true
        });
        KindEditor.create('#liao', {
            uploadJson: '<?php echo U("attachment/editer_upload");?>',
            fileManagerJson: '<?php echo U("attachment/editer_manager");?>',
            allowFileManager: true
        });
        KindEditor.create('#help', {
            uploadJson: '<?php echo U("attachment/editer_upload");?>',
            fileManagerJson: '<?php echo U("attachment/editer_manager");?>',
            allowFileManager: true
        });
    })
</script>
<script>
$(function(){
    $('.J_change_status').live('click', function(){
        if($(this).val() == '0'){
            $('#J_closed_reason').fadeIn();
        }else{
            $('#J_closed_reason').fadeOut();
        }
    });
});
</script>
</body>
</html>