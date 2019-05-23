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
<!--全局设置-->
<div class="pad_lr_10">
	<form id="info_form" action="<?php echo u('setting/edit');?>" method="post" enctype="multipart/form-data">
	<table width="100%" class="table_form">

        <tr>
            <th>客服电话 :</th>
            <td>
                <input type="text" name="setting[site_tel]" class="input-text" size="40" value="<?php echo C('pin_site_tel');?>">
            </td>
        </tr>
<!--
        <tr>
            <th>购票服务费 :</th>
            <td>
                <input onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" type="text" name="setting[station_price]" class="input-text" size="40" value="<?php echo C('pin_station_price');?>">
            </td>
        </tr>

        <tr>
            <th>同城速递服务费:</th>
            <td>
                起步<input  type="text" name="setting[city_min]" class="input-text" size="5" value="<?php echo C('pin_city_min');?>">米
                /单次<input onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" type="text" name="setting[danci_price]" class="input-text" size="5" value="<?php echo C('pin_danci_price');?>">元

            </td>
        </tr>-->

     <!--   <tr>
            <th>同城速递往返费用:</th>
            <td>
                <input  type="text" name="setting[wangfan_price]" class="input-text" size="8" value="<?php echo C('pin_wangfan_price');?>">元


            </td>
        </tr>

        <tr>
            <th>同城速递超出每公里:</th>
            <td>
              <input onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" type="text" name="setting[mei_price]" class="input-text" size="5" value="<?php echo C('pin_mei_price');?>">元
            </td>
        </tr>-->

       <!-- <tr>
            <th>同城速递超出每公里:</th>
            <td>
                每超出<input  type="text" name="setting[min_min]" class="input-text" size="5" value="<?php echo C('pin_min_min');?>">米
                / <input onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" type="text" name="setting[mei_price]" class="input-text" size="5" value="<?php echo C('pin_mei_price');?>">元

            </td>
        </tr>


        <tr>
            <th>商家的配送费:</th>
            <td>
                每超出<input  type="text" name="setting[chaochu_min]" class="input-text" size="5" value="<?php echo C('pin_chaochu_min');?>">米
                /<input onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" type="text" name="setting[chaochu_price]" class="input-text" size="5" value="<?php echo C('pin_chaochu_price');?>">元

            </td>
        </tr>
-->

      <!--  <tr>
            <th>超出一个商家的配送费:</th>
            <td>
                <input type="text" size="5"  name="setting[merchant_price]" class="input-text" size="40" value="<?php echo C('pin_merchant_price');?>"> 元
            </td>
        </tr>-->



        <tr>
            <th>订单提醒开关 :</th>
            <td>
                <label><input type="radio" <?php if(C('pin_s_waring') == '1'): ?>checked="checked"<?php endif; ?> value="1" name="setting[s_waring]"> <?php echo L('open');?></label> &nbsp;&nbsp;
                <label><input type="radio" <?php if(C('pin_s_waring') == '0'): ?>checked="checked"<?php endif; ?> value="0" name="setting[s_waring]"> <?php echo L('close');?></label>
                <span class="gray ml10">开启之后在订单管理,没隔3秒会自动提醒新订单</span>
            </td>
        </tr>

         <tr>
              <th>自提折扣:</th>
              <td>
                  <input type="text" size="5"  name="setting[fenxiang]" class="input-text" size="40" value="<?php echo C('pin_fenxiang');?>">折
              </td>
         </tr>

        <tr>
            <th>自提优惠说明 :</th>
            <td><textarea name="setting[fenxiang_about]"  style="width:68%;height:100px;"><?php echo C('pin_fenxiang_about');?></textarea></td>
        </tr>
        <!--
                <tr>
                    <th width="150">消费充值奖励比例:</th>
                    <td>
                        <span style="color: red">1元=</span><input onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" type="text" style="width:50px" name="setting[reward]" id="reward" class="input-text" value="<?php echo C('pin_reward');?>" />&nbsp;&nbsp;积分
                    </td>
                </tr>

                <tr>
                    <th>分享奖励说明 :</th>
                    <td><textarea name="setting[fenxiang_about]" id="fenxiang_about" style="width:68%;height:400px;visibility:hidden;resize:none;"><?php echo C('pin_fenxiang_about');?></textarea></td>
                </tr>
                -->
        <tr>
            <th>电影收费标准简介 :</th>
            <td><textarea name="setting[city_about]" id="city_about" style="width:68%;height:400px;visibility:hidden;resize:none;"><?php echo C('pin_city_about');?></textarea></td>
        </tr>

        <tr>
            <th>同城速递收费标准:</th>
            <td><textarea name="setting[city_money]" id="city_money" style="width:68%;height:400px;visibility:hidden;resize:none;"><?php echo C('pin_city_money');?></textarea></td>
        </tr>

        <tr>
            <th>同城速递服务内容 :</th>
            <td><textarea name="setting[city_service]" id="city_service" style="width:68%;height:400px;visibility:hidden;resize:none;"><?php echo C('pin_city_service');?></textarea></td>
        </tr>

