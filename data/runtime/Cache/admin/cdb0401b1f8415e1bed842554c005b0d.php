<?php if (!defined('THINK_PATH')) exit();?><!--编辑会员-->
<div class="dialog_content">
	<form id="info_form" action="<?php echo u('user/edit');?>" method="post">
	<table width="100%" class="table_form">
		<tr>
			<th width="100">会员昵称 :</th>
			<td><input type="text" name="name"  class="input-text" value="<?php echo ($info["name"]); ?>" size="30"></td>
		</tr>
        <tr>
            <th>联系电话 :</th>
            <td><input type="text" name="tel"  class="input-text" value="<?php echo ($info["tel"]); ?>" size="30"></td>
        </tr>
        <tr>
            <th>积分 :</th>
            <td><input type="text" name="score"  class="input-text" value="<?php echo ($info["score"]); ?>" size="30"></td>
        </tr>
        <tr>
            <th>性别 :</th>
            <td>
                <label><input type="radio" name="gender" value="1" <?php if($info["gender"] == 1): ?>checked<?php endif; ?>> 男</label>&nbsp;&nbsp;
                <label><input type="radio" name="gender" value="0" <?php if($info["gender"] == 0): ?>checked<?php endif; ?>> 女</label>
            </td>
        </tr>
       <!-- <tr>
            <th>会员邮箱 :</th>
            <td><input type="text" name="email" id="email" class="input-text" value="<?php echo ($info["email"]); ?>" size="30"></td>
        </tr>
        <tr>
            <th>职业 :</th>
            <td><input type="text" name="occupation"  class="input-text" value="<?php echo ($info["occupation"]); ?>" size="30"></td>
        </tr>-->

        <tr>
            <th>会员余额 :</th>
            <td><input type="text" name="money"  class="input-text" value="<?php if($info['money']){echo $info['money']; }else{echo '0.00' ;}?>" size="30"></td>
        </tr>
        <tr>
			<th>新密码 :</th>
			<td><input type="password" name="password" class="input-text" size="30">
            <label class="gray">&nbsp;&nbsp;不修改则留空</label>
            </td>
		</tr>
        <tr>
			<th>重复密码 :</th>
			<td><input type="password" name="repassword" class="input-text" size="30"></td>
		</tr>
        
	    <tr>
			<th><?php echo L('enabled');?> :</th>
			<td>
				<label><input type="radio" name="status" value="1" <?php if($info["status"] == 1): ?>checked<?php endif; ?>> <?php echo L('yes');?></label>&nbsp;&nbsp;
				<label><input type="radio" name="status" value="0" <?php if($info["status"] == 0): ?>checked<?php endif; ?>> <?php echo L('no');?></label>
			</td>
		</tr>
	</table>
	<input type="hidden" name="id" value="<?php echo ($info["id"]); ?>" />
	</form>
</div>
<script src="__STATIC__/js/fileuploader.js"></script>