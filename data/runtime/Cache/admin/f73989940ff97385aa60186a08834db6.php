<?php if (!defined('THINK_PATH')) exit();?><!--添加栏目-->
<div class="dialog_content">
    <form id="info_form" action="<?php echo U('item_cate/edit');?>" method="post">
        <table width="100%" class="table_form" style="margin-top:30px;">
            <tr>
                <th width="80px"><?php echo L('item_cate_name');?> :</th>
                <td>
                    <input type="text" name="name" id="J_name" value="<?php echo ($info["name"]); ?>" class="input-text" size="30">

                </td>
            </tr>

            <tr>
                <th>图标:</th>
                <td>
                    <?php if(!empty($info["img"])): ?><span class="attachment_icon J_attachment_icon" file-type="image" file-rel="<?php echo attach(get_thumb($info['img'], '_m'),'item_cate');?>">
                        <img src="<?php echo attach(get_thumb($info['img'], '_m'),'item_cate');?>" style="width:50px; height:50px;" /></span><?php endif; ?><br /><br/>
                    <input type="file" name="img" />

                </td>
            </tr>
            <tr>
                <th width="120">标签:</th>
                <td>
                    <select name="type">
                        <?php if(is_array($lable)): $i = 0; $__LIST__ = $lable;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if($info['type'] == $key): ?>selected="selected"<?php endif; ?>><?php echo ($val); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </td>
            </tr>
            <!--<tr>
                <th>普通会员折扣:</th>
                <td>
                    <input type="text" name="discount" value="<?php echo ($info["discount"]); ?>" class="input-text" size="30">
                    <span style="color:#777777;">&nbsp;&nbsp;(折扣数为0~1)</span>
                </td>
            </tr>
            <tr>
                <th>一星会员折扣:</th>
                <td>
                    <input type="text" name="discount1" value="<?php echo ($info["discount1"]); ?>"  class="input-text" size="30">
                    <span style="color:#777777;">&nbsp;&nbsp;(折扣数为0~1)</span>
                </td>
            </tr>
            <tr>
                <th>二星会员折扣:</th>
                <td>
                    <input type="text" name="discount2" value="<?php echo ($info["discount2"]); ?>"  class="input-text" size="30">
                    <span style="color:#777777;">&nbsp;&nbsp;(折扣数为0~1)</span>
                </td>
            </tr>
            <tr>
                <th>三星会员折扣:</th>
                <td>
                    <input type="text" name="discount3" value="<?php echo ($info["discount3"]); ?>"  class="input-text" size="30">
                    <span style="color:#777777;">&nbsp;&nbsp;(折扣数为0~1)</span>
                </td>
            </tr>
            <tr>
                <th>四星会员折扣:</th>
                <td>
                    <input type="text" name="discount4" value="<?php echo ($info["discount4"]); ?>"  class="input-text" size="30">
                    <span style="color:#777777;">&nbsp;&nbsp;(折扣数为0~1)</span>
                </td>
            </tr>
            <tr>
                <th>五星会员折扣:</th>
                <td>
                    <input type="text" name="discount5" value="<?php echo ($info["discount5"]); ?>"  class="input-text" size="30">
                    <span style="color:#777777;">&nbsp;&nbsp;(折扣数为0~1)</span>
                </td>
            </tr>
              <tr>
                <th>高级客户A折扣:</th>
                <td>
                    <input type="text" name="discount6" value="<?php echo ($info["discount6"]); ?>"  class="input-text" size="30">
                    <span style="color:#777777;">&nbsp;&nbsp;(折扣数为0~1)</span>
                </td>
            </tr>
            <tr>
                <th>高级客户B折扣:</th>
                <td>
                    <input type="text" name="discount7" value="<?php echo ($info["discount7"]); ?>"  class="input-text" size="30">
                    <span style="color:#777777;">&nbsp;&nbsp;(折扣数为0~1)</span>
                </td>
            </tr>
            <tr>
                <th>高级客户C折扣:</th>
                <td>
                    <input type="text" name="discount8" value="<?php echo ($info["discount8"]); ?>"  class="input-text" size="30">
                    <span style="color:#777777;">&nbsp;&nbsp;(折扣数为0~1)</span>
                </td>
            </tr>-->
            <input type="hidden" name="id" value="<?php echo ($info["id"]); ?>">
        </table>
    </form>
</div>

<script>
    $(function(){
        $.formValidator.initConfig({formid:"info_form",autotip:true});
        $('#J_name').formValidator({onshow:lang.please_input+lang.item_cate_name,onfocus:lang.please_input+lang.item_cate_name}).inputValidator({min:1,onerror:lang.please_input+lang.item_cate_name});

        $('#info_form').ajaxForm({success:complate, dataType:'json'});
        function complate(result){
            if(result.status == 1){
                $.dialog.get(result.dialog).close();
                $.pinphp.tip({content:result.msg});
                window.location.reload();
            } else {
                $.pinphp.tip({content:result.msg, icon:'alert'});
            }
        }
    });
</script>