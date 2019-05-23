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
<!--会员列表-->
<div class="pad_10" >
    <form name="searchform" method="get" >
        <table width="100%" cellspacing="0" class="search_form">
            <tbody>
            <tr>
                <td>
                    <div class="explain_col">
                        <input type="hidden" name="g" value="admin" />
                        <input type="hidden" name="m" value="user_merchant" />
                        <input type="hidden" name="a" value="index" />
                        <input type="hidden" name="menuid" value="<?php echo ($menuid); ?>" />
                        &nbsp;关键字 :
                        <input name="keyword" type="text" class="input-text" size="25" value="<?php echo ($search["keyword"]); ?>" />
                        <input type="submit" name="search" class="btn" value="搜索" />
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </form>

    <div class="J_tablelist table_list" data-acturi="<?php echo U('user_merchant/ajax_edit');?>">
        <table width="100%" cellspacing="0">
            <thead>
            <tr>
                <th width=25><input type="checkbox" id="checkall_t" class="J_checkall"></th>
                <th><span data-tdtype="order_by" data-field="id">ID</span></th>
                <th width="40" ><span data-tdtype="order_by" data-field="img">头像</span></th>
                <th width="80"><span data-tdtype="order_by" data-field="mid">所属商家</span></th>
                <th><span data-tdtype="order_by" data-field="username">登陆账号</span></th>
                <th><span data-tdtype="order_by" data-field="weixin_name">微信名字</span></th>
                <th width="120"><span data-tdtype="order_by" data-field="reg_time">添加时间</span></th>
                <th width="120"><span data-tdtype="order_by" data-field="last_time">最后登陆</span></th>
                <th width="30"><span data-tdtype="order_by" data-field="status"><?php echo L('status');?></span></th>
                <th width="80"><?php echo L('operations_manage');?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
                    <td align="center"><input type="checkbox" class="J_checkitem" value="<?php echo ($val["id"]); ?>"></td>
                    <td align="center"><?php echo ($val["id"]); ?></td>
                    <td align="center">
                        <?php if($val["cover"] != ''): ?><img style="width: 50px;height: 50px;" src="<?php echo ($val['cover']); ?>" />
                            <?php else: ?>
                            <img src="<?php echo avatar($val['id'], 32);?>" /><?php endif; ?>
                    </td>
                    <td align="center"><?php echo ($val["title"]); ?></td>
                    <td align="center"><?php echo ($val["tel"]); ?></td>
                    <td align="center"><?php echo ($val["weixin_name"]); ?></td>
                    <td align="center"><?php echo (date("Y-m-d H:i",$val["reg_time"])); ?></td>
                    <td align="center"><?php echo (date("Y-m-d H:i",$val["last_time"])); ?></td>
                    <td align="center"><img data-tdtype="toggle" data-value="<?php echo ($val["status"]); ?>" src="__STATIC__/images/admin/toggle_<?php if($val["status"] == 0): ?>disabled<?php else: ?>enabled<?php endif; ?>.gif" /></td>
                    <td align="center">
                        <a href="javascript:;" class="J_showdialog" data-uri="<?php echo u('user_merchant/edit', array('id'=>$val['id'], 'menuid'=>$menuid));?>" data-title="编辑-<?php echo ($val["user_merchantname"]); ?>" data-id="edit" data-width="520" data-height="330">编辑</a> | <a href="javascript:void(0);" class="J_confirmurl" data-uri="<?php echo u('user_merchant/delete', array('id'=>$val['id']));?>" data-acttype="ajax" data-msg="<?php echo sprintf(L('confirm_delete_one'),$val['user_merchantname']);?>"><?php echo L('delete');?></a></td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
        <div class="btn_wrap_fixed">
            <label class="select_all"><input type="checkbox" name="checkall" class="J_checkall"><?php echo L('select_all');?>/<?php echo L('cancel');?></label>
            <input type="button" class="btn" data-tdtype="batch_action" data-acttype="ajax" data-uri="<?php echo U('user_merchant/delete');?>" data-name="id" data-msg="<?php echo L('confirm_delete');?>" value="<?php echo L('delete');?>" />
            <div id="pages"><?php echo ($page); ?></div>
        </div>

    </div>
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
</body>
</html>
<link rel="stylesheet" type="text/css" href="__STATIC__/js/calendar/calendar-blue.css"/>
<script type="text/javascript" src="__STATIC__/js/calendar/calendar.js"></script>