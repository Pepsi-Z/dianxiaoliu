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
<!--编辑商品-->
<form id="info_form" action="<?php echo u('item/edit');?>" method="post" enctype="multipart/form-data">
<div class="pad_lr_10">
	<div class="col_tab">
		<ul class="J_tabs tab_but cu_li">
			<li class="current">基本信息</li>
            <li>展示图片</li>
			<!--<li>SEO设置</li>-->
            <li>商品规格</li>
		</ul>
		<div class="J_panes">
        <div class="content_list pad_10">
		<table width="100%" cellpadding="2" cellspacing="1" class="table_form">
            <tr>
                <th width="120">所属分类 :</th>
                <td>
                    <?php if($pid_info != '' ): ?><select  disabled="false" id="pid" name="pid">
                            <option value="<?php echo ($pid_info["id"]); ?>"><?php echo ($pid_info["name"]); ?></option>
                        </select><?php endif; ?>

                    <?php if($sid_info != '' ): ?><select  disabled="false" id="sid" name="sid">
                            <option value="<?php echo ($sid_info["id"]); ?>"><?php echo ($sid_info["title"]); ?></option>
                        </select><?php endif; ?>

                </td>
            </tr>


            <tr>
                <th>商品名称 :</th>
                <td><input type="text" value="<?php echo ($info["title"]); ?>" name="title" id="J_title" class="input-text" size="60"></td>
            </tr>

            <tr>
                <th>商品图片 :</th>
                <td>
                    <?php if(!empty($info['img'])): ?><img src="<?php echo attach(get_thumb($info['img'], '_m'), 'item');?>" width="100" height="100"/><br /><?php endif; ?>
                    <input type="file" name="img" />
                </td>
            </tr>

            <tr>
                <th>配送价 :</th>
                <td><input id='J_price' onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')" onafterpaste="this.value=this.value.replace(/[^0-9.]/g,'')" type="text" name="price" value="<?php echo ($info["price"]); ?>" class="input-text" size="10"> 元</td>
            </tr>
            <tr>
                <th>自提价 :</th>
                <td><input id='J_xs_price' onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')" onafterpaste="this.value=this.value.replace(/[^0-9.]/g,'')" type="text" name="tc_price" value="<?php echo ($info["tc_price"]); ?>" class="input-text" size="10"> 元</td>
            </tr>

          <!--  <tr>
                <th>商品库存 :</th>
                <td><input id='J_goods_stock' onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" onafterpaste="this.value=this.value.replace(/[^0-9]/g,'')" type="text" name="goods_stock" value="<?php echo ($info["goods_stock"]); ?>" class="input-text" size="10"> </td>
            </tr>-->

            <tr>
                <th>类型:</th>
                <td>
                    <input id="tuijian" name="tuijian" type="checkbox"  value="1" <?php if($info["tuijian"] == 1): ?>checked='checked'<?php endif; ?>  />&nbsp;小六推荐&nbsp;
                    <input id="love" name="love" type="checkbox"    value="1" <?php if($info["love"] == 1): ?>checked='checked'<?php endif; ?> />&nbsp;猜你喜欢
                </td>
            </tr>

            <tr>
                <th>是否上架:</th>
                <td><input type="radio" name="status" value="1" checked>是&nbsp; <input type="radio" name="status" value="0" >否</td>
            </tr>


            <tr>
                <th>套餐描述 :</th>
                <td><textarea name="item_desc" id="item_desc"style="width:68%;height:50px;"><?php echo ($info["item_desc"]); ?></textarea></td>
            </tr>


            <tr>
                <th>商品详情 :</th>
                <td><textarea name="info" id="info" style="width:68%;height:400px;visibility:hidden;resize:none;"><?php echo ($info["info"]); ?></textarea></td>
            </tr>

		    
		      <script>
		     $(function(){
        	 $('#free').change(function(){
        	  if($(this).val()==2)
        	  {
        	  	$('#address_form').show();
        	  }else
        	  {
        	  		$('#address_form').hide();
        	  }
        	 });
        	 set_address();
        })
        
          function set_address()
          {
          var addr_id =$("#free").find("option:selected").val();
          	 //var addr_id = $("#free:selected").val();
          
           if(addr_id == 2)
            {
               
                $('#address_form').show();
            }
            else
            {
                $('#address_form').hide();
     
            }
          }
		    </script>
		</table>
		</div>
        <div class="content_list pad_10 hidden">
        	<style>
				.addpic {}
				.addpic li { float:left; text-align:center; margin:0 0 10px 20px;}
				.addpic a { display:block;}
            </style>
            <ul class="addpic">
            <?php if(is_array($img_list)): $i = 0; $__LIST__ = $img_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><li class="album_<?php echo ($val['id']); ?>">
            <a href="javascript:void(0)" onclick="del_album(<?php echo ($val['id']); ?>);"><img src="__STATIC__/css/admin/bgimg/tv-collapsable.gif" /></a>
            <a><img src="<?php echo attach(get_thumb($val['url'], '_b'), 'item');?>" style="width:80px;height:60px; border:solid 1px #000; "/></a>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <div class="cb"></div>
            <table width="100%" cellpadding="2" cellspacing="1" class="table_form" id="first_upload_file">
                <tbody class="uplode_file">
                <tr>
                    <th width="100" align="left"><a href="javascript:void(0);" class="blue" onclick="add_file();"><img src="__STATIC__/css/admin/bgimg/tv-expandable.gif" /></a>上传文件 :</th>
                    <td><input type="file" name="imgs[]"></td>
                </tr>
                </tbody>
            </table>
        </div>
            <div class="content_list pad_10 hidden">
                <table width="100%" cellpadding="2" cellspacing="1" class="table_form" id="item_attr">
                    <?php if(is_array($attr_list)): $i = 0; $__LIST__ = $attr_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
                            <td width="220">
                                <a href="javascript:void(0);" class="blue" onclick="del_attr(<?php echo ($val["id"]); ?>,this);"><img src="__STATIC__/css/admin/bgimg/tv-collapsable.gif" /></a>
                                商品规格 :
                                <input type="text" name="attr[name][]" value="<?php echo ($val["attr_name"]); ?>" class="input-text" size="20">
                            </td>
                            <td width="">
                                商品价钱 :
                                <input type="text" name="attr[value][]" value="<?php echo ($val["attr_value"]); ?>" class="input-text" size="30">
                            </td>

                        </tr>
                        <input type="hidden" name="attr[id][]" value="<?php echo ($val["id"]); ?>" class="input-text" size="30">
                        <input type="hidden" name="attr[bs][]" value="1" class="input-text" size="30"><?php endforeach; endif; else: echo "" ;endif; ?>


                    <tbody class="add_item_attr">
                    <tr>
                        <th  width="220">
                            <a  href="javascript:void(0);" class="blue" onclick="add_attr();">
                                <img src="__STATIC__/css/admin/bgimg/tv-expandable.gif" />
                            </a>
                            商品规格 :<input type="text" name="attr[name][]" class="input-text" size="20">
                        </th>
                        <td>
                            商品价钱 :<input type="text" name="attr[value][]" class="input-text" size="30">
                        </td>
                        <input type="hidden" name="attr[bs][]" value="0" class="input-text" size="30">
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
		<div class="mt10"><input type="submit" value="<?php echo L('submit');?>" id="dosubmit" name="dosubmit" class="btn btn_submit" style="margin:0 0 10px 100px;"></div>
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
<link rel="stylesheet" href="__STATIC__/js/calendar/calendar-blue.css"/>
<script src="__STATIC__/js/calendar/calendar.js"></script>
<script>
    $(function(){
         $('#tuijian').click(function(){
             var attr = $(this).attr('checked');
             if(attr == 'checked'){
                 $(this).val('1')
             }else{
                 $(this).val('0')
             }

         })
        $('#love').click(function(){
            var attr = $(this).attr('checked');
            if(attr == 'checked'){
                $(this).val('1')
            }else{
                $(this).val('0')
            }

        })
    });
