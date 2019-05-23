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
<div class="pad_10">
    <form action="<?php echo U('admin_role/auth');?>" method="post">
    <div class="J_tablelist table_list">
    <table width="100%" cellspacing="0" id="J_auth_tree">
    	<thead>
        	<tr><th align="left"><?php echo L('role_auth');?> - <?php echo ($role["name"]); ?></th></tr>
        </thead>
    	<tbody>
        	<?php echo ($list); ?>
    	</tbody>
    </table>
    <input type="hidden" name="id" value="<?php echo ($role["id"]); ?>"></input>
    <div>
    
    <div class="btn_wrap_fixed">
    	<label class="select_all"><input type="checkbox" name="checkall" class="J_checkall"><?php echo L('select_all');?>/<?php echo L('cancel');?></label>
        <input type="submit" class="btn" name="dosubmit" value="<?php echo L('submit');?>"/>
    </div>
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
<script src="__STATIC__/js/jquery/plugins/jquery.treetable.js"></script>
<script>
$(function() {
    $("#J_auth_tree").treeTable({indent:20});

    $('.J_checkall').live('click', function(){
        $('.J_checkitem').attr('checked', this.checked);
        $('.J_checkall').attr('checked', this.checked);
    });

    $('.J_checkitem').live('click', function(){
        var chk = $("input[type='checkbox']"),
            count = chk.length,
            num = chk.index($(this)),
            level_top = level_bottom =  chk.eq(num).attr('level');
        for(var i=num; i>=0; i--){
            var le = chk.eq(i).attr('level');
            if(eval(le) < eval(level_top)){
                chk.eq(i).attr("checked", true);
                var level_top = level_top-1;
            }
        }
        for(var j=num+1; j<count; j++){
            var le = chk.eq(j).attr('level');
            if(chk.eq(num).attr("checked")) {
                if(eval(le) > eval(level_bottom)) chk.eq(j).attr("checked", true);
                else if(eval(le) == eval(level_bottom)) break;
            }else{
                if(eval(le) > eval(level_bottom)) chk.eq(j).attr("checked", false);
                else if(eval(le) == eval(level_bottom)) break;
            }
        }
    });
});
</script>
</body>
</html>