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
<script src="__STATIC__/js/highcharts.js"></script>
<script src="__STATIC__/js/exporting.js"></script>
<link href="__STATIC__/css/admin/sell.css" rel="stylesheet"/>
<!--会员列表-->
<div class="content" >
    <dl class="charts mt10">
        <dt>
        <form action="" method="get" name="form_select" onsubmit="return check()">
            <p>
                <input type="hidden" name="g" value="admin"/>
                <input type="hidden" name="m" value="tuan_log"/>
                <input type="hidden" name="a" value="index"/>
                <input type="hidden" name="days" value="-1"/>
                <span>商家名称 :</span>
                <input name="orderId" type="text" class="input-text" size="15" value="<?php echo ($search["orderId"]); ?>" />
                <span>订单状态 :</span>
                <select name="status">
                    <option value="">--所有--</option>
                    <?php foreach($order_status as $key=>$item){ ?>
                    <option <?php if($search['status']==$key) echo "selected=''"; ?> value="<?php echo $key; ?>"><?php echo $item; ?></option>
                    <?php } ?>

                </select>
                <span>自定义时间段:</span>
                <input type="text" name="start_time" id="start" size="12" onClick="WdatePicker()" class="Wdate" value="<?php echo ($start_time); ?>"/>
                <em style="margin-right: 8px;">~</em>
                <input type="text" name="end_time" id="end" size="12" onClick="WdatePicker()"  class="Wdate" value="<?php echo ($end_time); ?>"/>
                &nbsp;&nbsp;
                <input type="submit" class="button_search" value="确定" />
            </p>
        </form>
        <strong>销售概况</strong>
        <b>合计金额：</b><font>&yen;<?php echo ($order_sumPrice); ?></font>
        <b>成交订单：</b><font><?php echo ($con); ?>&nbsp;单</font>
        </dt>
        <div class="pad_lr_10" style="margin-top:10px;width:100%;padding:0px;">
            <div class="J_tablelist table_list" data-acturi="<?php echo U('item_cate/ajax_edit');?>">
                <table width="100%" cellspacing="0" id="J_cate_tree">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>订单编号</th>
                        <th>商家名称</th>
                        <th>商品名称</th>
                        <th>总销售额</th>
                        <th>商品销售额</th>
                        <th>购买人姓名</th>
                        <th>订单状态</th>
                        <th>下单时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
                            <td align="center"><span><?php echo ($val["id"]); ?></span></td>
                            <td align="center"><span><?php echo ($val["orderId"]); ?></span></td>
                            <td align="center"><span><?php echo ($val["c_title"]); ?></span></td>
                            <td align="center"><span><?php echo ($val["title"]); ?></span></td>
                            <td align="center"><span><?php echo ($val["order_sumPrice"]); ?></span></td>
                            <td align="center"><span><?php echo sprintf('%.2f',($val['price']*$val['quantity']))?></span></td>
                            <td align="center"><span><?php echo ($val["userName"]); ?></span></td>
                            <td align="center"><span>
                                <?php if($val['status'] == 1): ?>待付款
                                    <?php elseif($val['status'] == 2): ?>
                                    未消费
                                    <?php elseif($val['status'] == 4): ?>
                                    已消费<?php endif; ?>
                            </span></td>
                            <td align="center"><span><?php echo (date("Y-m-d H:i:s",$val["add_time"])); ?></span></td>
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </dl>
    <div class="btn_wrap_fixed">
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
</body>
</html>