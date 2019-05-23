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
<form id="info_form" action="<?php echo u('merchant/edit');?>" method="post" enctype="multipart/form-data">
    <div class="pad_lr_10">
        <div class="col_tab">
            <ul class="J_tabs tab_but cu_li">
                <li class="current">基本信息</li>
                <!--<li>SEO设置</li>-->
               <!-- <li>附加属性</li>-->
            </ul>
            <div class="J_panes">
                <div class="content_list pad_10">
                    <table width="100%" cellpadding="2" cellspacing="1" class="table_form">
                        <tr>
                            <th width="120">所属分类 :</th>
                            <td>
                                <select  disabled="false" id="pid" name="pid">
                                    <?php if(is_array($pid_list)): $i = 0; $__LIST__ = $pid_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                                <?php if($tid_list != ''): ?>&nbsp;&nbsp;
                                    <select disabled="false" name="tid" id="cid" readonly="readonly">
                                        <?php if(is_array($tid_list)): $i = 0; $__LIST__ = $tid_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>"><?php echo ($val["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select><?php endif; ?>
                                <?php if($ttid_list != ''): ?>&nbsp;&nbsp;
                                    <select disabled="false" name="ttid" id="ttid" readonly="readonly">
                                        <?php if(is_array($ttid_list)): $i = 0; $__LIST__ = $ttid_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>"><?php echo ($val["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select><?php endif; ?>
                            </td>
                        </tr>


                        <tr>
                            <th width="120">返积分规则 :</th>
                            <td>
                                <select id="rid" name="rid">
                                    <option value="">--返积分规则--</option>
                                    <?php if(is_array($rule)): $i = 0; $__LIST__ = $rule;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($vo['id'] == $info['rid']): ?>selected="selected"<?php endif; ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </td>
                        </tr>


                        <tr>
                            <th width="120">返利规则 :</th>
                            <td>
                                <select id="rm_id" name="rm_id">
                                    <option value="">--请选择返利规则--</option>
                                    <?php if(is_array($return_money)): $i = 0; $__LIST__ = $return_money;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($vo['id'] == $info['rm_id']): ?>selected="selected"<?php endif; ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </td>
                        </tr>


                        <tr>
                            <th>商家名称 :</th>
                            <td><input type="text" name="title" value="<?php echo ($info["title"]); ?>" id="J_title" class="input-text" size="30"></td>
                        </tr>

                        <tr>
                            <th>电话 :</th>
                            <td><input id='tel' type="text" value="<?php echo ($info["tel"]); ?>"  name="tel" class="input-text" size="30"></td>
                        </tr>


                        <tr>
                            <th>商家图片 :</th>
                            <td>
                                <?php if(!empty($info["img"])): ?><span class="attachment_icon J_attachment_icon" file-type="image" file-rel="<?php echo attach(get_thumb($info['img'], '_m'),'merchant');?>">
                                <img src="<?php echo attach(get_thumb($info['img'], '_m'),'merchant');?>" style="width:50px; height:50px;" /></span><?php endif; ?><br /><br/>
                                <input type="file" name="img" />

                            </td>
                        </tr>
                        <tr>
                            <th width="80">省市区:</th>
                            <td>
                                <select name="province" id="province" onchange="loadRegion('province',2,'city','<?php echo U('merchant/getRegion');?>');">
                                    <option value="<?php echo ($name[0]['id']); ?>" selected><?php if($name[0]['name'] != ''): echo ($name[0]['name']); else: ?>省份/直辖市<?php endif; ?></option>
                                    <?php if(is_array($province)): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" ><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>&nbsp;
                                <select name="city" id="city"  onchange="loadRegion('city',3,'town','<?php echo U('merchant/getRegion');?>');">
                                    <option value="<?php echo ($name[1]['id']); ?>" ><?php if($name[1]['name'] != ''): echo ($name[1]['name']); else: ?>市/县<?php endif; ?></option>
                                </select>&nbsp;
                                <select name="town" id="town">
                                    <option value="<?php echo ($name[2]['id']); ?>" ><?php if($name[2]['name'] != ''): echo ($name[2]['name']); else: ?>镇/区<?php endif; ?></option>
                                </select>
                            </td>
                        </tr>


                        <tr>
                            <th>详细地址 :</th>
                            <td><textarea name="address" id="address" style="width:68%;height:50px;"><?php echo ($info["address"]); ?></textarea></td>
                        </tr>


                        <tr>
                            <th>地理位置：</th>
                            <td>
                                <input type="text" readonly="readonly" name="xjing" id="store_jd" onclick="look('<?php echo u('merchant/map_look',array('xjing'=>$info['xjing'],'xwei'=>$info['xwei']));?>');"  class="input-text" size="10" value="<?php echo ($info["xjing"]); ?>"/>-
                                <input type="text"  readonly="readonly" name="xwei" id="store_wd" onclick="look('<?php echo u('merchant/map_look',array('xjing'=>$info['xjing'],'xwei'=>$info['xwei']));?>');" class="input-text" size="10" value="<?php echo ($info["xwei"]); ?>"/>  <label></label> </td>
                        </tr>




                        <tr>
                            <th>类型:</th>
                            <td>
                                <input id="zhe" type="checkbox" name="zhe" value="1" <?php if($info['zhe'] == 1): ?>checked="checked"<?php endif; ?> />&nbsp;折扣&nbsp;
                                <input id="tao" type="checkbox" name="tao" value="1" <?php if($info['tao'] == 1): ?>checked="checked"<?php endif; ?> />&nbsp;套餐
                                <input id="chang" type="checkbox" name="chang" value="1" <?php if($info['chang'] == 1): ?>checked="checked"<?php endif; ?> />&nbsp;常规
                            </td>
                        </tr>

                        <tr id="zhe1" style="display: none;">
                            <th>折扣 :</th>
                            <td><input id='discount'  value="<?php echo ($info["discount"]); ?>" type="text" name="discount" class="input-text" size="10"> 折</td>
                        </tr>
                        <tr id="zhe2" style="display: none;">
                            <th>已享人数 :</th>
                            <td><input id='enjoy_num' value="<?php echo ($info["enjoy_num"]); ?>" type="text" name="enjoy_num" class="input-text" size="10"> 人</td>
                        </tr>

                        <tr id="zhe3" style="display: none;">
                            <th>折扣标题 :</th>
                            <td><input type="text" value="<?php echo ($info["zhe_title"]); ?>" name="zhe_title" id="zhe_title" class="input-text" size="30"></td>
                        </tr>

                        <tr id="zhe4" style="display: none;">
                            <th>折扣描述 :</th>
                            <td><textarea name="desc" id="desc" style="width:68%;height:50px;"><?php echo ($info["desc"]); ?></textarea></td>
                        </tr>


                        <tr id="tao1" style="display: none;">
                            <th>套餐标题 :</th>
                            <td><input type="text" value="<?php echo ($info["tao_title"]); ?>" name="tao_title" id="tao_title" class="input-text" size="30"></td>
                        </tr>


                        <tr>
                            <th>状态: </th>
                            <td>
                                <label><input type="radio" name="status" class="radio_style" value="1" <?php if($info['status'] == 1): ?>checked="checked"<?php endif; ?>>&nbsp;是&nbsp;&nbsp;&nbsp;</label>
                                <label><input type="radio" name="status" class="radio_style" value="0" <?php if($info['status'] == 0): ?>checked="checked"<?php endif; ?>>&nbsp;否</label>
                            </td>
                        </tr>

                        <!--<tr>
                            <th>商家级别 :</th>
                            <td><input id='J_leve' onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')" onafterpaste="this.value=this.value.replace(/[^0-9.]/g,'')" type="text" name="level" value="<?php echo ($info["level"]); ?>" class="input-text" size="30"> 星 <span>（最多为5星）</span></td>
                        </tr>-->

                        <tr>
                            <th>商家描述 :</th>
                            <td><textarea name="merchant_desc" id="merchant_desc" style="width:68%;height:50px;"><?php echo ($info["merchant_desc"]); ?></textarea></td>
                        </tr>

                        <tr>
                            <th>商家详情 :</th>
                            <td><textarea name="info" id="info" style="width:68%;height:400px;visibility:hidden;resize:none;"><?php echo ($info["info"]); ?></textarea></td>
                        </tr>
                    </table>
                </div>

                <div class="content_list pad_10 hidden">
                    <table width="100%" cellpadding="2" cellspacing="1" class="table_form" id="item_attr">
                        <?php if(is_array($attr_list)): $i = 0; $__LIST__ = $attr_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
                                <td width="220">
                                    <a href="javascript:void(0);" class="blue" onclick="del_attr(<?php echo ($val["id"]); ?>,this);"><img src="__STATIC__/css/admin/bgimg/tv-collapsable.gif" /></a>
                                    属性名 :
                                    <input type="text" name="attr[name][]" value="<?php echo ($val["name"]); ?>" class="input-text" size="20">
                                </td>
                                <td width="">
                                    属性值 :
                                    <input type="text" name="attr[value][]" value="<?php echo ($val["value"]); ?>" class="input-text" size="30">
                                    字段单位
                                    <select name="attr[tag][]" >
                                        <?php if(is_array($teg)): $i = 0; $__LIST__ = $teg;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vals): $mod = ($i % 2 );++$i;?><option size="30" value="<?php echo ($key); ?>" <?php if($key == $val['tag']): ?>selected<?php endif; ?> ><?php echo ($vals); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>

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
                                属性名 :<input type="text" name="attr[name][]" class="input-text" size="20">
                            </th>
                            <td>
                                属性值 :<input type="text" name="attr[value][]" class="input-text" size="30">
                                字段单位 :
                                <select name="attr[tag][]" >
                                    <?php if(is_array($teg)): $i = 0; $__LIST__ = $teg;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option size="30" value="<?php echo ($key); ?>"><?php echo ($val); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
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
        //初始折
        if($('#zhe').attr('checked') == 'checked'){
            $('#zhe1').attr('style','');
            $('#zhe2').attr('style','');
            $('#zhe3').attr('style','');
            $('#zhe4').attr('style','');

        }else{
            $('#zhe1').attr('style','display:none');
            $('#zhe2').attr('style','display:none');
            $('#zhe3').attr('style','display:none');
            $('#zhe4').attr('style','display:none');

        }
        //初始套
        if($('#tao').attr('checked') == 'checked'){
            $('#tao1').attr('style','');
        }else{
            $('#tao1').attr('style','display:none');
        }



        $('#zhe').click(function(){
            var attr = $(this).attr('checked');  //是否勾选
            if(attr == 'checked'){
                $('#zhe1').attr('style','');
                $('#zhe2').attr('style','');
                $('#zhe3').attr('style','');
                $('#zhe4').attr('style','');

            }else{
                $('#zhe1').attr('style','display:none');
                $('#zhe2').attr('style','display:none');
                $('#zhe3').attr('style','display:none');
                $('#zhe4').attr('style','display:none');

            }

        })


        $('#tao').click(function(){
            var attr = $(this).attr('checked');  //是否勾选
            if(attr == 'checked') {
                $('#tao1').attr('style','');
            }else{
                $('#tao1').attr('style','display:none');
            }

        })


    })
