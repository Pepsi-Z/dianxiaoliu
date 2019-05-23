<?php if (!defined('THINK_PATH')) exit();?><!--编辑会员-->
<div class="dialog_content">
    <form id="info_form" action="<?php echo u('return_money/edit');?>" method="post">
        <table width="100%" class="table_form">
            <tr>
                <th>名称:</th>
                <td><input type="text"  name="name" id="name" class="input-text" value="<?php echo ($info["name"]); ?>" size="30" />
                </td>
            </tr>

            <tr>
                <th>规则:</th>
                <td>

                    <input  onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"  type="text" name="score" class="input-text" size="5" value="<?php echo ($info["score"]); ?>" >积分
                    =
                    <input  onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"  type="text" name="price" class="input-text" size="5" value="<?php echo ($info["price"]); ?>" >元

                </td>
            </tr>
        </table>
        <input type="hidden" name="id" value="<?php echo ($info["id"]); ?>" />
    </form>
</div>
<script src="__STATIC__/js/fileuploader.js"></script>