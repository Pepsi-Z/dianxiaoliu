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
<div class="subnav">
    <h1 class="title_2 line_x">订单详情</h1>
</div>
    <form id="info_form" action="<?php echo u('score_order/edit');?>" method="post" enctype="multipart/form-data">
<div class="pad_lr_10">
				<table width="100%" cellspacing="0" class="table_form">
					<tr>
						<th width="120">订单号 :</th>
						<td>
						<?php echo ($info["order_sn"]); ?>
						</td>
                        <th>商品名称 :</th>
						<td>
		                <?php echo ($info["item_name"]); ?>
		                </td>
					</tr>
		            <tr>
						
					</tr>
                    <tr>
						<th>商品数量 :</th>
						<td><?php echo ($info["item_num"]); ?></td>
                        <th>使用积分 :</th>
						<td><?php echo ($info["order_score"]); ?></td>
					</tr>
                    <tr>
						
					</tr>
		            <tr>
						<th>会员名称 :</th>
						<td>
                        <?php echo ($info["uname"]); ?>
                        </td>
                        <th>收货人 :</th>
						<td>
                        <?php echo ($info["consignee"]); ?>
                        </td>
		 			</tr>
                    <tr>
						
		 			</tr>
                    <tr>
						<th>收货人电话 :</th>
						<td>
                        <?php echo ($info["mobile"]); ?>
                        </td>
                        <th></th>
						<td>

                        </td>
		 			</tr>
                    <tr>
						
		 			</tr>
                    <tr>
						<th>收货地址 :</th>
						<td>
                        <?php echo ($address["saddress"]); echo ($address["address"]); ?>
                        </td>
                        <th>下单时间 :</th>
						<td><?php echo (date("Y-m-d H:i:s",$info["add_time"])); ?></td>
		 			</tr>
					
					<tr>
						
					</tr> 
                    <tr>
                    	<th>备注 :</th>
                        <td colspan="4">
                        <textarea id="remark" name="remark" style="width:70%; height:70px;"><?php echo ($info["remark"]); ?></textarea>
                        </td>
                    </tr>    
				</table>
		<div class="mt10"><input type="submit" value="完 成" id="dosubmit" name="dosubmit" class="btn btn_submit" style="margin:0 0 10px 100px;"><br /><br /><br /></div>
</div>
<input type="hidden" name="menuid"  value="<?php echo ($menuid); ?>"/>
<input type="hidden" name="id" id="id" value="<?php echo ($info["id"]); ?>" />
</form>
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