</script>
<script>
    function look (url) {
        var time = new Date();
        window.open(url,window,"dialogHeight:600px;dialogWidth:1000px;help:no");
    }
    $(function(){
        $('#pid').change(function(){
            $('#cid').get(0).options.length=1;
            var pid = $(this).val();
            $.post("<?php echo u('item/ajax_get_brand');?>",{ pid:pid },function(data){
//                alert(data.status)
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
    function loadRegion(sel,type_id,selName,url){
        jQuery("#"+selName+" option").each(function(){
            jQuery(this).remove();
        });
        jQuery("<option value=0>请选择</option>").appendTo(jQuery("#"+selName));
        if(jQuery("#"+sel).val()==0){
            return;
        }
        jQuery.getJSON(url,{pid:jQuery("#"+sel).val(),type:type_id},
                function(data){
                    if(data){
                        jQuery.each(data,function(idx,item){
                            jQuery("<option value="+item.id+">"+item.name+"</option>").appendTo(jQuery("#"+selName));
                        });
                    }else{
                        jQuery("<option value='0'>请选择</option>").appendTo(jQuery("#"+selName));
                    }
                }
        );
    };
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

        $("#tel").formValidator({onshow:'请填写商家电话',onfocus:'请填写商家电话'}).inputValidator({min:1,onerror:'请填写商家电话'}).defaultPassed();
        $("#address").formValidator({onshow:'请填写商家地址',onfocus:'请填写商家地址'}).inputValidator({min:1,onerror:'请填写商家地址'}).defaultPassed();


        $("#store_wd").formValidator({onshow:'请填写商家地理位置',onfocus:'请填写商家地理位置'}).inputValidator({min:1,onerror:'请填写商家地理位置'}).defaultPassed();

        //$("#discount").formValidator({onshow:'请填写商家折扣',onfocus:'请填写商家折扣'}).inputValidator({min:1,onerror:'请填写商家折扣'}).defaultPassed();

        //$("#enjoy_num").formValidator({onshow:'请填写商家已享人数',onfocus:'请填写商家已享人数'}).inputValidator({min:1,onerror:'请填写商家已享人数'}).defaultPassed();

        //$("#desc").formValidator({onshow:'请填写商家折扣描述',onfocus:'请填写商家折扣描述'}).inputValidator({min:1,onerror:'请填写商家折扣描述'}).defaultPassed();

        $("#J_leve").formValidator({onshow:'请填写商家等级',onfocus:'请填写商家等级'}).inputValidator({min:1,onerror:'请填写商家等级'}).defaultPassed();
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
        var url = "<?php echo U('merchant/delete_attr');?>";
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
        <th width="200">
            <a href="javascript:void(0);" class="blue" onclick="del_attrs(this);">
                <img src="__STATIC__/css/admin/bgimg/tv-collapsable.gif" />
            </a>
            属性名 :<input type="text" name="attr[name][]" class="input-text" size="20">
        </th>
        <td>属性值 :<input type="text" name="attr[value][]" class="input-text" size="30">
            字段单位 :
            <select name="attr[tag][]" >
                <?php if(is_array($teg)): $i = 0; $__LIST__ = $teg;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option size="30" value="<?php echo ($key); ?>"><?php echo ($val); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </td>
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