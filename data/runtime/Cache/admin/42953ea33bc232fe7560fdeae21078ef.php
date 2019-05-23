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
<!--商品列表-->
<div class="pad_lr_10" >
    <form name="searchform" method="get" >
    <table width="100%" cellspacing="0" class="search_form">
        <tbody>
            <tr>
                <td>
                <div class="explain_col">
                    <input type="hidden" name="g" value="admin" />
                    <input type="hidden" name="m" value="merchant" />
                    <input type="hidden" name="a" value="index" />
                    <input type="hidden" name="menuid" value="<?php echo ($menuid); ?>" />
					<?php if($sm != ''): ?><input type="hidden" name="sm" value="<?php echo ($sm); ?>" /><?php endif; ?>
                    <!--发布时间 :
                    <input type="text" name="time_start" id="J_time_start" class="date" size="12" value="<?php echo ($search["time_start"]); ?>">
                    -
                    <input type="text" name="time_end" id="J_time_end" class="date" size="12" value="<?php echo ($search["time_end"]); ?>">-->
					&nbsp;&nbsp;平台分类 :
                    <select id="pid" name="pid">
                        <option value="">--所有--</option>
                        <?php if(is_array($cate)): $i = 0; $__LIST__ = $cate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" <?php if($val['id'] == $search['pid']): ?>selected<?php endif; ?>><?php echo ($val["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <select name="cate_id" id="cid">
                        <option value="">--请选择商家类型--</option>
                        <?php if($search['cate_id'] != ''): ?><option value="" selected ><?php echo ($search['cname']); ?></option><?php endif; ?>
                    </select>
                    &nbsp;&nbsp;关键字 :
                    <input name="keyword" type="text" class="input-text" size="25" value="<?php echo ($search["keyword"]); ?>" />
                    <input type="submit" name="search" class="btn" value="搜索" />
					
                </div>
                </td>
            </tr>
        </tbody>
    </table>
    </form>
    <div class="J_tablelist table_list" data-acturi="<?php echo U('merchant/ajax_edit');?>">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width=25><input type="checkbox" id="checkall_t" class="J_checkall"></th>
                <th width="40">ID</th>
                <th width="100" align="center">商家名称</th>
                <th width="100" align="center">商家电话</th>
                <th width="120" align="center">返积分规则</th>
                <th width="80" align="center">返利规则</th>
                <th width="70">平台分类</th>
                <th width="80">商家类型</th>
                <th width="80">商家图片</th>
               <!-- <th width="80">折扣</th>-->
                <th width="160">添加时间</th>
                <th width="160">地址</th>
                <th width="100">排序</th>
                <th width="40">状态</th>
                <th width="150"><?php echo L('operations_manage');?></th>
            </tr>
        </thead>
    	<tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
                    <td align="center"><input type="checkbox" class="J_checkitem" value="<?php echo ($val["id"]); ?>"></td>
                    <td align="center"><?php echo ($val["id"]); ?></td>
                    <td align="center"><?php echo ($val["title"]); ?></td>
                    <td align="center"><?php echo ($val["tel"]); ?></td>
                    <td align="center"><?php echo ($val["rname"]); ?></td>
                    <td align="center"><?php echo ($val["rmname"]); ?></td>
                    <td align="center"><?php echo ($val["c_name"]); ?></td>
                    <td align="center">
                        <?php if($val["t_name"] != ''): echo ($val["t_name"]); ?>
                            <?php else: ?>
                            无<?php endif; ?>

                    </td>
                    <td align="center">
                        <?php if(!empty($val['img'])): ?><div class="img_border"><img src="<?php echo attach(get_thumb($val['img'], '_s'), 'merchant');?>" width="32" width="32" class="J_preview" data-bimg="<?php echo attach(get_thumb($val['img'], '_s'), 'merchant');?>"></div><?php endif; ?>
                    </td>
                    <!--<td align="center"><?php echo ($val["discount"]); ?></td>-->
                    <td align="center"><?php echo (date('Y-m-d H:i',$val["add_time"])); ?></td>
                  <!--  <td align="center"><?php echo ($val["p_name"]); ?>-<?php echo ($val["city_name"]); ?>-<?php echo ($val["area_name"]); ?></td>-->
                    <td align="center"><?php echo ($val["address"]); ?></td>
                    <td align="center"><span data-tdtype="edit" data-field="ordid" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["ordid"]); ?></span></td>
                    <td align="center"><img data-tdtype="toggle" data-id="<?php echo ($val["id"]); ?>" data-field="status" data-value="<?php echo ($val["status"]); ?>" src="__STATIC__/images/admin/toggle_<?php if($val["status"] == 0): ?>disabled<?php else: ?>enabled<?php endif; ?>.gif" /></td>
                    <td align="center">
                        <a href="<?php echo u('merchant/edit', array('id'=>$val['id'],'tid'=>$val['tid'], 'menuid'=>$menuid));?>"><?php echo L('edit');?></a> |
                        <a href="javascript:void(0);" class="J_confirmurl" data-uri="<?php echo u('merchant/delete', array('id'=>$val['id']));?>" data-acttype="ajax" data-msg="<?php echo sprintf(L('confirm_delete_one'),$val['title']);?>"><?php echo L('delete');?></a>
                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    	</tbody>
    </table>
    </div>
    </if>
    <div class="btn_wrap_fixed">
        <label class="select_all mr10"><input type="checkbox" name="checkall" class="J_checkall"><?php echo L('select_all');?>/<?php echo L('cancel');?></label>
        <input type="button" class="btn" data-tdtype="batch_action" data-acttype="ajax" data-uri="<?php echo U('merchant/delete');?>" data-name="id" data-msg="<?php echo L('confirm_delete');?>" value="<?php echo L('delete');?>" />
        <div id="pages"><?php echo ($page); ?></div>
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
<script src="__STATIC__/js/my97/My97DatePicker"></script>
<link rel="stylesheet" href="__STATIC__/js/calendar/calendar-blue.css"/>
<script src="__STATIC__/js/calendar/calendar.js"></script>
<script src="__STATIC__/js/calendar/calendar.js"></script>
<script>
    $(function(){
        $('#pid').change(function(){
            $('#cid').get(0).options.length=1;
            var pid = $(this).val();
            $.post("<?php echo u('item/ajax_get_brands');?>",{ pid:pid },function(data){
//                alert(data)
                if(data.status == 1){
                    $('#cid').get(0).options.length=1;
                    $.each(data.data,function(i,v){
                        var $option=$('<option value="'+v.id+'">'+v.name+'</option>');
                        $('#cid').append($option);
                    })
                }else{
//                    alert("该分类下没有品牌");
                }
            },"json");
        });
    });
</script>
<script>
    $(function(){
       $('.look').click(function(){
           var id = $(this).attr('data');
           $('#tr_'+id).toggle();
       })
    });

</script>

<script>
$('.J_preview').preview(); //查看大图
$('.J_cate_select').cate_select({top_option:lang.all}); //分类联动
$('.J_tooltip[title]').tooltip({offset:[10, 2], effect:'slide'}).dynamic({bottom:{direction:'down', bounce:true}});
</script>
</body>
</html>