</script>
<script>
</script>
<script type="text/javascript">
$('.J_cate_select').cate_select('请选择');
$(function() {	
	$('ul.J_tabs').tabs('div.J_panes > div');
	//自动获取标签
	$('#J_gettags').live('click', function() {
		var title = $.trim($('#J_title').val());
		if(title == ''){
			$.pinphp.tip({content:lang.article_title_isempty, icon:'alert'});
			return false;
		}
		$.getJSON('<?php echo U("item/ajax_gettags");?>', {title:title}, function(result){
			if(result.status == 1){
				$('#J_tags').val(result.data);
			}else{
				$.pinphp.tip({content:result.msg});
			}
		});
	});
	$.formValidator.initConfig({formid:"info_form",autotip:true});
	$("#J_title").formValidator({onshow:'请填写商品名称',onfocus:'请填写商品名称'}).inputValidator({min:1,onerror:'请填写商品名称'}).defaultPassed();
	$("#J_scprice").formValidator({onshow:'请填写商品市场价格',onfocus:'请填写商品市场价格'}).inputValidator({min:1,onerror:'请填写商品市场价格'});
	$("#J_price").formValidator({onshow:'请填写商品价格',onfocus:'请填写商品价格'}).inputValidator({min:1,onerror:'请填写商品价格'}).defaultPassed();
    $("#J_goods_stock").formValidator({onshow:'请填写商品库存',onfocus:'请填写商品库存'}).inputValidator({min:1,onerror:'请填写商品库存'});
	$("#J_goods_stock").formValidator({onshow:'请填写商品库存',onfocus:'请填写商品库存'}).inputValidator({min:1,onerror:'请填写商品库存'}).defaultPassed();
});
function get_child_cates(obj,to_id)
{
	var parent_id = $(obj).val();
	if( parent_id ){
		$.get('?m=item&a=get_child_cates&g=admin&parent_id='+parent_id,function(data){
				var obj = eval("("+data+")");
				$('#'+to_id).html( obj.content );
	    });
    }
}