<!--
        <tr>
            <th width="150"><?php echo L('ipban_switch');?> :</th>
            <td>
                <label><input type="radio" <?php if(C('pin_ipban_switch') == '1'): ?>checked="checked"<?php endif; ?> value="1" name="setting[ipban_switch]"> <?php echo L('open');?></label> &nbsp;&nbsp;
                <label><input type="radio" <?php if(C('pin_ipban_switch') == '0'): ?>checked="checked"<?php endif; ?> value="0" name="setting[ipban_switch]"> <?php echo L('close');?></label>
                <span class="gray ml10">如果本站没有添加黑名单数据，建议关闭改功能提升性能</span>
            </td>
        </tr>
        <tr>
            <th>商品审核 :</th>
            <td>
                <label><input type="radio" <?php if(C('pin_item_check') == '1'): ?>checked="checked"<?php endif; ?> value="1" name="setting[item_check]"> <?php echo L('open');?></label> &nbsp;&nbsp;
                <label><input type="radio" <?php if(C('pin_item_check') == '0'): ?>checked="checked"<?php endif; ?> value="0" name="setting[item_check]"> <?php echo L('close');?></label>
                <span class="gray ml10">开启之后用户发布的商品默认是未审核状态</span>
            </td>
        </tr>
        <tr>
            <th><?php echo L('avatar_size');?> :</th>
            <td>
                <input type="text" name="setting[avatar_size]" class="input-text" size="40" value="<?php echo C('pin_avatar_size');?>">
                <span class="gray ml10">用户头像规格，用","隔开</span>
            </td>
        </tr>
        <tr>
            <th><?php echo L('item_bimg');?> :</th>
            <td>
                <input type="text" name="setting[item_bimg][width]" class="input-text" size="5" value="<?php echo C('pin_item_bimg.width');?>"> × 
                <input type="text" name="setting[item_bimg][height]" class="input-text" size="5" value="<?php echo C('pin_item_bimg.height');?>">
            </td>
        </tr>
        <tr>
            <th><?php echo L('item_img');?> :</th>
            <td>
                <input type="text" name="setting[item_img][width]" class="input-text" size="5" value="<?php echo C('pin_item_img.width');?>"> × 
                <input type="text" name="setting[item_img][height]" class="input-text" size="5" value="<?php echo C('pin_item_img.height');?>">
            </td>
        </tr>
        <tr>
            <th><?php echo L('item_simg');?> :</th>
            <td>
                <input type="text" name="setting[item_simg][width]" class="input-text" size="5" value="<?php echo C('pin_item_simg.width');?>"> × 
                <input type="text" name="setting[item_simg][height]" class="input-text" size="5" value="<?php echo C('pin_item_simg.height');?>">
            </td>
        </tr>
        <tr>
            <th><?php echo L('itemcate_img');?> :</th>
            <td>
                <input type="text" name="setting[itemcate_img][width]" class="input-text" size="5" value="<?php echo C('pin_itemcate_img.width');?>"> × 
                <input type="text" name="setting[itemcate_img][height]" class="input-text" size="5" value="<?php echo C('pin_itemcate_img.height');?>">
            </td>
        </tr>
        <tr>
            <th><?php echo L('score_item_simg');?> :</th>
            <td>
                <input type="text" name="setting[score_item_img][swidth]" class="input-text" size="5" value="<?php echo C('pin_score_item_img.swidth');?>"> × 
                <input type="text" name="setting[score_item_img][sheight]" class="input-text" size="5" value="<?php echo C('pin_score_item_img.sheight');?>">
            </td>
        </tr>
        <tr>
            <th><?php echo L('score_item_bimg');?> :</th>
            <td>
                <input type="text" name="setting[score_item_img][bwidth]" class="input-text" size="5" value="<?php echo C('pin_score_item_img.bwidth');?>"> × 
                <input type="text" name="setting[score_item_img][bheight]" class="input-text" size="5" value="<?php echo C('pin_score_item_img.bheight');?>">
            </td>
        </tr>
        <tr>
            <th><?php echo L('statics_url');?> :</th>
            <td>
                <input type="text" name="setting[statics_url]" class="input-text" size="40" value="<?php echo C('pin_statics_url');?>">
                <span class="gray ml10">默认为空，外部服务器请填写如：http://s.pinphp.com</span>
            </td>
        </tr>-->
        <tr>
        	<th></th>
        	<td><input type="hidden" name="menuid"  value="<?php echo ($menuid); ?>"/><input type="submit" class="btn btn_submit" name="do" value="<?php echo L('submit');?>"/></td>
    	</tr>
	</table>
	</form>
</div>
<script src="__STATIC__/js/jquery/plugins/colorpicker.js"></script>
<script src="__STATIC__/js/kindeditor/kindeditor.js"></script>
<script>
    $(function() {
        KindEditor.create('#city_about', {
            uploadJson: '<?php echo U("attachment/editer_upload");?>',
            fileManagerJson: '<?php echo U("attachment/editer_manager");?>',
            allowFileManager: true
        });
        KindEditor.create('#fenxiang_about', {
            uploadJson: '<?php echo U("attachment/editer_upload");?>',
            fileManagerJson: '<?php echo U("attachment/editer_manager");?>',
            allowFileManager: true
        });

        KindEditor.create('#city_service', {
            uploadJson: '<?php echo U("attachment/editer_upload");?>',
            fileManagerJson: '<?php echo U("attachment/editer_manager");?>',
            allowFileManager: true
        });

        KindEditor.create('#city_money', {
            uploadJson: '<?php echo U("attachment/editer_upload");?>',
            fileManagerJson: '<?php echo U("attachment/editer_manager");?>',
            allowFileManager: true
        });
    })
</script>
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