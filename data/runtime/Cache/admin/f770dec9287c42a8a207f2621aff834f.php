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
<script charset="utf-8" src="__STATIC__/js/jquery.js" type="text/javascript"></script>
<style>
.tdtitle {font-family: "宋体";
    height: 32px;
    line-height: 32px;
    padding-right: 5px;
    text-align: right;
    vertical-align: top;}

.tblist thead tr th, .tblist tbody tr.tblist_th td {
    background: url("__STATIC__/images/tblistthbg.gif") repeat-x scroll 0 0 transparent;
    border-right: 1px solid #DDDDDD;
    height: 30px;
    overflow: hidden;
    padding: 0 8px;
    text-align: center;
    white-space: nowrap;
}
.tblist tbody tr, .tbmodify .tblist tbody tr {
    background: none repeat scroll 0 0 #FFFFFF;
}
.tblist {
    border-left: 1px solid #DDDDDD;
    width: 100%;
}	
.tblist tbody tr, .tbmodify .tblist tbody tr {
    background: none repeat scroll 0 0 #FFFFFF;
}
.tblist tbody tr td, .tbmodify .tblist tbody tr td {
    border-bottom: 1px solid #DDDDDD;
    border-left: 0 solid #DDDDDD;
    border-right: 1px solid #DDDDDD;
    line-height: 18px;
    text-align: center;
}
.tblist tbody tr td {
    padding: 2px 4px;
}
.tbdetail {
    width: 100%;
}
.tbdetail td {
    background-color: #FFFFFF;
    height: 32px;
    line-height: 33px;
    padding: 3px 0 3px 5px;
    /*text-align: left; */
}
.tbdetail .ltd {
    padding-left: 5px;
  text-align: left;
}
.tbdetail .rtd {
    padding-right: 5px;
   text-align: right;	
}
</style>
<script charset="utf-8" src="__STATIC__/js/jquery.js" type="text/javascript"></script>
<!--编辑商品-->
<form id="info_form" action="<?php echo u('tuan_order/edit');?>" method="post" enctype="multipart/form-data">
<div class="pad_lr_10">
	<div class="col_tab">
		
		<div class="J_panes">
        <div class="content_list pad_10">
        <div align="center" style="background:none repeat scroll 0 0 #E6F1F6;font-size:14px;font-weight:bold;padding:10px 0;">基本信息</div>
		
        
       
        
        
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbdetail">
                    <tbody>
                        <tr>
                            <td width="12%" class="tdtitle" >订单编号：</td>
                            <td width="22%"><?php echo ($info["orderId"]); ?></td>
                          
                            <td width="12%" class="tdtitle">订单状态：</td>
                            <td width="22%">   
                         <?php switch($info["status"]): case "1": ?>待付款<?php break;?>
                             <?php case "2": ?>未消费<?php break;?>
                             <?php case "4": ?>已消费<?php break;?>
                             <?php case "6": ?>后台下单<?php break;?>
                        <?php default: ?><font color="red">关闭</font><?php endswitch;?>
                        </td>
                         <td width="12%" class="tdtitle"></td>
                            <td width="22%"></td>
                        </tr>
                        <tr>
                            <td class="tdtitle">下单时间：</td>
                            <td><?php echo (date('Y/m/d H:i:s',$info["add_time"])); ?></td>
                            <td class="tdtitle">付款时间：</td>
                            <td><?php if($info["support_time"] == ''): ?>-<?php else: echo (date('Y/m/d H:i:s',$info["support_time"])); endif; ?></td>
                            <td class="tdtitle">商品总额：</td>
                            <td class="red"><span id="labProductTotal">¥<?php echo ($info["goods_sumPrice"]); ?></span></td>
                        </tr>
                        <tr>
                            <td class="tdtitle">订单总额：</td>
                            <td class="red"><span id="labOrderTotal">¥<?php echo ($info["order_sumPrice"]); ?></span></td>
                        </tr>
                       
                       <!-- <tr>
                            <td class="tdtitle">客户要求：</td>
                            <td style="padding:5px 0 5px 10px;" colspan="5"><?php echo ($info["note"]); ?></td>
                        </tr>-->
                        <tr>
                            <td class="tdtitle">客服备注：</td>
                            <td style="padding:5px 0 5px 10px;" colspan="5">
                                <span  id="labSellerRemark"><?php echo ($info["sellerRemark"]); ?></span>
                                 <form method="POST" >
                                <input type="hidden" name="id" value="<?php echo ($info["id"]); ?>" />
                                    <input type="hidden" name="g" value="admin" />
                                    <input type="hidden" name="m" value="tuan_order" />
                                   <input type="hidden" name="a" value="updateRemark" />
                                   <span style="display:none;" id="sellerRemark_modify">
	                                    <textarea style="width:600px;height:45px;margin-bottom:3px;resize: none;"  id="txtSellerRemark" name="txtSellerRemark"><?php echo ($info["sellerRemark"]); ?></textarea>
                                    <br>
                                    <input type="submit" class="button" id="btnSellerRemark" value="保存" name="btnSellerRemark">
                                    <input type="button" value="取消" class="button" onclick="Cancel()">
                                </span>
                                <input type="button" value="修改" class="button"  onclick="Modify()" id="btnModifySellerRemark" name="btnModifySellerRemark">
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <script>
                function Modify()
                {
                	$('#labSellerRemark').hide();
                	$('#btnModifySellerRemark').hide();
                	$('#sellerRemark_modify').show();
                }
                  function Cancel()
                {
                	$('#labSellerRemark').show();
                	$('#btnModifySellerRemark').show();
                	$('#sellerRemark_modify').hide();
                }
              
                </script>
                
            <!-- 
                 <div align="center" style="background:none repeat scroll 0 0 #E6F1F6;font-size:14px;font-weight:bold;padding:10px 0;">支付信息</div>    
               <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbdetail">
                    <tbody>
                        <tr>
                            <td width="12%" class="tdtitle">支付方式：</td>
                            <td width="22%">
                             <?php switch($info["supportmetho"]): case "1": ?>支付宝支付<?php break;?>
                         <?php case "2": ?>货到付款<?php break;?>
                         <?php case "3": ?>微信支付<?php break;?>
                        <?php default: ?>-<?php endswitch;?>
                           </td>
                           <td width="12%" class="tdtitle">是否货到付款:</td>
                            <td width="22%"> <?php switch($info["supportmetho"]): case "1": ?>否<?php break;?>
                         <?php case "2": ?>是<?php break;?>
                         <?php case "3": ?>否<?php break;?>
                        <?php default: ?>-<?php endswitch;?></td>
                             <td width="12%" class="tdtitle"></td>
                            <td width="22%" class="red"><span id="labPaymentFee"></span></td> 
                        </tr>
                    </tbody>
                </table>
                 -->


                 <div align="center" style="background:none repeat scroll 0 0 #E6F1F6;font-size:14px;font-weight:bold;padding:10px 0;">商品明细</div>    
                   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tblist">
                    <thead>
                        <tr>
                            <th width="70">商品图片</th>
                            <th>商品名称</th>							
						<!--	<th>商品属性</th>-->
                            <th width="60">单价</th>
                            <th width="60">数量</th>
                            <th width="60">小计</th>
                        </tr>
                    </thead>
                    <tbody>
                     <?php if(is_array($order_detail)): $i = 0; $__LIST__ = $order_detail;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="">
                            <td><img width="68" alt="nopic" src="<?php echo attach(get_thumb($vo['img'], '_b'), 'item');?>"></td>
                            <td class="ltd">
                                <?php echo ($vo["title"]); ?>
                                
                            </td>
						<!--	<td>
							<?php if($vo['attr']): if(is_array($vo["attr"])): $i = 0; $__LIST__ = $vo["attr"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vod): $mod = ($i % 2 );++$i;?><span style="color:red;"><?php echo ($vod["name"]); ?> : </span><span style="margin-left:5px;"><?php echo ($vod["value"]); ?> &nbsp;</span><?php endforeach; endif; else: echo "" ;endif; ?>
                            <?php else: ?>
                            亲，此商品无额外属性<?php endif; ?>
							</td>-->
                            <td class="red rtd">¥<?php echo ($vo["price"]); ?></td>
                            <td><?php echo ($vo["quantity"]); ?></td>
                            <td class="red rtd">¥<?php echo $vo['price']*$vo['quantity']; ?></td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>   
                    </tbody>
                </table>      
                
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbdetail">
                    <tbody>
                        <tr>
                            <td class="rtd">总额：<span class="red" id="labProdAllTotal">¥<?php echo ($info["goods_sumPrice"]); ?></span>  </td>
                        </tr>
                        
                    </tbody>
                </table>          
		</div>
      
		
        </div>
      <!--  <a data-height="130" data-width="400" data-id="add" data-title="添加收货地址" data-uri="/index.php?g=admin&amp;m=address&amp;a=add" href="javascript:void(0);" class="add fb J_showdialog"><em>添加收货地址</em></a>-->
      
		<div class="mt10" style="text-align:center;">
		<a href="<?php echo U('tuan_order/index');?>">  <input type="button"  value=" 返回列表 " class="btn btn_cannel"></a></div>
	</div>
</div>
<input type="hidden" name="menuid"  value="<?php echo ($menuid); ?>"/>
<input type="hidden" name="id" value="<?php echo ($info["id"]); ?>" />
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
<script>
    $('#dosubmit').click(function(){
        var id = "<?php echo ($info["id"]); ?>";
        $.post("<?php echo U('tuan_order/peisong');?>",{id:id},function(data){
            if(data == 1){
                alert('配送成功！');
                window.location.replace("<?php echo U('tuan_order/index');?>");
            } else {
                alert('配送失败！');
            }
        },'html')
    })
</script>
</body>
</html>
<script src="__STATIC__/js/jquery/plugins/colorpicker.js"></script>
<script src="__STATIC__/js/kindeditor/kindeditor.js"></script>