function add_file()
{
    $("#next_upload_file .uplode_file").clone().insertAfter($("#first_upload_file .uplode_file:last"));
}
function del_file_box(obj)
{
	$(obj).parent().parent().remove();
}
function del_album(id)
{
	var url = "<?php echo U('item/delete_album');?>";
    $.get(url+"&album_id="+id, function(data){
		if(data==1){
		    $('.album_'+id).remove();
		};
    });
}
function add_attr()
{
    $("#hidden_attr .add_item_attr").clone().insertAfter($("#item_attr .add_item_attr:last"));
}
function del_attrs(obj)
{
	$(obj).parent().parent().remove();
}
function del_attr(id,obj)
{
	var url = "<?php echo U('item/delete_attr');?>";
    $.get(url+"&attr_id="+id, function(data){
		if(data==1){
		    $(obj).parent().parent().remove();
		};
    });
}
</script>
<table id="next_upload_file" style="display:none;">
<tbody class="uplode_file">
   <tr>
      <th width="100"><a href="javascript:void(0);" onclick="del_file_box(this);" class="blue"><img src="__STATIC__/css/admin/bgimg/tv-collapsable.gif" /></a>上传文件 :</th>
      <td><input type="file" name="imgs[]"></td>
   </tr>
</tbody>
</table>

<table id="hidden_attr" style="display:none;">
    <tbody class="add_item_attr">
    <tr>
        <th width="220">
            <a href="javascript:void(0);" class="blue" onclick="del_attrs(this);">
                <img src="__STATIC__/css/admin/bgimg/tv-collapsable.gif" />
            </a>
            商品规格 :<input type="text" name="attr[name][]" class="input-text" size="20">
        </th>
        <td>商品价钱 :<input type="text" name="attr[value][]" class="input-text" size="30"></td>

        <input type="hidden" name="attr[bs][]" value="0" class="input-text" size="30">
    </tr>
    </tbody>
</table>

</body>
</html>
<script src="__STATIC__/js/jquery/plugins/colorpicker.js"></script>
<script src="__STATIC__/js/kindeditor/kindeditor.js"></script>
<script>

$(function() {
	KindEditor.create('#info', {
		uploadJson : '<?php echo U("attachment/editer_upload");?>',
		fileManagerJson : '<?php echo U("attachment/editer_manager");?>',
		allowFileManager : true
	});
	$('ul.J_tabs').tabs('div.J_panes > div');

	//颜色选择器
	$('.J_color_picker').colorpicker();

	//自动获取标签
	$('#J_gettags').live('click', function() {
		var title = $.trim($('#J_title').val());
		if(title == ''){
			$.pinphp.tip({content:lang.article_title_isempty, icon:'alert'});
			return false;
		}
		$.getJSON('<?php echo U("article/ajax_gettags");?>', {title:title}, function(result){
			if(result.status == 1){
				$('#J_tags').val(result.data);
			}else{
				$.pinphp.tip({content:result.msg});
			}
		});
	});
	
});
</script>